<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Portal Dashboard View - Comprehensive Performance & Commission Command Center
 */
$title = $pageTitle ?? 'Advisor Command Center — Surya Vistaara';
?>

<div class="container-fluid py-4">
    <!-- Hero Welcome Banner -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #0369A1 100%);">
        <div class="card-body p-4 p-lg-5 position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1 mb-2 rounded-pill"><i class="bi bi-patch-check-fill me-1"></i>Certified Solar Advisor</span>
                    <h2 class="fw-bold mb-1">Welcome back, <?= htmlspecialchars($advisor['first_name'] . ' ' . $advisor['last_name']) ?>!</h2>
                    <p class="text-white-50 mb-3 small">
                        Advisor Code: <span class="text-warning font-monospace fw-bold"><?= htmlspecialchars($advisor['advisor_code']) ?></span> | 
                        Referral Link: <span class="text-info font-monospace">/register-advisor?ref=<?= htmlspecialchars($advisor['referral_code'] ?? $advisor['advisor_code']) ?></span>
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= url('/advisor/qr-code') ?>" class="btn btn-warning btn-sm text-dark fw-bold shadow-sm">
                            <i class="bi bi-qr-code me-1"></i>My Referral QR & Link
                        </a>
                        <a href="<?= url('/advisor/my-network') ?>" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-diagram-3 me-1"></i>My Network Tree
                        </a>
                        <a href="<?= url('/advisor/rewards') ?>" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-trophy me-1"></i>Lifetime Rewards
                        </a>
                        <?php if (!empty($joiningFeePaid)): ?>
                            <a href="<?= url('/print/id-card/' . $advisor['id']) ?>" target="_blank" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-person-vcard me-1"></i>Print ID Card
                            </a>
                            <a href="<?= url('/print/advisor-invoice/' . $advisor['id']) ?>" target="_blank" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-receipt-cutoff me-1"></i>GST Invoice
                            </a>
                            <a href="<?= url('/print/leaflet/' . $advisor['id']) ?>" target="_blank" class="btn btn-warning text-dark fw-bold btn-sm shadow-sm">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i>Personalized Brochure
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <div class="bg-white bg-opacity-10 p-3 rounded-3 text-start d-inline-block border border-light border-opacity-10">
                        <div class="text-white-50 small text-uppercase">Wallet Balance</div>
                        <div class="h2 fw-bold text-warning mb-0">₹<?= number_format((float)($wallet['balance'] ?? 0), 2) ?></div>
                        <div class="text-white-50 small mt-1">
                            <a href="<?= url('/advisor/wallet') ?>" class="text-info text-decoration-none"><i class="bi bi-arrow-right-circle me-1"></i>View Wallet & Ledger</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($paymentSuccess)): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($paymentSuccess) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($paymentError)): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($paymentError) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($joiningFeePaid)): ?>
        <!-- REGISTRATION FEE / ACTIVATION PROMPT CARD -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden border border-warning">
            <div class="card-header bg-warning-subtle text-dark py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark fw-bold px-2 py-1"><i class="bi bi-shield-lock-fill me-1"></i> ACCOUNT ACTIVATION REQUIRED</span>
                    <strong class="font-heading fs-6 text-navy">Advisor Registration Fee: ₹<?= number_format((float)$joiningFeeAmount) ?></strong>
                </div>
                <?php if (!empty($pendingJoiningPayment)): ?>
                    <span class="badge bg-primary text-white px-3 py-2"><i class="bi bi-hourglass-split me-1"></i> Payment Verification in Progress</span>
                <?php else: ?>
                    <span class="badge bg-dark text-warning px-3 py-2"><i class="bi bi-person-lock me-1"></i> Free Tier • Network Only</span>
                <?php endif; ?>
            </div>
            <div class="card-body p-4 bg-white">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <h5 class="fw-bold text-navy mb-2">
                            <?php if (!empty($pendingJoiningPayment)): ?>
                                <i class="bi bi-clock-history text-primary me-2"></i>Payment Details Submitted — Awaiting Admin Approval
                            <?php else: ?>
                                <i class="bi bi-unlock-fill text-warning me-2"></i>Unlock Customer Registrations & Direct Commissions
                            <?php endif; ?>
                        </h5>
                        <p class="text-secondary small mb-3">
                            You are currently registered on the <strong>Free Advisor Network Tier</strong>. You can freely share your referral link (<code class="fw-bold">/register-advisor?ref=<?= htmlspecialchars($advisor['referral_code']) ?></code>) to recruit advisors and grow your 9-level network.
                        </p>
                        
                        <?php if (!empty($pendingJoiningPayment)): ?>
                            <div class="p-3 bg-light rounded-3 border mb-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                                    <span class="text-muted small">Submitted Transaction Record:</span>
                                    <span class="badge bg-info-subtle text-info border">Status: PENDING ADMIN VERIFICATION</span>
                                </div>
                                <div class="row g-2 small">
                                    <div class="col-sm-4"><strong>UTR / Txn Ref:</strong> <span class="font-monospace text-primary fw-bold"><?= htmlspecialchars($pendingJoiningPayment['transaction_ref']) ?></span></div>
                                    <div class="col-sm-4"><strong>Amount:</strong> <span class="text-success fw-bold">₹<?= number_format((float)$pendingJoiningPayment['amount']) ?></span></div>
                                    <div class="col-sm-4"><strong>Date:</strong> <?= htmlspecialchars($pendingJoiningPayment['payment_date']) ?></div>
                                </div>
                                <div class="text-muted mt-2" style="font-size: 0.76rem;">
                                    <i class="bi bi-info-circle me-1"></i> Our Accounts Desk is verifying this payment against company bank credits. Once approved, Customer Registration will unlock instantly.
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning py-2 px-3 small d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-exclamation-circle-fill text-warning fs-5"></i>
                                <div>
                                    To <strong>register rooftop solar customers</strong> and earn project commissions, please pay the one-time Registration Fee of <strong>₹<?= number_format((float)$joiningFeeAmount) ?></strong> and submit your transaction details below.
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Official Company Bank & UPI Details -->
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-navy mb-2" style="font-size: 0.88rem;"><i class="bi bi-bank2 text-primary me-1"></i> Official SVPL Payment Channels:</h6>
                            <div class="row g-3 small">
                                <div class="col-md-6 border-end-md">
                                    <strong class="text-navy d-block">Bank Transfer (IMPS / NEFT)</strong>
                                    <div>A/C Name: <strong><?= htmlspecialchars(company_name()) ?></strong></div>
                                    <div>Bank: <strong>State Bank of India (SBI)</strong></div>
                                    <div>A/C No: <span class="font-monospace text-primary fw-bold">42398712345</span></div>
                                    <div>IFSC: <span class="font-monospace text-primary fw-bold">SBIN0001234</span></div>
                                </div>
                                <div class="col-md-6">
                                    <strong class="text-navy d-block">UPI / QR Payment</strong>
                                    <div>Corporate UPI ID:</div>
                                    <div class="p-1 px-2 bg-white rounded border font-monospace fw-bold text-success d-flex justify-content-between align-items-center mt-1">
                                        <span>suryavistaara@sbi</span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.7rem;" onclick="navigator.clipboard.writeText('suryavistaara@sbi'); alert('UPI ID copied to clipboard!');">
                                            <i class="bi bi-clipboard"></i> Copy
                                        </button>
                                    </div>
                                    <div class="text-muted mt-1" style="font-size: 0.72rem;">Supports GPay, PhonePe, Paytm, BHIM, Cred</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Submission Form -->
                    <div class="col-lg-5">
                        <div class="p-3 p-md-4 rounded-3 bg-light border shadow-sm">
                            <h6 class="fw-bold text-navy mb-3 d-flex align-items-center gap-2">
                                <i class="bi bi-receipt-cutoff text-success"></i> 
                                <span><?= !empty($pendingJoiningPayment) ? 'Update Payment UTR Details' : 'Submit Payment Details' ?></span>
                            </h6>

                            <form action="<?= url('/advisor/submit-payment') ?>" method="POST">
                                <?= csrf_field() ?>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold text-navy mb-1">Payment Method *</label>
                                    <select name="payment_method" class="form-select form-select-sm" required>
                                        <option value="UPI" <?= (($pendingJoiningPayment['payment_method'] ?? 'UPI') === 'UPI') ? 'selected' : '' ?>>UPI (GPay / PhonePe / Paytm / BHIM)</option>
                                        <option value="BANK_TRANSFER" <?= (($pendingJoiningPayment['payment_method'] ?? '') === 'BANK_TRANSFER') ? 'selected' : '' ?>>Bank Transfer (IMPS / NEFT / RTGS)</option>
                                        <option value="CASH" <?= (($pendingJoiningPayment['payment_method'] ?? '') === 'CASH') ? 'selected' : '' ?>>Cash Deposit / SVPL Office</option>
                                        <option value="CARD" <?= (($pendingJoiningPayment['payment_method'] ?? '') === 'CARD') ? 'selected' : '' ?>>Debit / Credit Card</option>
                                        <option value="OTHER" <?= (($pendingJoiningPayment['payment_method'] ?? '') === 'OTHER') ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small fw-semibold text-navy mb-1">UTR / Transaction ID / Reference No. *</label>
                                    <input type="text" name="transaction_ref" class="form-control form-control-sm font-monospace fw-bold text-uppercase" placeholder="e.g. 425612348970" value="<?= htmlspecialchars($pendingJoiningPayment['transaction_ref'] ?? '') ?>" required>
                                    <small class="text-muted" style="font-size: 0.72rem;">12-digit UTR from UPI app or bank transaction receipt.</small>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small fw-semibold text-navy mb-1">Payment Date *</label>
                                    <input type="date" name="payment_date" class="form-control form-control-sm" value="<?= htmlspecialchars($pendingJoiningPayment['payment_date'] ?? date('Y-m-d')) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-navy mb-1">Remarks (Optional)</label>
                                    <input type="text" name="payment_remarks" class="form-control form-control-sm" placeholder="e.g. Paid from mobile 98XXXXXXXX" value="">
                                </div>

                                <button type="submit" class="btn btn-svpl-solar btn-sm w-100 py-2 fw-bold">
                                    <i class="bi bi-send-check-fill me-1"></i> <?= !empty($pendingJoiningPayment) ? 'Update Payment Details' : 'Submit Registration Fee (₹' . number_format((float)$joiningFeeAmount) . ')' ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($personalCustomer)): ?>
        <!-- LINKED PERSONAL ROOFTOP SOLAR STATUS STRIP -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #061528 0%, #0f2d59 60%, #047857 100%); border-left: 5px solid #10b981 !important;">
            <div class="card-body p-3 p-md-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: bold; flex-shrink: 0; box-shadow: 0 4px 12px rgba(16,185,129,0.35);">
                        <i class="bi bi-house-check-fill"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h5 class="fw-bold font-heading mb-0 text-white">My Personal Rooftop Solar Project</h5>
                            <span class="badge bg-success text-white fw-bold" style="font-size: 0.72rem;">Customer A/C: <?= htmlspecialchars($personalCustomer['customer_code']) ?></span>
                            <span class="badge bg-warning text-dark fw-bold" style="font-size: 0.72rem;">Stage: <?= htmlspecialchars($personalLead['stage'] ?? 'REGISTRATION') ?></span>
                        </div>
                        <p class="text-white-50 small mb-0">
                            Your <strong><?= $personalCustomer['proposed_solar_kw'] ?? 3 ?> kW</strong> installation is actively progressing. You can track all 15 stages, acknowledge equipment dispatch, and view your DBT subsidy anytime.
                        </p>
                    </div>
                </div>
                <div>
                    <a href="<?= url('/advisor/my-solar') ?>" class="btn btn-success fw-bold px-3 py-2 shadow d-inline-flex align-items-center gap-2">
                        <i class="bi bi-speedometer2"></i> <span>Open 15-Stage Tracker →</span>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- 4 CORE COMMISSION & EARNINGS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-semibold text-uppercase">Approved Earnings</span>
                        <div class="bg-success-subtle text-success p-2 rounded-circle"><i class="bi bi-check-circle"></i></div>
                    </div>
                    <div class="h3 fw-bold text-success mb-0">₹<?= number_format((float)($commStats['approved_commission'] ?? 0), 2) ?></div>
                    <div class="text-muted small mt-1">Lifetime approved and settled</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-semibold text-uppercase">Pending Commissions</span>
                        <div class="bg-warning-subtle text-warning p-2 rounded-circle"><i class="bi bi-hourglass-split"></i></div>
                    </div>
                    <div class="h3 fw-bold text-dark mb-0">₹<?= number_format((float)($commStats['pending_commission'] ?? 0), 2) ?></div>
                    <div class="text-muted small mt-1">Awaiting admin review / company credit</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-semibold text-uppercase">Current Month Earnings</span>
                        <div class="bg-primary-subtle text-primary p-2 rounded-circle"><i class="bi bi-calendar-check"></i></div>
                    </div>
                    <div class="h3 fw-bold text-primary mb-0">₹<?= number_format((float)($commStats['current_month_earnings'] ?? 0), 2) ?></div>
                    <div class="text-muted small mt-1"><?= date('F Y') ?> approved payouts</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-semibold text-uppercase">Previous Month Earnings</span>
                        <div class="bg-info-subtle text-info p-2 rounded-circle"><i class="bi bi-clock-history"></i></div>
                    </div>
                    <div class="h3 fw-bold text-dark mb-0">₹<?= number_format((float)($commStats['prev_month_earnings'] ?? 0), 2) ?></div>
                    <div class="text-muted small mt-1">Last cycle settled</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2ND ROW: SPECIAL BONUS, POOL BONUS & LIFETIME REWARDS -->
    <div class="row g-3 mb-4">
        <!-- Monthly Special Bonus Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-lightning-charge text-warning me-2"></i>Monthly Customer Bonus</h6>
                    <span class="badge bg-light text-secondary border"><?= date('F Y') ?></span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Personal Customers This Month:</span>
                        <span class="h4 fw-bold text-primary mb-0"><?= (int)$monthlyCustomerCount ?></span>
                    </div>
                    
                    <?php 
                        $nextBonusTarget = 5;
                        $nextBonusVal = 3000;
                        if ($monthlyCustomerCount >= 20) {
                            $nextBonusTarget = 20;
                            $nextBonusVal = 30000;
                        } elseif ($monthlyCustomerCount >= 10) {
                            $nextBonusTarget = 20;
                            $nextBonusVal = 30000;
                        } elseif ($monthlyCustomerCount >= 5) {
                            $nextBonusTarget = 10;
                            $nextBonusVal = 10000;
                        }
                        $progressPercent = min(100, round(($monthlyCustomerCount / $nextBonusTarget) * 100, 1));
                    ?>

                    <div class="text-muted small mb-1 d-flex justify-content-between">
                        <span>Target: <?= $nextBonusTarget ?> Customers</span>
                        <span class="fw-bold text-success">Bonus: ₹<?= number_format($nextBonusVal, 0) ?></span>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" style="width: <?= $progressPercent ?>%"></div>
                    </div>
                    <div class="p-2 bg-light rounded text-muted small">
                        <i class="bi bi-info-circle me-1 text-primary"></i>5 cust = ₹3k | 10 cust = ₹10k | 20 cust = ₹30k
                    </div>
                </div>
            </div>
        </div>

        <!-- Separate Pool Bonus Tree Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-diagram-3 text-success me-2"></i>Pool Bonus Status (PB)</h6>
                    <?php if ($poolInfo): ?>
                        <span class="badge bg-success font-monospace"><?= htmlspecialchars($poolInfo['pool_label']) ?></span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">In Progress</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if ($poolInfo): ?>
                        <div class="p-3 bg-success-subtle rounded-3 mb-3 text-success">
                            <div class="fw-bold fs-5"><i class="bi bi-patch-check-fill me-2"></i>Pool Qualified: <?= htmlspecialchars($poolInfo['pool_label']) ?></div>
                            <div class="small mt-1 text-dark">Pool Level: <strong>Level <?= (int)$poolInfo['pool_level'] ?></strong> | Parent: <strong><?= htmlspecialchars($poolInfo['parent_label'] ?? 'Root') ?></strong></div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Direct Pool Children:</span>
                            <span class="fw-bold text-dark"><?= (int)$poolInfo['direct_pool_children_count'] ?> / 3</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Total Pool Bonus Earned:</span>
                            <span class="fw-bold text-success">₹<?= number_format((float)$poolInfo['pool_earnings'], 2) ?></span>
                        </div>
                    <?php else: ?>
                        <p class="text-muted small mb-2">Qualify as a Pool Member by achieving 3 direct active advisors and 3 direct personal customers.</p>
                        <div class="small mb-1 d-flex justify-content-between text-muted">
                            <span>Direct Advisors: <strong><?= (int)($poolQualification['direct_advisors'] ?? 0) ?> / 3</strong></span>
                            <span>Personal Custs: <strong><?= (int)($poolQualification['personal_customers'] ?? 0) ?> / 3</strong></span>
                        </div>
                        <div class="progress mb-2" style="height: 8px;">
                            <?php 
                                $advProgress = min(50, ((int)($poolQualification['direct_advisors'] ?? 0) / 3) * 50);
                                $custProgress = min(50, ((int)($poolQualification['personal_customers'] ?? 0) / 3) * 50);
                            ?>
                            <div class="progress-bar bg-primary" style="width: <?= $advProgress ?>%"></div>
                            <div class="progress-bar bg-success" style="width: <?= $custProgress ?>%"></div>
                        </div>
                        <div class="text-muted small">Sequential PB number assigned immediately upon qualification!</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Lifetime Performance Rewards Progress Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-trophy text-warning me-2"></i>Lifetime Rewards</h6>
                    <a href="<?= url('/advisor/rewards') ?>" class="small text-decoration-none">View All &raquo;</a>
                </div>
                <div class="card-body">
                    <?php $nextRew = $rewardProgress['next_target'] ?? null; ?>
                    <?php if ($nextRew): ?>
                        <div class="text-muted small fw-semibold mb-1">Next Milestone:</div>
                        <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($nextRew['reward_name']) ?></h6>
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span><?= $nextRew['current'] ?> / <?= $nextRew['target'] ?> Customers</span>
                            <span class="fw-bold text-success">Reward: ₹<?= number_format($nextRew['reward_value'], 0) ?></span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: <?= $nextRew['progress_percent'] ?>%"></div>
                        </div>
                        <div class="text-muted small">
                            <?= $nextRew['remaining'] ?> more personal customer solar installations to achieve this reward!
                        </div>
                    <?php else: ?>
                        <div class="p-3 bg-light rounded text-center text-muted small">
                            <i class="bi bi-trophy fs-3 text-warning d-block mb-1"></i>
                            All lifetime reward milestones unlocked or in progress!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 3RD ROW: NETWORK & UPLINE QUALIFICATION OVERVIEW -->
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-people me-2 text-primary"></i>Advisor Network & Downline Summary</h6>
                    <a href="<?= url('/advisor/my-network') ?>" class="btn btn-outline-primary btn-sm">Full 9-Level Tree</a>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="text-muted small">Direct Advisors</div>
                                <div class="h4 fw-bold text-primary mb-0"><?= (int)($networkStats['direct_advisors'] ?? 0) ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="text-muted small">Total Downline</div>
                                <div class="h4 fw-bold text-dark mb-0"><?= (int)($networkStats['total_downline'] ?? 0) ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="text-muted small">Personal Custs</div>
                                <div class="h4 fw-bold text-success mb-0"><?= (int)$personalCustomerCount ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded-3 border-start border-4 border-info">
                        <div class="fw-bold text-dark small mb-1"><i class="bi bi-shield-check me-1 text-info"></i>Upline Commission Qualification Level:</div>
                        <div class="text-muted small">
                            You currently have <strong><?= (int)$personalCustomerCount ?> personal customer(s)</strong>, qualifying you for <strong>Level <?= (int)$maxEligibleLevel ?></strong> of upline referral commissions.
                            <?php if ($maxEligibleLevel < 9): ?>
                                <span class="d-block mt-1 text-primary">Refer <?= max(0, 3 - $personalCustomerCount) ?> more personal customer(s) to unlock up to Level 9 full upline commissions.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-wallet2 me-2 text-success"></i>Recent Wallet Ledger Transactions</h6>
                    <a href="<?= url('/advisor/wallet') ?>" class="btn btn-outline-success btn-sm">View Ledger</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light text-muted text-uppercase">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Ref / Description</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $recentTxns = \App\Services\WalletService::getAdvisorTransactions((int)$advisor['id'], 5);
                                ?>
                                <?php if (empty($recentTxns)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No transactions recorded yet.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentTxns as $txn): ?>
                                        <tr>
                                            <td class="font-monospace text-muted"><?= date('d-M-Y', strtotime($txn['created_at'])) ?></td>
                                            <td><span class="badge bg-light text-dark border"><?= str_replace('_', ' ', $txn['transaction_type']) ?></span></td>
                                            <td class="text-truncate" style="max-width: 180px;"><?= htmlspecialchars($txn['description']) ?></td>
                                            <td class="text-end fw-bold <?= (float)$txn['credit_amount'] > 0 ? 'text-success' : 'text-danger' ?>">
                                                <?= (float)$txn['credit_amount'] > 0 ? '+₹' . number_format((float)$txn['credit_amount'], 2) : '-₹' . number_format((float)$txn['debit_amount'], 2) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

