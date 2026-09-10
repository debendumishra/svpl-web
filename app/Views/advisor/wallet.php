<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Advisor Wallet & Earnings View (Solar Luminary Design System)
 */
$title = "My Wallet & Payouts — SVPL Advisor";
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">My Commission Wallet & Instant Payouts</h3>
            <span class="badge bg-success text-white fw-bold px-2 py-1" style="font-size: 0.72rem;">Direct Bank NEFT</span>
        </div>
        <p class="text-secondary small mb-0">Direct commissions, 9-level team overrides, and withdrawal transaction logs</p>
    </div>
</div>

<!-- WALLET CARDS -->
<div class="row g-3 mb-4 animate-fade-in stagger-1">
    <div class="col-md-4">
        <div class="stat-card-modern shadow-sm text-center h-100">
            <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Available Wallet Balance</span>
            <h2 class="font-heading fw-bold text-success my-2">₹<?= number_format((float)($wallet['balance'] ?? 0), 2) ?></h2>
            <button class="btn btn-svpl-green btn-sm mt-1 px-3 shadow-sm" onclick="alert('Withdrawal request submitted! Payout will be transferred to your registered bank account.');">
                <i class="bi bi-bank me-1"></i> Request Bank Withdrawal
            </button>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-modern shadow-sm text-center h-100">
            <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Lifetime Commissions Earned</span>
            <h2 class="font-heading fw-bold text-navy my-2">₹<?= number_format((float)($wallet['total_earned'] ?? 0), 2) ?></h2>
            <span class="text-secondary small">Direct Leads + 9-Level Overrides</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-modern shadow-sm text-center h-100">
            <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Statutory TDS Deducted (5%)</span>
            <h2 class="font-heading fw-bold text-danger my-2">₹<?= number_format((float)(($wallet['total_earned'] ?? 0) * 0.05), 2) ?></h2>
            <span class="text-secondary small">Deposited with IT Department</span>
        </div>
    </div>
</div>

<!-- TRANSACTION HISTORY -->
<div class="card card-svpl p-4 bg-white border-0 shadow-sm animate-fade-in stagger-2">
    <h5 class="font-heading fw-bold mb-3 text-navy">Wallet Transaction History</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle small mb-0">
            <thead class="table-light">
                <tr class="text-secondary text-uppercase">
                    <th>Txn #</th>
                    <th>Type</th>
                    <th>Amount Credited</th>
                    <th>Balance After</th>
                    <th>Description</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-secondary">No wallet transactions recorded yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border font-monospace">#<?= $t['id'] ?></span></td>
                            <td><span class="badge bg-success-subtle text-success border border-success-subtle"><?= htmlspecialchars($t['txn_type']) ?></span></td>
                            <td class="text-success fw-bold fs-6">+ ₹<?= number_format((float)$t['amount'], 2) ?></td>
                            <td class="text-navy fw-semibold">₹<?= number_format((float)$t['balance_after'], 2) ?></td>
                            <td class="text-secondary"><?= htmlspecialchars($t['description']) ?></td>
                            <td class="text-secondary"><?= date('d M Y, h:i A', strtotime($t['created_at'] ?? 'now')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
