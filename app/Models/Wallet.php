<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Wallet Model
 */

namespace App\Models;

use App\Helpers\Database;

class Wallet
{
    public static function getByUserId(int $userId): ?array
    {
        $wallet = Database::fetchOne("SELECT * FROM wallets WHERE user_id = ?", [$userId]);
        if (!$wallet) {
            Database::query("INSERT INTO wallets (user_id, balance, total_earned, total_withdrawn, pending_clearance) VALUES (?, 0, 0, 0, 0)", [$userId]);
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

    public static function getTransactions(int $userId, int $limit = 50): array
    {
        return Database::fetchAll(
            "SELECT * FROM wallet_transactions WHERE user_id = ? ORDER BY id DESC LIMIT {$limit}",
            [$userId]
        );
    }
}
