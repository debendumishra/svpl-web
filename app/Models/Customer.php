<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Customer Model
 */

namespace App\Models;

use App\Helpers\Database;

class Customer
{
    public static function findById(int $id): ?array
    {
        return Database::fetchOne("SELECT c.*, u.mobile as user_mobile, u.email as user_email,
                                          a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name
                                   FROM customers c
                                   LEFT JOIN users u ON c.user_id = u.id
                                   LEFT JOIN advisors a ON c.advisor_id = a.id
                                   WHERE c.id = ?", [$id]);
    }

    public static function findByUserId(int $userId): ?array
    {
        return Database::fetchOne("SELECT c.*, a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name
                                   FROM customers c
                                   LEFT JOIN advisors a ON c.advisor_id = a.id
                                   WHERE c.user_id = ?", [$userId]);
    }

    public static function create(array $data): int
    {
        $sql = "INSERT INTO customers (
                    user_id, customer_code, advisor_id, first_name, last_name,
                    mobile, email, state, district, block, gram_panchayat,
                    village, pincode, address_line, discom_name, consumer_number,
                    sanctioned_load_kw, proposed_solar_kw, monthly_avg_bill,
                    roof_type, roof_area_sqft, status, created_at
                ) VALUES (
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?, ?,
                    ?, ?, ?, NOW()
                )";

        Database::query($sql, [
            $data['user_id'] ?? null,
            $data['customer_code'],
            $data['advisor_id'] ?? null,
            $data['first_name'],
            $data['last_name'],
            $data['mobile'],
            $data['email'] ?? null,
            $data['state'] ?? 'Odisha',
            $data['district'],
            $data['block'],
            $data['gram_panchayat'],
            $data['village'] ?? null,
            $data['pincode'],
            $data['address_line'] ?? null,
            $data['discom_name'] ?? 'TPCODL',
            $data['consumer_number'] ?? null,
            $data['sanctioned_load_kw'] ?? 2.0,
            $data['proposed_solar_kw'] ?? 2.0,
            $data['monthly_avg_bill'] ?? null,
            $data['roof_type'] ?? 'RCC Roof',
            $data['roof_area_sqft'] ?? 300,
            $data['status'] ?? 'New',
        ]);

        return (int) Database::lastInsertId();
    }

    public static function getByAdvisorId(int $advisorId): array
    {
        return Database::fetchAll(
            "SELECT c.*, l.stage as lead_stage, l.status as lead_status, l.id as lead_id
             FROM customers c
             LEFT JOIN leads l ON l.customer_id = c.id
             WHERE c.advisor_id = ?
             ORDER BY c.id DESC",
            [$advisorId]
        );
    }

    public static function getAll(int $limit = 100, int $offset = 0, ?string $search = null): array
    {
        $params = [];
        $where = "WHERE 1=1";
        if ($search) {
            $where .= " AND (c.customer_code LIKE ? OR c.first_name LIKE ? OR c.last_name LIKE ? OR c.mobile LIKE ? OR c.district LIKE ?)";
            $term = "%{$search}%";
            $params = [$term, $term, $term, $term, $term];
        }

        $sql = "SELECT c.*, a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name,
                       l.stage as lead_stage, l.status as lead_status, l.id as lead_id
                FROM customers c
                LEFT JOIN advisors a ON c.advisor_id = a.id
                LEFT JOIN leads l ON l.customer_id = c.id
                {$where}
                ORDER BY c.id DESC LIMIT {$limit} OFFSET {$offset}";

        return Database::fetchAll($sql, $params);
    }

    public static function generateCustomerCode(): string
    {
        $next = Database::fetchOne("SELECT COUNT(*) as cnt FROM customers");
        $num = ($next ? (int)$next['cnt'] : 0) + 2001;
        return 'SVPL-CUST-' . $num;
    }
}
