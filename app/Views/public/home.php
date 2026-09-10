<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Redesigned Public Homepage with Hero Image Carousel & Interactive Solar Calculator
 */
$title = "Surya Vistaara | PM Surya Ghar Odisha Rooftop Solar Scheme";
?>

<!-- HERO IMAGE CAROUSEL SECTION -->
<section class="hero-carousel">
    <div id="heroSolarCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <!-- Numbered / Bar Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroSolarCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroSolarCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroSolarCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <!-- Carousel Slides -->
        <div class="carousel-inner">
            <!-- Slide 1: Modern Rooftop Plant -->
            <div class="carousel-item active" style="background-image: url('<?= asset('assets/images/carousel-1.jpg') ?>');">
                <div class="carousel-overlay">
                    <div class="container py-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8 animate-fade-in">
                                <div class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3 shadow-sm" style="font-size: 0.8rem; letter-spacing: 0.05em;">
                                    <i class="bi bi-patch-check-fill text-dark me-1"></i> PM Surya Ghar Odisha Official Channel Partner
                                </div>
                                <h1 class="display-4 fw-extrabold text-white font-heading mb-3" style="font-weight: 800; line-height: 1.15; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                                    Empowering Odisha with <span style="color: #FBBF24;">Zero Electricity Bills</span>
                                </h1>
                                <p class="lead text-light mb-4" style="font-size: 1.2rem; max-width: 680px; text-shadow: 0 1px 4px rgba(0,0,0,0.6);">
                                    Get up to <strong>₹78,000 Central DBT</strong> + <strong>₹60,000 Odisha State Subsidy</strong> = <span class="badge bg-success fs-6 fw-bold">₹1,38,000 Total Govt. Grant</span> with easy solar bank loans at just <strong>5.6% p.a.</strong>
                                </p>
                                <div class="d-flex flex-wrap gap-3 mb-4">
                                    <a href="<?= url('/solar-solutions') ?>" class="btn btn-svpl-solar btn-lg shadow">
                                        <i class="bi bi-sun-fill me-2"></i> Explore Solar Packages
                                    </a>
                                    <a href="#solar-calculator-section" class="btn btn-outline-light btn-lg">
                                        <i class="bi bi-calculator me-2"></i> Calculate Savings
                                    </a>
                                </div>
                                <div class="d-flex align-items-center gap-4 text-light flex-wrap small">
                                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i> Tier-1 Dhwajja Mono PERC</span>
                                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i> Dual Subsidy DBT Enabled</span>
                                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i> 25 Years Linear Warranty</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Happy Family Savings -->
            <div class="carousel-item" style="background-image: url('<?= asset('assets/images/carousel-2.jpg') ?>');">
                <div class="carousel-overlay">
                    <div class="container py-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8 animate-fade-in">
                                <div class="badge bg-success text-white px-3 py-2 fw-bold text-uppercase mb-3 shadow-sm" style="font-size: 0.8rem; letter-spacing: 0.05em;">
                                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Zero Down Payment & Easy EMIs
                                </div>
                                <h1 class="display-4 fw-extrabold text-white font-heading mb-3" style="font-weight: 800; line-height: 1.15; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                                    Save <span style="color: #34D399;">₹3,000+ Every Month</span> on Grid Power
                                </h1>
                                <p class="lead text-light mb-4" style="font-size: 1.2rem; max-width: 680px; text-shadow: 0 1px 4px rgba(0,0,0,0.6);">
                                    Pay off your concessional 5.6% solar loan EMI (starting at <strong>₹545/mo</strong>) using your monthly electricity savings and enjoy 25 years of virtually free solar power!
                                </p>
                                <div class="d-flex flex-wrap gap-3 mb-4">
                                    <a href="<?= url('/become-advisor') ?>" class="btn btn-svpl-solar btn-lg shadow">
                                        <i class="bi bi-person-badge-fill me-2"></i> Join as Solar Advisor
                                    </a>
                                    <a href="<?= url('/contact') ?>" class="btn btn-outline-light btn-lg">
                                        <i class="bi bi-headset me-2"></i> Contact Advisor Desk
                                    </a>
                                </div>
                                <div class="d-flex align-items-center gap-4 text-light flex-wrap small">
                                    <span><i class="bi bi-check-circle-fill text-success me-1"></i> Zero Maintenance Hassles</span>
                                    <span><i class="bi bi-check-circle-fill text-success me-1"></i> Bi-directional Net Meter</span>
                                    <span><i class="bi bi-check-circle-fill text-success me-1"></i> 14-Day Fast-Track Delivery</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Engineering Team & Cyclone Resistant Structure -->
            <div class="carousel-item" style="background-image: url('<?= asset('assets/images/carousel-3.jpg') ?>');">
                <div class="carousel-overlay">
                    <div class="container py-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8 animate-fade-in">
                                <div class="badge bg-primary text-white px-3 py-2 fw-bold text-uppercase mb-3 shadow-sm" style="font-size: 0.8rem; letter-spacing: 0.05em;">
                                    <i class="bi bi-tools me-1"></i> Certified Solar Engineering Excellence
                                </div>
                                <h1 class="display-4 fw-extrabold text-white font-heading mb-3" style="font-weight: 800; line-height: 1.15; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                                    180 km/h Cyclone Resilient <span style="color: #FBBF24;">Dhwajja Technology</span>
                                </h1>
                                <p class="lead text-light mb-4" style="font-size: 1.2rem; max-width: 680px; text-shadow: 0 1px 4px rgba(0,0,0,0.6);">
                                    Engineered specifically for Odisha's coastal climate with hot-dip galvanized mounting structures, dual-MPPT smart Wi-Fi inverters, and high-density Mono PERC cells.
                                </p>
                                <div class="d-flex flex-wrap gap-3 mb-4">
                                    <a href="<?= url('/solar-solutions') ?>" class="btn btn-svpl-solar btn-lg shadow">
                                        <i class="bi bi-shield-check me-2"></i> View Solar Packages
                                    </a>
                                    <a href="<?= url('/pm-surya-ghar') ?>" class="btn btn-outline-light btn-lg">
                                        <i class="bi bi-info-circle me-2"></i> Scheme Guidelines
                                    </a>
                                </div>
                                <div class="d-flex align-items-center gap-4 text-light flex-wrap small">
                                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i> DCR Compliant Modules</span>
                                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i> TPCODL / TPNODL Certified</span>
                                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i> IoT Real-time Generation App</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroSolarCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroSolarCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

    <!-- QUICK HIGHLIGHT METRIC STRIP -->
    <div class="bg-navy py-3 border-bottom border-secondary text-white" style="background: #061528;">
        <div class="container">
            <div class="row g-3 text-center">
                <div class="col-6 col-md-3">
                    <div class="fs-4 fw-bold text-warning font-heading">₹1,38,000</div>
                    <div class="small text-light" style="font-size: 0.8rem;">Max Odisha Dual Subsidy</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="fs-4 fw-bold text-success font-heading">5.6% p.a.</div>
                    <div class="small text-light" style="font-size: 0.8rem;">Concessional Bank Loan</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="fs-4 fw-bold text-info font-heading">300 Units</div>
                    <div class="small text-light" style="font-size: 0.8rem;">Free Power Every Month</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="fs-4 fw-bold text-warning font-heading">25 Years</div>
                    <div class="small text-light" style="font-size: 0.8rem;">Module Performance Life</div>
                </div>
            </div>
        </div>
    </div>

    <!-- PM SURYA GHAR ODISHA SAVINGS CALCULATOR -->
    <section class="py-5 bg-light border-bottom" id="solar-calculator-section">
        <div class="container">
            <div class="text-center max-w-2xl mx-auto mb-5 animate-fade-in">
                <div class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-2">
                    <i class="bi bi-calculator-fill me-1"></i> Interactive PM Surya Ghar Estimator
                </div>
                <h2 class="font-heading fw-bold text-navy">Calculate Your Solar Rooftop Subsidy & Savings</h2>
                <p class="text-secondary small">
                    Select your proposed capacity to see exact Central DBT subsidy, Odisha state incentive, net customer investment, and 5.6% concessional bank EMI.
                </p>
            </div>

            <div class="row g-4 align-items-center">
                <!-- Left Column: Capacity Selector Buttons -->
                <div class="col-lg-5 animate-fade-in stagger-1">
                    <label class="form-label fw-bold text-navy mb-2">Select Solar Plant Capacity:</label>
                    <div class="d-grid gap-2 mb-4">
                        <button type="button" class="btn btn-outline-primary text-start p-3 calc-capacity-btn" data-kw="1">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="fs-6 d-block text-navy">1 kW Rooftop Plant</strong>
                                    <small class="text-secondary">~60 sq.ft. | 120-140 Units/month</small>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">₹45,000 Subsidy</span>
                            </div>
                        </button>
                        
                        <button type="button" class="btn btn-outline-primary text-start p-3 calc-capacity-btn" data-kw="2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="fs-6 d-block text-navy">2 kW Rooftop Plant</strong>
                                    <small class="text-secondary">~110 sq.ft. | 240-280 Units/month</small>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">₹90,000 Subsidy</span>
                            </div>
                        </button>
                        
                        <button type="button" class="btn btn-primary text-start p-3 calc-capacity-btn active-calc shadow-sm" data-kw="3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="fs-6 d-block text-white">3 kW Rooftop Plant (Most Popular)</strong>
                                    <small class="text-white-50">~160 sq.ft. | 360-420 Units/month</small>
                                </div>
                                <span class="badge bg-warning text-dark fw-bold">₹1,38,000 Subsidy</span>
                            </div>
                        </button>

                        <button type="button" class="btn btn-outline-primary text-start p-3 calc-capacity-btn" data-kw="5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="fs-6 d-block text-navy">5 kW Rooftop Plant</strong>
                                    <small class="text-secondary">~270 sq.ft. | 600-700 Units/month</small>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">₹1,38,000 Subsidy</span>
                            </div>
                        </button>
                    </div>

                    <a href="<?= url('/contact') ?>" class="btn btn-svpl-solar w-100 py-3 shadow">
                        <i class="bi bi-headset me-2"></i> Inquire Solar Installation via Local Advisor
                    </a>
                </div>

            <!-- Right Column: Dynamic Financial Breakdown Ledger -->
            <div class="col-lg-7 animate-fade-in stagger-2">
                <div class="card card-svpl p-4 border-0 shadow-lg" style="background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="font-heading fw-bold mb-0 text-navy">
                            <span id="displaySelectedKw">3</span> kW Turnkey Financial Statement
                        </h5>
                        <span class="badge bg-success text-white fw-bold px-3 py-1">Direct Bank DBT</span>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-secondary" style="width: 50%;">Gross Dhwajja Turnkey Project Cost:</td>
                                    <td class="text-end fw-bold text-navy" id="displayGrossCost">₹2,10,000/-</td>
                                </tr>
                                <tr class="table-light">
                                    <td class="text-success"><i class="bi bi-check-circle-fill text-success me-1"></i> Central Govt. Subsidy (PM Surya Ghar DBT):</td>
                                    <td class="text-end text-success fw-bold" id="displayCentralSubsidy">- ₹78,000/-</td>
                                </tr>
                                <tr class="table-light">
                                    <td class="text-warning-emphasis"><i class="bi bi-patch-check-fill text-warning me-1"></i> Odisha State Govt. Subsidy (OREDA):</td>
                                    <td class="text-end text-success fw-bold" id="displayStateSubsidy">- ₹60,000/-</td>
                                </tr>
                                <tr class="table-success">
                                    <td class="fw-bold text-success"><i class="bi bi-gift-fill text-success me-1"></i> Total Combined Govt. Grant:</td>
                                    <td class="text-end text-success fw-bold fs-6" id="displayTotalSubsidy">- ₹1,38,000/-</td>
                                </tr>
                                <tr class="table-primary">
                                    <td class="fw-bold text-navy fs-6">Effective Net Investment Payable:</td>
                                    <td class="text-end text-primary fw-bold fs-4" id="displayNetCost">₹72,000/-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- EMI & Solar Savings Comparison Strip -->
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="row g-2 text-center">
                            <div class="col-sm-4 border-end">
                                <div class="text-secondary small fw-semibold">Easy Bank EMI (5.6%)</div>
                                <div class="font-heading fw-bold text-success fs-5" id="displayEmi">₹785 / mo</div>
                                <small class="text-muted">10-Year Loan</small>
                            </div>
                            <div class="col-sm-4 border-end">
                                <div class="text-secondary small fw-semibold">Monthly Bill Savings</div>
                                <div class="font-heading fw-bold text-primary fs-5" id="displaySavings">₹3,400 / mo</div>
                                <small class="text-muted">Grid Offset</small>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-secondary small fw-semibold">25-Yr Net Gain</div>
                                <div class="font-heading fw-bold text-navy fs-5">₹8.4 Lakhs+</div>
                                <small class="text-muted">Lifecycle ROI</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4 ODISHA DISCOMS & WHY DHWAJJA SOLAR -->
<section class="py-5" style="background-color: #F1F5F9;">
    <div class="container">
        <div class="text-center mb-5 animate-fade-in">
            <span class="badge bg-primary text-white fw-bold px-3 py-2 mb-2" style="font-size: 0.8rem;">
                APPROVED UTILITY PARTNERSHIPS
            </span>
            <h2 class="font-heading fw-bold text-navy">Approved Across All 4 Odisha DISCOMs</h2>
            <p class="text-secondary">Surya Vistaara handles end-to-end net-metering approvals, DISCOM site feasibility, and official grid sync.</p>
        </div>

        <div class="row g-3 mb-5 text-center">
            <div class="col-md-3 col-6">
                <div class="card card-svpl p-3 border-0 shadow-sm h-100">
                    <div class="font-heading fw-bold text-navy fs-5 mb-1">TPCODL</div>
                    <span class="text-secondary small">Central Odisha (Bhubaneswar, Cuttack, Puri)</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card card-svpl p-3 border-0 shadow-sm h-100">
                    <div class="font-heading fw-bold text-navy fs-5 mb-1">TPNODL</div>
                    <span class="text-secondary small">Northern Odisha (Balasore, Bhadrak, Mayurbhanj)</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card card-svpl p-3 border-0 shadow-sm h-100">
                    <div class="font-heading fw-bold text-navy fs-5 mb-1">TPSODL</div>
                    <span class="text-secondary small">Southern Odisha (Berhampur, Ganjam, Koraput)</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card card-svpl p-3 border-0 shadow-sm h-100">
                    <div class="font-heading fw-bold text-navy fs-5 mb-1">TPWODL</div>
                    <span class="text-secondary small">Western Odisha (Sambalpur, Rourkela, Jharsuguda)</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CALCULATOR INTERACTIVE JAVASCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pricingData = {
        '2': { gross: 160000, central: 60000, state: 50000, totalSub: 110000, net: 50000, emi: 545, savings: 2400 },
        '3': { gross: 210000, central: 78000, state: 60000, totalSub: 138000, net: 72000, emi: 785, savings: 3400 },
        '4': { gross: 260000, central: 78000, state: 60000, totalSub: 138000, net: 122000, emi: 1333, savings: 4500 },
        '5': { gross: 330000, central: 78000, state: 60000, totalSub: 138000, net: 192000, emi: 2098, savings: 5800 }
    };

    const buttons = document.querySelectorAll('.calc-capacity-btn');
    const displaySelectedKw = document.getElementById('displaySelectedKw');
    const displayGrossCost = document.getElementById('displayGrossCost');
    const displayCentralSubsidy = document.getElementById('displayCentralSubsidy');
    const displayStateSubsidy = document.getElementById('displayStateSubsidy');
    const displayTotalSubsidy = document.getElementById('displayTotalSubsidy');
    const displayNetCost = document.getElementById('displayNetCost');
    const displayEmi = document.getElementById('displayEmi');
    const displaySavings = document.getElementById('displaySavings');

    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            buttons.forEach(b => {
                b.classList.remove('btn-primary', 'active');
                b.classList.add('btn-outline-primary');
                const title = b.querySelector('strong');
                if (title) { title.classList.remove('text-white'); title.classList.add('text-navy'); }
            });

            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary', 'active');
            const thisTitle = this.querySelector('strong');
            if (thisTitle) { thisTitle.classList.remove('text-navy'); thisTitle.classList.add('text-white'); }

            const kw = this.getAttribute('data-kw');
            const data = pricingData[kw];
            if (data) {
                if (displaySelectedKw) displaySelectedKw.textContent = kw;
                if (displayGrossCost) displayGrossCost.textContent = '₹' + data.gross.toLocaleString('en-IN') + '/-';
                if (displayCentralSubsidy) displayCentralSubsidy.textContent = '- ₹' + data.central.toLocaleString('en-IN') + '/-';
                if (displayStateSubsidy) displayStateSubsidy.textContent = '- ₹' + data.state.toLocaleString('en-IN') + '/-';
                if (displayTotalSubsidy) displayTotalSubsidy.textContent = '- ₹' + data.totalSub.toLocaleString('en-IN') + '/-';
                if (displayNetCost) displayNetCost.textContent = '₹' + data.net.toLocaleString('en-IN') + '/-';
                if (displayEmi) displayEmi.textContent = '₹' + data.emi.toLocaleString('en-IN') + ' / mo';
                if (displaySavings) displaySavings.textContent = '₹' + data.savings.toLocaleString('en-IN') + ' / mo';
            }
        });
    });
});
</script>
