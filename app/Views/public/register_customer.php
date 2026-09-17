<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Customer Solar Application View
 */
$title = "Apply for PM Surya Ghar Rooftop Solar — SVPL Odisha";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-svpl p-4 p-md-5 shadow-sm border-0" style="border-radius: 16px;">
                <div class="text-center mb-4">
                    <span class="badge bg-success text-white px-3 py-2 fw-bold mb-2">PM SURYA GHAR MUFT BIJLI YOJANA</span>
                    <h2 class="fw-bold" style="color: #0B2545;">Apply for Rooftop Solar Installation in Odisha</h2>
                    <p class="text-muted">Get up to <strong>₹1,38,000 Combined Govt. Subsidy</strong> (₹78,000 Central DBT + ₹60,000 Odisha State Subsidy), reduce your electricity bill to ZERO, and enjoy 25 years of clean power.</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2 px-3 small d-flex align-items-center mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= url('/register-customer') ?>" method="POST">
                    
                    <!-- Advisor Referral -->
                    <?php if ($advisor): ?>
                        <div class="p-3 bg-warning-subtle text-dark rounded-3 mb-4 border border-warning-subtle">
                            <i class="bi bi-person-badge-fill text-warning me-1"></i> You are being assisted by Certified Solar Advisor:
                            <strong><?= htmlspecialchars($advisor['first_name'] . ' ' . $advisor['last_name']) ?></strong> (Code: <code><?= htmlspecialchars($advisor['advisor_code']) ?></code>)
                            <input type="hidden" name="advisor_code" value="<?= htmlspecialchars($advisor['referral_code']) ?>">
                        </div>
                    <?php else: ?>
                        <div class="p-3 bg-light rounded-3 mb-4 border">
                            <label class="form-label small fw-semibold">Referred by an SVPL Solar Advisor? (Optional):</label>
                            <input type="text" name="advisor_code" id="inputReferralCode" class="form-control" placeholder="Enter Advisor Code (e.g. SVPL1001)" value="<?= htmlspecialchars($ref ?? '') ?>">
                            <div id="referralFeedback" class="small mt-1"></div>
                        </div>
                    <?php endif; ?>

                    <!-- Applicant Information -->
                    <h5 class="fw-bold mb-3" style="color: #0B2545; border-bottom: 2px solid #E2E8F0; padding-bottom: 8px;">
                        1. Applicant & Electricity Bill Details
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Customer First Name *</label>
                            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($post['first_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Customer Last Name *</label>
                            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($post['last_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Father's / Husband's Name *</label>
                            <input type="text" name="father_husband_name" class="form-control" placeholder="Father's or Husband's Full Name" value="<?= htmlspecialchars($post['father_husband_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mobile Number *</label>
                            <input type="tel" name="mobile" class="form-control" placeholder="10-digit mobile" value="<?= htmlspecialchars($post['mobile'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" value="<?= htmlspecialchars($post['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Electricity Distribution Company (DISCOM) *</label>
                            <select name="discom_name" class="form-select" required>
                                <option value="TPCODL">TP Central Odisha Distribution Limited (TPCODL)</option>
                                <option value="TPNODL">TP Northern Odisha Distribution Limited (TPNODL)</option>
                                <option value="TPSODL">TP Southern Odisha Distribution Limited (TPSODL)</option>
                                <option value="TPWODL">TP Western Odisha Distribution Limited (TPWODL)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">DISCOM Consumer / CA Number (from bill) *</label>
                            <input type="text" name="consumer_number" class="form-control" placeholder="e.g. TPC-7890124" value="<?= htmlspecialchars($post['consumer_number'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Electricity Bill Holder Name *</label>
                            <input type="text" name="bill_holder_name" class="form-control" placeholder="Name as printed on DISCOM electricity bill" value="<?= htmlspecialchars($post['bill_holder_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check p-2 bg-light rounded border">
                                <input class="form-check-input ms-1" type="checkbox" id="billHolderMatch" required checked>
                                <label class="form-check-label small text-secondary ms-2" for="billHolderMatch">
                                    <strong class="text-navy">Mandatory Verification:</strong> I confirm that the electricity bill holder name matches the solar subsidy applicant.
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Solar Requirement & Package Choice -->
                    <?php
                        if (!isset($packages) || empty($packages)) {
                            $packages = \App\Models\Package::getAllActive();
                        }
                        $groupedPackages = [];
                        foreach ($packages as $pkg) {
                            $b = $pkg['brand'] ?? 'Dhwajja Solar';
                            $groupedPackages[$b][] = $pkg;
                        }
                        $selectedPkgId = $post['package_id'] ?? ($_GET['package_id'] ?? null);
                        $selectedPkgCode = $_GET['pkg'] ?? null;
                    ?>
                    <h5 class="fw-bold mb-3" style="color: #0B2545; border-bottom: 2px solid #E2E8F0; padding-bottom: 8px;">
                        <i class="bi bi-box-seam-fill text-warning me-1"></i> 2. Select Solar Brand Package & Capacity
                    </h5>
                    
                    <div class="row g-3 mb-4">
                        
                        <!-- Package Dropdown -->
                        <div class="col-12">
                            <label class="form-label fw-bold text-navy">
                                Choose Solar Package & Manufacturer <span class="text-danger">*</span>
                            </label>
                            <select name="package_id" id="selectCustomerPackage" class="form-select form-select-lg fw-bold text-navy" required onchange="onPackageChange(this)">
                                <option value="">-- Choose Solar Package --</option>
                                <?php foreach ($groupedPackages as $brandName => $pkgList): ?>
                                    <optgroup label="☀ <?= htmlspecialchars($brandName) ?>">
                                        <?php foreach ($pkgList as $p): ?>
                                            <?php
                                                $isSelected = ($selectedPkgId && $selectedPkgId == $p['id']) ||
                                                              ($selectedPkgCode && $selectedPkgCode === $p['package_code']) ||
                                                              (!$selectedPkgId && !$selectedPkgCode && $p['capacity_kw'] == 3.00 && $p['brand'] === 'Tata Power Solar');
                                            ?>
                                            <option value="<?= $p['id'] ?>"
                                                    data-cap="<?= $p['capacity_kw'] ?>"
                                                    data-gross="<?= (float)$p['total_price'] ?>"
                                                    data-sub="<?= (float)$p['estimated_subsidy'] ?>"
                                                    data-net="<?= (float)$p['net_customer_cost'] ?>"
                                                    data-type="<?= htmlspecialchars($p['system_type']) ?>"
                                                    data-brand="<?= htmlspecialchars($p['brand']) ?>"
                                                    data-title="<?= htmlspecialchars($p['title']) ?>"
                                                    data-features="<?= htmlspecialchars($p['key_features'] ?? '') ?>"
                                                    data-panels="<?= htmlspecialchars($p['panel_type'] ?? '') ?>"
                                                    data-inverter="<?= htmlspecialchars($p['inverter_type'] ?? '') ?>"
                                                    <?= $isSelected ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($p['brand']) ?> — <?= number_format($p['capacity_kw'], 0) ?> kW <?= htmlspecialchars($p['system_type']) ?> | Gross: ₹<?= number_format($p['total_price']) ?> <?= $p['estimated_subsidy'] > 0 ? ('(Net: ₹' . number_format($p['net_customer_cost']) . ')') : '' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Live Selected Package Card -->
                        <div class="col-12" id="pkgLiveCard">
                            <div class="p-3 bg-light rounded-3 border border-warning shadow-sm">
                                <div class="row align-items-center g-2">
                                    <div class="col-md-7">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge bg-warning text-dark fw-bold" id="cardPkgBrand">Brand</span>
                                            <span class="badge bg-primary" id="cardPkgType">On-Grid</span>
                                            <span class="badge bg-secondary" id="cardPkgCap">3 kW</span>
                                        </div>
                                        <h6 class="fw-bold text-navy mb-1" id="cardPkgTitle">Selected System Package</h6>
                                        <div class="small text-secondary" id="cardPkgSpecs">
                                            <span id="cardPkgPanels"></span> • <span id="cardPkgInverter"></span>
                                        </div>
                                        <div class="small text-dark mt-1 fst-italic" id="cardPkgFeatures"></div>
                                    </div>
                                    <div class="col-md-5 text-md-end border-start-md ps-md-3">
                                        <div class="small text-secondary">Gross Amount: <span class="text-decoration-line-through text-dark fw-semibold" id="cardPkgGross">₹0</span></div>
                                        <div class="small text-success fw-bold"><i class="bi bi-gift-fill me-1"></i> PM Surya Ghar Subsidy: <span id="cardPkgSub">- ₹0</span></div>
                                        <hr class="my-1">
                                        <div class="fw-bold text-navy">Net Customer Investment: <span class="fs-5 text-success" id="cardPkgNet">₹0</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Capacity & Load Controls -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Capacity (kW)</label>
                            <input type="number" step="0.5" name="proposed_solar_kw" id="inputProposedKw" class="form-control fw-bold text-success" value="3.0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Sanctioned Load (kW)</label>
                            <input type="number" step="0.5" name="sanctioned_load_kw" class="form-control" value="2.0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Average Monthly Bill (₹)</label>
                            <input type="number" name="monthly_avg_bill" class="form-control" placeholder="e.g. 2500" value="2500">
                        </div>

                    </div>

                    <!-- Location -->
                    <h5 class="fw-bold mb-3" style="color: #0B2545; border-bottom: 2px solid #E2E8F0; padding-bottom: 8px;">
                        3. Installation Address & Land Ownership (Odisha)
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">District *</label>
                            <select name="district" id="selectDistrict" class="form-select select-district" data-initial="<?= htmlspecialchars($post['district'] ?? '') ?>" required>
                                <option value="">Select District</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Block / Municipality *</label>
                            <select name="block" id="selectBlock" class="form-select select-block" data-initial="<?= htmlspecialchars($post['block'] ?? '') ?>" required>
                                <option value="">Select District first</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Gram Panchayat / Ward</label>
                            <input type="text" name="gram_panchayat" id="inputGp" class="form-control select-gp" list="listCustomerGps" placeholder="e.g. Mendhasala" data-initial="<?= htmlspecialchars($post['gram_panchayat'] ?? '') ?>" value="<?= htmlspecialchars($post['gram_panchayat'] ?? '') ?>">
                            <datalist id="listCustomerGps"></datalist>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Village / Locality</label>
                            <input type="text" name="village" id="inputVillage" class="form-control select-village" list="listCustomerVillages" placeholder="Village name" data-initial="<?= htmlspecialchars($post['village'] ?? '') ?>" value="<?= htmlspecialchars($post['village'] ?? '') ?>">
                            <datalist id="listCustomerVillages"></datalist>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Pincode *</label>
                            <input type="text" name="pincode" id="inputPincode" class="form-control input-pincode font-monospace" placeholder="6-digit PIN" maxlength="6" data-initial="<?= htmlspecialchars($post['pincode'] ?? '') ?>" value="<?= htmlspecialchars($post['pincode'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label fw-semibold">Full House / Plot Address</label>
                            <input type="text" name="address_line" class="form-control" placeholder="House No, Street, Landmark" value="<?= htmlspecialchars($post['address_line'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <div class="form-check p-2 bg-light rounded border">
                                <input class="form-check-input ms-1" type="checkbox" id="landPattaCheck" required checked>
                                <label class="form-check-label small text-secondary ms-2" for="landPattaCheck">
                                    <strong class="text-navy">Rooftop / Land Ownership:</strong> I confirm that I have legal ownership / land patta for the proposed solar rooftop premises.
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Security Verification CAPTCHA -->
                    <div class="p-3 bg-light rounded-3 mb-4 border">
                        <label class="form-label small fw-bold text-dark d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-shield-check text-success me-1"></i> Security Verification (CAPTCHA) *</span>
                            <span class="text-muted fw-normal" style="font-size: 0.75rem;">Case-insensitive</span>
                        </label>
                        <div class="row g-2 align-items-center">
                            <div class="col-sm-auto d-flex align-items-center gap-2">
                                <img src="<?= url('/captcha') ?>?t=<?= time() ?>" id="custCaptchaImg" alt="Security Code" style="height: 42px; border-radius: 6px; border: 1px solid #CBD5E1; cursor: pointer;" title="Click to refresh" onclick="this.src='<?= url('/captcha') ?>?t='+new Date().getTime()">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('custCaptchaImg').src='<?= url('/captcha') ?>?t='+new Date().getTime()" title="Get new code">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                            <div class="col-sm">
                                <input type="text" name="captcha" class="form-control text-uppercase font-monospace fw-bold" placeholder="Enter 5-character code" maxlength="6" required autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-svpl-green btn-lg w-100 py-3 fw-bold shadow-sm">
                        <i class="bi bi-send-check-fill me-2"></i> Submit Solar Application & Generate Proposal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function onPackageChange(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (!opt || !opt.value) {
        document.getElementById('pkgLiveCard').style.display = 'none';
        return;
    }

    const brand = opt.getAttribute('data-brand') || '';
    const title = opt.getAttribute('data-title') || '';
    const cap = opt.getAttribute('data-cap') || '3';
    const type = opt.getAttribute('data-type') || 'On-Grid';
    const gross = parseFloat(opt.getAttribute('data-gross')) || 0;
    const sub = parseFloat(opt.getAttribute('data-sub')) || 0;
    const net = parseFloat(opt.getAttribute('data-net')) || 0;
    const features = opt.getAttribute('data-features') || '';
    const panels = opt.getAttribute('data-panels') || '';
    const inverter = opt.getAttribute('data-inverter') || '';

    // Update UI Card
    document.getElementById('cardPkgBrand').innerText = brand;
    document.getElementById('cardPkgType').innerText = type;
    document.getElementById('cardPkgCap').innerText = parseInt(cap) + ' kW Capacity';
    document.getElementById('cardPkgTitle').innerText = title;
    document.getElementById('cardPkgPanels').innerText = panels;
    document.getElementById('cardPkgInverter').innerText = inverter;
    document.getElementById('cardPkgGross').innerText = '₹' + gross.toLocaleString('en-IN');
    document.getElementById('cardPkgSub').innerText = sub > 0 ? ('- ₹' + sub.toLocaleString('en-IN')) : '₹0';
    document.getElementById('cardPkgNet').innerText = '₹' + net.toLocaleString('en-IN');
    
    const featEl = document.getElementById('cardPkgFeatures');
    if (features && features !== 'None') {
        featEl.innerText = '★ ' + features;
        featEl.style.display = 'block';
    } else {
        featEl.style.display = 'none';
    }

    // Update Proposed kW input
    document.getElementById('inputProposedKw').value = parseFloat(cap).toFixed(1);
    document.getElementById('pkgLiveCard').style.display = 'block';
}

// Trigger initial load
document.addEventListener('DOMContentLoaded', function() {
    const pkgSelect = document.getElementById('selectCustomerPackage');
    if (pkgSelect) {
        onPackageChange(pkgSelect);
    }
});
</script>
