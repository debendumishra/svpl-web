<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * CommissionCalculationService - Multi-Level Calculation Engine & Snapshot Builder
 */

namespace App\Services;

use App\Helpers\Database;
use App\Models\Advisor;
use App\Models\Customer;
use App\Models\Genealogy;
use App\Models\Setting;
use App\Services\CommissionRuleService;

class CommissionCalculationService
{
    /**
     * Calculate and Generate Direct Advisor Joining Commission (₹700 to direct sponsor only)
     */
    public static function processAdvisorJoining(int $newAdvisorId, ?string $creditDate = null, ?int $paymentId = null): array
    {
        $newAdvisor = Advisor::findById($newAdvisorId);
        if (!$newAdvisor) {
            return ['status' => false, 'message' => 'New advisor not found'];
        }

        $sponsorId = (int)($newAdvisor['sponsor_id'] ?? 0);
        if (!$sponsorId) {
            return ['status' => true, 'message' => 'No direct sponsor; joining commission skipped', 'transactions' => []];
        }

        $sponsor = Advisor::findById($sponsorId);
        if (!$sponsor) {
            return ['status' => false, 'message' => 'Sponsor advisor not found'];
        }

        $joiningSettings = CommissionRuleService::getJoiningSettings();
        if (!$joiningSettings['enabled']) {
            return ['status' => true, 'message' => 'Joining commission currently disabled in settings', 'transactions' => []];
        }

        $directAmount = (float)$joiningSettings['direct_commission'];
        if ($directAmount <= 0) {
            return ['status' => true, 'message' => 'Direct joining commission amount is zero', 'transactions' => []];
        }

        // Commission period derived strictly from company credit date
        $creditDate = $creditDate ?: date('Y-m-d');
        $creditTs = strtotime($creditDate);
        $commMonth = (int)date('n', $creditTs);
        $commYear = (int)date('Y', $creditTs);

        // Duplicate prevention check
        $dupCheck = Database::fetchOne(
            "SELECT id FROM commission_transactions 
             WHERE advisor_id = ? AND source_advisor_id = ? AND commission_type = 'JOINING_COMMISSION' AND is_reversal = 0",
            [$sponsorId, $newAdvisorId]
        );
        if ($dupCheck) {
            return ['status' => false, 'message' => 'Joining commission already generated for this sponsor/advisor pair'];
        }

        $tdsRate = $joiningSettings['tds_percentage'];
        $adminRate = $joiningSettings['admin_deduction_percentage'];
        $tdsAmt = round(($directAmount * $tdsRate) / 100, 2);
        $adminAmt = round(($directAmount * $adminRate) / 100, 2);
        $netAmt = round($directAmount - $tdsAmt - $adminAmt, 2);

        $txnCode = 'COMM-JOIN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        $snapshot = [
            'type' => 'JOINING_COMMISSION',
            'new_advisor_id' => $newAdvisorId,
            'new_advisor_code' => $newAdvisor['advisor_code'],
            'new_advisor_name' => $newAdvisor['first_name'] . ' ' . $newAdvisor['last_name'],
            'sponsor_id' => $sponsorId,
            'sponsor_code' => $sponsor['advisor_code'],
            'joining_fee' => $joiningSettings['joining_fee'],
            'gross_commission' => $directAmount,
            'tds_rate' => $tdsRate,
            'tds_amount' => $tdsAmt,
            'admin_rate' => $adminRate,
            'admin_amount' => $adminAmt,
            'net_amount' => $netAmt,
            'company_credit_date' => $creditDate,
            'commission_month' => $commMonth,
            'commission_year' => $commYear,
        ];

        Database::execute(
            "INSERT INTO commission_transactions (
                transaction_code, customer_id, payment_id, advisor_id, source_advisor_id, 
                level, commission_type, rule_id, rule_version, product_name, payment_amount, 
                company_credit_date, commission_month, commission_year, gross_amount, 
                tds_deducted, admin_deducted, net_amount, qualification_status, qualification_notes, 
                rule_snapshot_json, status, created_at
             ) VALUES (?, NULL, ?, ?, ?, 1, 'JOINING_COMMISSION', NULL, 1, 'Advisor Onboarding', ?, ?, ?, ?, ?, ?, ?, ?, 'ELIGIBLE', 'Direct Sponsor Joining Commission (No Upline Flow)', ?, 'PENDING', NOW())",
            [
                $txnCode,
                $paymentId,
                $sponsorId,
                $newAdvisorId,
                $joiningSettings['joining_fee'],
                $creditDate,
                $commMonth,
                $commYear,
                $directAmount,
                $tdsAmt,
                $adminAmt,
                $netAmt,
                json_encode($snapshot)
            ]
        );

        $commId = (int)Database::lastInsertId();

        // Check if Auto-Approval is configured
        if ($joiningSettings['approval_mode'] === 'AUTO') {
            CommissionApprovalService::approveCommission($commId, 1);
        }

        return [
            'status' => true,
            'message' => 'Joining commission generated successfully',
            'transaction_id' => $commId,
            'transaction_code' => $txnCode,
            'net_amount' => $netAmt
        ];
    }

    /**
     * Calculate and Generate 9-Level Customer Referral Commission
     */
    public static function processCustomerReferral(
        int $customerId,
        ?string $creditDate = null,
        ?int $paymentId = null,
        float $paymentAmount = 0.00,
        ?string $productName = null,
        ?float $capacityKw = null,
        ?string $connectionType = null
    ): array {
        $customer = Customer::findById($customerId);
        if (!$customer) {
            return ['status' => false, 'message' => 'Customer not found'];
        }

        $referralAdvisorId = (int)($customer['advisor_id'] ?? 0);
        if (!$referralAdvisorId) {
            return ['status' => false, 'message' => 'Customer has no primary referring advisor linked'];
        }

        $directAdvisor = Advisor::findById($referralAdvisorId);
        if (!$directAdvisor) {
            return ['status' => false, 'message' => 'Direct referring advisor not found'];
        }

        $pkgId = !empty($customer['package_id']) ? (int)$customer['package_id'] : null;
        if (!$pkgId && !empty($customer['lead_id'])) {
            $lead = Database::fetchOne("SELECT package_id FROM leads WHERE id = ?", [$customer['lead_id']]);
            if (!empty($lead['package_id'])) {
                $pkgId = (int)$lead['package_id'];
            }
        }

        // Determine matching product rule from packages
        $rule = CommissionRuleService::matchProductRule(
            $productName ?: ($customer['product_name'] ?? null),
            $capacityKw ?: (float)($customer['proposed_solar_kw'] ?? 3.0),
            $connectionType ?: ($customer['connection_type'] ?? 'On-Grid'),
            $pkgId
        );

        // Date and Period rule
        $creditDate = $creditDate ?: date('Y-m-d');
        $creditTs = strtotime($creditDate);
        $commMonth = (int)date('n', $creditTs);
        $commYear = (int)date('Y', $creditTs);

        // Global settings
        $joiningSettings = CommissionRuleService::getJoiningSettings();
        $tdsRate = $joiningSettings['tds_percentage'];
        $adminRate = $joiningSettings['admin_deduction_percentage'];

        // Get 9-Level Uplines of Direct Advisor (Depth 1 = Level 2, Depth 2 = Level 3, etc.)
        $uplines = Genealogy::getUplines($directAdvisor['id'], 8);
        $uplineByLevel = [];
        foreach ($uplines as $up) {
            $uplineByLevel[((int)$up['depth']) + 1] = $up;
        }

        $results = [];

        // --- LEVEL 1: Direct Referring Advisor ---
        $l1Gross = (float)($rule['levels'][1] ?? $rule['direct_commission']);
        $l1Tds = round(($l1Gross * $tdsRate) / 100, 2);
        $l1Admin = round(($l1Gross * $adminRate) / 100, 2);
        $l1Net = round($l1Gross - $l1Tds - $l1Admin, 2);

        $l1Dup = Database::fetchOne(
            "SELECT id FROM commission_transactions 
             WHERE customer_id = ? AND advisor_id = ? AND level = 1 AND commission_type = 'CUSTOMER_REFERRAL' AND is_reversal = 0",
            [$customerId, $directAdvisor['id']]
        );

        if (!$l1Dup) {
            $l1TxnCode = 'COMM-REF-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            $l1Snapshot = [
                'rule_id' => $rule['id'],
                'rule_code' => $rule['rule_code'],
                'rule_name' => $rule['rule_name'],
                'product_name' => $rule['product_name'] ?? 'Solar System',
                'customer_id' => $customerId,
                'customer_code' => $customer['customer_code'],
                'customer_name' => $customer['first_name'] . ' ' . $customer['last_name'],
                'level' => 1,
                'advisor_id' => $directAdvisor['id'],
                'advisor_code' => $directAdvisor['advisor_code'],
                'advisor_name' => $directAdvisor['first_name'] . ' ' . $directAdvisor['last_name'],
                'gross_commission' => $l1Gross,
                'tds_rate' => $tdsRate,
                'tds_amount' => $l1Tds,
                'admin_rate' => $adminRate,
                'admin_amount' => $l1Admin,
                'net_amount' => $l1Net,
                'qualification_status' => 'ELIGIBLE',
                'qualification_notes' => 'Direct Level 1 Referrer',
                'company_credit_date' => $creditDate,
                'commission_month' => $commMonth,
                'commission_year' => $commYear,
            ];

            Database::execute(
                "INSERT INTO commission_transactions (
                    transaction_code, customer_id, payment_id, advisor_id, source_advisor_id, 
                    level, commission_type, rule_id, rule_version, product_name, payment_amount, 
                    company_credit_date, commission_month, commission_year, gross_amount, 
                    tds_deducted, admin_deducted, net_amount, qualification_status, qualification_notes, 
                    rule_snapshot_json, status, created_at
                 ) VALUES (?, ?, ?, ?, ?, 1, 'CUSTOMER_REFERRAL', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ELIGIBLE', 'Direct Level 1 Referrer', ?, 'PENDING', NOW())",
                [
                    $l1TxnCode,
                    $customerId,
                    $paymentId,
                    $directAdvisor['id'],
                    $directAdvisor['id'],
                    $rule['id'] ?? null,
                    $rule['version'] ?? 1,
                    $rule['product_name'] ?? 'Solar System',
                    $paymentAmount,
                    $creditDate,
                    $commMonth,
                    $commYear,
                    $l1Gross,
                    $l1Tds,
                    $l1Admin,
                    $l1Net,
                    json_encode($l1Snapshot)
                ]
            );

            $l1CommId = (int)Database::lastInsertId();
            $results[] = [
                'level' => 1,
                'advisor_id' => $directAdvisor['id'],
                'advisor_code' => $directAdvisor['advisor_code'],
                'advisor_name' => $directAdvisor['first_name'] . ' ' . $directAdvisor['last_name'],
                'gross' => $l1Gross,
                'net' => $l1Net,
                'status' => 'ELIGIBLE',
                'transaction_id' => $l1CommId
            ];

            if ($joiningSettings['approval_mode'] === 'AUTO') {
                CommissionApprovalService::approveCommission($l1CommId, 1);
            }
        }

        // --- LEVELS 2 TO 9: Uplines with Qualification Checks ---
        for ($lvl = 2; $lvl <= 9; $lvl++) {
            $upline = $uplineByLevel[$lvl] ?? null;
            if (!$upline) {
                continue; // No upline at this level in genealogy
            }

            $uplineAdvisorId = (int)$upline['id'];
            $uplinePersonalCustCount = self::getPersonalCustomerCount($uplineAdvisorId);
            $maxEligibleLevel = CommissionRuleService::getMaxEligibleLevel($uplinePersonalCustCount);

            $uplineGross = (float)($rule['levels'][$lvl] ?? ($lvl == 2 ? 1000.00 : 500.00));
            $isEligible = ($maxEligibleLevel >= $lvl && $uplineGross > 0);

            $qualStatus = $isEligible ? 'ELIGIBLE' : 'NOT_ELIGIBLE';
            $qualReason = $isEligible 
                ? "Qualified with {$uplinePersonalCustCount} personal customers (Max level: {$maxEligibleLevel})" 
                : "Advisor has only {$uplinePersonalCustCount} personal customer(s); maximum eligible upline level is {$maxEligibleLevel}";

            $uplineTds = $isEligible ? round(($uplineGross * $tdsRate) / 100, 2) : 0.00;
            $uplineAdmin = $isEligible ? round(($uplineGross * $adminRate) / 100, 2) : 0.00;
            $uplineNet = $isEligible ? round($uplineGross - $uplineTds - $uplineAdmin, 2) : 0.00;

            // Duplicate prevention check
            $upDup = Database::fetchOne(
                "SELECT id FROM commission_transactions 
                 WHERE customer_id = ? AND advisor_id = ? AND level = ? AND commission_type = 'CUSTOMER_REFERRAL' AND is_reversal = 0",
                [$customerId, $uplineAdvisorId, $lvl]
            );

            if (!$upDup) {
                $upTxnCode = 'COMM-REF-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
                $upSnapshot = [
                    'rule_id' => $rule['id'],
                    'rule_code' => $rule['rule_code'],
                    'rule_name' => $rule['rule_name'],
                    'product_name' => $rule['product_name'] ?? 'Solar System',
                    'customer_id' => $customerId,
                    'customer_code' => $customer['customer_code'],
                    'customer_name' => $customer['first_name'] . ' ' . $customer['last_name'],
                    'level' => $lvl,
                    'advisor_id' => $uplineAdvisorId,
                    'advisor_code' => $upline['advisor_code'],
                    'advisor_name' => $upline['first_name'] . ' ' . $upline['last_name'],
                    'personal_customer_count' => $uplinePersonalCustCount,
                    'max_eligible_level' => $maxEligibleLevel,
                    'gross_commission' => $uplineGross,
                    'tds_rate' => $tdsRate,
                    'tds_amount' => $uplineTds,
                    'admin_rate' => $adminRate,
                    'admin_amount' => $uplineAdmin,
                    'net_amount' => $uplineNet,
                    'qualification_status' => $qualStatus,
                    'qualification_notes' => $qualReason,
                    'company_credit_date' => $creditDate,
                    'commission_month' => $commMonth,
                    'commission_year' => $commYear,
                ];

                Database::execute(
                    "INSERT INTO commission_transactions (
                        transaction_code, customer_id, payment_id, advisor_id, source_advisor_id, 
                        level, commission_type, rule_id, rule_version, product_name, payment_amount, 
                        company_credit_date, commission_month, commission_year, gross_amount, 
                        tds_deducted, admin_deducted, net_amount, qualification_status, qualification_notes, 
                        rule_snapshot_json, status, created_at
                     ) VALUES (?, ?, ?, ?, ?, ?, 'CUSTOMER_REFERRAL', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                    [
                        $upTxnCode,
                        $customerId,
                        $paymentId,
                        $uplineAdvisorId,
                        $directAdvisor['id'],
                        $lvl,
                        $rule['id'] ?? null,
                        $rule['version'] ?? 1,
                        $rule['product_name'] ?? 'Solar System',
                        $paymentAmount,
                        $creditDate,
                        $commMonth,
                        $commYear,
                        $uplineGross,
                        $uplineTds,
                        $uplineAdmin,
                        $uplineNet,
                        $qualStatus,
                        $qualReason,
                        json_encode($upSnapshot),
                        $isEligible ? 'PENDING' : 'REJECTED'
                    ]
                );

                $upCommId = (int)Database::lastInsertId();
                $results[] = [
                    'level' => $lvl,
                    'advisor_id' => $uplineAdvisorId,
                    'advisor_code' => $upline['advisor_code'],
                    'advisor_name' => $upline['first_name'] . ' ' . $upline['last_name'],
                    'personal_customers' => $uplinePersonalCustCount,
                    'max_eligible_level' => $maxEligibleLevel,
                    'gross' => $uplineGross,
                    'net' => $uplineNet,
                    'status' => $qualStatus,
                    'reason' => $qualReason,
                    'transaction_id' => $upCommId
                ];

                if ($isEligible && $joiningSettings['approval_mode'] === 'AUTO') {
                    CommissionApprovalService::approveCommission($upCommId, 1);
                }
            }
        }

        // Check if direct advisor reaches new Monthly Special Bonus, Pool, or Reward milestones
        self::evaluateMonthlyBonus($directAdvisor['id'], $commMonth, $commYear);
        PoolBonusService::checkPoolQualification($directAdvisor['id']);
        RewardService::evaluateRewards($directAdvisor['id']);

        return [
            'status' => true,
            'message' => 'Customer referral commission processed across 9 levels',
            'rule' => $rule['rule_name'],
            'distributions' => $results
        ];
    }

    /**
     * Get Personal Lifetime Customer Count of an Advisor (Directly referred only)
     */
    public static function getPersonalCustomerCount(int $advisorId): int
    {
        $res = Database::fetchOne(
            "SELECT COUNT(DISTINCT c.id) as total 
             FROM customers c 
             WHERE c.advisor_id = ?",
            [$advisorId]
        );
        return (int)($res['total'] ?? 0);
    }

    /**
     * Get Personal Customer Count for a specific calendar month
     */
    public static function getMonthlyPersonalCustomerCount(int $advisorId, int $month, int $year): int
    {
        // Count customers whose qualifying payment / account credit occurred in that month/year
        $res = Database::fetchOne(
            "SELECT COUNT(DISTINCT c.id) as total 
             FROM customers c 
             WHERE c.advisor_id = ? 
               AND (
                 (MONTH(c.created_at) = ? AND YEAR(c.created_at) = ?)
                 OR EXISTS (
                   SELECT 1 FROM commission_transactions ct 
                   WHERE ct.customer_id = c.id AND ct.commission_month = ? AND ct.commission_year = ?
                 )
               )",
            [$advisorId, $month, $year, $month, $year]
        );
        return (int)($res['total'] ?? 0);
    }

    /**
     * Evaluate Monthly Customer Special Bonus for direct referrer (5 -> 3k, 10 -> 10k, 20 -> 30k)
     */
    public static function evaluateMonthlyBonus(int $advisorId, int $month, int $year): ?array
    {
        $custCount = self::getMonthlyPersonalCustomerCount($advisorId, $month, $year);
        $slabs = CommissionRuleService::getMonthlyBonusSlabs();
        if (empty($slabs)) return null;

        $achievedBonus = 0.00;
        $calcMode = Setting::get('monthly_bonus_calculation_mode', 'HIGHEST_SLAB');

        if ($calcMode === 'CUMULATIVE') {
            foreach ($slabs as $s) {
                if ($custCount >= (int)$s['min_customers']) {
                    $achievedBonus += (float)$s['bonus_amount'];
                }
            }
        } else {
            // HIGHEST_SLAB only
            foreach ($slabs as $s) {
                if ($custCount >= (int)$s['min_customers']) {
                    $achievedBonus = max($achievedBonus, (float)$s['bonus_amount']);
                }
            }
        }

        if ($achievedBonus <= 0) {
            return null;
        }

        // Check if bonus transaction already exists for this advisor in this month/year
        $existing = Database::fetchOne(
            "SELECT * FROM commission_transactions 
             WHERE advisor_id = ? AND commission_type = 'MONTHLY_SPECIAL_BONUS' 
               AND commission_month = ? AND commission_year = ? AND is_reversal = 0",
            [$advisorId, $month, $year]
        );

        $joiningSettings = CommissionRuleService::getJoiningSettings();
        $tdsRate = $joiningSettings['tds_percentage'];
        $adminRate = $joiningSettings['admin_deduction_percentage'];
        $tdsAmt = round(($achievedBonus * $tdsRate) / 100, 2);
        $adminAmt = round(($achievedBonus * $adminRate) / 100, 2);
        $netAmt = round($achievedBonus - $tdsAmt - $adminAmt, 2);

        $advisor = Advisor::findById($advisorId);

        if ($existing) {
            // If new slab is higher, update transaction
            if ($achievedBonus > (float)$existing['gross_amount'] && $existing['status'] === 'PENDING') {
                Database::execute(
                    "UPDATE commission_transactions SET 
                        gross_amount = ?, tds_deducted = ?, admin_deducted = ?, net_amount = ?, 
                        qualification_notes = ?, updated_at = NOW() 
                     WHERE id = ?",
                    [
                        $achievedBonus, $tdsAmt, $adminAmt, $netAmt,
                        "Updated to higher monthly slab ({$custCount} customers in {$month}/{$year})",
                        $existing['id']
                    ]
                );
            }
            return ['transaction_id' => $existing['id'], 'amount' => $achievedBonus];
        }

        // Insert new Monthly Bonus Transaction
        $txnCode = 'COMM-MBONUS-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        $snapshot = [
            'type' => 'MONTHLY_SPECIAL_BONUS',
            'advisor_id' => $advisorId,
            'advisor_code' => $advisor['advisor_code'] ?? '',
            'month' => $month,
            'year' => $year,
            'monthly_customers' => $custCount,
            'calculation_mode' => $calcMode,
            'gross_bonus' => $achievedBonus,
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
             ) VALUES (?, NULL, NULL, ?, ?, 1, 'MONTHLY_SPECIAL_BONUS', NULL, 1, 'Monthly Performance Bonus', 0.00, ?, ?, ?, ?, ?, ?, ?, 'ELIGIBLE', ?, ?, 'PENDING', NOW())",
            [
                $txnCode,
                $advisorId,
                $advisorId,
                date('Y-m-d'),
                $month,
                $year,
                $achievedBonus,
                $tdsAmt,
                $adminAmt,
                $netAmt,
                "Achieved {$custCount} personal customers in {$month}/{$year}",
                json_encode($snapshot)
            ]
        );

        $bonusId = (int)Database::lastInsertId();
        return ['transaction_id' => $bonusId, 'amount' => $achievedBonus];
    }

    /**
     * SIMULATOR: Simulate 9-level calculation without writing financial records
     */
    public static function simulateCustomerReferral(
        int $referralAdvisorId,
        ?string $productName = null,
        ?float $capacityKw = null,
        ?string $connectionType = null,
        ?string $creditDate = null
    ): array {
        $directAdvisor = Advisor::findById($referralAdvisorId);
        if (!$directAdvisor) {
            return ['status' => false, 'message' => 'Advisor not found'];
        }

        $rule = CommissionRuleService::matchProductRule($productName, $capacityKw, $connectionType);
        $creditDate = $creditDate ?: date('Y-m-d');
        $creditTs = strtotime($creditDate);
        $commMonth = (int)date('n', $creditTs);
        $commYear = (int)date('Y', $creditTs);

        $joiningSettings = CommissionRuleService::getJoiningSettings();
        $tdsRate = $joiningSettings['tds_percentage'];
        $adminRate = $joiningSettings['admin_deduction_percentage'];

        $uplines = Genealogy::getUplines($directAdvisor['id'], 8);
        $uplineByLevel = [];
        foreach ($uplines as $up) {
            $uplineByLevel[((int)$up['depth']) + 1] = $up;
        }

        $tree = [];
        $totalLiability = 0.00;

        // L1
        $l1PersonalCusts = self::getPersonalCustomerCount($directAdvisor['id']);
        $l1Gross = (float)($rule['levels'][1] ?? $rule['direct_commission']);
        $l1Tds = round(($l1Gross * $tdsRate) / 100, 2);
        $l1Net = round($l1Gross - $l1Tds, 2);
        $totalLiability += $l1Gross;

        $tree[] = [
            'level' => 1,
            'level_label' => 'Level 1 (Direct Referrer)',
            'advisor_id' => $directAdvisor['id'],
            'advisor_code' => $directAdvisor['advisor_code'],
            'advisor_name' => $directAdvisor['first_name'] . ' ' . $directAdvisor['last_name'],
            'personal_customers' => $l1PersonalCusts,
            'max_eligible_level' => 9,
            'applicable_level' => 1,
            'gross_commission' => $l1Gross,
            'net_commission' => $l1Net,
            'is_eligible' => true,
            'status' => 'ELIGIBLE',
            'reason' => 'Direct referring advisor receives configured product commission'
        ];

        // L2 to L9
        for ($lvl = 2; $lvl <= 9; $lvl++) {
            $upline = $uplineByLevel[$lvl] ?? null;
            if (!$upline) {
                $tree[] = [
                    'level' => $lvl,
                    'level_label' => "Level {$lvl}",
                    'advisor_id' => null,
                    'advisor_code' => '—',
                    'advisor_name' => 'No Upline Advisor',
                    'personal_customers' => 0,
                    'max_eligible_level' => 0,
                    'applicable_level' => $lvl,
                    'gross_commission' => 0.00,
                    'net_commission' => 0.00,
                    'is_eligible' => false,
                    'status' => 'NO_ADVISOR',
                    'reason' => "No upline exists in genealogy tree at Level {$lvl}"
                ];
                continue;
            }

            $uplineId = (int)$upline['id'];
            $custCount = self::getPersonalCustomerCount($uplineId);
            $maxLevel = CommissionRuleService::getMaxEligibleLevel($custCount);
            $gross = (float)($rule['levels'][$lvl] ?? ($lvl == 2 ? 1000.00 : 500.00));
            $isEligible = ($maxLevel >= $lvl && $gross > 0);

            $net = $isEligible ? round($gross - round(($gross * $tdsRate) / 100, 2), 2) : 0.00;
            if ($isEligible) $totalLiability += $gross;

            $tree[] = [
                'level' => $lvl,
                'level_label' => "Level {$lvl}",
                'advisor_id' => $uplineId,
                'advisor_code' => $upline['advisor_code'],
                'advisor_name' => $upline['first_name'] . ' ' . $upline['last_name'],
                'personal_customers' => $custCount,
                'max_eligible_level' => $maxLevel,
                'applicable_level' => $lvl,
                'gross_commission' => $gross,
                'net_commission' => $net,
                'is_eligible' => $isEligible,
                'status' => $isEligible ? 'ELIGIBLE' : 'NOT_ELIGIBLE',
                'reason' => $isEligible 
                    ? "Qualified with {$custCount} personal customer(s); eligible up to Level {$maxLevel}"
                    : "Advisor has only {$custCount} personal customer(s); maximum eligible upline level is {$maxLevel}"
            ];
        }

        return [
            'status' => true,
            'rule_matched' => $rule['rule_name'],
            'rule_code' => $rule['rule_code'],
            'credit_date' => $creditDate,
            'commission_month' => $commMonth,
            'commission_year' => $commYear,
            'total_commission_liability' => $totalLiability,
            'breakdown' => $tree
        ];
    }
}
