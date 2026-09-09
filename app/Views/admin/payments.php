<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Wallet Balances & Transactions View
 */
$title = "Wallet Balances & Transactions — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Wallet Ledgers & Banking</h3>
        <p class="text-muted small mb-0">Live audit of credits, bonuses, and advisor wallet balances</p>
    </div>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle small">
            <thead class="table-light">
                <tr>
                    <th>Txn #</th>
                    <th>User Name</th>
                    <th>Type</th>
                    <th>Amount Credited</th>
                    <th>Balance After</th>
                    <th>Description</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td>#<?= $t['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($t['full_name']) ?></strong><br>
                            <span class="text-muted small"><?= htmlspecialchars($t['mobile']) ?> (<?= $t['role'] ?>)</span>
                        </td>
                        <td><span class="badge bg-success-subtle text-success border border-success-subtle"><?= htmlspecialchars($t['txn_type']) ?></span></td>
                        <td class="text-success fw-bold">+ ₹<?= number_format((float)$t['amount'], 2) ?></td>
                        <td class="fw-semibold">₹<?= number_format((float)$t['balance_after'], 2) ?></td>
                        <td><?= htmlspecialchars($t['description']) ?></td>
                        <td class="text-muted"><?= $t['created_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
