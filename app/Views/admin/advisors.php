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
                    <?php foreach ($advisors as $adv): 
                        $advPhoto = !empty($adv['photo_url']) ? resolve_photo_url($adv['photo_url']) : null;
                        $initials = strtoupper(substr($adv['first_name'] ?? 'A', 0, 1) . substr($adv['last_name'] ?? 'D', 0, 1));
                    ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border font-monospace fw-bold"><?= htmlspecialchars($adv['advisor_code']) ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative" style="width: 40px; height: 48px; border-radius: 6px; overflow: hidden; background: linear-gradient(135deg, #0B2545 0%, #1E3A8A 100%); border: 1.5px solid #0f2d59; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center;">
                                        <?php if (!empty($advPhoto)): ?>
                                            <img src="<?= htmlspecialchars($advPhoto) ?>" alt="Photo" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <span class="text-white fw-bold" style="font-size: 0.78rem; display: none; align-items: center; justify-content: center; width: 100%; height: 100%; letter-spacing: 1px;"><?= htmlspecialchars($initials) ?></span>
                                        <?php else: ?>
                                            <span class="text-white fw-bold" style="font-size: 0.78rem; display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; letter-spacing: 1px;"><?= htmlspecialchars($initials) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-navy" style="font-size: 0.95rem;"><?= htmlspecialchars($adv['first_name'] . ' ' . $adv['last_name']) ?></div>
                                        <span class="text-secondary small"><i class="bi bi-telephone text-success me-1"></i><?= htmlspecialchars($adv['mobile']) ?></span>
                                    </div>
                                </div>
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
                                <?php if ($adv['status'] === 'PENDING_APPROVAL'): ?>
                                    <span class="badge bg-warning text-dark border border-warning-subtle fw-bold">
                                        <i class="bi bi-clock-history"></i> Fee Pending
                                    </span>
                                <?php elseif ($adv['status'] === 'ACTIVE'): ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        Active
                                    </span>
                                <?php elseif ($adv['status'] === 'QUALIFIED'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        Qualified
                                    </span>
                                <?php elseif ($adv['status'] === 'PAYMENT_REJECTED'): ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                        Fee Rejected
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark border">
                                        <?= htmlspecialchars($adv['status']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-bold <?= $adv['direct_customer_count'] >= 3 ? 'text-success' : 'text-primary' ?>">
                                    <?= $adv['direct_customer_count'] ?> / 3
                                </span>
                            </td>
                            <td><strong class="text-success">₹<?= number_format((float)($adv['wallet_balance'] ?? 0), 2) ?></strong></td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <a href="<?= url('/admin/advisors/' . $adv['id'] . '/edit') ?>" class="btn btn-outline-primary btn-sm py-1 px-2 fw-semibold" title="Edit / Modify Advisor Data">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <?php if ($adv['status'] === 'PENDING_APPROVAL'): ?>
                                        <a href="<?= url('/admin/payments') ?>" class="btn btn-sm btn-warning text-dark fw-bold py-1 px-2 shadow-sm" title="Verify Onboarding Fee (₹<?= number_format((float)($adv['joining_fee'] ?? advisor_joining_fee())) ?>)">
                                            <i class="bi bi-shield-check"></i>
                                        </a>
                                    <?php else: ?>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm border dropdown-toggle py-1 px-2" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <li><a class="dropdown-item" href="<?= url('/admin/advisors/' . $adv['id'] . '/edit') ?>"><i class="bi bi-pencil-square text-primary me-2"></i> Modify Advisor Data</a></li>
                                                <li><a class="dropdown-item" href="<?= url('/print/id-card/' . $adv['id']) ?>" target="_blank"><i class="bi bi-person-vcard text-primary me-2"></i> Print Solar ID Card</a></li>
                                                <li><a class="dropdown-item" href="<?= url('/print/appointment/' . $adv['id']) ?>" target="_blank"><i class="bi bi-file-earmark-text text-warning me-2"></i> Appointment Letter</a></li>
                                                <li><a class="dropdown-item" href="<?= url('/admin/network-tree?root_id=' . $adv['id']) ?>"><i class="bi bi-diagram-3 text-success me-2"></i> View 9-Level Tree</a></li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
