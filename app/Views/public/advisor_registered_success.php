<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Free Advisor Registration Success Screen
 */
$title = "Advisor Registration Successful — " . company_short_name();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="card card-svpl p-4 p-md-5 bg-white border-0 shadow-sm text-center" style="border-radius: 16px;">
                <div class="mb-3">
                    <div class="d-inline-flex p-3 rounded-circle bg-success-subtle text-success mb-2">
                        <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                    </div>
                </div>

                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-bold text-uppercase mx-auto mb-2" style="font-size: 0.78rem;">
                    Free Registration Activated
                </span>
                <h2 class="font-heading fw-bold text-navy mb-2">Welcome to <?= htmlspecialchars(company_short_name()) ?>!</h2>
                <p class="text-secondary small mb-4">
                    Your Solar Advisor account has been created successfully. You can <strong>log in immediately</strong> to access your dashboard, view your referral link, and start building your 9-level solar network.
                </p>

                <!-- Credentials & Account Summary Box -->
                <div class="p-4 bg-light rounded-3 border text-start mb-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">Advisor Name</span>
                            <strong class="text-navy"><?= htmlspecialchars($advisor['first_name'] . ' ' . $advisor['last_name']) ?></strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">Generated Advisor ID</span>
                            <span class="badge bg-white text-navy border font-monospace fw-bold fs-6"><?= htmlspecialchars($advisor['advisor_code']) ?></span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">User ID / Registered Mobile</span>
                            <strong class="text-navy font-monospace"><?= htmlspecialchars($advisor['mobile']) ?></strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">Your Referral Code</span>
                            <span class="badge bg-warning text-dark font-monospace fw-bold fs-6"><?= htmlspecialchars($advisor['referral_code']) ?></span>
                        </div>
                        <?php if (!empty($advisor['email'])): ?>
                            <div class="col-12">
                                <span class="text-secondary small d-block">Registered Email</span>
                                <strong class="text-primary font-monospace"><?= htmlspecialchars($advisor['email']) ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($emailSent)): ?>
                    <div class="alert alert-success text-start small mb-4 py-2 d-flex align-items-center gap-2">
                        <i class="bi bi-envelope-check-fill text-success fs-5"></i>
                        <div>
                            Login credentials and account details have been sent to <strong><?= htmlspecialchars($advisor['email']) ?></strong>.
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Step-by-step next actions -->
                <div class="alert alert-info text-start small mb-4 py-3">
                    <div class="d-flex gap-2">
                        <i class="bi bi-lightbulb-fill text-warning fs-5 flex-shrink-0"></i>
                        <div>
                            <strong>Getting Started:</strong>
                            <ol class="mb-0 ps-3 mt-1">
                                <li><strong>Immediate Login:</strong> Click below to log in with your Mobile/Advisor ID and password.</li>
                                <li><strong>Build Your Downline (Free):</strong> Share your referral code (<code><?= htmlspecialchars($advisor['referral_code']) ?></code>) to onboard new advisors into your 9-level team.</li>
                                <li><strong>Unlock Customer Registrations:</strong> When you are ready to register solar rooftop customers and earn project commissions, submit your one-time Registration Fee (₹<?= number_format(advisor_joining_fee()) ?>) inside your dashboard.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="<?= url('/login') ?>" class="btn btn-svpl-solar px-4 py-2 fw-semibold">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Log In to Advisor Portal
                    </a>
                    <a href="<?= url('/') ?>" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-house me-1"></i> Return to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
