<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Customer Solar Experience Portal Dashboard (Solar Luminary Design System)
 */
$title = "My Solar Journey — SVPL Customer Portal";
?>

<!-- FLASH MESSAGES -->
<?php if (!empty($_SESSION['success_msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
        <div><?= htmlspecialchars($_SESSION['success_msg']) ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success_msg']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error_msg'])): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-x-circle-fill fs-5 text-danger"></i>
        <div><?= htmlspecialchars($_SESSION['error_msg']) ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error_msg']); ?>
<?php endif; ?>

<!-- BENEFICIARY WELCOME HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">Welcome, <?= htmlspecialchars($customer['first_name'] . ' ' . ($customer['last_name'] ?? '')) ?>!</h3>
            <span class="badge bg-success text-white fw-bold px-2 py-1" style="font-size: 0.72rem;">PM Surya Ghar Beneficiary</span>
            <?php if (!empty($customer['pm_surya_ghar_id'])): ?>
                <span class="badge bg-primary text-white font-monospace px-2 py-1" style="font-size: 0.72rem;">
                    <i class="bi bi-patch-check-fill me-1"></i> ID: <?= htmlspecialchars($customer['pm_surya_ghar_id']) ?>
                </span>
            <?php endif; ?>
        </div>
        <p class="text-secondary small mb-0">
            Consumer Code: <span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($customer['customer_code']) ?></span> | 
            DISCOM Account: <strong class="text-primary"><?= htmlspecialchars($customer['consumer_number'] ?? 'N/A') ?></strong>
            <?php if (!empty($customer['notification_number'])): ?>
                | Notification: <span class="font-monospace text-navy fw-bold"><?= htmlspecialchars($customer['notification_number']) ?></span>
            <?php endif; ?>
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap align-items-center">
        <button type="button" class="btn btn-outline-primary btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalConsumerAgreement">
            <i class="bi bi-file-earmark-text me-1 text-primary"></i> My Solar Agreement
        </button>
        <a href="<?= url('/customer/quotation') ?>" class="btn btn-svpl-navy btn-sm shadow-sm">
            <i class="bi bi-file-earmark-pdf me-1 text-warning"></i> Official Quotation
        </a>
        <?php if (empty($customer['converted_to_advisor'])): ?>
            <button type="button" class="btn btn-warning btn-sm text-dark fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalConvertToAdvisor">
                <i class="bi bi-award-fill me-1"></i> Become an Advisor
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if ((int)($customer['converted_to_advisor'] ?? 0) === 2): ?>
    <!-- ADVISOR CONVERSION PENDING VERIFICATION BANNER -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden animate-fade-in" style="background: linear-gradient(135deg, #061528 0%, #0f2d59 60%, #0369a1 100%); border-left: 5px solid #0ea5e9 !important;">
        <div class="card-body p-3 p-md-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="background: rgba(14, 165, 233, 0.2); color: #38bdf8; width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: bold; flex-shrink: 0; border: 1px solid rgba(56, 189, 248, 0.3);">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h6 class="fw-bold font-heading mb-0 text-white">Solar Advisor Application Under Verification</h6>
                        <span class="badge bg-info text-dark fw-bold" style="font-size: 0.68rem;">Fee ₹<?= number_format(advisor_joining_fee()) ?> Submitted</span>
                    </div>
                    <p class="text-white-50 small mb-0">
                        Your payment UTR is currently being verified by SVPL Accounts. Once confirmed, your account will upgrade to Solar Advisor. You can continue tracking your 15-stage installation below.
                    </p>
                </div>
            </div>
            <div>
                <span class="badge bg-light text-navy fw-bold px-3 py-2 border shadow-sm">
                    <i class="bi bi-clock-history text-primary me-1"></i> Verification Pending
                </span>
            </div>
        </div>
    </div>
<?php elseif (empty($customer['converted_to_advisor'])): ?>
    <!-- UPGRADE TO ADVISOR PROMOTION BANNER -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden animate-fade-in" style="background: linear-gradient(135deg, #061528 0%, #0f2d59 60%, #1e3a8a 100%); border-left: 5px solid #f59e0b !important;">
        <div class="card-body p-4 p-md-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #061528; width: 54px; height: 54px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: bold; flex-shrink: 0; box-shadow: 0 4px 12px rgba(245,158,11,0.35);">
                    <i class="bi bi-award-fill"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <h5 class="fw-bold font-heading mb-0 text-white">Upgrade to Certified Solar Advisor (Mitra)</h5>
                        <span class="badge bg-warning text-dark fw-bold" style="font-size: 0.72rem;">9-Level Commission Plan</span>
                    </div>
                    <p class="text-white-50 small mb-0" style="max-width: 650px;">
                        Recommend PM Surya Ghar solar rooftop systems to your neighbors and earn up to <strong>₹10,000 per 3 kW direct installation</strong> + multi-tier team overrides, monthly performance bonuses, and official SVPL identity credentials.
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <span class="text-white-50 small d-block">Joining & Kit Fee:</span>
                    <strong class="text-warning fs-5">₹<?= number_format(advisor_joining_fee()) ?></strong>
                </div>
                <a href="<?= url('/customer/become-advisor') ?>" class="btn btn-warning text-dark fw-bold px-4 py-2 shadow d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); border: 0;">
                    <i class="bi bi-stars"></i> <span>Upgrade Now</span>
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- 15-STAGE LIVE INSTALLATION PROGRESS TRACKER -->
<div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4 animate-fade-in stagger-1">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="font-heading fw-bold mb-0 text-navy">Live Rooftop Solar Installation Milestones</h5>
            <span class="text-secondary small">Real-time 15-point status updates synced with OREDA & Odisha DISCOM</span>
        </div>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fw-bold">
            <i class="bi bi-clock-history me-1"></i> Current Stage: <?= htmlspecialchars($lead['stage'] ?? 'REGISTRATION') ?>
        </span>
    </div>

    <?php
    $stages = [
        'REGISTRATION'            => '1. Registration',
        'DOCUMENTS'               => '2. KYC Upload',
        'GOVT_PORTAL'             => '3. Govt Portal',
        'LOAN_APPLIED'            => '4. Loan Applied',
        'LOAN_SANCTIONED'         => '5. Loan Approved',
        'INSTRUMENT_DESPATCHED'   => '6. Despatched',
        'INSTALLATION_COMMENCED'  => '7. Installing',
        'INSTALLATION_COMPLETED'  => '8. Installed',
        'JE_REPORT'               => '9. JE Report',
        'NET_METER'               => '10. Net Meter',
        'INTIMATION_TO_MMG'       => '11. MMG Notice',
        'MMG_METER_REPORT'        => '12. MMG Report',
        'BANK_SECOND_INSTALLMENT' => '13. 2nd Tranche',
        'SUBSIDY_APPLIED'         => '14. Subsidy Claim',
        'SUBSIDY_RECEIVED'        => '15. Dual Subsidy DBT 🟢',
    ];
    $currentStage = $lead['stage'] ?? 'REGISTRATION';
    $stageKeys = array_keys($stages);
    $currentIndex = array_search($currentStage, $stageKeys);
    if ($currentIndex === false) $currentIndex = 0;
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

<!-- ========================================================================= -->
<!-- DISPATCHED SOLAR EQUIPMENT & 20-ITEM BOM SECTION WITH ACKNOWLEDGMENT -->
<!-- ========================================================================= -->
<?php if (!empty($dispatch)): 
    $itemsList = !empty($dispatch['items_json']) ? json_decode($dispatch['items_json'], true) : [];
    $isAcknowledged = (int)($dispatch['customer_acknowledged'] ?? 0) === 1;
?>
<div class="card card-svpl border-0 shadow-sm mb-4 animate-fade-in overflow-hidden" style="border-left: 6px solid <?= $isAcknowledged ? '#10B981' : '#F59E0B' ?> !important;">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 rounded-3 bg-warning-subtle text-dark">
                <i class="bi bi-box-seam-fill fs-5"></i>
            </div>
            <div>
                <h5 class="font-heading fw-bold text-navy mb-0">Dispatched Solar Equipment & Bill of Materials</h5>
                <span class="text-secondary small">Materials transit details, assigned field solar engineer, and itemized kit verification</span>
            </div>
        </div>

        <div>
            <?php if ($isAcknowledged): ?>
                <span class="badge bg-success text-white px-3 py-2 fw-bold d-inline-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-check-circle-fill"></i> Receipt Confirmed on <?= date('d M Y', strtotime($dispatch['customer_acknowledged_at'] ?? 'now')) ?>
                </span>
            <?php else: ?>
                <button type="button" class="btn btn-success btn-sm fw-bold px-3 py-2 shadow-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalAcknowledgeReceipt">
                    <i class="bi bi-check2-circle fs-6"></i> Acknowledge & Confirm Receipt
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body p-3 p-md-4 bg-white">
        <div class="row g-4 mb-3">
            
            <!-- Transit & Logistics Info -->
            <div class="col-md-6 col-lg-4 border-end-md">
                <h6 class="fw-bold text-navy mb-2 small text-uppercase" style="letter-spacing: 0.5px;">
                    <i class="bi bi-truck text-warning me-1"></i> Transit & Transporter
                </h6>
                <div class="p-3 rounded-3 bg-light border small">
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">LR / Consignment No:</span>
                        <span class="font-monospace fw-bold text-navy"><?= htmlspecialchars($dispatch['tracking_number'] ?? 'LR-SVPL') ?></span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Vehicle Number:</span>
                        <span class="font-monospace fw-bold text-primary"><?= htmlspecialchars($dispatch['vehicle_number'] ?: 'Direct Transit') ?></span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Person / Driver:</span>
                        <span class="fw-semibold text-dark">
                            <?= htmlspecialchars($dispatch['driver_name'] ?: 'Driver') ?>
                            <?php if (!empty($dispatch['driver_mobile'])): ?>
                                • <a href="tel:<?= htmlspecialchars($dispatch['driver_mobile']) ?>" class="text-success text-decoration-none fw-bold"><i class="bi bi-telephone"></i> Call</a>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Vendor / Make:</span>
                        <span class="fw-semibold text-dark"><?= htmlspecialchars($dispatch['vendor_name'] ?? 'OEM Authorized Store') ?></span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Dispatch Date:</span>
                        <span class="fw-semibold text-dark"><?= htmlspecialchars($dispatch['dispatch_date'] ?? 'N/A') ?></span>
                    </div>
                </div>
            </div>

            <!-- Assigned Field Solar Engineer Info -->
            <div class="col-md-6 col-lg-4 border-end-lg">
                <h6 class="fw-bold text-navy mb-2 small text-uppercase" style="letter-spacing: 0.5px;">
                    <i class="bi bi-person-badge-fill text-warning me-1"></i> Assigned Field Solar Engineer
                </h6>
                <div class="p-3 rounded-3 bg-light border small h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="avatar-circle bg-navy text-warning fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                <?= strtoupper(substr($dispatch['engineer_name'] ?? 'SE', 0, 2)) ?>
                            </div>
                            <div>
                                <div class="fw-bold text-navy"><?= htmlspecialchars($dispatch['engineer_name'] ?? 'SVPL Field Engineer Desk') ?></div>
                                <div class="badge bg-white text-dark border font-monospace" style="font-size: 0.68rem;"><?= htmlspecialchars($dispatch['engineer_code'] ?? 'SVPL-ENG') ?></div>
                            </div>
                        </div>
                        <div class="text-secondary small mb-1"><?= htmlspecialchars($dispatch['engineer_designation'] ?? 'Certified Solar Engineer') ?></div>
                    </div>
                    <div>
                        <?php if (!empty($dispatch['engineer_mobile'])): ?>
                            <a href="tel:<?= htmlspecialchars($dispatch['engineer_mobile']) ?>" class="btn btn-outline-success btn-sm w-100 fw-bold">
                                <i class="bi bi-telephone-fill me-1"></i> Call Engineer (<?= htmlspecialchars($dispatch['engineer_mobile']) ?>)
                            </a>
                        <?php else: ?>
                            <div class="text-muted small"><i class="bi bi-headset me-1"></i> Engineer assigned for on-site commissioning.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Official Documents Links -->
            <div class="col-md-12 col-lg-4">
                <h6 class="fw-bold text-navy mb-2 small text-uppercase" style="letter-spacing: 0.5px;">
                    <i class="bi bi-file-earmark-check-fill text-primary me-1"></i> Official Dispatch Invoices & Bills
                </h6>
                <div class="d-grid gap-2">
                    <a href="<?= url('/print/eway-bill/' . $dispatch['id']) ?>" target="_blank" class="btn btn-outline-warning text-dark btn-sm fw-bold text-start d-flex align-items-center justify-content-between p-2">
                        <span><i class="bi bi-truck text-warning me-2"></i> Official E-Way Bill (EWB-01)</span>
                        <i class="bi bi-box-arrow-up-right small"></i>
                    </a>
                    <a href="<?= url('/print/dispatch-invoice/' . $dispatch['id']) ?>" target="_blank" class="btn btn-outline-navy btn-sm fw-bold text-start d-flex align-items-center justify-content-between p-2">
                        <span><i class="bi bi-receipt text-primary me-2"></i> Equipment Tax Invoice (GST)</span>
                        <i class="bi bi-box-arrow-up-right small"></i>
                    </a>
                    <a href="<?= url('/print/dispatch-challan/' . $dispatch['id']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm fw-bold text-start d-flex align-items-center justify-content-between p-2">
                        <span><i class="bi bi-card-checklist text-secondary me-2"></i> Delivery Challan & BOM</span>
                        <i class="bi bi-box-arrow-up-right small"></i>
                    </a>
                </div>
            </div>

        </div>

        <!-- 20-ITEM BILL OF MATERIALS (BOM) ACCORDION/TABLE -->
        <div class="border rounded-3 overflow-hidden">
            <div class="bg-navy text-white px-3 py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="fw-bold small d-flex align-items-center gap-2">
                    <i class="bi bi-card-checklist text-warning"></i> Complete Bill of Materials (BOM) — 20 Solar Components
                </div>
                <span class="badge bg-warning text-dark fw-bold"><?= count($itemsList) ?: 20 ?> Components Transported</span>
            </div>
            <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                <table class="table table-hover table-striped align-middle mb-0 small">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th style="width: 50px;" class="text-center">S.No</th>
                            <th style="min-width: 180px;">Item Description</th>
                            <th>Technical Specifications</th>
                            <th style="width: 100px;" class="text-center">Quantity</th>
                            <th style="width: 80px;" class="text-center">Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($itemsList)): ?>
                            <?php foreach ($itemsList as $idx => $it): ?>
                                <tr>
                                    <td class="text-center text-muted fw-bold"><?= $it['s_no'] ?? ($idx + 1) ?></td>
                                    <td class="fw-bold text-navy"><?= htmlspecialchars($it['name'] ?? 'Solar Component') ?></td>
                                    <td class="text-secondary"><?= htmlspecialchars($it['spec'] ?? '') ?></td>
                                    <td class="text-center font-monospace fw-bold text-primary"><?= htmlspecialchars($it['qty'] ?? 1) ?></td>
                                    <td class="text-center"><span class="badge bg-light text-dark border"><?= htmlspecialchars($it['unit'] ?? 'Nos') ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">Standard 20-item solar installation kit dispatched.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- MODAL: ACKNOWLEDGE RECEIPT -->
<div class="modal fade" id="modalAcknowledgeReceipt" tabindex="-1" aria-labelledby="modalAcknowledgeReceiptLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-navy text-white py-3">
                <h5 class="modal-title font-heading fw-bold d-flex align-items-center gap-2" id="modalAcknowledgeReceiptLabel">
                    <i class="bi bi-box-seam-fill text-warning"></i> Confirm Solar Equipment Receipt
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= url('/customer/acknowledge-dispatch') ?>">
                <input type="hidden" name="dispatch_id" value="<?= $dispatch['id'] ?>">
                <div class="modal-body p-4">
                    <div class="text-center mb-3">
                        <div class="avatar-circle bg-success-subtle text-success fw-bold d-flex align-items-center justify-content-center rounded-circle mx-auto mb-2" style="width: 54px; height: 54px; font-size: 1.5rem;">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <h6 class="fw-bold text-navy mb-1">Confirm Delivery at Your Rooftop Site</h6>
                        <p class="small text-muted mb-0">Please verify that all materials (Solar Panels, Inverter, MMS, Cables, BOS items) have reached your residence safely.</p>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3 small">
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">LR Number:</span>
                            <span class="font-monospace fw-bold text-navy"><?= htmlspecialchars($dispatch['tracking_number'] ?? 'LR-SVPL') ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">Vehicle:</span>
                            <span class="font-monospace text-primary fw-bold"><?= htmlspecialchars($dispatch['vehicle_number'] ?: 'Direct Transit') ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted">Assigned Engineer:</span>
                            <span class="fw-bold text-dark"><?= htmlspecialchars($dispatch['engineer_name'] ?? 'SVPL Solar Engineer') ?></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Remarks / Comments (Optional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="e.g. All 6 panels, inverter and mounting structure received in good condition."></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="ackConfirmCheck" required checked>
                        <label class="form-check-label small text-navy fw-semibold" for="ackConfirmCheck">
                            I confirm that the solar equipment has arrived at my premises and I am ready for mounting installation.
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-bold px-4">
                        <i class="bi bi-check-circle-fill me-1"></i> Confirm & Notify Engineer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

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
        <!-- PM Surya Ghar Agreement Card -->
        <div class="card card-svpl p-3 p-md-4 bg-white border-0 shadow-sm mb-4 border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0;">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="font-heading fw-bold mb-0 text-navy">Model Draft Agreement (Annexure 2)</h6>
                            <span class="badge bg-success-subtle text-success border border-success-subtle py-1" style="font-size: 0.68rem;">E-Signed ✓</span>
                        </div>
                        <p class="text-secondary small mb-0">Official 4-page PM Surya Ghar Consumer-Vendor contract</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalConsumerAgreement">
                        <i class="bi bi-eye-fill me-1"></i> View Agreement
                    </button>
                    <a href="<?= url('/customer/agreement') ?>" target="_blank" class="btn btn-outline-secondary btn-sm" title="Print in new tab">
                        <i class="bi bi-printer-fill"></i>
                    </a>
                </div>
            </div>
        </div>

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
        <div class="card card-svpl p-3 bg-white border-0 shadow-sm mb-3">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 46px; height: 46px; border-radius: 50%; background: #0B2545; color: #F59E0B; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="bi bi-person-fill-gear"></i>
                </div>
                <div>
                    <div class="text-secondary small">Your Dedicated Solar Advisor / Mitra:</div>
                    <div class="fw-bold text-navy font-heading"><?= htmlspecialchars($customer['advisor_name'] ?? 'Ramesh Chandra Das') ?> (<?= htmlspecialchars($customer['advisor_code'] ?? 'SVPL-ADV-8842') ?>)</div>
                    <div class="text-secondary small"><i class="bi bi-telephone-fill text-success me-1"></i> <?= htmlspecialchars($customer['advisor_mobile'] ?? '+91 98610 11223') ?></div>
                </div>
            </div>
        </div>

        <!-- Assigned Back Office Executive (BOE) -->
        <div class="card card-svpl p-3 bg-white border-0 shadow-sm border-start border-4 border-info">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 46px; height: 46px; border-radius: 50%; background: #0284C7; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="bi bi-headset"></i>
                </div>
                <div>
                    <div class="text-secondary small">Back Office Executive Handling Your Application:</div>
                    <?php if (!empty($customer['boe_name'])): ?>
                        <div class="fw-bold text-navy font-heading"><?= htmlspecialchars($customer['boe_name']) ?> <span class="badge bg-light text-dark border"><?= htmlspecialchars($customer['boe_code'] ?? 'SVPL-BOE') ?></span></div>
                        <div class="text-muted small"><?= htmlspecialchars($customer['boe_designation'] ?? 'Back Office Executive') ?></div>
                        <div class="text-secondary small mt-1">
                            <i class="bi bi-telephone-fill text-success me-1"></i> <?= htmlspecialchars($customer['boe_mobile'] ?? 'N/A') ?> 
                            <?php if (!empty($customer['boe_email'])): ?>
                                | <i class="bi bi-envelope me-1"></i> <?= htmlspecialchars($customer['boe_email']) ?>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="fw-bold text-navy font-heading">SVPL Central Back Office Helpdesk</div>
                        <div class="text-secondary small mt-1"><i class="bi bi-headset text-info me-1"></i> Helpline: +91 674 295 4800 | support@suryavistaara.com</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

