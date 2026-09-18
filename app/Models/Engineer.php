<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Engineer Model — Solar Site & Project Engineers Master & Dispatch Assignment
 */

namespace App\Models;

use App\Helpers\Database;

class Engineer
{
    /**
     * Get all engineers with optional filters and statistics
     */
    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT e.*, u.email as user_email, u.is_active as user_active,
                       (SELECT COUNT(*) FROM package_dispatches pd WHERE pd.engineer_id = e.id) as total_dispatches,
                       (SELECT COUNT(*) FROM package_dispatches pd WHERE pd.engineer_id = e.id AND pd.status = 'Dispatched') as pending_dispatches,
                       (SELECT COUNT(*) FROM package_dispatches pd WHERE pd.engineer_id = e.id AND pd.status = 'In Transit') as transit_dispatches,
                       (SELECT COUNT(*) FROM package_dispatches pd WHERE pd.engineer_id = e.id AND pd.status = 'Delivered') as completed_installations
                FROM engineers e
                LEFT JOIN users u ON e.user_id = u.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'ACTIVE') {
                $sql .= " AND e.is_active = 1";
            } elseif ($filters['status'] === 'INACTIVE') {
                $sql .= " AND e.is_active = 0";
            }
        }

        if (!empty($filters['district'])) {
            $sql .= " AND e.assigned_districts LIKE ?";
            $params[] = "%" . trim($filters['district']) . "%";
        }

        if (!empty($filters['search'])) {
            $term = "%" . trim($filters['search']) . "%";
            $sql .= " AND (e.full_name LIKE ? OR e.engineer_code LIKE ? OR e.mobile LIKE ? OR e.email LIKE ? OR e.designation LIKE ?)";
            $params = array_merge($params, [$term, $term, $term, $term, $term]);
        }

        $sql .= " ORDER BY e.is_active DESC, e.id ASC";

        return Database::fetchAll($sql, $params);
    }

    /**
     * Get list of active engineers for dropdown selection during dispatch
     */
    public static function getActive(): array
    {
        return Database::fetchAll(
            "SELECT e.*, 
                    (SELECT COUNT(*) FROM package_dispatches pd WHERE pd.engineer_id = e.id AND pd.status != 'Delivered') as active_assignments
             FROM engineers e 
             WHERE e.is_active = 1 
             ORDER BY e.full_name ASC"
        );
    }

    /**
     * Find engineer by ID
     */
    public static function findById(int $id): ?array
    {
        $sql = "SELECT e.*, u.email as user_email, u.mobile as user_mobile
                FROM engineers e
                LEFT JOIN users u ON e.user_id = u.id
                WHERE e.id = ?";
        return Database::fetchOne($sql, [$id]);
    }

    /**
     * Find engineer by User ID (for Engineer login session)
     */
    public static function findByUserId(int $userId): ?array
    {
        $sql = "SELECT e.*, u.email as user_email, u.mobile as user_mobile
                FROM engineers e
                JOIN users u ON e.user_id = u.id
                WHERE e.user_id = ?";
        return Database::fetchOne($sql, [$userId]);
    }

    /**
     * Create a new Engineer record and corresponding User login account
     */
    public static function create(array $data): int
    {
        $mobile = trim($data['mobile'] ?? '');
        $email = trim($data['email'] ?? '');
        $fullName = trim($data['full_name'] ?? '');
        $designation = trim($data['designation'] ?? 'Solar Installation Project Engineer');
        $qualification = trim($data['qualification'] ?? 'B.Tech Electrical / Renewable Energy');
        $districts = trim($data['assigned_districts'] ?? 'Khordha, Cuttack, Puri');
        $aadhaar = trim($data['aadhaar_number'] ?? '');
        $expYears = (float)($data['experience_years'] ?? 3.0);
        $password = !empty($data['password']) ? trim($data['password']) : 'Engineer@123';

        // Generate Engineer Code if empty
        $code = trim($data['engineer_code'] ?? '');
        if (empty($code)) {
            $lastId = Database::fetchOne("SELECT MAX(id) as max_id FROM engineers")['max_id'] ?? 0;
            $code = 'SVPL-ENG-' . str_pad((string)($lastId + 1), 3, '0', STR_PAD_LEFT);
        }

        // Check or create user login
        $user = Database::fetchOne("SELECT id FROM users WHERE mobile = ? OR (email IS NOT NULL AND email != '' AND email = ?)", [$mobile, $email]);
        $userId = null;
        if ($user) {
            $userId = (int) $user['id'];
            Database::query("UPDATE users SET role = 'ENGINEER', full_name = ?, designation = ? WHERE id = ?", [$fullName, $designation, $userId]);
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            Database::query("
                INSERT INTO users (role, email, mobile, password_hash, full_name, employee_code, designation, is_active, created_at)
                VALUES ('ENGINEER', ?, ?, ?, ?, ?, ?, 1, NOW())
            ", [
                $email ?: null,
                $mobile,
                $hash,
                $fullName,
                $code,
                $designation
            ]);
            $userId = (int) Database::lastInsertId();
        }

        $sql = "INSERT INTO engineers (
                    user_id, engineer_code, full_name, mobile, alt_mobile,
                    email, password_text, designation, qualification, assigned_districts,
                    aadhaar_number, experience_years, photo_url, is_active, created_at
                ) VALUES (
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, NOW()
                )";

        Database::query($sql, [
            $userId,
            $code,
            $fullName,
            $mobile,
            trim($data['alt_mobile'] ?? '') ?: null,
            $email ?: null,
            $password,
            $designation,
            $qualification,
            $districts,
            $aadhaar ?: null,
            $expYears,
            trim($data['photo_url'] ?? '') ?: null,
            isset($data['is_active']) ? (int)$data['is_active'] : 1
        ]);

        return (int) Database::lastInsertId();
    }

    /**
     * Update an existing Engineer record
     */
    public static function update(int $id, array $data): bool
    {
        $eng = self::findById($id);
        if (!$eng) return false;

        $fullName = trim($data['full_name'] ?? $eng['full_name']);
        $mobile = trim($data['mobile'] ?? $eng['mobile']);
        $email = trim($data['email'] ?? $eng['email']);
        $designation = trim($data['designation'] ?? $eng['designation']);
        $qualification = trim($data['qualification'] ?? $eng['qualification']);
        $districts = trim($data['assigned_districts'] ?? $eng['assigned_districts']);
        $aadhaar = trim($data['aadhaar_number'] ?? $eng['aadhaar_number']);
        $expYears = (float)($data['experience_years'] ?? $eng['experience_years']);
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : (int)$eng['is_active'];
        $newPass = !empty($data['password']) ? trim($data['password']) : null;

        $sql = "UPDATE engineers SET
                    full_name = ?,
                    mobile = ?,
                    alt_mobile = ?,
                    email = ?,
                    password_text = COALESCE(?, password_text),
                    designation = ?,
                    qualification = ?,
                    assigned_districts = ?,
                    aadhaar_number = ?,
                    experience_years = ?,
                    photo_url = COALESCE(?, photo_url),
                    is_active = ?
                WHERE id = ?";

        Database::query($sql, [
            $fullName,
            $mobile,
            trim($data['alt_mobile'] ?? '') ?: null,
            $email ?: null,
            $newPass,
            $designation,
            $qualification,
            $districts,
            $aadhaar ?: null,
            $expYears,
            !empty($data['photo_url']) ? trim($data['photo_url']) : null,
            $isActive,
            $id
        ]);

        // Sync with users table
        if (!empty($eng['user_id'])) {
            Database::query("
                UPDATE users SET 
                    full_name = ?, 
                    mobile = ?, 
                    email = COALESCE(?, email), 
                    designation = ?, 
                    is_active = ? 
                WHERE id = ?
            ", [
                $fullName,
                $mobile,
                $email ?: null,
                $designation,
                $isActive,
                $eng['user_id']
            ]);

            // If new password provided
            if (!empty($data['password'])) {
                $hash = password_hash(trim($data['password']), PASSWORD_DEFAULT);
                Database::query("UPDATE users SET password_hash = ? WHERE id = ?", [$hash, $eng['user_id']]);
            }
        }

        return true;
    }

    /**
     * Toggle active/inactive status
     */
    public static function toggleStatus(int $id): bool
    {
        $eng = self::findById($id);
        if (!$eng) return false;

        $newStatus = $eng['is_active'] == 1 ? 0 : 1;
        Database::query("UPDATE engineers SET is_active = ? WHERE id = ?", [$newStatus, $id]);
        if (!empty($eng['user_id'])) {
            Database::query("UPDATE users SET is_active = ? WHERE id = ?", [$newStatus, $eng['user_id']]);
        }
        return true;
    }

    /**
     * Delete engineer
     */
    public static function delete(int $id): bool
    {
        $eng = self::findById($id);
        if (!$eng) return false;

        // Check if assigned in any dispatches
        $hasDispatches = Database::fetchOne("SELECT COUNT(*) as cnt FROM package_dispatches WHERE engineer_id = ?", [$id]);
        if (!empty($hasDispatches['cnt']) && (int)$hasDispatches['cnt'] > 0) {
            // Soft deactivate
            self::toggleStatus($id);
            return false;
        }

        Database::query("DELETE FROM engineers WHERE id = ?", [$id]);
        if (!empty($eng['user_id'])) {
            Database::query("DELETE FROM users WHERE id = ?", [$eng['user_id']]);
        }
        return true;
    }

    /**
     * Get all dispatches assigned to a specific engineer
     */
    public static function getAssignedDispatches(int $engineerId, ?string $status = null): array
    {
        $sql = "SELECT pd.*, l.lead_code, l.proposed_capacity_kw, l.stage as lead_stage,
                       l.estimated_project_cost, l.customer_payable_amount,
                       c.customer_code, c.first_name as cust_first, c.last_name as cust_last, c.mobile as cust_mobile,
                       c.district as cust_dist, c.block as cust_block, c.gram_panchayat as cust_gp, c.village as cust_vil,
                       c.pincode as cust_pin, c.address_line as cust_address,
                       c.discom_name as cust_discom, c.consumer_number as cust_ca,
                       a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name, a.mobile as advisor_mobile,
                       p.title as package_title, p.brand as package_brand, p.capacity_kw as pkg_cap
                FROM package_dispatches pd
                LEFT JOIN leads l ON pd.lead_id = l.id
                LEFT JOIN customers c ON (pd.customer_id = c.id OR l.customer_id = c.id)
                LEFT JOIN advisors a ON (pd.advisor_id = a.id OR l.advisor_id = a.id)
                LEFT JOIN packages p ON l.package_id = p.id
                WHERE pd.engineer_id = ?";
        $params = [$engineerId];

        if (!empty($status) && $status !== 'ALL') {
            $sql .= " AND pd.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY (pd.status != 'Delivered') DESC, pd.id DESC";

        return Database::fetchAll($sql, $params);
    }

    /**
     * Get aggregate statistics for Engineer portal or admin dashboard
     */
    public static function getStats(int $engineerId = 0): array
    {
        if ($engineerId > 0) {
            $total = Database::fetchOne("SELECT COUNT(*) as cnt FROM package_dispatches WHERE engineer_id = ?", [$engineerId])['cnt'] ?? 0;
            $inTransit = Database::fetchOne("SELECT COUNT(*) as cnt FROM package_dispatches WHERE engineer_id = ? AND status IN ('Dispatched', 'In Transit', 'Out for Delivery')", [$engineerId])['cnt'] ?? 0;
            $delivered = Database::fetchOne("SELECT COUNT(*) as cnt FROM package_dispatches WHERE engineer_id = ? AND status = 'Delivered'", [$engineerId])['cnt'] ?? 0;
            $acknowledged = Database::fetchOne("SELECT COUNT(*) as cnt FROM package_dispatches WHERE engineer_id = ? AND customer_acknowledged = 1", [$engineerId])['cnt'] ?? 0;

            return [
                'total_assigned'   => (int)$total,
                'in_transit'       => (int)$inTransit,
                'delivered'        => (int)$delivered,
                'acknowledged'     => (int)$acknowledged,
            ];
        }

        $totalEng = Database::fetchOne("SELECT COUNT(*) as cnt FROM engineers")['cnt'] ?? 0;
        $activeEng = Database::fetchOne("SELECT COUNT(*) as cnt FROM engineers WHERE is_active = 1")['cnt'] ?? 0;
        $totalAssigned = Database::fetchOne("SELECT COUNT(*) as cnt FROM package_dispatches WHERE engineer_id IS NOT NULL")['cnt'] ?? 0;

        return [
            'total_engineers'  => (int)$totalEng,
            'active_engineers' => (int)$activeEng,
            'total_assigned'   => (int)$totalAssigned,
        ];
    }
}
