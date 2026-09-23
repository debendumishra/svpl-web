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
                                          a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name, a.mobile as advisor_mobile, a.email as advisor_email,
                                          boe.full_name as boe_name, boe.employee_code as boe_code, boe.mobile as boe_mobile, boe.email as boe_email, boe.designation as boe_designation,
                                          l.stage as lead_stage, l.status as lead_status, l.id as lead_id
                                   FROM customers c
                                   LEFT JOIN users u ON c.user_id = u.id
                                   LEFT JOIN advisors a ON c.advisor_id = a.id
                                   LEFT JOIN users boe ON c.assigned_boe_id = boe.id
                                   LEFT JOIN leads l ON l.customer_id = c.id
                                   WHERE c.id = ?", [$id]);
    }

    public static function findByUserId(int $userId): ?array
    {
        return Database::fetchOne("SELECT c.*, a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name, a.mobile as advisor_mobile, a.email as advisor_email,
                                          boe.full_name as boe_name, boe.employee_code as boe_code, boe.mobile as boe_mobile, boe.email as boe_email, boe.designation as boe_designation,
                                          l.stage as lead_stage, l.status as lead_status, l.id as lead_id
                                   FROM customers c
                                   LEFT JOIN advisors a ON c.advisor_id = a.id
                                   LEFT JOIN users boe ON c.assigned_boe_id = boe.id
                                   LEFT JOIN leads l ON l.customer_id = c.id
                                   WHERE c.user_id = ?", [$userId]);
    }

    public static function create(array $data): int
    {
        $sql = "INSERT INTO customers (
                    user_id, customer_code, advisor_id, first_name, last_name, full_name, father_husband_name, dob,
                    mobile, email, state, district, block, gram_panchayat,
                    village, pincode, address_line, discom_name, consumer_number, pm_surya_ghar_id, notification_number,
                    electricity_bill_mobile, electricity_bill_dob,
                    sanctioned_load_kw, proposed_solar_kw, monthly_avg_bill,
                    roof_type, roof_area_sqft, photo_url, customer_signature, status,
                    agreement_accepted, agreement_accepted_at, assigned_boe_id, created_at
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?,
                    ?, ?,
                    ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, NOW()
                )";

        $billDob = !empty($data['electricity_bill_dob']) ? $data['electricity_bill_dob'] : (!empty($data['dob']) ? $data['dob'] : null);
        $dob = !empty($data['dob']) ? $data['dob'] : $billDob;
        $fullName = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        $fatherHusband = !empty($data['father_husband_name']) ? trim($data['father_husband_name']) : (!empty($data['father_spouse_name']) ? trim($data['father_spouse_name']) : null);
        $agreementAccepted = !empty($data['agreement_accepted']) ? 1 : 0;
        $agreementAcceptedAt = $agreementAccepted ? (!empty($data['agreement_accepted_at']) ? $data['agreement_accepted_at'] : date('Y-m-d H:i:s')) : null;

        Database::execute($sql, [
            $data['user_id'] ?? null,
            $data['customer_code'],
            $data['advisor_id'] ?? null,
            $data['first_name'],
            $data['last_name'],
            $fullName,
            $fatherHusband,
            $dob,
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
            $data['pm_surya_ghar_id'] ?? null,
            $data['notification_number'] ?? null,
            $data['electricity_bill_mobile'] ?? $data['mobile'] ?? null,
            $billDob,
            $data['sanctioned_load_kw'] ?? 2.0,
            $data['proposed_solar_kw'] ?? 3.0,
            $data['monthly_avg_bill'] ?? null,
            $data['roof_type'] ?? 'RCC Roof',
            $data['roof_area_sqft'] ?? 300,
            $data['photo_url'] ?? null,
            $data['customer_signature'] ?? null,
            $data['status'] ?? 'New',
            $agreementAccepted,
            $agreementAcceptedAt,
            $data['assigned_boe_id'] ?? null,
        ]);

        return (int) Database::lastInsertId();
    }

    public static function updatePmSuryaGharId(int $customerId, string $pmId, ?string $notificationNo = null): bool
    {
        return Database::execute(
            "UPDATE customers SET pm_surya_ghar_id = ?, notification_number = COALESCE(?, notification_number), updated_at = NOW() WHERE id = ?",
            [$pmId, $notificationNo, $customerId]
        );
    }

    public static function getByAdvisorId(int $advisorId): array
    {
        return self::getDirectCustomersWithIssues($advisorId);
    }

    public static function getDirectCustomersWithIssues(int $advisorId): array
    {
        return Database::fetchAll(
            "SELECT c.*, l.stage as lead_stage, l.status as lead_status, l.id as lead_id,
                    boe.full_name as boe_name, boe.employee_code as boe_code, boe.mobile as boe_mobile, boe.email as boe_email, boe.designation as boe_designation,
                    (SELECT remarks FROM customer_status_history WHERE customer_id = c.id ORDER BY id DESC LIMIT 1) as latest_boe_remark,
                    (SELECT to_status FROM customer_status_history WHERE customer_id = c.id ORDER BY id DESC LIMIT 1) as boe_audit_status,
                    (SELECT COUNT(*) FROM documents WHERE entity_type = 'CUSTOMER' AND entity_id = c.id) as doc_count,
                    (SELECT COUNT(*) FROM documents WHERE entity_type = 'CUSTOMER' AND entity_id = c.id AND status IN ('Rejected', 'Action Required')) as issue_doc_count
             FROM customers c
             LEFT JOIN leads l ON l.customer_id = c.id
             LEFT JOIN users boe ON c.assigned_boe_id = boe.id
             WHERE c.advisor_id = ?
             ORDER BY c.id DESC",
            [$advisorId]
        );
    }

    public static function getTeamCustomersForAdvisor(int $advisorId, int $maxDepth = 9): array
    {
        $sql = "SELECT c.id, c.customer_code, c.first_name, c.last_name, c.district, c.block,
                       c.discom_name, c.sanctioned_load_kw, c.proposed_solar_kw, c.created_at,
                       g.depth as level_depth,
                       a.id as direct_advisor_id, a.advisor_code, a.referral_code, 
                       CONCAT(a.first_name, ' ', a.last_name) as direct_advisor_name,
                       a.mobile as direct_advisor_mobile, a.email as direct_advisor_email,
                       l.id as lead_id, l.stage as lead_stage, l.status as lead_status,
                       (SELECT remarks FROM customer_status_history WHERE customer_id = c.id ORDER BY id DESC LIMIT 1) as latest_boe_remark,
                       (SELECT user_name FROM customer_status_history WHERE customer_id = c.id ORDER BY id DESC LIMIT 1) as boe_auditor_name,
                       (SELECT to_status FROM customer_status_history WHERE customer_id = c.id ORDER BY id DESC LIMIT 1) as boe_audit_status
                FROM advisor_genealogy g
                JOIN customers c ON c.advisor_id = g.descendant_id
                JOIN advisors a ON c.advisor_id = a.id
                LEFT JOIN leads l ON l.customer_id = c.id
                WHERE g.ancestor_id = ? AND g.depth > 0 AND g.depth <= ?
                ORDER BY c.id DESC";

        return Database::fetchAll($sql, [$advisorId, $maxDepth]);
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
                       l.stage as lead_stage, l.status as lead_status, l.id as lead_id,
                       boe.full_name as boe_name, boe.employee_code as boe_code, boe.designation as boe_designation
                FROM customers c
                LEFT JOIN advisors a ON c.advisor_id = a.id
                LEFT JOIN leads l ON l.customer_id = c.id
                LEFT JOIN users boe ON c.assigned_boe_id = boe.id
                {$where}
                ORDER BY c.id DESC LIMIT {$limit} OFFSET {$offset}";

        return Database::fetchAll($sql, $params);
    }

    public static function getCustomersForBOE(int $boeUserId, ?string $search = null): array
    {
        $params = [];
        $where = "WHERE (
                    (COALESCE(l.stage, 'REGISTRATION') = 'REGISTRATION' AND (c.assigned_boe_id IS NULL OR c.assigned_boe_id = ?))
                    OR (c.assigned_boe_id = ?)
                  )";
        $params[] = $boeUserId;
        $params[] = $boeUserId;

        if ($search) {
            $where .= " AND (c.customer_code LIKE ? OR c.first_name LIKE ? OR c.last_name LIKE ? OR c.mobile LIKE ? OR c.district LIKE ?)";
            $term = "%{$search}%";
            $params = array_merge($params, [$term, $term, $term, $term, $term]);
        }

        $sql = "SELECT c.*, a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name, a.mobile as advisor_mobile, a.email as advisor_email,
                       l.stage as lead_stage, l.status as lead_status, l.id as lead_id,
                       boe.full_name as boe_name, boe.employee_code as boe_code
                FROM customers c
                LEFT JOIN advisors a ON c.advisor_id = a.id
                LEFT JOIN leads l ON l.customer_id = c.id
                LEFT JOIN users boe ON c.assigned_boe_id = boe.id
                {$where}
                ORDER BY c.id DESC";

        return Database::fetchAll($sql, $params);
    }

    public static function assignBOE(int $customerId, int $boeUserId): bool
    {
        $res = Database::execute("UPDATE customers SET assigned_boe_id = ?, updated_at = NOW() WHERE id = ?", [$boeUserId, $customerId]);
        Database::execute("UPDATE leads SET assigned_to_user_id = ?, updated_at = NOW() WHERE customer_id = ?", [$boeUserId, $customerId]);
        return $res;
    }

    public static function update(int $id, array $data): bool
    {
        $sql = "UPDATE customers SET 
                    first_name = ?, last_name = ?, full_name = ?, father_husband_name = ?, dob = ?,
                    mobile = ?, alt_mobile = ?, email = ?,
                    state = ?, district = ?, block = ?, gram_panchayat = ?, village = ?, pincode = ?, address_line = ?,
                    discom_name = ?, discom = ?, consumer_number = ?, electricity_consumer_no = ?,
                    electricity_bill_mobile = ?, electricity_bill_dob = ?,
                    sanctioned_load_kw = ?, proposed_solar_kw = ?, monthly_avg_bill = ?,
                    roof_type = ?, roof_area_sqft = ?,
                    bank_name = ?, bank_branch = ?, account_holder = ?, account_number = ?, ifsc_code = ?,
                    advisor_id = ?, status = ?,
                    updated_at = NOW()
                WHERE id = ?";

        $discom = $data['discom_name'] ?? 'TPCODL';
        $consumerNo = $data['consumer_number'] ?? null;
        $fullName = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        $billDob = !empty($data['electricity_bill_dob']) ? $data['electricity_bill_dob'] : (!empty($data['dob']) ? $data['dob'] : null);
        $dob = !empty($data['dob']) ? $data['dob'] : $billDob;
        $billMobile = !empty($data['electricity_bill_mobile']) ? $data['electricity_bill_mobile'] : ($data['mobile'] ?? null);

        $res = Database::execute($sql, [
            $data['first_name'],
            $data['last_name'],
            $fullName,
            $data['father_husband_name'] ?? $data['father_spouse_name'] ?? null,
            $dob,
            $data['mobile'],
            $data['alt_mobile'] ?? null,
            $data['email'] ?? null,
            $data['state'] ?? 'Odisha',
            $data['district'],
            $data['block'],
            $data['gram_panchayat'],
            $data['village'] ?? null,
            $data['pincode'],
            $data['address_line'] ?? null,
            $discom,
            $discom,
            $consumerNo,
            $consumerNo,
            $billMobile,
            $billDob,
            !empty($data['sanctioned_load_kw']) ? (float)$data['sanctioned_load_kw'] : 2.0,
            !empty($data['proposed_solar_kw']) ? (float)$data['proposed_solar_kw'] : 3.0,
            !empty($data['monthly_avg_bill']) ? (float)$data['monthly_avg_bill'] : null,
            $data['roof_type'] ?? 'RCC Roof',
            !empty($data['roof_area_sqft']) ? (float)$data['roof_area_sqft'] : 300,
            $data['bank_name'] ?? null,
            $data['bank_branch'] ?? null,
            $data['account_holder'] ?? null,
            $data['account_number'] ?? null,
            $data['ifsc_code'] ?? null,
            !empty($data['advisor_id']) ? (int)$data['advisor_id'] : null,
            $data['status'] ?? 'New',
            $id
        ]);

        // Sync with linked user account if exists
        $customer = self::findById($id);
        if ($customer && !empty($customer['user_id'])) {
            $fullName = trim($data['first_name'] . ' ' . $data['last_name']);
            Database::execute("UPDATE users SET full_name = ?, mobile = ?, email = COALESCE(?, email), updated_at = NOW() WHERE id = ?", [
                $fullName,
                $data['mobile'],
                $data['email'] ?? null,
                (int)$customer['user_id']
            ]);
        }

        // Sync with linked lead if exists
        if (!empty($data['proposed_solar_kw']) || !empty($data['advisor_id'])) {
            Database::execute("UPDATE leads SET 
                                proposed_capacity_kw = COALESCE(?, proposed_capacity_kw),
                                advisor_id = COALESCE(?, advisor_id),
                                updated_at = NOW()
                               WHERE customer_id = ?", [
                !empty($data['proposed_solar_kw']) ? (float)$data['proposed_solar_kw'] : null,
                !empty($data['advisor_id']) ? (int)$data['advisor_id'] : null,
                $id
            ]);
        }

        return $res;
    }

    public static function generateCustomerCode(): string
    {
        $next = Database::fetchOne("SELECT COUNT(*) as cnt FROM customers");
        $num = ($next ? (int)$next['cnt'] : 0) + 2001;
        return 'SVPL-CUST-' . $num;
    }
}
