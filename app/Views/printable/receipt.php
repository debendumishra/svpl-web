<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Printable Payment Receipt
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt - <?= htmlspecialchars($advisor['advisor_code'] ?? 'SVPL') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; padding: 40px; background: #FFF; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print mb-4 text-end">
        <button onclick="window.print()" class="btn btn-primary btn-sm">Print Receipt</button>
    </div>

    <div class="border p-4 rounded-3" style="max-width: 650px; margin: 0 auto;">
        <div class="text-center border-bottom pb-3 mb-3">
            <h4 class="fw-bold mb-0" style="color: #0B2545;">SURYA VISTAARA PVT. LTD.</h4>
            <small class="text-muted">Official Payment Receipt</small>
        </div>

        <div class="d-flex justify-content-between mb-3 small">
            <div><strong>Receipt No:</strong> SVPL-REC-<?= date('Ymd') ?>-<?= $advisor['id'] ?? '0' ?></div>
            <div><strong>Date:</strong> <?= date('d M Y') ?></div>
        </div>

        <p class="small">Received with thanks from <strong><?= htmlspecialchars(($advisor['first_name'] ?? '') . ' ' . ($advisor['last_name'] ?? '')) ?></strong> (ID: <code><?= htmlspecialchars($advisor['advisor_code'] ?? 'N/A') ?></code>) a sum of <strong>₹1,500.00</strong> towards Advisor Induction & Welcome Kit Fee.</p>

        <div class="text-end mt-4 pt-3 border-top small">
            <strong>For Surya Vistaara Pvt. Ltd.</strong><br>
            <span class="text-muted">Authorized Accounts Department</span>
        </div>
    </div>
</body>
</html>
