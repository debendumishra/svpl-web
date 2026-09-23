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
use App\Models\Package;
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

        // Commission & Earnings Summaries
        $currentMonth = (int)date('n');
        $currentYear = (int)date('Y');
        $prevMonth = $currentMonth === 1 ? 12 : $currentMonth - 1;
        $prevYear = $currentMonth === 1 ? $currentYear - 1 : $currentYear;

        $commStats = \App\Helpers\Database::fetchOne(
            "SELECT 
                COALESCE(SUM(CASE WHEN status = 'PENDING' AND is_reversal = 0 THEN net_amount ELSE 0 END), 0) as pending_commission,
                COALESCE(SUM(CASE WHEN status IN ('APPROVED', 'CREDITED_TO_WALLET') AND is_reversal = 0 THEN net_amount ELSE 0 END), 0) as approved_commission,
                COALESCE(SUM(CASE WHEN commission_month = ? AND commission_year = ? AND status IN ('APPROVED', 'CREDITED_TO_WALLET') AND is_reversal = 0 THEN net_amount ELSE 0 END), 0) as current_month_earnings,
                COALESCE(SUM(CASE WHEN commission_month = ? AND commission_year = ? AND status IN ('APPROVED', 'CREDITED_TO_WALLET') AND is_reversal = 0 THEN net_amount ELSE 0 END), 0) as prev_month_earnings
             FROM commission_transactions 
             WHERE advisor_id = ?",
            [$currentMonth, $currentYear, $prevMonth, $prevYear, $advisor['id']]
        );

        // Personal Customer Metrics
        $personalCustomerCount = \App\Services\CommissionCalculationService::getPersonalCustomerCount((int)$advisor['id']);
        $monthlyCustomerCount = \App\Services\CommissionCalculationService::getMonthlyPersonalCustomerCount((int)$advisor['id'], $currentMonth, $currentYear);
        $maxEligibleLevel = \App\Services\CommissionRuleService::getMaxEligibleLevel($personalCustomerCount);

        // Pool Status
        $poolInfo = \App\Services\PoolBonusService::getAdvisorPoolInfo((int)$advisor['id']);
        $poolQualification = \App\Services\PoolBonusService::checkPoolQualification((int)$advisor['id']);

        // Lifetime Rewards Progress
        $rewardProgress = \App\Services\RewardService::getAdvisorRewards((int)$advisor['id']);

        // Check if Advisor has personal solar rooftop connection (converted from customer)
        $personalCustomer = self::getPersonalCustomerRecord((int)$user['id'], (int)$advisor['id'], $advisor['mobile'] ?? null);
        $personalLead = $personalCustomer ? Lead::findByCustomerCode($personalCustomer['customer_code']) : null;
        if ($personalCustomer) {
            $_SESSION['has_personal_solar'] = 1;
        }

        // Check Advisor Registration Fee payment status
        $joiningFeePaid = (int)($advisor['joining_fee_paid'] ?? 0);
        $pendingJoiningPayment = \App\Helpers\Database::fetchOne(
            "SELECT * FROM payments WHERE entity_type = 'ADVISOR' AND entity_id = ? AND purpose = 'JOINING_FEE' AND status = 'PENDING' ORDER BY id DESC LIMIT 1",
            [$advisor['id']]
        );
        $latestJoiningPayment = \App\Helpers\Database::fetchOne(
            "SELECT * FROM payments WHERE entity_type = 'ADVISOR' AND entity_id = ? AND purpose = 'JOINING_FEE' ORDER BY id DESC LIMIT 1",
            [$advisor['id']]
        );

        $paymentSuccess = $_SESSION['payment_success'] ?? null;
        $paymentError = $_SESSION['payment_error'] ?? null;
        unset($_SESSION['payment_success'], $_SESSION['payment_error']);

        Response::view('advisor/dashboard', [
            'pageTitle' => 'Advisor Command Center — Surya Vistaara',
            'advisor' => $advisor,
            'wallet' => $wallet,
            'customers' => $customers,
            'leads' => $leads,
            'networkStats' => $networkStats,
            'recentCommissions' => $recentCommissions,
            'commStats' => $commStats,
            'personalCustomerCount' => $personalCustomerCount,
            'monthlyCustomerCount' => $monthlyCustomerCount,
            'maxEligibleLevel' => $maxEligibleLevel,
            'poolInfo' => $poolInfo,
            'poolQualification' => $poolQualification,
            'rewardProgress' => $rewardProgress,
            'personalCustomer' => $personalCustomer,
            'personalLead' => $personalLead,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'joiningFeePaid' => $joiningFeePaid,
            'pendingJoiningPayment' => $pendingJoiningPayment,
            'latestJoiningPayment' => $latestJoiningPayment,
            'paymentSuccess' => $paymentSuccess,
            'paymentError' => $paymentError,
            'joiningFeeAmount' => advisor_joining_fee()
        ]);
    }

    public function myNetwork(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        if (!$advisor) {
            Response::redirect('/login');
            return;
        }
        $sponsor = Advisor::getImmediateSponsor(!empty($advisor['sponsor_id']) ? (int)$advisor['sponsor_id'] : null);
        $downlines = Genealogy::getDownlines((int) $advisor['id'], 9);
        $stats = GenealogyService::getNetworkStats((int) $advisor['id']);
        $poolInfo = \App\Services\PoolBonusService::getAdvisorPoolInfo((int)$advisor['id']);
        $poolMembers = \App\Services\PoolBonusService::getPoolTree();
        $nestedPoolTree = \App\Services\PoolBonusService::getNestedPoolTree();

        Response::view('advisor/my_network', [
            'pageTitle' => 'My 9-Level Downline Network — SVPL',
            'advisor' => $advisor,
            'sponsor' => $sponsor,
            'downlines' => $downlines,
            'stats' => $stats,
            'poolInfo' => $poolInfo,
            'poolMembers' => $poolMembers,
            'nestedPoolTree' => $nestedPoolTree
        ]);
    }

    public function rewards(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        $rewardProgress = \App\Services\RewardService::getAdvisorRewards((int)$advisor['id']);

        Response::view('advisor/rewards', [
            'pageTitle' => 'My Lifetime Performance Rewards — SVPL',
            'advisor' => $advisor,
            'rewardProgress' => $rewardProgress
        ]);
    }

    public function submitRewardClaim(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        $claimId = (int)($_POST['claim_id'] ?? 0);
        $claimType = trim($_POST['claim_type'] ?? 'CASH');
        $remarks = trim($_POST['remarks'] ?? '');

        $res = \App\Services\RewardService::submitClaim($claimId, (int)$advisor['id'], $claimType, $remarks);
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            Response::json($res);
            return;
        }

        Response::redirect('/advisor/rewards?msg=claimed');
    }

    public function myCustomers(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        $directCustomers = Customer::getDirectCustomersWithIssues((int) $advisor['id']);
        $teamCustomers = Customer::getTeamCustomersForAdvisor((int) $advisor['id']);

        Response::view('advisor/my_customers', [
            'pageTitle' => 'My Customer Installations & Team Network — SVPL',
            'advisor' => $advisor,
            'directCustomers' => $directCustomers,
            'teamCustomers' => $teamCustomers,
            'successMsg' => $_SESSION['success_msg'] ?? null,
            'errorMsg' => $_SESSION['error_msg'] ?? null,
        ]);

        unset($_SESSION['success_msg'], $_SESSION['error_msg']);
    }

    public function editCustomer(string $id): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $customerId = (int) $id;

        $customer = Customer::findById($customerId);
        if (!$customer) {
            Response::notFound("Customer application #{$id} not found.");
            return;
        }

        // Security / Privilege check: Only direct sponsor advisor can edit customer data
        if ((int)($customer['advisor_id'] ?? 0) !== (int)$advisor['id']) {
            $_SESSION['error_msg'] = "Access Denied: You can only edit applications of your directly registered customers.";
            Response::redirect('/advisor/customers');
            return;
        }

        $documents = Document::getByCustomerId($customerId);
        $auditHistory = Lead::getCustomerAuditHistory($customerId);
        $packages = Package::getAllActive();

        Response::view('advisor/edit_customer', [
            'pageTitle' => "Rectify Customer Application: {$customer['customer_code']} — SVPL Advisor",
            'advisor' => $advisor,
            'customer' => $customer,
            'documents' => $documents,
            'auditHistory' => $auditHistory,
            'packages' => $packages,
            'districts' => \App\Models\Location::getDistricts(),
            'successMsg' => $_SESSION['success_msg'] ?? null,
            'errorMsg' => $_SESSION['error_msg'] ?? null,
        ]);

        unset($_SESSION['success_msg'], $_SESSION['error_msg']);
    }

    public function updateCustomer(string $id): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $customerId = (int) $id;

        $customer = Customer::findById($customerId);
        if (!$customer) {
            Response::notFound("Customer application not found.");
            return;
        }

        // Security check
        if ((int)($customer['advisor_id'] ?? 0) !== (int)$advisor['id']) {
            $_SESSION['error_msg'] = "Access Denied: You can only edit applications of your directly registered customers.";
            Response::redirect('/advisor/customers');
            return;
        }

        $post = $_POST;
        $firstName = trim($post['first_name'] ?? '');
        $lastName = trim($post['last_name'] ?? '');
        $mobile = trim($post['mobile'] ?? '');
        $consumerNo = trim($post['consumer_number'] ?? '');

        if (empty($firstName) || empty($lastName) || empty($mobile) || empty($consumerNo)) {
            $_SESSION['error_msg'] = "Please fill in Customer Name, Mobile Number, and DISCOM Consumer Number.";
            Response::redirect("/advisor/customers/{$customerId}/edit");
            return;
        }

        $updateData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'father_husband_name' => trim($post['father_husband_name'] ?? ''),
            'dob' => !empty($post['dob']) ? $post['dob'] : null,
            'mobile' => $mobile,
            'alt_mobile' => trim($post['alt_mobile'] ?? ''),
            'email' => trim($post['email'] ?? ''),
            'state' => trim($post['state'] ?? 'Odisha'),
            'district' => trim($post['district'] ?? 'Khordha'),
            'block' => trim($post['block'] ?? ''),
            'gram_panchayat' => trim($post['gram_panchayat'] ?? ''),
            'village' => trim($post['village'] ?? ''),
            'pincode' => trim($post['pincode'] ?? '751024'),
            'address_line' => trim($post['address_line'] ?? ''),
            'discom_name' => trim($post['discom_name'] ?? 'TPCODL'),
            'consumer_number' => $consumerNo,
            'electricity_bill_mobile' => trim($post['electricity_bill_mobile'] ?? $mobile),
            'electricity_bill_dob' => !empty($post['electricity_bill_dob']) ? $post['electricity_bill_dob'] : null,
            'sanctioned_load_kw' => (float)($post['sanctioned_load_kw'] ?? 2.0),
            'proposed_solar_kw' => (float)($post['proposed_solar_kw'] ?? 3.0),
            'monthly_avg_bill' => (float)($post['monthly_avg_bill'] ?? 0),
            'roof_type' => trim($post['roof_type'] ?? 'RCC Roof'),
            'roof_area_sqft' => (float)($post['roof_area_sqft'] ?? 300),
            'bank_name' => trim($post['bank_name'] ?? ''),
            'bank_branch' => trim($post['bank_branch'] ?? ''),
            'account_holder' => trim($post['account_holder'] ?? ''),
            'account_number' => trim($post['account_number'] ?? ''),
            'ifsc_code' => trim($post['ifsc_code'] ?? ''),
            'advisor_id' => (int)$advisor['id'],
            'status' => $customer['status'] ?? 'New',
        ];

        Customer::update($customerId, $updateData);

        // Record rectification note in audit history
        $leadId = (int)($customer['lead_id'] ?? 0);
        $rectifyNote = trim($post['rectification_note'] ?? 'Customer details updated & rectified by sponsor advisor.');
        \App\Helpers\Database::execute(
            "INSERT INTO customer_status_history (customer_id, lead_id, from_stage, to_stage, from_status, to_status, changed_by_user_id, user_code, user_name, user_designation, remarks, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            [
                $customerId,
                $leadId,
                $customer['lead_stage'] ?? 'DOCUMENTS',
                $customer['lead_stage'] ?? 'DOCUMENTS',
                $customer['lead_status'] ?? 'Action Required',
                'Rectified by Advisor',
                (int)$user['id'],
                $advisor['advisor_code'],
                $advisor['first_name'] . ' ' . $advisor['last_name'],
                'Sponsor Advisor',
                $rectifyNote
            ]
        );

        $_SESSION['success_msg'] = "Customer application #{$customer['customer_code']} details successfully updated and submitted for BOE review.";
        Response::redirect("/advisor/customers/{$customerId}/edit");
    }

    public function customerDocuments(string $id): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $customerId = (int) $id;

        $customer = Customer::findById($customerId);
        if (!$customer) {
            Response::notFound("Customer application #{$id} not found.");
            return;
        }

        // Security check
        if ((int)($customer['advisor_id'] ?? 0) !== (int)$advisor['id']) {
            $_SESSION['error_msg'] = "Access Denied: You can only manage documents for your directly registered customers.";
            Response::redirect('/advisor/customers');
            return;
        }

        $documents = Document::getByCustomerId($customerId);
        $auditHistory = Lead::getCustomerAuditHistory($customerId);

        Response::view('advisor/customer_documents', [
            'pageTitle' => "Manage & Rectify Documents: {$customer['customer_code']} — SVPL Advisor",
            'advisor' => $advisor,
            'customer' => $customer,
            'documents' => $documents,
            'auditHistory' => $auditHistory,
            'successMsg' => $_SESSION['success_msg'] ?? null,
            'errorMsg' => $_SESSION['error_msg'] ?? null,
        ]);

        unset($_SESSION['success_msg'], $_SESSION['error_msg']);
    }

    public function uploadCustomerDocument(string $id): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $customerId = (int) $id;

        $customer = Customer::findById($customerId);
        if (!$customer || (int)($customer['advisor_id'] ?? 0) !== (int)$advisor['id']) {
            $_SESSION['error_msg'] = "Access Denied.";
            Response::redirect('/advisor/customers');
            return;
        }

        $docType = trim($_POST['document_type'] ?? '');
        $title = trim($_POST['document_title'] ?? $docType);
        $remarks = trim($_POST['remarks'] ?? 'Uploaded by Sponsor Advisor');

        if (!$docType || empty($_FILES['document_file']['tmp_name'])) {
            $_SESSION['error_msg'] = "Please select a valid document file and type.";
            Response::redirect("/advisor/customers/{$customerId}/documents");
            return;
        }

        $file = $_FILES['document_file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'pdf', 'webp'];

        if (!in_array($ext, $allowed)) {
            $_SESSION['error_msg'] = "Invalid file format. Allowed: JPG, PNG, WEBP, PDF.";
            Response::redirect("/advisor/customers/{$customerId}/documents");
            return;
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/documents/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = strtolower($docType) . '_' . $customerId . '_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            \App\Helpers\ImageCompressor::compressIfNeeded($targetPath);
            $relPath = 'uploads/documents/' . $filename;

            Document::create([
                'entity_type' => 'CUSTOMER',
                'entity_id' => $customerId,
                'lead_id' => $customer['lead_id'] ?? null,
                'document_type' => $docType,
                'document_title' => $title,
                'file_path' => $relPath,
                'file_size' => filesize($targetPath),
                'mime_type' => mime_content_type($targetPath) ?: 'application/octet-stream',
                'status' => 'Uploaded',
                'remarks' => $remarks
            ]);

            $_SESSION['success_msg'] = "Document '{$title}' successfully uploaded and submitted for BOE verification.";
        } else {
            $_SESSION['error_msg'] = "Failed to upload document file.";
        }

        Response::redirect("/advisor/customers/{$customerId}/documents");
    }

    public function replaceCustomerDocument(string $id, string $docId): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $customerId = (int) $id;
        $documentId = (int) $docId;

        $customer = Customer::findById($customerId);
        if (!$customer || (int)($customer['advisor_id'] ?? 0) !== (int)$advisor['id']) {
            $_SESSION['error_msg'] = "Access Denied.";
            Response::redirect('/advisor/customers');
            return;
        }

        $doc = Document::findById($documentId);
        if (!$doc || (int)$doc['entity_id'] !== $customerId) {
            $_SESSION['error_msg'] = "Document record not found.";
            Response::redirect("/advisor/customers/{$customerId}/documents");
            return;
        }

        if (empty($_FILES['document_file']['tmp_name'])) {
            $_SESSION['error_msg'] = "Please select a replacement file.";
            Response::redirect("/advisor/customers/{$customerId}/documents");
            return;
        }

        $file = $_FILES['document_file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'pdf', 'webp'];

        if (!in_array($ext, $allowed)) {
            $_SESSION['error_msg'] = "Invalid file format. Allowed: JPG, PNG, WEBP, PDF.";
            Response::redirect("/advisor/customers/{$customerId}/documents");
            return;
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/documents/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = strtolower($doc['document_type']) . '_' . $customerId . '_replaced_' . time() . '.' . $ext;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            \App\Helpers\ImageCompressor::compressIfNeeded($targetPath);
            $relPath = 'uploads/documents/' . $filename;
            $remarks = trim($_POST['remarks'] ?? 'Rectified and re-uploaded by Sponsor Advisor');

            Document::replaceDocument($documentId, [
                'file_path' => $relPath,
                'file_size' => filesize($targetPath),
                'mime_type' => mime_content_type($targetPath) ?: 'application/octet-stream',
                'remarks' => $remarks
            ]);

            $_SESSION['success_msg'] = "Document '{$doc['document_title']}' has been successfully replaced and submitted for BOE review.";
        } else {
            $_SESSION['error_msg'] = "Failed to upload replacement file.";
        }

        Response::redirect("/advisor/customers/{$customerId}/documents");
    }

    public function myInvoice(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        if (!$advisor || empty($advisor['joining_fee_paid'])) {
            $_SESSION['payment_error'] = 'GST Tax Invoice is available after payment verification.';
            Response::redirect('/advisor/dashboard');
            return;
        }
        Response::redirect('/print/advisor-invoice/' . $advisor['id']);
    }

    public function myLeaflet(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        if (!$advisor || empty($advisor['joining_fee_paid'])) {
            $_SESSION['payment_error'] = 'Personalized Brochure / Leaflet is available after payment verification.';
            Response::redirect('/advisor/dashboard');
            return;
        }
        Response::redirect('/print/leaflet/' . $advisor['id']);
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
        $advisor = Advisor::findByUserId((int) $user['id']);
        $wallet = Wallet::getByUserId((int) $user['id']);
        $transactions = \App\Services\WalletService::getAdvisorTransactions((int)$advisor['id'], 100);
        $withdrawals = \App\Models\WithdrawalRequest::getByUserId((int) $user['id']);

        Response::view('advisor/wallet', [
            'pageTitle' => 'My Wallet & Financial Ledger — SVPL',
            'advisor' => $advisor,
            'wallet' => $wallet,
            'transactions' => $transactions,
            'withdrawals' => $withdrawals,
            'successMsg' => $_SESSION['success_msg'] ?? null,
            'errorMsg' => $_SESSION['error_msg'] ?? null,
        ]);

        unset($_SESSION['success_msg'], $_SESSION['error_msg']);
    }

    public function requestWithdrawal(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);

        if (!$advisor) {
            $_SESSION['error_msg'] = "Advisor profile not found.";
            Response::redirect(url('/advisor/wallet'));
            return;
        }

        $amount = (float) ($_POST['amount'] ?? 0);
        $wallet = Wallet::getByUserId((int) $user['id']);
        $currentBalance = (float) ($wallet['balance'] ?? 0);

        if ($amount < 100) {
            $_SESSION['error_msg'] = "Minimum withdrawal amount is ₹100.";
            Response::redirect(url('/advisor/wallet'));
            return;
        }

        if ($amount > $currentBalance) {
            $_SESSION['error_msg'] = "Requested amount (₹" . number_format($amount, 2) . ") exceeds your available wallet balance (₹" . number_format($currentBalance, 2) . ").";
            Response::redirect(url('/advisor/wallet'));
            return;
        }

        if (empty($advisor['account_number']) || empty($advisor['ifsc_code'])) {
            $_SESSION['error_msg'] = "Please update your Bank Name, Account Number, and IFSC Code in your profile before requesting a bank payout.";
            Response::redirect(url('/advisor/wallet'));
            return;
        }

        $tds = round($amount * 0.05, 2);
        $netPayable = $amount - $tds;

        $debitSuccess = Wallet::debit(
            (int) $user['id'],
            $amount,
            'WITHDRAWAL_REQUEST',
            "Bank Withdrawal Request of ₹" . number_format($amount, 2) . " (Net: ₹" . number_format($netPayable, 2) . " after 5% TDS)"
        );

        if (!$debitSuccess) {
            $_SESSION['error_msg'] = "Failed to process wallet debit. Please try again.";
            Response::redirect(url('/advisor/wallet'));
            return;
        }

        \App\Models\WithdrawalRequest::create([
            'user_id' => (int) $user['id'],
            'advisor_id' => (int) $advisor['id'],
            'amount' => $amount,
            'tds_amount' => $tds,
            'net_payable' => $netPayable,
            'bank_name' => $advisor['bank_name'] ?? 'N/A',
            'bank_branch' => $advisor['bank_branch'] ?? '',
            'account_holder' => $advisor['account_holder'] ?? ($advisor['first_name'] . ' ' . $advisor['last_name']),
            'account_number' => $advisor['account_number'],
            'ifsc_code' => $advisor['ifsc_code'],
            'status' => 'PENDING',
        ]);

        $_SESSION['success_msg'] = "Withdrawal request of ₹" . number_format($amount, 2) . " submitted successfully. Net payable ₹" . number_format($netPayable, 2) . " will be transferred to your bank account after approval.";
        Response::redirect(url('/advisor/wallet'));
    }

    public function exportLedger(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        $transactions = \App\Services\WalletService::getAdvisorTransactions((int)$advisor['id'], 5000);

        $filename = 'SVPL_Wallet_Ledger_' . ($advisor['advisor_code'] ?? $advisor['id']) . '_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM for Microsoft Excel
        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, [
            'Txn Ref',
            'Date & Time',
            'Type',
            'Description',
            'Product / Module',
            'Level',
            'Credit Inflow (Rs)',
            'Debit Outflow (Rs)',
            'Balance After (Rs)'
        ]);

        foreach ($transactions as $t) {
            fputcsv($output, [
                $t['transaction_ref'] ?? ('TXN-' . $t['id']),
                $t['created_at'],
                str_replace('_', ' ', $t['transaction_type'] ?? ''),
                $t['description'] ?? '',
                $t['product_name'] ?? '',
                !empty($t['level']) ? 'L' . $t['level'] : '',
                (float)($t['credit_amount'] ?? 0) > 0 ? number_format((float)$t['credit_amount'], 2, '.', '') : '0.00',
                (float)($t['debit_amount'] ?? 0) > 0 ? number_format((float)$t['debit_amount'], 2, '.', '') : '0.00',
                number_format((float)($t['balance_after'] ?? 0), 2, '.', '')
            ]);
        }
        fclose($output);
        exit;
    }

    public function printLedger(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        $wallet = Wallet::getByUserId((int) $user['id']);
        $transactions = \App\Services\WalletService::getAdvisorTransactions((int)$advisor['id'], 1000);

        Response::view('printable/wallet_statement', [
            'pageTitle' => 'Commission Wallet Statement — ' . ($advisor['advisor_code'] ?? 'SVPL'),
            'advisor' => $advisor,
            'wallet' => $wallet,
            'transactions' => $transactions
        ]);
    }

    public function exportPayouts(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        $withdrawals = \App\Models\WithdrawalRequest::getByUserId((int) $user['id']);

        $filename = 'SVPL_Bank_Payouts_' . ($advisor['advisor_code'] ?? $advisor['id']) . '_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM for Microsoft Excel
        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, [
            'Request Code',
            'Requested Date & Time',
            'Gross Amount (Rs)',
            '5% Statutory TDS (Rs)',
            'Net Payable (Rs)',
            'Bank Name',
            'Bank Branch',
            'Account Holder',
            'Account Number',
            'IFSC Code',
            'Status',
            'Bank UTR Number',
            'Admin Remarks',
            'Processed At'
        ]);

        foreach ($withdrawals as $w) {
            fputcsv($output, [
                $w['request_code'],
                $w['requested_at'],
                number_format((float)$w['amount'], 2, '.', ''),
                number_format((float)$w['tds_amount'], 2, '.', ''),
                number_format((float)$w['net_payable'], 2, '.', ''),
                $w['bank_name'] ?? '',
                $w['bank_branch'] ?? '',
                $w['account_holder'] ?? '',
                $w['account_number'] ?? '',
                $w['ifsc_code'] ?? '',
                $w['status'],
                $w['utr_number'] ?? '',
                $w['admin_remarks'] ?? '',
                $w['processed_at'] ?? ''
            ]);
        }
        fclose($output);
        exit;
    }

    public function printPayouts(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        $withdrawals = \App\Models\WithdrawalRequest::getByUserId((int) $user['id']);

        Response::view('printable/payouts_statement', [
            'pageTitle' => 'Bank Payouts Statement — ' . ($advisor['advisor_code'] ?? 'SVPL'),
            'advisor' => $advisor,
            'withdrawals' => $withdrawals
        ]);
    }

    public function idCard(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);

        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        if (empty($advisor['joining_fee_paid'])) {
            $_SESSION['payment_error'] = 'Official Advisor ID Card generation is locked until your one-time registration fee payment of ₹' . number_format(advisor_joining_fee()) . ' is received and approved by admin.';
            Response::redirect('/advisor/dashboard');
            return;
        }

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

        if (empty($advisor['joining_fee_paid'])) {
            $_SESSION['payment_error'] = 'Customer Registration is locked until your one-time registration fee payment of ₹' . number_format(advisor_joining_fee()) . ' is received and approved by admin.';
            Response::redirect('/advisor/dashboard');
            return;
        }

        $packages = Package::getAllActive();
        $joiningFeePaid = (int)($advisor['joining_fee_paid'] ?? 0);
        $pendingJoiningPayment = \App\Helpers\Database::fetchOne(
            "SELECT * FROM payments WHERE entity_type = 'ADVISOR' AND entity_id = ? AND purpose = 'JOINING_FEE' AND status = 'PENDING' ORDER BY id DESC LIMIT 1",
            [$advisor['id']]
        );

        Response::view('advisor/register_customer', [
            'pageTitle' => 'Register New Customer — SVPL Advisor',
            'advisor' => $advisor,
            'packages' => $packages,
            'post' => [],
            'joiningFeePaid' => $joiningFeePaid,
            'pendingJoiningPayment' => $pendingJoiningPayment,
            'joiningFeeAmount' => advisor_joining_fee(),
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

        // Security Guard: Gated Customer Registration
        if (empty($advisor['joining_fee_paid'])) {
            $_SESSION['payment_error'] = 'Customer Registration is locked until your one-time registration fee payment of ₹' . number_format(advisor_joining_fee()) . ' is received and approved by admin.';
            Response::redirect('/advisor/dashboard');
            return;
        }

        $packages = Package::getAllActive();
        $joiningFeePaid = (int)($advisor['joining_fee_paid'] ?? 0);

        $post = $_POST;
        $mobile = trim($post['mobile'] ?? '');

        if (empty($post['first_name']) || empty($post['last_name']) || empty($mobile) || empty($post['consumer_number'])) {
            Response::view('advisor/register_customer', [
                'pageTitle' => 'Register New Customer — SVPL Advisor',
                'advisor' => $advisor,
                'packages' => $packages,
                'error' => 'Please fill in Customer Name, Mobile Number, and DISCOM Consumer Number.',
                'post' => $post,
                'joiningFeePaid' => $joiningFeePaid,
                'joiningFeeAmount' => advisor_joining_fee(),
            ]);
            return;
        }

        // Package selection & calculations
        $packageId = !empty($post['package_id']) ? (int)$post['package_id'] : null;
        $selectedPkg = $packageId ? Package::findById($packageId) : null;

        $proposedKw = $selectedPkg ? (float)$selectedPkg['capacity_kw'] : (float)($post['proposed_solar_kw'] ?? 3.0);
        $totalCost = $selectedPkg ? (float)$selectedPkg['total_price'] : 210000.00;
        $totalSubsidy = $selectedPkg ? (float)$selectedPkg['estimated_subsidy'] : 138000.00;
        $centralSubsidy = min(78000.00, $totalSubsidy);
        $stateSubsidy = max(0.0, $totalSubsidy - $centralSubsidy);
        $netPayable = $selectedPkg ? (float)$selectedPkg['net_customer_cost'] : max(0.0, $totalCost - $totalSubsidy);

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

            // Process Customer Touch / Stylus Signature (Base64)
            $customerSigPath = null;
            $sigBase64 = trim($post['customer_signature_base64'] ?? '');
            if (!empty($sigBase64) && strpos($sigBase64, 'data:image') === 0) {
                $sigUploadDir = dirname(__DIR__, 2) . '/public/uploads/signatures/';
                if (!is_dir($sigUploadDir)) {
                    mkdir($sigUploadDir, 0777, true);
                }
                $sigData = explode(',', $sigBase64);
                if (isset($sigData[1])) {
                    $decodedSig = base64_decode($sigData[1]);
                    if ($decodedSig !== false) {
                        $sigFileName = 'cust_sig_' . $userId . '_' . time() . '.png';
                        $sigFilePath = $sigUploadDir . $sigFileName;
                        if (file_put_contents($sigFilePath, $decodedSig)) {
                            $customerSigPath = '/uploads/signatures/' . $sigFileName;
                        }
                    }
                }
            }

            $agreementAccepted = !empty($post['agreement_accepted']) ? 1 : 0;
            $agreementAcceptedAt = $agreementAccepted ? date('Y-m-d H:i:s') : null;

            $custCode = Customer::generateCustomerCode();
            $custId = Customer::create([
                'user_id' => $userId,
                'customer_code' => $custCode,
                'advisor_id' => (int) $advisor['id'],
                'first_name' => trim($post['first_name']),
                'last_name' => trim($post['last_name']),
                'father_husband_name' => trim($post['father_husband_name'] ?? ''),
                'dob' => $dob,
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
                'notification_number' => trim($post['notification_number'] ?? ''),
                'electricity_bill_mobile' => $billMobile,
                'electricity_bill_dob' => $billDob,
                'sanctioned_load_kw' => (float) ($post['sanctioned_load_kw'] ?? 2.0),
                'proposed_solar_kw' => $proposedKw,
                'monthly_avg_bill' => (float) ($post['monthly_avg_bill'] ?? 2500),
                'roof_type' => trim($post['roof_type'] ?? 'RCC Concrete Roof'),
                'customer_signature' => $customerSigPath,
                'agreement_accepted' => $agreementAccepted,
                'agreement_accepted_at' => $agreementAcceptedAt,
                'status' => 'New',
            ]);

            // Create Lead in Pipeline linked to this advisor
            $leadCode = Lead::generateLeadCode();
            $leadId = Lead::create([
                'lead_code' => $leadCode,
                'customer_id' => $custId,
                'advisor_id' => (int) $advisor['id'],
                'package_id' => $packageId,
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
                'electricity_bill_mobile' => $billMobile,
                'electricity_bill_dob' => $billDob,
                'proposed_capacity_kw' => $proposedKw,
                'estimated_project_cost' => $totalCost,
                'subsidy_amount' => $centralSubsidy,
                'state_subsidy' => $stateSubsidy,
                'customer_payable_amount' => $netPayable,
                'stage' => 'REGISTRATION',
                'status' => 'Application Submitted by Advisor ' . $advisor['advisor_code'],
            ]);

            // Process and Save Customer Documents
            $uploadDir = __DIR__ . '/../../public/uploads/documents/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $docMap = [
                'doc_electricity_bill' => ['type' => 'ELECTRICITY_BILL', 'title' => 'Electricity Bill'],
                'doc_aadhaar_card'     => ['type' => 'AADHAAR_CARD', 'title' => 'Customer Aadhaar Card'],
                'doc_passport_photo'   => ['type' => 'PASSPORT_PHOTO', 'title' => 'Customer Passport Size Photo'],
                'doc_pan_card'         => ['type' => 'PAN_CARD', 'title' => 'Customer PAN Card'],
                'doc_land_patta'       => ['type' => 'LAND_PATTA', 'title' => 'Land Patta / Property Ownership Document'],
                'doc_bank_passbook'    => ['type' => 'BANK_PASSBOOK', 'title' => 'Bank Passbook / Cancelled Cheque'],
                'doc_roof_photo'       => ['type' => 'ROOF_PHOTO', 'title' => 'Rooftop Solar Site Photo'],
                'doc_other'            => ['type' => 'OTHER_DOCUMENT', 'title' => 'Other Supporting Documents'],
            ];

            foreach ($docMap as $inputName => $meta) {
                if (!empty($_FILES[$inputName]['name']) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION));
                    $filename = strtolower($meta['type']) . '_' . $custId . '_' . time() . '_' . rand(100, 999) . '.' . $ext;
                    $targetPath = $uploadDir . $filename;
                    if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetPath)) {
                        \App\Helpers\ImageCompressor::compressIfNeeded($targetPath);
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

    public static function getPersonalCustomerRecord(int $userId, ?int $advisorId, ?string $mobile): ?array
    {
        // 1. By user_id
        $cust = \App\Helpers\Database::fetchOne("SELECT * FROM customers WHERE user_id = ? LIMIT 1", [$userId]);
        if ($cust) return $cust;

        // 2. By converted_advisor_id
        if ($advisorId) {
            $cust = \App\Helpers\Database::fetchOne("SELECT * FROM customers WHERE converted_advisor_id = ? LIMIT 1", [$advisorId]);
            if ($cust) return $cust;
        }

        // 3. By mobile
        if (!empty($mobile)) {
            $cust = \App\Helpers\Database::fetchOne("SELECT * FROM customers WHERE mobile = ? LIMIT 1", [$mobile]);
            if ($cust) return $cust;
        }

        return null;
    }

    public function mySolar(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);

        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        $customer = self::getPersonalCustomerRecord((int)$user['id'], (int)$advisor['id'], $advisor['mobile'] ?? null);

        if (!$customer) {
            $_SESSION['info_msg'] = "No personal rooftop solar project is currently linked to your advisor account.";
            Response::redirect('/advisor/dashboard');
            return;
        }

        $lead = Lead::findByCustomerCode($customer['customer_code']);
        $quotation = $lead ? \App\Models\Quotation::findByLeadId((int) $lead['id']) : null;
        $documents = \App\Models\Document::getByCustomerId((int) $customer['id']);
        $dispatch = \App\Models\PackageDispatch::findByCustomerId((int) $customer['id']);

        Response::view('advisor/my_solar', [
            'pageTitle' => 'My Rooftop Solar Project (15 Stages) — ' . company_name(),
            'advisor' => $advisor,
            'customer' => $customer,
            'lead' => $lead,
            'quotation' => $quotation,
            'documents' => $documents,
            'dispatch' => $dispatch,
        ]);
    }

    public function acknowledgeDispatch(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);

        if (!$advisor || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::redirect('/advisor/my-solar');
            return;
        }

        $customer = self::getPersonalCustomerRecord((int)$user['id'], (int)$advisor['id'], $advisor['mobile'] ?? null);
        if (!$customer) {
            Response::redirect('/advisor/dashboard');
            return;
        }

        $dispatchId = (int)($_POST['dispatch_id'] ?? 0);
        $notes = trim($_POST['notes'] ?? 'Received and verified by customer');

        if ($dispatchId <= 0) {
            $_SESSION['error_msg'] = "Invalid dispatch selected.";
            Response::redirect('/advisor/my-solar');
            return;
        }

        $dispatch = \App\Models\PackageDispatch::findById($dispatchId);
        if (!$dispatch || (int)$dispatch['customer_id'] !== (int)$customer['id']) {
            $_SESSION['error_msg'] = "Unauthorized or dispatch record not found.";
            Response::redirect('/advisor/my-solar');
            return;
        }

        $updated = \App\Models\PackageDispatch::acknowledgeReceipt($dispatchId, $notes);
        if ($updated) {
            \App\Models\AuditLog::log(
                $user['id'],
                'DISPATCH_ACKNOWLEDGED',
                'package_dispatches',
                $dispatchId,
                "Advisor {$advisor['advisor_code']} acknowledged personal equipment receipt for dispatch #{$dispatchId}"
            );
            $_SESSION['success_msg'] = "Solar materials & kit receipt successfully confirmed! Our assigned installation engineer has been notified.";
        } else {
            $_SESSION['error_msg'] = "Failed to record dispatch receipt acknowledgment.";
        }

        Response::redirect('/advisor/my-solar');
    }

    public function submitJoiningPayment(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        $paymentMethod = trim($_POST['payment_method'] ?? 'UPI');
        $transactionRef = trim($_POST['transaction_ref'] ?? '');
        $paymentDate = trim($_POST['payment_date'] ?? date('Y-m-d'));
        $paymentRemarks = trim($_POST['payment_remarks'] ?? '');

        if (empty($transactionRef)) {
            $_SESSION['payment_error'] = 'Please enter the UTR / Transaction ID from your payment receipt or UPI app.';
            Response::redirect('/advisor/dashboard');
            return;
        }

        $currentFee = advisor_joining_fee();

        // Check if there is already an existing pending payment record
        $existing = \App\Helpers\Database::fetchOne(
            "SELECT id FROM payments WHERE entity_type = 'ADVISOR' AND entity_id = ? AND purpose = 'JOINING_FEE' AND status = 'PENDING' LIMIT 1",
            [$advisor['id']]
        );

        if ($existing) {
            \App\Helpers\Database::execute(
                "UPDATE payments SET payment_method = ?, transaction_ref = ?, payment_date = ?, amount = ?, updated_at = NOW() WHERE id = ?",
                [$paymentMethod, $transactionRef, $paymentDate, $currentFee, $existing['id']]
            );
        } else {
            \App\Models\Payment::create([
                'entity_type' => 'ADVISOR',
                'entity_id' => $advisor['id'],
                'purpose' => 'JOINING_FEE',
                'amount' => $currentFee,
                'payment_method' => $paymentMethod,
                'transaction_ref' => $transactionRef,
                'status' => 'PENDING',
                'payment_date' => $paymentDate,
            ]);
        }

        \App\Models\AuditLog::log(
            (int)$user['id'],
            'ADVISOR_PAYMENT_SUBMITTED',
            'ADVISOR',
            (int)$advisor['id'],
            "Advisor {$advisor['advisor_code']} submitted Registration Fee payment of ₹" . number_format($currentFee) . " (UTR: {$transactionRef})"
        );

        $_SESSION['payment_success'] = "Payment details (UTR: {$transactionRef}) submitted successfully! Verification is in progress by Admin/Accounts. Once approved, Customer Registration will be instantly unlocked.";
        Response::redirect('/advisor/dashboard');
    }
}
