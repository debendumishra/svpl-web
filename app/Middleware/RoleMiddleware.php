<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Role-Based Access Control Middleware
 */

namespace App\Middleware;

use App\Helpers\Response;

class RoleMiddleware
{
    private array $allowedRoles;

    public function __construct(...$roles)
    {
        $this->allowedRoles = $roles;
    }

    public function handle(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            Response::redirect('/login');
            return false;
        }

        $userRole = $_SESSION['user_role'] ?? '';

        // SUPER_ADMIN has global access across all panels
        if ($userRole === 'SUPER_ADMIN') {
            return true;
        }

        if (!empty($this->allowedRoles) && !in_array($userRole, $this->allowedRoles, true)) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                Response::error('Access denied for this role.', [], 403);
            }
            Response::forbidden('You do not have permission to access this resource.');
            return false;
        }

        return true;
    }
}
