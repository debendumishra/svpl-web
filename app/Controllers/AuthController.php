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
use App\Models\Payment;
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
        $paymentMethod = trim($post['payment_method'] ?? 'UPI');
        $transactionRef = trim($post['transaction_ref'] ?? '');
        $paymentDate = trim($post['payment_date'] ?? date('Y-m-d'));
        $paymentRemarks = trim($post['payment_remarks'] ?? '');

        // Validation
        if (empty($post['first_name']) || empty($post['last_name']) || empty($mobile) || empty($post['district']) || empty($post['block']) || empty($transactionRef)) {
            Response::view('public/register_advisor', [
                'pageTitle' => 'Join as Solar Advisor — SVPL',
                'error' => 'Please fill in all mandatory fields (*) including the ₹2,700 Onboarding Fee Transaction UTR/Ref number.',
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

        // Photo Processing (File Upload or Live Camera Capture)
        $photoUrl = null;
        $uploadDir = __DIR__ . '/../../public/uploads/advisors/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $base64Photo = trim($post['advisor_photo_base64'] ?? '');
        if (!empty($base64Photo) && preg_match('#^data:image/(\w+);base64,#i', $base64Photo, $typeMatch)) {
            $imageType = strtolower($typeMatch[1]);
            $base64Data = substr($base64Photo, strpos($base64Photo, ',') + 1);
            $decodedData = base64_decode($base64Data);
            if ($decodedData !== false) {
                $photoFileName = 'adv_photo_' . time() . '_' . rand(1000, 9999) . '.' . ($imageType === 'png' ? 'png' : 'jpg');
                if (file_put_contents($uploadDir . $photoFileName, $decodedData)) {
                    $photoUrl = 'uploads/advisors/' . $photoFileName;
                }
            }
        } elseif (!empty($_FILES['advisor_photo']['name']) && $_FILES['advisor_photo']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['advisor_photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $photoFileName = 'adv_photo_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['advisor_photo']['tmp_name'], $uploadDir . $photoFileName)) {
                    $photoUrl = 'uploads/advisors/' . $photoFileName;
                }
            }
        }

        Database::beginTransaction();
        try {
            // 1. Create User (inactive until payment confirmed by admin)
            $userId = User::create([
                'role' => 'ADVISOR',
                'email' => !empty($email) ? $email : null,
                'mobile' => $mobile,
                'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                'full_name' => trim($post['first_name'] . ' ' . $post['last_name']),
                'is_active' => 0,
            ]);

            // 2. Generate Advisor Codes
            $advCode = Advisor::generateAdvisorCode();
            $newRefCode = Advisor::generateReferralCode();

            // 3. Create Advisor Profile (PENDING_APPROVAL status)
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
                'photo_url' => $photoUrl,
                'blood_group' => trim($post['blood_group'] ?? 'O+ve'),
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
                'status' => 'PENDING_APPROVAL',
                'joining_fee' => 2700.00,
                'joining_fee_paid' => 0,
            ]);

            // 4. Save Document Record if photo uploaded
            if ($photoUrl) {
                \App\Models\Document::create([
                    'entity_type' => 'ADVISOR',
                    'entity_id' => $advId,
                    'document_type' => 'PASSPORT_PHOTO',
                    'document_title' => 'Official Advisor ID Card Photo',
                    'file_path' => $photoUrl,
                    'mime_type' => 'image/jpeg',
                    'status' => 'Uploaded',
                ]);
            }

            // 4. Create Payment record for Admin Verification
            $paymentId = Payment::create([
                'entity_type' => 'ADVISOR',
                'entity_id' => $advId,
                'purpose' => 'JOINING_FEE',
                'amount' => 2700.00,
                'payment_method' => $paymentMethod,
                'transaction_ref' => $transactionRef,
                'status' => 'PENDING',
                'payment_date' => $paymentDate,
            ]);

            // 5. Update Genealogy Closure Table
            Genealogy::addAdvisor($advId, $sponsorId);

            // 6. Initialize Wallet
            Wallet::create([
                'user_id' => $userId,
                'balance' => 0.00,
                'total_earned' => 0.00,
                'total_withdrawn' => 0.00,
                'pending_clearance' => 0.00,
            ]);

            // 7. Log Audit Trail
            AuditLog::log($userId, 'ADVISOR_REGISTERED', 'ADVISOR', $advId, "Advisor {$advCode} registered with fee ₹2,700 pending verification (UTR: {$transactionRef})");

            Database::commit();

            // Render Awaiting Verification screen with details
            $advData = Advisor::findById($advId);
            Response::view('public/advisor_registered_pending', [
                'pageTitle' => 'Registration Submitted — Awaiting Verification — SVPL',
                'advisor' => $advData,
                'paymentMethod' => $paymentMethod,
                'transactionRef' => $transactionRef,
                'paymentDate' => $paymentDate,
                'paymentRemarks' => $paymentRemarks,
            ]);
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
        $user = AuthService::user();
        if ($user && ($user['role'] ?? '') === 'ADVISOR') {
            Response::redirect('/advisor/register-customer');
            return;
        }

        Response::view('public/login', [
            'pageTitle' => 'Advisor Login Required — SVPL',
            'error' => 'Customer registration is conducted exclusively through authorized SVPL Advisors. Please log in to your Advisor account to submit customer applications.',
        ]);
    }

    public function registerCustomer(): void
    {
        $user = AuthService::user();
        if ($user && ($user['role'] ?? '') === 'ADVISOR') {
            (new AdvisorController())->registerCustomer();
            return;
        }

        Response::redirect('/login');
    }

    public function validateReferralCode(): void
    {
        $code = trim($_GET['code'] ?? '');
        if (empty($code)) {
            Response::json(['valid' => false, 'message' => 'Please provide a referral code.']);
            return;
        }

        $adv = Advisor::findByReferralCode($code);
        if ($adv) {
            $fullName = trim(($adv['first_name'] ?? '') . ' ' . ($adv['last_name'] ?? ''));
            if (empty($fullName)) {
                $fullName = $adv['user_full_name'] ?? 'Authorized Advisor';
            }
            Response::json([
                'valid' => true,
                'advisor' => [
                    'code' => $adv['advisor_code'],
                    'referral_code' => $adv['referral_code'],
                    'name' => $fullName,
                    'district' => $adv['district'] ?? 'Odisha',
                    'block' => $adv['block'] ?? '',
                    'status' => $adv['status'] ?? 'ACTIVE',
                ]
            ]);
        } else {
            Response::json(['valid' => false, 'message' => 'No active advisor found matching "' . htmlspecialchars($code) . '".']);
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
