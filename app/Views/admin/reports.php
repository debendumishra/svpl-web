<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Reports & Analytics View
 */
$title = "Analytics & Reports — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Reports & Data Export Center</h3>
        <p class="text-muted small mb-0">Download verified Excel/CSV datasets for accounting, auditing, and field reviews</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm text-center">
            <i class="bi bi-funnel text-primary display-4 mb-3"></i>
            <h5 class="fw-bold" style="color: #0B2545;">Solar Leads Master Report</h5>
            <p class="text-muted small">Complete list of customer leads across all 10 stages with costs and DISCOM meters.</p>
            <a href="<?= url('/admin/export/csv?type=leads') ?>" class="btn btn-svpl-navy w-100">
                <i class="bi bi-download me-1"></i> Download Leads CSV
            </a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm text-center">
            <i class="bi bi-people text-warning display-4 mb-3"></i>
            <h5 class="fw-bold" style="color: #0B2545;">Advisor Network Registry</h5>
            <p class="text-muted small">All registered advisors, sponsors, qualification statuses, and districts.</p>
            <a href="<?= url('/admin/export/csv?type=advisors') ?>" class="btn btn-svpl-gold w-100">
                <i class="bi bi-download me-1"></i> Download Advisors CSV
            </a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm text-center">
            <i class="bi bi-cash-stack text-success display-4 mb-3"></i>
            <h5 class="fw-bold" style="color: #0B2545;">Commission & TDS Payouts</h5>
            <p class="text-muted small">9-Level commission ledger, statutory 5% TDS deductions, and net payouts.</p>
            <a href="<?= url('/admin/export/csv?type=commissions') ?>" class="btn btn-svpl-green w-100">
                <i class="bi bi-download me-1"></i> Download Commissions CSV
            </a>
        </div>
    </div>
</div>
