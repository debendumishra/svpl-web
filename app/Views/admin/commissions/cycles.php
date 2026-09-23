<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Monthly Commission Cycles & Settlement Locking
 */
$title = $pageTitle ?? 'Commission Cycles & Locking';
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-calendar-check text-primary me-2"></i>Commission Cycles & Month Locking
            </h1>
            <p class="text-muted small mb-0">Monthly Settlement Period Summaries, Audit Reconciliation & Final Locking</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url('/admin/commissions') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to Desk
            </a>
        </div>
    </div>

    <!-- Cycle Selector -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="<?= url('/admin/commissions/cycles') ?>" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <label class="small text-muted mb-1">Select Month</label>
                    <select name="month" class="form-select form-select-sm">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $month == $m ? 'selected' : '' ?>>
                                <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small text-muted mb-1">Select Year</label>
                    <select name="year" class="form-select form-select-sm">
                        <?php for ($y = 2024; $y <= 2030; $y++): ?>
                            <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2 pt-3">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-filter me-1"></i>Load Cycle</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Active Cycle Summary Banner -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 border-start border-4 <?= $summary['is_locked'] ? 'border-danger bg-danger-subtle' : 'border-success' ?>">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <span class="badge <?= $summary['is_locked'] ? 'bg-danger' : 'bg-success' ?> mb-2">
                        <?= $summary['is_locked'] ? 'MONTH LOCKED (IMMUTABLE)' : 'MONTH OPEN (ACTIVE CALCULATIONS)' ?>
                    </span>
                    <h3 class="fw-bold text-dark mb-1">
                        Settlement Cycle: <?= date('F', mktime(0,0,0, $month, 10)) ?> <?= $year ?>
                    </h3>
                    <p class="text-muted small mb-0">
                        Total Transactions: <strong><?= $summary['total_transactions'] ?></strong> | 
                        Verified Payments Received: <strong><?= $summary['total_payments'] ?></strong> (₹<?= number_format($summary['total_business'], 2) ?>)
                    </p>
                </div>
                <div class="text-end mt-3 mt-md-0">
                    <?php if (!$summary['is_locked']): ?>
                        <form method="POST" action="<?= url('/admin/commissions/cycles/lock') ?>" onsubmit="return confirm('Lock this month cycle? Ordinary users will not be able to modify its commissions.')">
                            <input type="hidden" name="month" value="<?= $month ?>">
                            <input type="hidden" name="year" value="<?= $year ?>">
                            <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                <i class="bi bi-lock-fill me-1"></i>Lock Month Cycle
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="<?= url('/admin/commissions/cycles/unlock') ?>" onsubmit="return confirm('Super Admin: Unlock this cycle? Reason will be recorded in the audit log.')">
                            <input type="hidden" name="month" value="<?= $month ?>">
                            <input type="hidden" name="year" value="<?= $year ?>">
                            <input type="hidden" name="reason" value="Super Admin manual unlocked">
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-unlock-fill me-1"></i>Unlock Month (Super Admin)
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Financial Breakdown Grid -->
            <div class="row g-3 mt-3 pt-3 border-top">
                <div class="col-md-3 col-sm-6">
                    <div class="text-muted small">Total Level Commissions</div>
                    <div class="h5 fw-bold text-dark mb-0">₹<?= number_format($summary['l1_commission'] + $summary['upline_commission'], 2) ?></div>
                    <div class="text-muted small">L1: ₹<?= number_format($summary['l1_commission'], 2) ?> | Upline: ₹<?= number_format($summary['upline_commission'], 2) ?></div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="text-muted small">Monthly Special Bonus</div>
                    <div class="h5 fw-bold text-primary mb-0">₹<?= number_format($summary['monthly_bonus'], 2) ?></div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="text-muted small">Pool Bonus (PB)</div>
                    <div class="h5 fw-bold text-success mb-0">₹<?= number_format($summary['pool_bonus'], 2) ?></div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="text-muted small">Lifetime Rewards Settled</div>
                    <div class="h5 fw-bold text-warning mb-0">₹<?= number_format($summary['total_rewards'], 2) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cycle History Table -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Historical Settlement Cycles</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th>Cycle Period</th>
                        <th class="text-end">Business Amount</th>
                        <th class="text-end">Commissions</th>
                        <th class="text-end">Monthly Bonus</th>
                        <th class="text-end">Pool Bonus</th>
                        <th class="text-end">Settled Total</th>
                        <th>Lock Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php if (empty($allCycles)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No historical locked cycles found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($allCycles as $c): ?>
                            <tr>
                                <td class="fw-bold text-dark">
                                    <?= date('F', mktime(0,0,0, $c['cycle_month'], 10)) ?> <?= $c['cycle_year'] ?>
                                </td>
                                <td class="text-end">₹<?= number_format((float)$c['total_business_amount'], 2) ?></td>
                                <td class="text-end">₹<?= number_format((float)$c['total_commissions'], 2) ?></td>
                                <td class="text-end">₹<?= number_format((float)$c['total_bonus'], 2) ?></td>
                                <td class="text-end">₹<?= number_format((float)$c['total_pool'], 2) ?></td>
                                <td class="text-end fw-bold text-success fs-6">₹<?= number_format((float)$c['total_payable'], 2) ?></td>
                                <td>
                                    <span class="badge <?= $c['is_locked'] ? 'bg-danger' : 'bg-success' ?>">
                                        <?= $c['is_locked'] ? 'Locked' : 'Open' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= url('/admin/commissions/cycles?month=' . $c['cycle_month'] . '&year=' . $c['cycle_year']) ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>View
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

