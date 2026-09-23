<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Front Controller & Unified Request Handler (XAMPP & Production Ready)
 */

declare(strict_types=1);

// Load Constants & Configuration (Loads .env)
require_once dirname(__DIR__) . '/config/constants.php';
$appConfig = require dirname(__DIR__) . '/config/app.php';

// Error Handling Configuration (Controlled by .env APP_DEBUG or APP_ENV)
$appEnv = strtolower(getenv('APP_ENV') ?: 'production');
$appDebug = getenv('APP_DEBUG') === 'true' || $appEnv === 'development';

if ($appDebug) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

// Start secure session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
    ]);
}

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
    error_log("Database initialization notice: " . $t->getMessage());
}

// Load Routes
require_once dirname(__DIR__) . '/routes/web.php';

// Dispatch Request
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    Router::dispatch($uri, $method);
} catch (\Throwable $e) {
    error_log("Unhandled Application Exception: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    if ($appDebug) {
        echo "<div style='font-family: sans-serif; padding: 25px; background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 8px; margin: 20px;'>";
        echo "<h3 style='margin-top:0;'>❌ Application Error (Debug Mode Active)</h3>";
        echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line " . $e->getLine() . ")</p>";
        echo "<pre style='background: #fff; padding: 15px; border-radius: 4px; overflow: auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        echo "</div>";
    } else {
        http_response_code(500);
        echo "<div style='font-family: sans-serif; text-align: center; padding: 60px 20px;'>";
        echo "<h2 style='color: #0B2545;'>500 — Server Configuration Error</h2>";
        echo "<p style='color: #64748b;'>An internal error occurred. Please check your database settings in .env.</p>";
        echo "</div>";
    }
}
