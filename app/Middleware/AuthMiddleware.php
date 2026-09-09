<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Auth Middleware
 */

namespace App\Middleware;

use App\Helpers\Response;

class AuthMiddleware
{
    public function handle(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                Response::error('Session expired or unauthorized. Please log in.', [], 401);
            }
            Response::redirect('/login');
            return false;
        }

        return true;
    }
}
