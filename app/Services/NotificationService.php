<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Notification Service - In-App, WhatsApp Share link generator, SMS hooks
 */

namespace App\Services;

use App\Helpers\Database;

class NotificationService
{
    public static function send(int $userId, string $title, string $message, string $type = 'INFO', ?string $linkUrl = null): int
    {
        $sql = "INSERT INTO notifications (user_id, title, message, type, link_url, is_read) 
                VALUES (?, ?, ?, ?, ?, 0)";
        Database::execute($sql, [$userId, $title, $message, $type, $linkUrl]);
        return (int)Database::lastInsertId();
    }

    public static function getUnreadForUser(int $userId, int $limit = 10): array
    {
        return Database::fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY id DESC LIMIT ?",
            [$userId, $limit]
        );
    }

    public static function markAllRead(int $userId): bool
    {
        return Database::execute("UPDATE notifications SET is_read = 1 WHERE user_id = ?", [$userId]);
    }

    public static function getWhatsAppShareUrl(string $message, ?string $phone = null): string
    {
        $encodedMsg = urlencode($message);
        if ($phone) {
            $cleanPhone = preg_replace('/\D/', '', $phone);
            if (strlen($cleanPhone) === 10) {
                $cleanPhone = '91' . $cleanPhone;
            }
            return "https://api.whatsapp.com/send?phone={$cleanPhone}&text={$encodedMsg}";
        }
        return "https://api.whatsapp.com/send?text={$encodedMsg}";
    }
}
