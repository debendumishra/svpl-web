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
        return Database::fetchOne(
            "SELECT * FROM users WHERE email = ? OR mobile = ?",
            [$identifier, $identifier]
        );
    }

    public static function findByMobile(string $mobile): ?array
    {
        return Database::fetchOne("SELECT * FROM users WHERE mobile = ?", [$mobile]);
    }

    public static function findByEmail(string $email): ?array
    {
        return Database::fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public static function create(array $data): int
    {
        $sql = "INSERT INTO users (role, email, mobile, password_hash, full_name, is_active, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        Database::query($sql, [
            $data['role'] ?? 'CUSTOMER',
            $data['email'] ?? null,
            $data['mobile'],
            $data['password_hash'],
            $data['full_name'],
            $data['is_active'] ?? 1,
        ]);
        return (int) Database::lastInsertId();
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
}
