<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Executive Admin Dashboard View
 */
$title = "Executive Dashboard — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Executive Control Center</h3>
        <p class="text-muted small mb-0">PM Surya Ghar Solar Network & Lead Overview for Odisha</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/admin/leads') ?>" class="btn btn-svpl-navy btn-sm">
            <i class="bi bi-funnel-fill me-1"></i> View Lead Pipeline
        </a>
        <a href="<?= url('/admin/network-tree') ?>" class="btn btn-svpl-gold btn-sm">
            <i class="bi bi-diagram-3-fill me-1"></i> Multi-Level Tree
        </a>
    </div>
</div>

<!-- 6 Core KPI Metrics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4 col-xl-2 col-6">
        <div class="card card-svpl p-3 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Total Advisors</span>
                <div class="stat-icon bg-warning-subtle text-warning" style="width:36px;height:36px;font-size:1.1rem;"><i class="bi bi-person-badge"></i></div>
            </div>
            <h4 class="fw-bold mb-0" style="color: #0B2545;"><?= number_format($totalAdvisors) ?></h4>
            <span class="text-success small" style="font-size: 0.75rem;"><i class="bi bi-arrow-up-right"></i> Active Network</span>
        </div>
    </div>

    <div class="col-md-4 col-xl-2 col-6">
        <div class="card card-svpl p-3 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Total Customers</span>
                <div class="stat-icon bg-primary-subtle text-primary" style="width:36px;height:36px;font-size:1.1rem;"><i class="bi bi-people"></i></div>
            </div>
            <h4 class="fw-bold mb-0 text-primary"><?= number_format($totalCustomers) ?></h4>
            <span class="text-muted small" style="font-size: 0.75rem;">Odisha Households</span>
        </div>
    </div>

    <div class="col-md-4 col-xl-2 col-6">
        <div class="card card-svpl p-3 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Pipeline Leads</span>
                <div class="stat-icon bg-info-subtle text-info" style="width:36px;height:36px;font-size:1.1rem;"><i class="bi bi-sun"></i></div>
            </div>
            <h4 class="fw-bold mb-0 text-info"><?= number_format($totalLeads) ?></h4>
            <span class="text-muted small" style="font-size: 0.75rem;">Active Proposals</span>
        </div>
    </div>

    <div class="col-md-4 col-xl-2 col-6">
        <div class="card card-svpl p-3 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Installations</span>
                <div class="stat-icon bg-success-subtle text-success" style="width:36px;height:36px;font-size:1.1rem;"><i class="bi bi-tools"></i></div>
            </div>
            <h4 class="fw-bold mb-0 text-success"><?= number_format($activeInstallations) ?></h4>
            <span class="text-success small" style="font-size: 0.75rem;">Grid Synchronized</span>
        </div>
    </div>

    <div class="col-md-4 col-xl-2 col-6">
        <div class="card card-svpl p-3 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Commissions</span>
                <div class="stat-icon bg-danger-subtle text-danger" style="width:36px;height:36px;font-size:1.1rem;"><i class="bi bi-cash-stack"></i></div>
            </div>
            <h5 class="fw-bold mb-0 text-danger">₹<?= number_format($totalCommissions, 2) ?></h5>
            <span class="text-muted small" style="font-size: 0.75rem;">9-Level Distributed</span>
        </div>
    </div>

    <div class="col-md-4 col-xl-2 col-6">
        <div class="card card-svpl p-3 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Subsidies Claimed</span>
                <div class="stat-icon bg-success-subtle text-success" style="width:36px;height:36px;font-size:1.1rem;"><i class="bi bi-bank"></i></div>
            </div>
            <h5 class="fw-bold mb-0 text-success">₹<?= number_format($totalSubsidies, 2) ?></h5>
            <span class="text-muted small" style="font-size: 0.75rem;">Direct DBT Value</span>
        </div>
    </div>
</div>

<!-- Pipeline Stage Breakdown & Recent Leads -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="color: #0B2545;">Recent Customer Solar Leads</h5>
                <a href="<?= url('/admin/leads') ?>" class="small text-decoration-none">View All Leads <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>Lead ID</th>
                            <th>Customer Name</th>
                            <th>District</th>
                            <th>Capacity</th>
                            <th>Current Stage</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentLeads as $lead): ?>
                            <tr>
                                <td><code><?= htmlspecialchars($lead['lead_code']) ?></code></td>
                                <td>
                                    <strong><?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?></strong><br>
                                    <span class="text-muted small"><?= htmlspecialchars($lead['mobile']) ?></span>
                                </td>
                                <td><?= htmlspecialchars($lead['district']) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= $lead['proposed_capacity_kw'] ?> kW</span></td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        <?= htmlspecialchars($lead['stage']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= url('/admin/leads/' . $lead['id']) ?>" class="btn btn-outline-primary btn-sm">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4">
            <h5 class="fw-bold mb-3" style="color: #0B2545;">Pipeline Stage Distribution</h5>
            <div class="d-flex flex-column gap-2">
                <?php foreach ($stageStats as $st): ?>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light border">
                        <span class="small fw-semibold"><?= htmlspecialchars($st['stage']) ?></span>
                        <span class="badge bg-dark rounded-pill"><?= $st['count'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
