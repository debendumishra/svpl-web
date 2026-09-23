<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * CommissionApprovalService - Workflow, Locking, Batch Approval & Wallet Dispatch
 */

namespace App\Services;

use App\Helpers\Database;
use App\Services\WalletService;
use App\Services\CommissionRuleService;

class CommissionApprovalService
{
    /**
     * Approve Single Commission Transaction & Dispatch Wallet Ledger Credit
     */
    public static function approveCommission(int $transactionId, ?int $approvedBy = null): array
    {
        Database::beginTransaction();

        try {
            // Lock commission transaction row
            if (Database::getDriver() !== 'sqlite') {
                $txn = Database::fetchOne("SELECT * FROM commission_transactions WHERE id = ? FOR UPDATE", [$transactionId]);
            } else {
                $txn = Database::fetchOne("SELECT * FROM commission_transactions WHERE id = ?", [$transactionId]);
            }

            if (!$txn) {
                Database::rollBack();
                return ['status' => false, 'message' => 'Commission transaction not found'];
            }

            if ($txn['status'] === 'APPROVED' || $txn['status'] === 'CREDITED_TO_WALLET') {
                Database::rollBack();
                return ['status' => true, 'message' => 'Commission transaction is already approved'];
            }

            if ($txn['status'] === 'REJECTED' || $txn['status'] === 'CANCELLED' || $txn['status'] === 'REVERSED') {
                Database::rollBack();
                return ['status' => false, 'message' => "Cannot approve a transaction with status: {$txn['status']}"];
            }

            $advisorId = (int)$txn['advisor_id'];
            $netAmount = (float)$txn['net_amount'];

            // 1. Update Commission Transaction Status
            Database::execute(
                "UPDATE commission_transactions SET 
                    status = 'APPROVED', approved_by = ?, approved_at = NOW(), updated_at = NOW() 
                 WHERE id = ?",
                [$approvedBy, $transactionId]
            );

            // 2. Dispatch Wallet Credit via WalletService with double-entry ledger entry
            if ($netAmount > 0) {
                $desc = "{$txn['commission_type']} (Ref: {$txn['transaction_code']})";
                $creditRes = WalletService::creditAdvisor(
                    $advisorId,
                    $netAmount,
                    $txn['commission_type'],
                    $desc,
                    $transactionId,
                    $txn['transaction_code'],
                    $approvedBy
                );

                if (!$creditRes['status']) {
                    Database::rollBack();
                    return ['status' => false, 'message' => 'Wallet credit failed: ' . $creditRes['message']];
                }

                // Update to CREDITED_TO_WALLET
                Database::execute(
                    "UPDATE commission_transactions SET status = 'CREDITED_TO_WALLET' WHERE id = ?",
                    [$transactionId]
                );
            }

            // 3. Log Immutable Audit Trail
            CommissionRuleService::logAudit(
                $approvedBy,
                'APPROVE_COMMISSION',
                'COMMISSION_TRANSACTION',
                $transactionId,
                $txn['status'],
                'CREDITED_TO_WALLET',
                "Approved commission #{$txn['transaction_code']} for Advisor ID {$advisorId} (Net: ₹{$netAmount})"
            );

            Database::commit();
            return [
                'status' => true,
                'message' => 'Commission approved and credited to wallet successfully',
                'transaction_code' => $txn['transaction_code'],
                'net_amount' => $netAmount
            ];

        } catch (\Throwable $t) {
            Database::rollBack();
            return ['status' => false, 'message' => 'Approval error: ' . $t->getMessage()];
        }
    }

    /**
     * Batch Approve multiple commission transactions
     */
    public static function bulkApprove(array $transactionIds, ?int $approvedBy = null): array
    {
        $approvedCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($transactionIds as $id) {
            $res = self::approveCommission((int)$id, $approvedBy);
            if ($res['status']) {
                $approvedCount++;
            } else {
                $failedCount++;
                $errors[] = "#{$id}: " . $res['message'];
            }
        }

        return [
            'status' => true,
            'approved_count' => $approvedCount,
            'failed_count' => $failedCount,
            'errors' => $errors
        ];
    }

    /**
     * Reject Commission Transaction
     */
    public static function rejectCommission(int $transactionId, ?string $reason = null, ?int $adminId = null): array
    {
        $txn = Database::fetchOne("SELECT * FROM commission_transactions WHERE id = ?", [$transactionId]);
        if (!$txn) return ['status' => false, 'message' => 'Transaction not found'];

        if ($txn['status'] === 'CREDITED_TO_WALLET') {
            return ['status' => false, 'message' => 'Cannot directly reject an already credited transaction. Use Reverse Commission instead.'];
        }

        Database::execute(
            "UPDATE commission_transactions SET status = 'REJECTED', qualification_notes = ?, approved_by = ?, approved_at = NOW(), updated_at = NOW() WHERE id = ?",
            [$reason ?: 'Rejected by admin', $adminId, $transactionId]
        );

        CommissionRuleService::logAudit(
            $adminId,
            'REJECT_COMMISSION',
            'COMMISSION_TRANSACTION',
            $transactionId,
            $txn['status'],
            'REJECTED',
            $reason ?: 'Rejected by Admin'
        );

        return ['status' => true, 'message' => 'Commission rejected successfully'];
    }

    /**
     * Put Commission Transaction on Hold
     */
    public static function holdCommission(int $transactionId, ?string $reason = null, ?int $adminId = null): array
    {
        $txn = Database::fetchOne("SELECT * FROM commission_transactions WHERE id = ?", [$transactionId]);
        if (!$txn) return ['status' => false, 'message' => 'Transaction not found'];

        Database::execute(
            "UPDATE commission_transactions SET status = 'ON_HOLD', qualification_notes = ?, updated_at = NOW() WHERE id = ?",
            [$reason ?: 'Put on hold for verification', $transactionId]
        );

        CommissionRuleService::logAudit(
            $adminId,
            'HOLD_COMMISSION',
            'COMMISSION_TRANSACTION',
            $transactionId,
            $txn['status'],
            'ON_HOLD',
            $reason ?: 'Put on hold by Admin'
        );

        return ['status' => true, 'message' => 'Commission placed on hold'];
    }
}
