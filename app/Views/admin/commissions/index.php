<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Commission Management Desk
 */
$title = $pageTitle ?? 'Commission Management Desk';
?>

<div class="container-fluid py-4">
    <!-- Header Banner -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-cash-stack text-warning me-2"></i>Commission Management Desk
            </h1>
            <p class="text-muted small mb-0">Multi-Level Advisor Commissions, Approvals, Approvals Ledger & Audit Trails</p>
        </div>
        <div class="d-flex gap-2 mt-2 mt-md-0">
            <a href="<?= url('/admin/commissions/simulator') ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-calculator me-1"></i>Commission Simulator
            </a>
            <a href="<?= url('/admin/commissions/pool-tree') ?>" class="btn btn-outline-success btn-sm">
                <i class="bi bi-diagram-3 me-1"></i>Pool Tree (PB1–PB11)
            </a>
            <a href="<?= url('/admin/commissions/settings') ?>" class="btn btn-primary btn-sm shadow-sm">
                <i class="bi bi-sliders me-1"></i>Control Center & Rules
            </a>
        </div>
    </div>

    <!-- Financial KPI Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="text-muted small fw-semibold text-uppercase">Pending Approval</div>
                    <div class="h3 fw-bold text-dark mt-2 mb-0">₹<?= number_format($stats['pending_amount'] ?? 0, 2) ?></div>
                    <div class="text-muted small mt-1"><?= (int)($stats['pending_count'] ?? 0) ?> transactions awaiting review</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="text-muted small fw-semibold text-uppercase">Approved & Credited</div>
                    <div class="h3 fw-bold text-success mt-2 mb-0">₹<?= number_format($stats['approved_amount'] ?? 0, 2) ?></div>
                    <div class="text-muted small mt-1"><?= (int)($stats['approved_count'] ?? 0) ?> transactions settled</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="text-muted small fw-semibold text-uppercase">Reversed / Cancelled</div>
                    <div class="h3 fw-bold text-danger mt-2 mb-0">₹<?= number_format($stats['reversed_amount'] ?? 0, 2) ?></div>
                    <div class="text-muted small mt-1">Reversal debit entries</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-info">
                <div class="card-body">
                    <div class="text-muted small fw-semibold text-uppercase">Cycle Period</div>
                    <div class="h4 fw-bold text-primary mt-2 mb-0"><?= date('F', mktime(0, 0, 0, $month, 10)) ?> <?= $year ?></div>
                    <div class="text-muted small mt-1">
                        <a href="<?= url('/admin/commissions/cycles?month=' . $month . '&year=' . $year) ?>" class="text-decoration-none">View Cycle Status & Lock &raquo;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="<?= url('/admin/commissions') ?>" class="row g-2 align-items-center">
                <div class="col-md-2 col-sm-6">
                    <label class="small text-muted mb-1">Month</label>
                    <select name="month" class="form-select form-select-sm">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $month == $m ? 'selected' : '' ?>>
                                <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-1 col-sm-6">
                    <label class="small text-muted mb-1">Year</label>
                    <select name="year" class="form-select form-select-sm">
                        <?php for ($y = 2024; $y <= 2030; $y++): ?>
                            <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="small text-muted mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="PENDING" <?= $status === 'PENDING' ? 'selected' : '' ?>>Pending Approval</option>
                        <option value="APPROVED" <?= $status === 'APPROVED' ? 'selected' : '' ?>>Approved</option>
                        <option value="CREDITED_TO_WALLET" <?= $status === 'CREDITED_TO_WALLET' ? 'selected' : '' ?>>Credited to Wallet</option>
                        <option value="ON_HOLD" <?= $status === 'ON_HOLD' ? 'selected' : '' ?>>On Hold</option>
                        <option value="REJECTED" <?= $status === 'REJECTED' ? 'selected' : '' ?>>Rejected</option>
                        <option value="REVERSED" <?= $status === 'REVERSED' ? 'selected' : '' ?>>Reversed</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="small text-muted mb-1">Type</label>
                    <select name="type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="CUSTOMER_REFERRAL" <?= $type === 'CUSTOMER_REFERRAL' ? 'selected' : '' ?>>Customer Referral</option>
                        <option value="JOINING_COMMISSION" <?= $type === 'JOINING_COMMISSION' ? 'selected' : '' ?>>Advisor Joining</option>
                        <option value="MONTHLY_SPECIAL_BONUS" <?= $type === 'MONTHLY_SPECIAL_BONUS' ? 'selected' : '' ?>>Monthly Special Bonus</option>
                        <option value="POOL_BONUS" <?= $type === 'POOL_BONUS' ? 'selected' : '' ?>>Pool Bonus</option>
                    </select>
                </div>
                <div class="col-md-1 col-sm-6">
                    <label class="small text-muted mb-1">Level</label>
                    <select name="level" class="form-select form-select-sm">
                        <option value="">All</option>
                        <?php for ($lvl = 1; $lvl <= 9; $lvl++): ?>
                            <option value="<?= $lvl ?>" <?= $level == $lvl ? 'selected' : '' ?>>L<?= $lvl ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label class="small text-muted mb-1">Search</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Advisor, Customer or Txn Code" value="<?= htmlspecialchars($search ?? '') ?>">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                <div class="col-md-1 col-sm-12 text-end pt-3">
                    <a href="<?= url('/admin/commissions') ?>" class="btn btn-outline-secondary btn-sm" title="Reset Filters"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Action Toolbar & Table -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <input type="checkbox" id="selectAll" class="form-check-input mt-0">
                <label for="selectAll" class="small fw-semibold text-muted mb-0">Select All Pending</label>
                <button type="button" id="btnBulkApprove" class="btn btn-success btn-sm ms-3" disabled>
                    <i class="bi bi-check-circle me-1"></i>Approve Selected
                </button>
            </div>
            <div class="small text-muted">
                Showing <?= count($transactions) ?> record(s)
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="commTable">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th width="40"></th>
                        <th>Txn Code</th>
                        <th>Credit Date / Month</th>
                        <th>Level</th>
                        <th>Advisor</th>
                        <th>Customer / Source</th>
                        <th>Type & Product</th>
                        <th class="text-end">Gross</th>
                        <th class="text-end">TDS (5%)</th>
                        <th class="text-end">Net Payable</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="12" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                No commission transactions found for the selected filters.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $txn): ?>
                            <?php 
                                $isPending = ($txn['status'] === 'PENDING' && $txn['is_reversal'] == 0);
                                $statusBadge = match($txn['status']) {
                                    'APPROVED' => 'bg-success',
                                    'CREDITED_TO_WALLET' => 'bg-success text-white',
                                    'PENDING' => 'bg-warning text-dark',
                                    'ON_HOLD' => 'bg-info text-dark',
                                    'REJECTED' => 'bg-danger',
                                    'REVERSED' => 'bg-secondary',
                                    default => 'bg-light text-dark'
                                };
                            ?>
                            <?php
                                $sourceName = !empty($txn['customer_name']) ? $txn['customer_name'] : (!empty($txn['source_advisor_name']) ? $txn['source_advisor_name'] : '—');
                                $sourceCode = !empty($txn['customer_code']) ? $txn['customer_code'] : (!empty($txn['source_advisor_code']) ? $txn['source_advisor_code'] : '');
                                $monthName = date('F', mktime(0, 0, 0, (int)$txn['commission_month'], 10)) . ' ' . $txn['commission_year'];
                                $creditDateStr = !empty($txn['company_credit_date']) ? $txn['company_credit_date'] : date('Y-m-d', strtotime($txn['created_at']));
                            ?>
                            <tr class="<?= $txn['is_reversal'] ? 'table-danger' : '' ?>">
                                <td>
                                    <?php if ($txn['status'] === 'PENDING' && !$txn['is_reversal']): ?>
                                        <input type="checkbox" class="form-check-input comm-checkbox" value="<?= $txn['id'] ?>">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="fw-bold font-monospace text-dark"><?= htmlspecialchars($txn['transaction_code']) ?></span>
                                    <?php if ($txn['is_reversal']): ?>
                                        <span class="badge bg-danger ms-1">REVERSAL</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div><?= htmlspecialchars($creditDateStr) ?></div>
                                    <span class="badge bg-light text-secondary border">
                                        <?= date('M', mktime(0, 0, 0, $txn['commission_month'], 10)) ?> <?= $txn['commission_year'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary fw-bold">L<?= $txn['level'] ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($txn['advisor_name']) ?></div>
                                    <div class="text-muted small font-monospace"><?= htmlspecialchars($txn['advisor_code']) ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($txn['customer_name'])): ?>
                                        <div class="fw-semibold"><?= htmlspecialchars($txn['customer_name']) ?></div>
                                        <div class="text-muted small font-monospace"><?= htmlspecialchars($txn['customer_code']) ?></div>
                                    <?php elseif (!empty($txn['source_advisor_name'])): ?>
                                        <div class="fw-semibold"><?= htmlspecialchars($txn['source_advisor_name']) ?></div>
                                        <div class="text-muted small font-monospace"><?= htmlspecialchars($txn['source_advisor_code']) ?></div>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= str_replace('_', ' ', $txn['commission_type']) ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($txn['product_name'] ?? 'General') ?></div>
                                </td>
                                <td class="text-end fw-semibold">₹<?= number_format((float)$txn['gross_amount'], 2) ?></td>
                                <td class="text-end text-muted">₹<?= number_format((float)$txn['tds_deducted'], 2) ?></td>
                                <td class="text-end fw-bold text-success fs-6">₹<?= number_format((float)$txn['net_amount'], 2) ?></td>
                                <td>
                                    <span class="badge <?= $statusBadge ?>"><?= $txn['status'] ?></span>
                                    <?php if ($txn['qualification_status'] === 'NOT_ELIGIBLE'): ?>
                                        <div class="text-danger small mt-1" title="<?= htmlspecialchars($txn['qualification_notes'] ?? '') ?>">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Not Qualified
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-info btn-sm view-snapshot" 
                                                data-snapshot="<?= htmlspecialchars($txn['rule_snapshot_json'] ?? '{}', ENT_QUOTES, 'UTF-8') ?>" 
                                                data-code="<?= htmlspecialchars($txn['transaction_code'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-notes="<?= htmlspecialchars($txn['qualification_notes'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-advisor-name="<?= htmlspecialchars($txn['advisor_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-advisor-code="<?= htmlspecialchars($txn['advisor_code'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-source-name="<?= htmlspecialchars($sourceName, ENT_QUOTES, 'UTF-8') ?>"
                                                data-source-code="<?= htmlspecialchars($sourceCode, ENT_QUOTES, 'UTF-8') ?>"
                                                data-level="<?= (int)$txn['level'] ?>"
                                                data-type="<?= htmlspecialchars(str_replace('_', ' ', $txn['commission_type'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                                data-product="<?= htmlspecialchars($txn['product_name'] ?? 'Solar System', ENT_QUOTES, 'UTF-8') ?>"
                                                data-gross="<?= number_format((float)$txn['gross_amount'], 2, '.', '') ?>"
                                                data-tds="<?= number_format((float)$txn['tds_deducted'], 2, '.', '') ?>"
                                                data-net="<?= number_format((float)$txn['net_amount'], 2, '.', '') ?>"
                                                data-status="<?= htmlspecialchars($txn['status'] ?? 'PENDING', ENT_QUOTES, 'UTF-8') ?>"
                                                data-period="<?= htmlspecialchars($monthName, ENT_QUOTES, 'UTF-8') ?>"
                                                data-date="<?= htmlspecialchars($creditDateStr, ENT_QUOTES, 'UTF-8') ?>"
                                                title="View Audit Breakdown Snapshot">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <?php if ($txn['status'] === 'PENDING' && !$txn['is_reversal']): ?>
                                            <button type="button" class="btn btn-success btn-sm btn-approve" data-id="<?= $txn['id'] ?>" title="Approve & Credit Wallet">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm btn-hold" data-id="<?= $txn['id'] ?>" title="Put on Hold">
                                                <i class="bi bi-pause"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm btn-reject" data-id="<?= $txn['id'] ?>" title="Reject">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        <?php elseif ($txn['status'] === 'ON_HOLD' && !$txn['is_reversal']): ?>
                                            <button type="button" class="btn btn-success btn-sm btn-approve" data-id="<?= $txn['id'] ?>" title="Approve & Credit Wallet">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm btn-reject" data-id="<?= $txn['id'] ?>" title="Reject">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        <?php elseif (in_array($txn['status'], ['APPROVED', 'CREDITED_TO_WALLET']) && !$txn['is_reversal']): ?>
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-reverse" data-id="<?= $txn['id'] ?>" data-code="<?= htmlspecialchars($txn['transaction_code'] ?? '', ENT_QUOTES, 'UTF-8') ?>" title="Reverse Commission">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Snapshot Audit Breakdown Modal -->
<div class="modal fade" id="snapshotModal" tabindex="-1" aria-labelledby="snapshotModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-primary text-white py-3">
                <div>
                    <h5 class="modal-title h6 mb-1 d-flex align-items-center gap-2" id="snapshotModalTitle">
                        <i class="bi bi-shield-check text-warning"></i>
                        <span>Commission Audit & Calculation Breakdown</span>
                    </h5>
                    <div class="small opacity-75 font-monospace" id="modalTxnCodeHeader">#TXN-CODE</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <!-- Financial KPI Cards -->
                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <div class="bg-white p-3 rounded-2 border text-center shadow-sm">
                            <div class="text-muted small text-uppercase fw-semibold" style="font-size: 0.72rem;">Gross Commission</div>
                            <div class="h5 fw-bold text-dark mb-0 mt-1" id="modalGross">₹0.00</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-white p-3 rounded-2 border text-center shadow-sm">
                            <div class="text-muted small text-uppercase fw-semibold" style="font-size: 0.72rem;">TDS Deduction (5%)</div>
                            <div class="h5 fw-bold text-danger mb-0 mt-1" id="modalTds">-₹0.00</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-white p-3 rounded-2 border text-center border-success shadow-sm">
                            <div class="text-muted small text-uppercase fw-semibold text-success" style="font-size: 0.72rem;">Net Payable</div>
                            <div class="h5 fw-bold text-success mb-0 mt-1" id="modalNet">₹0.00</div>
                        </div>
                    </div>
                </div>

                <!-- Core Details Grid -->
                <div class="card border shadow-none bg-white mb-3">
                    <div class="card-body p-3">
                        <h6 class="small fw-bold text-uppercase text-muted mb-3 border-bottom pb-2">
                            <i class="bi bi-info-circle me-1 text-primary"></i>Transaction & Hierarchy Overview
                        </h6>
                        <div class="row g-3 small">
                            <div class="col-md-6">
                                <div class="text-muted mb-1">Beneficiary Advisor:</div>
                                <div class="fw-bold text-dark fs-6" id="modalAdvisorName">—</div>
                                <div class="font-monospace text-muted small" id="modalAdvisorCode">—</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted mb-1">Customer / Source:</div>
                                <div class="fw-bold text-dark fs-6" id="modalSourceName">—</div>
                                <div class="font-monospace text-muted small" id="modalSourceCode">—</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted mb-1">Commission Type:</div>
                                <div class="fw-semibold text-dark" id="modalType">—</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted mb-1">Hierarchy Level:</div>
                                <span class="badge bg-primary-subtle text-primary fw-bold" id="modalLevel">L1</span>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted mb-1">Accounting Period:</div>
                                <div class="fw-semibold text-dark" id="modalPeriod">—</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted mb-1">Company Credit Date:</div>
                                <div class="fw-semibold text-dark font-monospace" id="modalCreditDate">—</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted mb-1">Current Status:</div>
                                <span class="badge bg-secondary" id="modalStatusBadge">PENDING</span>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted mb-1">Product / Scheme:</div>
                                <div class="fw-semibold text-dark" id="modalProduct">—</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Qualification & Rule Engine Notes -->
                <div class="alert alert-info border mb-3 p-3" id="modalNotesAlert">
                    <div class="fw-bold small text-uppercase mb-1"><i class="bi bi-check2-circle me-1"></i>Qualification Audit Note:</div>
                    <div id="modalNotesText" class="small">Standard system calculation.</div>
                </div>

                <!-- Snapshot Parameters Table (Parsed from JSON) -->
                <div class="card border shadow-none bg-white mb-3">
                    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-uppercase text-muted"><i class="bi bi-sliders me-1 text-warning"></i>Applied Rule Engine Snapshot</span>
                        <span class="badge bg-light text-secondary border">Immutable Snapshot</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0 small">
                            <tbody id="modalSnapshotTableBody">
                                <!-- Populated dynamically by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Collapsible Raw Developer Payload -->
                <div class="accordion" id="devJsonAccordion">
                    <div class="accordion-item border rounded">
                        <h2 class="accordion-header" id="headingDevJson">
                            <button class="accordion-button collapsed py-2 small fw-semibold text-muted bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDevJson" aria-expanded="false" aria-controls="collapseDevJson">
                                <i class="bi bi-code-slash me-2"></i>View Raw Developer JSON Payload
                            </button>
                        </h2>
                        <div id="collapseDevJson" class="accordion-collapse collapse" aria-labelledby="headingDevJson" data-bs-parent="#devJsonAccordion">
                            <div class="accordion-body p-2 bg-dark">
                                <pre class="text-light mb-0 p-2 small font-monospace" id="modalSnapshotJson" style="max-height: 220px; overflow-y: auto; font-size: 0.78rem; line-height: 1.4;"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white py-2">
                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = '<?= rtrim(url(''), '/') ?>';

    // Helper: Universal POST helper with fallback
    async function apiPost(endpoint, data) {
        const formData = new URLSearchParams();
        for (const key in data) {
            if (Array.isArray(data[key])) {
                data[key].forEach(val => formData.append(key + '[]', val));
            } else {
                formData.append(key, data[key]);
            }
        }

        const response = await fetch(baseUrl + endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData.toString()
        });

        if (!response.ok) {
            throw new Error('Server returned HTTP status ' + response.status);
        }

        return await response.json();
    }

    // Currency Formatter
    function formatInr(val) {
        const num = parseFloat(val) || 0;
        return '₹' + num.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Select All Checkbox Handler
    const selectAllCheckbox = document.getElementById('selectAll');
    const bulkApproveBtn = document.getElementById('btnBulkApprove');

    function updateBulkButton() {
        const checkedBoxes = document.querySelectorAll('.comm-checkbox:checked');
        const count = checkedBoxes.length;
        if (bulkApproveBtn) {
            bulkApproveBtn.disabled = (count === 0);
            bulkApproveBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Approve Selected (' + count + ')';
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.comm-checkbox').forEach(cb => {
                cb.checked = isChecked;
            });
            updateBulkButton();
        });
    }

    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('comm-checkbox')) {
            updateBulkButton();
        }
    });

    // Global Delegated Click Handler
    document.addEventListener('click', async function(e) {
        // 1. View Snapshot Modal
        const viewBtn = e.target.closest('.view-snapshot');
        if (viewBtn) {
            e.preventDefault();
            const code = viewBtn.getAttribute('data-code') || '';
            const notes = viewBtn.getAttribute('data-notes') || '';
            const rawJson = viewBtn.getAttribute('data-snapshot') || '{}';
            const advName = viewBtn.getAttribute('data-advisor-name') || '—';
            const advCode = viewBtn.getAttribute('data-advisor-code') || '—';
            const srcName = viewBtn.getAttribute('data-source-name') || '—';
            const srcCode = viewBtn.getAttribute('data-source-code') || '—';
            const lvl = viewBtn.getAttribute('data-level') || '1';
            const type = viewBtn.getAttribute('data-type') || '—';
            const prod = viewBtn.getAttribute('data-product') || '—';
            const gross = viewBtn.getAttribute('data-gross') || '0';
            const tds = viewBtn.getAttribute('data-tds') || '0';
            const net = viewBtn.getAttribute('data-net') || '0';
            const status = viewBtn.getAttribute('data-status') || 'PENDING';
            const period = viewBtn.getAttribute('data-period') || '—';
            const dateStr = viewBtn.getAttribute('data-date') || '—';

            // Populate Overview Elements
            document.getElementById('modalTxnCodeHeader').textContent = '#' + code;
            document.getElementById('modalGross').textContent = formatInr(gross);
            document.getElementById('modalTds').textContent = '-' + formatInr(tds);
            document.getElementById('modalNet').textContent = formatInr(net);
            document.getElementById('modalAdvisorName').textContent = advName;
            document.getElementById('modalAdvisorCode').textContent = advCode;
            document.getElementById('modalSourceName').textContent = srcName;
            document.getElementById('modalSourceCode').textContent = srcCode;
            document.getElementById('modalType').textContent = type;
            document.getElementById('modalLevel').textContent = 'Level ' + lvl;
            document.getElementById('modalPeriod').textContent = period;
            document.getElementById('modalCreditDate').textContent = dateStr;
            document.getElementById('modalProduct').textContent = prod;

            const statusBadge = document.getElementById('modalStatusBadge');
            statusBadge.textContent = status;
            statusBadge.className = 'badge ' + (status === 'APPROVED' || status === 'CREDITED_TO_WALLET' ? 'bg-success' : (status === 'ON_HOLD' ? 'bg-info text-dark' : (status === 'REJECTED' ? 'bg-danger' : 'bg-warning text-dark')));

            document.getElementById('modalNotesText').innerHTML = notes ? escapeHtml(notes) : '<span class="text-muted">Standard rule engine matrix applied without exceptions.</span>';

            // Parse Snapshot JSON into Table
            let parsed = {};
            try {
                parsed = (typeof rawJson === 'object') ? rawJson : JSON.parse(rawJson);
                if (typeof parsed === 'string') {
                    try { parsed = JSON.parse(parsed); } catch(_) {}
                }
            } catch(err) {
                parsed = { raw: rawJson };
            }

            const tableBody = document.getElementById('modalSnapshotTableBody');
            tableBody.innerHTML = '';

            if (parsed && typeof parsed === 'object' && Object.keys(parsed).length > 0) {
                for (const key in parsed) {
                    const tr = document.createElement('tr');
                    const th = document.createElement('th');
                    th.className = 'text-muted fw-semibold ps-3';
                    th.style.width = '35%';
                    th.textContent = formatKeyName(key);

                    const td = document.createElement('td');
                    td.className = 'pe-3 font-monospace';
                    let val = parsed[key];
                    if (typeof val === 'object' && val !== null) {
                        td.textContent = JSON.stringify(val);
                    } else if (typeof val === 'number') {
                        td.textContent = val.toString();
                    } else {
                        td.textContent = val !== null && val !== undefined ? val : '—';
                    }
                    tr.appendChild(th);
                    tr.appendChild(td);
                    tableBody.appendChild(tr);
                }
            } else {
                tableBody.innerHTML = '<tr><td colspan="2" class="text-muted text-center py-2">No snapshot rule parameters recorded.</td></tr>';
            }

            // Developer Payload
            const jsonPre = document.getElementById('modalSnapshotJson');
            jsonPre.textContent = JSON.stringify(parsed, null, 4);

            const modalEl = document.getElementById('snapshotModal');
            if (window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            } else if (window.$) {
                $('#snapshotModal').modal('show');
            }
            return;
        }

        // 2. Single Approve
        const approveBtn = e.target.closest('.btn-approve');
        if (approveBtn) {
            e.preventDefault();
            const id = approveBtn.getAttribute('data-id');
            if (!id) return;

            if (!confirm('Approve this commission and dispatch ledger credit to the Advisor wallet?')) {
                return;
            }

            const originalHtml = approveBtn.innerHTML;
            approveBtn.disabled = true;
            approveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

            try {
                const res = await apiPost('/admin/commissions/approve', { transaction_id: id });
                if (res.status) {
                    window.location.reload();
                } else {
                    alert('Approval Failed: ' + (res.message || 'Unknown error'));
                    approveBtn.disabled = false;
                    approveBtn.innerHTML = originalHtml;
                }
            } catch(err) {
                alert('Request failed: ' + err.message);
                approveBtn.disabled = false;
                approveBtn.innerHTML = originalHtml;
            }
            return;
        }

        // 3. Put on Hold
        const holdBtn = e.target.closest('.btn-hold');
        if (holdBtn) {
            e.preventDefault();
            const id = holdBtn.getAttribute('data-id');
            if (!id) return;

            const reason = prompt('Reason for placing this commission on hold:');
            if (reason === null) return;

            const originalHtml = holdBtn.innerHTML;
            holdBtn.disabled = true;
            holdBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

            try {
                const res = await apiPost('/admin/commissions/hold', { transaction_id: id, reason: reason });
                if (res.status) {
                    window.location.reload();
                } else {
                    alert('Hold action failed: ' + (res.message || 'Unknown error'));
                    holdBtn.disabled = false;
                    holdBtn.innerHTML = originalHtml;
                }
            } catch(err) {
                alert('Request failed: ' + err.message);
                holdBtn.disabled = false;
                holdBtn.innerHTML = originalHtml;
            }
            return;
        }

        // 4. Reject
        const rejectBtn = e.target.closest('.btn-reject');
        if (rejectBtn) {
            e.preventDefault();
            const id = rejectBtn.getAttribute('data-id');
            if (!id) return;

            const reason = prompt('Please enter rejection reason:');
            if (reason === null) return;

            const originalHtml = rejectBtn.innerHTML;
            rejectBtn.disabled = true;
            rejectBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

            try {
                const res = await apiPost('/admin/commissions/reject', { transaction_id: id, reason: reason });
                if (res.status) {
                    window.location.reload();
                } else {
                    alert('Rejection failed: ' + (res.message || 'Unknown error'));
                    rejectBtn.disabled = false;
                    rejectBtn.innerHTML = originalHtml;
                }
            } catch(err) {
                alert('Request failed: ' + err.message);
                rejectBtn.disabled = false;
                rejectBtn.innerHTML = originalHtml;
            }
            return;
        }

        // 5. Reverse
        const reverseBtn = e.target.closest('.btn-reverse');
        if (reverseBtn) {
            e.preventDefault();
            const id = reverseBtn.getAttribute('data-id');
            const code = reverseBtn.getAttribute('data-code') || '';
            if (!id) return;

            const reason = prompt('REVERSAL: Enter reason for reversing commission ' + code + ' (this will debit the Advisor wallet ledger):');
            if (!reason) return;

            const originalHtml = reverseBtn.innerHTML;
            reverseBtn.disabled = true;
            reverseBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

            try {
                const res = await apiPost('/admin/commissions/reverse', { transaction_id: id, reason: reason });
                if (res.status) {
                    alert('Commission reversed successfully. Ledger debit recorded.');
                    window.location.reload();
                } else {
                    alert('Reversal failed: ' + (res.message || 'Unknown error'));
                    reverseBtn.disabled = false;
                    reverseBtn.innerHTML = originalHtml;
                }
            } catch(err) {
                alert('Request failed: ' + err.message);
                reverseBtn.disabled = false;
                reverseBtn.innerHTML = originalHtml;
            }
            return;
        }

        // 6. Bulk Approve
        const bulkBtn = e.target.closest('#btnBulkApprove');
        if (bulkBtn) {
            e.preventDefault();
            const checkedBoxes = document.querySelectorAll('.comm-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            if (ids.length === 0) return;

            if (!confirm('Approve ' + ids.length + ' selected commission transactions and credit advisor wallets?')) {
                return;
            }

            const originalHtml = bulkBtn.innerHTML;
            bulkBtn.disabled = true;
            bulkBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Processing...';

            try {
                const res = await apiPost('/admin/commissions/bulk-approve', { transaction_ids: ids });
                if (res.status) {
                    alert('Successfully approved ' + (res.approved_count || ids.length) + ' commissions!');
                    window.location.reload();
                } else {
                    alert('Bulk Approval Error: ' + (res.message || 'Unknown error'));
                    bulkBtn.disabled = false;
                    bulkBtn.innerHTML = originalHtml;
                }
            } catch(err) {
                alert('Request failed: ' + err.message);
                bulkBtn.disabled = false;
                bulkBtn.innerHTML = originalHtml;
            }
            return;
        }
    });

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatKeyName(key) {
        return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }
});
</script>

