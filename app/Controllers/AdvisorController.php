<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Controller - Advisor Portal, Downline Network, Customers, Leads, ID Card & Wallet
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Services\AuthService;
use App\Models\Advisor;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Genealogy;
use App\Models\Wallet;
use App\Models\Commission;
use App\Services\GenealogyService;
use App\Services\DocumentGenerator;

class AdvisorController
{
    public function dashboard(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);

        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        $wallet = Wallet::getByUserId((int) $user['id']);
        $customers = Customer::getByAdvisorId((int) $advisor['id']);
        $leads = Lead::getByAdvisorId((int) $advisor['id']);
        $networkStats = GenealogyService::getNetworkStats((int) $advisor['id']);
        $recentCommissions = Commission::getByAdvisorId((int) $advisor['id']);

        Response::view('advisor/dashboard', [
            'pageTitle' => 'Advisor Dashboard — Surya Vistaara',
            'advisor' => $advisor,
            'wallet' => $wallet,
            'customers' => $customers,
            'leads' => $leads,
            'networkStats' => $networkStats,
            'recentCommissions' => $recentCommissions,
        ]);
    }

    public function myNetwork(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $downlines = Genealogy::getDownlines((int) $advisor['id'], 9);
        $stats = GenealogyService::getNetworkStats((int) $advisor['id']);

        Response::view('advisor/my_network', [
            'pageTitle' => 'My 9-Level Downline Network — SVPL',
            'advisor' => $advisor,
            'downlines' => $downlines,
            'stats' => $stats,
        ]);
    }

    public function myCustomers(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $customers = Customer::getByAdvisorId((int) $advisor['id']);

        Response::view('advisor/my_customers', [
            'pageTitle' => 'My Customer Installations — SVPL',
            'advisor' => $advisor,
            'customers' => $customers,
        ]);
    }

    public function leads(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $leads = Lead::getByAdvisorId((int) $advisor['id']);

        Response::view('advisor/leads', [
            'pageTitle' => 'My Rooftop Solar Leads — SVPL',
            'advisor' => $advisor,
            'leads' => $leads,
        ]);
    }

    public function wallet(): void
    {
        $user = AuthService::user();
        $wallet = Wallet::getByUserId((int) $user['id']);
        $transactions = Wallet::getTransactions((int) $user['id'], 50);

        Response::view('advisor/wallet', [
            'pageTitle' => 'My Wallet & Earnings — SVPL',
            'wallet' => $wallet,
            'transactions' => $transactions,
        ]);
    }

    public function idCard(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $qrUrl = DocumentGenerator::getQrCodeUrl(DocumentGenerator::getVerificationUrl('ADVISOR', $advisor['referral_code']));

        Response::view('advisor/id_card', [
            'pageTitle' => 'My Official Advisor ID Card — SVPL',
            'advisor' => $advisor,
            'qrUrl' => $qrUrl,
        ]);
    }

    public function showRegisterCustomer(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);

        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        Response::view('advisor/register_customer', [
            'pageTitle' => 'Register New Customer — SVPL Advisor',
            'advisor' => $advisor,
            'post' => [],
        ]);
    }

    public function registerCustomer(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);

        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        $post = $_POST;
        $mobile = trim($post['mobile'] ?? '');

        if (empty($post['first_name']) || empty($post['last_name']) || empty($mobile) || empty($post['consumer_number'])) {
            Response::view('advisor/register_customer', [
                'pageTitle' => 'Register New Customer — SVPL Advisor',
                'advisor' => $advisor,
                'error' => 'Please fill in Customer Name, Mobile Number, and DISCOM Consumer Number.',
                'post' => $post,
            ]);
            return;
        }

        \App\Helpers\Database::beginTransaction();
        try {
            $password = 'Solar@123';
            $existingUser = \App\Models\User::findByMobile($mobile);
            if ($existingUser) {
                $userId = (int) $existingUser['id'];
            } else {
                $userId = \App\Models\User::create([
                    'role' => 'CUSTOMER',
                    'email' => !empty($post['email']) ? trim($post['email']) : null,
                    'mobile' => $mobile,
                    'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                    'full_name' => trim($post['first_name'] . ' ' . $post['last_name']),
                    'is_active' => 1,
                ]);
            }

            // Generate Customer Code & Record
            $custCode = Customer::generateCustomerCode();
            $custId = Customer::create([
                'user_id' => $userId,
                'customer_code' => $custCode,
                'advisor_id' => (int) $advisor['id'],
                'first_name' => trim($post['first_name']),
                'last_name' => trim($post['last_name']),
                'mobile' => $mobile,
                'email' => !empty($post['email']) ? trim($post['email']) : null,
                'state' => 'Odisha',
                'district' => trim($post['district'] ?? $advisor['district'] ?? 'Khordha'),
                'block' => trim($post['block'] ?? 'Bhubaneswar'),
                'gram_panchayat' => trim($post['gram_panchayat'] ?? ''),
                'village' => trim($post['village'] ?? ''),
                'pincode' => trim($post['pincode'] ?? '751020'),
                'address_line' => trim($post['address_line'] ?? ''),
                'discom_name' => trim($post['discom_name'] ?? 'TPCODL'),
                'consumer_number' => trim($post['consumer_number']),
                'sanctioned_load_kw' => (float) ($post['sanctioned_load_kw'] ?? 2.0),
                'proposed_solar_kw' => (float) ($post['proposed_solar_kw'] ?? 3.0),
                'monthly_avg_bill' => (float) ($post['monthly_avg_bill'] ?? 2500),
                'roof_type' => trim($post['roof_type'] ?? 'RCC Concrete Roof'),
                'status' => 'New',
            ]);

            // Create Lead in Pipeline linked to this advisor
            $leadCode = Lead::generateLeadCode();
            $leadId = Lead::create([
                'lead_code' => $leadCode,
                'customer_id' => $custId,
                'advisor_id' => (int) $advisor['id'],
                'lead_source' => 'Advisor Portal (' . $advisor['advisor_code'] . ')',
                'first_name' => trim($post['first_name']),
                'last_name' => trim($post['last_name']),
                'mobile' => $mobile,
                'email' => !empty($post['email']) ? trim($post['email']) : null,
                'state' => 'Odisha',
                'district' => trim($post['district'] ?? $advisor['district'] ?? 'Khordha'),
                'block' => trim($post['block'] ?? 'Bhubaneswar'),
                'gram_panchayat' => trim($post['gram_panchayat'] ?? ''),
                'pincode' => trim($post['pincode'] ?? '751020'),
                'discom_name' => trim($post['discom_name'] ?? 'TPCODL'),
                'consumer_number' => trim($post['consumer_number']),
                'proposed_capacity_kw' => (float) ($post['proposed_solar_kw'] ?? 3.0),
                'stage' => 'REGISTRATION',
                'status' => 'Application Submitted by Advisor ' . $advisor['advisor_code'],
            ]);

            // Process and Save Customer Documents
            $uploadDir = __DIR__ . '/../../public/uploads/documents/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $docMap = [
                'doc_electricity_bill' => ['type' => 'ELECTRICITY_BILL', 'title' => 'Electricity Bill (' . trim($post['consumer_number']) . ')'],
                'doc_aadhaar_card'     => ['type' => 'AADHAAR_CARD', 'title' => 'Customer Aadhaar Card'],
                'doc_pan_card'         => ['type' => 'PAN_CARD', 'title' => 'Customer PAN Card'],
                'doc_land_patta'       => ['type' => 'LAND_PATTA', 'title' => 'Land Patta / Property Ownership Document'],
                'doc_bank_passbook'    => ['type' => 'BANK_PASSBOOK', 'title' => 'Bank Passbook / Cancelled Cheque'],
                'doc_roof_photo'       => ['type' => 'ROOF_PHOTO', 'title' => 'Rooftop Solar Site Photo'],
            ];

            foreach ($docMap as $inputName => $meta) {
                if (!empty($_FILES[$inputName]['name']) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION));
                    $filename = strtolower($meta['type']) . '_' . $custId . '_' . time() . '_' . rand(100, 999) . '.' . $ext;
                    $targetPath = $uploadDir . $filename;
                    if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetPath)) {
                        \App\Models\Document::create([
                            'entity_type' => 'CUSTOMER',
                            'entity_id' => $custId,
                            'lead_id' => $leadId,
                            'document_type' => $meta['type'],
                            'document_title' => $meta['title'],
                            'file_path' => 'uploads/documents/' . $filename,
                            'file_size' => $_FILES[$inputName]['size'],
                            'mime_type' => $_FILES[$inputName]['type'],
                            'status' => 'Uploaded',
                        ]);
                    }
                }
            }

            // Log Audit
            \App\Models\AuditLog::log($user['id'], 'CUSTOMER_REGISTERED_BY_ADVISOR', 'CUSTOMER', $custId, "Customer {$custCode} registered by Advisor {$advisor['advisor_code']} ({$advisor['referral_code']})");

            \App\Helpers\Database::commit();

            Response::redirect('/advisor/customers?success=1&code=' . urlencode($custCode));
        } catch (\Throwable $t) {
            \App\Helpers\Database::rollBack();
            Response::view('advisor/register_customer', [
                'pageTitle' => 'Register New Customer — SVPL Advisor',
                'advisor' => $advisor,
                'error' => 'Registration failed: ' . $t->getMessage(),
                'post' => $post,
            ]);
        }
    }

    public function qrCode(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $refUrl = url('/advisor/register-customer');
        $qrUrl = DocumentGenerator::getQrCodeUrl(DocumentGenerator::getVerificationUrl('ADVISOR', $advisor['referral_code']));

        Response::view('advisor/qr_code', [
            'pageTitle' => 'My Advisor QR Code — SVPL',
            'advisor' => $advisor,
            'refUrl' => $refUrl,
            'qrUrl' => $qrUrl,
        ]);
    }
}
