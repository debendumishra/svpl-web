<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Auth Controller - Login, Registration (Advisor & Customer), Referral Verification
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Helpers\Csrf;
use App\Helpers\Captcha;
use App\Services\AuthService;
use App\Models\User;
use App\Models\Advisor;
use App\Models\Customer;
use App\Models\Genealogy;
use App\Models\Wallet;
use App\Models\Lead;
use App\Models\Setting;
use App\Models\AuditLog;
use App\Helpers\Database;

class AuthController
{
    public function showLogin(): void
    {
        if (AuthService::check()) {
            $user = AuthService::user();
            if (in_array($user['role'], ['SUPER_ADMIN', 'ADMIN', 'ACCOUNTS', 'OPERATIONS'])) {
                Response::redirect('/admin/dashboard');
            } elseif ($user['role'] === 'ADVISOR') {
                Response::redirect('/advisor/dashboard');
            } else {
                Response::redirect('/customer/dashboard');
            }
        }

        Response::view('public/login', [
            'pageTitle' => 'Account Login — Surya Vistaara Pvt. Ltd.',
        ]);
    }

    public function login(): void
    {
        $identifier = trim($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';
        $captcha = trim($_POST['captcha'] ?? '');

        if (empty($identifier) || empty($password)) {
            Response::view('public/login', [
                'pageTitle' => 'Account Login — SVPL',
                'error' => 'Please enter your Mobile number/Email and Password.',
                'oldIdentifier' => $identifier,
            ]);
            return;
        }

        // Verify CAPTCHA
        if (!Captcha::verify($captcha)) {
            Response::view('public/login', [
                'pageTitle' => 'Account Login — SVPL',
                'error' => 'Invalid or expired Security CAPTCHA code. Please enter the characters shown in the image.',
                'oldIdentifier' => $identifier,
            ]);
            return;
        }

        $res = AuthService::attempt($identifier, $password);
        if (!$res['success']) {
            Response::view('public/login', [
                'pageTitle' => 'Account Login — SVPL',
                'error' => $res['message'],
                'oldIdentifier' => $identifier,
            ]);
            return;
        }

        $role = $res['user']['role'];
        if (in_array($role, ['SUPER_ADMIN', 'ADMIN', 'ACCOUNTS', 'OPERATIONS'])) {
            Response::redirect('/admin/dashboard');
        } elseif ($role === 'ADVISOR') {
            Response::redirect('/advisor/dashboard');
        } else {
            Response::redirect('/customer/dashboard');
        }
    }

    public function logout(): void
    {
        AuthService::logout();
        Response::redirect('/login');
    }

    public function showRegisterAdvisor(): void
    {
        $ref = trim($_GET['ref'] ?? '');
        $sponsor = null;
        if (!empty($ref)) {
            $sponsor = Advisor::findByReferralCode($ref);
        }

        Response::view('public/register_advisor', [
            'pageTitle' => 'Join as Solar Advisor — SVPL Odisha Network',
            'sponsor' => $sponsor,
            'ref' => $ref,
        ]);
    }

    public function registerAdvisor(): void
    {
        $post = $_POST;
        $mobile = trim($post['mobile'] ?? '');
        $email = trim($post['email'] ?? '');
        $password = $post['password'] ?? 'Password@123';
        $refCode = trim($post['referral_code'] ?? '');
        $captcha = trim($post['captcha'] ?? '');

        // Validation
        if (empty($post['first_name']) || empty($post['last_name']) || empty($mobile) || empty($post['district']) || empty($post['block'])) {
            Response::view('public/register_advisor', [
                'pageTitle' => 'Join as Solar Advisor — SVPL',
                'error' => 'Please fill in all mandatory fields (*).',
                'post' => $post,
            ]);
            return;
        }

        // CAPTCHA verification
        if (!Captcha::verify($captcha)) {
            Response::view('public/register_advisor', [
                'pageTitle' => 'Join as Solar Advisor — SVPL',
                'error' => 'Invalid or expired Security CAPTCHA code. Please enter the characters shown in the image.',
                'post' => $post,
            ]);
            return;
        }

        if (User::findByMobile($mobile)) {
            Response::view('public/register_advisor', [
                'pageTitle' => 'Join as Solar Advisor — SVPL',
                'error' => 'This mobile number is already registered. Please login.',
                'post' => $post,
            ]);
            return;
        }

        // Sponsor Verification
        $sponsorId = null;
        if (!empty($refCode)) {
            $sp = Advisor::findByReferralCode($refCode);
            if ($sp) {
                $sponsorId = (int) $sp['id'];
            }
        }

        Database::beginTransaction();
        try {
            // 1. Create User
            $userId = User::create([
                'role' => 'ADVISOR',
                'email' => !empty($email) ? $email : null,
                'mobile' => $mobile,
                'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                'full_name' => trim($post['first_name'] . ' ' . $post['last_name']),
                'is_active' => 1,
            ]);

            // 2. Generate Advisor Codes
            $advCode = Advisor::generateAdvisorCode();
            $newRefCode = Advisor::generateReferralCode();

            // 3. Create Advisor Profile
            $advId = Advisor::create([
                'user_id' => $userId,
                'advisor_code' => $advCode,
                'referral_code' => $newRefCode,
                'sponsor_id' => $sponsorId,
                'first_name' => trim($post['first_name']),
                'last_name' => trim($post['last_name']),
                'father_spouse_name' => trim($post['father_spouse_name'] ?? ''),
                'dob' => $post['dob'] ?? null,
                'gender' => $post['gender'] ?? 'Male',
                'mobile' => $mobile,
                'alt_mobile' => trim($post['alt_mobile'] ?? ''),
                'email' => !empty($email) ? $email : null,
                'state' => 'Odisha',
                'district' => trim($post['district']),
                'block' => trim($post['block']),
                'gram_panchayat' => trim($post['gram_panchayat'] ?? ''),
                'village' => trim($post['village'] ?? ''),
                'pincode' => trim($post['pincode'] ?? '751024'),
                'address_line' => trim($post['address_line'] ?? ''),
                'aadhaar_number' => trim($post['aadhaar_number'] ?? ''),
                'pan_number' => trim($post['pan_number'] ?? ''),
                'bank_name' => trim($post['bank_name'] ?? ''),
                'bank_branch' => trim($post['bank_branch'] ?? ''),
                'account_holder' => trim($post['account_holder'] ?? ''),
                'account_number' => trim($post['account_number'] ?? ''),
                'ifsc_code' => trim($post['ifsc_code'] ?? ''),
                'status' => 'ACTIVE',
                'joining_fee_paid' => 1,
            ]);

            // 4. Update Genealogy Closure Table
            Genealogy::addAdvisor($advId, $sponsorId);

            // 5. Initialize Wallet
            Wallet::create([
                'user_id' => $userId,
                'balance' => 0.00,
                'total_earned' => 0.00,
                'total_withdrawn' => 0.00,
                'pending_clearance' => 0.00,
            ]);

            // 6. Log Audit Trail
            AuditLog::log($userId, 'ADVISOR_REGISTERED', 'ADVISOR', $advId, "Advisor {$advCode} self-registered with sponsor " . ($sponsorId ? "#{$sponsorId}" : "DIRECT"));

            Database::commit();

            // Auto-login registered advisor
            AuthService::attempt($mobile, $password);
            Response::redirect('/advisor/dashboard?welcome=1');
        } catch (\Throwable $e) {
            Database::rollBack();
            Response::view('public/register_advisor', [
                'pageTitle' => 'Join as Solar Advisor — SVPL',
                'error' => 'Registration failed: ' . $e->getMessage(),
                'post' => $post,
            ]);
        }
    }

    public function showRegisterCustomer(): void
    {
        $ref = trim($_GET['ref'] ?? '');
        $advisor = null;
        if (!empty($ref)) {
            $advisor = Advisor::findByReferralCode($ref);
        }

        Response::view('public/register_customer', [
            'pageTitle' => 'Apply for PM Surya Ghar Rooftop Solar — SVPL Odisha',
            'advisor' => $advisor,
            'ref' => $ref,
        ]);
    }

    public function registerCustomer(): void
    {
        $post = $_POST;
        $mobile = trim($post['mobile'] ?? '');
        $refCode = trim($post['advisor_code'] ?? '');
        $captcha = trim($post['captcha'] ?? '');

        if (empty($post['first_name']) || empty($post['last_name']) || empty($mobile) || empty($post['consumer_number'])) {
            Response::view('public/register_customer', [
                'pageTitle' => 'Apply for PM Surya Ghar Solar — SVPL',
                'error' => 'Please provide Name, Mobile, and DISCOM Consumer Number.',
                'post' => $post,
            ]);
            return;
        }

        // CAPTCHA verification
        if (!Captcha::verify($captcha)) {
            Response::view('public/register_customer', [
                'pageTitle' => 'Apply for PM Surya Ghar Solar — SVPL',
                'error' => 'Invalid or expired Security CAPTCHA code. Please enter the characters shown in the image.',
                'post' => $post,
            ]);
            return;
        }

        $advisorId = null;
        if (!empty($refCode)) {
            $adv = Advisor::findByReferralCode($refCode);
            if ($adv) {
                $advisorId = (int) $adv['id'];
            }
        }

        Database::beginTransaction();
        try {
            $password = 'Solar@123';
            $userId = null;
            $existingUser = User::findByMobile($mobile);
            if ($existingUser) {
                $userId = (int) $existingUser['id'];
            } else {
                $userId = User::create([
                    'role' => 'CUSTOMER',
                    'email' => !empty($post['email']) ? trim($post['email']) : null,
                    'mobile' => $mobile,
                    'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                    'full_name' => trim($post['first_name'] . ' ' . $post['last_name']),
                    'is_active' => 1,
                ]);
            }

            $custCode = Customer::generateCustomerCode();
            $custId = Customer::create([
                'user_id' => $userId,
                'customer_code' => $custCode,
                'advisor_id' => $advisorId,
                'first_name' => trim($post['first_name']),
                'last_name' => trim($post['last_name']),
                'mobile' => $mobile,
                'email' => !empty($post['email']) ? trim($post['email']) : null,
                'state' => 'Odisha',
                'district' => trim($post['district'] ?? 'Khordha'),
                'block' => trim($post['block'] ?? 'Bhubaneswar'),
                'gram_panchayat' => trim($post['gram_panchayat'] ?? ''),
                'village' => trim($post['village'] ?? ''),
                'pincode' => trim($post['pincode'] ?? '751024'),
                'discom_name' => trim($post['discom_name'] ?? 'TPCODL'),
                'consumer_number' => trim($post['consumer_number']),
                'sanctioned_load_kw' => (float) ($post['sanctioned_load_kw'] ?? 2.0),
                'proposed_solar_kw' => (float) ($post['proposed_solar_kw'] ?? 2.0),
                'monthly_avg_bill' => (float) ($post['monthly_avg_bill'] ?? 1500),
                'status' => 'New',
            ]);

            // Create Lead in Pipeline
            $leadCode = Lead::generateLeadCode();
            $leadId = Lead::create([
                'lead_code' => $leadCode,
                'customer_id' => $custId,
                'advisor_id' => $advisorId,
                'lead_source' => $advisorId ? 'Advisor Referral' : 'Direct Portal Application',
                'first_name' => trim($post['first_name']),
                'last_name' => trim($post['last_name']),
                'mobile' => $mobile,
                'email' => !empty($post['email']) ? trim($post['email']) : null,
                'state' => 'Odisha',
                'district' => trim($post['district'] ?? 'Khordha'),
                'block' => trim($post['block'] ?? 'Bhubaneswar'),
                'gram_panchayat' => trim($post['gram_panchayat'] ?? ''),
                'pincode' => trim($post['pincode'] ?? '751024'),
                'discom_name' => trim($post['discom_name'] ?? 'TPCODL'),
                'consumer_number' => trim($post['consumer_number']),
                'proposed_capacity_kw' => (float) ($post['proposed_solar_kw'] ?? 2.0),
                'package_id' => (int) ($post['package_id'] ?? 2),
                'stage' => 'REGISTRATION',
                'status' => 'New Application Received',
            ]);

            Database::commit();

            AuthService::attempt($mobile, $password);
            Response::redirect('/customer/dashboard?welcome=1');
        } catch (\Throwable $t) {
            Database::rollBack();
            Response::view('public/register_customer', [
                'pageTitle' => 'Apply for PM Surya Ghar Solar — SVPL',
                'error' => 'Application submission failed: ' . $t->getMessage(),
                'post' => $post,
            ]);
        }
    }

    public function validateReferralCode(): void
    {
        $code = trim($_GET['code'] ?? '');
        $adv = Advisor::findByReferralCode($code);
        if ($adv) {
            Response::json([
                'valid' => true,
                'advisor' => [
                    'code' => $adv['advisor_code'],
                    'name' => $adv['first_name'] . ' ' . $adv['last_name'],
                    'district' => $adv['district'],
                    'status' => $adv['status'],
                ]
            ]);
        } else {
            Response::json(['valid' => false, 'message' => 'Invalid or inactive referral code.']);
        }
    }

    public function convertCustomer(): void
    {
        $user = AuthService::user();
        $custId = (int) ($user['customer_id'] ?? 0);
        $cust = Customer::findById($custId);

        if (!$cust) {
            Response::json(['status' => false, 'message' => 'Customer profile not found.']);
            return;
        }

        Database::beginTransaction();
        try {
            User::updateRole((int)$user['id'], 'ADVISOR');
            $advCode = Advisor::generateAdvisorCode();
            $refCode = Advisor::generateReferralCode();

            $advId = Advisor::create([
                'user_id' => $user['id'],
                'advisor_code' => $advCode,
                'referral_code' => $refCode,
                'sponsor_id' => $cust['advisor_id'] ?? null,
                'first_name' => $cust['first_name'],
                'last_name' => $cust['last_name'],
                'mobile' => $cust['mobile'],
                'email' => $cust['email'],
                'state' => $cust['state'],
                'district' => $cust['district'],
                'block' => $cust['block'],
                'gram_panchayat' => $cust['gram_panchayat'],
                'pincode' => $cust['pincode'],
                'status' => 'NEW',
                'joining_fee_paid' => 1,
            ]);

            Genealogy::insertNode($advId, $cust['advisor_id'] ?? null);
            Wallet::getByUserId((int)$user['id']);

            $_SESSION['user_role'] = 'ADVISOR';
            $_SESSION['advisor_id'] = $advId;
            $_SESSION['advisor_code'] = $advCode;

            Database::commit();
            Response::json(['status' => true, 'redirect' => url('/advisor/dashboard')]);
        } catch (\Throwable $t) {
            Database::rollBack();
            Response::json(['status' => false, 'message' => $t->getMessage()]);
        }
    }
}
