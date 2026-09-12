<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Become an Advisor & Business Opportunity Presentation
 */
$title = "Become a Solar Advisor — Business Opportunity & 9-Level Rewards | SVPL";
?>

<!-- HERO SECTION -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, #040B15 0%, #0B2545 100%); border-bottom: 3px solid var(--svpl-gold);">
    <div class="container py-3">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-3">
                    <i class="bi bi-person-badge-fill me-1"></i> HIGH-INCOME SOLAR CAREER IN ODISHA
                </span>
                <h1 class="display-5 fw-bold font-heading mb-3 text-white">
                    Become an Authorized <span style="color: var(--svpl-gold-bright);">Solar Advisor Partner</span>
                </h1>
                <p class="lead text-light mb-4" style="font-size: 1.1rem; max-width: 680px;">
                    Join <?= htmlspecialchars(company_name()) ?>'s mission to power 1 Lakh+ Odisha homes under PM Surya Ghar Muft Bijli Yojana. Earn direct customer commissions, 9-level network overrides, and lifetime passive rewards.
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= url('/register-advisor') ?>" class="btn btn-svpl-solar btn-lg shadow-sm">
                        <i class="bi bi-shield-check me-1"></i> Register as Advisor (₹<?= number_format(advisor_joining_fee()) ?>)
                    </a>
                    <a href="#compensationPlan" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-graph-up-arrow me-1"></i> 9-Level Commission Plan
                    </a>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block">
                <div class="card p-4 border-2 border-warning shadow-lg text-dark" style="background: #FFFFFF; border-radius: 16px;">
                    <div class="text-center mb-3">
                        <span class="badge bg-success text-white px-3 py-1">ONBOARDING KIT</span>
                        <h4 class="font-heading fw-bold text-navy mt-2">₹<?= number_format(advisor_joining_fee()) ?> One-Time Fee</h4>
                        <p class="text-muted small mb-0">Complete Digital License & Marketing Kit</p>
                    </div>
                    <ul class="list-unstyled small text-secondary mb-3">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Instant Printable Photo ID Card</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Doorstep Customer QR Code</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Digital Customer Registration Portal</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> 9-Level Network Tree Access</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Weekly Direct Bank NEFT Payouts</li>
                    </ul>
                    <a href="<?= url('/register-advisor') ?>" class="btn btn-svpl-solar w-100 fw-bold">
                        Join Now & Get Activated
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4 ADVISOR ADVANTAGES -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase fw-bold text-warning small">Why Join Surya Vistaara?</span>
            <h2 class="font-heading fw-bold text-navy">Designed for Ambitious Entrepreneurs in Odisha</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card card-svpl h-100 p-4 bg-white border shadow-sm text-center">
                    <div class="stat-icon-modern bg-warning-subtle text-warning mx-auto mb-3" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h5 class="font-heading fw-bold text-navy mb-2">High Direct Incentives</h5>
                    <p class="text-secondary small mb-0">
                        Earn substantial direct commissions on every 2kW, 3kW, and 5kW customer rooftop solar application installed in your area.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card card-svpl h-100 p-4 bg-white border shadow-sm text-center">
                    <div class="stat-icon-modern bg-primary-subtle text-primary mx-auto mb-3" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                    <h5 class="font-heading fw-bold text-navy mb-2">9-Level Downline Income</h5>
                    <p class="text-secondary small mb-0">
                        Build your team of district and block advisors. Earn passive overrides across 9 generation levels as your network grows.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card card-svpl h-100 p-4 bg-white border shadow-sm text-center">
                    <div class="stat-icon-modern bg-success-subtle text-success mx-auto mb-3" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>
                    <h5 class="font-heading fw-bold text-navy mb-2">Doorstep QR Technology</h5>
                    <p class="text-secondary small mb-0">
                        Never send customer documents over WhatsApp. Use your personalized QR code to enroll consumers directly on your phone.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card card-svpl h-100 p-4 bg-white border shadow-sm text-center">
                    <div class="stat-icon-modern bg-info-subtle text-info mx-auto mb-3" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        <i class="bi bi-bank2"></i>
                    </div>
                    <h5 class="font-heading fw-bold text-navy mb-2">Direct Bank NEFT</h5>
                    <p class="text-secondary small mb-0">
                        Track your earnings live in your Advisor Wallet with statutory 5% TDS compliance and fast bank transfer withdrawals.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9-LEVEL COMPENSATION PLAN -->
<section class="py-5 bg-white" id="compensationPlan">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2">EARNING STRUCTURE</span>
            <h2 class="font-heading fw-bold text-navy">9-Level Multi-Tier Network Override Structure</h2>
            <p class="text-secondary mx-auto" style="max-width: 650px;">
                Our transparent distribution model ensures team leaders and field advisors earn fair commissions at every level.
            </p>
        </div>

        <div class="row g-4 align-items-center">
            <div class="col-lg-7">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-dark">
                            <tr class="small text-uppercase">
                                <th>Generation Level</th>
                                <th>Advisor Network Role</th>
                                <th class="text-end">Commission Disbursal (Per Plant)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-warning fw-bold">
                                <td><span class="badge bg-warning text-dark">Direct</span> Level 1</td>
                                <td>Direct Lead Enroller / Field Advisor</td>
                                <td class="text-end text-success fs-6">₹2,000 – ₹5,000</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">Level 2</span></td>
                                <td>Immediate Sponsor / Senior Mitra</td>
                                <td class="text-end fw-bold text-navy">₹500.00</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">Level 3</span></td>
                                <td>Block Team Leader</td>
                                <td class="text-end fw-bold text-navy">₹300.00</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">Level 4</span></td>
                                <td>Sub-Division Coordinator</td>
                                <td class="text-end fw-bold text-navy">₹200.00</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">Level 5</span></td>
                                <td>District Executive</td>
                                <td class="text-end fw-bold text-navy">₹150.00</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">Level 6</span></td>
                                <td>Regional Coordinator</td>
                                <td class="text-end fw-bold text-navy">₹100.00</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">Level 7</span></td>
                                <td>State Area Manager</td>
                                <td class="text-end fw-bold text-navy">₹100.00</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">Level 8</span></td>
                                <td>Zonal Director</td>
                                <td class="text-end fw-bold text-navy">₹100.00</td>
                            </tr>
                            <tr class="table-light">
                                <td><span class="badge bg-dark text-warning">Level 9</span></td>
                                <td>Corporate Master Executive</td>
                                <td class="text-end fw-bold text-navy">₹100.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card card-svpl p-4 bg-light border shadow-sm">
                    <h5 class="font-heading fw-bold text-navy mb-3">Fast-Track Qualification Rule</h5>
                    <div class="p-3 bg-white rounded-3 border mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-warning text-dark p-2 fs-5"><i class="bi bi-check-all"></i></span>
                            <div>
                                <strong class="text-navy">3 Direct Customer Installations</strong>
                                <p class="text-secondary small mb-0">Enroll 3 direct rooftop customers to qualify as a <span class="badge bg-success">GREEN QUALIFIED ADVISOR</span> and unlock full 9-level overrides.</p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-success-subtle border-success-subtle small mb-3">
                        <i class="bi bi-shield-lock-fill text-success me-1"></i>
                        <strong>Statutory Compliance:</strong> All earnings undergo automated 5% TDS deduction with Form 16 certificates issued quarterly.
                    </div>

                    <a href="<?= url('/register-advisor') ?>" class="btn btn-svpl-solar w-100 py-3 fw-bold">
                        <i class="bi bi-person-plus-fill me-1"></i> Register as Advisor (₹<?= number_format(advisor_joining_fee()) ?>)
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
