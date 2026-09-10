<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Advisor Portal Dashboard View (Solar Luminary Design System)
 */
$title = "Advisor Command Center — Surya Vistaara";
?>

<!-- ADVISOR HERO WELCOME STRIP -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">Welcome back, <?= htmlspecialchars($advisor['first_name']) ?>!</h3>
            <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.72rem;">Certified Solar Advisor</span>
        </div>
        <p class="text-secondary small mb-0">
            Advisor ID: <span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($advisor['advisor_code']) ?></span> | 
            Referral Code: <strong class="text-primary"><?= htmlspecialchars($advisor['referral_code']) ?></strong>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/advisor/qr-code') ?>" class="btn btn-svpl-solar btn-sm shadow-sm">
            <i class="bi bi-qr-code me-1"></i> Doorstep QR Code
        </a>
        <a href="<?= url('/print/id-card/' . $advisor['id']) ?>" target="_blank" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-person-vcard me-1"></i> Print ID Card
        </a>
    </div>
</div>

<!-- QUALIFICATION STATUS NOTIFICATION -->
<div class="animate-fade-in stagger-1 mb-4">
    <?php if ($advisor['status'] === 'QUALIFIED'): ?>
        <div class="card p-3 border-0 shadow-sm" style="background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); border-left: 5px solid #10B981 !important; border-radius: 12px;">
            <div class="d-flex align-items-center">
                <i class="bi bi-patch-check-fill text-success fs-1 me-3"></i>
                <div>
                    <h6 class="font-heading fw-bold mb-1 text-success">QUALIFIED ADVISOR STATUS ACTIVE</h6>
                    <span class="text-secondary small">You have completed your initial direct qualifications and unlocked the entire 9-level network commission distribution structure!</span>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card p-3 border-0 shadow-sm" style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%); border-left: 5px solid #F59E0B !important; border-radius: 12px;">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill text-warning fs-1 me-3"></i>
                <div class="w-100">
                    <h6 class="font-heading fw-bold mb-1 text-dark">NEW ADVISOR — 3-Customer Qualification Progress</h6>
                    <div class="text-secondary small mb-2">Complete <?= max(0, 3 - $advisor['direct_customer_count']) ?> more direct customer solar installations to unlock your full 9-level team overrides.</div>
                    <div class="progress bg-white" style="height: 8px; max-width: 320px; border-radius: 10px;">
                        <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= min(100, ($advisor['direct_customer_count'] / 3) * 100) ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- 4 ADVISOR PERFORMANCE METRIC CARDS -->
<div class="row g-3 mb-4 animate-fade-in stagger-2">
    <div class="col-md-3 col-6">
        <div class="stat-card-modern shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Wallet Balance</span>
                <div class="stat-icon-modern bg-success-subtle text-success"><i class="bi bi-wallet2"></i></div>
            </div>
            <h3 class="font-heading fw-bold mb-0 text-success">₹<?= number_format((float)($wallet['balance'] ?? 0), 2) ?></h3>
            <span class="small text-secondary" style="font-size: 0.75rem;"><i class="bi bi-arrow-down-circle text-success me-1"></i> Available to withdraw</span>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="stat-card-modern shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Direct Clients</span>
                <div class="stat-icon-modern bg-primary-subtle text-primary"><i class="bi bi-people"></i></div>
            </div>
            <h3 class="font-heading fw-bold mb-0 text-primary"><?= count($customers) ?></h3>
            <span class="small text-secondary" style="font-size: 0.75rem;"><?= $advisor['direct_customer_count'] ?> Completed</span>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="stat-card-modern shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Downline Team</span>
                <div class="stat-icon-modern bg-warning-subtle text-warning"><i class="bi bi-diagram-3"></i></div>
            </div>
            <h3 class="font-heading fw-bold mb-0 text-warning-emphasis"><?= $networkStats['total_downline'] ?></h3>
            <span class="small text-secondary" style="font-size: 0.75rem;">Across 9 Levels</span>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="stat-card-modern shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Total Earned</span>
                <div class="stat-icon-modern bg-info-subtle text-info"><i class="bi bi-cash-coin"></i></div>
            </div>
            <h3 class="font-heading fw-bold mb-0 text-navy">₹<?= number_format((float)($wallet['total_earned'] ?? 0), 2) ?></h3>
            <span class="small text-secondary" style="font-size: 0.75rem;">Lifetime Commissions</span>
        </div>
    </div>
</div>

<!-- LEADS & RECENT COMMISSIONS -->
<div class="row g-4 animate-fade-in stagger-3">
    <!-- Direct Leads Table -->
    <div class="col-lg-7">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-heading fw-bold mb-0 text-navy">My Direct Customer Leads</h5>
                <a href="<?= url('/advisor/customers') ?>" class="btn btn-outline-primary btn-sm">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <?php if (empty($leads)): ?>
                <div class="text-center py-5 text-secondary">
                    <i class="bi bi-sun fs-1 text-warning mb-2 d-block"></i>
                    <p class="mb-2">No customer leads captured yet.</p>
                    <a href="<?= url('/register-customer?ref=' . urlencode($advisor['referral_code'])) ?>" class="btn btn-svpl-solar btn-sm">
                        + Register First Customer
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr class="text-secondary text-uppercase">
                                <th>Lead Code</th>
                                <th>Customer</th>
                                <th>Capacity</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($leads, 0, 5) as $l): ?>
                                <tr>
                                    <td><span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($l['lead_code'] ?? 'LEAD-' . $l['id']) ?></span></td>
                                    <td>
                                        <div class="fw-bold text-navy"><?= htmlspecialchars($l['first_name'] . ' ' . $l['last_name']) ?></div>
                                        <small class="text-secondary"><?= htmlspecialchars($l['phone_number'] ?? 'N/A') ?></small>
                                    </td>
                                    <td><span class="badge bg-primary-subtle text-primary"><?= $l['proposed_capacity_kw'] ?? '3' ?> kW</span></td>
                                    <td><span class="badge bg-success-subtle text-success"><?= htmlspecialchars($l['stage'] ?? 'SUBMITTED') ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Commission Feed -->
    <div class="col-lg-5">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-heading fw-bold mb-0 text-navy">Recent Commission Credits</h5>
                <a href="<?= url('/advisor/wallet') ?>" class="small text-decoration-none fw-semibold">Wallet History <i class="bi bi-arrow-right"></i></a>
            </div>
            <?php if (empty($recentCommissions)): ?>
                <div class="text-center py-4 text-secondary">
                    <i class="bi bi-wallet-fill text-muted fs-2 mb-2 d-block"></i>
                    Commissions will credit here automatically upon customer installation completions.
                </div>
            <?php else: ?>
                <div class="d-flex flex-column gap-2">
                    <?php foreach (array_slice($recentCommissions, 0, 5) as $c): ?>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light border small">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-dark text-warning">Level <?= $c['level'] ?></span>
                                <div>
                                    <strong class="text-navy">Lead #<?= htmlspecialchars($c['lead_code'] ?? 'N/A') ?></strong>
                                    <div class="text-secondary" style="font-size: 0.72rem;"><?= date('d M Y', strtotime($c['created_at'] ?? 'now')) ?></div>
                                </div>
                            </div>
                            <span class="text-success fw-bold fs-6">+ ₹<?= number_format((float)$c['net_amount'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
