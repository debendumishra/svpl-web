

<?php if (!empty($successMsg)): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($successMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($errorMsg)): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($errorMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="m-0 font-outfit fw-bold text-navy">BOE-Wise Customer Summary & Performance Reports</h4>
        <p class="text-muted small mb-0">Track application progress, assigned customers, and stage distribution across all Back Office Executives.</p>
    </div>
    <div>
        <a href="<?= url('/admin/boe') ?>" class="btn btn-outline-primary fw-bold">
            <i class="bi bi-people me-1"></i> Manage BOE Staff
        </a>
    </div>
</div>

<?php foreach ($reportData as $row): ?>
    <?php $b = $row['boe']; ?>
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="m-0 font-outfit fw-bold text-navy">
                    <i class="bi bi-person-badge-fill text-info me-2"></i> <?= htmlspecialchars($b['full_name']) ?>
                    <span class="badge bg-light text-dark border ms-2"><?= htmlspecialchars($b['employee_code'] ?? 'SVPL-BOE') ?></span>
                </h6>
                <small class="text-muted"><i class="bi bi-telephone text-success"></i> <?= htmlspecialchars($b['mobile']) ?> | <?= htmlspecialchars($b['email'] ?? '') ?></small>
            </div>
            <div>
                <span class="badge bg-primary fs-6 px-3 py-2">Total Assigned: <?= $row['total_assigned'] ?> Applications</span>
            </div>
        </div>

        <div class="card-body">
            <!-- Stage Breakdown Badges -->
            <div class="row g-2 mb-4">
                <div class="col-md-2 col-6">
                    <div class="p-2 rounded bg-light border text-center">
                        <span class="text-muted small d-block">Registration</span>
                        <strong class="text-dark fs-5"><?= $row['stage_counts']['REGISTRATION'] ?></strong>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="p-2 rounded bg-light border text-center">
                        <span class="text-muted small d-block">Documents</span>
                        <strong class="text-info fs-5"><?= $row['stage_counts']['DOCUMENTS'] ?></strong>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="p-2 rounded bg-light border text-center">
                        <span class="text-muted small d-block">Govt Portal</span>
                        <strong class="text-warning fs-5"><?= $row['stage_counts']['GOVT_PORTAL'] ?></strong>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="p-2 rounded bg-light border text-center">
                        <span class="text-muted small d-block">Loan Processing</span>
                        <strong class="text-primary fs-5"><?= $row['stage_counts']['LOAN'] ?></strong>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="p-2 rounded bg-light border text-center">
                        <span class="text-muted small d-block">Installation</span>
                        <strong class="text-success fs-5"><?= $row['stage_counts']['INSTALLATION'] ?></strong>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="p-2 rounded bg-light border text-center">
                        <span class="text-muted small d-block">Subsidy</span>
                        <strong class="text-success fs-5"><?= $row['stage_counts']['SUBSIDY'] ?></strong>
                    </div>
                </div>
            </div>

            <!-- Customer Applications List -->
            <?php if (empty($row['customers'])): ?>
                <div class="text-center text-muted py-3">No customer applications assigned to this executive yet.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Customer Code</th>
                                <th>Customer Name</th>
                                <th>District</th>
                                <th>Advisor Name & Code</th>
                                <th>Current Stage</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($row['customers'] as $c): ?>
                            <tr>
                                <td><strong class="text-navy"><?= htmlspecialchars($c['customer_code']) ?></strong></td>
                                <td><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></td>
                                <td><?= htmlspecialchars($c['district']) ?></td>
                                <td><small class="text-muted"><?= htmlspecialchars($c['advisor_name'] ?? 'Direct') ?> (<?= htmlspecialchars($c['advisor_code'] ?? '-') ?>)</small></td>
                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($c['lead_stage'] ?? 'REGISTRATION') ?></span></td>
                                <td class="text-end">
                                    <a href="<?= url('/boe/customers/' . $c['id']) ?>" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-warning text-dark" data-bs-toggle="modal" data-bs-target="#swapBoeModal_<?= $c['id'] ?>">
                                        <i class="bi bi-arrow-left-right me-1"></i> Swap BOE
                                    </button>

                                    <!-- Swap BOE Case Reassignment Modal -->
                                    <div class="modal fade text-start" id="swapBoeModal_<?= $c['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="<?= url('/admin/boe/reassign') ?>" method="POST">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="customer_id" value="<?= $c['id'] ?>">

                                                    <div class="modal-header bg-navy text-white">
                                                        <h6 class="modal-title font-outfit fw-bold">
                                                            <i class="bi bi-arrow-left-right text-warning me-2"></i> Reassign Case: <?= htmlspecialchars($c['customer_code']) ?>
                                                        </h6>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="p-3 bg-light rounded-3 mb-3 border">
                                                            <div class="small text-muted">Customer Name:</div>
                                                            <strong class="font-outfit text-navy fs-6"><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></strong>
                                                            <div class="small text-muted mt-1">District: <?= htmlspecialchars($c['district']) ?> | Current Stage: <span class="badge bg-info text-dark"><?= htmlspecialchars($c['lead_stage'] ?? 'REGISTRATION') ?></span></div>
                                                            <div class="small text-muted mt-1">Currently Assigned BOE: <strong><?= htmlspecialchars($b['full_name']) ?> (<?= htmlspecialchars($b['employee_code'] ?? 'BOE') ?>)</strong></div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold">Select New Assigned Executive (BOE)</label>
                                                            <select name="new_boe_id" class="form-select" required>
                                                                <option value="">-- Reassign Back to Unassigned Stage 1 Pool --</option>
                                                                <?php foreach (($allBoeList ?? []) as $boeOpt): ?>
                                                                    <option value="<?= $boeOpt['id'] ?>" <?= ((int)$boeOpt['id'] === (int)$b['id']) ? 'selected' : '' ?>>
                                                                        <?= htmlspecialchars($boeOpt['full_name']) ?> (<?= htmlspecialchars($boeOpt['employee_code'] ?? 'BOE') ?>) — <?= htmlspecialchars($boeOpt['mobile']) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold">Reason / Transfer Remarks</label>
                                                            <textarea name="remarks" class="form-control" rows="2" placeholder="Describe the reason for swapping case between BOE staff..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-warning btn-sm fw-bold text-dark">
                                                            <i class="bi bi-check-circle-fill me-1"></i> Transfer & Update Case
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
