<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Settings, AuditLog & Location Models
 */

namespace App\Models;

use App\Helpers\Database;

class Setting
{
    public static function get(string $key, $default = null)
    {
        $res = Database::fetchOne("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
        return $res ? $res['setting_value'] : $default;
    }

    public static function getAll(): array
    {
        $rows = Database::fetchAll("SELECT * FROM settings ORDER BY category ASC, setting_key ASC");
        $settings = [];
        foreach ($rows as $r) {
            $settings[$r['setting_key']] = $r['setting_value'];
        }
        return $settings;
    }

    public static function set(string $key, string $value): bool
    {
        return Database::execute(
            "INSERT INTO settings (setting_key, setting_value, updated_at) VALUES (?, ?, NOW())
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = NOW()",
            [$key, $value]
        );
    }
}

