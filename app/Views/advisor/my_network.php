<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor My 9-Level Network View
 */
$title = "My 9-Level Downline Network — SVPL";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">My 9-Level Downline Team</h3>
        <p class="text-muted small mb-0">Total Network Size: <strong><?= $stats['total_downline'] ?> Advisors</strong> across Odisha</p>
    </div>
</div>

<!-- 9-Level Level Breakdown Grid -->
<div class="row g-2 mb-4">
    <?php for ($lvl = 1; $lvl <= 9; $lvl++): ?>
        <div class="col">
            <div class="card p-2 text-center bg-white border shadow-sm">
                <span class="text-muted small" style="font-size: 0.75rem;">Level <?= $lvl ?></span>
                <h5 class="fw-bold mb-0 <?= $lvl === 1 ? 'text-primary' : 'text-dark' ?>"><?= $stats['levels'][$lvl] ?? 0 ?></h5>
            </div>
        </div>
    <?php endfor; ?>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <h5 class="fw-bold mb-3" style="color: #0B2545;">Downline Members List</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle small">
            <thead class="table-light">
                <tr>
                    <th>Level</th>
                    <th>Advisor Code</th>
                    <th>Full Name</th>
                    <th>Mobile</th>
                    <th>District</th>
                    <th>Status</th>
                    <th>Direct Cust.</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($downlines)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">You have no downline team members yet. Share your referral link to build your team!</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($downlines as $dl): ?>
                        <tr>
                            <td><span class="badge bg-secondary">Level <?= $dl['depth'] ?></span></td>
                            <td><code><?= htmlspecialchars($dl['advisor_code']) ?></code></td>
                            <td><strong><?= htmlspecialchars($dl['first_name'] . ' ' . $dl['last_name']) ?></strong></td>
                            <td><?= htmlspecialchars($dl['mobile']) ?></td>
                            <td><?= htmlspecialchars($dl['district']) ?></td>
                            <td>
                                <span class="badge <?= $dl['status'] === 'QUALIFIED' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                    <?= htmlspecialchars($dl['status']) ?>
                                </span>
                            </td>
                            <td><?= $dl['direct_customer_count'] ?> / 3</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
