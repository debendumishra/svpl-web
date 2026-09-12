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
            <?php if ($logoUrl = company_logo_url()): ?>
                <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars(company_name()) ?>" style="max-height: 48px; width: auto; max-width: 180px; object-fit: contain; margin-bottom: 8px;">
            <?php endif; ?>
            <h4 class="fw-bold mb-0" style="color: #0B2545;"><?= htmlspecialchars(strtoupper(company_name())) ?></h4>
            <small class="text-muted"><?= htmlspecialchars(company_address()) ?> | Tel: <?= htmlspecialchars(company_phone()) ?></small>
            <div class="mt-1"><span class="badge bg-primary-subtle text-primary border fw-bold">Official Payment Receipt</span></div>
        </div>

        <div class="d-flex justify-content-between mb-3 small">
            <div><strong>Receipt No:</strong> <?= htmlspecialchars(company_short_name()) ?>-REC-<?= date('Ymd') ?>-<?= $advisor['id'] ?? '0' ?></div>
            <div><strong>Date:</strong> <?= date('d M Y') ?></div>
        </div>

        <p class="small">Received with thanks from <strong><?= htmlspecialchars(($advisor['first_name'] ?? '') . ' ' . ($advisor['last_name'] ?? '')) ?></strong> (ID: <code><?= htmlspecialchars($advisor['advisor_code'] ?? 'N/A') ?></code>) a sum of <strong>₹<?= number_format((float)company_setting('advisor_joining_fee', 1500), 2) ?></strong> towards Advisor Induction & Welcome Kit Fee.</p>

        <div class="text-end mt-4 pt-3 border-top small">
            <strong>For <?= htmlspecialchars(company_name()) ?></strong><br>
            <span class="text-muted">Authorized Accounts Department</span>
        </div>
    </div>
</body>
</html>
