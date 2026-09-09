<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Genealogy Model - 9-Level Closure Table Management
 */

namespace App\Models;

use App\Helpers\Database;

class Genealogy
{
    /**
     * Insert a new advisor into the closure table
     */
    public static function insertNode(int $advisorId, ?int $sponsorId = null): void
    {
        // 1. Self reference (depth 0)
        Database::query(
            "INSERT INTO advisor_genealogy (ancestor_id, descendant_id, depth) VALUES (?, ?, 0)
             ON DUPLICATE KEY UPDATE depth = 0",
            [$advisorId, $advisorId]
        );

        // 2. If sponsor exists, copy all uplines of sponsor and increment depth by 1
        if ($sponsorId) {
            $sql = "INSERT INTO advisor_genealogy (ancestor_id, descendant_id, depth)
                    SELECT g.ancestor_id, ?, g.depth + 1
                    FROM advisor_genealogy g
                    WHERE g.descendant_id = ? AND g.depth < 9
                    ON DUPLICATE KEY UPDATE depth = VALUES(depth)";
            Database::query($sql, [$advisorId, $sponsorId]);
        }
    }

    /**
     * Get 9-Level Uplines of an advisor
     */
    public static function getUplines(int $advisorId, int $maxDepth = 9): array
    {
        $sql = "SELECT g.depth, a.*, u.full_name as user_full_name, u.mobile as user_mobile
                FROM advisor_genealogy g
                JOIN advisors a ON g.ancestor_id = a.id
                JOIN users u ON a.user_id = u.id
                WHERE g.descendant_id = ? AND g.depth > 0 AND g.depth <= ?
                ORDER BY g.depth ASC";
        return Database::fetchAll($sql, [$advisorId, $maxDepth]);
    }

    /**
     * Get Direct & Downline Advisors
     */
    public static function getDownlines(int $advisorId, int $maxDepth = 9): array
    {
        $sql = "SELECT g.depth, a.*, u.full_name as user_full_name, u.mobile as user_mobile,
                       w.balance as wallet_balance
                FROM advisor_genealogy g
                JOIN advisors a ON g.descendant_id = a.id
                JOIN users u ON a.user_id = u.id
                LEFT JOIN wallets w ON a.user_id = w.user_id
                WHERE g.ancestor_id = ? AND g.depth > 0 AND g.depth <= ?
                ORDER BY g.depth ASC, a.id ASC";
        return Database::fetchAll($sql, [$advisorId, $maxDepth]);
    }

    /**
     * Get Direct Downlines only (depth = 1)
     */
    public static function getDirectDownlines(int $advisorId): array
    {
        return self::getDownlines($advisorId, 1);
    }

    /**
     * Count Downline members by level
     */
    public static function getDownlineCountByLevel(int $advisorId): array
    {
        $sql = "SELECT depth, COUNT(*) as count 
                FROM advisor_genealogy 
                WHERE ancestor_id = ? AND depth > 0 
                GROUP BY depth 
                ORDER BY depth ASC";
        return Database::fetchAll($sql, [$advisorId]);
    }
}
