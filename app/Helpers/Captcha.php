<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * High-Security Self-Contained CAPTCHA Generator & Verifier
 * Generates dynamic SVG challenges with noise vectors, text distortion, and session verification
 */

namespace App\Helpers;

class Captcha
{
    private const SESSION_KEY = 'svpl_captcha_code';
    private const SESSION_TIME = 'svpl_captcha_time';
    private const EXPIRY_SECONDS = 900; // 15 minutes

    /**
     * Generate a new random security code and store in session
     */
    public static function generate(int $length = 5): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Avoid ambiguous characters like 0, O, 1, I, L
        $chars = '23456789ABCDEFGHJKMNPQRSTUVWXYZ';
        $code = '';
        $maxIndex = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, $maxIndex)];
        }

        $_SESSION[self::SESSION_KEY] = $code;
        $_SESSION[self::SESSION_TIME] = time();

        return $code;
    }

    /**
     * Get current or newly generated captcha code
     */
    public static function getCode(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION[self::SESSION_KEY]) || (time() - ($_SESSION[self::SESSION_TIME] ?? 0)) > self::EXPIRY_SECONDS) {
            return self::generate();
        }

        return (string) $_SESSION[self::SESSION_KEY];
    }

    /**
     * Verify user input against session CAPTCHA code
     */
    public static function verify(?string $userInput): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($userInput) || empty($_SESSION[self::SESSION_KEY])) {
            return false;
        }

        // Check expiration
        $time = $_SESSION[self::SESSION_TIME] ?? 0;
        if ((time() - $time) > self::EXPIRY_SECONDS) {
            unset($_SESSION[self::SESSION_KEY], $_SESSION[self::SESSION_TIME]);
            return false;
        }

        $stored = (string) $_SESSION[self::SESSION_KEY];
        $input = strtoupper(trim($userInput));

        // Always invalidate code after verification attempt to prevent replay attacks
        unset($_SESSION[self::SESSION_KEY], $_SESSION[self::SESSION_TIME]);

        return ($input === strtoupper($stored));
    }

    /**
     * Render SVG Image directly to output stream
     */
    public static function render(): void
    {
        $code = self::generate();

        header('Content-Type: image/svg+xml');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo self::generateSvg($code);
        exit;
    }

    /**
     * Generate SVG Markup string
     */
    public static function generateSvg(string $code, int $width = 150, int $height = 42): string
    {
        $len = strlen($code);
        $charSpacing = ($width - 30) / max(1, $len);

        $colors = ['#0B2545', '#D97706', '#059669', '#1E40AF', '#B45309', '#047857', '#4338CA'];
        $bgPatterns = '';

        // Random background noise lines
        for ($i = 0; $i < 6; $i++) {
            $x1 = random_int(0, $width);
            $y1 = random_int(0, $height);
            $x2 = random_int(0, $width);
            $y2 = random_int(0, $height);
            $color = $colors[array_rand($colors)];
            $bgPatterns .= "<line x1='{$x1}' y1='{$y1}' x2='{$x2}' y2='{$y2}' stroke='{$color}' stroke-opacity='0.25' stroke-width='1.5'/>";
        }

        // Random background noise circles/dots
        for ($i = 0; $i < 15; $i++) {
            $cx = random_int(5, $width - 5);
            $cy = random_int(5, $height - 5);
            $r = random_int(1, 3);
            $color = $colors[array_rand($colors)];
            $bgPatterns .= "<circle cx='{$cx}' cy='{$cy}' r='{$r}' fill='{$color}' fill-opacity='0.2'/>";
        }

        // Render each character with rotation, color, and positioning
        $charSvg = '';
        for ($i = 0; $i < $len; $i++) {
            $char = htmlspecialchars($code[$i], ENT_QUOTES, 'UTF-8');
            $x = 15 + ($i * $charSpacing) + random_int(-3, 3);
            $y = 28 + random_int(-3, 3);
            $rot = random_int(-18, 18);
            $fontSize = random_int(21, 25);
            $color = $colors[random_int(0, count($colors) - 1)];

            $charSvg .= "<text x='{$x}' y='{$y}' font-family='Arial, Helvetica, sans-serif' font-size='{$fontSize}px' font-weight='800' fill='{$color}' transform='rotate({$rot}, {$x}, {$y})' letter-spacing='2'>{$char}</text>";
        }

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}" style="background-color: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 6px; user-select: none;">
    <rect width="100%" height="100%" fill="#F8FAFC" rx="6" />
    {$bgPatterns}
    {$charSvg}
</svg>
SVG;
    }
}
