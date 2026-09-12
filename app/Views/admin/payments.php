<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Payment Verification & Wallet Ledgers (Solar Luminary Design System)
 */
$title = "Payment Verification & Wallet Ledgers — SVPL Admin";
$pendingCount = count($pendingPayments ?? []);
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">Payment Verification & Accounts Desk</h3>
            <?php if ($pendingCount > 0): ?>
                <span class="badge bg-danger animate-pulse px-2 py-1"><?= $pendingCount ?> Pending Approval</span>
            <?php else: ?>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check2-circle"></i> All Clear</span>
            <?php endif; ?>
        </div>
        <p class="text-secondary small mb-0">Review advisor joining fee UTRs, verified customer payments, and wallet audit ledgers</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/admin/export/csv?type=payments') ?>" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Payments CSV
        </a>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
        <div><?= htmlspecialchars($success) ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
        <div><?= htmlspecialchars($error) ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- NAVIGATION TABS -->
<ul class="nav nav-pills mb-4 gap-2" id="paymentTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-semibold d-flex align-items-center gap-2" id="tab-pending-btn" data-bs-toggle="pill" data-bs-target="#tab-pending" type="button" role="tab">
            <i class="bi bi-shield-exclamation text-warning"></i>
            Pending Advisor Onboarding Fees
            <?php if ($pendingCount > 0): ?>
                <span class="badge bg-danger text-white rounded-pill px-2"><?= $pendingCount ?></span>
            <?php endif; ?>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold d-flex align-items-center gap-2" id="tab-verified-btn" data-bs-toggle="pill" data-bs-target="#tab-verified" type="button" role="tab">
            <i class="bi bi-receipt-cutoff text-success"></i>
            Verified Payment Receipts
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold d-flex align-items-center gap-2" id="tab-wallet-btn" data-bs-toggle="pill" data-bs-target="#tab-wallet" type="button" role="tab">
            <i class="bi bi-wallet2 text-primary"></i>
            Advisor Wallet Transactions
        </button>
    </li>
</ul>

<div class="tab-content" id="paymentTabsContent">
    
    <!-- 1. PENDING ADVISOR ONBOARDING PAYMENTS -->
    <div class="tab-pane fade show active" id="tab-pending" role="tabpanel">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-heading fw-bold text-navy mb-0">
                    <i class="bi bi-clock-history text-warning me-2"></i> Advisor Fee Verification Queue
                </h5>
                <span class="text-secondary small">Confirming payment activates advisor login and enables digital ID card generation</span>
            </div>

            <?php if (empty($pendingPayments)): ?>
                <div class="text-center py-5 text-secondary">
                    <i class="bi bi-shield-check fs-1 text-success d-block mb-2"></i>
                    <h6 class="fw-bold text-navy">No Pending Advisor Payments</h6>
                    <p class="small text-muted mb-0">All registered solar advisor onboarding fees have been confirmed.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr class="text-secondary text-uppercase">
                                <th>Advisor Code & Date</th>
                                <th>Applicant Details</th>
                                <th>Location</th>
                                <th>Amount</th>
                                <th>Payment Mode</th>
                                <th>Submitted UTR / Txn Ref</th>
                                <th class="text-end">Verification Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingPayments as $p): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace fw-bold"><?= htmlspecialchars($p['advisor_code']) ?></span>
                                        <div class="text-muted" style="font-size: 0.72rem;"><?= date('d M Y, h:i A', strtotime($p['created_at'])) ?></div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-navy"><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?></div>
                                        <div class="text-secondary" style="font-size: 0.78rem;">
                                            <i class="bi bi-telephone-fill text-success me-1"></i><?= htmlspecialchars($p['mobile']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-navy fw-semibold"><?= htmlspecialchars($p['district']) ?></div>
                                        <span class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($p['block']) ?></span>
                                    </td>
                                    <td>
                                        <strong class="text-success fs-6">₹<?= number_format((float)$p['amount'], 2) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><?= htmlspecialchars($p['payment_method']) ?></span>
                                        <div class="text-muted" style="font-size: 0.72rem;">Date: <?= htmlspecialchars($p['payment_date']) ?></div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-warning-subtle text-dark border border-warning font-monospace fs-6 px-2 py-1">
                                                <?= htmlspecialchars($p['transaction_ref'] ?? 'N/A') ?>
                                            </span>
                                            <button type="button" class="btn btn-sm btn-light border py-0 px-2" title="Copy UTR" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($p['transaction_ref'] ?? '') ?>'); alert('UTR copied to clipboard!');">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <!-- CONFIRM PAYMENT FORM -->
                                            <form method="POST" action="<?= url('/admin/payments/confirm') ?>" onsubmit="return confirm('Confirm receipt of ₹<?= number_format((float)$p['amount'], 2) ?> for <?= htmlspecialchars($p['first_name']) ?> (<?= htmlspecialchars($p['advisor_code']) ?>)? This will activate their advisor account immediately.');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="payment_id" value="<?= $p['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-success fw-bold px-3 shadow-sm">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Confirm & Activate
                                                </button>
                                            </form>

                                            <!-- REJECT FORM -->
                                            <form method="POST" action="<?= url('/admin/payments/reject') ?>" onsubmit="return confirm('Reject onboarding payment for <?= htmlspecialchars($p['advisor_code']) ?>?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="payment_id" value="<?= $p['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-x-circle"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- 2. VERIFIED PAYMENT RECEIPTS -->
    <div class="tab-pane fade" id="tab-verified" role="tabpanel">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm">
            <h5 class="font-heading fw-bold text-navy mb-3">Verified Payment Receipts</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr class="text-secondary text-uppercase">
                            <th>Receipt / Code</th>
                            <th>Entity Type & Name</th>
                            <th>Purpose</th>
                            <th>Payment Mode</th>
                            <th>UTR / Ref</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentPayments)): ?>
                            <tr><td colspan="9" class="text-center py-4 text-secondary">No payment records found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($recentPayments as $rp): ?>
                                <tr>
                                    <td>
                                        <div class="font-monospace fw-bold text-navy"><?= htmlspecialchars($rp['receipt_number']) ?></div>
                                        <span class="text-muted" style="font-size: 0.72rem;"><?= htmlspecialchars($rp['payment_code']) ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-navy"><?= htmlspecialchars($rp['entity_name']) ?></div>
                                        <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.7rem;"><?= htmlspecialchars($rp['entity_code']) ?></span>
                                        <span class="text-muted small ms-1">(<?= htmlspecialchars($rp['entity_type']) ?>)</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary border"><?= htmlspecialchars($rp['purpose']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($rp['payment_method']) ?></td>
                                    <td><span class="font-monospace text-primary"><?= htmlspecialchars($rp['transaction_ref'] ?? '—') ?></span></td>
                                    <td><strong class="text-success">₹<?= number_format((float)$rp['amount'], 2) ?></strong></td>
                                    <td>
                                        <span class="badge <?= $rp['status'] === 'SUCCESS' ? 'bg-success text-white' : ($rp['status'] === 'PENDING' ? 'bg-warning text-dark' : 'bg-danger text-white') ?>">
                                            <?= htmlspecialchars($rp['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($rp['payment_date']) ?></td>
                                    <td class="text-end">
                                        <a href="<?= url('/print/receipt/' . $rp['id']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary py-1 px-2">
                                            <i class="bi bi-printer"></i>
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

    <!-- 3. WALLET LEDGER AUDIT -->
    <div class="tab-pane fade" id="tab-wallet" role="tabpanel">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm">
            <h5 class="font-heading fw-bold text-navy mb-3">Advisor Wallet Transactions</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle small mb-0">
                    <thead class="table-light">
                        <tr class="text-secondary text-uppercase">
                            <th>Txn #</th>
                            <th>User Name & Contact</th>
                            <th>Type</th>
                            <th>Amount Credited</th>
                            <th>Balance After</th>
                            <th>Description</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transactions)): ?>
                            <tr><td colspan="7" class="text-center py-4 text-secondary">No wallet transactions recorded.</td></tr>
                        <?php else: ?>
                            <?php foreach ($transactions as $t): ?>
                                <tr>
                                    <td>#<?= $t['id'] ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($t['full_name']) ?></strong><br>
                                        <span class="text-muted small"><?= htmlspecialchars($t['mobile']) ?> (<?= $t['role'] ?>)</span>
                                    </td>
                                    <td><span class="badge bg-success-subtle text-success border border-success-subtle"><?= htmlspecialchars($t['txn_type']) ?></span></td>
                                    <td class="text-success fw-bold">+ ₹<?= number_format((float)$t['amount'], 2) ?></td>
                                    <td class="fw-semibold">₹<?= number_format((float)$t['balance_after'], 2) ?></td>
                                    <td><?= htmlspecialchars($t['description']) ?></td>
                                    <td class="text-muted"><?= $t['created_at'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
