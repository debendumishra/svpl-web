<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Customer Controller - Customer Portal, Live Solar Progress, Quotation, Document Uploads
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Services\AuthService;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Document;
use App\Models\Quotation;

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

        Response::view('customer/dashboard', [
            'pageTitle' => 'My Solar Portal — SVPL Customer',
            'customer' => $customer,
            'lead' => $lead,
            'quotation' => $quotation,
            'documents' => $documents,
        ]);
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
