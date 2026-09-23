<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * WalletService - Immutable Double-Entry Ledger & Concurrency-Safe Wallet Manager
 */

namespace App\Services;

use App\Helpers\Database;
use App\Models\Wallet;
use App\Models\Advisor;

class WalletService
{
    /**
     * Credit Advisor Wallet with Immutable Ledger Entry
     */
    public static function creditAdvisor(
        int $advisorId,
        float $amount,
        string $transactionType,
        string $description,
        ?int $commissionId = null,
        ?string $referenceNo = null,
        ?int $createdBy = null
    ): array {
        if ($amount <= 0) {
            return ['status' => false, 'message' => 'Amount must be greater than zero'];
        }

        $advisor = Advisor::findById($advisorId);
        if (!$advisor) {
            return ['status' => false, 'message' => 'Advisor not found'];
        }

        $userId = (int) $advisor['user_id'];
        $wallet = Wallet::getByUserId($userId);
        if (!$wallet) {
            return ['status' => false, 'message' => 'Wallet could not be initialized'];
        }

        $db = Database::getInstance();
        Database::beginTransaction();

        try {
            // Check idempotency: If this commission has already been credited to wallet, prevent duplicate credit!
            if ($commissionId) {
                $exists = Database::fetchOne(
                    "SELECT id FROM advisor_wallet_transactions WHERE commission_id = ? AND transaction_type = ? AND credit_amount > 0",
                    [$commissionId, $transactionType]
                );
                if ($exists) {
                    Database::rollBack();
                    return ['status' => false, 'message' => 'Commission already credited to wallet ledger (Idempotent Check)'];
                }
            }

            // Lock the wallet row to prevent race conditions in concurrent approvals
            if (Database::getDriver() !== 'sqlite') {
                $lockedWallet = Database::fetchOne("SELECT balance, total_earned FROM wallets WHERE user_id = ? FOR UPDATE", [$userId]);
            } else {
                $lockedWallet = Database::fetchOne("SELECT balance, total_earned FROM wallets WHERE user_id = ?", [$userId]);
            }

            $currentBalance = (float)($lockedWallet['balance'] ?? 0.00);
            $newBalance = round($currentBalance + $amount, 2);
            $newTotalEarned = round(((float)($lockedWallet['total_earned'] ?? 0.00)) + $amount, 2);

            // Update Wallet Balance
            Database::execute(
                "UPDATE wallets SET balance = ?, total_earned = ?, updated_at = NOW() WHERE user_id = ?",
                [$newBalance, $newTotalEarned, $userId]
            );

            // Generate Unique Ledger Ref
            $txnRef = 'TXN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

            // Insert Immutable Ledger Transaction
            Database::execute(
                "INSERT INTO advisor_wallet_transactions (
                    transaction_ref, advisor_id, user_id, commission_id, transaction_type, 
                    credit_amount, debit_amount, balance_after, description, reference_no, created_by, created_at
                 ) VALUES (?, ?, ?, ?, ?, ?, 0.00, ?, ?, ?, ?, NOW())",
                [
                    $txnRef,
                    $advisorId,
                    $userId,
                    $commissionId,
                    $transactionType,
                    $amount,
                    $newBalance,
                    $description,
                    $referenceNo,
                    $createdBy
                ]
            );

            $txnId = (int)Database::lastInsertId();

            // Also record in legacy wallet_transactions for backward compatibility
            Database::execute(
                "INSERT INTO wallet_transactions (wallet_id, user_id, txn_type, amount, balance_after, description, reference_id, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
                [$wallet['id'], $userId, $transactionType, $amount, $newBalance, $description, $commissionId]
            );

            Database::commit();
            return [
                'status' => true,
                'transaction_id' => $txnId,
                'transaction_ref' => $txnRef,
                'balance_after' => $newBalance
            ];

        } catch (\Throwable $t) {
            Database::rollBack();
            return ['status' => false, 'message' => 'Wallet credit failed: ' . $t->getMessage()];
        }
    }

    /**
     * Debit Advisor Wallet with Ledger Entry (Withdrawals, Reversals, Adjustments)
     */
    public static function debitAdvisor(
        int $advisorId,
        float $amount,
        string $transactionType,
        string $description,
        ?int $commissionId = null,
        ?string $referenceNo = null,
        ?int $createdBy = null
    ): array {
        if ($amount <= 0) {
            return ['status' => false, 'message' => 'Amount must be greater than zero'];
        }

        $advisor = Advisor::findById($advisorId);
        if (!$advisor) {
            return ['status' => false, 'message' => 'Advisor not found'];
        }

        $userId = (int) $advisor['user_id'];
        $wallet = Wallet::getByUserId($userId);
        if (!$wallet) {
            return ['status' => false, 'message' => 'Wallet not found'];
        }

        Database::beginTransaction();

        try {
            if (Database::getDriver() !== 'sqlite') {
                $lockedWallet = Database::fetchOne("SELECT balance, total_withdrawn FROM wallets WHERE user_id = ? FOR UPDATE", [$userId]);
            } else {
                $lockedWallet = Database::fetchOne("SELECT balance, total_withdrawn FROM wallets WHERE user_id = ?", [$userId]);
            }

            $currentBalance = (float)($lockedWallet['balance'] ?? 0.00);

            // Reversals can allow negative balance if money was already withdrawn, but standard withdrawals check balance
            if ($transactionType === 'WITHDRAWAL' && $currentBalance < $amount) {
                Database::rollBack();
                return ['status' => false, 'message' => 'Insufficient wallet balance'];
            }

            $newBalance = round($currentBalance - $amount, 2);
            $newTotalWithdrawn = ($transactionType === 'WITHDRAWAL') ? round(((float)($lockedWallet['total_withdrawn'] ?? 0.00)) + $amount, 2) : (float)($lockedWallet['total_withdrawn'] ?? 0.00);

            Database::execute(
                "UPDATE wallets SET balance = ?, total_withdrawn = ?, updated_at = NOW() WHERE user_id = ?",
                [$newBalance, $newTotalWithdrawn, $userId]
            );

            $txnRef = 'TXN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

            Database::execute(
                "INSERT INTO advisor_wallet_transactions (
                    transaction_ref, advisor_id, user_id, commission_id, transaction_type, 
                    credit_amount, debit_amount, balance_after, description, reference_no, created_by, created_at
                 ) VALUES (?, ?, ?, ?, ?, 0.00, ?, ?, ?, ?, ?, NOW())",
                [
                    $txnRef,
                    $advisorId,
                    $userId,
                    $commissionId,
                    $transactionType,
                    $amount,
                    $newBalance,
                    $description,
                    $referenceNo,
                    $createdBy
                ]
            );

            $txnId = (int)Database::lastInsertId();

            Database::execute(
                "INSERT INTO wallet_transactions (wallet_id, user_id, txn_type, amount, balance_after, description, reference_id, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
                [$wallet['id'], $userId, $transactionType, -$amount, $newBalance, $description, $commissionId]
            );

            Database::commit();
            return [
                'status' => true,
                'transaction_id' => $txnId,
                'transaction_ref' => $txnRef,
                'balance_after' => $newBalance
            ];

        } catch (\Throwable $t) {
            Database::rollBack();
            return ['status' => false, 'message' => 'Wallet debit failed: ' . $t->getMessage()];
        }
    }

    /**
     * Get Advisor Wallet Ledger History
     */
    public static function getAdvisorTransactions(int $advisorId, int $limit = 100): array
    {
        $sql = "SELECT awt.*, ct.transaction_code as commission_code, ct.product_name, ct.level, ct.rule_snapshot_json,
                       u.full_name as creator_name
                FROM advisor_wallet_transactions awt
                LEFT JOIN commission_transactions ct ON awt.commission_id = ct.id
                LEFT JOIN users u ON awt.created_by = u.id
                WHERE awt.advisor_id = ?
                ORDER BY awt.id DESC
                LIMIT {$limit}";
        return Database::fetchAll($sql, [$advisorId]);
    }

    /**
     * Reconcile Advisor Wallet Balance from Ledger
     */
    public static function reconcileWallet(int $advisorId): array
    {
        $advisor = Advisor::findById($advisorId);
        if (!$advisor) return ['status' => false, 'message' => 'Advisor not found'];

        $totals = Database::fetchOne(
            "SELECT COALESCE(SUM(credit_amount), 0) as total_credits, COALESCE(SUM(debit_amount), 0) as total_debits 
             FROM advisor_wallet_transactions WHERE advisor_id = ?",
            [$advisorId]
        );

        $reconciledBalance = round((float)$totals['total_credits'] - (float)$totals['total_debits'], 2);

        Database::execute(
            "UPDATE wallets SET balance = ?, total_earned = ?, updated_at = NOW() WHERE user_id = ?",
            [$reconciledBalance, (float)$totals['total_credits'], $advisor['user_id']]
        );

        return [
            'status' => true,
            'total_credits' => (float)$totals['total_credits'],
            'total_debits' => (float)$totals['total_debits'],
            'reconciled_balance' => $reconciledBalance
        ];
    }
}
