<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor My Customers View
 */
$title = "My Customers — SVPL Advisor";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">My Direct Customers</h3>
        <p class="text-muted small mb-0">Rooftop solar installations assisted directly by you</p>
    </div>
    <a href="<?= url('/advisor/register-customer') ?>" class="btn btn-svpl-green btn-sm">
        <i class="bi bi-person-plus-fill me-1"></i> + Register New Customer
    </a>
</div>

<?php if (!empty($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
        <div>
            <strong>Customer Application Submitted!</strong> Customer Code <code><?= htmlspecialchars($_GET['code'] ?? '') ?></code> has been successfully registered and assigned to your Advisor account. Documents uploaded for verification.
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle small">
            <thead class="table-light">
                <tr>
                    <th>Customer Code</th>
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    <th>DISCOM / Meter</th>
                    <th>Capacity</th>
                    <th>Installation Stage</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No direct customers enrolled yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($c['customer_code']) ?></code></td>
                            <td><strong><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></strong></td>
                            <td><?= htmlspecialchars($c['mobile']) ?></td>
                            <td>
                                <span><?= htmlspecialchars($c['discom_name'] ?? 'TPCODL') ?></span><br>
                                <code><?= htmlspecialchars($c['consumer_number'] ?? 'N/A') ?></code>
                            </td>
                            <td><?= $c['proposed_solar_kw'] ?> kW</td>
                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($c['lead_stage'] ?? 'REGISTRATION') ?></span></td>
                            <td><span class="badge bg-primary-subtle text-primary border"><?= htmlspecialchars($c['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
