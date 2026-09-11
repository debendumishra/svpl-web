<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Back Office Executive (BOE) Controller
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Services\AuthService;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Document;
use App\Models\User;
use App\Models\AuditLog;

class BoeController
{
    private function authUser(): array
    {
        $user = AuthService::user();
        if (!$user || !in_array($user['role'], ['BOE', 'ADMIN', 'SUPER_ADMIN', 'OPERATIONS'])) {
            Response::redirect('/login');
            exit;
        }
        return $user;
    }

    public function dashboard(): void
    {
        $user = $this->authUser();
        $boeUserId = (int)$user['id'];

        $allBoeCustomers = Customer::getCustomersForBOE($boeUserId);
        
        $poolCustomers = array_filter($allBoeCustomers, function($c) {
            return ($c['lead_stage'] ?? 'REGISTRATION') === 'REGISTRATION' && empty($c['assigned_boe_id']);
        });

        $myCustomers = array_filter($allBoeCustomers, function($c) use ($boeUserId) {
            return (int)($c['assigned_boe_id'] ?? 0) === $boeUserId;
        });

        $pendingDocs = array_filter($myCustomers, function($c) {
            return ($c['lead_stage'] ?? '') === 'DOCUMENTS' || ($c['lead_status'] ?? '') === 'Documents Pending';
        });

        $completedLeads = array_filter($myCustomers, function($c) {
            return in_array($c['lead_stage'] ?? '', ['INSTALLATION_COMPLETED', 'JE_REPORT', 'SUBSIDY_APPLIED', 'SUBSIDY_RECEIVED']);
        });

        Response::view('boe/dashboard', [
            'pageTitle' => 'BOE Processing Center — SVPL',
            'user' => $user,
            'poolCount' => count($poolCustomers),
            'myCount' => count($myCustomers),
            'pendingDocsCount' => count($pendingDocs),
            'completedCount' => count($completedLeads),
            'recentCustomers' => array_slice($myCustomers, 0, 10),
            'recentPool' => array_slice($poolCustomers, 0, 10),
        ], 'boe');
    }

    public function customers(): void
    {
        $user = $this->authUser();
        $boeUserId = (int)$user['id'];
        $search = trim($_GET['search'] ?? '');
        $tab = trim($_GET['tab'] ?? 'pool'); // 'pool' or 'my' or 'all'

        $allBoeCustomers = Customer::getCustomersForBOE($boeUserId, $search);

        $poolCustomers = [];
        $myCustomers = [];

        foreach ($allBoeCustomers as $c) {
            if (($c['lead_stage'] ?? 'REGISTRATION') === 'REGISTRATION' && empty($c['assigned_boe_id'])) {
                $poolCustomers[] = $c;
            } elseif ((int)($c['assigned_boe_id'] ?? 0) === $boeUserId) {
                $myCustomers[] = $c;
            }
        }

        Response::view('boe/customers', [
            'pageTitle' => 'Solar Applications Pool & Assignments — SVPL',
            'user' => $user,
            'tab' => $tab,
            'search' => $search,
            'poolCustomers' => $poolCustomers,
            'myCustomers' => $myCustomers,
            'allCustomers' => $allBoeCustomers,
        ], 'boe');
    }

    public function customerDetail(string $id): void
    {
        $user = $this->authUser();
        $boeUserId = (int)$user['id'];
        $customerId = (int)$id;

        $customer = Customer::findById($customerId);
        if (!$customer) {
            Response::notFound("Customer record not found.");
            return;
        }

        // Enforce BOE Access Control Rule
        // Stage 1 (REGISTRATION) customers can be viewed by all BOEs.
        // Stage 2+ customers can ONLY be viewed by the assigned BOE (or Admin).
        $leadStage = $customer['lead_stage'] ?? 'REGISTRATION';
        $assignedBoe = (int)($customer['assigned_boe_id'] ?? 0);

        if ($user['role'] === 'BOE') {
            if ($leadStage !== 'REGISTRATION' && $assignedBoe !== 0 && $assignedBoe !== $boeUserId) {
                Response::forbidden("Access Denied: This customer application is currently assigned to another Back Office Executive.");
                return;
            }
        }

        $lead = null;
        if (!empty($customer['lead_id'])) {
            $lead = Lead::findById((int)$customer['lead_id']);
        }

        $documents = Document::getByCustomerId($customerId);
        $auditHistory = Lead::getCustomerAuditHistory($customerId);

        Response::view('boe/customer_detail', [
            'pageTitle' => "Customer Application: {$customer['customer_code']} — SVPL",
            'user' => $user,
            'customer' => $customer,
            'lead' => $lead,
            'documents' => $documents,
            'auditHistory' => $auditHistory,
            'successMsg' => $_SESSION['success_msg'] ?? null,
            'errorMsg' => $_SESSION['error_msg'] ?? null,
        ], 'boe');

        unset($_SESSION['success_msg'], $_SESSION['error_msg']);
    }

    public function updateStatus(): void
    {
        $user = $this->authUser();
        $boeUserId = (int)$user['id'];

        $customerId = (int)($_POST['customer_id'] ?? 0);
        $leadId = (int)($_POST['lead_id'] ?? 0);
        $stage = trim($_POST['stage'] ?? '');
        $status = trim($_POST['status'] ?? '');
        $remarks = trim($_POST['remarks'] ?? '');

        if (!$customerId || !$stage || !$status) {
            $_SESSION['error_msg'] = "Invalid status update data.";
            Response::redirect("/boe/customers/{$customerId}");
            return;
        }

        $customer = Customer::findById($customerId);
        if (!$customer) {
            Response::notFound("Customer not found.");
            return;
        }

        // If lead doesn't exist, create one
        if (!$leadId && !empty($customer['lead_id'])) {
            $leadId = (int)$customer['lead_id'];
        }

        if (!$leadId) {
            $leadId = Lead::create([
                'customer_id' => $customerId,
                'advisor_id' => $customer['advisor_id'] ?? null,
                'first_name' => $customer['first_name'],
                'last_name' => $customer['last_name'],
                'mobile' => $customer['mobile'],
                'email' => $customer['email'] ?? null,
                'district' => $customer['district'],
                'block' => $customer['block'],
                'gram_panchayat' => $customer['gram_panchayat'],
                'village' => $customer['village'] ?? null,
                'pincode' => $customer['pincode'],
                'discom_name' => $customer['discom_name'] ?? 'TPCODL',
                'consumer_number' => $customer['consumer_number'] ?? null,
                'proposed_capacity_kw' => $customer['proposed_solar_kw'] ?? 3.0,
                'stage' => $stage,
                'status' => $status,
            ]);
        }

        // Automatically assign customer to this BOE if unassigned
        if (empty($customer['assigned_boe_id']) && $user['role'] === 'BOE') {
            Customer::assignBOE($customerId, $boeUserId);
        }

        // Perform stage update with full audit logging (Req 7 & 2)
        Lead::updateStage($leadId, $stage, $status, $remarks, $boeUserId);

        $_SESSION['success_msg'] = "Customer application status successfully updated to {$stage} ({$status}).";
        Response::redirect("/boe/customers/{$customerId}");
    }

    public function uploadDocument(): void
    {
        $user = $this->authUser();
        $boeUserId = (int)$user['id'];

        $customerId = (int)($_POST['customer_id'] ?? 0);
        $docType = trim($_POST['document_type'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $remarks = trim($_POST['remarks'] ?? '');

        if (!$customerId || !$docType || empty($_FILES['document_file']['tmp_name'])) {
            $_SESSION['error_msg'] = "Please select a valid document file and type.";
            Response::redirect("/boe/customers/{$customerId}");
            return;
        }

        $file = $_FILES['document_file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'pdf', 'webp'];

        if (!in_array($ext, $allowed)) {
            $_SESSION['error_msg'] = "Invalid file format. Allowed: JPG, PNG, WEBP, PDF.";
            Response::redirect("/boe/customers/{$customerId}");
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
            
            $customer = Customer::findById($customerId);
            $leadId = $customer['lead_id'] ?? null;

            Document::create([
                'entity_type' => 'CUSTOMER',
                'entity_id' => $customerId,
                'lead_id' => $leadId,
                'document_type' => $docType,
                'document_title' => $title ?: $docType,
                'file_path' => $relPath,
                'file_size' => $file['size'],
                'mime_type' => $file['type'] ?: 'application/octet-stream',
                'status' => 'Verified',
                'remarks' => $remarks ?: "Uploaded by BOE: {$user['name']} ({$user['employee_code']})",
            ]);

            // Automatically assign BOE if unassigned
            if (empty($customer['assigned_boe_id']) && $user['role'] === 'BOE') {
                Customer::assignBOE($customerId, $boeUserId);
            }

            AuditLog::log($boeUserId, 'DOCUMENT_UPLOAD', 'CUSTOMER', $customerId, "BOE uploaded document {$docType}");

            $_SESSION['success_msg'] = "Document '{$docType}' uploaded and verified successfully.";
        } else {
            $_SESSION['error_msg'] = "Failed to upload document file. Please try again.";
        }

        Response::redirect("/boe/customers/{$customerId}");
    }

    public function replaceDocument(): void
    {
        $user = $this->authUser();
        $boeUserId = (int)$user['id'];

        $docId = (int)($_POST['document_id'] ?? 0);
        $customerId = (int)($_POST['customer_id'] ?? 0);
        $remarks = trim($_POST['remarks'] ?? '');

        if (!$docId || empty($_FILES['document_file']['tmp_name'])) {
            $_SESSION['error_msg'] = "Please select a replacement file.";
            Response::redirect("/boe/customers/{$customerId}");
            return;
        }

        $existingDoc = Document::findById($docId);
        if (!$existingDoc) {
            $_SESSION['error_msg'] = "Document record not found.";
            Response::redirect("/boe/customers/{$customerId}");
            return;
        }

        $file = $_FILES['document_file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'pdf', 'webp'];

        if (!in_array($ext, $allowed)) {
            $_SESSION['error_msg'] = "Invalid file format. Allowed: JPG, PNG, WEBP, PDF.";
            Response::redirect("/boe/customers/{$customerId}");
            return;
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/documents/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = strtolower($existingDoc['document_type']) . '_repl_' . $customerId . '_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            \App\Helpers\ImageCompressor::compressIfNeeded($targetPath);
            $relPath = 'uploads/documents/' . $filename;

            Document::replaceDocument($docId, [
                'file_path' => $relPath,
                'file_size' => $file['size'],
                'mime_type' => $file['type'] ?: 'application/octet-stream',
                'remarks' => $remarks ?: "Replaced by BOE: {$user['name']} ({$user['employee_code']}) at " . date('Y-m-d H:i'),
            ]);

            AuditLog::log($boeUserId, 'DOCUMENT_REPLACE', 'DOCUMENT', $docId, "BOE replaced document #{$docId} ({$existingDoc['document_type']})");

            $_SESSION['success_msg'] = "Document '{$existingDoc['document_type']}' successfully replaced.";
        } else {
            $_SESSION['error_msg'] = "Failed to upload replacement file.";
        }

        Response::redirect("/boe/customers/{$customerId}");
    }

    public function requestDocument(): void
    {
        $user = $this->authUser();
        $boeUserId = (int)$user['id'];

        $customerId = (int)($_POST['customer_id'] ?? 0);
        $missingDoc = trim($_POST['missing_document'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!$customerId || !$missingDoc) {
            $_SESSION['error_msg'] = "Please specify the missing document name.";
            Response::redirect("/boe/customers/{$customerId}");
            return;
        }

        $customer = Customer::findById($customerId);
        if (!$customer) {
            Response::notFound("Customer not found.");
            return;
        }

        // Log request in audit history
        $leadId = $customer['lead_id'] ?? null;
        $userCode = $user['employee_code'] ?? 'BOE-' . $boeUserId;
        $userName = $user['name'];
        $userDesg = $user['designation'] ?? 'Back Office Executive';
        $note = "BOE Requested missing document [{$missingDoc}] from Advisor/Customer. Note: " . ($message ?: 'Urgent upload requested');

        if ($leadId) {
            Lead::updateStage((int)$leadId, $customer['lead_stage'] ?? 'DOCUMENTS', $customer['lead_status'] ?? 'Documents Pending', $note, $boeUserId);
        } else {
            \App\Helpers\Database::execute(
                "INSERT INTO customer_status_history (customer_id, lead_id, from_stage, to_stage, from_status, to_status, changed_by_user_id, user_code, user_name, user_designation, remarks, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [$customerId, null, $customer['lead_stage'] ?? 'DOCUMENTS', $customer['lead_stage'] ?? 'DOCUMENTS', $customer['status'], $customer['status'], $boeUserId, $userCode, $userName, $userDesg, $note]
            );
        }

        AuditLog::log($boeUserId, 'DOCUMENT_REQUEST', 'CUSTOMER', $customerId, "BOE requested document {$missingDoc} for customer {$customer['customer_code']}");

        $_SESSION['success_msg'] = "Document request for '{$missingDoc}' logged and notification recorded for Advisor.";
        Response::redirect("/boe/customers/{$customerId}");
    }

    public function reports(): void
    {
        $user = $this->authUser();
        $boeUserId = (int)$user['id'];

        // Enforce self-performance report restriction for BOE role
        if ($user['role'] === 'BOE') {
            $boeUsers = [$user];
        } else {
            $boeUsers = User::getBOEUsers();
        }

        $reportData = [];
        foreach ($boeUsers as $b) {
            $bId = (int)$b['id'];
            $customers = \App\Helpers\Database::fetchAll(
                "SELECT c.*, l.stage as lead_stage
                 FROM customers c
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
                'OTHER' => 0,
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
                } else {
                    $stageCounts['OTHER']++;
                }
            }

            $reportData[] = [
                'boe' => $b,
                'total_assigned' => count($customers),
                'stage_counts' => $stageCounts,
                'customers' => $customers,
            ];
        }

        Response::view('boe/reports', [
            'pageTitle' => ($user['role'] === 'BOE') ? 'My Performance & Customer Summary — SVPL' : 'BOE Performance & Customer Summary Reports — SVPL',
            'user' => $user,
            'reportData' => $reportData,
        ], 'boe');
    }

    public function idCard(): void
    {
        $user = $this->authUser();
        Response::redirect('/print/boe-id-card/' . $user['id']);
    }
}
