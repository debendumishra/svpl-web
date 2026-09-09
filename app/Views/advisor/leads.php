<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Leads Pipeline View
 */
$title = "My Solar Leads — SVPL Advisor";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">My Solar Rooftop Leads</h3>
        <p class="text-muted small mb-0">Track all customer proposals and subsidy progress</p>
    </div>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle small">
            <thead class="table-light">
                <tr>
                    <th>Lead Code</th>
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    <th>Capacity</th>
                    <th>Estimated Cost</th>
                    <th>Govt Subsidy</th>
                    <th>Pipeline Stage</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($leads)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No solar leads registered under your referral yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($leads as $l): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($l['lead_code']) ?></code></td>
                            <td><strong><?= htmlspecialchars($l['first_name'] . ' ' . $l['last_name']) ?></strong></td>
                            <td><?= htmlspecialchars($l['mobile']) ?></td>
                            <td><span class="badge bg-light text-dark border"><?= $l['proposed_capacity_kw'] ?> kW</span></td>
                            <td>₹<?= number_format((float)$l['estimated_project_cost'], 2) ?></td>
                            <td class="text-success fw-bold">₹<?= number_format((float)$l['subsidy_amount'], 2) ?></td>
                            <td><span class="badge bg-primary-subtle text-primary border"><?= htmlspecialchars($l['stage']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
