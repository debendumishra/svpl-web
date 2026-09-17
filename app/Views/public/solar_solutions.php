<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Solar Solutions & Rooftop Brand Packages View
 */
$title = "Solar Solutions & Brand Packages — Surya Vistaara (Dhwajja Solar India)";

// Retrieve packages from controller or model fallback
if (!isset($packages) || empty($packages)) {
    $packages = \App\Models\Package::getAllActive();
}
$brands = $brands ?? \App\Models\Package::getBrands();
?>

<!-- HERO HEADER -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, #040B15 0%, #0B2545 100%); border-bottom: 3px solid var(--svpl-gold);">
    <div class="container py-3">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-3 shadow-sm">
                    <i class="bi bi-patch-check-fill me-1"></i> TIER-1 BRAND SOLAR CATALOG
                </span>
                <h1 class="display-5 fw-bold font-heading mb-3 text-white">
                    Official Solar Rooftop Packages for <span style="color: var(--svpl-gold-bright);">Odisha Homes & Businesses</span>
                </h1>
                <p class="lead text-light mb-4" style="font-size: 1.1rem; max-width: 720px;">
                    Explore verified On-Grid, Hybrid, and Off-Grid solar packages from leading manufacturers including 
                    <strong>Tata Power Solar, Waaree Energies, Adani Solar, Loom Solar, UTL / Luminous, and IYRO Solar</strong>.
                    Eligible for up to <strong>₹1,38,000 Dual Government Subsidies</strong> under PM Surya Ghar Muft Bijli Yojana.
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="#packageCatalog" class="btn btn-svpl-solar btn-lg shadow-sm">
                        <i class="bi bi-grid-3x3-gap-fill me-2"></i> Browse Brand Catalog
                    </a>
                    <a href="#packageTable" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-table me-1"></i> Full Price Matrix
                    </a>
                    <a href="<?= url('/contact') ?>" class="btn btn-warning text-dark btn-lg fw-bold">
                        <i class="bi bi-headset me-1"></i> Request Custom Quote
                    </a>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-center">
                <div class="p-4 rounded-4 border border-warning shadow-lg" style="background: rgba(6, 21, 40, 0.85); backdrop-filter: blur(10px);">
                    <div style="font-size: 2.5rem; color: var(--svpl-gold);">☀</div>
                    <h4 class="text-white font-heading fw-bold mb-2">Max Government Subsidy</h4>
                    <p class="text-light small mb-3">Central DBT (₹78,000) + Odisha State Subsidy (₹60,000) credited directly to your bank account.</p>
                    <div class="badge bg-success-subtle text-success border border-success-subtle p-2 w-100 fs-6 fw-bold">
                        Up to ₹1,38,000 Combined Subsidy
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BRAND FILTERS & CATALOG SECTION -->
<section class="py-5 bg-light" id="packageCatalog">
    <div class="container">
        
        <div class="text-center mb-4">
            <span class="text-uppercase fw-bold text-warning small tracking-wider">Turnkey Solar Solutions</span>
            <h2 class="font-heading fw-bold text-navy">Certified Tier-1 Solar Rooftop Packages</h2>
            <p class="text-secondary mx-auto" style="max-width: 680px;">
                Filter by vendor brand or system architecture (On-Grid, Hybrid with Lithium/Tubular storage, or Off-Grid).
            </p>
        </div>

        <!-- Filter Controls -->
        <div class="card bg-white border shadow-sm p-3 mb-4 rounded-3">
            <div class="row g-3 align-items-center">
                
                <!-- Brand Filter Buttons -->
                <div class="col-lg-8">
                    <label class="form-label small fw-bold text-secondary mb-1">Filter by Brand / Vendor:</label>
                    <div class="d-flex flex-wrap gap-2" id="brandFilters">
                        <button type="button" class="btn btn-sm btn-navy filter-brand-btn active" data-brand="ALL">
                            All Brands (<?= count($packages) ?>)
                        </button>
                        <?php foreach ($brands as $b): ?>
                            <?php 
                                $bCount = count(array_filter($packages, fn($p) => ($p['brand'] ?? '') === $b));
                            ?>
                            <button type="button" class="btn btn-sm btn-outline-navy filter-brand-btn" data-brand="<?= htmlspecialchars($b) ?>">
                                <?= htmlspecialchars($b) ?> <span class="badge bg-secondary ms-1"><?= $bCount ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- System Type Filter -->
                <div class="col-lg-4">
                    <label class="form-label small fw-bold text-secondary mb-1">Filter by System Type:</label>
                    <select class="form-select form-select-sm" id="selectSystemType" onchange="filterPackages()">
                        <option value="ALL">All System Types (On-Grid, Hybrid, Off-Grid)</option>
                        <option value="On-Grid">On-Grid (Net-Metered with Subsidy)</option>
                        <option value="Hybrid">Hybrid (Solar + Battery Storage)</option>
                        <option value="Off-Grid">Off-Grid (Self-Sustaining)</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- Package Cards Grid -->
        <div class="row g-4" id="packagesGrid">
            <?php foreach ($packages as $pkg): ?>
                <?php 
                    $pBrand = $pkg['brand'] ?? 'Dhwajja Solar';
                    $pType = $pkg['system_type'] ?? 'On-Grid';
                    $pCap = (float)($pkg['capacity_kw'] ?? 3);
                    $pGross = (float)($pkg['total_price'] ?? 0);
                    $pSub = (float)($pkg['estimated_subsidy'] ?? 0);
                    $pNet = (float)($pkg['net_customer_cost'] ?? ($pGross - $pSub));
                    $pFeatures = $pkg['key_features'] ?? '';
                    $pCode = $pkg['package_code'] ?? '';

                    $isHybrid = stripos($pType, 'Hybrid') !== false;
                    $isOffGrid = stripos($pType, 'Off-Grid') !== false;
                ?>
                <div class="col-md-6 col-lg-4 package-card-item" data-brand="<?= htmlspecialchars($pBrand) ?>" data-type="<?= htmlspecialchars($pType) ?>">
                    <div class="card card-svpl h-100 p-4 bg-white border shadow-sm d-flex flex-column rounded-3 position-relative">
                        
                        <!-- Top Badges -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge <?= $isHybrid ? 'bg-info text-dark' : ($isOffGrid ? 'bg-danger text-white' : 'bg-primary text-white') ?> fw-bold px-2 py-1">
                                <i class="bi <?= $isHybrid ? 'bi-battery-charging' : ($isOffGrid ? 'bi-slash-circle' : 'bi-plug-fill') ?> me-1"></i>
                                <?= htmlspecialchars($pType) ?>
                            </span>
                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle fw-bold">
                                <?= number_format($pCap, 0) ?> kW Capacity
                            </span>
                        </div>

                        <!-- Brand Title -->
                        <div class="mb-2">
                            <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                                <?= htmlspecialchars($pBrand) ?>
                            </span>
                            <h4 class="font-heading fw-bold text-navy mb-1" style="font-size: 1.25rem;">
                                <?= htmlspecialchars($pkg['title'] ?? ($pBrand . ' ' . $pCap . 'kW ' . $pType)) ?>
                            </h4>
                        </div>

                        <!-- Pricing Breakdown Box -->
                        <div class="p-3 bg-light rounded-3 mb-3 border">
                            <div class="d-flex justify-content-between text-secondary small mb-1">
                                <span>Gross System Cost:</span>
                                <span class="fw-bold text-dark">₹<?= number_format($pGross) ?></span>
                            </div>
                            <?php if ($pSub > 0): ?>
                                <div class="d-flex justify-content-between text-success small fw-bold mb-1">
                                    <span><i class="bi bi-gift-fill me-1"></i> Combined Govt. Subsidy:</span>
                                    <span>- ₹<?= number_format($pSub) ?></span>
                                </div>
                                <hr class="my-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-navy small">Net Customer Investment:</span>
                                    <span class="fw-bold text-success fs-5">₹<?= number_format($pNet) ?></span>
                                </div>
                            <?php else: ?>
                                <hr class="my-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-navy small">Direct Package Price:</span>
                                    <span class="fw-bold text-navy fs-5">₹<?= number_format($pGross) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Technical Specs & Notes -->
                        <div class="flex-grow-1 small text-secondary mb-3">
                            <div class="mb-2">
                                <strong class="text-dark"><i class="bi bi-sun text-warning me-1"></i> Panels:</strong> 
                                <?= htmlspecialchars($pkg['panel_type'] ?? 'Mono PERC / Tier-1') ?>
                            </div>
                            <div class="mb-2">
                                <strong class="text-dark"><i class="bi bi-cpu text-primary me-1"></i> Inverter:</strong> 
                                <?= htmlspecialchars($pkg['inverter_type'] ?? 'Smart Grid Inverter') ?>
                            </div>
                            <?php if (!empty($pFeatures) && $pFeatures !== 'None'): ?>
                                <div class="p-2 bg-warning-subtle rounded border border-warning-subtle text-dark small mt-2">
                                    <i class="bi bi-info-circle-fill text-warning me-1"></i>
                                    <?= htmlspecialchars($pFeatures) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-auto pt-2">
                            <a href="<?= url('/contact?pkg=' . urlencode($pCode)) ?>" class="btn btn-svpl-solar w-100 fw-bold shadow-sm">
                                <i class="bi bi-check2-circle me-1"></i> Inquire & Book Plant
                            </a>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- No Results Fallback -->
        <div class="text-center py-5 d-none" id="noPackagesMsg">
            <i class="bi bi-search text-muted display-4"></i>
            <h5 class="text-navy fw-bold mt-3">No packages match the selected criteria</h5>
            <p class="text-secondary small">Please select a different brand or system type above.</p>
        </div>

    </div>
</section>

<!-- COMPLETE PRICE & COMPARISON MATRIX TABLE -->
<section class="py-5 bg-white border-top" id="packageTable">
    <div class="container">
        
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-3">
            <div>
                <span class="badge bg-warning text-dark fw-bold px-3 py-1 mb-2">FULL VENDOR COMPARISON</span>
                <h2 class="font-heading fw-bold text-navy mb-1">Official Solar Package Pricing Matrix</h2>
                <p class="text-secondary small mb-0">Transparent gross rates, government subsidies, and battery configurations across all 6 partner brands.</p>
            </div>
            <div>
                <a href="<?= url('/contact') ?>" class="btn btn-outline-navy btn-sm fw-bold">
                    <i class="bi bi-printer me-1"></i> Request Official Proforma Quotation
                </a>
            </div>
        </div>

        <div class="table-responsive shadow-sm rounded-3 border">
            <table class="table table-hover align-middle mb-0 bg-white">
                <thead style="background: #0B2545; color: #ffffff;">
                    <tr>
                        <th class="ps-3 py-3">Vendor / Brand</th>
                        <th class="py-3 text-center">Capacity</th>
                        <th class="py-3">System Type</th>
                        <th class="py-3 text-end">Amount (Gross)</th>
                        <th class="py-3 text-end">Govt. Subsidy</th>
                        <th class="py-3 text-end">Net Cost</th>
                        <th class="py-3" style="min-width: 260px;">Key Features / Battery Notes</th>
                        <th class="pe-3 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($packages as $pkg): ?>
                        <?php 
                            $pBrand = $pkg['brand'] ?? 'Dhwajja Solar';
                            $pType = $pkg['system_type'] ?? 'On-Grid';
                            $pCap = (float)($pkg['capacity_kw'] ?? 3);
                            $pGross = (float)($pkg['total_price'] ?? 0);
                            $pSub = (float)($pkg['estimated_subsidy'] ?? 0);
                            $pNet = (float)($pkg['net_customer_cost'] ?? ($pGross - $pSub));
                            $pFeatures = $pkg['key_features'] ?? '';
                            $pCode = $pkg['package_code'] ?? '';

                            $isHybrid = stripos($pType, 'Hybrid') !== false;
                            $isOffGrid = stripos($pType, 'Off-Grid') !== false;
                        ?>
                        <tr>
                            <td class="ps-3 fw-bold text-navy">
                                <i class="bi bi-shield-check text-warning me-1"></i>
                                <?= htmlspecialchars($pBrand) ?>
                            </td>
                            <td class="text-center fw-bold">
                                <span class="badge bg-secondary-subtle text-dark"><?= number_format($pCap, 0) ?> kW</span>
                            </td>
                            <td>
                                <span class="badge <?= $isHybrid ? 'bg-info-subtle text-info' : ($isOffGrid ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary') ?> fw-bold">
                                    <?= htmlspecialchars($pType) ?>
                                </span>
                            </td>
                            <td class="text-end font-monospace fw-bold text-dark">
                                ₹<?= number_format($pGross) ?>
                            </td>
                            <td class="text-end font-monospace fw-bold <?= $pSub > 0 ? 'text-success' : 'text-muted' ?>">
                                <?= $pSub > 0 ? ('- ₹' . number_format($pSub)) : '₹0' ?>
                            </td>
                            <td class="text-end font-monospace fw-bold text-navy fs-6">
                                ₹<?= number_format($pNet) ?>
                            </td>
                            <td class="small text-secondary">
                                <?= (!empty($pFeatures) && $pFeatures !== 'None') ? htmlspecialchars($pFeatures) : '<span class="text-muted fst-italic">Standard Tier-1 setup</span>' ?>
                            </td>
                            <td class="pe-3 text-center">
                                <a href="<?= url('/contact?pkg=' . urlencode($pCode)) ?>" class="btn btn-sm btn-outline-primary fw-bold text-nowrap">
                                    Book Now
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>

<!-- HARDWARE SPECIFICATIONS & QUALITY ASSURANCE -->
<section class="py-5 bg-light border-top">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-3">TIER-1 QUALITY ASSURANCE</span>
                <h2 class="font-heading fw-bold text-navy mb-3">Engineered for High Performance & Odisha Climate</h2>
                <p class="text-secondary mb-4">
                    Every installation by Surya Vistaara is powered by tier-1 global manufacturing standards, built specifically to withstand coastal heat, heavy monsoons, and high cyclone wind speeds in Odisha.
                </p>

                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3 border shadow-sm">
                        <div style="background: #0B2545; color: #F59E0B; width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                            <i class="bi bi-sun"></i>
                        </div>
                        <div>
                            <h6 class="font-heading fw-bold text-navy mb-1">DCR Bi-Facial & TOPCon Mono PERC Modules</h6>
                            <p class="text-secondary small mb-0">Domestic Content Requirement (DCR) compliant modules providing up to 22.5% cell efficiency and front/rear double-sided energy generation.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3 border shadow-sm">
                        <div style="background: #0B2545; color: #F59E0B; width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                            <i class="bi bi-wifi"></i>
                        </div>
                        <div>
                            <h6 class="font-heading fw-bold text-navy mb-1">Smart Inverters with Live IoT Cloud Monitoring</h6>
                            <p class="text-secondary small mb-0">Real-time daily electricity generation and grid-export tracking right on your smartphone app.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3 border shadow-sm">
                        <div style="background: #0B2545; color: #F59E0B; width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <h6 class="font-heading fw-bold text-navy mb-1">Cyclone-Resistant Galvanized Mounting</h6>
                            <p class="text-secondary small mb-0">Hot-dip galvanized steel / anodized aluminum structures engineered to withstand wind gusts up to 180 km/h.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-svpl p-4 bg-white border shadow-sm rounded-3">
                    <h4 class="font-heading fw-bold text-navy mb-3 text-center">Solar Savings & Subsidy Calculator</h4>
                    <p class="text-secondary small text-center mb-4">Calculate your lifetime electricity savings under PM Surya Ghar Muft Bijli Yojana Odisha.</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Current Monthly Electricity Bill (₹):</label>
                        <input type="range" class="form-range" min="1000" max="10000" step="500" value="2500" id="calcBillRange" oninput="updateCalculator(this.value)">
                        <div class="d-flex justify-content-between text-navy fw-bold">
                            <span>₹1,000</span>
                            <span class="badge bg-primary fs-6" id="calcBillVal">₹2,500 / month</span>
                            <span>₹10,000</span>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary small">Recommended Capacity:</span>
                            <strong class="text-navy" id="calcCap">3 kW System</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary small">Combined Govt Grant:</span>
                            <strong class="text-success" id="calcSub">₹1,38,000 Subsidy</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary small">Estimated Net Investment:</span>
                            <strong class="text-primary fs-6" id="calcNet">₹72,000</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary small">Annual Electricity Savings:</span>
                            <strong class="text-success" id="calcSav">₹30,000 / year</strong>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-navy">25-Year Lifetime Savings:</span>
                            <span class="fs-5 fw-bold text-success" id="calcTotal">₹7,50,000</span>
                        </div>
                    </div>

                    <a href="<?= url('/contact') ?>" class="btn btn-svpl-solar w-100 py-2 fw-bold shadow-sm">
                        <i class="bi bi-headset me-1"></i> Inquire for This Package via Advisor
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Filter interactive logic
let activeBrand = 'ALL';

document.querySelectorAll('.filter-brand-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-brand-btn').forEach(b => {
            b.classList.remove('active', 'btn-navy');
            b.classList.add('btn-outline-navy');
        });
        this.classList.add('active', 'btn-navy');
        this.classList.remove('btn-outline-navy');
        activeBrand = this.getAttribute('data-brand');
        filterPackages();
    });
});

function filterPackages() {
    const selectedType = document.getElementById('selectSystemType').value;
    const cards = document.querySelectorAll('.package-card-item');
    let visibleCount = 0;

    cards.forEach(card => {
        const cardBrand = card.getAttribute('data-brand');
        const cardType = card.getAttribute('data-type');

        const brandMatch = (activeBrand === 'ALL' || cardBrand === activeBrand);
        const typeMatch = (selectedType === 'ALL' || cardType.indexOf(selectedType) !== -1);

        if (brandMatch && typeMatch) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    const noMsg = document.getElementById('noPackagesMsg');
    if (visibleCount === 0) {
        noMsg.classList.remove('d-none');
    } else {
        noMsg.classList.add('d-none');
    }
}

function updateCalculator(val) {
    document.getElementById('calcBillVal').innerText = '₹' + parseInt(val).toLocaleString() + ' / month';
    var cap = 3;
    var sub = 138000;
    var net = 72000;
    var savYear = parseInt(val) * 12;

    if (val <= 1500) {
        cap = 2;
        sub = 110000;
        net = 50000;
    } else if (val <= 3500) {
        cap = 3;
        sub = 138000;
        net = 72000;
    } else {
        cap = 5;
        sub = 138000;
        net = 192000;
    }

    document.getElementById('calcCap').innerText = cap + ' kW System';
    document.getElementById('calcSub').innerText = '₹' + sub.toLocaleString() + ' Subsidy';
    document.getElementById('calcNet').innerText = '₹' + net.toLocaleString();
    document.getElementById('calcSav').innerText = '₹' + savYear.toLocaleString() + ' / year';
    document.getElementById('calcTotal').innerText = '₹' + (savYear * 25).toLocaleString();
}
</script>
