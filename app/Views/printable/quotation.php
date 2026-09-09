<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Printable Quotation Proposal
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Proposal - <?= htmlspecialchars($lead['lead_code']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; padding: 40px; background: #FFF; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print mb-4 text-end">
        <button onclick="window.print()" class="btn btn-primary btn-sm">Print Proposal</button>
    </div>

    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h3 class="fw-bold mb-0" style="color: #0B2545;">SURYA VISTAARA PVT. LTD.</h3>
            <small class="text-muted">PM Surya Ghar Rooftop Solar Proposal | Dhwajja Solar Promoter</small>
        </div>
        <div class="text-end">
            <h6 class="mb-0"><strong>Quote #:</strong> <?= htmlspecialchars($quotation['quotation_number'] ?? ('SVPL-QTN-' . $lead['id'])) ?></h6>
            <small class="text-muted">Date: <?= date('d M Y') ?></small>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <strong>Customer Details:</strong><br>
            <?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?><br>
            Mobile: <?= htmlspecialchars($lead['mobile']) ?><br>
            DISCOM: <?= htmlspecialchars($lead['discom_name'] ?? 'TPCODL') ?> (Consumer #: <?= htmlspecialchars($lead['consumer_number'] ?? 'N/A') ?>)<br>
            <?= htmlspecialchars($lead['district'] . ', ' . $lead['block']) ?>
        </div>
        <div class="col-6 text-end">
            <strong>System Specifications:</strong><br>
            Capacity: <strong><?= $lead['proposed_capacity_kw'] ?> kW On-Grid System</strong><br>
            Panels: Tier-1 Mono PERC Half-Cut 540W<br>
            Inverter: Smart Grid-Tied Inverter with Wi-Fi
        </div>
    </div>

    <table class="table table-bordered mb-4">
        <thead class="table-light">
            <tr>
                <th>Item Description</th>
                <th class="text-end">Amount (INR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Benchmark System Cost (Inclusive of Panels, Inverter, Structure, Installation)</td>
                <td class="text-end">₹<?= number_format((float)$lead['estimated_project_cost'], 2) ?></td>
            </tr>
            <tr class="text-success fw-bold">
                <td>Less: PM Surya Ghar Direct Central DBT Subsidy</td>
                <td class="text-end">- ₹<?= number_format((float)($lead['subsidy_amount'] ?? 78000), 2) ?></td>
            </tr>
            <tr class="text-success fw-bold">
                <td>Less: Odisha State Government Solar Subsidy</td>
                <td class="text-end">- ₹<?= number_format((float)($lead['state_subsidy'] ?? 60000), 2) ?></td>
            </tr>
            <tr class="table-primary fs-5 fw-bold">
                <td>Net Customer Payable Amount</td>
                <td class="text-end">₹<?= number_format((float)$lead['customer_payable_amount'], 2) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="small text-muted border p-3 rounded">
        <strong>Notes:</strong> Both Central DBT Subsidy (up to ₹78,000) and Odisha State Government Solar Subsidy (₹60,000) will be credited following DISCOM Junior Engineer (JE) commissioning report verification and net-meter synchronization.
    </div>
</body>
</html>
