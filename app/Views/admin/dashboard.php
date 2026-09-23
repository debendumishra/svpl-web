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

<?php 
$totalAlerts = $alerts['totalAlertCount'] ?? 0;
?>

<!-- ========================================================================= -->
<!-- EXECUTIVE ACTION CENTER & REAL-TIME CRITICAL ALERTS -->
<!-- ========================================================================= -->
<div class="card card-svpl border-0 shadow-sm rounded-3 mb-4 overflow-hidden animate-fade-in" style="background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%); border-left: 4px solid #f59e0b !important;">
    <div class="card-header bg-white py-3 px-3 px-md-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <div style="background: #fef3c7; color: #b45309; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="bi bi-bell-fill"></i>
            </div>
            <div>
                <h5 class="font-heading fw-bold text-navy mb-0">Executive Action & Critical Alerts Hub</h5>
                <span class="text-secondary small">Real-time operational bottlenecks & pending approvals across Odisha ecosystem</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($totalAlerts > 0): ?>
                <span class="badge bg-danger px-3 py-2 fs-6 shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= $totalAlerts ?> Pending Actions
                </span>
            <?php else: ?>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-bold">
                    <i class="bi bi-check-circle-fill me-1"></i> All Operational Queues Clear
                </span>
            <?php endif; ?>
            <a href="<?= url('/admin/dashboard') ?>" class="btn btn-light border btn-sm" title="Refresh Dashboard Status">
                <i class="bi bi-arrow-clockwise"></i>
            </a>
        </div>
    </div>

    <div class="card-body p-3 p-md-4">
        <!-- PRIMARY 9-ALERT CARDS GRID -->
        <div class="row g-3">
            
            <!-- 1. Customer Registered but Not Yet Claimed by BOE -->
            <div class="col-xl-4 col-md-6">
                <div class="p-3 rounded-3 border bg-white h-100 shadow-sm d-flex flex-column justify-content-between position-relative hover-lift">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-2 <?= ($alerts['unclaimedRegistrations'] > 0) ? 'bg-danger-subtle text-danger' : 'bg-light text-secondary' ?> fs-5">
                                <i class="bi bi-person-x-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-navy mb-0" style="font-size: 0.92rem;">1. Unclaimed Registrations</h6>
                                <span class="text-muted small" style="font-size: 0.72rem;">Customer Registered (No BOE)</span>
                            </div>
                        </div>
                        <span class="badge <?= ($alerts['unclaimedRegistrations'] > 0) ? 'bg-danger' : 'bg-secondary' ?> rounded-pill px-2 py-1">
                            <?= $alerts['unclaimedRegistrations'] ?> Leads
                        </span>
                    </div>
                    <p class="text-secondary small mb-3" style="font-size: 0.76rem;">
                        New customer registrations waiting in the pool. Not yet claimed by any Back Office Executive.
                    </p>
                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="small <?= ($alerts['unclaimedRegistrations'] > 0) ? 'text-danger fw-bold' : 'text-muted' ?>" style="font-size: 0.75rem;">
                            <?= ($alerts['unclaimedRegistrations'] > 0) ? '⚠ Needs BOE Assignment' : '✓ Queue Cleared' ?>
                        </span>
                        <a href="<?= url('/admin/boe-management') ?>" class="btn btn-sm btn-outline-danger py-0 px-2 fw-semibold" style="font-size: 0.75rem;">
                            Claim Pool →
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Advisor Joined and Approval Pending -->
            <div class="col-xl-4 col-md-6">
                <div class="p-3 rounded-3 border bg-white h-100 shadow-sm d-flex flex-column justify-content-between position-relative hover-lift">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-2 <?= ($alerts['pendingAdvisorApprovals'] > 0) ? 'bg-warning-subtle text-warning-emphasis' : 'bg-light text-secondary' ?> fs-5">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-navy mb-0" style="font-size: 0.92rem;">2. Advisor Approvals Pending</h6>
                                <span class="text-muted small" style="font-size: 0.72rem;">₹2,700 Onboarding Fee Verification</span>
                            </div>
                        </div>
                        <span class="badge <?= ($alerts['pendingAdvisorApprovals'] > 0) ? 'bg-warning text-dark fw-bold' : 'bg-secondary' ?> rounded-pill px-2 py-1">
                            <?= $alerts['pendingAdvisorApprovals'] ?> Pending
                        </span>
                    </div>
                    <p class="text-secondary small mb-3" style="font-size: 0.76rem;">
                        Newly registered field advisors awaiting bank UTR payment confirmation & ID activation.
                    </p>
                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="small <?= ($alerts['pendingAdvisorApprovals'] > 0) ? 'text-warning-emphasis fw-bold' : 'text-muted' ?>" style="font-size: 0.75rem;">
                            <?= ($alerts['pendingAdvisorApprovals'] > 0) ? '⚠ Awaiting UTR Verification' : '✓ All Approved' ?>
                        </span>
                        <a href="<?= url('/admin/payments') ?>" class="btn btn-sm btn-outline-warning text-dark py-0 px-2 fw-bold" style="font-size: 0.75rem;">
                            Review & Activate →
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. 15 Point Crucial Stages Pending -->
            <div class="col-xl-4 col-md-6">
                <div class="p-3 rounded-3 border bg-white h-100 shadow-sm d-flex flex-column justify-content-between position-relative hover-lift">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-2 <?= ($alerts['totalCrucialStagesPending'] > 0) ? 'bg-primary-subtle text-primary' : 'bg-light text-secondary' ?> fs-5">
                                <i class="bi bi-kanban-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-navy mb-0" style="font-size: 0.92rem;">3. 15-Point Crucial Stages</h6>
                                <span class="text-muted small" style="font-size: 0.72rem;">Milestone Pipeline Bottlenecks</span>
                            </div>
                        </div>
                        <span class="badge <?= ($alerts['totalCrucialStagesPending'] > 0) ? 'bg-primary' : 'bg-secondary' ?> rounded-pill px-2 py-1">
                            <?= $alerts['totalCrucialStagesPending'] ?> Leads
                        </span>
                    </div>
                    <div class="d-flex flex-wrap gap-1 mb-2" style="font-size: 0.68rem;">
                        <span class="badge bg-light text-dark border">Docs: <strong><?= $alerts['crucialStagesBreakdown']['DOCUMENTS'] ?></strong></span>
                        <span class="badge bg-light text-dark border">Feasibility: <strong><?= $alerts['crucialStagesBreakdown']['GOVT_PORTAL'] ?></strong></span>
                        <span class="badge bg-light text-dark border">Loan: <strong><?= $alerts['crucialStagesBreakdown']['LOAN_APPLIED'] ?></strong></span>
                        <span class="badge bg-light text-dark border">Net Meter: <strong><?= $alerts['crucialStagesBreakdown']['NET_METER_APPLIED'] ?></strong></span>
                        <span class="badge bg-light text-dark border">Subsidy: <strong><?= $alerts['crucialStagesBreakdown']['SUBSIDY_APPLIED'] ?></strong></span>
                    </div>
                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="small text-primary fw-semibold" style="font-size: 0.75rem;">Stage 1–15 Workflow</span>
                        <a href="<?= url('/admin/leads') ?>" class="btn btn-sm btn-outline-primary py-0 px-2 fw-semibold" style="font-size: 0.75rem;">
                            View Pipeline →
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. Despatch Pending -->
            <div class="col-xl-4 col-md-6">
                <div class="p-3 rounded-3 border bg-white h-100 shadow-sm d-flex flex-column justify-content-between position-relative hover-lift">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-2 <?= ($alerts['pendingDespatches'] > 0) ? 'bg-warning-subtle text-dark' : 'bg-light text-secondary' ?> fs-5">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-navy mb-0" style="font-size: 0.92rem;">4. Despatch Pending</h6>
                                <span class="text-muted small" style="font-size: 0.72rem;">Stage 6: Solar Kit & Instruments</span>
                            </div>
                        </div>
                        <span class="badge <?= ($alerts['pendingDespatches'] > 0) ? 'bg-warning text-dark fw-bold' : 'bg-secondary' ?> rounded-pill px-2 py-1">
                            <?= $alerts['pendingDespatches'] ?> Ready
                        </span>
                    </div>
                    <p class="text-secondary small mb-3" style="font-size: 0.76rem;">
                        Loan Sanctioned customers waiting for BOS equipment BOM, E-Way Bill & delivery dispatch.
                    </p>
                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="small <?= ($alerts['pendingDespatches'] > 0) ? 'text-warning-emphasis fw-bold' : 'text-muted' ?>" style="font-size: 0.75rem;">
                            <?= ($alerts['pendingDespatches'] > 0) ? '⚡ Ready for Shipment' : '✓ All Despatched' ?>
                        </span>
                        <a href="<?= url('/admin/dispatches') ?>" class="btn btn-sm btn-outline-warning text-dark py-0 px-2 fw-bold" style="font-size: 0.75rem;">
                            Despatch Now →
                        </a>
                    </div>
                </div>
            </div>

            <!-- 5. Customer Receive Pending -->
            <div class="col-xl-4 col-md-6">
                <div class="p-3 rounded-3 border bg-white h-100 shadow-sm d-flex flex-column justify-content-between position-relative hover-lift">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-2 <?= ($alerts['customerReceivePending'] > 0) ? 'bg-info-subtle text-info-emphasis' : 'bg-light text-secondary' ?> fs-5">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-navy mb-0" style="font-size: 0.92rem;">5. Customer Receive Pending</h6>
                                <span class="text-muted small" style="font-size: 0.72rem;">Delivery Receipt Acknowledgment</span>
                            </div>
                        </div>
                        <span class="badge <?= ($alerts['customerReceivePending'] > 0) ? 'bg-info text-dark fw-bold' : 'bg-secondary' ?> rounded-pill px-2 py-1">
                            <?= $alerts['customerReceivePending'] ?> In Transit
                        </span>
                    </div>
                    <p class="text-secondary small mb-3" style="font-size: 0.76rem;">
                        Dispatched solar equipment shipments where customer OTP/signature confirmation is pending.
                    </p>
                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="small text-info-emphasis fw-semibold" style="font-size: 0.75rem;">
                            <?= ($alerts['customerReceivePending'] > 0) ? '🚚 Awaiting Client Ack' : '✓ All Acknowledged' ?>
                        </span>
                        <a href="<?= url('/admin/dispatches') ?>" class="btn btn-sm btn-outline-info py-0 px-2 fw-semibold" style="font-size: 0.75rem;">
                            Track Shipments →
                        </a>
                    </div>
                </div>
            </div>

            <!-- 6. Engineer Installation Pending -->
            <div class="col-xl-4 col-md-6">
                <div class="p-3 rounded-3 border bg-white h-100 shadow-sm d-flex flex-column justify-content-between position-relative hover-lift">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-2 <?= ($alerts['engineerInstallationPending'] > 0) ? 'bg-danger-subtle text-danger' : 'bg-light text-secondary' ?> fs-5">
                                <i class="bi bi-tools"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-navy mb-0" style="font-size: 0.92rem;">6. Engineer Install Pending</h6>
                                <span class="text-muted small" style="font-size: 0.72rem;">Stage 7–8: Field Solar Erection</span>
                            </div>
                        </div>
                        <span class="badge <?= ($alerts['engineerInstallationPending'] > 0) ? 'bg-danger' : 'bg-secondary' ?> rounded-pill px-2 py-1">
                            <?= $alerts['engineerInstallationPending'] ?> Pending
                        </span>
                    </div>
                    <p class="text-secondary small mb-3" style="font-size: 0.76rem;">
                        Delivered equipment awaiting rooftop structural assembly, inverter wiring & engineer sign-off.
                    </p>
                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="small <?= ($alerts['engineerInstallationPending'] > 0) ? 'text-danger fw-bold' : 'text-muted' ?>" style="font-size: 0.75rem;">
                            <?= ($alerts['engineerInstallationPending'] > 0) ? '⚡ Field Action Required' : '✓ All Synced' ?>
                        </span>
                        <a href="<?= url('/admin/engineers') ?>" class="btn btn-sm btn-outline-danger py-0 px-2 fw-semibold" style="font-size: 0.75rem;">
                            Assign Engineers →
                        </a>
                    </div>
                </div>
            </div>

            <!-- 7. Advisor Claim Bank Credit -->
            <div class="col-xl-4 col-md-6">
                <div class="p-3 rounded-3 border bg-white h-100 shadow-sm d-flex flex-column justify-content-between position-relative hover-lift">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-2 <?= ($alerts['pendingWithdrawalsCount'] > 0) ? 'bg-danger-subtle text-danger' : 'bg-light text-secondary' ?> fs-5">
                                <i class="bi bi-bank"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-navy mb-0" style="font-size: 0.92rem;">7. Advisor Claim Bank Credit</h6>
                                <span class="text-muted small" style="font-size: 0.72rem;">Advisor Wallet Payout Requests</span>
                            </div>
                        </div>
                        <span class="badge <?= ($alerts['pendingWithdrawalsCount'] > 0) ? 'bg-danger' : 'bg-secondary' ?> rounded-pill px-2 py-1">
                            <?= $alerts['pendingWithdrawalsCount'] ?> Requests
                        </span>
                    </div>
                    <p class="text-secondary small mb-3" style="font-size: 0.76rem;">
                        Advisors requested bank transfer with statutory 5% TDS. Total Pending: <strong>₹<?= number_format($alerts['pendingWithdrawalsAmount'], 2) ?></strong>
                    </p>
                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="small <?= ($alerts['pendingWithdrawalsCount'] > 0) ? 'text-danger fw-bold' : 'text-muted' ?>" style="font-size: 0.75rem;">
                            <?= ($alerts['pendingWithdrawalsCount'] > 0) ? '₹' . number_format($alerts['pendingWithdrawalsAmount']) . ' to disburse' : '✓ All Settled' ?>
                        </span>
                        <a href="<?= url('/admin/withdrawals?status=PENDING') ?>" class="btn btn-sm btn-outline-danger py-0 px-2 fw-semibold" style="font-size: 0.75rem;">
                            Settle Bank UTR →
                        </a>
                    </div>
                </div>
            </div>

            <!-- 8. Commission Credit Pending -->
            <div class="col-xl-4 col-md-6">
                <div class="p-3 rounded-3 border bg-white h-100 shadow-sm d-flex flex-column justify-content-between position-relative hover-lift">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-2 <?= ($alerts['pendingCommissionsCount'] > 0) ? 'bg-success-subtle text-success' : 'bg-light text-secondary' ?> fs-5">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-navy mb-0" style="font-size: 0.92rem;">8. Commission Credit Pending</h6>
                                <span class="text-muted small" style="font-size: 0.72rem;">9-Level Network Payout Approval</span>
                            </div>
                        </div>
                        <span class="badge <?= ($alerts['pendingCommissionsCount'] > 0) ? 'bg-success' : 'bg-secondary' ?> rounded-pill px-2 py-1">
                            <?= $alerts['pendingCommissionsCount'] ?> Credits
                        </span>
                    </div>
                    <p class="text-secondary small mb-3" style="font-size: 0.76rem;">
                        Generated MLM commission slabs awaiting executive authorization. Total: <strong>₹<?= number_format($alerts['pendingCommissionsAmount'], 2) ?></strong>
                    </p>
                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="small <?= ($alerts['pendingCommissionsCount'] > 0) ? 'text-success fw-bold' : 'text-muted' ?>" style="font-size: 0.75rem;">
                            <?= ($alerts['pendingCommissionsCount'] > 0) ? '₹' . number_format($alerts['pendingCommissionsAmount']) . ' Payout' : '✓ Up to Date' ?>
                        </span>
                        <a href="<?= url('/admin/commissions') ?>" class="btn btn-sm btn-outline-success py-0 px-2 fw-semibold" style="font-size: 0.75rem;">
                            Disburse Credits →
                        </a>
                    </div>
                </div>
            </div>

            <!-- 9. BOE Claimed but Not Processed Application -->
            <div class="col-xl-4 col-md-6">
                <div class="p-3 rounded-3 border bg-white h-100 shadow-sm d-flex flex-column justify-content-between position-relative hover-lift">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-2 <?= ($alerts['boeStalledApplications'] > 0) ? 'bg-danger-subtle text-danger' : 'bg-light text-secondary' ?> fs-5">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-navy mb-0" style="font-size: 0.92rem;">9. BOE Stalled Applications</h6>
                                <span class="text-muted small" style="font-size: 0.72rem;">Claimed but Idle for > 48 Hours</span>
                            </div>
                        </div>
                        <span class="badge <?= ($alerts['boeStalledApplications'] > 0) ? 'bg-danger' : 'bg-secondary' ?> rounded-pill px-2 py-1">
                            <?= $alerts['boeStalledApplications'] ?> Stalled
                        </span>
                    </div>
                    <p class="text-secondary small mb-3" style="font-size: 0.76rem;">
                        Customer applications claimed by a BOE with no document upload or stage advance in 48+ hours.
                    </p>
                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="small <?= ($alerts['boeStalledApplications'] > 0) ? 'text-danger fw-bold' : 'text-muted' ?>" style="font-size: 0.75rem;">
                            <?= ($alerts['boeStalledApplications'] > 0) ? '⚠ SLA Breach Warning' : '✓ SLA On Track' ?>
                        </span>
                        <a href="<?= url('/admin/boe-management') ?>" class="btn btn-sm btn-outline-danger py-0 px-2 fw-semibold" style="font-size: 0.75rem;">
                            Audit BOEs →
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- ADDITIONAL OPERATIONAL ACTION STRIP -->
        <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2 bg-light p-2 rounded-2">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <span class="small fw-bold text-navy"><i class="bi bi-sliders text-warning me-1"></i> Quick Action Counters:</span>
                <a href="<?= url('/admin/id-cards') ?>" class="text-decoration-none small d-inline-flex align-items-center gap-1">
                    <span class="badge bg-white text-dark border"><i class="bi bi-person-badge text-primary me-1"></i> ID Cards Pending: <strong><?= $alerts['pendingIdCards'] ?></strong></span>
                </a>
                <a href="<?= url('/admin/leads') ?>" class="text-decoration-none small d-inline-flex align-items-center gap-1">
                    <span class="badge bg-white text-dark border"><i class="bi bi-globe text-info me-1"></i> Direct Web Leads: <strong><?= $alerts['unassignedDirectLeads'] ?></strong></span>
                </a>
            </div>
            <div class="small text-muted">
                <i class="bi bi-clock-history me-1"></i> Live query auto-refreshed on page load
            </div>
        </div>

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
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr class="text-uppercase text-secondary">
                            <th>Lead Code</th>
                            <th>Customer & Contact</th>
                            <th>Location / DISCOM</th>
                            <th>Capacity</th>
                            <th>Pipeline Stage</th>
                            <th>Assigned Advisor</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentLeads)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No recent solar leads registered yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            $stageBadgeMap = [
                                'REGISTRATION'            => ['class' => 'bg-secondary text-white', 'text' => '1. Registration'],
                                'DOCUMENTS'               => ['class' => 'bg-info text-dark', 'text' => '2. Documents'],
                                'GOVT_PORTAL'             => ['class' => 'bg-primary text-white', 'text' => '3. Govt Portal'],
                                'LOAN_APPLIED'            => ['class' => 'bg-warning text-dark', 'text' => '4. Loan Applied'],
                                'LOAN_SANCTIONED'         => ['class' => 'bg-primary-subtle text-primary border', 'text' => '5. Loan Approved'],
                                'INSTRUMENT_DESPATCHED'   => ['class' => 'bg-warning-subtle text-dark border', 'text' => '6. Despatched'],
                                'INSTALLATION_COMMENCED'  => ['class' => 'bg-info-subtle text-info-emphasis border', 'text' => '7. Installing'],
                                'INSTALLATION_COMPLETED'  => ['class' => 'bg-primary text-white', 'text' => '8. Installed'],
                                'JE_REPORT'               => ['class' => 'bg-dark text-white', 'text' => '9. JE Inspected'],
                                'NET_METER'               => ['class' => 'bg-info text-white', 'text' => '10. Net Meter'],
                                'INTIMATION_TO_MMG'       => ['class' => 'bg-secondary-subtle text-dark border', 'text' => '11. MMG Intimated'],
                                'MMG_METER_REPORT'        => ['class' => 'bg-purple text-white', 'text' => '12. MMG Report'],
                                'BANK_SECOND_INSTALLMENT' => ['class' => 'bg-success-subtle text-success border', 'text' => '13. Bank 2nd Inst'],
                                'SUBSIDY_APPLIED'         => ['class' => 'bg-warning-subtle text-warning-emphasis border', 'text' => '14. Subsidy Applied'],
                                'SUBSIDY_RECEIVED'        => ['class' => 'bg-success text-white', 'text' => '🟢 15. Active Customer'],
                            ];
                            foreach ($recentLeads as $lead): 
                                $custName = trim(($lead['first_name'] ?? '') . ' ' . ($lead['last_name'] ?? ''));
                                if (empty($custName)) {
                                    $custName = $lead['customer_name'] ?? 'Customer #' . $lead['id'];
                                }
                                $custPhone = $lead['mobile'] ?? $lead['phone_number'] ?? 'N/A';
                                $leadCode = $lead['lead_code'] ?? 'LEAD-' . $lead['id'];
                                $capacity = $lead['proposed_capacity_kw'] ?? $lead['proposed_solar_kw'] ?? '3.0';
                                $stInfo = $stageBadgeMap[$lead['stage'] ?? ''] ?? ['class' => 'bg-light text-dark border', 'text' => $lead['stage'] ?? 'SUBMITTED'];
                            ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace fw-bold"><?= htmlspecialchars($leadCode) ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-navy"><?= htmlspecialchars($custName) ?></div>
                                        <div class="text-secondary" style="font-size: 0.78rem;">
                                            <i class="bi bi-telephone-fill text-success me-1"></i><?= htmlspecialchars($custPhone) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-navy fw-semibold"><?= htmlspecialchars($lead['district'] ?? 'Khordha') ?></div>
                                        <span class="badge bg-light text-dark border" style="font-size: 0.68rem;"><?= htmlspecialchars($lead['discom_name'] ?? 'TPCODL') ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary fw-bold"><?= $capacity ?> kW</span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $stInfo['class'] ?> px-2 py-1">
                                            <?= htmlspecialchars($stInfo['text']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-navy fw-semibold small"><?= htmlspecialchars($lead['advisor_name'] ?? 'Direct SVPL') ?></div>
                                        <?php if (!empty($lead['advisor_code'])): ?>
                                            <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.65rem;"><?= htmlspecialchars($lead['advisor_code']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= url('/admin/lead/' . $lead['id']) ?>" class="btn btn-sm btn-svpl-navy py-1 px-2">
                                            <i class="bi bi-eye-fill me-1"></i> Manage
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
