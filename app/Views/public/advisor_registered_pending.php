<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Registration Submitted — Awaiting Admin Payment Verification
 */
$title = "Registration Submitted — Awaiting Verification — SVPL";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="card card-svpl p-4 p-md-5 bg-white border-0 shadow-sm text-center" style="border-radius: 16px;">
                <div class="mb-3">
                    <div class="d-inline-flex p-3 rounded-circle bg-warning-subtle text-warning-emphasis mb-2">
                        <i class="bi bi-clock-history fs-1 text-warning"></i>
                    </div>
                </div>

                <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mx-auto mb-2" style="font-size: 0.78rem;">
                    Payment Under Verification
                </span>
                <h2 class="font-heading fw-bold text-navy mb-2">Application Submitted Successfully!</h2>
                <p class="text-secondary small mb-4">
                    Thank you for applying to become an authorized <strong>SVPL Solar Advisor</strong>. Your onboarding payment details have been received and sent to the SVPL Accounts & Superadmin desk for verification.
                </p>

                <!-- Summary Details Box -->
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
                            <span class="text-secondary small d-block">Registered Mobile (Login ID)</span>
                            <strong class="text-navy"><?= htmlspecialchars($advisor['mobile']) ?></strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">District / Territory</span>
                            <strong class="text-navy"><?= htmlspecialchars($advisor['district']) ?> (<?= htmlspecialchars($advisor['block']) ?>)</strong>
                        </div>
                        <div class="col-12"><hr class="my-1"></div>
                        <div class="col-sm-4">
                            <span class="text-secondary small d-block">Onboarding Fee</span>
                            <strong class="text-success fs-6">₹<?= number_format((float)($advisor['joining_fee'] ?? advisor_joining_fee()), 2) ?></strong>
                        </div>
                        <div class="col-sm-4">
                            <span class="text-secondary small d-block">Payment Mode</span>
                            <span class="badge bg-light text-dark border"><?= htmlspecialchars($paymentMethod) ?></span>
                        </div>
                        <div class="col-sm-4">
                            <span class="text-secondary small d-block">Submitted UTR / Txn Ref</span>
                            <strong class="font-monospace text-primary"><?= htmlspecialchars($transactionRef) ?></strong>
                        </div>
                    </div>
                </div>

                <!-- Step-by-step next actions -->
                <div class="alert alert-info text-start small mb-4 py-3">
                    <div class="d-flex gap-2">
                        <i class="bi bi-info-circle-fill text-info fs-5 flex-shrink-0"></i>
                        <div>
                            <strong>What happens next?</strong>
                            <ol class="mb-0 ps-3 mt-1">
                                <li>The <?= htmlspecialchars(company_short_name()) ?> Finance / Superadmin team will verify your ₹<?= number_format((float)($advisor['joining_fee'] ?? advisor_joining_fee())) ?> UTR reference with bank records.</li>
                                <li>Upon confirmation, your advisor portal access and ID Card generation will be activated automatically.</li>
                                <li>You will then be able to log in using your registered mobile number and password.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="<?= url('/login') ?>" class="btn btn-svpl-solar px-4 py-2 fw-semibold">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Go to Advisor Login
                    </a>
                    <a href="<?= url('/') ?>" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-house me-1"></i> Return to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
