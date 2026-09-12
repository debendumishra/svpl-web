<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Controller - Executive Dashboard, Advisors, Customers, Leads, Commissions, Settings & Audits
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Helpers\Database;
use App\Models\Advisor;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Commission;
use App\Models\PackageDispatch;
use App\Models\Setting;
use App\Models\AuditLog;
use App\Models\Document;
use App\Models\Quotation;
use App\Models\JEReport;
use App\Models\Loan;
use App\Models\Subsidy;
use App\Models\Payment;
use App\Models\CompanyLedger;
use App\Models\User;
use App\Services\AuthService;

class AdminController
{
    public function dashboard(): void
    {
        // Platform Metric Statistics
        $totalAdvisors = Database::fetchOne("SELECT COUNT(*) as cnt FROM advisors")['cnt'] ?? 0;
        $totalCustomers = Database::fetchOne("SELECT COUNT(*) as cnt FROM customers")['cnt'] ?? 0;
        $totalLeads = Database::fetchOne("SELECT COUNT(*) as cnt FROM leads")['cnt'] ?? 0;
        $activeInstallations = Database::fetchOne("SELECT COUNT(*) as cnt FROM leads WHERE stage IN ('INSTALLATION_COMMENCED', 'INSTALLATION_COMPLETED')")['cnt'] ?? 0;
        $totalCommissions = Database::fetchOne("SELECT COALESCE(SUM(commission_amount), 0) as total FROM commissions")['total'] ?? 0;
        $totalSubsidies = Database::fetchOne("SELECT COALESCE(SUM(subsidy_amount), 0) as total FROM leads WHERE stage = 'SUBSIDY_RECEIVED'")['total'] ?? 0;
        $pendingPaymentsCount = Payment::countPendingAdvisorPayments();

        // Stage breakdown
        $stageStats = Database::fetchAll("SELECT stage, COUNT(*) as count FROM leads GROUP BY stage");

        // Recent Leads & Advisors
        $recentLeads = Lead::getAll(10);
        $recentAdvisors = Advisor::getAll(10);

        Response::view('admin/dashboard', [
            'pageTitle' => 'Executive Dashboard — SVPL Admin',
            'totalAdvisors' => (int) $totalAdvisors,
            'totalCustomers' => (int) $totalCustomers,
            'totalLeads' => (int) $totalLeads,
            'activeInstallations' => (int) $activeInstallations,
            'totalCommissions' => (float) $totalCommissions,
            'totalSubsidies' => (float) $totalSubsidies,
            'pendingPaymentsCount' => (int) $pendingPaymentsCount,
            'stageStats' => $stageStats,
            'recentLeads' => $recentLeads,
            'recentAdvisors' => $recentAdvisors,
        ]);
    }

    public function advisors(): void
    {
        $search = $_GET['q'] ?? null;
        $advisors = Advisor::getAll(100, 0, $search);
        Response::view('admin/advisors', [
            'pageTitle' => 'Advisor Management — SVPL Admin',
            'advisors' => $advisors,
            'search' => $search,
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function editAdvisor(string $id): void
    {
        $advisorId = (int)$id;
        $advisor = Advisor::findById($advisorId);
        if (!$advisor) {
            Response::notFound("Advisor #{$id} not found.");
            return;
        }

        $allAdvisors = Database::fetchAll("SELECT id, advisor_code, first_name, last_name, district FROM advisors WHERE id != ? ORDER BY first_name ASC", [$advisorId]);

        Response::view('admin/edit_advisor', [
            'pageTitle' => "Edit Advisor: {$advisor['advisor_code']} ({$advisor['first_name']} {$advisor['last_name']}) — SVPL Admin",
            'advisor' => $advisor,
            'allAdvisors' => $allAdvisors,
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function updateAdvisor(string $id): void
    {
        $advisorId = (int)$id;
        $advisor = Advisor::findById($advisorId);
        if (!$advisor) {
            Response::notFound("Advisor #{$id} not found.");
            return;
        }

        $post = $_POST;
        $photoUrl = null;

        // Process Live Camera Base64 Photo
        if (!empty($post['advisor_photo_base64']) && strpos($post['advisor_photo_base64'], 'data:image') === 0) {
            $base64Parts = explode(',', $post['advisor_photo_base64']);
            if (count($base64Parts) === 2) {
                $imageData = base64_decode($base64Parts[1]);
                if ($imageData !== false) {
                    $uploadDir = dirname(dirname(__DIR__)) . '/public/uploads/advisors/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $filename = 'adv_photo_' . time() . '_' . rand(1000, 9999) . '.jpg';
                    $targetPath = $uploadDir . $filename;
                    if (file_put_contents($targetPath, $imageData)) {
                        $photoUrl = 'public/uploads/advisors/' . $filename;
                    }
                }
            }
        }
        // Process File Upload Photo
        elseif (isset($_FILES['advisor_photo']) && $_FILES['advisor_photo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['advisor_photo'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ext, $allowedExts)) {
                $uploadDir = dirname(dirname(__DIR__)) . '/public/uploads/advisors/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $filename = 'adv_photo_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $targetPath = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $photoUrl = 'public/uploads/advisors/' . $filename;
                }
            }
        }

        $updateData = [
            'first_name' => trim($post['first_name'] ?? $advisor['first_name']),
            'last_name' => trim($post['last_name'] ?? $advisor['last_name']),
            'father_spouse_name' => trim($post['father_spouse_name'] ?? ''),
            'dob' => !empty($post['dob']) ? trim($post['dob']) : null,
            'gender' => trim($post['gender'] ?? 'Male'),
            'photo_url' => $photoUrl,
            'blood_group' => trim($post['blood_group'] ?? 'O+ve'),
            'mobile' => trim($post['mobile'] ?? $advisor['mobile']),
            'alt_mobile' => trim($post['alt_mobile'] ?? ''),
            'email' => trim($post['email'] ?? ''),
            'state' => trim($post['state'] ?? 'Odisha'),
            'district' => trim($post['district'] ?? $advisor['district']),
            'block' => trim($post['block'] ?? $advisor['block']),
            'gram_panchayat' => trim($post['gram_panchayat'] ?? $advisor['gram_panchayat']),
            'village' => trim($post['village'] ?? ''),
            'pincode' => trim($post['pincode'] ?? $advisor['pincode']),
            'address_line' => trim($post['address_line'] ?? ''),
            'aadhaar_number' => trim($post['aadhaar_number'] ?? ''),
            'pan_number' => strtoupper(trim($post['pan_number'] ?? '')),
            'bank_name' => trim($post['bank_name'] ?? ''),
            'bank_branch' => trim($post['bank_branch'] ?? ''),
            'account_holder' => trim($post['account_holder'] ?? ''),
            'account_number' => trim($post['account_number'] ?? ''),
            'ifsc_code' => strtoupper(trim($post['ifsc_code'] ?? '')),
            'status' => trim($post['status'] ?? $advisor['status']),
            'joining_fee_paid' => isset($post['joining_fee_paid']) ? (int)$post['joining_fee_paid'] : (int)$advisor['joining_fee_paid'],
            'sponsor_id' => !empty($post['sponsor_id']) ? (int)$post['sponsor_id'] : null,
        ];

        $res = Advisor::update($advisorId, $updateData);

        if ($res) {
            $admin = AuthService::user();
            AuditLog::log($admin ? (int)$admin['id'] : null, 'ADVISOR_UPDATE', "Admin updated advisor record #{$advisorId} ({$advisor['advisor_code']})");
            Response::redirect('/admin/advisors/' . $advisorId . '/edit?success=' . urlencode("Advisor {$advisor['advisor_code']} profile updated successfully!"));
        } else {
            Response::redirect('/admin/advisors/' . $advisorId . '/edit?error=' . urlencode("Failed to update advisor details."));
        }
    }

    public function customers(): void
    {
        $search = $_GET['q'] ?? null;
        $customers = Customer::getAll(100, 0, $search);
        Response::view('admin/customers', [
            'pageTitle' => 'Customer Registry — SVPL Admin',
            'customers' => $customers,
            'search' => $search,
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function editCustomer(string $id): void
    {
        $customerId = (int)$id;
        $customer = Customer::findById($customerId);
        if (!$customer) {
            Response::notFound("Customer #{$id} not found.");
            return;
        }

        $allAdvisors = Database::fetchAll("SELECT id, advisor_code, first_name, last_name, district FROM advisors ORDER BY first_name ASC");

        Response::view('admin/edit_customer', [
            'pageTitle' => "Edit Customer: {$customer['customer_code']} ({$customer['first_name']} {$customer['last_name']}) — SVPL Admin",
            'customer' => $customer,
            'allAdvisors' => $allAdvisors,
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function updateCustomer(string $id): void
    {
        $customerId = (int)$id;
        $customer = Customer::findById($customerId);
        if (!$customer) {
            Response::notFound("Customer #{$id} not found.");
            return;
        }

        $post = $_POST;
        $updateData = [
            'first_name' => trim($post['first_name'] ?? $customer['first_name']),
            'last_name' => trim($post['last_name'] ?? $customer['last_name']),
            'father_spouse_name' => trim($post['father_spouse_name'] ?? ''),
            'mobile' => trim($post['mobile'] ?? $customer['mobile']),
            'alt_mobile' => trim($post['alt_mobile'] ?? ''),
            'email' => trim($post['email'] ?? ''),
            'state' => trim($post['state'] ?? 'Odisha'),
            'district' => trim($post['district'] ?? $customer['district']),
            'block' => trim($post['block'] ?? $customer['block']),
            'gram_panchayat' => trim($post['gram_panchayat'] ?? $customer['gram_panchayat']),
            'village' => trim($post['village'] ?? ''),
            'pincode' => trim($post['pincode'] ?? $customer['pincode']),
            'address_line' => trim($post['address_line'] ?? ''),
            'discom_name' => trim($post['discom_name'] ?? 'TPCODL'),
            'consumer_number' => trim($post['consumer_number'] ?? ''),
            'sanctioned_load_kw' => !empty($post['sanctioned_load_kw']) ? (float)$post['sanctioned_load_kw'] : 2.0,
            'proposed_solar_kw' => !empty($post['proposed_solar_kw']) ? (float)$post['proposed_solar_kw'] : 3.0,
            'monthly_avg_bill' => !empty($post['monthly_avg_bill']) ? (float)$post['monthly_avg_bill'] : null,
            'roof_type' => trim($post['roof_type'] ?? 'RCC Roof'),
            'roof_area_sqft' => !empty($post['roof_area_sqft']) ? (float)$post['roof_area_sqft'] : 300,
            'bank_name' => trim($post['bank_name'] ?? ''),
            'bank_branch' => trim($post['bank_branch'] ?? ''),
            'account_holder' => trim($post['account_holder'] ?? ''),
            'account_number' => trim($post['account_number'] ?? ''),
            'ifsc_code' => strtoupper(trim($post['ifsc_code'] ?? '')),
            'advisor_id' => !empty($post['advisor_id']) ? (int)$post['advisor_id'] : null,
            'status' => trim($post['status'] ?? $customer['status']),
        ];

        $res = Customer::update($customerId, $updateData);

        if ($res) {
            $admin = AuthService::user();
            AuditLog::log($admin ? (int)$admin['id'] : null, 'CUSTOMER_UPDATE', "Admin updated customer record #{$customerId} ({$customer['customer_code']})");
            Response::redirect('/admin/customers/' . $customerId . '/edit?success=' . urlencode("Customer {$customer['customer_code']} profile updated successfully!"));
        } else {
            Response::redirect('/admin/customers/' . $customerId . '/edit?error=' . urlencode("Failed to update customer details."));
        }
    }

    public function leads(): void
    {
        $stage = $_GET['stage'] ?? null;
        $search = $_GET['q'] ?? null;
        $leads = Lead::getAll(100, 0, $stage, $search);

        Response::view('admin/leads', [
            'pageTitle' => 'Lead Pipeline — SVPL Admin',
            'leads' => $leads,
            'currentStage' => $stage,
            'search' => $search,
        ]);
    }

    public function leadDetail(string $id): void
    {
        $lead = Lead::findById((int) $id);
        if (!$lead) {
            Response::notFound("Lead record #{$id} not found.");
            return;
        }

        $documents = Document::getByLeadId((int) $id);
        $history = Lead::getHistory((int) $id);
        $quotation = Quotation::findByLeadId((int) $id);
        $jeReport = JEReport::findByLeadId((int) $id);
        $loan = Loan::findByLeadId((int) $id);
        $subsidy = Subsidy::findByLeadId((int) $id);

        Response::view('admin/lead_detail', [
            'pageTitle' => "Lead Details: {$lead['lead_code']} — SVPL",
            'lead' => $lead,
            'documents' => $documents,
            'history' => $history,
            'quotation' => $quotation,
            'jeReport' => $jeReport,
            'loan' => $loan,
            'subsidy' => $subsidy,
        ]);
    }

    public function networkTree(): void
    {
        $rootId = (int) ($_GET['root_id'] ?? 1);
        $rootAdvisor = Advisor::findById($rootId);
        if (!$rootAdvisor) {
            $rootAdvisor = Advisor::findById(1);
        }

        Response::view('admin/network_tree', [
            'pageTitle' => 'Multi-Level Network Genealogy Visualizer — SVPL',
            'rootAdvisor' => $rootAdvisor,
        ]);
    }

    public function commissions(): void
    {
        $commissions = Commission::getAll(100);
        $slabs = Commission::getPlanSlabs();

        Response::view('admin/commissions', [
            'pageTitle' => 'Commission & Hierarchy Payouts — SVPL Admin',
            'commissions' => $commissions,
            'slabs' => $slabs,
        ]);
    }

    public function payments(): void
    {
        $pendingPayments = Payment::getPendingAdvisorPayments();
        $recentPayments = Payment::getRecentPayments(50);
        $transactions = Database::fetchAll("SELECT wt.*, u.full_name, u.role, u.mobile FROM wallet_transactions wt JOIN users u ON wt.user_id = u.id ORDER BY wt.id DESC LIMIT 100");
        
        Response::view('admin/payments', [
            'pageTitle' => 'Payment Verification & Wallet Balances — SVPL Admin',
            'pendingPayments' => $pendingPayments,
            'recentPayments' => $recentPayments,
            'transactions' => $transactions,
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function confirmPayment(): void
    {
        $paymentId = (int) ($_POST['payment_id'] ?? 0);
        $admin = AuthService::user();
        $adminId = $admin ? (int) $admin['id'] : 1;

        if ($paymentId <= 0) {
            Response::redirect('/admin/payments?error=' . urlencode('Invalid payment record specified.'));
            return;
        }

        $res = Payment::confirmAdvisorPayment($paymentId, $adminId);
        if ($res) {
            Response::redirect('/admin/payments?success=' . urlencode('Payment of ₹2,700 confirmed successfully! Advisor account is now ACTIVE and can log in.'));
        } else {
            Response::redirect('/admin/payments?error=' . urlencode('Failed to confirm payment. Please try again.'));
        }
    }

    public function rejectPayment(): void
    {
        $paymentId = (int) ($_POST['payment_id'] ?? 0);
        $reason = trim($_POST['reason'] ?? 'Invalid UTR reference / Payment not credited');
        $admin = AuthService::user();
        $adminId = $admin ? (int) $admin['id'] : 1;

        if ($paymentId <= 0) {
            Response::redirect('/admin/payments?error=' . urlencode('Invalid payment record specified.'));
            return;
        }

        $res = Payment::rejectAdvisorPayment($paymentId, $adminId, $reason);
        if ($res) {
            Response::redirect('/admin/payments?success=' . urlencode('Advisor onboarding payment marked as REJECTED.'));
        } else {
            Response::redirect('/admin/payments?error=' . urlencode('Failed to reject payment. Please try again.'));
        }
    }

    public function dispatches(): void
    {
        $dispatches = PackageDispatch::getAll(100);
        $advisors = Advisor::getAll(100);
        $leads = Lead::getAll(100);

        Response::view('admin/dispatches', [
            'pageTitle' => 'Solar Equipment & Kit Dispatches — SVPL Admin',
            'dispatches' => $dispatches,
            'advisors' => $advisors,
            'leads' => $leads,
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function createDispatch(): void
    {
        $post = $_POST;
        $dispatchType = trim($post['dispatch_type'] ?? 'ADVISOR_KIT');
        $advisorId = !empty($post['advisor_id']) ? (int)$post['advisor_id'] : null;
        $leadId = !empty($post['lead_id']) ? (int)$post['lead_id'] : null;
        $trackingNumber = trim($post['tracking_number'] ?? '');
        $courierPartner = trim($post['courier_partner'] ?? 'SVPL Logistics Odisha');
        $itemsIncluded = trim($post['items_included'] ?? '');
        $deliveryAddress = trim($post['delivery_address'] ?? '');
        $status = trim($post['status'] ?? 'Dispatched');
        $dispatchDate = trim($post['dispatch_date'] ?? date('Y-m-d'));

        if (empty($trackingNumber)) {
            $trackingNumber = 'TRK-SVPL-' . date('Ymd') . '-' . rand(100, 999);
        }

        $id = PackageDispatch::create([
            'advisor_id' => $advisorId,
            'lead_id' => $leadId,
            'dispatch_type' => $dispatchType,
            'tracking_number' => $trackingNumber,
            'courier_partner' => $courierPartner,
            'items_included' => $itemsIncluded,
            'delivery_address' => $deliveryAddress,
            'status' => $status,
            'dispatch_date' => $dispatchDate,
        ]);

        if ($id) {
            Response::redirect('/admin/dispatches?success=' . urlencode("Dispatch record {$trackingNumber} created successfully!"));
        } else {
            Response::redirect('/admin/dispatches?error=' . urlencode("Failed to create dispatch record."));
        }
    }

    public function updateDispatchStatus(): void
    {
        $dispatchId = (int)($_POST['dispatch_id'] ?? 0);
        $status = trim($_POST['status'] ?? 'Delivered');
        $deliveryDate = !empty($_POST['delivery_date']) ? trim($_POST['delivery_date']) : ($status === 'Delivered' ? date('Y-m-d') : null);

        if ($dispatchId <= 0) {
            Response::redirect('/admin/dispatches?error=' . urlencode("Invalid dispatch record ID."));
            return;
        }

        $res = PackageDispatch::updateStatus($dispatchId, $status, $deliveryDate);
        if ($res) {
            Response::redirect('/admin/dispatches?success=' . urlencode("Dispatch #{$dispatchId} updated to {$status}."));
        } else {
            Response::redirect('/admin/dispatches?error=' . urlencode("Failed to update dispatch status."));
        }
    }

    public function reports(): void
    {
        Response::view('admin/reports', [
            'pageTitle' => 'Analytics & Export Reports — SVPL Admin',
        ]);
    }

    public function settings(): void
    {
        $settings = Setting::getAll();
        Response::view('admin/settings', [
            'pageTitle' => 'System Settings & Company Branding — ' . company_short_name() . ' Admin',
            'settings' => $settings,
            'successMsg' => $_SESSION['success_msg'] ?? (isset($_GET['saved']) ? 'Settings updated successfully!' : null),
            'errorMsg' => $_SESSION['error_msg'] ?? null,
        ]);
        unset($_SESSION['success_msg'], $_SESSION['error_msg']);
    }

    public function updateSettings(): void
    {
        $brandingDir = dirname(dirname(__DIR__)) . '/public/uploads/branding/';
        if (!is_dir($brandingDir)) {
            mkdir($brandingDir, 0777, true);
        }

        // 1. Process Text Settings
        $ignoredKeys = ['_csrf', '_csrf_token', 'company_logo_base64', 'remove_logo', 'remove_favicon'];
        foreach ($_POST as $key => $val) {
            if (!in_array($key, $ignoredKeys, true) && is_string($val)) {
                Setting::set($key, trim($val));
            }
        }

        // 2. Process Logo Removal
        if (!empty($_POST['remove_logo']) && $_POST['remove_logo'] === '1') {
            Setting::remove('company_logo');
        }

        // 3. Process Favicon Removal
        if (!empty($_POST['remove_favicon']) && $_POST['remove_favicon'] === '1') {
            Setting::remove('company_favicon');
        }

        // 4. Process Company Logo Upload
        // 4A. File Upload
        if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['company_logo'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExts = ['png', 'jpg', 'jpeg', 'webp', 'svg'];
            if (in_array($ext, $allowedExts, true)) {
                $filename = 'company_logo_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $brandingDir . $filename)) {
                    Setting::set('company_logo', 'public/uploads/branding/' . $filename, 'company');
                }
            }
        }
        // 4B. Base64 Logo / Camera Capture
        elseif (!empty($_POST['company_logo_base64']) && strpos($_POST['company_logo_base64'], 'data:image/') === 0) {
            $base64Str = $_POST['company_logo_base64'];
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Str, $type)) {
                $data = substr($base64Str, strpos($base64Str, ',') + 1);
                $decoded = base64_decode($data);
                if ($decoded !== false) {
                    $ext = strtolower($type[1]) === 'jpeg' ? 'jpg' : strtolower($type[1]);
                    $filename = 'company_logo_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    if (file_put_contents($brandingDir . $filename, $decoded)) {
                        Setting::set('company_logo', 'public/uploads/branding/' . $filename, 'company');
                    }
                }
            }
        }

        // 5. Process Favicon Upload
        if (isset($_FILES['company_favicon']) && $_FILES['company_favicon']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['company_favicon'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExts = ['png', 'ico', 'svg', 'jpg', 'jpeg', 'webp'];
            if (in_array($ext, $allowedExts, true)) {
                $filename = 'favicon_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $brandingDir . $filename)) {
                    Setting::set('company_favicon', 'public/uploads/branding/' . $filename, 'company');
                }
            }
        }

        $currentUser = AuthService::user();
        AuditLog::log($currentUser['id'] ?? 1, 'SETTINGS_UPDATE', 'SYSTEM', 1, "Updated platform configuration and company branding");

        $_SESSION['success_msg'] = "System settings & company branding updated successfully!";
        Response::redirect('/admin/settings');
    }

    public function ledger(): void
    {
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $entryType = $_GET['entry_type'] ?? null;
        $partyType = $_GET['party_type'] ?? null;
        $accountHead = $_GET['account_head'] ?? null;
        $paymentMode = $_GET['payment_mode'] ?? null;
        $search = $_GET['search'] ?? null;
        $activeTab = $_GET['tab'] ?? 'book'; // 'book', 'party', 'heads'

        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'entry_type' => $entryType,
            'party_type' => $partyType,
            'account_head' => $accountHead,
            'payment_mode' => $paymentMode,
            'search' => $search
        ];

        $entries = CompanyLedger::getAll($filters, 250);
        $summary = CompanyLedger::getFinancialSummary();
        $headSummary = CompanyLedger::getAccountHeadSummary($startDate, $endDate);
        $distinctParties = CompanyLedger::getDistinctParties();
        
        $partyStatement = null;
        $selectedParty = $_GET['party_name'] ?? null;
        if (!empty($selectedParty)) {
            $partyStatement = CompanyLedger::getPartyStatement($selectedParty, $startDate, $endDate);
        }

        $advisors = Advisor::getAll(100);
        $customers = Customer::getAll(100);

        Response::view('admin/ledger', [
            'pageTitle' => 'Company Financial Books & Account Ledger — SVPL',
            'entries' => $entries,
            'summary' => $summary,
            'headSummary' => $headSummary,
            'distinctParties' => $distinctParties,
            'partyStatement' => $partyStatement,
            'selectedParty' => $selectedParty,
            'advisors' => $advisors,
            'customers' => $customers,
            'filters' => $filters,
            'activeTab' => $activeTab,
            'accountHeads' => CompanyLedger::ACCOUNT_HEADS,
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function createLedgerEntry(): void
    {
        $post = $_POST;
        $entryType = strtoupper(trim($post['entry_type'] ?? 'RECEIPT'));
        $entryDate = trim($post['entry_date'] ?? date('Y-m-d'));
        $accountHead = trim($post['account_head'] ?? '');
        $partyType = trim($post['party_type'] ?? 'OTHER');
        $partyName = trim($post['party_name'] ?? '');
        $partyIdentifier = trim($post['party_identifier'] ?? '');
        $partyId = !empty($post['party_id']) ? (int)$post['party_id'] : null;
        $paymentMode = trim($post['payment_mode'] ?? 'UPI');
        $referenceNo = trim($post['reference_no'] ?? '');
        $amount = (float)($post['amount'] ?? 0);
        $narration = trim($post['narration'] ?? '');

        $admin = AuthService::user();
        $adminId = $admin ? (int)$admin['id'] : 1;

        if (empty($accountHead) || empty($partyName) || $amount <= 0) {
            Response::redirect('/admin/ledger?error=' . urlencode('Please fill in Account Head, Party Name, and a valid Amount (> 0).'));
            return;
        }

        $debit = ($entryType === 'PAYMENT') ? $amount : 0.00;
        $credit = ($entryType === 'RECEIPT') ? $amount : 0.00;

        $id = CompanyLedger::create([
            'entry_type' => $entryType,
            'entry_date' => $entryDate,
            'account_head' => $accountHead,
            'party_type' => $partyType,
            'party_id' => $partyId,
            'party_name' => $partyName,
            'party_identifier' => $partyIdentifier,
            'payment_mode' => $paymentMode,
            'reference_no' => $referenceNo,
            'debit_amount' => $debit,
            'credit_amount' => $credit,
            'narration' => $narration,
            'status' => 'CONFIRMED',
            'created_by_user_id' => $adminId,
        ]);

        if ($id) {
            AuditLog::log($adminId, 'LEDGER_ENTRY_RECORDED', 'LEDGER', $id, "Recorded {$entryType} of ₹" . number_format($amount, 2) . " under {$accountHead} for {$partyName}");
            Response::redirect('/admin/ledger?success=' . urlencode("Voucher entry recorded successfully with updated running balance!"));
        } else {
            Response::redirect('/admin/ledger?error=' . urlencode("Failed to record entry. Please try again."));
        }
    }

    public function exportLedgerCsv(): void
    {
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $entryType = $_GET['entry_type'] ?? null;
        $partyType = $_GET['party_type'] ?? null;
        $search = $_GET['search'] ?? null;

        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'entry_type' => $entryType,
            'party_type' => $partyType,
            'search' => $search
        ];

        $entries = CompanyLedger::getAll($filters, 1000);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=SVPL_Company_Ledger_' . date('Ymd_His') . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Voucher No', 'Date', 'Type', 'Account Head', 'Party Type', 'Party Name', 'Party Code / ID', 'Payment Mode', 'Reference / UTR', 'Debit Outflow (Rs)', 'Credit Inflow (Rs)', 'Running Balance (Rs)', 'Narration', 'Recorded By', 'Recorded At']);

        foreach ($entries as $e) {
            fputcsv($output, [
                $e['voucher_no'],
                $e['entry_date'],
                $e['entry_type'],
                $e['account_head'],
                $e['party_type'],
                $e['party_name'],
                $e['party_identifier'] ?? '',
                $e['payment_mode'],
                $e['reference_no'] ?? '',
                number_format((float)$e['debit_amount'], 2, '.', ''),
                number_format((float)$e['credit_amount'], 2, '.', ''),
                number_format((float)$e['running_balance'], 2, '.', ''),
                $e['narration'] ?? '',
                $e['created_by_name'] ?? 'System',
                $e['created_at']
            ]);
        }
        fclose($output);
        exit;
    }

    public function auditLogs(): void
    {
        $logs = AuditLog::getRecent(100);
        Response::view('admin/audit_logs', [
            'pageTitle' => 'Security Audit Logs — SVPL Admin',
            'logs' => $logs,
        ]);
    }

    public function boeManagement(): void
    {
        $boeList = User::getBOEUsers();
        
        foreach ($boeList as &$b) {
            $bId = (int)$b['id'];
            $b['assigned_customers_count'] = Database::fetchOne("SELECT COUNT(*) as cnt FROM customers WHERE assigned_boe_id = ?", [$bId])['cnt'] ?? 0;
            $b['completed_stage_count'] = Database::fetchOne(
                "SELECT COUNT(*) as cnt FROM customers c LEFT JOIN leads l ON l.customer_id = c.id WHERE c.assigned_boe_id = ? AND l.stage IN ('INSTALLATION_COMPLETED', 'JE_REPORT', 'SUBSIDY_APPLIED', 'SUBSIDY_RECEIVED')",
                [$bId]
            )['cnt'] ?? 0;
        }

        $nextCode = User::generateBOECode();

        Response::view('admin/boe_management', [
            'pageTitle' => 'Back Office Executive (BOE) Staff Management — SVPL Admin',
            'boeList' => $boeList,
            'nextCode' => $nextCode,
            'successMsg' => $_SESSION['success_msg'] ?? null,
            'errorMsg' => $_SESSION['error_msg'] ?? null,
        ]);

        unset($_SESSION['success_msg'], $_SESSION['error_msg']);
    }

    public function createBoe(): void
    {
        $fullName = trim($_POST['full_name'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $employeeCode = trim($_POST['employee_code'] ?? User::generateBOECode());
        $designation = trim($_POST['designation'] ?? 'Back Office Executive');
        $jurisdiction = trim($_POST['jurisdiction'] ?? '');
        $bloodGroup = trim($_POST['blood_group'] ?? 'O+ve');
        $address = trim($_POST['address'] ?? '');
        $password = trim($_POST['password'] ?? 'Password@123');

        if (empty($fullName) || empty($mobile) || empty($password)) {
            $_SESSION['error_msg'] = "Full Name, Mobile Number and Password are required fields.";
            Response::redirect('/admin/boe');
            return;
        }

        if (User::findByMobile($mobile)) {
            $_SESSION['error_msg'] = "User with mobile number {$mobile} already exists.";
            Response::redirect('/admin/boe');
            return;
        }

        if (!empty($email) && User::findByEmail($email)) {
            $_SESSION['error_msg'] = "User with email address {$email} already exists.";
            Response::redirect('/admin/boe');
            return;
        }

        // Photo Upload / Live Webcam Capture
        $photoUrl = null;
        $uploadDir = dirname(dirname(__DIR__)) . '/public/uploads/staff/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // 1. Live Webcam Base64
        if (!empty($_POST['boe_photo_base64']) && strpos($_POST['boe_photo_base64'], 'data:image/') === 0) {
            $base64Str = $_POST['boe_photo_base64'];
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Str, $type)) {
                $data = substr($base64Str, strpos($base64Str, ',') + 1);
                $decoded = base64_decode($data);
                if ($decoded !== false) {
                    $ext = strtolower($type[1]) === 'jpeg' ? 'jpg' : strtolower($type[1]);
                    $filename = 'boe_cam_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    if (file_put_contents($uploadDir . $filename, $decoded)) {
                        $photoUrl = 'public/uploads/staff/' . $filename;
                    }
                }
            }
        }
        // 2. File Upload
        elseif (isset($_FILES['boe_photo']) && $_FILES['boe_photo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['boe_photo'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ext, $allowedExts)) {
                $filename = 'boe_photo_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                    $photoUrl = 'public/uploads/staff/' . $filename;
                }
            }
        }

        $userId = User::create([
            'role' => 'BOE',
            'full_name' => $fullName,
            'mobile' => $mobile,
            'email' => $email ?: null,
            'employee_code' => $employeeCode,
            'designation' => $designation,
            'jurisdiction' => $jurisdiction ?: null,
            'blood_group' => $bloodGroup,
            'photo_url' => $photoUrl,
            'address' => $address ?: null,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'is_active' => 1,
        ]);

        $currentUser = AuthService::user();
        AuditLog::log($currentUser['id'] ?? 1, 'BOE_CREATE', 'USER', $userId, "Created BOE Staff: {$fullName} ({$employeeCode})");

        $_SESSION['success_msg'] = "Back Office Executive account created successfully! Code: {$employeeCode}, Password: {$password}";
        Response::redirect('/admin/boe');
    }

    public function updateBoe(string $id): void
    {
        $userId = (int)$id;
        $user = User::findById($userId);
        if (!$user || $user['role'] !== 'BOE') {
            $_SESSION['error_msg'] = "BOE Staff record not found.";
            Response::redirect('/admin/boe');
            return;
        }

        $fullName = trim($_POST['full_name'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $designation = trim($_POST['designation'] ?? $user['designation']);
        $jurisdiction = trim($_POST['jurisdiction'] ?? '');
        $bloodGroup = trim($_POST['blood_group'] ?? 'O+ve');
        $address = trim($_POST['address'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $isActive = isset($_POST['is_active']) ? (int)$_POST['is_active'] : (int)$user['is_active'];

        if (empty($fullName) || empty($mobile)) {
            $_SESSION['error_msg'] = "Full Name and Mobile Number are required.";
            Response::redirect('/admin/boe');
            return;
        }

        // Validate unique mobile/email if changed
        if ($mobile !== $user['mobile']) {
            $existing = User::findByMobile($mobile);
            if ($existing && (int)$existing['id'] !== $userId) {
                $_SESSION['error_msg'] = "User with mobile number {$mobile} already exists.";
                Response::redirect('/admin/boe');
                return;
            }
        }
        if (!empty($email) && $email !== ($user['email'] ?? '')) {
            $existing = User::findByEmail($email);
            if ($existing && (int)$existing['id'] !== $userId) {
                $_SESSION['error_msg'] = "User with email {$email} already exists.";
                Response::redirect('/admin/boe');
                return;
            }
        }

        // Photo Handling
        $photoUrl = $user['photo_url'];
        $uploadDir = dirname(dirname(__DIR__)) . '/public/uploads/staff/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // 1. Live Webcam Snapshot Base64
        if (!empty($_POST['boe_photo_base64']) && strpos($_POST['boe_photo_base64'], 'data:image/') === 0) {
            $base64Str = $_POST['boe_photo_base64'];
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Str, $type)) {
                $data = substr($base64Str, strpos($base64Str, ',') + 1);
                $decoded = base64_decode($data);
                if ($decoded !== false) {
                    $ext = strtolower($type[1]) === 'jpeg' ? 'jpg' : strtolower($type[1]);
                    $filename = 'boe_cam_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    if (file_put_contents($uploadDir . $filename, $decoded)) {
                        $photoUrl = 'public/uploads/staff/' . $filename;
                    }
                }
            }
        }
        // 2. File Upload
        elseif (isset($_FILES['boe_photo']) && $_FILES['boe_photo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['boe_photo'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ext, $allowedExts)) {
                $filename = 'boe_photo_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                    $photoUrl = 'public/uploads/staff/' . $filename;
                }
            }
        }

        $updateData = [
            'full_name' => $fullName,
            'mobile' => $mobile,
            'email' => $email ?: null,
            'designation' => $designation,
            'jurisdiction' => $jurisdiction ?: null,
            'blood_group' => $bloodGroup,
            'photo_url' => $photoUrl,
            'address' => $address ?: null,
            'is_active' => $isActive,
        ];

        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        User::updateBOE($userId, $updateData);

        $currentUser = AuthService::user();
        AuditLog::log($currentUser['id'] ?? 1, 'BOE_UPDATE', 'USER', $userId, "Updated BOE Staff: {$fullName} ({$user['employee_code']})");

        $_SESSION['success_msg'] = "BOE Executive [{$user['employee_code']}] updated successfully!";
        Response::redirect('/admin/boe');
    }

    public function toggleBoeStatus(string $id): void
    {
        $userId = (int)$id;
        $user = User::findById($userId);
        if ($user && $user['role'] === 'BOE') {
            $newStatus = ((int)$user['is_active']) ? 0 : 1;
            Database::execute("UPDATE users SET is_active = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $userId]);
            
            $currentUser = AuthService::user();
            AuditLog::log($currentUser['id'] ?? 1, 'BOE_STATUS_TOGGLE', 'USER', $userId, "Toggled BOE active status to {$newStatus}");
            $_SESSION['success_msg'] = "BOE account status updated successfully.";
        }
        Response::redirect('/admin/boe');
    }

    public function boeReports(): void
    {
        $boeUsers = User::getBOEUsers();

        $reportData = [];
        foreach ($boeUsers as $b) {
            $bId = (int)$b['id'];
            $customers = Database::fetchAll(
                "SELECT c.*, a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name,
                        l.stage as lead_stage, l.status as lead_status
                 FROM customers c
                 LEFT JOIN advisors a ON c.advisor_id = a.id
                 LEFT JOIN leads l ON l.customer_id = c.id
                 WHERE c.assigned_boe_id = ?
                 ORDER BY c.id DESC",
                [$bId]
            );

            $stageCounts = [
                'REGISTRATION' => 0,
                'DOCUMENTS' => 0,
                'GOVT_PORTAL' => 0,
                'LOAN' => 0,
                'INSTALLATION' => 0,
                'JE_REPORT' => 0,
                'SUBSIDY' => 0,
            ];

            foreach ($customers as $c) {
                $st = $c['lead_stage'] ?? 'REGISTRATION';
                if (strpos($st, 'LOAN') !== false) {
                    $stageCounts['LOAN']++;
                } elseif (strpos($st, 'INSTALLATION') !== false) {
                    $stageCounts['INSTALLATION']++;
                } elseif (strpos($st, 'SUBSIDY') !== false) {
                    $stageCounts['SUBSIDY']++;
                } elseif (isset($stageCounts[$st])) {
                    $stageCounts[$st]++;
                }
            }

            $reportData[] = [
                'boe' => $b,
                'total_assigned' => count($customers),
                'stage_counts' => $stageCounts,
                'customers' => $customers,
            ];
        }

        Response::view('admin/boe_reports', [
            'pageTitle' => 'BOE-Wise Customer Summary & Performance Reports — SVPL Admin',
            'reportData' => $reportData,
            'allBoeList' => $boeUsers,
            'successMsg' => $_SESSION['success_msg'] ?? null,
            'errorMsg' => $_SESSION['error_msg'] ?? null,
        ]);

        unset($_SESSION['success_msg'], $_SESSION['error_msg']);
    }

    public function reassignBoe(): void
    {
        $customerId = (int)($_POST['customer_id'] ?? 0);
        $newBoeId = !empty($_POST['new_boe_id']) ? (int)$_POST['new_boe_id'] : null;
        $remarks = trim($_POST['remarks'] ?? 'Case reassigned by Admin/Manager');

        $customer = Customer::findById($customerId);
        if (!$customer) {
            $_SESSION['error_msg'] = "Customer record not found.";
            Response::redirect($_SERVER['HTTP_REFERER'] ?? '/admin/boe/reports');
            return;
        }

        $oldBoeId = $customer['assigned_boe_id'] ? (int)$customer['assigned_boe_id'] : null;
        $oldBoe = $oldBoeId ? User::findById($oldBoeId) : null;
        $oldBoeName = $oldBoe ? ($oldBoe['full_name'] . ' (' . ($oldBoe['employee_code'] ?? 'BOE') . ')') : 'Unassigned Stage 1 Pool';

        $newBoe = $newBoeId ? User::findById($newBoeId) : null;
        $newBoeName = $newBoe ? ($newBoe['full_name'] . ' (' . ($newBoe['employee_code'] ?? 'BOE') . ')') : 'Unassigned Stage 1 Shared Pool';

        // Update assigned_boe_id on customer record
        Database::execute("UPDATE customers SET assigned_boe_id = ?, updated_at = NOW() WHERE id = ?", [
            $newBoeId,
            $customerId
        ]);

        $currentUser = AuthService::user();
        $adminId = $currentUser['id'] ?? 1;
        $adminName = $currentUser['full_name'] ?? 'Admin/Manager';
        $adminCode = $currentUser['employee_code'] ?? ($currentUser['role'] ?? 'ADMIN');
        $adminDesg = $currentUser['designation'] ?? ($currentUser['role'] ?? 'Executive Manager');

        // Fetch current lead stage & status
        $lead = Lead::findByCustomerId($customerId);
        $stage = $lead['stage'] ?? 'REGISTRATION';

        $auditRemarks = "Swapped assigned BOE from [{$oldBoeName}] to [{$newBoeName}]. Note: {$remarks}";

        // Record in customer_status_history audit trail
        Database::execute(
            "INSERT INTO customer_status_history 
             (customer_id, lead_id, from_stage, to_stage, to_status, user_id, user_name, user_code, user_designation, remarks, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            [
                $customerId,
                $lead['id'] ?? null,
                $stage,
                $stage,
                "BOE SWAPPED",
                $adminId,
                $adminName,
                $adminCode,
                $adminDesg,
                $auditRemarks
            ]
        );

        AuditLog::log($adminId, 'BOE_REASSIGNED', 'CUSTOMER', $customerId, $auditRemarks);

        $_SESSION['success_msg'] = "Customer application #{$customer['customer_code']} successfully reassigned to {$newBoeName}!";
        Response::redirect($_SERVER['HTTP_REFERER'] ?? '/admin/boe/reports');
    }

    public function withdrawals(): void
    {
        $statusFilter = $_GET['status'] ?? 'ALL';
        $withdrawals = \App\Models\WithdrawalRequest::getAll($statusFilter);

        $currentUser = AuthService::user();
        $isManager = strpos($_SERVER['REQUEST_URI'] ?? '', '/manager/') !== false;
        $prefix = $isManager ? '/manager' : '/admin';

        Response::view('admin/withdrawals', [
            'pageTitle' => 'Advisor Bank Withdrawals & Payouts — SVPL',
            'withdrawals' => $withdrawals,
            'statusFilter' => $statusFilter,
            'prefix' => $prefix,
            'successMsg' => $_SESSION['success_msg'] ?? null,
            'errorMsg' => $_SESSION['error_msg'] ?? null,
        ]);

        unset($_SESSION['success_msg'], $_SESSION['error_msg']);
    }

    public function approveWithdrawal(int $id): void
    {
        $currentUser = AuthService::user();
        $utrNumber = trim($_POST['utr_number'] ?? '');
        $remarks = trim($_POST['remarks'] ?? '');

        if (empty($utrNumber)) {
            $_SESSION['error_msg'] = "Bank UTR / Transaction reference number is required to approve & pay a withdrawal.";
            Response::redirect($_SERVER['HTTP_REFERER'] ?? '/admin/withdrawals');
            return;
        }

        $res = \App\Models\WithdrawalRequest::approveAndPay($id, (int)$currentUser['id'], $utrNumber, $remarks);

        if ($res) {
            $_SESSION['success_msg'] = "Withdrawal request approved and processed! Transaction UTR: {$utrNumber}";
        } else {
            $_SESSION['error_msg'] = "Failed to process withdrawal payout. Request may already be processed or invalid.";
        }

        Response::redirect($_SERVER['HTTP_REFERER'] ?? '/admin/withdrawals');
    }

    public function rejectWithdrawal(int $id): void
    {
        $currentUser = AuthService::user();
        $rejectionReason = trim($_POST['rejection_reason'] ?? 'Rejected by Administrator');

        $res = \App\Models\WithdrawalRequest::reject($id, (int)$currentUser['id'], $rejectionReason);

        if ($res) {
            $_SESSION['success_msg'] = "Withdrawal request rejected. Funds refunded to Advisor's wallet.";
        } else {
            $_SESSION['error_msg'] = "Failed to reject withdrawal request.";
        }

        Response::redirect($_SERVER['HTTP_REFERER'] ?? '/admin/withdrawals');
    }
}
