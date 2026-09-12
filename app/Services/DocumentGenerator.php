<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Document Generator & QR Code Verification Helper
 */

namespace App\Services;

use App\Helpers\Formatter;

class DocumentGenerator
{
    /**
     * Generate QR Code Data URL using public QR API (or fallback SVG)
     */
    public static function getQrCodeUrl(string $payload): string
    {
        return "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=" . urlencode($payload);
    }

    /**
     * Get Verification URL for any entity (Advisor ID card, Quotation, Receipt)
     */
    public static function getVerificationUrl(string $type, string $code): string
    {
        if (function_exists('id_card_verify_url')) {
            return id_card_verify_url($type, $code);
        }
        return "https://suryavistaara.com/verify?type=" . urlencode($type) . "&code=" . urlencode($code);
    }
}
