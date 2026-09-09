<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Customers Registry View
 */
$title = "Customer Registry — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Customer Solar Registry</h3>
        <p class="text-muted small mb-0">Rooftop solar applicants and installations in Odisha</p>
    </div>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <form method="GET" action="<?= url('/admin/customers') ?>" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Search Customer Code, Name, Mobile..." value="<?= htmlspecialchars($search ?? '') ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-svpl-navy btn-sm w-100"><i class="bi bi-search me-1"></i> Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light small">
                <tr>
                    <th>Customer Code</th>
                    <th>Full Name</th>
                    <th>Mobile</th>
                    <th>DISCOM / Consumer No.</th>
                    <th>District</th>
                    <th>Capacity</th>
                    <th>Assisting Advisor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $c): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($c['customer_code']) ?></code></td>
                        <td><strong><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></strong></td>
                        <td><?= htmlspecialchars($c['mobile']) ?></td>
                        <td>
                            <span class="badge bg-secondary"><?= htmlspecialchars($c['discom_name'] ?? 'TPCODL') ?></span><br>
                            <code><?= htmlspecialchars($c['consumer_number'] ?? 'N/A') ?></code>
                        </td>
                        <td><?= htmlspecialchars($c['district']) ?></td>
                        <td><?= $c['proposed_solar_kw'] ?> kW</td>
                        <td>
                            <?php if ($c['advisor_name']): ?>
                                <span class="small fw-semibold"><?= htmlspecialchars($c['advisor_name']) ?></span><br>
                                <code><?= htmlspecialchars($c['advisor_code']) ?></code>
                            <?php else: ?>
                                <span class="text-muted small">Direct</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary border"><?= htmlspecialchars($c['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
