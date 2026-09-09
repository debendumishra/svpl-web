<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * CSRF Protection Middleware
 */

namespace App\Middleware;

use App\Helpers\Csrf;
use App\Helpers\Response;

class CsrfMiddleware
{
    public function handle(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

            if (!Csrf::validate($token)) {
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    Response::error('Invalid or expired CSRF security token.', [], 403);
                }
                Response::forbidden('Invalid or expired CSRF security token. Please refresh the page and try again.');
                return false;
            }
        }

        return true;
    }
}
