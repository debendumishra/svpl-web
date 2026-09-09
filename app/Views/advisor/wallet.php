<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Wallet & Earnings View
 */
$title = "My Wallet & Payouts — SVPL Advisor";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">My Earnings & Wallet</h3>
        <p class="text-muted small mb-0">Direct commissions, 9-level network overrides, and withdrawal history</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm text-center">
            <span class="text-muted small fw-semibold">Current Wallet Balance</span>
            <h2 class="fw-bold text-success my-2">₹<?= number_format((float)($wallet['balance'] ?? 0), 2) ?></h2>
            <button class="btn btn-svpl-green btn-sm mt-2" onclick="alert('Withdrawal request submitted! Payout will be processed to your registered bank account.');">
                <i class="bi bi-bank me-1"></i> Request Bank Payout
            </button>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm text-center">
            <span class="text-muted small fw-semibold">Lifetime Total Earned</span>
            <h2 class="fw-bold my-2" style="color: #0B2545;">₹<?= number_format((float)($wallet['total_earned'] ?? 0), 2) ?></h2>
            <span class="text-muted small">All Direct & Team Overrides</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm text-center">
            <span class="text-muted small fw-semibold">Statutory TDS Deducted</span>
            <h2 class="fw-bold text-danger my-2">₹<?= number_format((float)($wallet['total_earned'] * 0.05), 2) ?></h2>
            <span class="text-muted small">5% TDS deposited with IT Dept</span>
        </div>
    </div>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <h5 class="fw-bold mb-3" style="color: #0B2545;">Wallet Transaction History</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle small">
            <thead class="table-light">
                <tr>
                    <th>Txn #</th>
                    <th>Type</th>
                    <th>Amount Credited</th>
                    <th>Balance After</th>
                    <th>Description</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No wallet transactions recorded yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td>#<?= $t['id'] ?></td>
                            <td><span class="badge bg-success-subtle text-success border border-success-subtle"><?= htmlspecialchars($t['txn_type']) ?></span></td>
                            <td class="text-success fw-bold">+ ₹<?= number_format((float)$t['amount'], 2) ?></td>
                            <td>₹<?= number_format((float)$t['balance_after'], 2) ?></td>
                            <td><?= htmlspecialchars($t['description']) ?></td>
                            <td class="text-muted"><?= $t['created_at'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
