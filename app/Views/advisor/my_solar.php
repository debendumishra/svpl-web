<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Advisor's Personal Rooftop Solar Connection (15-Stage Tracker & Dispatch Verification)
 */
$title = "My Rooftop Solar Project — " . company_name();

// Decode 20 items BOM if available
$itemsList = [];
if (!empty($dispatch['items_json'])) {
    $itemsList = is_array($dispatch['items_json']) ? $dispatch['items_json'] : json_decode($dispatch['items_json'], true);
}
$isAcknowledged = !empty($dispatch['customer_acknowledged']) && (int)$dispatch['customer_acknowledged'] === 1;
?>

<div class="container-fluid py-3 py-md-4">
    
    <!-- TOP NAVIGATION & STATUS BAR -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="<?= url('/advisor/dashboard') ?>" class="btn btn-outline-secondary btn-sm fw-bold d-inline-flex align-items-center gap-1 shadow-sm rounded-pill px-3">
            <i class="bi bi-arrow-left fs-6"></i> <span>Back to Advisor Dashboard</span>
        </a>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-bold">
                <i class="bi bi-house-door-fill me-1"></i> Personal Solar Connection: <?= htmlspecialchars($customer['customer_code'] ?? 'CUS') ?>
            </span>
            <?php if ($lead): ?>
                <a href="<?= url('/print/quotation/' . $lead['id']) ?>" target="_blank" class="btn btn-outline-primary btn-sm fw-semibold shadow-sm">
                    <i class="bi bi-file-earmark-pdf me-1 text-danger"></i> Proposal PDF
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- FLASH ALERTS -->
    <?php if (!empty($_SESSION['success_msg'])): ?>
        <div class="alert alert-success py-2 px-3 small d-flex align-items-center rounded-3 mb-3 border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2 fs-5 flex-shrink-0"></i> 
            <div><?= htmlspecialchars($_SESSION['success_msg']) ?></div>
        </div>
        <?php unset($_SESSION['success_msg']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error_msg'])): ?>
        <div class="alert alert-danger py-2 px-3 small d-flex align-items-center rounded-3 mb-3 border-0 shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 flex-shrink-0"></i> 
            <div><?= htmlspecialchars($_SESSION['error_msg']) ?></div>
        </div>
        <?php unset($_SESSION['error_msg']); ?>
    <?php endif; ?>

    <!-- PERSONAL CONNECTION HERO BANNER -->
    <div class="card p-3 p-md-4 mb-4 border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #061528 0%, #0f2d59 60%, #1e3a8a 100%); border-radius: 20px; border-left: 5px solid #10b981 !important;">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success text-white fw-bold px-2 py-1">Personal Rooftop Project</span>
                    <span class="text-warning small fw-semibold">PM Surya Ghar Muft Bijli Yojana</span>
                </div>
                <h4 class="fw-bold font-heading mb-1 text-white">
                    <?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?>'s Solar Plant
                </h4>
                <div class="d-flex align-items-center gap-3 flex-wrap text-white-50 small mt-2">
                    <span><i class="bi bi-lightning-charge-fill text-warning me-1"></i> <strong><?= $customer['proposed_solar_kw'] ?? 3 ?> kW</strong> High-Efficiency Mono PERC</span>
                    <span><i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= htmlspecialchars($customer['district'] ?? 'Bhubaneswar') ?>, <?= htmlspecialchars($customer['state'] ?? 'Odisha') ?></span>
                    <span><i class="bi bi-building text-info me-1"></i> DISCOM: <?= htmlspecialchars($customer['discom_name'] ?? 'TPCODL') ?></span>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-20 d-inline-block text-center w-100 w-md-auto">
                    <div class="small text-white-50 text-uppercase fw-bold" style="font-size: 0.7rem;">Dual Subsidy Total</div>
                    <div class="text-success fw-bold fs-4 font-heading mb-0">₹1,38,000</div>
                    <span class="badge bg-warning text-dark mt-1" style="font-size: 0.68rem;">Central ₹78k + State ₹60k</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 15-STAGE LIVE INSTALLATION PROGRESS TRACKER -->
    <div class="card card-svpl p-3 p-md-4 bg-white border-0 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="font-heading fw-bold mb-0 text-navy">Live 15-Point Installation Milestone Tracker</h5>
                <span class="text-secondary small">Real-time status synced with OREDA & Odisha State DISCOM</span>
            </div>
            <span class="badge bg-primary text-white px-3 py-2 fw-bold">
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
            'JOINT_INSPECTION'        => '9. Inspection',
            'NET_METER_APPLIED'       => '10. Meter Applied',
            'NET_METER_TESTING'       => '11. Meter Testing',
            'NET_METER_INSTALLED'     => '12. Meter Installed',
            'COMMISSIONING'           => '13. Commissioned',
            'STATE_SUBSIDY'           => '14. State Subsidy',
            'CENTRAL_SUBSIDY'         => '15. Subsidy DBT'
        ];
        $currentStageKey = $lead['stage'] ?? 'REGISTRATION';
        $stageKeys = array_keys($stages);
        $currentIndex = array_search($currentStageKey, $stageKeys);
        if ($currentIndex === false) $currentIndex = 0;
        ?>

        <!-- Progress Bar -->
        <div class="progress mb-4" style="height: 10px; border-radius: 6px; background-color: #e2e8f0;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                 role="progressbar" 
                 style="width: <?= round((($currentIndex + 1) / count($stages)) * 100) ?>%;" 
                 aria-valuenow="<?= $currentIndex + 1 ?>" 
                 aria-valuemin="1" 
                 aria-valuemax="15">
            </div>
        </div>

        <!-- 15 Stage Badges Grid -->
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-2">
            <?php foreach ($stages as $key => $label): 
                $stageIdx = array_search($key, $stageKeys);
                $isCompleted = $stageIdx < $currentIndex;
                $isCurrent = $stageIdx === $currentIndex;
            ?>
                <div class="col">
                    <div class="p-2 rounded-3 text-center border h-100 d-flex flex-column justify-content-between <?= $isCurrent ? 'bg-primary text-white shadow-sm border-primary' : ($isCompleted ? 'bg-success-subtle text-success border-success-subtle' : 'bg-light text-muted') ?>" style="font-size: 0.76rem;">
                        <div class="fw-bold mb-1">
                            <?php if ($isCompleted): ?>
                                <i class="bi bi-check-circle-fill me-1"></i>
                            <?php elseif ($isCurrent): ?>
                                <i class="bi bi-arrow-right-circle-fill text-warning me-1"></i>
                            <?php else: ?>
                                <i class="bi bi-circle me-1 opacity-50"></i>
                            <?php endif; ?>
                            <?= $label ?>
                        </div>
                        <div style="font-size: 0.65rem;">
                            <?= $isCompleted ? 'Completed' : ($isCurrent ? 'In Progress' : 'Upcoming') ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (!empty($dispatch)): ?>
    <!-- EQUIPMENT DISPATCH & TRANSIT DETAILS -->
    <div class="card card-svpl border-0 shadow-sm mb-4">
        <div class="card-header bg-navy text-white p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 rounded-3 bg-warning-subtle text-dark">
                    <i class="bi bi-box-seam-fill fs-5"></i>
                </div>
                <div>
                    <h5 class="font-heading fw-bold text-navy mb-0 text-white">Dispatched Solar Equipment & Bill of Materials</h5>
                    <span class="text-white-50 small">Transit logistics, assigned solar engineer, and itemized 20-component verification</span>
                </div>
            </div>

            <div>
                <?php if ($isAcknowledged): ?>
                    <span class="badge bg-success text-white px-3 py-2 fw-bold d-inline-flex align-items-center gap-1 shadow-sm">
                        <i class="bi bi-check-circle-fill"></i> Receipt Confirmed on <?= date('d M Y', strtotime($dispatch['customer_acknowledged_at'] ?? 'now')) ?>
                    </span>
                <?php else: ?>
                    <button type="button" class="btn btn-success btn-sm fw-bold px-3 py-2 shadow-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalAdvisorAcknowledgeReceipt">
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
                            <span class="text-muted">LR / Consignment:</span>
                            <span class="font-monospace fw-bold text-navy"><?= htmlspecialchars($dispatch['tracking_number'] ?? 'LR-SVPL') ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">Vehicle Number:</span>
                            <span class="font-monospace fw-bold text-primary"><?= htmlspecialchars($dispatch['vehicle_number'] ?: 'Direct Transit') ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">Driver:</span>
                            <span class="fw-semibold text-dark">
                                <?= htmlspecialchars($dispatch['driver_name'] ?: 'Driver') ?>
                                <?php if (!empty($dispatch['driver_mobile'])): ?>
                                    • <a href="tel:<?= htmlspecialchars($dispatch['driver_mobile']) ?>" class="text-success text-decoration-none fw-bold"><i class="bi bi-telephone"></i> Call</a>
                                <?php endif; ?>
                            </span>
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
                        <i class="bi bi-person-badge-fill text-warning me-1"></i> Assigned Solar Field Engineer
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
                <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
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

    <!-- MODAL: ACKNOWLEDGE RECEIPT (ADVISOR VIEW) -->
    <div class="modal fade" id="modalAdvisorAcknowledgeReceipt" tabindex="-1" aria-labelledby="modalAdvAckLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-navy text-white py-3">
                    <h5 class="modal-title font-heading fw-bold d-flex align-items-center gap-2" id="modalAdvAckLabel">
                        <i class="bi bi-box-seam-fill text-warning"></i> Confirm Solar Equipment Delivery
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?= url('/advisor/acknowledge-dispatch') ?>">
                    <input type="hidden" name="dispatch_id" value="<?= $dispatch['id'] ?>">
                    <div class="modal-body p-4">
                        <div class="text-center mb-3">
                            <div class="avatar-circle bg-success-subtle text-success fw-bold d-flex align-items-center justify-content-center rounded-circle mx-auto mb-2" style="width: 50px; height: 50px; font-size: 1.5rem;">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <h6 class="fw-bold text-navy mb-1">Confirm Delivery at Your Rooftop Premises</h6>
                            <p class="small text-muted mb-0">Please verify that all solar components have arrived in good condition.</p>
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
                                <span class="text-muted">Engineer:</span>
                                <span class="fw-bold text-dark"><?= htmlspecialchars($dispatch['engineer_name'] ?? 'SVPL Solar Engineer') ?></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-navy">Remarks / Comments (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="e.g. All solar panels and inverter received safely."></textarea>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="ackAdvCheck" required checked>
                            <label class="form-check-label small text-navy fw-semibold" for="ackAdvCheck">
                                I confirm that all solar equipment has arrived at my premises and is ready for mounting installation.
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3">
                        <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success fw-bold px-4">
                            <i class="bi bi-check-circle-fill me-1"></i> Confirm & Notify Engineer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- FINANCIAL LEDGER & SUBSIDY BREAKDOWN -->
    <div class="row g-4 mb-4">
        <!-- Left Column: Financial Ledger -->
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
                                <td class="fw-bold text-navy">Effective Net Investment:</td>
                                <td class="text-primary fw-bold fs-5">₹<?= number_format((float)($lead['customer_payable_amount'] ?? 72000), 2) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-success-subtle text-dark border-success-subtle py-2 px-3 small d-flex justify-content-between align-items-center mb-0">
                    <span><i class="bi bi-bank text-success me-1"></i> Solar Loan @ 5.6% p.a.:</span>
                    <strong class="text-success fs-6">₹785 / month EMI</strong>
                </div>
            </div>
        </div>

        <!-- Right Column: Document Locker -->
        <div class="col-lg-6">
            <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-heading fw-bold mb-0 text-navy">KYC & Rooftop Feasibility Locker</h5>
                    <span class="badge bg-success-subtle text-success">Verified ✓</span>
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

                <div class="mt-4 p-3 bg-light rounded-3 border">
                    <div class="fw-bold text-navy small mb-1"><i class="bi bi-headset text-primary me-1"></i> Dedicated Installation Desk</div>
                    <p class="text-muted small mb-0">For questions regarding your rooftop physical installation, net meter sync, or subsidy DBT credit, reach our central engineering team.</p>
                </div>
            </div>
        </div>
    </div>

</div>
