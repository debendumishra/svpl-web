<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Public Homepage - PM Surya Ghar Odisha Hub & Solar Calculator
 */
$title = "Surya Vistaara Pvt. Ltd. | PM Surya Ghar Muft Bijli Yojana Odisha";
?>

<!-- HERO SECTION -->
<section class="hero-svpl text-white">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-badge">
                    <i class="bi bi-sun-fill text-warning"></i> Odisha's Dedicated Solar Promoter Network
                </div>
                <h1 class="display-4 fw-extrabold mb-3" style="font-weight: 800; letter-spacing: -1px;">
                    Empowering Odisha with <span style="color: #F59E0B;">Zero Electricity Bills</span> Under PM Surya Ghar
                </h1>
                <p class="lead text-light opacity-90 mb-4" style="font-size: 1.15rem; line-height: 1.6;">
                    Surya Vistaara Pvt. Ltd. (SVPL) is the authorized corporate promoter for <strong>Dhwajja Solar India Pvt. Ltd.</strong>, building the largest grassroots network of certified Solar Advisors across all 30 districts of Odisha.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="<?= url('/register-customer') ?>" class="btn btn-svpl-gold btn-lg shadow-sm">
                        <i class="bi bi-house-door-fill me-2"></i> Apply for Solar Rooftop
                    </a>
                    <a href="<?= url('/register-advisor') ?>" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-person-badge-fill me-2"></i> Join as Solar Advisor
                    </a>
                </div>
                <div class="d-flex align-items-center gap-4 text-light opacity-75 pt-2">
                    <div><i class="bi bi-check-circle-fill text-warning me-1"></i> Up to ₹1,38,000 Total Subsidy</div>
                    <div><i class="bi bi-check-circle-fill text-warning me-1"></i> 5.6% p.a. Solar Loan (EMIs from ₹545/mo)</div>
                    <div><i class="bi bi-check-circle-fill text-warning me-1"></i> 25-Year Warranty</div>
                </div>
            </div>

            <!-- Interactive Solar Calculator Card -->
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="card card-svpl p-4 text-dark shadow-lg border-0" style="border-radius: 16px;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="fw-bold mb-0 text-navy" style="color: #0B2545;">
                            <i class="bi bi-calculator-fill text-warning me-2"></i> Solar Savings Calculator
                        </h4>
                        <span class="badge bg-warning text-dark border border-warning">5.6% p.a. Solar Loan</span>
                    </div>
                    <p class="text-muted small mb-3">Official PM Surya Ghar rooftop pricing & state subsidy for Odisha households by <strong>Dhwajja Solar</strong>.</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Solar Plant Capacity:</label>
                        <select class="form-select form-select-lg" id="calcCapacity">
                            <option value="2">2 KW (120 sq.ft. | ₹1,10,000 Subsidy | Net: ₹50,000)</option>
                            <option value="3" selected>3 KW (180 sq.ft. | ₹1,38,000 Subsidy | Net: ₹72,000)</option>
                            <option value="4">4 KW (210 sq.ft. | ₹1,38,000 Subsidy | Net: ₹1,22,000)</option>
                            <option value="5">5 KW (270 sq.ft. | ₹1,38,000 Subsidy | Net: ₹1,92,000)</option>
                        </select>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Project Cost (Approx.):</span>
                            <strong id="calcTotalCost" class="text-dark">₹2,10,000/-</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1 text-success small">
                            <span><i class="bi bi-check2-circle me-1"></i> Total Central Subsidy (DBT):</span>
                            <strong id="calcCentralSubsidy">- ₹78,000/-</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-success small">
                            <span><i class="bi bi-patch-check-fill text-warning me-1"></i> Odisha State Subsidy:</span>
                            <strong id="calcStateSubsidy">- ₹60,000/-</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2 py-1 px-2 rounded bg-success-subtle text-success fw-bold">
                            <span>Total Govt. Subsidy Benefit:</span>
                            <strong id="calcTotalSubsidyDisplay">- ₹1,38,000/-</strong>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between fs-5 fw-bold" style="color: #0B2545;">
                            <span>Net Investment (Approx.):</span>
                            <span id="calcNetCost" class="text-primary">₹72,000/-</span>
                        </div>
                        <div class="d-flex justify-content-between pt-2 border-top mt-2 small text-muted">
                            <span><i class="bi bi-rulers me-1"></i> Approx. Rooftop Area: <strong id="calcArea" class="text-dark">180 sq.ft.</strong></span>
                            <span><i class="bi bi-bank text-warning me-1"></i> Easy EMI: <strong id="calcEmi" class="text-success">₹785 / month</strong></span>
                        </div>
                    </div>

                    <div class="alert alert-warning-subtle text-dark border-warning-subtle py-2 px-3 mb-3 small d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Monthly Generation Savings:</span>
                        <strong id="calcMonthlySavings" class="text-success fs-6">₹2,340 / month</strong>
                    </div>

                    <a href="<?= url('/register-customer') ?>" class="btn btn-svpl-navy w-100 py-2">
                        Check Solar Eligibility & Apply Now <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FINANCIAL COMPARISON & BENEFITS (FROM DHWAJJA SOLAR BROCHURE) -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge bg-primary px-3 py-2 text-white fw-bold mb-2">DHWAJJA SOLAR INDIA PVT. LTD.</span>
            <h2 class="fw-bold" style="color: #0B2545;">Financial Comparison & Benefits (Indicative)</h2>
            <p class="text-muted">Government subsidy support subject to applicable eligibility and DISCOM guidelines in Odisha</p>
        </div>

        <div class="card card-svpl border-0 shadow-sm mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-dark" style="background-color: #0B2545;">
                        <tr>
                            <th class="py-3">SOLAR PLANT</th>
                            <th>APPROX. ROOFTOP AREA</th>
                            <th>PROJECT COST (Approx.)</th>
                            <th>TOTAL SUBSIDY (Central)</th>
                            <th>STATE SUBSIDY (Odisha)</th>
                            <th>NET INVESTMENT (Approx.)</th>
                            <th>EASY MONTHLY EMI (5.6% p.a., 10 Yrs)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong class="text-navy">2 KW</strong></td>
                            <td>120 sq.ft.</td>
                            <td><strong>₹1,60,000/-</strong></td>
                            <td class="text-success fw-semibold">₹60,000/-</td>
                            <td class="text-warning fw-semibold">₹50,000/-</td>
                            <td><span class="badge bg-success fs-6 px-3 py-2">₹50,000/-</span></td>
                            <td class="text-primary fw-bold">₹545 / mo</td>
                        </tr>
                        <tr class="table-warning">
                            <td><strong class="text-navy">3 KW</strong> <span class="badge bg-warning text-dark ms-1">Recommended</span></td>
                            <td>180 sq.ft.</td>
                            <td><strong>₹2,10,000/-</strong></td>
                            <td class="text-success fw-bold">₹78,000/-</td>
                            <td class="text-warning fw-bold">₹60,000/-</td>
                            <td><span class="badge bg-success fs-6 px-3 py-2">₹72,000/-</span></td>
                            <td class="text-primary fw-bold">₹785 / mo</td>
                        </tr>
                        <tr>
                            <td><strong class="text-navy">4 KW</strong></td>
                            <td>210 sq.ft.</td>
                            <td><strong>₹2,60,000/-</strong></td>
                            <td class="text-success fw-semibold">₹78,000/-</td>
                            <td class="text-warning fw-semibold">₹60,000/-</td>
                            <td><span class="badge bg-primary fs-6 px-3 py-2">₹1,22,000/-</span></td>
                            <td class="text-primary fw-bold">₹1,333 / mo</td>
                        </tr>
                        <tr>
                            <td><strong class="text-navy">5 KW</strong></td>
                            <td>270 sq.ft.</td>
                            <td><strong>₹3,30,000/-</strong></td>
                            <td class="text-success fw-semibold">₹78,000/-</td>
                            <td class="text-warning fw-semibold">₹60,000/-</td>
                            <td><span class="badge bg-primary fs-6 px-3 py-2">₹1,92,000/-</span></td>
                            <td class="text-primary fw-bold">₹2,098 / mo</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-light p-3 small text-muted">
                <i class="bi bi-info-circle me-1"></i> Project cost, subsidy eligibility, and net investment may vary according to applicable government guidelines, site conditions, DISCOM requirements, and final quotation.
            </div>
        </div>

        <!-- SOLAR LOAN & EMI ILLUSTRATION -->
        <div class="row g-4 mt-2">
            <div class="col-lg-6">
                <div class="card card-svpl p-4 h-100 border-0 shadow-sm">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle bg-success-subtle text-success p-2 fs-4">
                            <i class="bi bi-bank2"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-navy" style="color: #0B2545;">Solar Loan & Easy EMI Available</h4>
                    </div>
                    <p class="text-muted small">Eligible customers can finance the applicable balance net investment amount through low-interest solar-loan facilities at concessional bank rates.</p>
                    
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <h6 class="fw-bold text-navy mb-2"><i class="bi bi-check-circle-fill text-success me-1"></i> Illustrative Calculation at 5.6% p.a. (3 KW Solar Plant):</h6>
                        <div class="d-flex justify-content-between small py-1 border-bottom"><span>Loan Amount:</span><strong>₹72,000</strong></div>
                        <div class="d-flex justify-content-between small py-1 border-bottom"><span>Illustrative Interest Rate:</span><strong>5.6% p.a.</strong></div>
                        <div class="d-flex justify-content-between small py-1 border-bottom"><span>Tenure:</span><strong>10 Years / 120 Months</strong></div>
                        <div class="d-flex justify-content-between small py-1 border-bottom text-success fw-bold"><span>Approx. Monthly EMI:</span><strong>₹785 per month</strong></div>
                        <div class="d-flex justify-content-between small py-1 border-bottom"><span>Approx. Total Repayment:</span><strong>₹94,195</strong></div>
                        <div class="d-flex justify-content-between small py-1"><span>Approx. Total Interest:</span><strong>₹22,195</strong></div>
                    </div>

                    <div class="small text-muted">
                        * EMI example calculated on a reducing-balance basis for illustration only. Actual EMI, interest rate, tenure, processing charges, and eligibility are subject to the lender's current terms and sanction.
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-svpl p-4 h-100 border-0 shadow-sm">
                    <h5 class="fw-bold mb-3 text-navy" style="color: #0B2545;"><i class="bi bi-table text-warning me-2"></i> EMI Illustration — 5.6% p.a., 10 Years</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Loan Amount</th>
                                    <th>Approx. Monthly EMI (10 Years)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>₹50,000</td><td class="text-success fw-bold">₹545</td></tr>
                                <tr class="table-warning"><td><strong>₹72,000 (3 kW Net)</strong></td><td class="text-success fw-bold">₹785</td></tr>
                                <tr><td>₹1,00,000</td><td class="text-success fw-bold">₹1,093</td></tr>
                                <tr><td>₹1,25,000</td><td class="text-success fw-bold">₹1,366</td></tr>
                                <tr><td>₹1,50,000</td><td class="text-success fw-bold">₹1,639</td></tr>
                                <tr><td>₹2,00,000</td><td class="text-success fw-bold">₹2,185</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-success-subtle text-success p-3 rounded-3 mt-auto mb-0 small">
                        <strong><i class="bi bi-telephone-fill me-1"></i> Check Your Solar Eligibility & EMI Today:</strong><br>
                        Call our helpline: <strong><a href="tel:9040999899" class="text-success text-decoration-none">9040999899</a></strong> for a FREE consultation, site assessment, and quotation.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HIGHLIGHTS & KEY STATS -->
<section class="py-5" style="background-color: #F8FAFC;">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <h2 class="fw-bold mb-1" style="color: #0B2545;">30</h2>
                    <p class="text-muted mb-0 small">Districts Covered in Odisha</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <h2 class="fw-bold mb-1 text-success">₹1,38,000</h2>
                    <p class="text-muted mb-0 small">Max Combined Subsidy</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <h2 class="fw-bold mb-1 text-warning">5.6% p.a.</h2>
                    <p class="text-muted mb-0 small">Concessional Solar Loan</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <h2 class="fw-bold mb-1" style="color: #10B981;">300 Units</h2>
                    <p class="text-muted mb-0 small">Free Monthly Electricity</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ADVISOR BUSINESS NETWORK OPPORTUNITY -->
<section class="py-5" style="background-color: #F8FAFC;">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-warning text-dark px-3 py-2 mb-2 fw-bold">ENTREPRENEURSHIP IN SOLAR</span>
                <h2 class="fw-bold mb-3" style="color: #0B2545;">Build a Rewarding Career as an SVPL Solar Advisor</h2>
                <p class="text-muted mb-4">
                    Join Odisha's fastest growing clean energy movement. As an SVPL Solar Advisor, you guide households to adopt PM Surya Ghar, earn attractive direct customer commissions, unlock 9-level upline network overrides, and qualify for executive leadership milestones.
                </p>
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="stat-icon bg-warning-subtle text-warning">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" style="color: #0B2545;">Direct Customer Commission & Bonus</h5>
                            <p class="text-muted small mb-0">Earn ₹1,000+ commission and an extra ₹500 direct customer bonus per successful installation.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="stat-icon bg-success-subtle text-success">
                            <i class="bi bi-diagram-3-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" style="color: #0B2545;">9-Level Downline Network Income</h5>
                            <p class="text-muted small mb-0">Build your team of advisors across blocks and gram panchayats to earn multi-tier team overrides.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="stat-icon bg-primary-subtle text-primary">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" style="color: #0B2545;">3-Customer Qualification Rule</h5>
                            <p class="text-muted small mb-0">Complete just 3 direct customer installations to automatically upgrade your rank to QUALIFIED status.</p>
                        </div>
                    </div>
                </div>
                <a href="<?= url('/register-advisor') ?>" class="btn btn-svpl-gold btn-lg">
                    Join as Solar Advisor Now <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0 text-center">
                <div class="card card-svpl p-4 bg-white border-0 shadow">
                    <h5 class="fw-bold mb-3" style="color: #0B2545;">9-Level Commission Distribution Plan</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped text-start">
                            <thead>
                                <tr class="table-dark">
                                    <th>Level</th>
                                    <th>Hierarchy Role</th>
                                    <th>Commission</th>
                                    <th>Bonus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-warning fw-bold">
                                    <td>Level 1</td>
                                    <td>Direct Sponsor</td>
                                    <td>₹1,000</td>
                                    <td>₹500</td>
                                </tr>
                                <tr>
                                    <td>Level 2</td>
                                    <td>2nd Generation Upline</td>
                                    <td>₹400</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Level 3</td>
                                    <td>3rd Generation Upline</td>
                                    <td>₹250</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Level 4</td>
                                    <td>4th Generation Upline</td>
                                    <td>₹150</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Level 5 to 9</td>
                                    <td>5th to 9th Generation</td>
                                    <td>₹100 to ₹35</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="small text-muted text-start mt-2">
                        * All payouts are subject to 5% statutory TDS deduction and credited directly to the registered advisor bank account.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
