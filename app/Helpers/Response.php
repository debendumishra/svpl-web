<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Response Helper - Handles JSON, HTML Views, Layout wrapping, Redirects, Errors
 */

namespace App\Helpers;

class Response
{
    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function success(string $message, array $data = [], int $statusCode = 200): void
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }

    public static function error(string $message, array $errors = [], int $statusCode = 400): void
    {
        self::json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $statusCode);
    }

    public static function redirect(string $path): void
    {
        $target = (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) ? $path : url($path);
        header("Location: " . $target);
        exit;
    }

    public static function view(string $viewPath, array $data = [], ?string $layout = null): void
    {
        $appConfig = require dirname(__DIR__, 2) . '/config/app.php';
        $cleanView = ltrim($viewPath, '/');
        $viewFile = dirname(__DIR__) . '/Views/' . $cleanView . '.php';

        if (!file_exists($viewFile)) {
            die("View file not found: " . htmlspecialchars($viewPath));
        }

        // Auto-detect layout if not explicitly provided
        if ($layout === null) {
            if (strpos($cleanView, 'admin/') === 0) {
                $layout = 'layouts/admin';
            } elseif (strpos($cleanView, 'advisor/') === 0) {
                $layout = 'layouts/advisor';
            } elseif (strpos($cleanView, 'customer/') === 0) {
                $layout = 'layouts/customer';
            } elseif (strpos($cleanView, 'printable/') === 0) {
                $layout = '';
            } else {
                $layout = 'layouts/main';
            }
        }

        // Extract variables to be accessible in the view
        extract($data);
        $app = $appConfig;
        $title = $pageTitle ?? ($data['title'] ?? 'Surya Vistaara Pvt. Ltd.');

        // Capture view content
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // Render with Layout if specified
        if (!empty($layout)) {
            $layoutFile = dirname(__DIR__) . '/Views/' . ltrim($layout, '/') . '.php';
            if (file_exists($layoutFile)) {
                include $layoutFile;
                return;
            }
        }

        echo $content;
    }

    public static function notFound(string $message = 'Page Not Found'): void
    {
        http_response_code(404);
        self::view('public/404', ['message' => $message], 'layouts/main');
        exit;
    }

    public static function forbidden(string $message = 'Access Denied'): void
    {
        http_response_code(403);
        self::view('public/403', ['message' => $message], 'layouts/main');
        exit;
    }

    public static function serverError(string $message = 'Internal Server Error'): void
    {
        http_response_code(500);
        self::view('public/500', ['message' => $message], 'layouts/main');
        exit;
    }
}
