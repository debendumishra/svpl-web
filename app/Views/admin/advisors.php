<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Advisors Management View
 */
$title = "Advisor Network Management — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Solar Advisor Registry</h3>
        <p class="text-muted small mb-0">Total active registered clean energy promoters in Odisha</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/admin/export/csv?type=advisors') ?>" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
        </a>
    </div>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <form method="GET" action="<?= url('/admin/advisors') ?>" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Search by Name, Code, Mobile, District..." value="<?= htmlspecialchars($search ?? '') ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-svpl-navy btn-sm w-100"><i class="bi bi-search me-1"></i> Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light small">
                <tr>
                    <th>Advisor ID</th>
                    <th>Full Name</th>
                    <th>Mobile</th>
                    <th>Odisha District / Block</th>
                    <th>Sponsor</th>
                    <th>Status</th>
                    <th>Direct Cust.</th>
                    <th>Wallet Balance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($advisors as $adv): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($adv['advisor_code']) ?></code></td>
                        <td>
                            <strong><?= htmlspecialchars($adv['first_name'] . ' ' . $adv['last_name']) ?></strong>
                        </td>
                        <td><?= htmlspecialchars($adv['mobile']) ?></td>
                        <td>
                            <?= htmlspecialchars($adv['district']) ?><br>
                            <span class="text-muted small"><?= htmlspecialchars($adv['block']) ?></span>
                        </td>
                        <td>
                            <?php if ($adv['sponsor_code']): ?>
                                <code><?= htmlspecialchars($adv['sponsor_code']) ?></code>
                            <?php else: ?>
                                <span class="text-muted small">Direct SVPL</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= $adv['status'] === 'QUALIFIED' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                <?= htmlspecialchars($adv['status']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold <?= $adv['direct_customer_count'] >= 3 ? 'text-success' : 'text-primary' ?>">
                                <?= $adv['direct_customer_count'] ?> / 3
                            </span>
                        </td>
                        <td>₹<?= number_format((float)($adv['wallet_balance'] ?? 0), 2) ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?= url('/print/id-card/' . $adv['id']) ?>" target="_blank"><i class="bi bi-person-vcard me-2"></i> Print ID Card</a></li>
                                    <li><a class="dropdown-item" href="<?= url('/print/appointment/' . $adv['id']) ?>" target="_blank"><i class="bi bi-file-earmark-text me-2"></i> Appointment Letter</a></li>
                                    <li><a class="dropdown-item" href="<?= url('/admin/network-tree?root_id=' . $adv['id']) ?>"><i class="bi bi-diagram-3 me-2"></i> View Downline Tree</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
