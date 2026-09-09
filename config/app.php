<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Application Configuration - Configured for XAMPP (http://localhost/SVPL-Web) & Production
 */

// Auto-detect Base URL dynamically when hosted in XAMPP or custom virtual host
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? '') == 443 ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$basePath = ($scriptDir === '/' || $scriptDir === '.') ? '' : $scriptDir;

// If scriptDir ends with /public, remove it for clean root base path
if (substr($basePath, -7) === '/public') {
    $basePath = substr($basePath, 0, -7);
}

$detectedBaseUrl = $protocol . $host . $basePath;

return [
    'app_name' => 'Surya Vistaara Pvt. Ltd.',
    'app_short_name' => 'SVPL',
    'app_tagline' => 'PM Surya Ghar: Muft Bijli Yojana Network & Lead Management Platform',
    'promoter_for' => 'Dhwajja Solar India Pvt. Ltd.',
    'website' => 'https://www.suryavistaara.com',
    'domain' => 'PM Surya Ghar Rooftop Solar Network & Customer Lead Management',
    'coverage' => 'Odisha, India',
    'support_email' => 'support@suryavistaara.com',
    'support_phone' => '+91 674 295 4800',
    'head_office' => 'Plot No. 402, DLF Cybercity, Patia, Bhubaneswar, Odisha - 751024, India',
    'base_url' => getenv('APP_URL') ?: $detectedBaseUrl,
    'base_path' => $basePath,
    'timezone' => 'Asia/Kolkata',
    'currency' => 'INR',
    'currency_symbol' => '₹',
    'version' => '1.0.0',
    'debug' => true,
    
    // Storage Paths
    'storage_path' => dirname(__DIR__) . '/storage',
    'document_storage' => dirname(__DIR__) . '/storage/documents',
    'generated_storage' => dirname(__DIR__) . '/storage/generated',
    'log_storage' => dirname(__DIR__) . '/storage/logs',

    // Security & Upload Rules
    'max_upload_size' => 5 * 1024 * 1024, // 5MB
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'webp'],
    'allowed_mimes' => ['image/jpeg', 'image/png', 'application/pdf', 'image/webp'],
];
