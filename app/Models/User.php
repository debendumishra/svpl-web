<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * User Model
 */

namespace App\Models;

use App\Helpers\Database;

class User
{
    public static function findById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public static function findByEmailOrMobile(string $identifier): ?array
    {
        $identifier = trim($identifier);
        if (empty($identifier)) {
            return null;
        }

        // 1. Direct match on users email, mobile, or employee_code
        $user = Database::fetchOne(
            "SELECT * FROM users WHERE email = ? OR mobile = ? OR employee_code = ?",
            [$identifier, $identifier, $identifier]
        );
        if ($user) {
            return $user;
        }

        // 2. Match by advisor code or referral code in advisors table
        $adv = Database::fetchOne(
            "SELECT user_id FROM advisors WHERE advisor_code = ? OR referral_code = ?",
            [$identifier, $identifier]
        );
        if ($adv && !empty($adv['user_id'])) {
            return self::findById((int)$adv['user_id']);
        }

        // 3. Match by customer code in customers table
        $cust = Database::fetchOne(
            "SELECT user_id FROM customers WHERE customer_code = ?",
            [$identifier]
        );
        if ($cust && !empty($cust['user_id'])) {
            return self::findById((int)$cust['user_id']);
        }

        return null;
    }

    public static function findByMobile(string $mobile): ?array
    {
        return Database::fetchOne("SELECT * FROM users WHERE mobile = ?", [$mobile]);
    }

    public static function findByEmail(string $email): ?array
    {
        return Database::fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public static function findByEmployeeCode(string $code): ?array
    {
        $code = trim($code);
        if (empty($code)) {
            return null;
        }
        // Match employee_code directly
        $user = Database::fetchOne("SELECT * FROM users WHERE employee_code = ?", [$code]);
        if ($user) {
            return $user;
        }

        // Match SVPL-BOE-{id} or extract numeric suffix
        if (preg_match('/^SVPL-BOE-(\d+)$/i', $code, $m)) {
            $user = Database::fetchOne("SELECT * FROM users WHERE employee_code = ? OR id = ?", [$code, (int)$m[1]]);
            if ($user) {
                return $user;
            }
        }

        // Match by mobile or numeric ID
        if (is_numeric($code)) {
            return Database::fetchOne("SELECT * FROM users WHERE id = ? OR mobile = ?", [(int)$code, $code]);
        }

        return Database::fetchOne("SELECT * FROM users WHERE employee_code = ? OR mobile = ?", [$code, $code]);
    }

    public static function create(array $data): int
    {
        $sql = "INSERT INTO users (role, email, mobile, password_hash, full_name, employee_code, designation, jurisdiction, blood_group, photo_url, address, is_active, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        Database::execute($sql, [
            $data['role'] ?? 'CUSTOMER',
            $data['email'] ?? null,
            $data['mobile'],
            $data['password_hash'],
            $data['full_name'],
            $data['employee_code'] ?? null,
            $data['designation'] ?? 'Back Office Executive',
            $data['jurisdiction'] ?? null,
            $data['blood_group'] ?? null,
            $data['photo_url'] ?? null,
            $data['address'] ?? null,
            $data['is_active'] ?? 1,
        ]);
        return (int) Database::lastInsertId();
    }

    public static function updateBOE(int $userId, array $data): bool
    {
        $fields = [];
        $params = [];

        $allowed = ['full_name', 'email', 'mobile', 'designation', 'jurisdiction', 'blood_group', 'photo_url', 'address', 'is_active'];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $data)) {
                $fields[] = "`$f` = ?";
                $params[] = $data[$f];
            }
        }

        if (!empty($data['password_hash'])) {
            $fields[] = "`password_hash` = ?";
            $params[] = $data['password_hash'];
        }

        if (empty($fields)) {
            return false;
        }

        $params[] = $userId;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ? AND role = 'BOE'";
        return Database::execute($sql, $params);
    }

    public static function updatePassword(int $userId, string $newHash): bool
    {
        return Database::execute("UPDATE users SET password_hash = ? WHERE id = ?", [$newHash, $userId]);
    }

    public static function updateLastLogin(int $userId): bool
    {
        return Database::execute("UPDATE users SET last_login = NOW() WHERE id = ?", [$userId]);
    }

    public static function updateRole(int $userId, string $role): bool
    {
        return Database::execute("UPDATE users SET role = ? WHERE id = ?", [$role, $userId]);
    }

    public static function getBOEUsers(): array
    {
        return Database::fetchAll("SELECT * FROM users WHERE role = 'BOE' ORDER BY id DESC");
    }

    public static function generateBOECode(): string
    {
        $res = Database::fetchOne("SELECT COUNT(*) as cnt FROM users WHERE role = 'BOE'");
        $num = ($res ? (int)$res['cnt'] : 0) + 101;
        return 'SVPL-BOE-' . $num;
    }
}
