<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Router Helper - Flexible URL Dispatcher with Regex parameters & XAMPP Subdirectory Auto-stripping
 */

namespace App\Helpers;

class Router
{
    private static array $routes = [];
    private static array $groupMiddleware = [];

    public static function get(string $path, $handler, array $middleware = []): void
    {
        self::addRoute('GET', $path, $handler, $middleware);
    }

    public static function post(string $path, $handler, array $middleware = []): void
    {
        self::addRoute('POST', $path, $handler, $middleware);
    }

    public static function any(string $path, $handler, array $middleware = []): void
    {
        self::addRoute('ANY', $path, $handler, $middleware);
    }

    public static function group(array $attributes, callable $callback): void
    {
        $previousMiddleware = self::$groupMiddleware;
        if (isset($attributes['middleware'])) {
            $middlewares = is_array($attributes['middleware']) ? $attributes['middleware'] : [$attributes['middleware']];
            self::$groupMiddleware = array_merge(self::$groupMiddleware, $middlewares);
        }

        $callback();

        self::$groupMiddleware = $previousMiddleware;
    }

    private static function addRoute(string $method, string $path, $handler, array $middleware = []): void
    {
        $allMiddleware = array_merge(self::$groupMiddleware, $middleware);
        self::$routes[] = [
            'method'     => $method,
            'path'       => '/' . trim($path, '/'),
            'handler'    => $handler,
            'middleware' => $allMiddleware,
        ];
    }

    public static function dispatch(string $uri, string $requestMethod): void
    {
        $parsedUri = parse_url($uri, PHP_URL_PATH) ?? '/';
        $cleanPath = '/' . trim($parsedUri, '/');

        // Step 1: Strip /public if present
        $cleanPath = preg_replace('#^/public(?:/|$)#i', '/', $cleanPath);

        // Step 2: Strip /svpl-web or /SVPL-Web or /svpl_web case-insensitively
        $cleanPath = preg_replace('#^/svpl[-_]?web(?:/|$)#i', '/', $cleanPath);

        // Step 3: Strip base_path_url() if still prefixed
        $base = base_path_url();
        if (!empty($base) && stripos($cleanPath, $base) === 0) {
            $cleanPath = substr($cleanPath, strlen($base));
        }

        // Step 4: Strip /index.php if present
        $cleanPath = preg_replace('#^/index\.php(?:/|$)#i', '/', $cleanPath);

        // Normalize to leading slash
        $cleanPath = '/' . trim($cleanPath, '/');

        foreach (self::$routes as $route) {
            if ($route['method'] !== 'ANY' && $route['method'] !== $requestMethod) {
                continue;
            }

            // Convert route pattern with {param} to regex
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $cleanPath, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Run Middlewares
                foreach ($route['middleware'] as $mw) {
                    if (is_callable($mw)) {
                        $mw();
                    } elseif (is_object($mw) && method_exists($mw, 'handle')) {
                        $res = $mw->handle();
                        if ($res === false) return;
                    } elseif (is_string($mw) && class_exists($mw)) {
                        $mwInstance = new $mw();
                        if (method_exists($mwInstance, 'handle')) {
                            $res = $mwInstance->handle();
                            if ($res === false) return;
                        }
                    }
                }

                // Execute Handler
                if (is_callable($route['handler'])) {
                    call_user_func_array($route['handler'], $params);
                    return;
                }

                if (is_array($route['handler'])) {
                    [$controllerClass, $method] = $route['handler'];
                    if (class_exists($controllerClass)) {
                        $controller = new $controllerClass();
                        if (method_exists($controller, $method)) {
                            call_user_func_array([$controller, $method], $params);
                            return;
                        }
                    }
                }

                if (is_string($route['handler']) && strpos($route['handler'], '@') !== false) {
                    [$class, $method] = explode('@', $route['handler']);
                    $controllerClass = "App\\Controllers\\" . $class;
                    if (class_exists($controllerClass)) {
                        $controller = new $controllerClass();
                        if (method_exists($controller, $method)) {
                            call_user_func_array([$controller, $method], $params);
                            return;
                        }
                    }
                }
            }
        }

        // If no matching route found
        Response::notFound("Page or endpoint not found: " . htmlspecialchars($cleanPath));
    }
}
