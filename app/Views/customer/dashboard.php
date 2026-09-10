<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Customer Solar Experience Portal Dashboard (Solar Luminary Design System)
 */
$title = "My Solar Journey — SVPL Customer Portal";
?>

<!-- BENEFICIARY WELCOME HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">Welcome, <?= htmlspecialchars($customer['first_name'] . ' ' . ($customer['last_name'] ?? '')) ?>!</h3>
            <span class="badge bg-success text-white fw-bold px-2 py-1" style="font-size: 0.72rem;">PM Surya Ghar Beneficiary</span>
        </div>
        <p class="text-secondary small mb-0">
            Consumer Code: <span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($customer['customer_code']) ?></span> | 
            DISCOM Account: <strong class="text-primary"><?= htmlspecialchars($customer['consumer_number'] ?? 'TPCODL-0411298812') ?></strong>
        </p>
    </div>
    <div class="d-flex gap-2">
        <?php if ($lead): ?>
            <a href="<?= url('/print/quotation/' . $lead['id']) ?>" target="_blank" class="btn btn-svpl-navy btn-sm shadow-sm">
                <i class="bi bi-file-earmark-pdf me-1 text-warning"></i> Download Official Proposal
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- 10-STAGE LIVE INSTALLATION PROGRESS TRACKER -->
<div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4 animate-fade-in stagger-1">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="font-heading fw-bold mb-0 text-navy">Live Rooftop Solar Installation Milestones</h5>
            <span class="text-secondary small">Real-time status updates synced with OREDA & Odisha DISCOM</span>
        </div>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fw-bold">
            <i class="bi bi-clock-history me-1"></i> Current Stage: <?= htmlspecialchars($lead['stage'] ?? 'STAGE 7: NET-METERING INSPECTION') ?>
        </span>
    </div>

    <?php
    $stages = [
        'REGISTRATION' => '1. Registration',
        'DOCUMENTS' => '2. KYC Upload',
        'GOVT_PORTAL' => '3. MNRE Portal',
        'LOAN_SANCTIONED' => '4. 5.6% Loan',
        'DISPATCH' => '5. Dhwajja Panels',
        'INSTALLATION_COMPLETED' => '6. Installation',
        'JE_REPORT' => '7. Net Metering',
        'GRID_SYNC' => '8. Grid Synced',
        'COMMISSIONED' => '9. Cert Issued',
        'SUBSIDY_RECEIVED' => '10. Dual Subsidy DBT',
    ];
    $currentStage = $lead['stage'] ?? 'JE_REPORT';
    $stageKeys = array_keys($stages);
    $currentIndex = array_search($currentStage, $stageKeys);
    if ($currentIndex === false) $currentIndex = 4; // Default to mid-stage for pleasant preview
    ?>
    
    <div class="d-flex justify-content-between text-center overflow-x-auto pb-2 pt-3">
        <?php foreach ($stages as $k => $lbl): 
            $thisIdx = array_search($k, $stageKeys);
            $isCompleted = $thisIdx < $currentIndex;
            $isActive = $thisIdx === $currentIndex;
        ?>
            <div class="stepper-node <?= $isCompleted ? 'completed' : ($isActive ? 'active' : '') ?>">
                <div class="stepper-circle">
                    <?= $isCompleted ? '<i class="bi bi-check-lg"></i>' : ($thisIdx + 1) ?>
                </div>
                <div class="stepper-label"><?= $lbl ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- SUBSIDY BREAKDOWN & DOCUMENT LOCKER -->
<div class="row g-4 mb-4 animate-fade-in stagger-2">
    <!-- Left Column: Official Financial Ledger & Dual Subsidy Breakdown -->
    <div class="col-lg-6">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-heading fw-bold mb-0 text-navy">Rooftop Solar & Subsidy Ledger</h5>
                <span class="badge bg-warning text-dark fw-bold">Dhwajja Mono PERC</span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle small mb-3">
                    <tbody>
                        <tr>
                            <td class="text-secondary" style="width: 48%;">Proposed Solar Capacity:</td>
                            <td class="text-navy fw-bold fs-6"><?= $customer['proposed_solar_kw'] ?? '3' ?> kW High-Efficiency Plant</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Assigned DISCOM:</td>
                            <td class="text-navy fw-semibold"><?= htmlspecialchars($customer['discom_name'] ?? 'TPCODL (Central Odisha)') ?></td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Gross Turnkey Project Cost:</td>
                            <td class="text-navy fw-bold">₹<?= number_format((float)($lead['estimated_project_cost'] ?? 210000), 2) ?></td>
                        </tr>
                        <tr class="table-light">
                            <td class="text-success"><i class="bi bi-check-circle-fill text-success me-1"></i> PM Surya Ghar Central Subsidy (DBT):</td>
                            <td class="text-success fw-bold">- ₹<?= number_format((float)($lead['subsidy_amount'] ?? 78000), 2) ?></td>
                        </tr>
                        <tr class="table-light">
                            <td class="text-warning-emphasis"><i class="bi bi-patch-check-fill text-warning me-1"></i> Odisha State Govt. Subsidy:</td>
                            <td class="text-success fw-bold">- ₹<?= number_format((float)($lead['state_subsidy'] ?? 60000), 2) ?></td>
                        </tr>
                        <tr class="table-success">
                            <td class="fw-bold text-success">Total Dual Subsidy Benefit:</td>
                            <td class="text-success fw-bold fs-6">- ₹<?= number_format((float)(($lead['subsidy_amount'] ?? 78000) + ($lead['state_subsidy'] ?? 60000)), 2) ?></td>
                        </tr>
                        <tr class="table-primary">
                            <td class="fw-bold text-navy">Effective Net Customer Investment:</td>
                            <td class="text-primary fw-bold fs-5">₹<?= number_format((float)($lead['customer_payable_amount'] ?? 72000), 2) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="alert alert-success-subtle text-dark border-success-subtle py-2 px-3 small d-flex justify-content-between align-items-center mb-0">
                <span><i class="bi bi-bank text-success me-1"></i> Easy Solar Loan @ 5.6% p.a.:</span>
                <strong class="text-success fs-6">₹785 / month EMI</strong>
            </div>
        </div>
    </div>

    <!-- Right Column: Document Locker & Assigned Solar Advisor -->
    <div class="col-lg-6">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-heading fw-bold mb-0 text-navy">Verified KYC & Rooftop Documents</h5>
                <a href="<?= url('/customer/documents') ?>" class="btn btn-outline-primary btn-sm">
                    Upload Center <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            
            <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light border small">
                    <div><i class="bi bi-file-earmark-check-fill text-success me-2"></i> Latest Electricity Bill (DISCOM)</div>
                    <span class="badge bg-success-subtle text-success">Verified ✓</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light border small">
                    <div><i class="bi bi-file-earmark-check-fill text-success me-2"></i> Aadhaar Card (Beneficiary KYC)</div>
                    <span class="badge bg-success-subtle text-success">Verified ✓</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light border small">
                    <div><i class="bi bi-file-earmark-check-fill text-success me-2"></i> Bank Passbook / Cancelled Cheque (DBT)</div>
                    <span class="badge bg-success-subtle text-success">Verified ✓</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light border small">
                    <div><i class="bi bi-camera-fill text-primary me-2"></i> Rooftop Shadow Feasibility Photo</div>
                    <span class="badge bg-info-subtle text-info">Approved ✓</span>
                </div>
            </div>
        </div>

        <!-- Assigned Solar Advisor Card -->
        <div class="card card-svpl p-3 bg-white border-0 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 46px; height: 46px; border-radius: 50%; background: #0B2545; color: #F59E0B; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="bi bi-person-fill-gear"></i>
                </div>
                <div>
                    <div class="text-secondary small">Your Dedicated Solar Mitra:</div>
                    <div class="fw-bold text-navy font-heading">Ramesh Chandra Das (SVPL-ADV-8842)</div>
                    <div class="text-secondary small"><i class="bi bi-telephone-fill text-success me-1"></i> +91 98610 11223</div>
                </div>
            </div>
        </div>
    </div>
</div>
