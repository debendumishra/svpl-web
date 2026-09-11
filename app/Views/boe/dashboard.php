<div class="row g-3 g-md-4 mb-4">
    <!-- Stat 1: Shared Stage 1 Pool -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-warning h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.75rem;">Stage 1 Pool</span>
                    <h2 class="mb-0 fw-bold text-dark mt-1"><?= $poolCount ?></h2>
                    <span class="text-warning small" style="font-size: 0.75rem;"><i class="bi bi-person-lines-fill"></i> Unassigned</span>
                </div>
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                    <i class="bi bi-inbox-fill fs-2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 2: My Assigned Customers -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-info h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.75rem;">Claimed Leads</span>
                    <h2 class="mb-0 fw-bold text-info mt-1"><?= $myCount ?></h2>
                    <span class="text-info small" style="font-size: 0.75rem;"><i class="bi bi-person-check-fill"></i> Assigned to Me</span>
                </div>
                <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info">
                    <i class="bi bi-people-fill fs-2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 3: Pending Documents -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-danger h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.75rem;">Pending Docs</span>
                    <h2 class="mb-0 fw-bold text-danger mt-1"><?= $pendingDocsCount ?></h2>
                    <span class="text-danger small" style="font-size: 0.75rem;"><i class="bi bi-exclamation-triangle-fill"></i> Action Needed</span>
                </div>
                <div class="rounded-circle bg-danger bg-opacity-10 p-3 text-danger">
                    <i class="bi bi-file-earmark-arrow-up-fill fs-2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 4: Completed/Advanced Stages -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-success h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.75rem;">Advanced / Installed</span>
                    <h2 class="mb-0 fw-bold text-success mt-1"><?= $completedCount ?></h2>
                    <span class="text-success small" style="font-size: 0.75rem;"><i class="bi bi-check-circle-fill"></i> Milestones</span>
                </div>
                <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                    <i class="bi bi-sun-fill fs-2"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pool Applications Table -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-outfit fw-bold text-navy"><i class="bi bi-inbox-fill me-2 text-warning"></i> Shared Stage 1 Applications Pool</h6>
                <a href="<?= url('/boe/customers?tab=pool') ?>" class="btn btn-outline-warning btn-sm">View All Pool</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentPool)): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-check-all fs-1 text-success"></i>
                        <p class="mb-0 mt-2">No unassigned Stage 1 applications in pool.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>Customer Code</th>
                                    <th>Customer Name</th>
                                    <th>District</th>
                                    <th>Advisor</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentPool as $c): ?>
                                <tr>
                                    <td><strong class="text-navy"><?= htmlspecialchars($c['customer_code']) ?></strong></td>
                                    <td><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($c['district']) ?></span></td>
                                    <td><small class="text-muted"><?= htmlspecialchars($c['advisor_name'] ?? 'Direct') ?></small></td>
                                    <td class="text-end">
                                        <a href="<?= url('/boe/customers/' . $c['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye-fill me-1"></i> View & Claim
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- My Claimed Customers Table -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-outfit fw-bold text-navy"><i class="bi bi-person-check-fill me-2 text-info"></i> My Handled Applications</h6>
                <a href="<?= url('/boe/customers?tab=my') ?>" class="btn btn-outline-info btn-sm">View All Assigned</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentCustomers)): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-folder2-open fs-1"></i>
                        <p class="mb-0 mt-2">You haven't claimed any customer applications yet.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>Customer</th>
                                    <th>Stage</th>
                                    <th>District</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentCustomers as $c): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($c['customer_code']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark"><?= htmlspecialchars($c['lead_stage'] ?? 'REGISTRATION') ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($c['district']) ?></td>
                                    <td class="text-end">
                                        <a href="<?= url('/boe/customers/' . $c['id']) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square me-1"></i> Process
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
