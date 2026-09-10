<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Executive Admin Dashboard View (High-Contrast & Animated)
 */
$title = "Executive Command Center — SVPL Admin";
?>

<!-- TOP COMMAND HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">Executive Command Center</h3>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.75rem;">
                <i class="bi bi-circle-fill text-success me-1" style="font-size: 0.5rem;"></i> Odisha Live Sync
            </span>
        </div>
        <p class="text-secondary small mb-0">PM Surya Ghar Muft Bijli Yojana — Solar Network & Lead Analytics</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/admin/leads') ?>" class="btn btn-svpl-navy btn-sm shadow-sm">
            <i class="bi bi-kanban-fill me-1 text-warning"></i> Lead Pipeline
        </a>
        <a href="<?= url('/admin/network-tree') ?>" class="btn btn-svpl-solar btn-sm shadow-sm">
            <i class="bi bi-diagram-3-fill me-1"></i> 9-Level Tree Visualizer
        </a>
    </div>
</div>

<!-- 6 EXECUTIVE KPI METRICS GRID -->
<div class="row g-3 mb-4 animate-fade-in stagger-1">
    <div class="col-md-4 col-xl-2 col-6">
        <div class="stat-card-modern shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Field Advisors</span>
                <div class="stat-icon-modern bg-warning-subtle text-warning"><i class="bi bi-person-badge"></i></div>
            </div>
            <h4 class="font-heading fw-bold mb-0 text-navy"><?= number_format($totalAdvisors) ?></h4>
            <span class="text-success small fw-semibold" style="font-size: 0.75rem;"><i class="bi bi-arrow-up-right"></i> Active Network</span>
        </div>
    </div>

    <div class="col-md-4 col-xl-2 col-6">
        <div class="stat-card-modern shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Solar Clients</span>
                <div class="stat-icon-modern bg-primary-subtle text-primary"><i class="bi bi-people"></i></div>
            </div>
            <h4 class="font-heading fw-bold mb-0 text-primary"><?= number_format($totalCustomers) ?></h4>
            <span class="text-secondary small" style="font-size: 0.75rem;">Odisha Homes</span>
        </div>
    </div>

    <div class="col-md-4 col-xl-2 col-6">
        <div class="stat-card-modern shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Active Leads</span>
                <div class="stat-icon-modern bg-info-subtle text-info"><i class="bi bi-sun"></i></div>
            </div>
            <h4 class="font-heading fw-bold mb-0 text-info"><?= number_format($totalLeads) ?></h4>
            <span class="text-secondary small" style="font-size: 0.75rem;">10 Stages Live</span>
        </div>
    </div>

    <div class="col-md-4 col-xl-2 col-6">
        <div class="stat-card-modern shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Installed</span>
                <div class="stat-icon-modern bg-success-subtle text-success"><i class="bi bi-tools"></i></div>
            </div>
            <h4 class="font-heading fw-bold mb-0 text-success"><?= number_format($activeInstallations) ?></h4>
            <span class="text-success small fw-semibold" style="font-size: 0.75rem;"><i class="bi bi-lightning-charge-fill"></i> Grid Synced</span>
        </div>
    </div>

    <div class="col-md-4 col-xl-2 col-6">
        <div class="stat-card-modern shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Commissions</span>
                <div class="stat-icon-modern bg-danger-subtle text-danger"><i class="bi bi-cash-stack"></i></div>
            </div>
            <h5 class="font-heading fw-bold mb-0 text-danger">₹<?= number_format($totalCommissions, 2) ?></h5>
            <span class="text-secondary small" style="font-size: 0.75rem;">9-Level Disbursed</span>
        </div>
    </div>

    <div class="col-md-4 col-xl-2 col-6">
        <div class="stat-card-modern shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Dual Subsidy</span>
                <div class="stat-icon-modern bg-success-subtle text-success"><i class="bi bi-bank"></i></div>
            </div>
            <h5 class="font-heading fw-bold mb-0 text-success">₹<?= number_format($totalSubsidies, 2) ?></h5>
            <span class="text-success small fw-semibold" style="font-size: 0.75rem;">₹1.38L Max / Home</span>
        </div>
    </div>
</div>

<!-- PIPELINE & RECENT LEADS -->
<div class="row g-4 mb-4 animate-fade-in stagger-2">
    <!-- Left Column: Recent Leads Table -->
    <div class="col-lg-8">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="font-heading fw-bold mb-0 text-navy">Recent Customer Solar Leads</h5>
                    <span class="text-secondary small">Live tracking across Odisha DISCOM zones</span>
                </div>
                <a href="<?= url('/admin/leads') ?>" class="btn btn-outline-primary btn-sm">
                    View All Leads <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-uppercase text-secondary">
                            <th>Lead ID</th>
                            <th>Customer & Location</th>
                            <th>Capacity</th>
                            <th>Stage</th>
                            <th>Advisor</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentLeads)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No recent solar leads registered yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentLeads as $lead): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($lead['lead_number']) ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-navy"><?= htmlspecialchars($lead['customer_name']) ?></div>
                                        <div class="text-secondary small"><?= htmlspecialchars($lead['phone_number'] ?? 'N/A') ?> | <span class="badge bg-light text-dark border"><?= htmlspecialchars($lead['discom_name'] ?? 'TPCODL') ?></span></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary fw-bold"><?= $lead['proposed_solar_kw'] ?> kW</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                            <?= htmlspecialchars($lead['stage']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-secondary"><?= htmlspecialchars($lead['advisor_name'] ?? 'Direct SVPL') ?></small>
                                    </td>
                                    <td>
                                        <a href="<?= url('/admin/lead/' . $lead['id']) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Quick Actions & Odisha DISCOM Stats -->
    <div class="col-lg-4">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4">
            <h5 class="font-heading fw-bold mb-3 text-navy">Odisha Subsidy Summary</h5>
            <div class="p-3 bg-light rounded-3 border mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small"><i class="bi bi-check-circle-fill text-success me-1"></i> Central DBT Subsidy:</span>
                    <strong class="text-success">₹78,000 max</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small"><i class="bi bi-patch-check-fill text-warning me-1"></i> Odisha State Subsidy:</span>
                    <strong class="text-warning-emphasis">₹60,000 max</strong>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between align-items-center text-navy fw-bold">
                    <span>Total Subsidy Benefit:</span>
                    <span class="text-success fs-5">₹1,38,000</span>
                </div>
            </div>
            <div class="d-grid gap-2">
                <a href="<?= url('/register-customer') ?>" class="btn btn-svpl-solar">
                    <i class="bi bi-plus-circle-fill me-1"></i> Create Customer Lead
                </a>
                <a href="<?= url('/register-advisor') ?>" class="btn btn-outline-dark">
                    <i class="bi bi-person-plus-fill me-1"></i> Onboard New Advisor
                </a>
            </div>
        </div>

        <div class="card card-svpl p-4 bg-white border-0 shadow-sm">
            <h6 class="font-heading fw-bold mb-3 text-navy">DISCOM Coverage</h6>
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <span class="small text-secondary">TPCODL (Central Odisha)</span>
                <span class="badge bg-success-subtle text-success">Active</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <span class="small text-secondary">TPNODL (Northern Odisha)</span>
                <span class="badge bg-success-subtle text-success">Active</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <span class="small text-secondary">TPSODL (Southern Odisha)</span>
                <span class="badge bg-success-subtle text-success">Active</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="small text-secondary">TPWODL (Western Odisha)</span>
                <span class="badge bg-success-subtle text-success">Active</span>
            </div>
        </div>
    </div>
</div>
