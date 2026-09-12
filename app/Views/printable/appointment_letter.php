<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Printable Official Appointment Letter
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Letter - <?= htmlspecialchars($advisor['advisor_code']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Times New Roman', Times, serif; color: #1E293B; padding: 40px; background: #FFF; line-height: 1.6; }
        .letterhead { border-bottom: 2px solid #0B2545; padding-bottom: 15px; margin-bottom: 30px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print mb-4 text-end">
        <button onclick="window.print()" class="btn btn-primary btn-sm">Print Letter</button>
    </div>

    <div class="letterhead text-center">
        <?php if ($logoUrl = company_logo_url()): ?>
            <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars(company_name()) ?>" style="max-height: 54px; width: auto; max-width: 220px; object-fit: contain; margin-bottom: 8px;">
        <?php endif; ?>
        <h2 class="fw-bold mb-1" style="color: #0B2545;"><?= htmlspecialchars(strtoupper(company_name())) ?></h2>
        <p class="small text-muted mb-0">Authorized Corporate Promoter for <?= htmlspecialchars(company_promoter()) ?> in Odisha</p>
        <p class="small text-muted mb-0"><?= htmlspecialchars(company_address()) ?> | <?= htmlspecialchars(company_email()) ?> | Tel: <?= htmlspecialchars(company_phone()) ?></p>
    </div>

    <div class="d-flex justify-content-between mb-4">
        <div><strong>Ref:</strong> <?= htmlspecialchars(company_short_name()) ?>/APPT/<?= date('Y') ?>/<?= htmlspecialchars($advisor['advisor_code']) ?></div>
        <div><strong>Date:</strong> <?= date('d F, Y') ?></div>
    </div>

    <p>To,<br>
    <strong><?= htmlspecialchars($advisor['first_name'] . ' ' . $advisor['last_name']) ?></strong><br>
    Advisor Code: <code><?= htmlspecialchars($advisor['advisor_code']) ?></code><br>
    <?= htmlspecialchars($advisor['district'] . ', ' . $advisor['block']) ?>, Odisha - <?= htmlspecialchars($advisor['pincode']) ?></p>

    <h5 class="fw-bold text-center my-4 text-decoration-underline">SUB: APPOINTMENT AS AUTHORIZED SOLAR ADVISOR (ODISHA)</h5>

    <p>Dear <?= htmlspecialchars($advisor['first_name']) ?>,</p>

    <p>We are pleased to appoint you as an <strong>Authorized Solar Advisor</strong> for <?= htmlspecialchars(company_name()) ?> (<?= htmlspecialchars(company_short_name()) ?>), promoting the Government of India's <em>PM Surya Ghar: Muft Bijli Yojana</em> across your designated territory in Odisha.</p>

    <h6 class="fw-bold">Terms of Appointment:</h6>
    <ol>
        <li><strong>Role & Mandate:</strong> You are authorized to survey residential rooftops, counsel consumers regarding Central DBT Subsidies, and assist with document uploads.</li>
        <li><strong>Compensation:</strong> You will be compensated according to the official <?= htmlspecialchars(company_short_name()) ?> Multi-Tier Commission Structure upon successful DISCOM synchronization.</li>
        <li><strong>Qualification Target:</strong> Completing <?= htmlspecialchars(company_setting('advisor_required_customers', '3')) ?> direct customer rooftop installations will grant you full QUALIFIED Advisor standing.</li>
    </ol>

    <div class="row mt-5 pt-5">
        <div class="col-6">
            ___________________________<br>
            <strong>Authorized Signatory</strong><br>
            <?= htmlspecialchars(company_name()) ?>
        </div>
        <div class="col-6 text-end">
            ___________________________<br>
            <strong>Advisor Signature</strong><br>
            <?= htmlspecialchars($advisor['first_name'] . ' ' . $advisor['last_name']) ?>
        </div>
    </div>
</body>
</html>
