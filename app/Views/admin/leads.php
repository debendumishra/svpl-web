<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Admin Lead Pipeline View (10-Stage Pipeline Filtering & Management)
 */
$title = "Lead Pipeline Management — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">15-Point Solar Lead Lifecycle Pipeline</h3>
            <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.72rem;">Odisha PM Surya Ghar</span>
        </div>
        <p class="text-secondary small mb-0">Track applications across registration, feasibility, 5.6% bank loans, instrument dispatch, installation, JE net-metering, MMG meter change, bank 2nd tranche, and ₹1.38L DBT subsidies.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/admin/dispatches') ?>" class="btn btn-outline-navy btn-sm shadow-sm">
            <i class="bi bi-truck me-1"></i> Despatch Desk
        </a>
        <a href="<?= url('/register-customer') ?>" class="btn btn-svpl-solar btn-sm shadow-sm">
            <i class="bi bi-plus-circle-fill me-1"></i> + New Lead
        </a>
        <a href="<?= url('/admin/export/csv?type=leads') ?>" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
        </a>
    </div>
</div>

<!-- 15 STAGE FILTER PILLS -->
<div class="d-flex gap-2 overflow-x-auto pb-3 mb-3 animate-fade-in stagger-1">
    <a href="<?= url('/admin/leads') ?>" class="btn btn-sm <?= empty($currentStage) ? 'btn-svpl-navy' : 'btn-outline-secondary' ?>">
        All 15 Stages
    </a>
    <?php
    $allStages = [
        'REGISTRATION'              => '1. Registration',
        'DOCUMENTS'                 => '2. Documents',
        'GOVT_PORTAL'               => '3. Govt Portal',
        'LOAN_APPLIED'              => '4. Loan Applied',
        'LOAN_SANCTIONED'           => '5. Loan Sanctioned',
        'INSTRUMENT_DESPATCHED'     => '6. Instrument Despatched',
        'INSTALLATION_COMMENCED'    => '7. Installing',
        'INSTALLATION_COMPLETED'    => '8. Installed',
        'JE_REPORT'                 => '9. JE Report',
        'NET_METER'                 => '10. Net Meter',
        'INTIMATION_TO_MMG'         => '11. Intimation MMG',
        'MMG_METER_REPORT'          => '12. MMG Meter Report',
        'BANK_SECOND_INSTALLMENT'   => '13. Bank 2nd Tranche',
        'SUBSIDY_APPLIED'           => '14. Subsidy Applied',
        'SUBSIDY_RECEIVED'          => '15. Subsidy Received',
    ];
    foreach ($allStages as $k => $lbl): ?>
        <a href="<?= url('/admin/leads?stage=' . $k) ?>" class="btn btn-sm <?= ($currentStage ?? '') === $k ? 'btn-svpl-solar' : 'btn-outline-secondary' ?> text-nowrap">
            <?= $lbl ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- LEADS DATA TABLE -->
<div class="card card-svpl p-4 bg-white border-0 shadow-sm animate-fade-in stagger-2">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small">
                <tr class="text-secondary text-uppercase">
                    <th>Lead Code</th>
                    <th>Customer Name & Contact</th>
                    <th>Location / DISCOM</th>
                    <th>Capacity</th>
                    <th>Stage Status</th>
                    <th>Gross / Subsidy</th>
                    <th>Assigned Advisor</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($leads)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-secondary">No leads found for the selected stage.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($leads as $l): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($l['lead_code'] ?? 'LEAD-' . $l['id']) ?></span></td>
                            <td>
                                <div class="fw-bold text-navy"><?= htmlspecialchars(($l['first_name'] ?? '') . ' ' . ($l['last_name'] ?? '')) ?></div>
                                <span class="text-secondary small"><?= htmlspecialchars($l['mobile'] ?? $l['phone_number'] ?? 'N/A') ?></span>
                            </td>
                            <td>
                                <div class="text-navy small fw-semibold"><?= htmlspecialchars($l['district'] ?? 'Khordha') ?></div>
                                <span class="badge bg-light text-dark border" style="font-size: 0.68rem;"><?= htmlspecialchars($l['discom_name'] ?? 'TPCODL') ?></span>
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary fw-bold"><?= $l['proposed_capacity_kw'] ?? $l['proposed_solar_kw'] ?? '3' ?> kW</span></td>
                            <td>
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                    <?= htmlspecialchars($l['stage'] ?? 'SUBMITTED') ?>
                                </span>
                            </td>
                            <td>
                                <div class="small text-secondary">Cost: ₹<?= number_format((float)($l['estimated_project_cost'] ?? 210000)) ?></div>
                                <div class="small text-success fw-bold">Dual Sub: ₹1,38,000</div>
                            </td>
                            <td>
                                <span class="small text-navy fw-semibold"><?= htmlspecialchars($l['advisor_name'] ?? 'Direct SVPL') ?></span>
                            </td>
                            <td>
                                <a href="<?= url('/admin/lead/' . $l['id']) ?>" class="btn btn-svpl-navy btn-sm">
                                    Manage <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
