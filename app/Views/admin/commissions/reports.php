<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Financial & Commission Ledger Reports Desk
 */
$title = $pageTitle ?? 'Commission & Financial Reports';
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-file-earmark-spreadsheet text-success me-2"></i>Commission & Financial Ledger Reports
            </h1>
            <p class="text-muted small mb-0">Auditable Financial Statements, Advisor Earnings & System Audit Logs</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url('/admin/commissions') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to Desk
            </a>
            <a href="<?= url('/admin/commissions/reports?report=' . $reportType . '&month=' . $month . '&year=' . $year . '&export=csv') ?>" class="btn btn-success btn-sm shadow-sm">
                <i class="bi bi-download me-1"></i>Export to CSV
            </a>
        </div>
    </div>

    <!-- Report Type Tabs -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link <?= $reportType === 'advisor_earnings' ? 'active' : '' ?>" href="<?= url('/admin/commissions/reports?report=advisor_earnings&month=' . $month . '&year=' . $year) ?>">
                <i class="bi bi-people me-1"></i>Advisor Earnings Breakdown
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $reportType === 'audit_logs' ? 'active' : '' ?>" href="<?= url('/admin/commissions/reports?report=audit_logs') ?>">
                <i class="bi bi-shield-check me-1"></i>Commission Audit Trail
            </a>
        </li>
    </ul>

    <!-- Filter Bar for Advisor Earnings -->
    <?php if ($reportType === 'advisor_earnings'): ?>
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-3">
                <form method="GET" action="<?= url('/admin/commissions/reports') ?>" class="row g-2 align-items-center">
                    <input type="hidden" name="report" value="advisor_earnings">
                    <div class="col-md-3">
                        <label class="small text-muted mb-1">Month</label>
                        <select name="month" class="form-select form-select-sm">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $month == $m ? 'selected' : '' ?>><?= date('F', mktime(0,0,0,$m,10)) ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted mb-1">Year</label>
                        <select name="year" class="form-select form-select-sm">
                            <?php for ($y = 2024; $y <= 2030; $y++): ?>
                                <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2 pt-3">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-filter me-1"></i>Filter Period</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Advisor Earnings Table -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th>Advisor</th>
                            <th class="text-end">Joining Comm</th>
                            <th class="text-end">Direct Customer</th>
                            <th class="text-end">Upline Comm</th>
                            <th class="text-end">Special Bonus</th>
                            <th class="text-end">Pool Bonus</th>
                            <th class="text-end">Total Approved</th>
                            <th class="text-end">Pending</th>
                            <th class="text-end">Wallet Balance</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php if (empty($data)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">No earning data found for selected period.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data as $row): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($row['advisor_name']) ?></div>
                                        <div class="text-muted font-monospace small"><?= htmlspecialchars($row['advisor_code']) ?></div>
                                    </td>
                                    <td class="text-end">₹<?= number_format((float)$row['joining_earnings'], 2) ?></td>
                                    <td class="text-end">₹<?= number_format((float)$row['direct_customer_earnings'], 2) ?></td>
                                    <td class="text-end">₹<?= number_format((float)$row['upline_earnings'], 2) ?></td>
                                    <td class="text-end">₹<?= number_format((float)$row['monthly_bonus_earnings'], 2) ?></td>
                                    <td class="text-end">₹<?= number_format((float)$row['pool_earnings'], 2) ?></td>
                                    <td class="text-end fw-bold text-success fs-6">₹<?= number_format((float)$row['total_approved_earnings'], 2) ?></td>
                                    <td class="text-end text-warning">₹<?= number_format((float)$row['pending_earnings'], 2) ?></td>
                                    <td class="text-end fw-bold text-primary">₹<?= number_format((float)$row['current_wallet_balance'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <!-- Commission Audit Trail Table -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th>Timestamp</th>
                            <th>Action</th>
                            <th>Entity</th>
                            <th>Reason / Description</th>
                            <th>Role / IP</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php if (empty($data)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No audit logs recorded yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data as $log): ?>
                                <tr>
                                    <td class="font-monospace text-muted"><?= $log['created_at'] ?></td>
                                    <td><span class="badge bg-primary"><?= htmlspecialchars($log['action']) ?></span></td>
                                    <td><?= htmlspecialchars($log['entity_type']) ?> #<?= $log['entity_id'] ?></td>
                                    <td><?= htmlspecialchars($log['reason'] ?? '—') ?></td>
                                    <td class="font-monospace small"><?= htmlspecialchars($log['user_role'] ?? 'ADMIN') ?> (<?= htmlspecialchars($log['ip_address'] ?? '127.0.0.1') ?>)</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

