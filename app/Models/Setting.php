<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Settings, AuditLog & Location Models
 */

namespace App\Models;

use App\Helpers\Database;

class Setting
{
    private static array $cache = [];
    private static bool $allLoaded = false;

    public static function get(string $key, $default = null)
    {
        if (array_key_exists($key, self::$cache)) {
            return self::$cache[$key] !== null ? self::$cache[$key] : $default;
        }

        if (self::$allLoaded) {
            return $default;
        }

        try {
            $res = Database::fetchOne("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
            if ($res) {
                self::$cache[$key] = $res['setting_value'];
                return $res['setting_value'];
            }
        } catch (\Throwable $e) {
            return $default;
        }

        self::$cache[$key] = null;
        return $default;
    }

    public static function getAll(): array
    {
        try {
            $rows = Database::fetchAll("SELECT * FROM settings ORDER BY category ASC, setting_key ASC");
            $settings = [];
            foreach ($rows as $r) {
                $settings[$r['setting_key']] = $r['setting_value'];
                self::$cache[$r['setting_key']] = $r['setting_value'];
            }
            self::$allLoaded = true;
            return $settings;
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function set(string $key, string $value, string $category = 'general'): bool
    {
        self::$cache[$key] = $value;
        try {
            return Database::execute(
                "INSERT INTO settings (setting_key, setting_value, category, updated_at) VALUES (?, ?, ?, NOW())
                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = NOW()",
                [$key, $value, $category]
            );
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function remove(string $key): bool
    {
        unset(self::$cache[$key]);
        try {
            return Database::execute("DELETE FROM settings WHERE setting_key = ?", [$key]);
        } catch (\Throwable $e) {
            return false;
        }
    }
}

