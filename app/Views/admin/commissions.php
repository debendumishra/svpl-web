<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Commissions & Hierarchy Payouts View (Solar Luminary Design System)
 */
$title = "Commissions & Payouts — SVPL Admin";
$totalDistributed = 0;
$totalTds = 0;
foreach ($commissions as $c) {
    $totalDistributed += (float)($c['net_amount'] ?? 0);
    $totalTds += (float)($c['tds_deducted'] ?? 0);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">Commission & 9-Level Overrides Ledger</h3>
            <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.72rem;">Multi-Level Payouts</span>
        </div>
        <p class="text-secondary small mb-0">Automated 9-tier commission distribution, direct customer bonus, and 5% statutory TDS ledger</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/admin/settings') ?>" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-gear-fill me-1"></i> Commission Settings
        </a>
        <a href="<?= url('/admin/export/csv?type=commissions') ?>" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
        </a>
    </div>
</div>

<!-- 3 SUMMARY STATS -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="p-3 bg-white rounded-3 shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small fw-bold text-uppercase">Total Payouts Distributed</span>
                <h4 class="fw-bold text-success mb-0 mt-1">₹<?= number_format($totalDistributed, 2) ?></h4>
            </div>
            <div class="p-3 bg-success-subtle text-success rounded-circle fs-4">
                <i class="bi bi-wallet2"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-white rounded-3 shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small fw-bold text-uppercase">Total TDS Retained (5%)</span>
                <h4 class="fw-bold text-primary mb-0 mt-1">₹<?= number_format($totalTds, 2) ?></h4>
            </div>
            <div class="p-3 bg-primary-subtle text-primary rounded-circle fs-4">
                <i class="bi bi-bank"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-white rounded-3 shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small fw-bold text-uppercase">Hierarchy Coverage</span>
                <h4 class="fw-bold text-navy mb-0 mt-1">9 Active Levels</h4>
            </div>
            <div class="p-3 bg-warning-subtle text-warning rounded-circle fs-4">
                <i class="bi bi-diagram-3"></i>
            </div>
        </div>
    </div>
</div>

<!-- 9-LEVEL COMMISSION PLAN REFERENCE ACCORDION / CARD -->
<div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="font-heading fw-bold text-navy mb-0">9-Level Compensation Plan Matrix</h5>
            <span class="text-secondary small">Triggered automatically when solar installations reach completion or subsidy receipt</span>
        </div>
        <a href="<?= url('/admin/network-tree') ?>" class="btn btn-sm btn-svpl-solar">
            <i class="bi bi-diagram-3-fill me-1"></i> Open Tree Visualizer
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-bordered text-center align-middle mb-0 small">
            <thead class="table-light">
                <tr class="text-secondary text-uppercase" style="font-size: 0.72rem;">
                    <th>Level</th>
                    <th>Designation</th>
                    <th>Commission (₹)</th>
                    <th>Direct Bonus (₹)</th>
                    <th>Gross Potential (₹)</th>
                    <th>Statutory TDS</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($slabs)): ?>
                    <?php foreach ($slabs as $s): ?>
                        <tr class="<?= $s['level'] == 1 ? 'table-warning-subtle fw-bold' : '' ?>">
                            <td><span class="badge bg-navy text-white">Level <?= $s['level'] ?></span></td>
                            <td class="text-start ps-3">
                                <?php if ($s['level'] == 1): ?>
                                    <strong class="text-navy">Direct Onboarding Advisor</strong>
                                <?php elseif ($s['level'] == 2): ?>
                                    <span>Level 2 Mentor Partner</span>
                                <?php elseif ($s['level'] == 3): ?>
                                    <span>Level 3 Area Supervisor</span>
                                <?php else: ?>
                                    <span class="text-muted">Level <?= $s['level'] ?> Network Uplink</span>
                                <?php endif; ?>
                            </td>
                            <td><strong class="text-success">₹<?= number_format((float)$s['commission_amount'], 2) ?></strong></td>
                            <td>
                                <?php if ($s['level'] == 1): ?>
                                    <span class="badge bg-success text-white">+ ₹500.00 / Install</span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong class="text-navy">₹<?= number_format((float)$s['commission_amount'] + ($s['level'] == 1 ? 500 : 0), 2) ?></strong>
                            </td>
                            <td><span class="text-secondary">5% Section 194H</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td><span class="badge bg-navy text-white">Level 1</span></td>
                        <td class="text-start ps-3"><strong>Direct Sponsoring Advisor</strong></td>
                        <td><strong class="text-success">₹1,000.00</strong></td>
                        <td><span class="badge bg-success text-white">+ ₹500.00 Bonus</span></td>
                        <td><strong class="text-navy">₹1,500.00</strong></td>
                        <td><span class="text-secondary">5% Section 194H</span></td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-secondary">Level 2</span></td>
                        <td class="text-start ps-3">Level 2 Mentor Partner</td>
                        <td><strong class="text-success">₹500.00</strong></td>
                        <td><span class="text-muted">—</span></td>
                        <td><strong class="text-navy">₹500.00</strong></td>
                        <td><span class="text-secondary">5% Section 194H</span></td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-secondary">Level 3</span></td>
                        <td class="text-start ps-3">Level 3 Area Supervisor</td>
                        <td><strong class="text-success">₹300.00</strong></td>
                        <td><span class="text-muted">—</span></td>
                        <td><strong class="text-navy">₹300.00</strong></td>
                        <td><span class="text-secondary">5% Section 194H</span></td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-secondary">Levels 4-9</span></td>
                        <td class="text-start ps-3">Territory Network Overrides</td>
                        <td><strong class="text-success">₹100 - ₹200</strong></td>
                        <td><span class="text-muted">—</span></td>
                        <td><strong class="text-navy">₹100 - ₹200</strong></td>
                        <td><span class="text-secondary">5% Section 194H</span></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- LIVE COMMISSION TRANSACTIONS LEDGER -->
<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <h5 class="font-heading fw-bold text-navy mb-3">Live Commission Payout Ledger</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle small mb-0">
            <thead class="table-light">
                <tr class="text-secondary text-uppercase">
                    <th>Txn ID</th>
                    <th>Advisor Name & Code</th>
                    <th>Lead Reference</th>
                    <th>Level</th>
                    <th>Gross Base</th>
                    <th>Direct Bonus</th>
                    <th>TDS (5%)</th>
                    <th>Net Credited</th>
                    <th>Status</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($commissions)): ?>
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-wallet2 fs-1 d-block mb-2 text-secondary"></i>
                            No commission distributions generated yet. Commissions are triggered automatically upon lead installation milestone.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($commissions as $comm): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border font-monospace">#<?= $comm['id'] ?></span></td>
                            <td>
                                <div class="fw-bold text-navy"><?= htmlspecialchars($comm['advisor_name']) ?></div>
                                <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.68rem;"><?= htmlspecialchars($comm['advisor_code']) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border font-monospace"><?= htmlspecialchars($comm['lead_code'] ?? 'N/A') ?></span>
                            </td>
                            <td><span class="badge bg-secondary">Level <?= $comm['level'] ?></span></td>
                            <td>₹<?= number_format((float)$comm['commission_amount'], 2) ?></td>
                            <td>
                                <?php if ((float)$comm['bonus_amount'] > 0): ?>
                                    <span class="text-success fw-semibold">+ ₹<?= number_format((float)$comm['bonus_amount'], 2) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-danger">- ₹<?= number_format((float)$comm['tds_deducted'], 2) ?></td>
                            <td><strong class="text-success fs-6">₹<?= number_format((float)$comm['net_amount'], 2) ?></strong></td>
                            <td><span class="badge bg-success text-white"><i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($comm['status']) ?></span></td>
                            <td class="text-muted"><?= date('d M Y, h:i A', strtotime($comm['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
