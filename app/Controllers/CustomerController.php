<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Customer Controller - Customer Portal, Live Solar Progress, Quotation, Document Uploads, Dispatch Receipt
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Services\AuthService;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Document;
use App\Models\Quotation;
use App\Models\PackageDispatch;
use App\Models\AuditLog;

class CustomerController
{
    public function dashboard(): void
    {
        $user = AuthService::user();
        $customer = Customer::findByUserId((int) $user['id']);

        if (!$customer) {
            Response::redirect('/login');
            return;
        }

        $lead = Lead::findByCustomerCode($customer['customer_code']);
        $quotation = $lead ? Quotation::findByLeadId((int) $lead['id']) : null;
        $documents = Document::getByCustomerId((int) $customer['id']);
        
        // Fetch latest equipment dispatch for this customer
        $dispatch = PackageDispatch::findByCustomerId((int) $customer['id']);

        Response::view('customer/dashboard', [
            'pageTitle' => 'My Solar Portal — SVPL Customer',
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
        $customer = Customer::findByUserId((int) $user['id']);

        if (!$customer || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::redirect('/customer/dashboard');
            return;
        }

        $dispatchId = (int)($_POST['dispatch_id'] ?? 0);
        $notes = trim($_POST['notes'] ?? 'Received and verified by customer');

        if ($dispatchId <= 0) {
            $_SESSION['error_msg'] = "Invalid dispatch selected.";
            Response::redirect('/customer/dashboard');
            return;
        }

        $dispatch = PackageDispatch::findById($dispatchId);
        if (!$dispatch || (int)$dispatch['customer_id'] !== (int)$customer['id']) {
            $_SESSION['error_msg'] = "Unauthorized or dispatch record not found.";
            Response::redirect('/customer/dashboard');
            return;
        }

        // Acknowledge receipt
        PackageDispatch::acknowledgeReceipt($dispatchId, $notes);

        // Advance Lead stage if at Stage 6 (INSTRUMENT_DISPATCHED)
        $lead = Lead::findByCustomerCode($customer['customer_code']);
        if ($lead && in_array($lead['lead_stage'] ?? '', ['INSTRUMENT_DISPATCHED', 'FEASIBILITY_APPROVED', 'LOAN_APPROVED'])) {
            Lead::updateStage(
                (int)$lead['id'],
                'INSTALLATION_COMMENCED',
                7,
                'Customer acknowledged delivery of solar equipment. Ready for rooftop installation.'
            );
        }

        AuditLog::log(
            $user['id'],
            'CUSTOMER_ACKNOWLEDGE_RECEIPT',
            'package_dispatches',
            $dispatchId,
            "Customer {$customer['customer_code']} acknowledged receipt of solar equipment (LR: {$dispatch['tracking_number']})"
        );

        $_SESSION['success_msg'] = "Thank you! Equipment receipt successfully confirmed. Your assigned solar engineer has been notified to commence rooftop installation!";
        Response::redirect('/customer/dashboard');
    }

    public function quotation(): void
    {
        $user = AuthService::user();
        $customer = Customer::findByUserId((int) $user['id']);
        $lead = Lead::findByCustomerCode($customer['customer_code']);
        $quotation = $lead ? Quotation::findByLeadId((int) $lead['id']) : null;

        Response::view('customer/quotation', [
            'pageTitle' => 'PM Surya Ghar Solar Quotation — SVPL',
            'customer' => $customer,
            'lead' => $lead,
            'quotation' => $quotation,
        ]);
    }

    public function documents(): void
    {
        $user = AuthService::user();
        $customer = Customer::findByUserId((int) $user['id']);
        $lead = Lead::findByCustomerCode($customer['customer_code']);
        $documents = Document::getByCustomerId((int) $customer['id']);

        Response::view('customer/documents', [
            'pageTitle' => 'My Uploaded Documents — SVPL',
            'customer' => $customer,
            'lead' => $lead,
            'documents' => $documents,
        ]);
    }

    public function uploadDocument(): void
    {
        $user = AuthService::user();
        $customer = Customer::findByUserId((int) $user['id']);
        $docType = $_POST['document_type'] ?? 'OTHER';

        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            Response::redirect('/customer/documents?error=upload_failed');
            return;
        }

        $uploadDir = dirname(__DIR__, 2) . '/storage/documents/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $fileName = 'DOC_' . $customer['id'] . '_' . $docType . '_' . time() . '.' . $ext;
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
            \App\Helpers\ImageCompressor::compressIfNeeded($targetFile);
            $lead = Lead::findByCustomerCode($customer['customer_code']);
            Document::create([
                'entity_type' => 'CUSTOMER',
                'entity_id' => $customer['id'],
                'lead_id' => $lead['id'] ?? null,
                'document_type' => $docType,
                'document_title' => ucwords(str_replace('_', ' ', $docType)),
                'file_path' => 'storage/documents/' . $fileName,
                'file_size' => $_FILES['file']['size'],
                'mime_type' => $_FILES['file']['type'],
                'status' => 'Uploaded',
            ]);

            Response::redirect('/customer/documents?success=1');
        } else {
            Response::redirect('/customer/documents?error=save_failed');
        }
    }
}
