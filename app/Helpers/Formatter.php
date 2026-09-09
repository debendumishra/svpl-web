<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Formatter Helper - Currency, Dates, Privacy PII Masking, QR Utilities
 */

namespace App\Helpers;

class Formatter
{
    /**
     * Format amount into Indian Rupee format (e.g. ₹ 1,50,000.00)
     */
    public static function currency(float|int|string|null $amount, bool $withSymbol = true): string
    {
        $amount = (float)($amount ?? 0);
        $isNegative = $amount < 0;
        $amount = abs($amount);

        $exploded = explode('.', sprintf('%.2f', $amount));
        $integerPart = $exploded[0];
        $decimalPart = $exploded[1];

        $lastThree = substr($integerPart, -3);
        $otherNumbers = substr($integerPart, 0, -3);

        if ($otherNumbers !== '') {
            $lastThree = ',' . $lastThree;
        }

        $formattedInteger = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $otherNumbers) . $lastThree;
        $result = $formattedInteger . '.' . $decimalPart;

        if ($isNegative) {
            $result = '-' . $result;
        }

        return ($withSymbol ? '₹ ' : '') . $result;
    }

    /**
     * Format Date
     */
    public static function date(?string $date, string $format = 'd M Y'): string
    {
        if (empty($date)) {
            return '—';
        }
        $timestamp = strtotime($date);
        return $timestamp ? date($format, $timestamp) : '—';
    }

    /**
     * Format DateTime
     */
    public static function datetime(?string $datetime, string $format = 'd M Y, h:i A'): string
    {
        if (empty($datetime)) {
            return '—';
        }
        $timestamp = strtotime($datetime);
        return $timestamp ? date($format, $timestamp) : '—';
    }

    /**
     * Mask Aadhaar: 123456789012 -> XXXX XXXX 9012
     */
    public static function maskAadhaar(?string $aadhaar): string
    {
        if (empty($aadhaar)) return '—';
        $clean = preg_replace('/\D/', '', $aadhaar);
        if (strlen($clean) >= 4) {
            $lastFour = substr($clean, -4);
            return 'XXXX XXXX ' . $lastFour;
        }
        return 'XXXX XXXX XXXX';
    }

    /**
     * Mask PAN: ABCDE1234F -> XXXXX1234F
     */
    public static function maskPan(?string $pan): string
    {
        if (empty($pan)) return '—';
        $pan = strtoupper(trim($pan));
        if (strlen($pan) >= 10) {
            return 'XXXXX' . substr($pan, 5);
        }
        return 'XXXXXXXXXX';
    }

    /**
     * Mask Bank Account: 123456789012 -> XXXXXX9012
     */
    public static function maskAccount(?string $account): string
    {
        if (empty($account)) return '—';
        $clean = trim($account);
        $len = strlen($clean);
        if ($len > 4) {
            return str_repeat('X', max(4, $len - 4)) . substr($clean, -4);
        }
        return 'XXXXXX';
    }

    /**
     * Mask Phone: 9876543210 -> +91 XXXXX 43210
     */
    public static function maskPhone(?string $phone): string
    {
        if (empty($phone)) return '—';
        $clean = preg_replace('/\D/', '', $phone);
        if (strlen($clean) >= 10) {
            $lastFive = substr($clean, -5);
            return '+91 XXXXX ' . $lastFive;
        }
        return '+91 XXXXX XXXXX';
    }

    /**
     * Generate Quick QR URL using standard SVG/image provider
     */
    public static function getQrImageUrl(string $data, int $size = 200): string
    {
        $encoded = urlencode($data);
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encoded}&margin=2&format=svg";
    }

    /**
     * Sanitize string for safe output
     */
    public static function escape(?string $str): string
    {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}
