<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * CommissionLedgerService - Reversals, Month Locking, Cycle Summary & Financial Audits
 */

namespace App\Services;

use App\Helpers\Database;
use App\Services\WalletService;
use App\Services\CommissionRuleService;

class CommissionLedgerService
{
    /**
     * Reverse an Approved/Credited Commission Transaction
     */
    public static function reverseCommission(int $transactionId, string $reason, ?int $adminId = null): array
    {
        Database::beginTransaction();

        try {
            if (Database::getDriver() !== 'sqlite') {
                $txn = Database::fetchOne("SELECT * FROM commission_transactions WHERE id = ? FOR UPDATE", [$transactionId]);
            } else {
                $txn = Database::fetchOne("SELECT * FROM commission_transactions WHERE id = ?", [$transactionId]);
            }

            if (!$txn) {
                Database::rollBack();
                return ['status' => false, 'message' => 'Commission transaction not found'];
            }

            if ($txn['is_reversal'] == 1 || $txn['status'] === 'REVERSED') {
                Database::rollBack();
                return ['status' => false, 'message' => 'Transaction is already reversed'];
            }

            $advisorId = (int)$txn['advisor_id'];
            $netAmount = (float)$txn['net_amount'];

            // 1. If previously credited to wallet, create a reversing debit ledger transaction
            if ($txn['status'] === 'CREDITED_TO_WALLET' && $netAmount > 0) {
                $debitRes = WalletService::debitAdvisor(
                    $advisorId,
                    $netAmount,
                    'COMMISSION_REVERSAL',
                    "Commission Reversal: {$txn['transaction_code']} (Reason: {$reason})",
                    $transactionId,
                    "REV-{$txn['transaction_code']}",
                    $adminId
                );

                if (!$debitRes['status']) {
                    Database::rollBack();
                    return ['status' => false, 'message' => 'Failed to debit wallet during reversal: ' . $debitRes['message']];
                }
            }

            // 2. Mark original transaction as REVERSED
            Database::execute(
                "UPDATE commission_transactions SET 
                    status = 'REVERSED', is_reversal = 1, reversal_reason = ?, updated_at = NOW() 
                 WHERE id = ?",
                [$reason, $transactionId]
            );

            // 3. Insert Reversal Counter-Entry in commission_transactions
            $revCode = 'REV-' . $txn['transaction_code'];
            Database::execute(
                "INSERT INTO commission_transactions (
                    transaction_code, customer_id, payment_id, advisor_id, source_advisor_id, 
                    level, commission_type, rule_id, rule_version, product_name, payment_amount, 
                    company_credit_date, commission_month, commission_year, gross_amount, 
                    tds_deducted, admin_deducted, net_amount, qualification_status, qualification_notes, 
                    rule_snapshot_json, status, approved_by, approved_at, is_reversal, parent_transaction_id, reversal_reason, created_at
                 ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'REVERSED', ?, ?, 'REVERSED', ?, NOW(), 1, ?, ?, NOW())",
                [
                    $revCode,
                    $txn['customer_id'],
                    $txn['payment_id'],
                    $txn['advisor_id'],
                    $txn['source_advisor_id'],
                    $txn['level'],
                    $txn['commission_type'] . '_REVERSAL',
                    $txn['rule_id'],
                    $txn['rule_version'],
                    $txn['product_name'],
                    -$txn['payment_amount'],
                    $txn['company_credit_date'],
                    $txn['commission_month'],
                    $txn['commission_year'],
                    -$txn['gross_amount'],
                    -$txn['tds_deducted'],
                    -$txn['admin_deducted'],
                    -$txn['net_amount'],
                    "Reversal: {$reason}",
                    $txn['rule_snapshot_json'],
                    $adminId,
                    $transactionId,
                    $reason
                ]
            );

            CommissionRuleService::logAudit(
                $adminId,
                'REVERSE_COMMISSION',
                'COMMISSION_TRANSACTION',
                $transactionId,
                $txn['status'],
                'REVERSED',
                "Reversed commission #{$txn['transaction_code']} for Advisor {$advisorId}. Reason: {$reason}"
            );

            Database::commit();
            return [
                'status' => true,
                'message' => 'Commission transaction reversed and financial ledger updated successfully',
                'reversal_code' => $revCode
            ];

        } catch (\Throwable $t) {
            Database::rollBack();
            return ['status' => false, 'message' => 'Reversal error: ' . $t->getMessage()];
        }
    }

    /**
     * Reverse all commissions generated from a specific Payment / Customer transaction
     */
    public static function reverseAllPaymentCommissions(int $paymentId, string $reason, ?int $adminId = null): array
    {
        $transactions = Database::fetchAll(
            "SELECT id FROM commission_transactions WHERE payment_id = ? AND is_reversal = 0 AND status != 'REVERSED'",
            [$paymentId]
        );

        $count = 0;
        foreach ($transactions as $t) {
            $res = self::reverseCommission((int)$t['id'], $reason, $adminId);
            if ($res['status']) $count++;
        }

        return ['status' => true, 'reversed_count' => $count];
    }

    /**
     * Get Monthly Commission Cycle Summary
     */
    public static function getMonthlyCycleSummary(int $month, int $year): array
    {
        // 1. Total business / payments received in company credit date
        $bizRow = Database::fetchOne(
            "SELECT COALESCE(SUM(amount), 0) as total_business, COUNT(*) as total_payments 
             FROM payments 
             WHERE MONTH(payment_date) = ? AND YEAR(payment_date) = ? AND status = 'CONFIRMED'",
            [$month, $year]
        );

        // 2. Commission transactions breakdown for this month
        $commSummary = Database::fetchOne(
            "SELECT 
                COALESCE(SUM(CASE WHEN is_reversal = 0 THEN gross_amount ELSE 0 END), 0) as total_gross,
                COALESCE(SUM(CASE WHEN is_reversal = 0 THEN tds_deducted ELSE 0 END), 0) as total_tds,
                COALESCE(SUM(CASE WHEN is_reversal = 0 THEN net_amount ELSE 0 END), 0) as total_net,
                COALESCE(SUM(CASE WHEN commission_type = 'CUSTOMER_REFERRAL' AND level = 1 AND is_reversal = 0 THEN net_amount ELSE 0 END), 0) as l1_comm,
                COALESCE(SUM(CASE WHEN commission_type = 'CUSTOMER_REFERRAL' AND level > 1 AND is_reversal = 0 THEN net_amount ELSE 0 END), 0) as upline_comm,
                COALESCE(SUM(CASE WHEN commission_type = 'JOINING_COMMISSION' AND is_reversal = 0 THEN net_amount ELSE 0 END), 0) as joining_comm,
                COALESCE(SUM(CASE WHEN commission_type = 'MONTHLY_SPECIAL_BONUS' AND is_reversal = 0 THEN net_amount ELSE 0 END), 0) as monthly_bonus,
                COALESCE(SUM(CASE WHEN commission_type = 'POOL_BONUS' AND is_reversal = 0 THEN net_amount ELSE 0 END), 0) as pool_bonus,
                COALESCE(SUM(CASE WHEN status = 'PENDING' AND is_reversal = 0 THEN net_amount ELSE 0 END), 0) as pending_amount,
                COALESCE(SUM(CASE WHEN status IN ('APPROVED', 'CREDITED_TO_WALLET') AND is_reversal = 0 THEN net_amount ELSE 0 END), 0) as approved_amount,
                COALESCE(SUM(CASE WHEN status = 'REVERSED' OR is_reversal = 1 THEN gross_amount ELSE 0 END), 0) as reversed_amount,
                COUNT(*) as total_transaction_count
             FROM commission_transactions 
             WHERE commission_month = ? AND commission_year = ?",
            [$month, $year]
        );

        // 3. Customer Special Bonus for this month
        $custBonus = Database::fetchOne(
            "SELECT * FROM customer_special_bonus_rules WHERE bonus_month = ? AND bonus_year = ?",
            [$month, $year]
        );

        // 4. Rewards claimed in this month
        $rewardRow = Database::fetchOne(
            "SELECT COALESCE(SUM(ar.total_reward_value), 0) as total_rewards 
             FROM advisor_reward_claims arc 
             JOIN advisor_rewards ar ON arc.reward_id = ar.id 
             WHERE MONTH(arc.created_at) = ? AND YEAR(arc.created_at) = ? AND arc.status = 'APPROVED'",
            [$month, $year]
        );

        // 5. Check Cycle Lock Status
        $cycle = Database::fetchOne("SELECT * FROM commission_cycles WHERE cycle_month = ? AND cycle_year = ?", [$month, $year]);
        $isLocked = !empty($cycle['is_locked']);

        return [
            'month' => $month,
            'year' => $year,
            'total_business' => (float)($bizRow['total_business'] ?? 0.00),
            'total_payments' => (int)($bizRow['total_payments'] ?? 0),
            'total_gross' => (float)($commSummary['total_gross'] ?? 0.00),
            'total_tds' => (float)($commSummary['total_tds'] ?? 0.00),
            'total_net' => (float)($commSummary['total_net'] ?? 0.00),
            'l1_commission' => (float)($commSummary['l1_comm'] ?? 0.00),
            'upline_commission' => (float)($commSummary['upline_comm'] ?? 0.00),
            'joining_commission' => (float)($commSummary['joining_comm'] ?? 0.00),
            'monthly_bonus' => (float)($commSummary['monthly_bonus'] ?? 0.00),
            'pool_bonus' => (float)($commSummary['pool_bonus'] ?? 0.00),
            'total_rewards' => (float)($rewardRow['total_rewards'] ?? 0.00),
            'customer_special_bonus' => (float)($custBonus['bonus_amount'] ?? 0.00),
            'pending_amount' => (float)($commSummary['pending_amount'] ?? 0.00),
            'approved_amount' => (float)($commSummary['approved_amount'] ?? 0.00),
            'reversed_amount' => (float)($commSummary['reversed_amount'] ?? 0.00),
            'total_transactions' => (int)($commSummary['total_transaction_count'] ?? 0),
            'is_locked' => $isLocked,
            'cycle_record' => $cycle
        ];
    }

    /**
     * Lock Commission Cycle Month
     */
    public static function lockMonth(int $month, int $year, int $adminId): bool
    {
        $summary = self::getMonthlyCycleSummary($month, $year);
        $existing = Database::fetchOne("SELECT id FROM commission_cycles WHERE cycle_month = ? AND cycle_year = ?", [$month, $year]);

        if ($existing) {
            Database::execute(
                "UPDATE commission_cycles SET 
                    total_business_amount = ?, total_commissions = ?, total_bonus = ?, total_pool = ?, 
                    total_rewards = ?, total_payable = ?, is_locked = 1, locked_by = ?, locked_at = NOW(), updated_at = NOW() 
                 WHERE id = ?",
                [
                    $summary['total_business'],
                    $summary['total_net'],
                    $summary['monthly_bonus'],
                    $summary['pool_bonus'],
                    $summary['total_rewards'],
                    $summary['approved_amount'],
                    $adminId,
                    $existing['id']
                ]
            );
        } else {
            Database::execute(
                "INSERT INTO commission_cycles (
                    cycle_month, cycle_year, total_business_amount, total_commissions, total_bonus, 
                    total_pool, total_rewards, total_payable, is_locked, locked_by, locked_at, created_at
                 ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, NOW(), NOW())",
                [
                    $month,
                    $year,
                    $summary['total_business'],
                    $summary['total_net'],
                    $summary['monthly_bonus'],
                    $summary['pool_bonus'],
                    $summary['total_rewards'],
                    $summary['approved_amount'],
                    $adminId
                ]
            );
        }

        CommissionRuleService::logAudit($adminId, 'LOCK_COMMISSION_CYCLE', 'COMMISSION_CYCLE', null, '0', '1', "Locked Commission Cycle for {$month}/{$year}");
        return true;
    }

    /**
     * Unlock Commission Cycle Month (Super Admin authorization with reason)
     */
    public static function unlockMonth(int $month, int $year, string $reason, int $superAdminId): bool
    {
        Database::execute(
            "UPDATE commission_cycles SET is_locked = 0, locked_by = NULL, locked_at = NULL, updated_at = NOW() WHERE cycle_month = ? AND cycle_year = ?",
            [$month, $year]
        );
        CommissionRuleService::logAudit($superAdminId, 'UNLOCK_COMMISSION_CYCLE', 'COMMISSION_CYCLE', null, '1', '0', "Unlocked Cycle for {$month}/{$year}. Reason: {$reason}");
        return true;
    }
}
