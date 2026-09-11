<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Admin & Manager Advisor Bank Withdrawals & Payouts Management View
 */
$title = "Advisor Bank Withdrawals & Payouts — SVPL Admin";
$prefix = $prefix ?? '/admin';
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">Advisor Bank Withdrawals & Payout Management</h3>
            <span class="badge bg-success text-white fw-bold px-2 py-1" style="font-size: 0.72rem;">5% TDS Statutory Compliance</span>
        </div>
        <p class="text-secondary small mb-0">Process NEFT / IMPS bank payouts to advisors, track UTR numbers, and approve/reject withdrawal requests.</p>
    </div>
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

<?php
$pendingCount = 0; $pendingSum = 0;
$paidCount = 0; $paidSum = 0;
$rejectedCount = 0;

foreach ($withdrawals as $w) {
    if ($w['status'] === 'PENDING') {
        $pendingCount++;
        $pendingSum += (float)$w['net_payable'];
    } elseif ($w['status'] === 'PAID' || $w['status'] === 'APPROVED') {
        $paidCount++;
        $paidSum += (float)$w['net_payable'];
    } elseif ($w['status'] === 'REJECTED') {
        $rejectedCount++;
    }
}
?>

<!-- SUMMARY STAT CARDS -->
<div class="row g-3 mb-4 animate-fade-in stagger-1">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-warning h-100">
            <div class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Pending Payout Requests</div>
            <h3 class="font-heading fw-bold text-dark my-1"><?= $pendingCount ?> <small class="fs-6 text-muted">requests</small></h3>
            <span class="text-warning fw-bold small">₹<?= number_format($pendingSum, 2) ?> Net Payout</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-success h-100">
            <div class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Total Paid Out</div>
            <h3 class="font-heading fw-bold text-success my-1">₹<?= number_format($paidSum, 2) ?></h3>
            <span class="text-muted small"><?= $paidCount ?> Bank Transfers Completed</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-secondary h-100">
            <div class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Total Requests Submitted</div>
            <h3 class="font-heading fw-bold text-navy my-1"><?= count($withdrawals) ?></h3>
            <span class="text-muted small">All Time Withdrawal Records</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-danger h-100">
            <div class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">Rejected Requests</div>
            <h3 class="font-heading fw-bold text-danger my-1"><?= $rejectedCount ?></h3>
            <span class="text-muted small">Refunded to Wallet Balance</span>
        </div>
    </div>
</div>

<!-- FILTER TABS & TABLE -->
<div class="card card-svpl border-0 shadow-sm bg-white p-4 animate-fade-in stagger-2 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h5 class="font-heading fw-bold m-0 text-navy">Advisor Bank Withdrawal Log</h5>

        <!-- Filter Pills -->
        <ul class="nav nav-pills small" style="font-size: 0.82rem;">
            <li class="nav-item">
                <a class="nav-link <?= ($statusFilter === 'ALL') ? 'active bg-navy' : 'text-dark' ?>" href="<?= url($prefix . '/withdrawals?status=ALL') ?>">All Requests</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($statusFilter === 'PENDING') ? 'active bg-warning text-dark fw-bold' : 'text-dark' ?>" href="<?= url($prefix . '/withdrawals?status=PENDING') ?>">
                    Pending Approval <span class="badge bg-danger ms-1"><?= $pendingCount ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($statusFilter === 'PAID') ? 'active bg-success' : 'text-dark' ?>" href="<?= url($prefix . '/withdrawals?status=PAID') ?>">Paid & Settled</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($statusFilter === 'REJECTED') ? 'active bg-danger' : 'text-dark' ?>" href="<?= url($prefix . '/withdrawals?status=REJECTED') ?>">Rejected</a>
            </li>
        </ul>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle small mb-0">
            <thead class="table-light">
                <tr class="text-secondary text-uppercase" style="font-size: 0.75rem;">
                    <th>Req Code / Date</th>
                    <th>Advisor</th>
                    <th>Requested Amt</th>
                    <th>5% TDS</th>
                    <th>Net Payable</th>
                    <th>Destination Bank Account</th>
                    <th>Status</th>
                    <th>Bank UTR / Remarks</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($withdrawals)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-4 text-secondary">No withdrawal requests found matching filter.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($withdrawals as $w): ?>
                        <tr>
                            <td>
                                <strong class="text-navy font-monospace d-block"><?= htmlspecialchars($w['request_code']) ?></strong>
                                <small class="text-muted"><?= date('d M Y, h:i A', strtotime($w['requested_at'])) ?></small>
                            </td>
                            <td>
                                <div class="fw-bold text-navy"><?= htmlspecialchars($w['advisor_name']) ?></div>
                                <div class="text-muted small">Code: <span class="badge bg-light text-dark border"><?= htmlspecialchars($w['advisor_code'] ?? 'ADV') ?></span> | Mobile: <?= htmlspecialchars($w['advisor_mobile']) ?></div>
                            </td>
                            <td class="fw-bold text-dark">₹<?= number_format((float)$w['amount'], 2) ?></td>
                            <td class="text-danger">- ₹<?= number_format((float)$w['tds_amount'], 2) ?></td>
                            <td class="text-success fw-bold fs-6">₹<?= number_format((float)$w['net_payable'], 2) ?></td>
                            <td>
                                <div class="fw-semibold text-navy"><?= htmlspecialchars($w['bank_name'] ?? 'N/A') ?> <?= !empty($w['bank_branch']) ? '(' . htmlspecialchars($w['bank_branch']) . ')' : '' ?></div>
                                <div><strong>A/C:</strong> <code><?= htmlspecialchars($w['account_number'] ?? 'N/A') ?></code></div>
                                <div class="text-muted small">IFSC: <code><?= htmlspecialchars($w['ifsc_code'] ?? 'N/A') ?></code> | Name: <?= htmlspecialchars($w['account_holder'] ?? $w['advisor_name']) ?></div>
                            </td>
                            <td>
                                <?php if ($w['status'] === 'PAID' || $w['status'] === 'APPROVED'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle-fill me-1"></i> PAID</span>
                                <?php elseif ($w['status'] === 'REJECTED'): ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-x-circle-fill me-1"></i> REJECTED</span>
                                <?php else: ?>
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle"><i class="bi bi-hourglass-split me-1"></i> PENDING</span>
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
                                    <span class="text-muted small">Awaiting Payout</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-nowrap">
                                <?php if ($w['status'] === 'PENDING'): ?>
                                    <button type="button" class="btn btn-success btn-sm me-1 fw-bold" data-bs-toggle="modal" data-bs-target="#approveModal-<?= $w['id'] ?>">
                                        <i class="bi bi-check-lg me-1"></i> Approve & Pay
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#rejectModal-<?= $w['id'] ?>">
                                        <i class="bi bi-x-lg me-1"></i> Reject
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted small"><i class="bi bi-lock-fill"></i> Settled</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL DIALOGS RENDERED OUTSIDE THE TABLE FOR CLEAN BOOTSTRAP STACKING -->
<?php if (!empty($withdrawals)): ?>
    <?php foreach ($withdrawals as $w): ?>
        <?php if ($w['status'] === 'PENDING'): ?>
            <!-- APPROVE & PAY MODAL -->
            <div class="modal fade" id="approveModal-<?= $w['id'] ?>" tabindex="-1" aria-labelledby="approveModalLabel-<?= $w['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <form action="<?= url($prefix . '/withdrawals/approve/' . $w['id']) ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="modal-header bg-success text-white py-3">
                                <h5 class="modal-title font-heading fw-bold" id="approveModalLabel-<?= $w['id'] ?>">
                                    <i class="bi bi-check-circle-fill me-2"></i> Approve & Record Bank Payout
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4 text-start">
                                <div class="p-3 bg-light rounded-3 border mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-muted">Advisor Name:</span>
                                        <strong class="text-navy"><?= htmlspecialchars($w['advisor_name']) ?> (<?= htmlspecialchars($w['advisor_code']) ?>)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-muted">Requested Amount:</span>
                                        <span class="fw-semibold">₹<?= number_format((float)$w['amount'], 2) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-muted">Less 5% TDS:</span>
                                        <span class="text-danger">- ₹<?= number_format((float)$w['tds_amount'], 2) ?></span>
                                    </div>
                                    <hr class="my-1">
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Net Payout Amount:</span>
                                        <span class="text-success fs-6">₹<?= number_format((float)$w['net_payable'], 2) ?></span>
                                    </div>
                                </div>

                                <div class="p-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3 mb-3 small">
                                    <div class="fw-bold text-navy mb-1"><i class="bi bi-bank me-1"></i> Destination Bank Details:</div>
                                    <div><strong>Bank:</strong> <?= htmlspecialchars($w['bank_name'] ?? 'N/A') ?></div>
                                    <div><strong>Account Holder:</strong> <?= htmlspecialchars($w['account_holder'] ?? $w['advisor_name']) ?></div>
                                    <div><strong>Account No:</strong> <code><?= htmlspecialchars($w['account_number'] ?? 'N/A') ?></code></div>
                                    <div><strong>IFSC Code:</strong> <code><?= htmlspecialchars($w['ifsc_code'] ?? 'N/A') ?></code></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-navy">Bank UTR / IMPS / NEFT Reference No <span class="text-danger">*</span></label>
                                    <input type="text" name="utr_number" class="form-control" placeholder="e.g. UTR123456789098" required>
                                    <div class="form-text small">Enter the bank transaction reference ID generated after transferring funds.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-navy">Approval Remarks (Optional)</label>
                                    <input type="text" name="remarks" class="form-control" placeholder="e.g. NEFT transfer completed successfully">
                                </div>
                            </div>
                            <div class="modal-footer bg-light py-2">
                                <button type="button" class="btn btn-light btn-sm border" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success btn-sm fw-bold px-4">
                                    <i class="bi bi-check-lg me-1"></i> Confirm & Mark Paid
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- REJECT MODAL -->
            <div class="modal fade" id="rejectModal-<?= $w['id'] ?>" tabindex="-1" aria-labelledby="rejectModalLabel-<?= $w['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <form action="<?= url($prefix . '/withdrawals/reject/' . $w['id']) ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="modal-header bg-danger text-white py-3">
                                <h5 class="modal-title font-heading fw-bold" id="rejectModalLabel-<?= $w['id'] ?>">
                                    <i class="bi bi-x-circle-fill me-2"></i> Reject Withdrawal Request
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4 text-start">
                                <p class="small text-muted mb-3">Rejecting this request will immediately refund the requested amount <strong>(₹<?= number_format((float)$w['amount'], 2) ?>)</strong> back to the advisor's available wallet balance.</p>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-navy">Rejection Reason <span class="text-danger">*</span></label>
                                    <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Explain reason for rejection e.g. Incorrect bank account number or invalid IFSC code" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer bg-light py-2">
                                <button type="button" class="btn btn-light btn-sm border" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger btn-sm fw-bold px-4">
                                    <i class="bi bi-x-lg me-1"></i> Confirm Rejection
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
