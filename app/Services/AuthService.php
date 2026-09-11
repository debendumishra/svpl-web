<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Authentication & Session Service
 */

namespace App\Services;

use App\Models\User;
use App\Models\Advisor;
use App\Models\Customer;
use App\Models\AuditLog;

class AuthService
{
    public static function attempt(string $identifier, string $password): array
    {
        $user = User::findByEmailOrMobile($identifier);
        if (!$user) {
            return ['success' => false, 'message' => 'Invalid mobile number/email or password.'];
        }

        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid mobile number/email or password.'];
        }

        // Check advisor onboarding fee payment & approval status
        if ($user['role'] === 'ADVISOR') {
            $adv = Advisor::findByUserId((int) $user['id']);
            if ($adv) {
                if ($adv['status'] === 'PENDING_APPROVAL' || empty($adv['joining_fee_paid'])) {
                    return [
                        'success' => false, 
                        'message' => 'Your advisor registration (Fee: ₹2,700) is pending payment confirmation by SVPL Admin/Accounts. You will be able to log in as soon as your payment is verified.'
                    ];
                }
                if ($adv['status'] === 'PAYMENT_REJECTED') {
                    return [
                        'success' => false, 
                        'message' => 'Your onboarding payment verification was rejected. Please contact SVPL Admin support with your valid UTR / transaction receipt.'
                    ];
                }
                if (in_array($adv['status'], ['SUSPENDED', 'INACTIVE'])) {
                    return [
                        'success' => false, 
                        'message' => 'Your advisor account is currently suspended or inactive. Please contact SVPL administrator.'
                    ];
                }
            }
        }

        if (!(int)$user['is_active']) {
            return ['success' => false, 'message' => 'Your account is deactivated. Please contact SVPL administrator.'];
        }

        // Set session variables
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_mobile'] = $user['mobile'];

        // Load advisor/customer specific IDs
        if ($user['role'] === 'ADVISOR') {
            $adv = Advisor::findByUserId((int) $user['id']);
            if ($adv) {
                $_SESSION['advisor_id'] = (int) $adv['id'];
                $_SESSION['advisor_code'] = $adv['advisor_code'];
                $_SESSION['advisor_status'] = $adv['status'];
            }
        } elseif ($user['role'] === 'CUSTOMER') {
            $cust = Customer::findByUserId((int) $user['id']);
            if ($cust) {
                $_SESSION['customer_id'] = (int) $cust['id'];
                $_SESSION['customer_code'] = $cust['customer_code'];
            }
        } elseif ($user['role'] === 'BOE') {
            $_SESSION['employee_code'] = $user['employee_code'] ?? ('SVPL-BOE-' . $user['id']);
            $_SESSION['designation'] = $user['designation'] ?? 'Back Office Executive';
        }

        User::updateLastLogin((int) $user['id']);
        AuditLog::log((int) $user['id'], 'LOGIN_SUCCESS', 'USER', (int) $user['id'], 'User logged in successfully');

        return ['success' => true, 'user' => $user];
    }

    public static function user(): ?array
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'],
            'role' => $_SESSION['user_role'] ?? 'CUSTOMER',
            'name' => $_SESSION['user_name'] ?? '',
            'email' => $_SESSION['user_email'] ?? '',
            'mobile' => $_SESSION['user_mobile'] ?? '',
            'advisor_id' => $_SESSION['advisor_id'] ?? null,
            'advisor_code' => $_SESSION['advisor_code'] ?? null,
            'customer_id' => $_SESSION['customer_id'] ?? null,
            'employee_code' => $_SESSION['employee_code'] ?? null,
            'designation' => $_SESSION['designation'] ?? null,
        ];
    }

    public static function check(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function logout(): void
    {
        if (!empty($_SESSION['user_id'])) {
            AuditLog::log((int)$_SESSION['user_id'], 'LOGOUT', 'USER', (int)$_SESSION['user_id']);
        }
        $_SESSION = [];
        if (session_id() !== '' || headers_sent() === false) {
            session_destroy();
        }
    }
}
