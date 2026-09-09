<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Commissions & Hierarchy Payouts View
 */
$title = "Commissions & Payouts — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Commission & Overrides Ledger</h3>
        <p class="text-muted small mb-0">9-Level compensation distributions and 5% TDS tax tracking</p>
    </div>
    <a href="<?= url('/admin/export/csv?type=commissions') ?>" class="btn btn-outline-success btn-sm">
        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
    </a>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle small">
            <thead class="table-light">
                <tr>
                    <th>Txn ID</th>
                    <th>Advisor Name</th>
                    <th>Lead Reference</th>
                    <th>Hierarchy Level</th>
                    <th>Gross Base</th>
                    <th>Direct Bonus</th>
                    <th>TDS (5%)</th>
                    <th>Net Credited</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commissions as $comm): ?>
                    <tr>
                        <td>#<?= $comm['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($comm['advisor_name']) ?></strong><br>
                            <code><?= htmlspecialchars($comm['advisor_code']) ?></code>
                        </td>
                        <td><code><?= htmlspecialchars($comm['lead_code'] ?? 'N/A') ?></code></td>
                        <td><span class="badge bg-secondary">Level <?= $comm['level'] ?></span></td>
                        <td>₹<?= number_format((float)$comm['commission_amount'], 2) ?></td>
                        <td>₹<?= number_format((float)$comm['bonus_amount'], 2) ?></td>
                        <td class="text-danger">- ₹<?= number_format((float)$comm['tds_deducted'], 2) ?></td>
                        <td class="text-success fw-bold">₹<?= number_format((float)$comm['net_amount'], 2) ?></td>
                        <td><span class="badge bg-success"><?= htmlspecialchars($comm['status']) ?></span></td>
                        <td class="text-muted"><?= $comm['created_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
