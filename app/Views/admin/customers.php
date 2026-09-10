<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Customers Registry View - Solar Luminary Design System
 */
$title = "Customer Solar Registry — SVPL Admin";
$customers = $customers ?? [];
$search = $search ?? '';

$stageMap = [
    'REGISTRATION'              => ['label' => '1. Lead Registered', 'badge' => 'bg-secondary text-white'],
    'DOCUMENTS'                 => ['label' => '2. Documents Verified', 'badge' => 'bg-info text-dark'],
    'GOVT_PORTAL'               => ['label' => '3. Govt Portal Submitted', 'badge' => 'bg-primary text-white'],
    'LOAN_APPLIED'              => ['label' => '4. Bank Loan Applied', 'badge' => 'bg-warning text-dark fw-bold'],
    'LOAN_SANCTIONED'           => ['label' => '5. Loan Sanctioned', 'badge' => 'bg-primary-subtle text-primary border border-primary-subtle fw-bold'],
    'INSTALLATION_COMMENCED'    => ['label' => '6. Installation Commenced', 'badge' => 'bg-dark text-warning fw-bold'],
    'INSTALLATION_COMPLETED'    => ['label' => '7. Installation Completed', 'badge' => 'bg-success text-white fw-bold'],
    'JE_REPORT'                 => ['label' => '8. DISCOM JE Inspection', 'badge' => 'bg-info-subtle text-dark border'],
    'SUBSIDY_APPLIED'           => ['label' => '9. Subsidy Applied', 'badge' => 'bg-primary text-white'],
    'SUBSIDY_RECEIVED'          => ['label' => '10. Subsidy Disbursed (Active)', 'badge' => 'bg-success text-white fw-bold'],
];
?>

<div class="container-fluid px-3 px-lg-4 py-3">
    <!-- PAGE HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-navy text-warning fw-bold px-2 py-1">
                    <i class="bi bi-people-fill me-1"></i> Citizens Registry
                </span>
                <span class="badge bg-light text-dark border fw-semibold">
                    <?= count($customers) ?> Enrolled Beneficiaries
                </span>
            </div>
            <h3 class="font-heading fw-bold mb-0 text-navy">Customer Solar Registry & Lifecycle</h3>
            <p class="text-secondary small mb-0">Rooftop solar applicants, 10-stage project statuses, and DISCOM consumer linkage in Odisha</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= url('/admin/leads') ?>" class="btn btn-outline-navy btn-sm fw-semibold">
                <i class="bi bi-kanban me-1"></i> 10-Stage Pipeline
            </a>
            <a href="<?= url('/register-customer') ?>" class="btn btn-svpl-solar btn-sm fw-bold shadow-sm">
                <i class="bi bi-plus-circle-fill me-1"></i> + Register Customer
            </a>
        </div>
    </div>

    <!-- SEARCH & FILTER BAR -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-body p-3">
            <form method="GET" action="<?= url('/admin/customers') ?>" class="row g-2 align-items-center">
                <div class="col-md-8 col-lg-9">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" class="form-control border-start-0" placeholder="Search by Customer Code (e.g. SVPL-CUST-2003), Full Name, Mobile, or District..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-navy fw-bold flex-fill">
                        <i class="bi bi-funnel-fill me-1"></i> Search
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="<?= url('/admin/customers') ?>" class="btn btn-light border" title="Reset Search">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- CUSTOMERS TABLE CARD -->
    <div class="card border-0 shadow-sm rounded-3 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase tracking-wider">
                    <tr>
                        <th>Customer Code</th>
                        <th>Beneficiary Name</th>
                        <th>Mobile / Contact</th>
                        <th>DISCOM & Meter No.</th>
                        <th>District / Block</th>
                        <th>Capacity</th>
                        <th>Assisting Advisor</th>
                        <th>Live Project Stage</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($customers)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                                No customer records found matching your search.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($customers as $c): 
                            $currentStage = !empty($c['lead_stage']) ? $c['lead_stage'] : (!empty($c['status']) ? $c['status'] : 'REGISTRATION');
                            $stageInfo = $stageMap[$currentStage] ?? [
                                'label' => ucwords(strtolower(str_replace('_', ' ', $currentStage))),
                                'badge' => 'bg-secondary text-white'
                            ];
                        ?>
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark font-monospace border fw-bold px-2 py-1">
                                        <?= htmlspecialchars($c['customer_code']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></div>
                                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($c['village'] ?? $c['gram_panchayat'] ?? 'Odisha') ?></small>
                                </td>
                                <td>
                                    <div class="font-monospace text-dark fw-semibold"><?= htmlspecialchars($c['mobile']) ?></div>
                                    <?php if (!empty($c['email'])): ?>
                                        <div class="text-muted small text-truncate" style="max-width: 140px; font-size: 0.75rem;"><?= htmlspecialchars($c['email']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary border fw-semibold">
                                        <?= htmlspecialchars($c['discom_name'] ?? 'TPCODL') ?>
                                    </span>
                                    <div class="font-monospace text-navy fw-semibold small mt-1">
                                        <?= htmlspecialchars($c['consumer_number'] ?? 'Not Linked') ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($c['district']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($c['block'] ?? '') ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-warning bg-opacity-10 text-dark border border-warning fw-bold">
                                        <?= $c['proposed_solar_kw'] ?? 3.0 ?> kW
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($c['advisor_name'])): ?>
                                        <div class="fw-semibold text-navy small"><?= htmlspecialchars($c['advisor_name']) ?></div>
                                        <span class="badge bg-light text-dark font-monospace border" style="font-size: 0.68rem;">
                                            <?= htmlspecialchars($c['advisor_code']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border">Direct HQ Lead</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?= $stageInfo['badge'] ?> px-2 py-1" style="font-size: 0.78rem;">
                                        <i class="bi bi-record-circle me-1"></i> <?= htmlspecialchars($stageInfo['label']) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <?php if (!empty($c['lead_id'])): ?>
                                        <a href="<?= url('/admin/leads/' . $c['lead_id']) ?>" class="btn btn-outline-navy btn-sm fw-semibold" title="View 10-Stage Pipeline Tracker">
                                            <i class="bi bi-arrow-up-right-circle me-1"></i> Track Lead
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= url('/admin/leads') ?>" class="btn btn-light border btn-sm text-secondary" title="View Pipeline">
                                            <i class="bi bi-kanban"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
