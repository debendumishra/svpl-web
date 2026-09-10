<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Admin Advisors Management View (Solar Luminary Design System)
 */
$title = "Advisor Network Management — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">Solar Advisor Partner Registry</h3>
            <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.72rem;">9-Level Network</span>
        </div>
        <p class="text-secondary small mb-0">Total certified solar advisors promoting Dhwajja Solar & PM Surya Ghar across Odisha</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/register-advisor') ?>" class="btn btn-svpl-solar btn-sm shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> + Register Advisor
        </a>
        <a href="<?= url('/admin/export/csv?type=advisors') ?>" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
        </a>
    </div>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm animate-fade-in stagger-1">
    <form method="GET" action="<?= url('/admin/advisors') ?>" class="row g-2 mb-4">
        <div class="col-md-5">
            <input type="text" name="q" class="form-control" placeholder="Search by Name, Advisor ID, Mobile, District..." value="<?= htmlspecialchars($search ?? '') ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-svpl-navy w-100"><i class="bi bi-search me-1"></i> Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small">
                <tr class="text-secondary text-uppercase">
                    <th>Advisor ID</th>
                    <th>Full Name & Contact</th>
                    <th>Odisha District / Block</th>
                    <th>Sponsor Code</th>
                    <th>Status</th>
                    <th>Direct Installs</th>
                    <th>Wallet Balance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($advisors)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-secondary">No advisors found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($advisors as $adv): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($adv['advisor_code']) ?></span></td>
                            <td>
                                <div class="fw-bold text-navy"><?= htmlspecialchars($adv['first_name'] . ' ' . $adv['last_name']) ?></div>
                                <span class="text-secondary small"><?= htmlspecialchars($adv['mobile']) ?></span>
                            </td>
                            <td>
                                <div class="text-navy small fw-semibold"><?= htmlspecialchars($adv['district']) ?></div>
                                <span class="text-secondary small"><?= htmlspecialchars($adv['block'] ?? '') ?></span>
                            </td>
                            <td>
                                <?php if ($adv['sponsor_code']): ?>
                                    <span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($adv['sponsor_code']) ?></span>
                                <?php else: ?>
                                    <span class="text-secondary small">Direct SVPL</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $adv['status'] === 'QUALIFIED' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning-emphasis border border-warning-subtle' ?>">
                                    <?= htmlspecialchars($adv['status']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold <?= $adv['direct_customer_count'] >= 3 ? 'text-success' : 'text-primary' ?>">
                                    <?= $adv['direct_customer_count'] ?> / 3
                                </span>
                            </td>
                            <td><strong class="text-success">₹<?= number_format((float)($adv['wallet_balance'] ?? 0), 2) ?></strong></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Options
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li><a class="dropdown-item" href="<?= url('/print/id-card/' . $adv['id']) ?>" target="_blank"><i class="bi bi-person-vcard text-primary me-2"></i> Print Solar ID Card</a></li>
                                        <li><a class="dropdown-item" href="<?= url('/print/appointment/' . $adv['id']) ?>" target="_blank"><i class="bi bi-file-earmark-text text-warning me-2"></i> Appointment Letter</a></li>
                                        <li><a class="dropdown-item" href="<?= url('/admin/network-tree?root_id=' . $adv['id']) ?>"><i class="bi bi-diagram-3 text-success me-2"></i> View 9-Level Tree</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
