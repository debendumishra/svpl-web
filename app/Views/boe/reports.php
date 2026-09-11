<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-outfit fw-bold text-navy">
            <i class="bi bi-file-earmark-bar-graph-fill me-2 text-primary"></i> 
            <?= (($user['role'] ?? '') === 'BOE') ? 'My Performance & Customer Applications Report' : 'BOE-Wise Customer Applications Performance Report' ?>
        </h6>
    </div>
    <div class="card-body">
        <?php foreach ($reportData as $row): ?>
            <?php $b = $row['boe']; ?>
            <div class="border rounded-3 p-3 mb-4 bg-white shadow-sm">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 pb-2 border-bottom">
                    <div>
                        <h5 class="mb-0 font-outfit fw-bold text-navy"><?= htmlspecialchars($b['full_name'] ?? $b['name'] ?? 'Executive Staff') ?></h5>
                        <span class="badge bg-info text-dark me-2"><?= htmlspecialchars($b['employee_code'] ?? 'SVPL-BOE') ?></span>
                        <span class="text-muted small"><i class="bi bi-telephone"></i> <?= htmlspecialchars($b['mobile'] ?? '') ?></span>
                    </div>
                    <div>
                        <span class="badge bg-primary fs-6 px-3 py-2">Total Handled: <?= $row['total_assigned'] ?> Applications</span>
                    </div>
                </div>

                <!-- Stage Breakdowns -->
                <div class="row g-2 mb-3">
                    <div class="col-4 col-md-2">
                        <div class="p-2 rounded bg-light border text-center">
                            <span class="text-muted small d-block" style="font-size: 0.7rem;">Registration</span>
                            <strong class="text-dark"><?= $row['stage_counts']['REGISTRATION'] ?></strong>
                        </div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="p-2 rounded bg-light border text-center">
                            <span class="text-muted small d-block" style="font-size: 0.7rem;">Documents</span>
                            <strong class="text-info"><?= $row['stage_counts']['DOCUMENTS'] ?></strong>
                        </div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="p-2 rounded bg-light border text-center">
                            <span class="text-muted small d-block" style="font-size: 0.7rem;">Govt Portal</span>
                            <strong class="text-warning"><?= $row['stage_counts']['GOVT_PORTAL'] ?></strong>
                        </div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="p-2 rounded bg-light border text-center">
                            <span class="text-muted small d-block" style="font-size: 0.7rem;">Loan Sanction</span>
                            <strong class="text-primary"><?= $row['stage_counts']['LOAN'] ?></strong>
                        </div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="p-2 rounded bg-light border text-center">
                            <span class="text-muted small d-block" style="font-size: 0.7rem;">Installation</span>
                            <strong class="text-success"><?= $row['stage_counts']['INSTALLATION'] ?></strong>
                        </div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="p-2 rounded bg-light border text-center">
                            <span class="text-muted small d-block" style="font-size: 0.7rem;">Subsidy</span>
                            <strong class="text-success"><?= $row['stage_counts']['SUBSIDY'] ?></strong>
                        </div>
                    </div>
                </div>

                <!-- Detailed Applications List -->
                <?php if (!empty($row['customers'])): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Customer Name</th>
                                    <th>District</th>
                                    <th>Current Stage</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($row['customers'], 0, 5) as $c): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($c['customer_code']) ?></strong></td>
                                    <td><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></td>
                                    <td><?= htmlspecialchars($c['district']) ?></td>
                                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($c['lead_stage'] ?? 'REGISTRATION') ?></span></td>
                                    <td class="text-end">
                                        <a href="<?= url('/boe/customers/' . $c['id']) ?>" class="btn btn-xs btn-outline-primary py-0">View</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
