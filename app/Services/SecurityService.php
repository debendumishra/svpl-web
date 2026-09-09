<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Security Service & PII Protection
 */

namespace App\Services;

use App\Helpers\Formatter;

class SecurityService
{
    public static function maskAadhaar(?string $aadhaar): string
    {
        return Formatter::maskAadhaar($aadhaar);
    }

    public static function maskPan(?string $pan): string
    {
        return Formatter::maskPan($pan);
    }

    public static function maskBank(?string $acc): string
    {
        return Formatter::maskAccount($acc);
    }

    public static function sanitizeInput(array $data): array
    {
        $clean = [];
        foreach ($data as $k => $v) {
            if (is_string($v)) {
                $clean[$k] = trim(strip_tags($v));
            } elseif (is_array($v)) {
                $clean[$k] = self::sanitizeInput($v);
            } else {
                $clean[$k] = $v;
            }
        }
        return $clean;
    }
}
