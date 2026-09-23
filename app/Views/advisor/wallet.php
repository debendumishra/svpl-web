<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Advisor Wallet & Payouts View (Solar Luminary Design System)
 */
$title = "My Wallet & Payouts — SVPL Advisor";
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">My Commission Wallet & Instant Bank Payouts</h3>
            <span class="badge bg-success text-white fw-bold px-2 py-1" style="font-size: 0.72rem;">Direct Bank NEFT / IMPS</span>
        </div>
        <p class="text-secondary small mb-0">Direct commissions, 9-level team overrides, TDS compliance, and withdrawal transaction logs</p>
    </div>
    <button type="button" class="btn btn-svpl-green fw-bold px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#requestWithdrawalModal">
        <i class="bi bi-bank me-1"></i> Request Bank Withdrawal
    </button>
</div>

<?php if (!empty($successMsg)): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($successMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($errorMsg)): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($errorMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- WALLET SUMMARY CARDS -->
<div class="row g-3 mb-4 animate-fade-in stagger-1">
    <div class="col-md-4">
        <div class="stat-card-modern shadow-sm text-center h-100">
            <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Available Wallet Balance</span>
            <h2 class="font-heading fw-bold text-success my-2">₹<?= number_format((float)($wallet['balance'] ?? 0), 2) ?></h2>
            <button class="btn btn-svpl-green btn-sm mt-1 px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#requestWithdrawalModal">
                <i class="bi bi-bank me-1"></i> Request Payout
            </button>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-modern shadow-sm text-center h-100">
            <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Lifetime Commissions Earned</span>
            <h2 class="font-heading fw-bold text-navy my-2">₹<?= number_format((float)($wallet['total_earned'] ?? 0), 2) ?></h2>
            <span class="text-secondary small">Direct Leads + 9-Level Overrides</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-modern shadow-sm text-center h-100">
            <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Statutory TDS Deducted (5%)</span>
            <h2 class="font-heading fw-bold text-danger my-2">₹<?= number_format((float)(($wallet['total_earned'] ?? 0) * 0.05), 2) ?></h2>
            <span class="text-secondary small">Deposited with Income Tax Dept</span>
        </div>
    </div>
</div>

<!-- BANK WITHDRAWAL REQUESTS HISTORY -->
<div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4 animate-fade-in stagger-2">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h5 class="font-heading fw-bold m-0 text-navy"><i class="bi bi-bank me-2 text-success"></i>Bank Payout Requests & Status</h5>
            <p class="text-muted small mb-0">Record of all withdrawal requests, statutory 5% TDS deductions, and bank disbursals</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border me-1">Total: <?= count($withdrawals ?? []) ?></span>
            <a href="<?= url('/advisor/wallet/export-payouts') ?>" class="btn btn-outline-success btn-sm fw-bold">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <a href="<?= url('/advisor/wallet/print-payouts') ?>" target="_blank" class="btn btn-outline-danger btn-sm fw-bold">
                <i class="bi bi-file-earmark-pdf me-1"></i> Print / PDF
            </a>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle small mb-0">
            <thead class="table-light">
                <tr class="text-secondary text-uppercase">
                    <th>Req #</th>
                    <th>Date</th>
                    <th>Requested Amt</th>
                    <th>5% TDS</th>
                    <th>Net Payout</th>
                    <th>Bank Account</th>
                    <th>Status</th>
                    <th>Bank UTR / Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($withdrawals)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-secondary">No withdrawal requests submitted yet. Click <strong>Request Bank Withdrawal</strong> above to payout your wallet balance.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($withdrawals as $w): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($w['request_code']) ?></span></td>
                            <td class="text-secondary"><?= date('d M Y, h:i A', strtotime($w['requested_at'])) ?></td>
                            <td class="fw-bold text-navy">₹<?= number_format((float)$w['amount'], 2) ?></td>
                            <td class="text-danger">- ₹<?= number_format((float)$w['tds_amount'], 2) ?></td>
                            <td class="text-success fw-bold fs-6">₹<?= number_format((float)$w['net_payable'], 2) ?></td>
                            <td>
                                <div class="small fw-semibold"><?= htmlspecialchars($w['bank_name'] ?? 'Bank') ?></div>
                                <code class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($w['account_number'] ?? 'N/A') ?> (IFSC: <?= htmlspecialchars($w['ifsc_code'] ?? 'N/A') ?>)</code>
                            </td>
                            <td>
                                <?php if ($w['status'] === 'PAID' || $w['status'] === 'APPROVED'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle-fill me-1"></i> PAID / PROCESSED</span>
                                <?php elseif ($w['status'] === 'REJECTED'): ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-x-circle-fill me-1"></i> REJECTED</span>
                                <?php else: ?>
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle"><i class="bi bi-hourglass-split me-1"></i> PENDING APPROVAL</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($w['utr_number'])): ?>
                                    <div class="small fw-bold text-success"><i class="bi bi-receipt me-1"></i> UTR: <?= htmlspecialchars($w['utr_number']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($w['admin_remarks'])): ?>
                                    <small class="text-muted d-block"><?= htmlspecialchars($w['admin_remarks']) ?></small>
                                <?php endif; ?>
                                <?php if (empty($w['utr_number']) && empty($w['admin_remarks'])): ?>
                                    <span class="text-muted small">Under Review</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- TRANSACTION HISTORY -->
<!-- COMPREHENSIVE WALLET LEDGER TRANSACTIONS -->
<div class="card card-svpl p-4 bg-white border-0 shadow-sm animate-fade-in stagger-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h5 class="font-heading fw-bold m-0 text-navy"><i class="bi bi-journal-text me-2 text-primary"></i>Immutable Wallet Ledger Statement</h5>
            <p class="text-muted small mb-0">Double-entry audit ledger tracking every credit, commission, bonus and payout</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border font-monospace"><?= count($transactions ?? []) ?> Entries</span>
            <a href="<?= url('/advisor/wallet/export-ledger') ?>" class="btn btn-outline-success btn-sm fw-bold">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <a href="<?= url('/advisor/wallet/print-ledger') ?>" target="_blank" class="btn btn-outline-danger btn-sm fw-bold">
                <i class="bi bi-file-earmark-pdf me-1"></i> Print / PDF
            </a>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle small mb-0">
            <thead class="table-light text-uppercase">
                <tr class="text-secondary">
                    <th>Date</th>
                    <th>Txn Ref</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th class="text-end">Credit (+)</th>
                    <th class="text-end">Debit (-)</th>
                    <th class="text-end">Balance After</th>
                    <th class="text-center">Audit</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-secondary">No ledger transactions recorded yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td class="text-secondary font-monospace"><?= date('d-M-Y H:i', strtotime($t['created_at'])) ?></td>
                            <td><span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($t['transaction_ref'] ?? ('TXN-' . $t['id'])) ?></span></td>
                            <td><span class="badge bg-primary-subtle text-primary fw-semibold"><?= str_replace('_', ' ', $t['transaction_type']) ?></span></td>
                            <td>
                                <div><?= htmlspecialchars($t['description']) ?></div>
                                <?php if (!empty($t['product_name'])): ?>
                                    <span class="text-muted small"><?= htmlspecialchars($t['product_name']) ?> <?= !empty($t['level']) ? '(L' . $t['level'] . ')' : '' ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-bold text-success fs-6">
                                <?= (float)$t['credit_amount'] > 0 ? '+₹' . number_format((float)$t['credit_amount'], 2) : '—' ?>
                            </td>
                            <td class="text-end fw-bold text-danger fs-6">
                                <?= (float)$t['debit_amount'] > 0 ? '-₹' . number_format((float)$t['debit_amount'], 2) : '—' ?>
                            </td>
                            <td class="text-end fw-bold text-dark font-monospace">
                                ₹<?= number_format((float)$t['balance_after'], 2) ?>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($t['rule_snapshot_json'])): ?>
                                    <button type="button" class="btn btn-outline-info btn-sm view-adv-snapshot" 
                                            data-snapshot="<?= htmlspecialchars($t['rule_snapshot_json']) ?>"
                                            title="View Commission Breakdown">
                                        <i class="bi bi-file-text"></i>
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- REQUEST WITHDRAWAL MODAL -->
<div class="modal fade" id="requestWithdrawalModal" tabindex="-1" aria-labelledby="requestWithdrawalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?= url('/advisor/wallet/request-withdrawal') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header bg-navy text-white py-3">
                    <h5 class="modal-title font-heading fw-bold" id="requestWithdrawalModalLabel">
                        <i class="bi bi-bank text-warning me-2"></i> Request Bank Payout Withdrawal
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small fw-bold">Available Wallet Balance:</span>
                            <strong class="text-success fs-5">₹<?= number_format((float)($wallet['balance'] ?? 0), 2) ?></strong>
                        </div>
                    </div>

                    <?php if (empty($advisor['account_number']) || empty($advisor['ifsc_code'])): ?>
                        <div class="alert alert-warning small mb-3">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Bank details missing!</strong> Please update your Bank Name, Account Number, and IFSC Code in your profile before submitting a payout request.
                        </div>
                    <?php else: ?>
                        <div class="p-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3 mb-3 small">
                            <div class="fw-bold text-navy mb-1"><i class="bi bi-credit-card-2-front me-1"></i> Destination Payout Account:</div>
                            <div><strong>Bank:</strong> <?= htmlspecialchars($advisor['bank_name'] ?? 'N/A') ?></div>
                            <div><strong>Account Holder:</strong> <?= htmlspecialchars($advisor['account_holder'] ?? ($advisor['first_name'] . ' ' . $advisor['last_name'])) ?></div>
                            <div><strong>Account No:</strong> <code><?= htmlspecialchars($advisor['account_number'] ?? 'N/A') ?></code></div>
                            <div><strong>IFSC Code:</strong> <code><?= htmlspecialchars($advisor['ifsc_code'] ?? 'N/A') ?></code></div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Withdrawal Amount (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text fw-bold">₹</span>
                            <input type="number" step="0.01" min="100" max="<?= (float)($wallet['balance'] ?? 0) ?>" name="amount" id="withdrawalAmountInput" class="form-control" placeholder="Enter amount to withdraw" required>
                        </div>
                        <div class="form-text small">Minimum withdrawal: ₹100. Maximum: ₹<?= number_format((float)($wallet['balance'] ?? 0), 2) ?></div>
                    </div>

                    <!-- Payout Breakdown Card -->
                    <div class="p-3 bg-light rounded-3 border mb-3" id="payoutCalcBox">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Requested Amount:</span>
                            <span class="fw-semibold text-navy" id="calcRequested">₹0.00</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Less 5% Statutory TDS:</span>
                            <span class="fw-semibold text-danger" id="calcTds">- ₹0.00</span>
                        </div>
                        <hr class="my-1">
                        <div class="d-flex justify-content-between fw-bold">
                            <span class="text-dark">Net Bank Credit Amount:</span>
                            <span class="text-success fs-6" id="calcNet">₹0.00</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-light btn-sm border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-green btn-sm fw-bold px-4" <?= (empty($advisor['account_number']) || empty($advisor['ifsc_code']) || ($wallet['balance'] ?? 0) < 100) ? 'disabled' : '' ?>>
                        <i class="bi bi-send-check-fill me-1"></i> Submit Withdrawal Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('withdrawalAmountInput');
    const calcRequested = document.getElementById('calcRequested');
    const calcTds = document.getElementById('calcTds');
    const calcNet = document.getElementById('calcNet');

    if (input) {
        input.addEventListener('input', function() {
            let val = parseFloat(this.value) || 0;
            if (val < 0) val = 0;

            const tds = Math.round(val * 0.05 * 100) / 100;
            const net = Math.max(0, val - tds);

            calcRequested.textContent = '₹' + val.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            calcTds.textContent = '- ₹' + tds.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            calcNet.textContent = '₹' + net.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        });
    }

    // Snapshot Modal Trigger
    document.querySelectorAll('.view-adv-snapshot').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const raw = this.getAttribute('data-snapshot');
            try {
                const parsed = JSON.parse(raw);
                document.getElementById('advSnapshotJson').textContent = JSON.stringify(parsed, null, 4);
            } catch(e) {
                document.getElementById('advSnapshotJson').textContent = raw;
            }
            const modal = new bootstrap.Modal(document.getElementById('advSnapshotModal'));
            modal.show();
        });
    });
});
</script>

<!-- Snapshot Modal for Advisor -->
<div class="modal fade" id="advSnapshotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white">
                <h5 class="modal-title h6 mb-0"><i class="bi bi-shield-check me-2"></i>Commission Calculation Snapshot</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <pre class="bg-light p-3 rounded small font-monospace border" id="advSnapshotJson" style="max-height: 350px; overflow-y: auto;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
