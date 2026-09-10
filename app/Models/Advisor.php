<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Model
 */

namespace App\Models;

use App\Helpers\Database;

class Advisor
{
    public static function findById(int $id): ?array
    {
        return Database::fetchOne("SELECT a.*, u.full_name as user_full_name, u.email as user_email, u.mobile as user_mobile 
                                   FROM advisors a 
                                   JOIN users u ON a.user_id = u.id 
                                   WHERE a.id = ?", [$id]);
    }

    public static function findByUserId(int $userId): ?array
    {
        return Database::fetchOne("SELECT a.*, u.full_name as user_full_name, u.email as user_email, u.mobile as user_mobile 
                                   FROM advisors a 
                                   JOIN users u ON a.user_id = u.id 
                                   WHERE a.user_id = ?", [$userId]);
    }

    public static function findByReferralCode(string $code): ?array
    {
        $code = trim($code);
        if (empty($code)) {
            return null;
        }
        return Database::fetchOne("SELECT a.*, u.full_name as user_full_name, u.mobile as user_mobile 
                                   FROM advisors a 
                                   JOIN users u ON a.user_id = u.id 
                                   WHERE a.referral_code = ? OR a.advisor_code = ? OR a.mobile = ? OR u.mobile = ?", [$code, $code, $code, $code]);
    }

    public static function create(array $data): int
    {
        $sql = "INSERT INTO advisors (
                    user_id, advisor_code, referral_code, sponsor_id, 
                    first_name, last_name, father_spouse_name, dob, gender, 
                    mobile, alt_mobile, email, state, district, block, 
                    gram_panchayat, village, pincode, address_line, 
                    aadhaar_number, pan_number, bank_name, bank_branch, 
                    account_holder, account_number, ifsc_code, status, 
                    joining_fee_paid, created_at
                ) VALUES (
                    ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, 
                    ?, ?, ?, ?, 
                    ?, ?, ?, ?, 
                    ?, NOW()
                )";

        Database::query($sql, [
            $data['user_id'],
            $data['advisor_code'],
            $data['referral_code'],
            $data['sponsor_id'] ?? null,
            $data['first_name'],
            $data['last_name'],
            $data['father_spouse_name'] ?? null,
            $data['dob'] ?? null,
            $data['gender'] ?? 'Male',
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
            $data['aadhaar_number'] ?? null,
            $data['pan_number'] ?? null,
            $data['bank_name'] ?? null,
            $data['bank_branch'] ?? null,
            $data['account_holder'] ?? null,
            $data['account_number'] ?? null,
            $data['ifsc_code'] ?? null,
            $data['status'] ?? 'NEW',
            $data['joining_fee_paid'] ?? 1,
        ]);

        return (int) Database::lastInsertId();
    }

    public static function getAll(int $limit = 100, int $offset = 0, ?string $search = null): array
    {
        $params = [];
        $where = "WHERE 1=1";
        if ($search) {
            $where .= " AND (a.advisor_code LIKE ? OR a.first_name LIKE ? OR a.last_name LIKE ? OR a.mobile LIKE ? OR a.district LIKE ?)";
            $term = "%{$search}%";
            $params = [$term, $term, $term, $term, $term];
        }

        $sql = "SELECT a.*, sp.advisor_code as sponsor_code, CONCAT(sp.first_name, ' ', sp.last_name) as sponsor_name,
                       w.balance as wallet_balance
                FROM advisors a
                LEFT JOIN advisors sp ON a.sponsor_id = sp.id
                LEFT JOIN wallets w ON a.user_id = w.user_id
                {$where}
                ORDER BY a.id DESC LIMIT {$limit} OFFSET {$offset}";

        return Database::fetchAll($sql, $params);
    }

    public static function updateStatus(int $advisorId, string $status): bool
    {
        return Database::execute("UPDATE advisors SET status = ?, updated_at = NOW() WHERE id = ?", [$status, $advisorId]);
    }

    public static function incrementDirectCustomerCount(int $advisorId): int
    {
        Database::execute("UPDATE advisors SET direct_customer_count = direct_customer_count + 1 WHERE id = ?", [$advisorId]);
        $adv = self::findById($advisorId);
        return $adv ? (int) $adv['direct_customer_count'] : 0;
    }

    public static function markQualified(int $advisorId): bool
    {
        return Database::execute("UPDATE advisors SET status = 'QUALIFIED', qualified_at = NOW() WHERE id = ?", [$advisorId]);
    }

    public static function generateAdvisorCode(): string
    {
        $next = Database::fetchOne("SELECT COUNT(*) as cnt FROM advisors");
        $num = ($next ? (int)$next['cnt'] : 0) + 1001;
        return 'SVPL-ADV-' . $num;
    }

    public static function generateReferralCode(): string
    {
        $next = Database::fetchOne("SELECT COUNT(*) as cnt FROM advisors");
        $num = ($next ? (int)$next['cnt'] : 0) + 1001;
        return 'SVPL' . $num;
    }
}
