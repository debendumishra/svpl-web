<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Portal Dashboard View
 */
$title = "Advisor Dashboard — Surya Vistaara";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Welcome back, <?= htmlspecialchars($advisor['first_name']) ?>!</h3>
        <p class="text-muted small mb-0">Advisor ID: <code><?= htmlspecialchars($advisor['advisor_code']) ?></code> | Referral Code: <strong><?= htmlspecialchars($advisor['referral_code']) ?></strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/advisor/qr-code') ?>" class="btn btn-svpl-gold btn-sm">
            <i class="bi bi-qr-code me-1"></i> Customer QR Code
        </a>
        <a href="<?= url('/print/id-card/' . $advisor['id']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-person-vcard me-1"></i> Print ID Card
        </a>
    </div>
</div>

<!-- Qualification Status Banner -->
<?php if ($advisor['status'] === 'QUALIFIED'): ?>
    <div class="alert alert-success d-flex align-items-center mb-4 py-3 px-4 shadow-sm border-0" style="background: #E8F8F0; border-radius: 12px;">
        <i class="bi bi-patch-check-fill text-success fs-2 me-3"></i>
        <div>
            <h6 class="fw-bold mb-0 text-success">QUALIFIED ADVISOR STATUS ACTIVE</h6>
            <span class="small text-muted">You have successfully completed 3+ direct customers and unlocked all 9-level downline overrides!</span>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-warning d-flex align-items-center mb-4 py-3 px-4 shadow-sm border-0" style="background: #FFF8E6; border-radius: 12px;">
        <i class="bi bi-exclamation-circle-fill text-warning fs-2 me-3"></i>
        <div>
            <h6 class="fw-bold mb-0 text-dark">NEW ADVISOR — 3-Customer Qualification Progress</h6>
            <div class="small text-muted mb-1">Complete <?= max(0, 3 - $advisor['direct_customer_count']) ?> more direct customer installations to unlock your full 9-level team overrides.</div>
            <div class="progress" style="height: 6px; max-width: 300px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: <?= min(100, ($advisor['direct_customer_count'] / 3) * 100) ?>%"></div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- 4 Core Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card card-svpl p-3 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Wallet Balance</span>
                <i class="bi bi-wallet2 text-success fs-4"></i>
            </div>
            <h3 class="fw-bold mb-0 text-success">₹<?= number_format((float)($wallet['balance'] ?? 0), 2) ?></h3>
            <span class="small text-muted">Available to withdraw</span>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="card card-svpl p-3 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Direct Customers</span>
                <i class="bi bi-people text-primary fs-4"></i>
            </div>
            <h3 class="fw-bold mb-0 text-primary"><?= count($customers) ?></h3>
            <span class="small text-muted"><?= $advisor['direct_customer_count'] ?> Completed</span>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="card card-svpl p-3 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Downline Team</span>
                <i class="bi bi-diagram-3 text-warning fs-4"></i>
            </div>
            <h3 class="fw-bold mb-0 text-warning"><?= $networkStats['total_downline'] ?></h3>
            <span class="small text-muted">Across 9 Levels</span>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="card card-svpl p-3 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Total Earned</span>
                <i class="bi bi-cash-coin text-info fs-4"></i>
            </div>
            <h3 class="fw-bold mb-0" style="color: #0B2545;">₹<?= number_format((float)($wallet['total_earned'] ?? 0), 2) ?></h3>
            <span class="small text-muted">Lifetime Commissions</span>
        </div>
    </div>
</div>

<!-- Recent Leads & Commissions -->
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="color: #0B2545;">My Customer Leads</h5>
                <a href="<?= url('/advisor/leads') ?>" class="small text-decoration-none">View All Leads <i class="bi bi-arrow-right"></i></a>
            </div>
            <?php if (empty($leads)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-sun fs-2 text-warning mb-2 d-block"></i>
                    No leads generated yet. Share your referral link with households!
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Lead Code</th>
                                <th>Customer</th>
                                <th>Capacity</th>
                                <th>Stage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($leads, 0, 5) as $l): ?>
                                <tr>
                                    <td><code><?= htmlspecialchars($l['lead_code']) ?></code></td>
                                    <td><?= htmlspecialchars($l['first_name'] . ' ' . $l['last_name']) ?></td>
                                    <td><?= $l['proposed_capacity_kw'] ?> kW</td>
                                    <td><span class="badge bg-primary-subtle text-primary border"><?= htmlspecialchars($l['stage']) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm">
            <h5 class="fw-bold mb-3" style="color: #0B2545;">Recent Commission Earnings</h5>
            <?php if (empty($recentCommissions)): ?>
                <p class="text-muted small mb-0">Commissions will credit here automatically upon customer installation completions.</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-2">
                    <?php foreach (array_slice($recentCommissions, 0, 5) as $c): ?>
                        <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light border small">
                            <div>
                                <span class="badge bg-secondary me-1">L<?= $c['level'] ?></span>
                                <strong>Lead #<?= htmlspecialchars($c['lead_code'] ?? 'N/A') ?></strong>
                            </div>
                            <span class="text-success fw-bold">+ ₹<?= number_format((float)$c['net_amount'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
