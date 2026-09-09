<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Commission Model
 */

namespace App\Models;

use App\Helpers\Database;

class Commission
{
    public static function create(array $data): int
    {
        $sql = "INSERT INTO commissions (
                    lead_id, customer_id, advisor_id, source_advisor_id,
                    level, commission_amount, bonus_amount, tds_deducted,
                    net_amount, status, calculation_notes, created_at
                ) VALUES (
                    ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, NOW()
                )";

        Database::query($sql, [
            $data['lead_id'] ?? null,
            $data['customer_id'] ?? null,
            $data['advisor_id'],
            $data['source_advisor_id'] ?? null,
            $data['level'] ?? 1,
            $data['commission_amount'] ?? 0.00,
            $data['bonus_amount'] ?? 0.00,
            $data['tds_deducted'] ?? 0.00,
            $data['net_amount'] ?? 0.00,
            $data['status'] ?? 'APPROVED',
            $data['calculation_notes'] ?? null,
        ]);

        return (int) Database::lastInsertId();
    }

    public static function getByAdvisorId(int $advisorId): array
    {
        $sql = "SELECT c.*, l.lead_code, cust.customer_code, 
                       CONCAT(cust.first_name, ' ', cust.last_name) as customer_name
                FROM commissions c
                LEFT JOIN leads l ON c.lead_id = l.id
                LEFT JOIN customers cust ON c.customer_id = cust.id
                WHERE c.advisor_id = ?
                ORDER BY c.id DESC";
        return Database::fetchAll($sql, [$advisorId]);
    }

    public static function getAll(int $limit = 100, int $offset = 0): array
    {
        $sql = "SELECT c.*, a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name,
                       l.lead_code, cust.customer_code, CONCAT(cust.first_name, ' ', cust.last_name) as customer_name
                FROM commissions c
                JOIN advisors a ON c.advisor_id = a.id
                LEFT JOIN leads l ON c.lead_id = l.id
                LEFT JOIN customers cust ON c.customer_id = cust.id
                ORDER BY c.id DESC LIMIT {$limit} OFFSET {$offset}";
        return Database::fetchAll($sql);
    }

    public static function getPlanSlabs(): array
    {
        return Database::fetchAll("SELECT * FROM commission_plans ORDER BY level ASC");
    }
}
