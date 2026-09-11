<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Front Controller & Unified Request Handler (XAMPP & Production Ready)
 */

declare(strict_types=1);

// Error Handling Configuration
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// Start secure session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
    ]);
}

// Load Constants & Configuration
require_once dirname(__DIR__) . '/config/constants.php';
$appConfig = require dirname(__DIR__) . '/config/app.php';

// Set Timezone
date_default_timezone_set($appConfig['timezone'] ?? 'Asia/Kolkata');

// Simple PSR-4 Style Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = dirname(__DIR__) . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Auto-check if DB exists, if not run installer once automatically
use App\Helpers\Database;
use App\Helpers\Router;

try {
    if (!Database::tableExists('users') || !Database::tableExists('settings')) {
        require_once dirname(__DIR__) . '/database/setup.php';
        \DatabaseSetup::run();
    }
} catch (\Throwable $t) {
    // If table check fails, let the router continue or handle appropriately
}

// Load Routes
require_once dirname(__DIR__) . '/routes/web.php';

// Dispatch Request
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

Router::dispatch($uri, $method);
