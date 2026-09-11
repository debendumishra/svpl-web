<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Lead Model - 10-Stage Pipeline
 */

namespace App\Models;

use App\Helpers\Database;

class Lead
{
    public static function findById(int $id): ?array
    {
        return Database::fetchOne(
            "SELECT l.*, c.customer_code, c.consumer_number, c.sanctioned_load_kw,
                    a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name,
                    p.title as package_title, p.capacity_kw as pkg_capacity
             FROM leads l
             LEFT JOIN customers c ON l.customer_id = c.id
             LEFT JOIN advisors a ON l.advisor_id = a.id
             LEFT JOIN packages p ON l.package_id = p.id
             WHERE l.id = ?",
            [$id]
        );
    }

    public static function findByCustomerCode(string $code): ?array
    {
        return Database::fetchOne(
            "SELECT l.* FROM leads l
             JOIN customers c ON l.customer_id = c.id
             WHERE c.customer_code = ?",
            [$code]
        );
    }

    public static function create(array $data): int
    {
        $sql = "INSERT INTO leads (
                    lead_code, customer_id, advisor_id, lead_source,
                    first_name, last_name, mobile, email,
                    state, district, block, gram_panchayat, pincode,
                    discom_name, consumer_number, proposed_capacity_kw,
                    package_id, stage, status, estimated_project_cost,
                    subsidy_amount, state_subsidy, customer_payable_amount, created_at
                ) VALUES (
                    ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, NOW()
                )";

        Database::query($sql, [
            $data['lead_code'],
            $data['customer_id'] ?? null,
            $data['advisor_id'] ?? null,
            $data['lead_source'] ?? 'Advisor Referral',
            $data['first_name'],
            $data['last_name'],
            $data['mobile'],
            $data['email'] ?? null,
            $data['state'] ?? 'Odisha',
            $data['district'],
            $data['block'],
            $data['gram_panchayat'],
            $data['pincode'],
            $data['discom_name'] ?? 'TPCODL',
            $data['consumer_number'] ?? null,
            $data['proposed_capacity_kw'] ?? 3.0,
            $data['package_id'] ?? 3,
            $data['stage'] ?? 'REGISTRATION',
            $data['status'] ?? 'New',
            $data['estimated_project_cost'] ?? 210000.00,
            $data['subsidy_amount'] ?? 78000.00,
            $data['state_subsidy'] ?? 60000.00,
            $data['customer_payable_amount'] ?? 72000.00,
        ]);

        return (int) Database::lastInsertId();
    }

    public static function updateStage(int $leadId, string $stage, string $status, ?string $remarks = null, ?int $userId = null): bool
    {
        $lead = self::findById($leadId);
        $prevStage = $lead['stage'] ?? 'REGISTRATION';
        $prevStatus = $lead['status'] ?? 'New';
        $customerId = $lead['customer_id'] ?? null;

        $sql = "UPDATE leads SET stage = ?, status = ?, updated_at = NOW() WHERE id = ?";
        $res = Database::execute($sql, [$stage, $status, $leadId]);

        if ($customerId) {
            Database::execute("UPDATE customers SET status = ?, updated_at = NOW() WHERE id = ?", [$stage, (int)$customerId]);

            // Auto-assign customer to BOE if moving out of Stage 1 (REGISTRATION) and user is a BOE
            if ($userId) {
                $user = User::findById($userId);
                if ($user && $user['role'] === 'BOE') {
                    $cust = Customer::findById((int)$customerId);
                    if ($cust && empty($cust['assigned_boe_id'])) {
                        Customer::assignBOE((int)$customerId, $userId);
                    }
                }
            }
        }

        // Fetch User Audit Metadata
        $userCode = 'SYSTEM';
        $userName = 'System Action';
        $userDesignation = 'System';
        if ($userId) {
            $user = User::findById($userId);
            if ($user) {
                $userName = $user['full_name'] ?? 'User #' . $userId;
                $userCode = $user['employee_code'] ?? ($user['advisor_code'] ?? 'USR-' . $user['id']);
                $userDesignation = $user['designation'] ?? ($user['role'] ?? 'Staff');
            }
        }

        // Record in lead_stage_history
        Database::execute(
            "INSERT INTO lead_stage_history (lead_id, stage, from_stage, to_stage, status_notes, remarks, changed_by_user_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
            [$leadId, $stage, $prevStage, $stage, "Status changed to {$status}", $remarks, $userId]
        );

        // Record in customer_status_history (Comprehensive Audit Trail)
        if ($customerId) {
            Database::execute(
                "INSERT INTO customer_status_history (customer_id, lead_id, from_stage, to_stage, from_status, to_status, changed_by_user_id, user_code, user_name, user_designation, remarks, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [$customerId, $leadId, $prevStage, $stage, $prevStatus, $status, $userId, $userCode, $userName, $userDesignation, $remarks]
            );
        }

        return $res;
    }

    public static function getHistory(int $leadId): array
    {
        return Database::fetchAll(
            "SELECT * FROM lead_stage_history WHERE lead_id = ? ORDER BY id ASC",
            [$leadId]
        );
    }

    public static function getCustomerAuditHistory(int $customerId): array
    {
        return Database::fetchAll(
            "SELECT * FROM customer_status_history WHERE customer_id = ? ORDER BY id DESC",
            [$customerId]
        );
    }

    public static function getByAdvisorId(int $advisorId): array
    {
        return Database::fetchAll(
            "SELECT l.*, c.customer_code, p.title as package_title
             FROM leads l
             LEFT JOIN customers c ON l.customer_id = c.id
             LEFT JOIN packages p ON l.package_id = p.id
             WHERE l.advisor_id = ?
             ORDER BY l.id DESC",
            [$advisorId]
        );
    }

    public static function getAll(int $limit = 100, int $offset = 0, ?string $stage = null, ?string $search = null): array
    {
        $params = [];
        $where = "WHERE 1=1";

        if ($stage && $stage !== 'ALL') {
            $where .= " AND l.stage = ?";
            $params[] = $stage;
        }

        if ($search) {
            $where .= " AND (l.lead_code LIKE ? OR l.first_name LIKE ? OR l.last_name LIKE ? OR l.mobile LIKE ? OR l.district LIKE ?)";
            $term = "%{$search}%";
            $params = array_merge($params, [$term, $term, $term, $term, $term]);
        }

        $sql = "SELECT l.*, c.customer_code, a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name,
                       p.title as package_title
                FROM leads l
                LEFT JOIN customers c ON l.customer_id = c.id
                LEFT JOIN advisors a ON l.advisor_id = a.id
                LEFT JOIN packages p ON l.package_id = p.id
                {$where}
                ORDER BY l.id DESC LIMIT {$limit} OFFSET {$offset}";

        return Database::fetchAll($sql, $params);
    }

    public static function generateLeadCode(): string
    {
        $next = Database::fetchOne("SELECT COUNT(*) as cnt FROM leads");
        $num = ($next ? (int)$next['cnt'] : 0) + 3001;
        return 'SVPL-LEAD-' . $num;
    }
}
