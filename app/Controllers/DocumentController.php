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
