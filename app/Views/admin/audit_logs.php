<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Security Audit Logs View
 */
$title = "Audit Logs — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Security & Action Audit Trail</h3>
        <p class="text-muted small mb-0">Immutable logs of logins, stage transitions, and financial payouts</p>
    </div>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle small">
            <thead class="table-light">
                <tr>
                    <th>Log ID</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Entity</th>
                    <th>IP Address</th>
                    <th>Details</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $l): ?>
                    <tr>
                        <td>#<?= $l['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($l['full_name'] ?? 'System') ?></strong><br>
                            <span class="text-muted small"><?= htmlspecialchars($l['role'] ?? 'CLI') ?></span>
                        </td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($l['action']) ?></span></td>
                        <td><code><?= htmlspecialchars($l['entity_type'] ?? 'N/A') ?> #<?= $l['entity_id'] ?? '' ?></code></td>
                        <td><?= htmlspecialchars($l['ip_address']) ?></td>
                        <td><?= htmlspecialchars($l['details'] ?? '') ?></td>
                        <td class="text-muted"><?= $l['created_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
