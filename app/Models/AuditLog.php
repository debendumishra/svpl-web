<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * AuditLog Model
 */

namespace App\Models;

use App\Helpers\Database;

class AuditLog
{
    public static function log($param1 = null, $param2 = '', ?string $entityType = null, ?int $entityId = null, ?string $notes = null): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'CLI/System';

        $userId = null;
        $action = 'ACTION';

        if (is_string($param1) && !is_numeric($param1)) {
            // Called as: AuditLog::log('ACTION_NAME', 'Notes/Details...', 'ENTITY_TYPE', $entityId)
            $action = $param1;
            $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
            $notes = is_string($param2) ? $param2 : $notes;
        } else {
            // Called as: AuditLog::log($userId, 'ACTION_NAME', ...)
            $userId = $param1 !== null ? (int)$param1 : (isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null);
            $action = (string)$param2;
            if ($entityType !== null && $entityId === null && $notes === null && strlen($entityType) > 30) {
                $notes = $entityType;
                $entityType = null;
            }
        }

        try {
            Database::query(
                "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, ip_address, user_agent, details, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
                [$userId, $action, $entityType, $entityId, $ip, substr($agent, 0, 255), $notes]
            );
        } catch (\Throwable $t) {
            // Ignore audit log errors to prevent blocking primary transactions
        }
    }

    public static function getRecent(int $limit = 100): array
    {
        $sql = "SELECT al.*, u.full_name, u.role, u.email
                FROM audit_logs al
                LEFT JOIN users u ON al.user_id = u.id
                ORDER BY al.id DESC LIMIT {$limit}";
        return Database::fetchAll($sql);
    }
}
