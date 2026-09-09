<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Lead Pipeline View (10-Stage Pipeline Filtering)
 */
$title = "Lead Pipeline — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">10-Stage Solar Lead Pipeline</h3>
        <p class="text-muted small mb-0">Track applications across registration, bank loans, JE inspection, and DBT subsidies</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/admin/export/csv?type=leads') ?>" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
        </a>
    </div>
</div>

<!-- Stage Filter Pills -->
<div class="d-flex gap-2 overflow-auto pb-3 mb-3">
    <a href="<?= url('/admin/leads') ?>" class="btn btn-sm <?= empty($currentStage) ? 'btn-svpl-navy' : 'btn-outline-secondary' ?>">
        All Stages
    </a>
    <?php
    $allStages = [
        'REGISTRATION' => '1. Registration',
        'DOCUMENTS' => '2. Documents',
        'GOVT_PORTAL' => '3. Govt Portal',
        'LOAN_APPLIED' => '4. Loan Applied',
        'LOAN_SANCTIONED' => '5. Loan Sanctioned',
        'INSTALLATION_COMMENCED' => '6. Installing',
        'INSTALLATION_COMPLETED' => '7. Completed',
        'JE_REPORT' => '8. JE Report',
        'SUBSIDY_APPLIED' => '9. Subsidy Applied',
        'SUBSIDY_RECEIVED' => '10. Subsidy Received',
    ];
    foreach ($allStages as $k => $lbl): ?>
        <a href="<?= url('/admin/leads?stage=' . $k) ?>" class="btn btn-sm <?= ($currentStage ?? '') === $k ? 'btn-svpl-gold' : 'btn-outline-secondary' ?> text-nowrap">
            <?= $lbl ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light small">
                <tr>
                    <th>Lead Code</th>
                    <th>Customer Name</th>
                    <th>District</th>
                    <th>Capacity</th>
                    <th>Stage</th>
                    <th>Cost / Subsidy</th>
                    <th>Advisor</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leads as $l): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($l['lead_code']) ?></code></td>
                        <td>
                            <strong><?= htmlspecialchars($l['first_name'] . ' ' . $l['last_name']) ?></strong><br>
                            <span class="text-muted small"><?= htmlspecialchars($l['mobile']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($l['district']) ?></td>
                        <td><span class="badge bg-light text-dark border"><?= $l['proposed_capacity_kw'] ?> kW</span></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                <?= htmlspecialchars($l['stage']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="small text-muted">Cost: ₹<?= number_format((float)$l['estimated_project_cost']) ?></span><br>
                            <strong class="small text-success">Sub: ₹<?= number_format((float)$l['subsidy_amount']) ?></strong>
                        </td>
                        <td>
                            <?php if ($l['advisor_name']): ?>
                                <span class="small fw-semibold"><?= htmlspecialchars($l['advisor_name']) ?></span>
                            <?php else: ?>
                                <span class="text-muted small">Direct</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= url('/admin/leads/' . $l['id']) ?>" class="btn btn-svpl-navy btn-sm">
                                Manage Lifecycle <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
