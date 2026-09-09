<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Solar Equipment & Kit Dispatches View
 */
$title = "Equipment Dispatches — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Solar Equipment & Kit Dispatches</h3>
        <p class="text-muted small mb-0">Fulfillment tracking for solar panels, inverters, and advisor induction kits</p>
    </div>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle small">
            <thead class="table-light">
                <tr>
                    <th>Tracking #</th>
                    <th>Lead Reference</th>
                    <th>Type</th>
                    <th>Courier Partner</th>
                    <th>Items Included</th>
                    <th>Dispatch Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dispatches)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No equipment dispatches recorded yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($dispatches as $d): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($d['tracking_number']) ?></code></td>
                            <td><code><?= htmlspecialchars($d['lead_code'] ?? 'N/A') ?></code></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($d['dispatch_type']) ?></span></td>
                            <td><?= htmlspecialchars($d['courier_partner']) ?></td>
                            <td><?= htmlspecialchars($d['items_included']) ?></td>
                            <td><?= htmlspecialchars($d['dispatch_date']) ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($d['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
