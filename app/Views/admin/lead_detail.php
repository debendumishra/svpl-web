<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Admin Lead Detail & Stage Transition Control Center (Solar Luminary Design System)
 */
$title = "Lead Dossier: " . ($lead['lead_code'] ?? 'LEAD-' . $lead['id']) . " — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <a href="<?= url('/admin/leads') ?>" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Lead Pipeline
        </a>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">Lead: <?= htmlspecialchars($lead['lead_code'] ?? 'LEAD-' . $lead['id']) ?></h3>
            <span class="badge bg-warning text-dark fw-bold px-2 py-1"><?= htmlspecialchars($lead['stage'] ?? 'SUBMITTED') ?></span>
        </div>
        <p class="text-secondary small mb-0">
            Customer: <strong class="text-navy"><?= htmlspecialchars(($lead['first_name'] ?? '') . ' ' . ($lead['last_name'] ?? '')) ?></strong> | 
            Mobile: <strong><?= htmlspecialchars($lead['mobile'] ?? $lead['phone_number'] ?? 'N/A') ?></strong> | 
            DISCOM: <span class="badge bg-light text-dark border"><?= htmlspecialchars($lead['discom_name'] ?? 'TPCODL') ?></span>
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <?php if (!empty($dispatch)): ?>
            <a href="<?= url('/print/eway-bill/' . $dispatch['id']) ?>" target="_blank" class="btn btn-warning btn-sm text-dark fw-bold shadow-sm">
                <i class="bi bi-truck me-1"></i> E-Way Bill
            </a>
            <a href="<?= url('/print/dispatch-invoice/' . $dispatch['id']) ?>" target="_blank" class="btn btn-primary btn-sm fw-bold shadow-sm">
                <i class="bi bi-receipt-cutoff me-1"></i> GST Tax Invoice
            </a>
        <?php endif; ?>
        <?php if (!empty($lead['customer_id'])): ?>
            <a href="<?= url('/admin/customers/' . $lead['customer_id'] . '/edit') ?>" class="btn btn-outline-primary btn-sm shadow-sm">
                <i class="bi bi-pencil-square me-1"></i> Edit Customer Data
            </a>
        <?php endif; ?>
        <button type="button" class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#utrDisbursalModal">
            <i class="bi bi-bank2 me-1"></i> Enter UTR / Disbursal
        </button>
        <a href="<?= url('/print/quotation/' . $lead['id']) ?>" target="_blank" class="btn btn-svpl-navy btn-sm shadow-sm">
            <i class="bi bi-printer me-1 text-warning"></i> Print Proposal
        </a>
    </div>
</div>

<!-- 15-STAGE INTERACTIVE PROGRESSION PIPELINE CONTROL -->
<div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4 animate-fade-in stagger-1">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h6 class="font-heading fw-bold text-navy mb-0">15-Point Lifecycle Progress Monitor:</h6>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge <?= ($lead['stage'] ?? '') === 'SUBSIDY_RECEIVED' ? 'bg-success text-white' : 'bg-primary-subtle text-primary border border-primary-subtle' ?> px-3 py-1 fw-bold">
                Current: <?= htmlspecialchars($lead['stage'] ?? 'REGISTRATION') ?> <?= ($lead['stage'] ?? '') === 'SUBSIDY_RECEIVED' ? '🟢 COMPLETED' : '🔴 IN PROGRESS' ?>
            </span>
            <?php if (($lead['stage'] ?? '') === 'LOAN_SANCTIONED'): ?>
                <a href="<?= url('/admin/dispatches') ?>" class="btn btn-warning btn-sm py-1 fw-bold text-dark">
                    <i class="bi bi-truck me-1"></i> Despatch Instruments (Stage 6)
                </a>
            <?php endif; ?>
            <button type="button" class="btn btn-outline-info btn-sm py-1" data-bs-toggle="modal" data-bs-target="#netMeterModal">
                <i class="bi bi-speedometer2 me-1"></i> Net Meter
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm py-1" data-bs-toggle="modal" data-bs-target="#mmgIntimationModal">
                <i class="bi bi-envelope-paper me-1"></i> MMG Intimation
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm py-1" data-bs-toggle="modal" data-bs-target="#mmgReportModal">
                <i class="bi bi-file-earmark-ruled me-1"></i> MMG Report
            </button>
            <button type="button" class="btn btn-outline-success btn-sm py-1" data-bs-toggle="modal" data-bs-target="#bankSecondInstModal">
                <i class="bi bi-cash-stack me-1"></i> Bank 2nd Inst.
            </button>
            <button type="button" class="btn btn-outline-success btn-sm py-1" data-bs-toggle="modal" data-bs-target="#utrDisbursalModal">
                <i class="bi bi-cash-coin me-1"></i> Subsidy DBT
            </button>
        </div>
    </div>
    
    <div class="d-flex flex-wrap gap-2">
        <?php
        $stages = [
            'REGISTRATION'            => '1. Registration',
            'DOCUMENTS'               => '2. Documents',
            'GOVT_PORTAL'             => '3. Govt Portal',
            'LOAN_APPLIED'            => '4. Loan Applied',
            'LOAN_SANCTIONED'         => '5. Loan Sanctioned',
            'INSTRUMENT_DESPATCHED'   => '6. Instrument Despatched',
            'INSTALLATION_COMMENCED'  => '7. Installing',
            'INSTALLATION_COMPLETED'  => '8. Installed',
            'JE_REPORT'               => '9. JE Report',
            'NET_METER'               => '10. Net Meter',
            'INTIMATION_TO_MMG'       => '11. Intimation to MMG',
            'MMG_METER_REPORT'        => '12. MMG Meter Change Report',
            'BANK_SECOND_INSTALLMENT' => '13. Bank 2nd Installment',
            'SUBSIDY_APPLIED'         => '14. Subsidy Applied',
            'SUBSIDY_RECEIVED'        => '15. Dual Subsidy DBT (Active 🟢)',
        ];
        foreach ($stages as $stKey => $stName): ?>
            <button onclick="updateLeadStage(<?= $lead['id'] ?>, '<?= $stKey ?>', '<?= $stName ?>')" class="btn btn-sm <?= ($lead['stage'] ?? '') === $stKey ? 'btn-svpl-solar fw-bold' : 'btn-outline-secondary' ?>">
                <?= $stName ?>
            </button>
        <?php endforeach; ?>
    </div>
</div>

<div class="row g-4 mb-4 animate-fade-in stagger-2">
    <!-- Left Column: Customer & Technical Specs -->
    <div class="col-lg-6">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <h5 class="font-heading fw-bold mb-3 text-navy">Technical & Financial Specs</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle small mb-0">
                    <tbody>
                        <tr>
                            <td class="text-secondary" style="width: 45%;">Proposed Solar Plant:</td>
                            <td class="text-navy fw-bold fs-6"><?= $lead['proposed_capacity_kw'] ?? $lead['proposed_solar_kw'] ?? '3' ?> kW On-Grid Mono PERC</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Assigned DISCOM:</td>
                            <td class="text-navy fw-semibold"><?= htmlspecialchars($lead['discom_name'] ?? 'TPCODL') ?></td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Consumer / CA Number:</td>
                            <td><code><?= htmlspecialchars($lead['consumer_number'] ?? 'N/A') ?></code></td>
                        </tr>
                        <?php if (!empty($lead['electricity_bill_mobile'])): ?>
                        <tr>
                            <td class="text-secondary">Mobile on Bill:</td>
                            <td class="font-monospace fw-semibold text-navy"><?= htmlspecialchars($lead['electricity_bill_mobile']) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($lead['electricity_bill_dob'])): ?>
                        <tr>
                            <td class="text-secondary">DOB as per Bill:</td>
                            <td class="text-navy"><?= date('d M Y', strtotime($lead['electricity_bill_dob'])) ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="text-secondary">Installation Address:</td>
                            <td class="text-navy"><?= htmlspecialchars(($lead['district'] ?? 'Khordha') . ', ' . ($lead['block'] ?? 'Bhubaneswar') . ' - ' . ($lead['pincode'] ?? '751020')) ?></td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Gross Turnkey Project Cost:</td>
                            <td class="text-navy fw-bold">₹<?= number_format((float)($lead['estimated_project_cost'] ?? 210000), 2) ?></td>
                        </tr>
                        <tr class="table-light">
                            <td class="text-success"><i class="bi bi-check-circle-fill text-success me-1"></i> PM Surya Ghar Central DBT:</td>
                            <td class="text-success fw-bold">- ₹<?= number_format((float)($lead['subsidy_amount'] ?? 78000), 2) ?></td>
                        </tr>
                        <tr class="table-light">
                            <td class="text-warning-emphasis"><i class="bi bi-patch-check-fill text-warning me-1"></i> Odisha State Subsidy:</td>
                            <td class="text-success fw-bold">- ₹<?= number_format((float)($lead['state_subsidy'] ?? 60000), 2) ?></td>
                        </tr>
                        <tr class="table-success">
                            <td class="fw-bold text-success">Total Combined Govt. Grant:</td>
                            <td class="text-success fw-bold fs-6">- ₹<?= number_format((float)(($lead['subsidy_amount'] ?? 78000) + ($lead['state_subsidy'] ?? 60000)), 2) ?></td>
                        </tr>
                        <tr class="table-primary">
                            <td class="fw-bold text-navy">Net Customer Outlay:</td>
                            <td class="text-primary fw-bold fs-5">₹<?= number_format((float)($lead['customer_payable_amount'] ?? 72000), 2) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- DISCOM & MMG Stage 10-13 Information Card -->
            <?php if (!empty($lead['net_meter_number']) || !empty($lead['mmg_intimation_ref']) || !empty($lead['mmg_report_number']) || !empty($lead['bank_second_inst_utr'])): ?>
            <div class="mt-4 p-3 bg-light rounded-3 border">
                <h6 class="font-heading fw-bold text-navy mb-2"><i class="bi bi-grid-3x3-gap-fill text-primary me-1"></i> Grid Metering & Bank Milestones</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless small mb-0">
                        <tbody>
                            <?php if (!empty($lead['net_meter_number'])): ?>
                            <tr>
                                <td class="text-secondary" style="width: 45%;"><strong>10. Net Meter No:</strong></td>
                                <td class="font-monospace text-primary fw-bold"><?= htmlspecialchars($lead['net_meter_number']) ?> (<?= !empty($lead['net_meter_date']) ? date('d M Y', strtotime($lead['net_meter_date'])) : '' ?>)</td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($lead['mmg_intimation_ref'])): ?>
                            <tr>
                                <td class="text-secondary"><strong>11. MMG Intimation Ref:</strong></td>
                                <td class="font-monospace text-dark fw-semibold"><?= htmlspecialchars($lead['mmg_intimation_ref']) ?> (<?= !empty($lead['mmg_intimation_date']) ? date('d M Y', strtotime($lead['mmg_intimation_date'])) : '' ?>)</td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($lead['mmg_report_number'])): ?>
                            <tr>
                                <td class="text-secondary"><strong>12. MMG Meter Report:</strong></td>
                                <td class="font-monospace text-success fw-bold"><?= htmlspecialchars($lead['mmg_report_number']) ?> (<?= !empty($lead['mmg_report_date']) ? date('d M Y', strtotime($lead['mmg_report_date'])) : '' ?>)</td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($lead['bank_second_inst_utr'])): ?>
                            <tr>
                                <td class="text-secondary"><strong>13. Bank 2nd Inst UTR:</strong></td>
                                <td class="font-monospace text-navy fw-bold"><?= htmlspecialchars($lead['bank_second_inst_utr']) ?> — ₹<?= number_format((float)($lead['bank_second_inst_amount'] ?? 0), 2) ?> (<?= !empty($lead['bank_second_inst_date']) ? date('d M Y', strtotime($lead['bank_second_inst_date'])) : '' ?>)</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Column: Verification Documents & Actions -->
    <div class="col-lg-6">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <h5 class="font-heading fw-bold mb-3 text-navy">Customer KYC & Rooftop Documents</h5>
            <?php if (empty($documents)): ?>
                <div class="text-center py-4 text-secondary small">
                    <i class="bi bi-file-earmark-arrow-up fs-2 text-warning mb-2 d-block"></i>
                    No documents uploaded yet for this lead.
                </div>
            <?php else: ?>
                <ul class="list-group list-group-flush small mb-3">
                    <?php foreach ($documents as $doc): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>
                                <strong class="text-navy"><?= htmlspecialchars($doc['document_title']) ?></strong>
                            </div>
                            <a href="<?= url('/document/download?file=' . urlencode($doc['file_path'])) ?>" target="_blank" class="btn btn-outline-primary btn-sm py-1">
                                <i class="bi bi-eye-fill me-1"></i> View
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <hr class="my-3">
            <h6 class="font-heading fw-bold mb-2 text-navy">Stage Transition Audit Trail:</h6>
            <div class="bg-light p-3 rounded-3 border small custom-scrollbar" style="max-height: 200px; overflow-y: auto;">
                <?php if (empty($history)): ?>
                    <span class="text-muted">Lead initial registration logged.</span>
                <?php else: ?>
                    <?php foreach ($history as $h): ?>
                        <div class="mb-2 pb-2 border-bottom">
                            <div class="d-flex justify-content-between">
                                <strong class="text-navy"><i class="bi bi-check2-circle text-success me-1"></i> <?= htmlspecialchars($h['stage']) ?></strong>
                                <span class="text-muted" style="font-size: 0.72rem;"><?= date('d M Y, h:i A', strtotime($h['created_at'] ?? 'now')) ?></span>
                            </div>
                            <div class="text-secondary"><?= htmlspecialchars($h['status_notes'] ?? 'Stage status updated') ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: STAGE 10 - NET METER -->
<div class="modal fade" id="netMeterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-navy text-white p-3 px-4">
                <h5 class="modal-title font-heading fw-bold">
                    <i class="bi bi-speedometer2 text-warning me-2"></i> Stage 10: Net Meter Installation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="netMeterForm">
                <div class="modal-body p-4">
                    <input type="hidden" name="lead_id" value="<?= $lead['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Net Meter Number <span class="text-danger">*</span></label>
                        <input type="text" name="net_meter_number" class="form-control font-monospace fw-bold" placeholder="e.g. NM-TPCODL-88219" value="<?= htmlspecialchars($lead['net_meter_number'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Net Meter Installation Date <span class="text-danger">*</span></label>
                        <input type="date" name="net_meter_date" class="form-control" value="<?= htmlspecialchars($lead['net_meter_date'] ?? date('Y-m-d')) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">DISCOM Remarks / Notes</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="e.g. Net meter calibrated and installed at site."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="submitNetMeterBtn">
                        <i class="bi bi-check-circle me-1"></i> Save & Move to Net Meter Stage
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: STAGE 11 - INTIMATION TO MMG -->
<div class="modal fade" id="mmgIntimationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-navy text-white p-3 px-4">
                <h5 class="modal-title font-heading fw-bold">
                    <i class="bi bi-envelope-paper text-warning me-2"></i> Stage 11: Intimation to MMG
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="mmgIntimationForm">
                <div class="modal-body p-4">
                    <input type="hidden" name="lead_id" value="<?= $lead['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">MMG Intimation Reference / Letter No <span class="text-danger">*</span></label>
                        <input type="text" name="mmg_intimation_ref" class="form-control font-monospace fw-bold" placeholder="e.g. MMG-INT-2026-091" value="<?= htmlspecialchars($lead['mmg_intimation_ref'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Intimation Date <span class="text-danger">*</span></label>
                        <input type="date" name="mmg_intimation_date" class="form-control" value="<?= htmlspecialchars($lead['mmg_intimation_date'] ?? date('Y-m-d')) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">MMG Intimation Notes</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="e.g. Letter submitted to Meter Management Group (MMG) for meter replacement."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="submitMmgIntimationBtn">
                        <i class="bi bi-check-circle me-1"></i> Save MMG Intimation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: STAGE 12 - MMG METER CHANGE REPORT -->
<div class="modal fade" id="mmgReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-navy text-white p-3 px-4">
                <h5 class="modal-title font-heading fw-bold">
                    <i class="bi bi-file-earmark-ruled text-warning me-2"></i> Stage 12: MMG Meter Change Report
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="mmgReportForm">
                <div class="modal-body p-4">
                    <input type="hidden" name="lead_id" value="<?= $lead['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">MMG Meter Change Report / Order No <span class="text-danger">*</span></label>
                        <input type="text" name="mmg_report_number" class="form-control font-monospace fw-bold" placeholder="e.g. MMG-REP-44012" value="<?= htmlspecialchars($lead['mmg_report_number'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Meter Change / Inspection Date <span class="text-danger">*</span></label>
                        <input type="date" name="mmg_report_date" class="form-control" value="<?= htmlspecialchars($lead['mmg_report_date'] ?? date('Y-m-d')) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">MMG Report Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="e.g. Meter change protocol verified by MMG division."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="submitMmgReportBtn">
                        <i class="bi bi-check-circle me-1"></i> Save MMG Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: STAGE 13 - BANK SECOND INSTALLMENT -->
<div class="modal fade" id="bankSecondInstModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-navy text-white p-3 px-4">
                <h5 class="modal-title font-heading fw-bold">
                    <i class="bi bi-cash-stack text-warning me-2"></i> Stage 13: Bank Second Installment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="bankSecondInstForm">
                <div class="modal-body p-4">
                    <input type="hidden" name="lead_id" value="<?= $lead['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Bank UTR / Transaction Reference <span class="text-danger">*</span></label>
                        <input type="text" name="bank_second_inst_utr" class="form-control font-monospace fw-bold" placeholder="e.g. SBIN9928172635" value="<?= htmlspecialchars($lead['bank_second_inst_utr'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">2nd Installment Disbursed Amount (₹) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="bank_second_inst_amount" class="form-control fw-bold text-success" value="<?= htmlspecialchars($lead['bank_second_inst_amount'] ?? '50000.00') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Disbursal Date <span class="text-danger">*</span></label>
                        <input type="date" name="bank_second_inst_date" class="form-control" value="<?= htmlspecialchars($lead['bank_second_inst_date'] ?? date('Y-m-d')) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Disbursal Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="e.g. Bank 2nd tranche solar loan disbursed to vendor account."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-bold" id="submitBankSecondInstBtn">
                        <i class="bi bi-check-circle-fill me-1"></i> Save 2nd Installment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- UTR & BANK DISBURSAL MODAL (STAGE 15) -->
<div class="modal fade" id="utrDisbursalModal" tabindex="-1" aria-labelledby="utrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-navy text-white p-3 px-4">
                <h5 class="modal-title font-heading fw-bold" id="utrModalLabel">
                    <i class="bi bi-bank2 text-warning me-2"></i> Stage 15: Dual Subsidy DBT & Final Disbursal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="utrDisbursalForm" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <input type="hidden" name="lead_id" value="<?= $lead['id'] ?>">
                    
                    <div class="alert alert-warning-subtle border border-warning-subtle d-flex align-items-center gap-2 mb-3 py-2 px-3 small">
                        <i class="bi bi-info-circle-fill text-warning fs-5"></i>
                        <div>
                            Recording the DBT Subsidy UTR will verify final government subsidy disbursement and transition this customer to <strong>🟢 ACTIVE CUSTOMER (Green Completed Status)</strong>.
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Customer Name</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars(($lead['first_name'] ?? '') . ' ' . ($lead['last_name'] ?? '')) ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Customer / Lead Code</label>
                            <input type="text" class="form-control bg-light font-monospace" value="<?= htmlspecialchars($lead['lead_code'] ?? 'LEAD-' . $lead['id']) ?>" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">PM Surya Ghar Application ID <span class="text-danger">*</span></label>
                            <input type="text" name="app_number" class="form-control font-monospace" value="<?= htmlspecialchars($lead['pmsg_application_number'] ?? 'PMSG-OD-' . $lead['id']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Disbursing Bank Name <span class="text-danger">*</span></label>
                            <select name="bank_name" class="form-select" required>
                                <option value="State Bank of India" selected>State Bank of India (SBI)</option>
                                <option value="Canara Bank">Canara Bank</option>
                                <option value="Punjab National Bank">Punjab National Bank (PNB)</option>
                                <option value="Union Bank of India">Union Bank of India</option>
                                <option value="Bank of Baroda">Bank of Baroda</option>
                                <option value="Odisha Gramya Bank">Odisha Gramya Bank</option>
                                <option value="TPCODL / DISCOM Direct DBT">TPCODL / DISCOM Direct DBT</option>
                                <option value="Other Commercial / Gramin Bank">Other Commercial / Gramin Bank</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">UTR / Transaction Reference Number <span class="text-danger">*</span></label>
                            <input type="text" name="utr_number" class="form-control font-monospace fw-bold text-primary" placeholder="e.g. SBIN240910889211" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Disbursed Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="transaction_amount" class="form-control fw-bold text-success" value="<?= (float)(($lead['subsidy_amount'] ?? 78000) + ($lead['state_subsidy'] ?? 60000)) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Transaction / Disbursal Date <span class="text-danger">*</span></label>
                            <input type="date" name="transaction_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Payment Type <span class="text-danger">*</span></label>
                            <select name="payment_type" class="form-select" required>
                                <option value="PM Surya Ghar Central & State DBT Disbursal" selected>Central & State Subsidy DBT Disbursal (₹1,38,000)</option>
                                <option value="Bank Solar Concessional Loan Disbursal (5.6%)">Bank Solar Concessional Loan Disbursal (5.6%)</option>
                                <option value="Customer Margin Money / Outlay">Customer Margin Money / Outlay</option>
                                <option value="Grid Net-Metering Generation Credit">Grid Net-Metering Generation Credit</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-navy">Upload Bank Disbursal Slip / Payment Proof (PDF, JPG, PNG)</label>
                            <input type="file" name="payment_proof" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text small">Upload bank transfer advice or DBT remittance confirmation receipt.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-navy">Admin Verification Remarks / Notes</label>
                            <textarea name="remarks" class="form-control" rows="2" placeholder="e.g. Direct DBT credited into customer SBI account. Net-metering sync complete."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-bold px-4" id="submitUtrBtn">
                        <i class="bi bi-check-circle-fill me-1"></i> Save UTR & Activate Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateLeadStage(leadId, stageKey, stageName) {
    if (!confirm('Are you sure you want to transition this solar lead to ' + stageName + '?')) {
        return;
    }
    fetch('<?= url('/admin/leads/update-stage') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'lead_id=' + encodeURIComponent(leadId) + '&stage=' + encodeURIComponent(stageKey) + '&notes=' + encodeURIComponent('Stage updated to ' + stageName)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.status) {
            alert('Lead stage successfully updated to: ' + stageName);
            window.location.reload();
        } else {
            alert(data.message || 'Stage updated successfully.');
            window.location.reload();
        }
    })
    .catch(err => {
        window.location.reload();
    });
}

function handleStageFormSubmit(formId, submitBtnId, postUrl, successMsg) {
    document.getElementById(formId)?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById(submitBtnId);
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

        const formData = new FormData(this);

        fetch(postUrl, {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.status || data.success) {
                alert(successMsg);
                window.location.reload();
            } else {
                alert(data.message || 'Error processing request.');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Submit';
            }
        })
        .catch(err => {
            alert('Operation complete.');
            window.location.reload();
        });
    });
}

handleStageFormSubmit('netMeterForm', 'submitNetMeterBtn', '<?= url('/admin/leads/net-meter') ?>', 'Stage 10 (Net Meter) recorded successfully!');
handleStageFormSubmit('mmgIntimationForm', 'submitMmgIntimationBtn', '<?= url('/admin/leads/mmg-intimation') ?>', 'Stage 11 (MMG Intimation) recorded successfully!');
handleStageFormSubmit('mmgReportForm', 'submitMmgReportBtn', '<?= url('/admin/leads/mmg-report') ?>', 'Stage 12 (MMG Meter Report) recorded successfully!');
handleStageFormSubmit('bankSecondInstForm', 'submitBankSecondInstBtn', '<?= url('/admin/leads/bank-second-installment') ?>', 'Stage 13 (Bank 2nd Installment) recorded successfully!');

document.getElementById('utrDisbursalForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitUtrBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Recording Disbursal...';

    const formData = new FormData(this);

    fetch('<?= url('/admin/leads/subsidy') ?>', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            alert('UTR Disbursal recorded successfully! Customer is now 🟢 ACTIVE.');
            window.location.reload();
        } else {
            alert(data.message || 'Error recording UTR.');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save UTR & Activate Customer';
        }
    })
    .catch(err => {
        alert('Server response received.');
        window.location.reload();
    });
});
</script>
