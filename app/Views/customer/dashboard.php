<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Customer Portal Dashboard View
 */
$title = "My Solar Installation — SVPL Customer Portal";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Welcome, <?= htmlspecialchars($customer['first_name']) ?>!</h3>
        <p class="text-muted small mb-0">Consumer Code: <code><?= htmlspecialchars($customer['customer_code']) ?></code> | Meter No: <strong><?= htmlspecialchars($customer['consumer_number'] ?? 'N/A') ?></strong></p>
    </div>
    <div class="d-flex gap-2">
        <?php if ($lead): ?>
            <a href="<?= url('/print/quotation/' . $lead['id']) ?>" target="_blank" class="btn btn-svpl-navy btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i> Solar Proposal
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- 10-Stage Progression Bar -->
<div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4">
    <h5 class="fw-bold mb-3" style="color: #0B2545;">Live Rooftop Solar Installation Progress</h5>
    <div class="alert alert-info py-2 px-3 small mb-3">
        <i class="bi bi-info-circle-fill me-1"></i> Current Stage: <strong><?= htmlspecialchars($lead['stage'] ?? 'REGISTRATION') ?></strong> — <?= htmlspecialchars($lead['status'] ?? 'Application submitted') ?>
    </div>

    <?php
    $stages = [
        'REGISTRATION' => 'Registration',
        'DOCUMENTS' => 'Documents',
        'GOVT_PORTAL' => 'Govt Portal',
        'LOAN_SANCTIONED' => 'Bank Loan',
        'INSTALLATION_COMPLETED' => 'Installation',
        'JE_REPORT' => 'JE Metering',
        'SUBSIDY_RECEIVED' => 'Subsidy DBT',
    ];
    $currentStage = $lead['stage'] ?? 'REGISTRATION';
    $stageKeys = array_keys($stages);
    $currentIndex = array_search($currentStage, $stageKeys);
    if ($currentIndex === false) $currentIndex = 0;
    ?>
    <div class="d-flex justify-content-between text-center overflow-auto pb-2">
        <?php foreach ($stages as $k => $lbl): 
            $thisIdx = array_search($k, $stageKeys);
            $isCompleted = $thisIdx < $currentIndex;
            $isActive = $thisIdx === $currentIndex;
        ?>
            <div style="min-width: 90px;">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-1 fw-bold <?= $isCompleted ? 'bg-success text-white' : ($isActive ? 'bg-warning text-dark' : 'bg-light border text-muted') ?>" style="width: 38px; height: 38px; font-size: 0.85rem;">
                    <?= $isCompleted ? '✓' : ($thisIdx + 1) ?>
                </div>
                <div class="small fw-semibold <?= $isActive ? 'text-navy fw-bold' : 'text-muted' ?>" style="font-size: 0.75rem;"><?= $lbl ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <h5 class="fw-bold mb-3" style="color: #0B2545;">My Solar Rooftop Package</h5>
            <table class="table table-bordered small">
                <tr>
                    <td class="text-muted" style="width: 45%;">Proposed System:</td>
                    <td><strong><?= $customer['proposed_solar_kw'] ?> kW On-Grid Mono PERC</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">DISCOM:</td>
                    <td><?= htmlspecialchars($customer['discom_name'] ?? 'TPCODL') ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Estimated Project Cost:</td>
                    <td><strong>₹<?= number_format((float)($lead['estimated_project_cost'] ?? 210000), 2) ?></strong></td>
                </tr>
                <tr>
                    <td class="text-muted"><i class="bi bi-check-circle-fill text-success me-1"></i> PM Surya Ghar Central DBT:</td>
                    <td class="text-success fw-bold">- ₹<?= number_format((float)($lead['subsidy_amount'] ?? 78000), 2) ?></td>
                </tr>
                <tr>
                    <td class="text-muted"><i class="bi bi-patch-check-fill text-warning me-1"></i> Odisha State Subsidy:</td>
                    <td class="text-success fw-bold">- ₹<?= number_format((float)($lead['state_subsidy'] ?? 60000), 2) ?></td>
                </tr>
                <tr class="table-success small">
                    <td class="fw-bold text-success">Total Combined Govt. Subsidy:</td>
                    <td class="text-success fw-bold fs-6">- ₹<?= number_format((float)(($lead['subsidy_amount'] ?? 78000) + ($lead['state_subsidy'] ?? 60000)), 2) ?></td>
                </tr>
                <tr class="table-primary">
                    <td class="fw-bold" style="color: #0B2545;">Net Customer Payable Cost:</td>
                    <td class="text-primary fw-bold fs-6">₹<?= number_format((float)($lead['customer_payable_amount'] ?? 72000), 2) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="color: #0B2545;">Uploaded Documents</h5>
                <a href="<?= url('/customer/documents') ?>" class="btn btn-outline-primary btn-sm">Upload More</a>
            </div>
            <?php if (empty($documents)): ?>
                <div class="text-center py-4 text-muted small">
                    <i class="bi bi-file-earmark-arrow-up fs-2 mb-2 d-block text-warning"></i>
                    Please upload your Electricity Bill and Aadhaar card to speed up DISCOM approvals.
                </div>
            <?php else: ?>
                <ul class="list-group list-group-flush small">
                    <?php foreach ($documents as $doc): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div><i class="bi bi-check-circle-fill text-success me-1"></i> <?= htmlspecialchars($doc['document_title']) ?></div>
                            <span class="badge bg-light text-dark border"><?= htmlspecialchars($doc['status']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
