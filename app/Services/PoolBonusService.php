<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * PoolBonusService - Separate PB1-PB11+ Pool Tree & Qualification Engine
 */

namespace App\Services;

use App\Helpers\Database;
use App\Models\Advisor;
use App\Models\Genealogy;
use App\Services\CommissionRuleService;
use App\Services\CommissionApprovalService;

class PoolBonusService
{
    /**
     * Check and trigger Pool Qualification for an Advisor
     * Qualification Criteria: direct advisors >= 3 AND personal customers >= 3
     */
    public static function checkPoolQualification(int $advisorId): array
    {
        // 1. Check if already a pool member
        $existingPool = Database::fetchOne("SELECT * FROM pool_members WHERE advisor_id = ?", [$advisorId]);
        if ($existingPool) {
            return [
                'status' => true,
                'is_qualified' => true,
                'already_member' => true,
                'pool_member' => $existingPool
            ];
        }

        // 2. Fetch configured rule
        $rule = CommissionRuleService::getPoolBonusRule();
        $minAdvisors = (int)($rule['min_direct_advisors'] ?? 3);
        $minCusts = (int)($rule['min_personal_customers'] ?? 3);

        // 3. Count direct advisors
        $directAdvisors = Database::fetchOne(
            "SELECT COUNT(*) as total FROM advisors WHERE sponsor_id = ? AND status = 'ACTIVE'",
            [$advisorId]
        );
        $directAdvisorCount = (int)($directAdvisors['total'] ?? 0);

        // 4. Count personal customers
        $personalCusts = Database::fetchOne(
            "SELECT COUNT(DISTINCT id) as total FROM customers WHERE advisor_id = ?",
            [$advisorId]
        );
        $personalCustCount = (int)($personalCusts['total'] ?? 0);

        if ($directAdvisorCount >= $minAdvisors && $personalCustCount >= $minCusts) {
            // Qualified! Enroll into Pool Tree
            return self::enrollIntoPool($advisorId, $rule);
        }

        return [
            'status' => true,
            'is_qualified' => false,
            'direct_advisors' => $directAdvisorCount,
            'personal_customers' => $personalCustCount,
            'required_advisors' => $minAdvisors,
            'required_customers' => $minCusts
        ];
    }

    /**
     * Enroll Qualified Advisor into the Pool Tree with 3-Child Placement & PB Numbering
     */
    public static function enrollIntoPool(int $advisorId, ?array $rule = null): array
    {
        $rule = $rule ?: CommissionRuleService::getPoolBonusRule();
        $maxChildren = (int)($rule['max_children_per_node'] ?? 3);

        Database::beginTransaction();

        try {
            // Double check existing
            $existing = Database::fetchOne("SELECT * FROM pool_members WHERE advisor_id = ?", [$advisorId]);
            if ($existing) {
                Database::rollBack();
                return ['status' => true, 'is_qualified' => true, 'pool_member' => $existing];
            }

            // Determine Next PB Number
            $maxNumRow = Database::fetchOne("SELECT MAX(pool_number) as max_num FROM pool_members");
            $nextNum = ((int)($maxNumRow['max_num'] ?? 0)) + 1;
            $poolLabel = 'PB' . $nextNum;

            $parentPoolId = null;
            $poolLevel = 1;

            if ($nextNum > 1) {
                // Find next available parent with direct_pool_children_count < maxChildren (Breadth-first by pool_number)
                $parent = Database::fetchOne(
                    "SELECT * FROM pool_members 
                     WHERE direct_pool_children_count < ? 
                     ORDER BY pool_number ASC 
                     LIMIT 1",
                    [$maxChildren]
                );

                if ($parent) {
                    $parentPoolId = (int)$parent['id'];
                    $poolLevel = (int)$parent['pool_level'] + 1;

                    // Increment parent direct children count
                    Database::execute(
                        "UPDATE pool_members SET direct_pool_children_count = direct_pool_children_count + 1 WHERE id = ?",
                        [$parentPoolId]
                    );
                }
            }

            // Insert Pool Member
            Database::execute(
                "INSERT INTO pool_members (pool_number, pool_label, advisor_id, parent_pool_id, pool_level, qualified_at, direct_pool_children_count, status, created_at) 
                 VALUES (?, ?, ?, ?, ?, NOW(), 0, 'ACTIVE', NOW())",
                [$nextNum, $poolLabel, $advisorId, $parentPoolId, $poolLevel]
            );

            $poolMemberId = (int)Database::lastInsertId();

            // Insert Pool Genealogy (Closure Table)
            // 1. Self reference (depth 0)
            Database::execute(
                "INSERT INTO pool_genealogy (ancestor_pool_id, descendant_pool_id, depth, created_at) VALUES (?, ?, 0, NOW())",
                [$poolMemberId, $poolMemberId]
            );

            // 2. Uplines in Pool Tree
            if ($parentPoolId) {
                $maxPoolLevels = (int)($rule['max_pool_levels'] ?? 11);
                Database::execute(
                    "INSERT INTO pool_genealogy (ancestor_pool_id, descendant_pool_id, depth, created_at) 
                     SELECT pg.ancestor_pool_id, ?, pg.depth + 1, NOW() 
                     FROM pool_genealogy pg 
                     WHERE pg.descendant_pool_id = ? AND pg.depth < ?",
                    [$poolMemberId, $parentPoolId, $maxPoolLevels]
                );
            }

            // Calculate Pool Commission for Uplines
            $commResults = self::distributePoolCommissions($poolMemberId, $advisorId, $rule);

            CommissionRuleService::logAudit(
                null,
                'POOL_MEMBER_QUALIFIED',
                'POOL_MEMBER',
                $poolMemberId,
                null,
                json_encode(['advisor_id' => $advisorId, 'pool_label' => $poolLabel, 'parent_id' => $parentPoolId]),
                "Advisor qualified as Pool Member {$poolLabel}"
            );

            Database::commit();

            $newMember = Database::fetchOne("SELECT * FROM pool_members WHERE id = ?", [$poolMemberId]);
            return [
                'status' => true,
                'is_qualified' => true,
                'pool_member' => $newMember,
                'commissions_generated' => $commResults
            ];

        } catch (\Throwable $t) {
            Database::rollBack();
            return ['status' => false, 'message' => 'Pool enrollment error: ' . $t->getMessage()];
        }
    }

    /**
     * Distribute Pool Commissions to Pool Tree Uplines
     */
    private static function distributePoolCommissions(int $newPoolMemberId, int $sourceAdvisorId, array $rule): array
    {
        $l1Amount = (float)($rule['level_1_amount'] ?? 1000.00);
        $subsequentAmount = (float)($rule['subsequent_level_amount'] ?? 500.00);
        $maxLevels = (int)($rule['max_pool_levels'] ?? 11);

        $joiningSettings = CommissionRuleService::getJoiningSettings();
        $tdsRate = $joiningSettings['tds_percentage'];
        $adminRate = $joiningSettings['admin_deduction_percentage'];

        // Get Uplines in Pool Tree
        $uplines = Database::fetchAll(
            "SELECT pg.depth, pm.*, a.advisor_code, a.first_name, a.last_name 
             FROM pool_genealogy pg 
             JOIN pool_members pm ON pg.ancestor_pool_id = pm.id 
             JOIN advisors a ON pm.advisor_id = a.id 
             WHERE pg.descendant_pool_id = ? AND pg.depth > 0 AND pg.depth <= ? 
             ORDER BY pg.depth ASC",
            [$newPoolMemberId, $maxLevels]
        );

        $results = [];

        foreach ($uplines as $up) {
            $poolDepth = (int)$up['depth'];
            $gross = ($poolDepth === 1) ? $l1Amount : $subsequentAmount;
            if ($gross <= 0) continue;

            $tdsAmt = round(($gross * $tdsRate) / 100, 2);
            $adminAmt = round(($gross * $adminRate) / 100, 2);
            $netAmt = round($gross - $tdsAmt - $adminAmt, 2);

            $txnCode = 'COMM-POOL-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            $snapshot = [
                'type' => 'POOL_BONUS',
                'pool_member_id' => $newPoolMemberId,
                'source_advisor_id' => $sourceAdvisorId,
                'recipient_pool_label' => $up['pool_label'],
                'recipient_advisor_id' => $up['advisor_id'],
                'recipient_advisor_code' => $up['advisor_code'],
                'pool_depth' => $poolDepth,
                'gross_amount' => $gross,
                'tds_amount' => $tdsAmt,
                'net_amount' => $netAmt
            ];

            Database::execute(
                "INSERT INTO commission_transactions (
                    transaction_code, customer_id, payment_id, advisor_id, source_advisor_id, 
                    level, commission_type, rule_id, rule_version, product_name, payment_amount, 
                    company_credit_date, commission_month, commission_year, gross_amount, 
                    tds_deducted, admin_deducted, net_amount, qualification_status, qualification_notes, 
                    rule_snapshot_json, status, created_at
                 ) VALUES (?, NULL, NULL, ?, ?, ?, 'POOL_BONUS', NULL, 1, 'Pool Bonus Network', 0.00, ?, ?, ?, ?, ?, ?, ?, 'ELIGIBLE', ?, ?, 'PENDING', NOW())",
                [
                    $txnCode,
                    $up['advisor_id'],
                    $sourceAdvisorId,
                    $poolDepth,
                    date('Y-m-d'),
                    (int)date('n'),
                    (int)date('Y'),
                    $gross,
                    $tdsAmt,
                    $adminAmt,
                    $netAmt,
                    "Pool Level {$poolDepth} bonus from new Pool Member enrollment",
                    json_encode($snapshot)
                ]
            );

            $commId = (int)Database::lastInsertId();
            $results[] = [
                'pool_label' => $up['pool_label'],
                'advisor_code' => $up['advisor_code'],
                'depth' => $poolDepth,
                'net' => $netAmt,
                'transaction_id' => $commId
            ];

            if ($joiningSettings['approval_mode'] === 'AUTO') {
                CommissionApprovalService::approveCommission($commId, 1);
            }
        }

        return $results;
    }

    /**
     * Get Pool Members and Tree Structure for Visualizer
     */
    public static function getPoolTree(?int $rootPoolMemberId = null): array
    {
        $sql = "SELECT pm.*, 
                       p_parent.pool_label as parent_pool_label,
                       p_parent.advisor_id as parent_advisor_id,
                       CONCAT(p_parent_adv.first_name, ' ', p_parent_adv.last_name) as parent_advisor_name,
                       p_parent_adv.advisor_code as parent_advisor_code,
                       a.advisor_code, 
                       CONCAT(a.first_name, ' ', a.last_name) as advisor_name, 
                       a.mobile, 
                       a.email,
                       a.district,
                       a.block,
                       a.created_at as advisor_joining_date,
                       (SELECT COUNT(*) FROM advisors adv_child WHERE adv_child.sponsor_id = a.id AND adv_child.status = 'ACTIVE') as direct_advisors_count,
                       (SELECT COUNT(*) FROM customers c WHERE c.advisor_id = a.id) as personal_customers,
                       (SELECT COALESCE(SUM(ct.net_amount), 0) FROM commission_transactions ct WHERE ct.advisor_id = a.id AND ct.commission_type = 'POOL_BONUS' AND ct.status IN ('APPROVED', 'CREDITED_TO_WALLET')) as pool_earnings
                FROM pool_members pm
                JOIN advisors a ON pm.advisor_id = a.id
                LEFT JOIN pool_members p_parent ON pm.parent_pool_id = p_parent.id
                LEFT JOIN advisors p_parent_adv ON p_parent.advisor_id = p_parent_adv.id
                ORDER BY pm.pool_number ASC";
        return Database::fetchAll($sql);
    }

    /**
     * Build nested hierarchical pool tree (Root -> Level 1 -> Level 2...)
     */
    public static function getNestedPoolTree(?int $rootPoolId = null): array
    {
        $members = self::getPoolTree();
        if (empty($members)) {
            return [];
        }

        $byId = [];
        foreach ($members as $m) {
            $m['children'] = [];
            $byId[$m['id']] = $m;
        }

        $tree = [];
        foreach ($byId as $id => &$node) {
            if (!empty($node['parent_pool_id']) && isset($byId[$node['parent_pool_id']])) {
                $byId[$node['parent_pool_id']]['children'][] = &$node;
            } else {
                $tree[] = &$node;
            }
        }
        unset($node);

        if ($rootPoolId && isset($byId[$rootPoolId])) {
            return [$byId[$rootPoolId]];
        }

        return $tree;
    }

    /**
     * Get Pool Statistics Summary
     */
    public static function getPoolStatistics(): array
    {
        $totalMembers = (int)(Database::fetchOne("SELECT COUNT(*) as cnt FROM pool_members")['cnt'] ?? 0);
        $totalEarnings = (float)(Database::fetchOne("SELECT COALESCE(SUM(net_amount), 0) as sm FROM commission_transactions WHERE commission_type = 'POOL_BONUS' AND status IN ('APPROVED', 'CREDITED_TO_WALLET')")['sm'] ?? 0.0);
        $maxLevel = (int)(Database::fetchOne("SELECT COALESCE(MAX(pool_level), 0) as ml FROM pool_members")['ml'] ?? 0);
        $pendingPayout = (float)(Database::fetchOne("SELECT COALESCE(SUM(net_amount), 0) as sm FROM commission_transactions WHERE commission_type = 'POOL_BONUS' AND status = 'PENDING'")['sm'] ?? 0.0);

        return [
            'total_members' => $totalMembers,
            'total_earnings' => $totalEarnings,
            'pending_payout' => $pendingPayout,
            'max_level' => $maxLevel,
        ];
    }

    /**
     * Get Advisor Pool Status
     */
    public static function getAdvisorPoolInfo(int $advisorId): ?array
    {
        $sql = "SELECT pm.*, p_parent.pool_label as parent_label,
                       a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name,
                       (SELECT COALESCE(SUM(ct.net_amount), 0) FROM commission_transactions ct WHERE ct.advisor_id = a.id AND ct.commission_type = 'POOL_BONUS' AND ct.status IN ('APPROVED', 'CREDITED_TO_WALLET')) as pool_earnings
                FROM pool_members pm
                JOIN advisors a ON pm.advisor_id = a.id
                LEFT JOIN pool_members p_parent ON pm.parent_pool_id = p_parent.id
                WHERE pm.advisor_id = ?";
        return Database::fetchOne($sql, [$advisorId]);
    }
}
