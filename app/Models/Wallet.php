<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Wallet Model
 */

namespace App\Models;

use App\Helpers\Database;

class Wallet
{
    public static function create(array $data): int
    {
        $existing = Database::fetchOne("SELECT id FROM wallets WHERE user_id = ?", [$data['user_id']]);
        if ($existing) {
            return (int) $existing['id'];
        }

        $sql = "INSERT INTO wallets (user_id, balance, total_earned, total_withdrawn, pending_clearance, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())";
        Database::query($sql, [
            $data['user_id'],
            $data['balance'] ?? 0.00,
            $data['total_earned'] ?? 0.00,
            $data['total_withdrawn'] ?? 0.00,
            $data['pending_clearance'] ?? 0.00,
        ]);

        return (int) Database::lastInsertId();
    }

    public static function getByUserId(int $userId): ?array
    {
        $wallet = Database::fetchOne("SELECT * FROM wallets WHERE user_id = ?", [$userId]);
        if (!$wallet) {
            self::create(['user_id' => $userId]);
            $wallet = Database::fetchOne("SELECT * FROM wallets WHERE user_id = ?", [$userId]);
        }
        return $wallet;
    }

    public static function credit(int $userId, float $amount, string $type, string $description, ?int $referenceId = null): bool
    {
        $wallet = self::getByUserId($userId);
        $newBalance = (float) $wallet['balance'] + $amount;
        $newTotalEarned = (float) $wallet['total_earned'] + $amount;

        Database::beginTransaction();
        try {
            Database::execute(
                "UPDATE wallets SET balance = ?, total_earned = ?, updated_at = NOW() WHERE user_id = ?",
                [$newBalance, $newTotalEarned, $userId]
            );

            Database::execute(
                "INSERT INTO wallet_transactions (wallet_id, user_id, txn_type, amount, balance_after, description, reference_id, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
                [$wallet['id'], $userId, $type, $amount, $newBalance, $description, $referenceId]
            );

            Database::commit();
            return true;
        } catch (\Throwable $t) {
            Database::rollBack();
            return false;
        }
    }

    public static function debit(int $userId, float $amount, string $type, string $description, ?int $referenceId = null): bool
    {
        $wallet = self::getByUserId($userId);
        if ((float) $wallet['balance'] < $amount) {
            return false;
        }

        $newBalance = (float) $wallet['balance'] - $amount;

        Database::beginTransaction();
        try {
            Database::execute(
                "UPDATE wallets SET balance = ?, updated_at = NOW() WHERE user_id = ?",
                [$newBalance, $userId]
            );

            Database::execute(
                "INSERT INTO wallet_transactions (wallet_id, user_id, txn_type, amount, balance_after, description, reference_id, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
                [$wallet['id'], $userId, $type, -$amount, $newBalance, $description, $referenceId]
            );

            Database::commit();
            return true;
        } catch (\Throwable $t) {
            Database::rollBack();
            return false;
        }
    }

    public static function refund(int $userId, float $amount, string $description, ?int $referenceId = null): bool
    {
        $wallet = self::getByUserId($userId);
        $newBalance = (float) $wallet['balance'] + $amount;

        Database::beginTransaction();
        try {
            Database::execute(
                "UPDATE wallets SET balance = ?, updated_at = NOW() WHERE user_id = ?",
                [$newBalance, $userId]
            );

            Database::execute(
                "INSERT INTO wallet_transactions (wallet_id, user_id, txn_type, amount, balance_after, description, reference_id, created_at)
                 VALUES (?, ?, 'WITHDRAWAL_REFUND', ?, ?, ?, ?, NOW())",
                [$wallet['id'], $userId, $amount, $newBalance, $description, $referenceId]
            );

            Database::commit();
            return true;
        } catch (\Throwable $t) {
            Database::rollBack();
            return false;
        }
    }

    public static function getTransactions(int $userId, int $limit = 50): array
    {
        return Database::fetchAll(
            "SELECT * FROM wallet_transactions WHERE user_id = ? ORDER BY id DESC LIMIT {$limit}",
            [$userId]
        );
    }
}
