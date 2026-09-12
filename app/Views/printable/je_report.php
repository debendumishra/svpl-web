<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Printable Junior Engineer (JE) Site Inspection Report
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JE Inspection Report - <?= htmlspecialchars($lead['lead_code']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; padding: 40px; background: #FFF; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print mb-4 text-end">
        <button onclick="window.print()" class="btn btn-primary btn-sm">Print Inspection Report</button>
    </div>

    <div class="border p-4 rounded-3">
        <div class="text-center border-bottom pb-3 mb-4">
            <h4 class="fw-bold mb-0 text-navy">ODISHA DISCOM SOLAR ROOFTOP INSPECTION REPORT</h4>
            <small class="text-muted">PM Surya Ghar: Muft Bijli Yojana Grid Synchronization Certificate</small>
        </div>

        <table class="table table-bordered small mb-4">
            <tr>
                <td class="text-muted" style="width: 35%;">Lead Reference:</td>
                <td><strong><?= htmlspecialchars($lead['lead_code']) ?></strong></td>
            </tr>
            <tr>
                <td class="text-muted">Customer Name:</td>
                <td><?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?></td>
            </tr>
            <tr>
                <td class="text-muted">DISCOM / Consumer No.:</td>
                <td><?= htmlspecialchars($lead['discom_name'] ?? 'TPCODL') ?> — <code><?= htmlspecialchars($lead['consumer_number'] ?? 'N/A') ?></code></td>
            </tr>
            <tr>
                <td class="text-muted">Sanctioned / Installed Capacity:</td>
                <td><?= $lead['proposed_capacity_kw'] ?> kW / <?= $lead['proposed_capacity_kw'] ?> kW</td>
            </tr>
            <tr>
                <td class="text-muted">Inspecting Officer:</td>
                <td><?= htmlspecialchars($jeReport['inspecting_officer_name'] ?? 'DISCOM Junior Engineer') ?></td>
            </tr>
            <tr>
                <td class="text-muted">Inspection Status:</td>
                <td><span class="badge bg-success">Passed & Synchronized</span></td>
            </tr>
        </table>

        <div class="row mt-5 pt-4">
            <div class="col-6">
                ___________________________<br>
                <strong>Junior Engineer Signature & Seal</strong><br>
                DISCOM Electrical Division, Odisha
            </div>
            <div class="col-6 text-end">
                ___________________________<br>
                <strong><?= htmlspecialchars(company_name()) ?> Site Engineer</strong>
            </div>
        </div>
    </div>
</body>
</html>
