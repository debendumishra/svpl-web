<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Document Controller - Printable Documents (ID Cards, Receipts, Proposals, Reports)
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\Advisor;
use App\Models\Lead;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\JEReport;
use App\Models\User;
use App\Models\PackageDispatch;
use App\Services\DocumentGenerator;

class DocumentController
{
    public function printIdCard(string $id): void
    {
        $advisor = Advisor::findById((int) $id);
        if (!$advisor) {
            Response::notFound("Advisor ID #{$id} not found.");
            return;
        }

        if (empty($advisor['joining_fee_paid'])) {
            Response::forbidden('Official Advisor ID Card generation is locked until the one-time registration fee payment of ₹' . number_format(advisor_joining_fee()) . ' is received and approved by admin.');
            return;
        }

        $qrUrl = DocumentGenerator::getQrCodeUrl(DocumentGenerator::getVerificationUrl('ADVISOR', $advisor['referral_code']));
        Response::view('printable/id_card', [
            'advisor' => $advisor,
            'qrUrl' => $qrUrl,
        ]);
    }

    public function printBoeIdCard(string $id): void
    {
        $user = User::findById((int) $id);
        if (!$user || $user['role'] !== 'BOE') {
            Response::notFound("BOE Staff ID #{$id} not found.");
            return;
        }

        $empCode = $user['employee_code'] ?? ('SVPL-BOE-' . $user['id']);
        $qrUrl = DocumentGenerator::getQrCodeUrl(DocumentGenerator::getVerificationUrl('STAFF', $empCode));

        Response::view('printable/boe_id_card', [
            'boe' => $user,
            'qrUrl' => $qrUrl,
        ]);
    }

    public function printAppointment(string $id): void
    {
        $advisor = Advisor::findById((int) $id);
        if (!$advisor) {
            Response::notFound("Advisor ID #{$id} not found.");
            return;
        }

        if (empty($advisor['joining_fee_paid'])) {
            Response::forbidden('Official Advisor Appointment Letter is locked until the one-time registration fee payment of ₹' . number_format(advisor_joining_fee()) . ' is received and approved by admin.');
            return;
        }

        Response::view('printable/appointment_letter', [
            'advisor' => $advisor,
        ]);
    }

    public function printReceipt(string $id): void
    {
        $advisor = Advisor::findById((int) $id);
        Response::view('printable/receipt', [
            'advisor' => $advisor,
        ]);
    }

    public function printAdvisorInvoice(string $id): void
    {
        $advisor = Advisor::findById((int) $id);
        if (!$advisor) {
            Response::notFound("Advisor ID #{$id} not found.");
            return;
        }

        if (empty($advisor['joining_fee_paid'])) {
            Response::forbidden('GST Tax Invoice is generated exclusively after one-time registration fee payment of ₹' . number_format(advisor_joining_fee()) . ' is received and approved by admin.');
            return;
        }

        $payment = \App\Helpers\Database::fetchOne(
            "SELECT * FROM payments WHERE entity_type = 'ADVISOR' AND entity_id = ? AND purpose = 'JOINING_FEE' AND status = 'CONFIRMED' ORDER BY id DESC LIMIT 1",
            [$advisor['id']]
        );

        $qrUrl = DocumentGenerator::getQrCodeUrl(DocumentGenerator::getVerificationUrl('ADVISOR', $advisor['referral_code']));

        Response::view('printable/advisor_gst_invoice', [
            'advisor' => $advisor,
            'payment' => $payment,
            'qrUrl' => $qrUrl,
        ]);
    }

    public function printLeaflet(string $id): void
    {
        $advisor = Advisor::findById((int) $id);
        if (!$advisor) {
            Response::notFound("Advisor ID #{$id} not found.");
            return;
        }

        if (empty($advisor['joining_fee_paid'])) {
            Response::forbidden('Personalized Marketing Brochure / Leaflet generation is locked until the one-time registration fee payment of ₹' . number_format(advisor_joining_fee()) . ' is received and approved by admin.');
            return;
        }

        Response::view('printable/advisor_leaflet', [
            'advisor' => $advisor,
        ]);
    }

    public function printQuotation(string $id): void
    {
        $lead = Lead::findById((int) $id);
        if (!$lead) {
            Response::notFound("Lead #{$id} not found.");
            return;
        }

        $customer = !empty($lead['customer_id']) ? Customer::findById((int) $lead['customer_id']) : null;
        $quotation = Quotation::findByLeadId((int) $id);
        Response::view('printable/quotation', [
            'lead' => $lead,
            'customer' => $customer,
            'quotation' => $quotation,
        ]);
    }

    public function printAgreement(string $id): void
    {
        $customerId = (int) $id;
        $customer = Customer::findById($customerId);
        if (!$customer) {
            Response::notFound("Customer #{$id} not found.");
            return;
        }

        $lead = !empty($customer['lead_id']) ? Lead::findById((int)$customer['lead_id']) : Lead::findByCustomerCode($customer['customer_code']);

        Response::view('printable/consumer_agreement', [
            'pageTitle' => 'PM Surya Ghar Consumer Agreement — ' . $customer['customer_code'],
            'customer' => $customer,
            'lead' => $lead,
        ]);
    }

    public function printJeReport(string $id): void
    {
        $lead = Lead::findById((int) $id);
        $jeReport = JEReport::findByLeadId((int) $id);

        Response::view('printable/je_report', [
            'lead' => $lead,
            'jeReport' => $jeReport,
        ]);
    }

    public function printEwayBill(string $id): void
    {
        $dispatch = PackageDispatch::findById((int) $id);
        if (!$dispatch) {
            Response::notFound("Dispatch record #{$id} not found.");
            return;
        }

        $items = [];
        if (!empty($dispatch['items_json'])) {
            try {
                $items = is_array($dispatch['items_json']) ? $dispatch['items_json'] : json_decode($dispatch['items_json'], true);
            } catch (\Throwable $e) {
                $items = [];
            }
        }
        if (empty($items)) {
            $items = PackageDispatch::STANDARD_INSTRUMENTS;
        }

        $ewbNumber = '2118' . str_pad((string)$dispatch['id'], 8, '0', STR_PAD_LEFT);
        $qrPayload = "EWB:{$ewbNumber}|GSTIN:21AAKCS8912K1Z9|DOC:{$dispatch['tracking_number']}|VEH:{$dispatch['vehicle_number']}|DATE:{$dispatch['dispatch_date']}";
        $qrUrl = DocumentGenerator::getQrCodeUrl($qrPayload);

        Response::view('printable/eway_bill', [
            'dispatch'   => $dispatch,
            'items'      => $items,
            'ewbNumber'  => $ewbNumber,
            'qrUrl'      => $qrUrl,
        ]);
    }

    public function printDispatchInvoice(string $id): void
    {
        $dispatch = PackageDispatch::findById((int) $id);
        if (!$dispatch) {
            Response::notFound("Dispatch record #{$id} not found.");
            return;
        }

        $items = [];
        if (!empty($dispatch['items_json'])) {
            try {
                $items = is_array($dispatch['items_json']) ? $dispatch['items_json'] : json_decode($dispatch['items_json'], true);
            } catch (\Throwable $e) {
                $items = [];
            }
        }
        if (empty($items)) {
            $items = PackageDispatch::STANDARD_INSTRUMENTS;
        }

        $invYear = !empty($dispatch['dispatch_date']) ? date('Y', strtotime($dispatch['dispatch_date'])) : date('Y');
        $invNextYear = substr((string)((int)$invYear + 1), -2);
        $invoiceNumber = "SVPL/INV/{$invYear}-{$invNextYear}/" . str_pad((string)$dispatch['id'], 4, '0', STR_PAD_LEFT);
        
        $qrPayload = "GSTIN:21AAKCS8912K1Z9|INV:{$invoiceNumber}|DATE:{$dispatch['dispatch_date']}|VAL:" . ($dispatch['estimated_project_cost'] ?? '180000') . "|BUYER:{$dispatch['cust_first']} {$dispatch['cust_last']}";
        $qrUrl = DocumentGenerator::getQrCodeUrl($qrPayload);

        Response::view('printable/dispatch_invoice', [
            'dispatch'      => $dispatch,
            'items'         => $items,
            'invoiceNumber' => $invoiceNumber,
            'qrUrl'         => $qrUrl,
        ]);
    }

    public function printDispatchChallan(string $id): void
    {
        $dispatch = PackageDispatch::findById((int) $id);
        if (!$dispatch) {
            Response::notFound("Dispatch record #{$id} not found.");
            return;
        }

        $items = [];
        if (!empty($dispatch['items_json'])) {
            try {
                $items = is_array($dispatch['items_json']) ? $dispatch['items_json'] : json_decode($dispatch['items_json'], true);
            } catch (\Throwable $e) {
                $items = [];
            }
        }
        if (empty($items)) {
            $items = PackageDispatch::STANDARD_INSTRUMENTS;
        }

        $challanNumber = $dispatch['tracking_number'] ?: ('CHL-' . date('Ymd') . '-' . $dispatch['id']);
        $qrPayload = "CHALLAN:{$challanNumber}|VEH:{$dispatch['vehicle_number']}|DRIVER:{$dispatch['driver_name']}|DATE:{$dispatch['dispatch_date']}";
        $qrUrl = DocumentGenerator::getQrCodeUrl($qrPayload);

        Response::view('printable/dispatch_challan', [
            'dispatch'      => $dispatch,
            'items'         => $items,
            'challanNumber' => $challanNumber,
            'qrUrl'         => $qrUrl,
        ]);
    }

    public function download(): void
    {
        $file = trim($_GET['file'] ?? '');
        if (empty($file)) {
            Response::notFound("No document file specified.");
            return;
        }

        // Clean relative path without traversal
        $cleanFile = ltrim(str_replace(['../', '..\\'], '', $file), '/\\');
        $rootDir = dirname(__DIR__, 2);

        // Candidate search paths
        $xamppPath = getenv('XAMPP_PATH') ? rtrim(getenv('XAMPP_PATH'), '/\\') : null;
        $candidates = [
            $rootDir . '/public/' . $cleanFile,
            $rootDir . '/' . $cleanFile,
            $rootDir . '/public/uploads/documents/' . basename($cleanFile),
            $rootDir . '/public/uploads/' . basename($cleanFile),
            $rootDir . '/uploads/documents/' . basename($cleanFile),
            $xamppPath ? $xamppPath . '/htdocs/svpl-web/public/' . $cleanFile : null,
            $xamppPath ? $xamppPath . '/htdocs/svpl-web/public/uploads/documents/' . basename($cleanFile) : null,
        ];

        $foundPath = null;
        foreach ($candidates as $cand) {
            if (!empty($cand) && file_exists($cand) && is_file($cand)) {
                $foundPath = $cand;
                break;
            }
        }

        if ($foundPath) {
            $ext = strtolower(pathinfo($foundPath, PATHINFO_EXTENSION));
            $mimeTypes = [
                'pdf'  => 'application/pdf',
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png'  => 'image/png',
                'webp' => 'image/webp',
                'gif'  => 'image/gif',
                'doc'  => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ];
            $contentType = $mimeTypes[$ext] ?? (function_exists('mime_content_type') ? mime_content_type($foundPath) : 'application/octet-stream');
            $disposition = (isset($_GET['download']) && $_GET['download'] === '1') ? 'attachment' : 'inline';

            header('Content-Type: ' . $contentType);
            header('Content-Disposition: ' . $disposition . '; filename="' . basename($foundPath) . '"');
            header('Content-Length: ' . filesize($foundPath));
            header('Cache-Control: public, max-age=3600');
            header('Pragma: public');
            readfile($foundPath);
            exit;
        }

        Response::notFound("Requested file could not be found on the server (" . htmlspecialchars(basename($file)) . ").");
    }
}
