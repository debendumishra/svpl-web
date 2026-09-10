<?php
/**
 * Company Financial Books & Account Ledger View
 * Solar Luminary Design System
 */
$title = $pageTitle ?? "Company Financial Books & Account Ledger — SVPL";
$entries = $entries ?? [];
$summary = $summary ?? [
    'total_receipts' => 0,
    'total_payments' => 0,
    'net_balance' => 0,
    'today_receipts' => 0,
    'today_payments' => 0,
    'total_transactions' => 0
];
$filters = $filters ?? [];
$activeTab = $activeTab ?? 'book';
$distinctParties = $distinctParties ?? [];
$advisors = $advisors ?? [];
$customers = $customers ?? [];
$headSummary = $headSummary ?? [];
$selectedParty = $selectedParty ?? '';
$partyStatement = $partyStatement ?? null;
?>

<div class="container-fluid px-3 px-lg-4 py-3">
    
    <!-- PAGE HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-navy text-warning fw-bold px-2 py-1">
                    <i class="bi bi-book-half me-1"></i> Double-Entry Accounts
                </span>
                <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold">
                    Live Treasury Books
                </span>
            </div>
            <h3 class="font-heading fw-bold mb-0 text-navy">Company Financial Ledger & Account Books</h3>
            <p class="text-secondary small mb-0">Daily monetary transactions date-wise, party-wise ledgers, payments, and receipts</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= url('/admin/ledger/export?' . http_build_query($filters)) ?>" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="bi bi-download me-1"></i> Export CSV
            </a>
            <button type="button" class="btn btn-svpl-solar btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNewLedgerEntry">
                <i class="bi bi-plus-circle-fill me-1"></i> + Record Transaction
            </button>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i>
            <div><?= htmlspecialchars($success) ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-5 me-2"></i>
            <div><?= htmlspecialchars($error) ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- TOP FINANCIAL METRICS WIDGETS -->
    <div class="row g-3 mb-4">
        <!-- Total Inflows / Receipts -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100 bg-white border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small text-uppercase fw-bold tracking-wide">Total Receipts (Inflow)</span>
                    <div class="badge bg-success bg-opacity-10 text-success p-2 rounded-circle">
                        <i class="bi bi-arrow-down-left fs-5"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h3 class="font-heading fw-bold text-success mb-0">₹<?= number_format($summary['total_receipts'], 2) ?></h3>
                </div>
                <div class="text-muted small mt-1">
                    <i class="bi bi-calendar-check me-1"></i> Today: <strong class="text-success">+₹<?= number_format($summary['today_receipts'], 2) ?></strong>
                </div>
            </div>
        </div>

        <!-- Total Outflows / Payments -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100 bg-white border-start border-4 border-danger">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small text-uppercase fw-bold tracking-wide">Total Payments (Outflow)</span>
                    <div class="badge bg-danger bg-opacity-10 text-danger p-2 rounded-circle">
                        <i class="bi bi-arrow-up-right fs-5"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h3 class="font-heading fw-bold text-danger mb-0">₹<?= number_format($summary['total_payments'], 2) ?></h3>
                </div>
                <div class="text-muted small mt-1">
                    <i class="bi bi-calendar-check me-1"></i> Today: <strong class="text-danger">-₹<?= number_format($summary['today_payments'], 2) ?></strong>
                </div>
            </div>
        </div>

        <!-- Net Treasury Balance -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100 bg-white border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small text-uppercase fw-bold tracking-wide">Net Treasury Balance</span>
                    <div class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-circle">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h3 class="font-heading fw-bold text-navy mb-0">₹<?= number_format($summary['net_balance'], 2) ?></h3>
                </div>
                <div class="text-muted small mt-1">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                        <i class="bi bi-shield-check me-1"></i> Reconciled Ledger
                    </span>
                </div>
            </div>
        </div>

        <!-- Total Journal Transactions -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100 bg-white border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small text-uppercase fw-bold tracking-wide">Recorded Entries</span>
                    <div class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-circle">
                        <i class="bi bi-receipt-cutoff fs-5"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h3 class="font-heading fw-bold text-dark mb-0"><?= number_format($summary['total_transactions']) ?></h3>
                    <span class="text-muted small">Vouchers</span>
                </div>
                <div class="text-muted small mt-1">
                    <i class="bi bi-clock-history me-1"></i> Month: <strong>₹<?= number_format($summary['month_receipts'], 2) ?> In / ₹<?= number_format($summary['month_payments'], 2) ?> Out</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- VIEW MODE NAVIGATION TABS -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-bottom p-2 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <ul class="nav nav-pills" id="ledgerTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link <?= ($activeTab === 'book') ? 'active' : '' ?> fw-semibold" href="<?= url('/admin/ledger?tab=book') ?>">
                        <i class="bi bi-journal-text me-1"></i> Daily Cash & Bank Register
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($activeTab === 'party') ? 'active' : '' ?> fw-semibold" href="<?= url('/admin/ledger?tab=party') ?>">
                        <i class="bi bi-person-lines-fill me-1"></i> Party-wise Ledger Statement
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($activeTab === 'heads') ? 'active' : '' ?> fw-semibold" href="<?= url('/admin/ledger?tab=heads') ?>">
                        <i class="bi bi-pie-chart-fill me-1"></i> Account Head & Category Summary
                    </a>
                </li>
            </ul>
            <div class="d-none d-md-flex align-items-center gap-1 text-muted small">
                <i class="bi bi-info-circle text-primary"></i> Transactions are sorted chronologically with immutable running balances.
            </div>
        </div>

        <div class="card-body p-3">
            
            <!-- FILTER & SEARCH BAR (Available for all tabs) -->
            <form method="GET" action="<?= url('/admin/ledger') ?>" class="row g-2 align-items-end mb-3">
                <input type="hidden" name="tab" value="<?= htmlspecialchars($activeTab) ?>">

                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold text-secondary mb-1">From Date</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="<?= htmlspecialchars($filters['start_date'] ?? '') ?>">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold text-secondary mb-1">To Date</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="<?= htmlspecialchars($filters['end_date'] ?? '') ?>">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold text-secondary mb-1">Entry Type</label>
                    <select name="entry_type" class="form-select form-select-sm">
                        <option value="">-- All Types --</option>
                        <option value="RECEIPT" <?= (($filters['entry_type'] ?? '') === 'RECEIPT') ? 'selected' : '' ?>>Receipts (Inflows)</option>
                        <option value="PAYMENT" <?= (($filters['entry_type'] ?? '') === 'PAYMENT') ? 'selected' : '' ?>>Payments (Outflows)</option>
                        <option value="CONTRA" <?= (($filters['entry_type'] ?? '') === 'CONTRA') ? 'selected' : '' ?>>Contra / Transfer</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-semibold text-secondary mb-1">Party Type</label>
                    <select name="party_type" class="form-select form-select-sm">
                        <option value="">-- All Parties --</option>
                        <option value="ADVISOR" <?= (($filters['party_type'] ?? '') === 'ADVISOR') ? 'selected' : '' ?>>Advisor</option>
                        <option value="CUSTOMER" <?= (($filters['party_type'] ?? '') === 'CUSTOMER') ? 'selected' : '' ?>>Customer</option>
                        <option value="VENDOR" <?= (($filters['party_type'] ?? '') === 'VENDOR') ? 'selected' : '' ?>>Vendor / Logistics</option>
                        <option value="INTERNAL" <?= (($filters['party_type'] ?? '') === 'INTERNAL') ? 'selected' : '' ?>>Company Treasury</option>
                        <option value="BANK" <?= (($filters['party_type'] ?? '') === 'BANK') ? 'selected' : '' ?>>Bank</option>
                        <option value="OTHER" <?= (($filters['party_type'] ?? '') === 'OTHER') ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div class="col-8 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Search Party / UTR / Voucher</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="e.g. Ramesh, ADV-OD-001, UPI/..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>
                <div class="col-4 col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-navy btn-sm w-100 fw-bold" title="Filter Records">
                        <i class="bi bi-funnel-fill"></i>
                    </button>
                    <a href="<?= url('/admin/ledger?tab=' . $activeTab) ?>" class="btn btn-light border btn-sm" title="Clear Filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>

            <hr class="my-3 text-muted opacity-25">

            <!-- TAB 1: DAILY CASH & BANK REGISTER -->
            <?php if ($activeTab === 'book'): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small text-uppercase tracking-wider">
                            <tr>
                                <th>Date & Voucher</th>
                                <th>Account Head</th>
                                <th>Party Name & Code</th>
                                <th>Payment Mode</th>
                                <th>Reference / UTR</th>
                                <th class="text-end text-danger">Debit Outflow (₹)</th>
                                <th class="text-end text-success">Credit Inflow (₹)</th>
                                <th class="text-end text-navy fw-bold">Running Balance (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($entries)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary"></i>
                                        No financial entries found matching the filter criteria.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($entries as $e): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark"><?= date('d M Y', strtotime($e['entry_date'])) ?></div>
                                            <span class="badge bg-light text-dark font-monospace border" style="font-size: 0.72rem;">
                                                <?= htmlspecialchars($e['voucher_no']) ?>
                                            </span>
                                            <?php if ($e['entry_type'] === 'RECEIPT'): ?>
                                                <span class="badge bg-success-subtle text-success" style="font-size: 0.65rem;">RCPT</span>
                                            <?php elseif ($e['entry_type'] === 'PAYMENT'): ?>
                                                <span class="badge bg-danger-subtle text-danger" style="font-size: 0.65rem;">PMT</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;"><?= htmlspecialchars($e['entry_type']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-navy"><?= htmlspecialchars($e['account_head']) ?></div>
                                            <?php if (!empty($e['narration'])): ?>
                                                <div class="text-muted small text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars($e['narration']) ?>">
                                                    <?= htmlspecialchars($e['narration']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($e['party_name']) ?></div>
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.68rem;"><?= htmlspecialchars($e['party_type']) ?></span>
                                                <?php if (!empty($e['party_identifier'])): ?>
                                                    <span class="badge bg-light text-dark font-monospace border" style="font-size: 0.68rem;"><?= htmlspecialchars($e['party_identifier']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border fw-semibold">
                                                <?= htmlspecialchars($e['payment_mode']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($e['reference_no'])): ?>
                                                <span class="font-monospace small text-secondary">
                                                    <?= htmlspecialchars($e['reference_no']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted small">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end font-monospace text-danger fw-semibold">
                                            <?php if ($e['debit_amount'] > 0): ?>
                                                -₹<?= number_format($e['debit_amount'], 2) ?>
                                            <?php else: ?>
                                                <span class="text-muted opacity-50">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end font-monospace text-success fw-bold">
                                            <?php if ($e['credit_amount'] > 0): ?>
                                                +₹<?= number_format($e['credit_amount'], 2) ?>
                                            <?php else: ?>
                                                <span class="text-muted opacity-50">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end font-monospace fw-bold <?= ($e['running_balance'] >= 0) ? 'text-navy' : 'text-danger' ?>">
                                            ₹<?= number_format($e['running_balance'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <!-- TAB 2: PARTY-WISE LEDGER STATEMENT -->
            <?php elseif ($activeTab === 'party'): ?>
                <div class="row g-3">
                    <div class="col-lg-4">
                        <div class="card border rounded-3 p-3 bg-light">
                            <h6 class="font-heading fw-bold text-navy mb-2">Select Party for Ledger Statement</h6>
                            <p class="text-muted small mb-3">View customized statement of accounts for any Advisor, Customer, or Vendor.</p>
                            
                            <form method="GET" action="<?= url('/admin/ledger') ?>">
                                <input type="hidden" name="tab" value="party">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Party Name or Code</label>
                                    <input type="text" name="party_name" class="form-control" list="partyList" placeholder="Search or select party..." value="<?= htmlspecialchars($selectedParty) ?>" required>
                                    <datalist id="partyList">
                                        <?php foreach ($distinctParties as $dp): ?>
                                            <option value="<?= htmlspecialchars($dp['party_name']) ?>">
                                                <?= htmlspecialchars($dp['party_type']) ?> | <?= htmlspecialchars($dp['party_identifier'] ?? '') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </datalist>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Date Range</label>
                                    <div class="input-group input-group-sm">
                                        <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($filters['start_date'] ?? '') ?>">
                                        <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($filters['end_date'] ?? '') ?>">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-navy btn-sm w-100 fw-bold">
                                    <i class="bi bi-file-earmark-text me-1"></i> Generate Statement
                                </button>
                            </form>

                            <?php if (!empty($distinctParties)): ?>
                                <div class="mt-4">
                                    <label class="form-label small fw-semibold text-secondary text-uppercase">Frequent Parties</label>
                                    <div class="d-flex flex-column gap-1">
                                        <?php foreach (array_slice($distinctParties, 0, 8) as $p): ?>
                                            <a href="<?= url('/admin/ledger?tab=party&party_name=' . urlencode($p['party_name'])) ?>" class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none bg-white border hover-shadow-sm">
                                                <span class="small fw-semibold text-dark text-truncate" style="max-width: 170px;"><?= htmlspecialchars($p['party_name']) ?></span>
                                                <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.68rem;"><?= htmlspecialchars($p['party_identifier'] ?? $p['party_type']) ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <?php if (empty($selectedParty)): ?>
                            <div class="text-center py-5 text-muted bg-light rounded-3 p-4">
                                <i class="bi bi-person-lines-fill fs-1 d-block mb-2 text-secondary"></i>
                                <h6 class="fw-bold">No Party Selected</h6>
                                <p class="small mb-0">Select an Advisor, Customer, or Vendor from the left panel to display their double-entry transaction statement.</p>
                            </div>
                        <?php elseif (empty($partyStatement['transactions'])): ?>
                            <div class="text-center py-5 text-muted bg-light rounded-3 p-4">
                                <i class="bi bi-folder-x fs-1 d-block mb-2 text-secondary"></i>
                                <h6 class="fw-bold">No Transactions Found for "<?= htmlspecialchars($selectedParty) ?>"</h6>
                                <p class="small mb-0">No ledger entries exist for this party under the chosen date filters.</p>
                            </div>
                        <?php else: ?>
                            <!-- Party Statement Summary Card -->
                            <div class="card border-0 shadow-sm rounded-3 mb-3 bg-navy text-white p-3">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                                    <div>
                                        <small class="text-warning text-uppercase fw-bold">Party Statement of Account</small>
                                        <h4 class="font-heading fw-bold mb-0 text-white"><?= htmlspecialchars($selectedParty) ?></h4>
                                    </div>
                                    <div class="text-md-end">
                                        <div class="small text-white-50">Net Account Balance</div>
                                        <h4 class="font-heading fw-bold mb-0 <?= ($partyStatement['net_party_balance'] >= 0) ? 'text-success' : 'text-danger' ?>">
                                            ₹<?= number_format($partyStatement['net_party_balance'], 2) ?>
                                        </h4>
                                    </div>
                                </div>
                                <hr class="border-secondary my-2">
                                <div class="d-flex justify-content-between small text-white-50">
                                    <span>Total Received: <strong class="text-success font-monospace">₹<?= number_format($partyStatement['total_credits'], 2) ?></strong></span>
                                    <span>Total Paid: <strong class="text-danger font-monospace">₹<?= number_format($partyStatement['total_debits'], 2) ?></strong></span>
                                    <span>Entries: <strong class="text-white font-monospace"><?= count($partyStatement['transactions']) ?></strong></span>
                                </div>
                            </div>

                            <!-- Party Statement Transactions Table -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-secondary small text-uppercase">
                                        <tr>
                                            <th>Date & Voucher</th>
                                            <th>Account Head & Narration</th>
                                            <th>Mode / UTR</th>
                                            <th class="text-end text-danger">Debit (₹)</th>
                                            <th class="text-end text-success">Credit (₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($partyStatement['transactions'] as $pt): ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-bold text-dark"><?= date('d M Y', strtotime($pt['entry_date'])) ?></div>
                                                    <span class="badge bg-light text-dark font-monospace border" style="font-size: 0.7rem;"><?= htmlspecialchars($pt['voucher_no']) ?></span>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-navy"><?= htmlspecialchars($pt['account_head']) ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($pt['narration'] ?? '—') ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border"><?= htmlspecialchars($pt['payment_mode']) ?></span>
                                                    <?php if (!empty($pt['reference_no'])): ?>
                                                        <div class="font-monospace text-secondary" style="font-size: 0.72rem;"><?= htmlspecialchars($pt['reference_no']) ?></div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end font-monospace text-danger fw-semibold">
                                                    <?= ($pt['debit_amount'] > 0) ? '-₹' . number_format($pt['debit_amount'], 2) : '—' ?>
                                                </td>
                                                <td class="text-end font-monospace text-success fw-bold">
                                                    <?= ($pt['credit_amount'] > 0) ? '+₹' . number_format($pt['credit_amount'], 2) : '—' ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <!-- TAB 3: ACCOUNT HEAD CATEGORY SUMMARY -->
            <?php elseif ($activeTab === 'heads'): ?>
                <div class="row g-3">
                    <div class="col-12">
                        <h6 class="font-heading fw-bold text-navy mb-3">Company Revenue & Expense Heads Breakdown</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-top border-4 border-success">
                            <h6 class="font-heading fw-bold text-success mb-3">
                                <i class="bi bi-box-arrow-in-down-left me-1"></i> Inflow & Revenue Heads (Receipts)
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <thead class="table-light text-secondary small">
                                        <tr>
                                            <th>Account Head</th>
                                            <th class="text-center">Txns</th>
                                            <th class="text-end">Total Inflow (₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $totalInflowHead = 0;
                                        foreach ($headSummary as $hs): 
                                            if ($hs['total_credit'] > 0):
                                                $totalInflowHead += (float)$hs['total_credit'];
                                        ?>
                                            <tr>
                                                <td class="fw-semibold text-dark"><?= htmlspecialchars($hs['account_head']) ?></td>
                                                <td class="text-center"><span class="badge bg-light text-dark border"><?= $hs['txn_count'] ?></span></td>
                                                <td class="text-end font-monospace text-success fw-bold">₹<?= number_format($hs['total_credit'], 2) ?></td>
                                            </tr>
                                        <?php 
                                            endif;
                                        endforeach; 
                                        ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th class="fw-bold">Total Inflows</th>
                                            <th></th>
                                            <th class="text-end font-monospace text-success fw-bold fs-6">₹<?= number_format($totalInflowHead, 2) ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-top border-4 border-danger">
                            <h6 class="font-heading fw-bold text-danger mb-3">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Outflow & Expense Heads (Payments)
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <thead class="table-light text-secondary small">
                                        <tr>
                                            <th>Account Head</th>
                                            <th class="text-center">Txns</th>
                                            <th class="text-end">Total Outflow (₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $totalOutflowHead = 0;
                                        foreach ($headSummary as $hs): 
                                            if ($hs['total_debit'] > 0):
                                                $totalOutflowHead += (float)$hs['total_debit'];
                                        ?>
                                            <tr>
                                                <td class="fw-semibold text-dark"><?= htmlspecialchars($hs['account_head']) ?></td>
                                                <td class="text-center"><span class="badge bg-light text-dark border"><?= $hs['txn_count'] ?></span></td>
                                                <td class="text-end font-monospace text-danger fw-semibold">₹<?= number_format($hs['total_debit'], 2) ?></td>
                                            </tr>
                                        <?php 
                                            endif;
                                        endforeach; 
                                        ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th class="fw-bold">Total Outflows</th>
                                            <th></th>
                                            <th class="text-end font-monospace text-danger fw-bold fs-6">₹<?= number_format($totalOutflowHead, 2) ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- MODAL: RECORD NEW FINANCIAL LEDGER TRANSACTION -->
<div class="modal fade" id="modalNewLedgerEntry" tabindex="-1" aria-labelledby="modalNewLedgerEntryLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="<?= url('/admin/ledger/create') ?>">
                <?= csrf_field() ?>
                
                <div class="modal-header bg-navy text-white">
                    <h5 class="modal-title font-heading fw-bold" id="modalNewLedgerEntryLabel">
                        <i class="bi bi-journal-plus text-warning me-2"></i> Record Financial Voucher / Ledger Entry
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="row g-3">
                        
                        <!-- Entry Type Radio Buttons -->
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-secondary">Transaction Direction *</label>
                            <div class="d-flex gap-3">
                                <div class="form-check form-check-inline border rounded p-2 px-3 flex-fill bg-success-subtle border-success">
                                    <input class="form-check-input" type="radio" name="entry_type" id="typeReceipt" value="RECEIPT" checked onchange="updateAccountHeads('RECEIPT')">
                                    <label class="form-check-label fw-bold text-success" for="typeReceipt">
                                        <i class="bi bi-arrow-down-left me-1"></i> RECEIPT (Money Inflow / Revenue)
                                    </label>
                                </div>
                                <div class="form-check form-check-inline border rounded p-2 px-3 flex-fill bg-danger-subtle border-danger">
                                    <input class="form-check-input" type="radio" name="entry_type" id="typePayment" value="PAYMENT" onchange="updateAccountHeads('PAYMENT')">
                                    <label class="form-check-label fw-bold text-danger" for="typePayment">
                                        <i class="bi bi-arrow-up-right me-1"></i> PAYMENT (Money Outflow / Expense)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Date & Account Head -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Transaction Date *</label>
                            <input type="date" name="entry_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Account Head / Category *</label>
                            <select name="account_head" id="accountHeadSelect" class="form-select" required>
                                <optgroup label="Revenue & Inflows" id="inflowGroup">
                                    <option value="Advisor Joining / Induction Fee">Advisor Joining / Induction Fee (₹2,700)</option>
                                    <option value="Customer Solar Project Payment">Customer Solar Project Payment</option>
                                    <option value="Government DBT / Subsidy Receipt">Government DBT / Subsidy Receipt</option>
                                    <option value="Capital / Equity Reserve">Capital / Equity Reserve</option>
                                    <option value="Miscellaneous Receipt">Miscellaneous Receipt</option>
                                </optgroup>
                                <optgroup label="Expenses & Outflows" id="outflowGroup">
                                    <option value="Commission Payout">Advisor Commission Payout</option>
                                    <option value="Solar Hardware Procurement">Solar Hardware Procurement</option>
                                    <option value="Marketing & Promotional Kits">Marketing & Promotional Kits (Canopy, Bags)</option>
                                    <option value="Logistics & Courier Delivery">Logistics & Courier Delivery</option>
                                    <option value="Office Rent & Admin Overhead">Office Rent & Admin Overhead</option>
                                    <option value="Statutory TDS Remittance">Statutory TDS Remittance (5%)</option>
                                    <option value="Miscellaneous Expense">Miscellaneous Expense</option>
                                </optgroup>
                            </select>
                        </div>

                        <!-- Party Type & Preset Selector -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Party Type *</label>
                            <select name="party_type" id="partyTypeSelect" class="form-select" onchange="onPartyTypeChange()" required>
                                <option value="ADVISOR">Advisor</option>
                                <option value="CUSTOMER">Customer</option>
                                <option value="VENDOR">Vendor / Logistics / Supplier</option>
                                <option value="INTERNAL">Company Internal Treasury</option>
                                <option value="BANK">Bank / Financial Institution</option>
                                <option value="OTHER">Other Party</option>
                            </select>
                        </div>

                        <!-- Quick Select from Registered Advisors/Customers -->
                        <div class="col-md-8" id="quickSelectAdvisorBox">
                            <label class="form-label fw-semibold small">Quick Pick Registered Advisor</label>
                            <select class="form-select" onchange="fillPartyFromAdvisor(this)">
                                <option value="">-- Or type manually below --</option>
                                <?php foreach ($advisors as $adv): ?>
                                    <option value="<?= htmlspecialchars($adv['advisor_code']) ?>" 
                                            data-name="<?= htmlspecialchars($adv['first_name'] . ' ' . $adv['last_name']) ?>"
                                            data-id="<?= $adv['id'] ?>">
                                        <?= htmlspecialchars($adv['advisor_code']) ?> — <?= htmlspecialchars($adv['first_name'] . ' ' . $adv['last_name']) ?> (<?= htmlspecialchars($adv['district']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Party Name & Code/Identifier -->
                        <div class="col-md-7">
                            <label class="form-label fw-semibold small">Party Full Name *</label>
                            <input type="text" name="party_name" id="partyNameInput" class="form-control" placeholder="e.g. Ramesh Chandra Sahoo / DTDC Express" required>
                            <input type="hidden" name="party_id" id="partyIdInput" value="">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold small">Party Identifier / Code</label>
                            <input type="text" name="party_identifier" id="partyIdentifierInput" class="form-control font-monospace" placeholder="e.g. ADV-OD-2026-002">
                        </div>

                        <!-- Payment Mode & Reference/UTR -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Payment Mode *</label>
                            <select name="payment_mode" class="form-select" required>
                                <option value="UPI">UPI (PhonePe, GPay, Paytm)</option>
                                <option value="NEFT">NEFT Bank Transfer</option>
                                <option value="IMPS">IMPS Instant Transfer</option>
                                <option value="RTGS">RTGS High-Value</option>
                                <option value="CASH">Cash in Hand / Office Treasury</option>
                                <option value="CHEQUE">Cheque / Demand Draft</option>
                                <option value="BANK_TRANSFER">Direct Bank Deposit</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Reference / UTR / Cheque #</label>
                            <input type="text" name="reference_no" class="form-control font-monospace" placeholder="e.g. UPI/123456789012">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Amount (₹) *</label>
                            <div class="input-group">
                                <span class="input-group-text fw-bold">₹</span>
                                <input type="number" step="0.01" name="amount" class="form-control font-monospace fw-bold" placeholder="0.00" required>
                            </div>
                        </div>

                        <!-- Narration / Detailed Notes -->
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Narration / Remarks</label>
                            <textarea name="narration" class="form-control" rows="2" placeholder="Provide detailed transaction narration, purpose, invoice number, or breakdown..."></textarea>
                        </div>

                    </div>
                </div>
                
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-solar fw-bold px-4">
                        <i class="bi bi-check2-circle me-1"></i> Post to Ledger Book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function fillPartyFromAdvisor(selectElem) {
    const selected = selectElem.options[selectElem.selectedIndex];
    if (selected && selected.value) {
        document.getElementById('partyNameInput').value = selected.getAttribute('data-name') || '';
        document.getElementById('partyIdentifierInput').value = selected.value;
        document.getElementById('partyIdInput').value = selected.getAttribute('data-id') || '';
        document.getElementById('partyTypeSelect').value = 'ADVISOR';
    }
}

function onPartyTypeChange() {
    const pType = document.getElementById('partyTypeSelect').value;
    const advisorBox = document.getElementById('quickSelectAdvisorBox');
    if (advisorBox) {
        advisorBox.style.display = (pType === 'ADVISOR') ? 'block' : 'none';
    }
}
</script>
