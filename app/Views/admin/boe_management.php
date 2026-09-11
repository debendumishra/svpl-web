

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
        <h4 class="m-0 font-outfit fw-bold text-navy">Back Office Executive (BOE) Staff Management</h4>
        <p class="text-muted small mb-0">Register, manage and track Back Office Executives handling solar applications.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#createBoeModal">
            <i class="bi bi-person-plus-fill me-1"></i> Add New BOE Staff
        </button>
        <a href="<?= url('/admin/boe/reports') ?>" class="btn btn-outline-info fw-bold ms-2">
            <i class="bi bi-file-earmark-bar-graph me-1"></i> BOE Performance Report
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-outfit fw-bold text-navy"><i class="bi bi-people-fill me-2 text-primary"></i> Registered Back Office Executives</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead class="table-light">
                    <tr>
                        <th>Employee Code</th>
                        <th>Executive Name</th>
                        <th>Designation</th>
                        <th>Contact Details</th>
                        <th>Assigned Customers</th>
                        <th>Completed Installations</th>
                        <th>Account Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($boeList)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No BOE staff registered yet. Click "Add New BOE Staff" above.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($boeList as $b): ?>
                        <tr>
                            <td><strong class="text-navy"><?= htmlspecialchars($b['employee_code'] ?? 'SVPL-BOE-' . $b['id']) ?></strong></td>
                            <td>
                                <strong><?= htmlspecialchars($b['full_name']) ?></strong><br>
                                <small class="text-muted">Registered <?= date('d M Y', strtotime($b['created_at'])) ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($b['designation'] ?? 'Back Office Executive') ?></span></td>
                            <td>
                                <i class="bi bi-telephone text-success"></i> <?= htmlspecialchars($b['mobile']) ?><br>
                                <small class="text-muted"><?= htmlspecialchars($b['email'] ?? 'No email') ?></small>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark fw-bold px-2 py-1"><?= $b['assigned_customers_count'] ?> Customers</span>
                            </td>
                            <td>
                                <span class="badge bg-success text-white fw-bold px-2 py-1"><?= $b['completed_stage_count'] ?> Completed</span>
                            </td>
                            <td>
                                <?php if ($b['is_active']): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Deactivated</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= url('/admin/boe/toggle/' . $b['id']) ?>" class="btn btn-sm <?= $b['is_active'] ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                                    <?= $b['is_active'] ? 'Deactivate' : 'Activate' ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Create New BOE Staff -->
<div class="modal fade" id="createBoeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= url('/admin/boe/create') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="modal-header bg-navy text-white">
                    <h6 class="modal-title font-outfit fw-bold"><i class="bi bi-person-plus-fill me-2 text-warning"></i> Register New Back Office Executive (BOE)</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Employee Code</label>
                        <input type="text" name="employee_code" class="form-control" value="<?= htmlspecialchars($nextCode) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="full_name" class="form-control" placeholder="e.g. Ramesh Chandra Das" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Mobile Number</label>
                            <input type="text" name="mobile" class="form-control" placeholder="10-digit mobile" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="boe@suryavistaara.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Designation</label>
                        <input type="text" name="designation" class="form-control" value="Back Office Executive" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Initial Password</label>
                        <input type="text" name="password" class="form-control" value="Password@123" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Create BOE Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
