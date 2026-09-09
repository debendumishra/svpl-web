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

        $qrUrl = DocumentGenerator::getQrCodeUrl(DocumentGenerator::getVerificationUrl('ADVISOR', $advisor['referral_code']));
        Response::view('printable/id_card', [
            'advisor' => $advisor,
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

    public function printQuotation(string $id): void
    {
        $lead = Lead::findById((int) $id);
        if (!$lead) {
            Response::notFound("Lead #{$id} not found.");
            return;
        }

        $quotation = Quotation::findByLeadId((int) $id);
        Response::view('printable/quotation', [
            'lead' => $lead,
            'quotation' => $quotation,
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

    public function download(): void
    {
        $file = $_GET['file'] ?? '';
        $fullPath = dirname(__DIR__, 2) . '/' . ltrim($file, '/');

        if (file_exists($fullPath) && is_file($fullPath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fullPath));
            readfile($fullPath);
            exit;
        }

        Response::notFound("Requested file could not be found.");
    }
}
