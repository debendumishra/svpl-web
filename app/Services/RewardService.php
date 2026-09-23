<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * RewardService - Lifetime Performance Reward Milestone Engine & Claims Manager
 */

namespace App\Services;

use App\Helpers\Database;
use App\Models\Advisor;
use App\Services\CommissionRuleService;
use App\Services\WalletService;

class RewardService
{
    /**
     * Evaluate Lifetime Customer Milestones for an Advisor
     */
    public static function evaluateRewards(int $advisorId): array
    {
        $advisor = Advisor::findById($advisorId);
        if (!$advisor) return ['status' => false, 'message' => 'Advisor not found'];

        $custCount = (int)Database::fetchOne(
            "SELECT COUNT(DISTINCT id) as total FROM customers WHERE advisor_id = ?",
            [$advisorId]
        )['total'] ?? 0;

        $slabs = CommissionRuleService::getRewardSlabs();
        $achievedNow = [];

        foreach ($slabs as $s) {
            $target = (int)$s['customer_target'];
            if ($custCount >= $target) {
                // Check if already claimed / recorded
                $claim = Database::fetchOne(
                    "SELECT * FROM advisor_reward_claims WHERE advisor_id = ? AND reward_id = ?",
                    [$advisorId, $s['id']]
                );

                if (!$claim) {
                    Database::execute(
                        "INSERT INTO advisor_reward_claims (advisor_id, reward_id, customer_count_snapshot, claim_type, status, created_at) 
                         VALUES (?, ?, ?, 'CASH', 'ACHIEVED', NOW())",
                        [$advisorId, $s['id'], $custCount]
                    );

                    $claimId = (int)Database::lastInsertId();
                    $achievedNow[] = [
                        'claim_id' => $claimId,
                        'reward_name' => $s['reward_name'],
                        'target' => $target,
                        'reward_value' => (float)$s['total_reward_value']
                    ];

                    CommissionRuleService::logAudit(
                        null,
                        'REWARD_ACHIEVED',
                        'ADVISOR_REWARD',
                        $claimId,
                        null,
                        json_encode(['advisor_id' => $advisorId, 'target' => $target, 'reward' => $s['reward_name']]),
                        "Advisor achieved Reward: {$s['reward_name']} ({$custCount} customers)"
                    );
                }
            }
        }

        return [
            'status' => true,
            'personal_customers' => $custCount,
            'newly_achieved' => $achievedNow
        ];
    }

    /**
     * Get Advisor Rewards Status (Achieved, Claimed, Next Target)
     */
    public static function getAdvisorRewards(int $advisorId): array
    {
        $custCount = (int)Database::fetchOne(
            "SELECT COUNT(DISTINCT id) as total FROM customers WHERE advisor_id = ?",
            [$advisorId]
        )['total'] ?? 0;

        $slabs = CommissionRuleService::getRewardSlabs();
        $claims = Database::fetchAll(
            "SELECT arc.*, ar.reward_name, ar.customer_target, ar.total_reward_value, ar.reward_type 
             FROM advisor_reward_claims arc 
             JOIN advisor_rewards ar ON arc.reward_id = ar.id 
             WHERE arc.advisor_id = ? 
             ORDER BY ar.customer_target ASC",
            [$advisorId]
        );

        $claimMap = [];
        foreach ($claims as $c) {
            $claimMap[(int)$c['reward_id']] = $c;
        }

        $rewardItems = [];
        $nextReward = null;

        foreach ($slabs as $s) {
            $rewardId = (int)$s['id'];
            $target = (int)$s['customer_target'];
            $claim = $claimMap[$rewardId] ?? null;

            $status = 'LOCKED';
            if ($claim) {
                $status = $claim['status'];
            } elseif ($custCount >= $target) {
                $status = 'ACHIEVED';
            }

            if ($status === 'LOCKED' && $nextReward === null) {
                $nextReward = [
                    'reward_name' => $s['reward_name'],
                    'target' => $target,
                    'current' => $custCount,
                    'remaining' => $target - $custCount,
                    'reward_value' => (float)$s['total_reward_value'],
                    'progress_percent' => min(100, round(($custCount / $target) * 100, 1))
                ];
            }

            $rewardItems[] = [
                'reward_id' => $rewardId,
                'reward_name' => $s['reward_name'],
                'customer_target' => $target,
                'per_customer_amount' => (float)$s['per_customer_amount'],
                'total_reward_value' => (float)$s['total_reward_value'],
                'reward_type' => $s['reward_type'],
                'status' => $status,
                'claim' => $claim
            ];
        }

        return [
            'personal_customers' => $custCount,
            'next_target' => $nextReward,
            'rewards' => $rewardItems
        ];
    }

    /**
     * Advisor Submits Claim Choice (Cash or Product)
     */
    public static function submitClaim(int $claimId, int $advisorId, string $claimType, ?string $remarks = null): array
    {
        $claim = Database::fetchOne("SELECT * FROM advisor_reward_claims WHERE id = ? AND advisor_id = ?", [$claimId, $advisorId]);
        if (!$claim) return ['status' => false, 'message' => 'Reward claim not found'];

        if ($claim['status'] !== 'ACHIEVED') {
            return ['status' => false, 'message' => 'Claim has already been submitted or processed'];
        }

        Database::execute(
            "UPDATE advisor_reward_claims SET claim_type = ?, remarks = ?, status = 'PENDING_APPROVAL', updated_at = NOW() WHERE id = ?",
            [$claimType, $remarks, $claimId]
        );

        return ['status' => true, 'message' => 'Reward claim submitted for Admin approval'];
    }

    /**
     * Admin Approves Reward Claim (Dispatches Cash to Wallet or Marks Product Pending/Delivered)
     */
    public static function approveClaim(int $claimId, string $actionType, ?string $deliveryRef = null, ?int $adminId = null): array
    {
        $claim = Database::fetchOne(
            "SELECT arc.*, ar.reward_name, ar.total_reward_value 
             FROM advisor_reward_claims arc 
             JOIN advisor_rewards ar ON arc.reward_id = ar.id 
             WHERE arc.id = ?",
            [$claimId]
        );
        if (!$claim) return ['status' => false, 'message' => 'Claim not found'];

        $advisorId = (int)$claim['advisor_id'];
        $rewardValue = (float)$claim['total_reward_value'];

        if ($actionType === 'CASH_PAYOUT') {
            // Credit Wallet
            $joiningSettings = CommissionRuleService::getJoiningSettings();
            $tdsRate = $joiningSettings['tds_percentage'];
            $tdsAmt = round(($rewardValue * $tdsRate) / 100, 2);
            $netAmt = round($rewardValue - $tdsAmt, 2);

            $creditRes = WalletService::creditAdvisor(
                $advisorId,
                $netAmt,
                'REWARD_CASH',
                "Reward Cash Payout: {$claim['reward_name']}",
                null,
                "REW-{$claimId}",
                $adminId
            );

            if (!$creditRes['status']) {
                return ['status' => false, 'message' => 'Wallet credit failed: ' . $creditRes['message']];
            }

            Database::execute(
                "UPDATE advisor_reward_claims SET 
                    status = 'APPROVED', claim_type = 'CASH', approved_by = ?, approved_at = NOW(), 
                    wallet_transaction_id = ?, updated_at = NOW() 
                 WHERE id = ?",
                [$adminId, $creditRes['transaction_id'], $claimId]
            );

        } elseif ($actionType === 'PRODUCT_DELIVERED') {
            Database::execute(
                "UPDATE advisor_reward_claims SET 
                    status = 'DELIVERED', claim_type = 'PRODUCT', approved_by = ?, approved_at = NOW(), 
                    delivery_date = NOW(), delivery_ref = ?, updated_at = NOW() 
                 WHERE id = ?",
                [$adminId, $deliveryRef, $claimId]
            );
        } else {
            Database::execute(
                "UPDATE advisor_reward_claims SET 
                    status = 'APPROVED', approved_by = ?, approved_at = NOW(), updated_at = NOW() 
                 WHERE id = ?",
                [$adminId, $claimId]
            );
        }

        CommissionRuleService::logAudit(
            $adminId,
            'APPROVE_REWARD_CLAIM',
            'ADVISOR_REWARD_CLAIM',
            $claimId,
            $claim['status'],
            'APPROVED',
            "Approved reward claim #{$claimId} ({$claim['reward_name']})"
        );

        return ['status' => true, 'message' => 'Reward claim approved successfully'];
    }

    /**
     * Get all Reward Claims for Admin Management
     */
    public static function getAllClaims(?string $status = null): array
    {
        $params = [];
        $where = "";
        if ($status) {
            $where = "WHERE arc.status = ?";
            $params[] = $status;
        }

        $sql = "SELECT arc.*, ar.reward_name, ar.customer_target, ar.total_reward_value, ar.reward_type as configured_type,
                       a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name, a.mobile as advisor_mobile,
                       u.full_name as approved_by_name
                FROM advisor_reward_claims arc
                JOIN advisor_rewards ar ON arc.reward_id = ar.id
                JOIN advisors a ON arc.advisor_id = a.id
                LEFT JOIN users u ON arc.approved_by = u.id
                {$where}
                ORDER BY arc.id DESC";
        return Database::fetchAll($sql, $params);
    }
}
