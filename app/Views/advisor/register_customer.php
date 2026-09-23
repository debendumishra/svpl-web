<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Advisor Portal - Register New Customer Form (Locked Advisor Referral & Document Uploads)
 */
$title = "Register New Customer — SVPL Advisor Portal";
$advisorName = htmlspecialchars(trim(($advisor['first_name'] ?? '') . ' ' . ($advisor['last_name'] ?? '')));
$advisorCode = htmlspecialchars($advisor['advisor_code'] ?? 'N/A');
$referralCode = htmlspecialchars($advisor['referral_code'] ?? 'N/A');
$district = htmlspecialchars($advisor['district'] ?? 'Khordha');
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 animate-fade-in">
    <div>
        <a href="<?= url('/advisor/customers') ?>" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to My Customers
        </a>
        <h3 class="font-heading fw-bold mb-1 text-navy">
            <i class="bi bi-person-plus-fill text-warning me-2"></i> Register New Solar Customer
        </h3>
        <p class="text-secondary small mb-0">
            Submit complete customer details and upload required KYC documents directly for PM Surya Ghar processing.
        </p>
    </div>
    <span class="badge bg-success-subtle text-success border border-success px-3 py-2 fw-semibold">
        <i class="bi bi-shield-check me-1"></i> Advisor Portal Active
    </span>
</div>

<!-- LOCKED ADVISOR ATTRIBUTION CARD -->
<div class="card card-svpl border-0 shadow-sm p-3 mb-4 bg-navy text-white rounded-3 animate-fade-in stagger-1" style="background: linear-gradient(135deg, #061528 0%, #0B2545 100%);">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900; font-size: 1.25rem;">
                ☀
            </div>
            <div>
                <div class="text-warning small text-uppercase fw-bold letter-spacing-1">Referring Solar Advisor (Locked)</div>
                <div class="fs-5 fw-bold font-heading text-white"><?= $advisorName ?></div>
                <div class="small text-white-50">
                    Advisor Code: <span class="badge bg-light text-dark font-monospace"><?= $advisorCode ?></span> | 
                    Referral Code: <span class="badge bg-warning text-dark font-monospace"><?= $referralCode ?></span> | 
                    Base District: <strong class="text-white"><?= $district ?></strong>
                </div>
            </div>
        </div>
        <div class="text-end d-none d-md-block">
            <span class="badge bg-success text-white px-3 py-2 fw-bold">
                <i class="bi bi-check-circle-fill me-1"></i> 100% Commission Attribution
            </span>
            <div class="small text-white-50 mt-1" style="font-size: 0.75rem;">
                No manual referral code needed. Customer is automatically linked to your account.
            </div>
        </div>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (empty($joiningFeePaid)): ?>
    <!-- LOCKED CUSTOMER REGISTRATION NOTICE -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 border-start border-4 border-warning bg-warning-subtle">
        <div class="card-body p-4">
            <div class="d-flex align-items-start gap-3">
                <div class="p-3 bg-warning text-dark rounded-circle flex-shrink-0">
                    <i class="bi bi-lock-fill fs-3"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fw-bold text-navy mb-1">Customer Registration Locked</h5>
                    <p class="text-secondary small mb-3">
                        You are registered on the <strong>Free Advisor Network Tier</strong>. While you are free to refer advisors and build your 9-level network, registering rooftop solar customers and earning project commissions requires payment and admin approval of your one-time Registration Fee of <strong>₹<?= number_format((float)($joiningFeeAmount ?? advisor_joining_fee())) ?></strong>.
                    </p>

                    <?php if (!empty($pendingJoiningPayment)): ?>
                        <div class="p-3 bg-white rounded-3 border mb-3 small">
                            <div class="fw-bold text-primary mb-1"><i class="bi bi-hourglass-split me-1"></i> Payment Verification in Progress</div>
                            <div>Your payment of <strong>₹<?= number_format((float)$pendingJoiningPayment['amount']) ?></strong> (UTR: <span class="font-monospace fw-bold"><?= htmlspecialchars($pendingJoiningPayment['transaction_ref']) ?></span>) submitted on <?= htmlspecialchars($pendingJoiningPayment['payment_date']) ?> is currently awaiting verification by SVPL Accounts. Once confirmed, you can register customers immediately.</div>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= url('/advisor/dashboard') ?>" class="btn btn-warning text-dark fw-bold btn-sm shadow-sm">
                            <i class="bi bi-credit-card me-1"></i> Submit / View Registration Fee on Dashboard
                        </a>
                        <a href="<?= url('/advisor/my-network') ?>" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-diagram-3 me-1"></i> Build Advisor Network
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<form action="<?= url('/advisor/register-customer') ?>" method="POST" enctype="multipart/form-data" class="animate-fade-in stagger-2" id="advisorCustomerForm">
    <!-- Hidden Locked Referral Code -->
    <input type="hidden" name="advisor_code" value="<?= $referralCode ?>">

    <!-- SECTION 1: CUSTOMER PERSONAL DETAILS -->
    <div class="card card-svpl bg-white border-0 shadow-sm p-4 mb-4 rounded-3">
        <h5 class="font-heading fw-bold text-navy mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
            <span class="badge bg-primary rounded-circle px-2 py-1 fs-6">1</span>
            Customer Personal Details
        </h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Customer First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" class="form-control" placeholder="e.g. Ramesh" value="<?= htmlspecialchars($post['first_name'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Customer Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" class="form-control" placeholder="e.g. Mohanty" value="<?= htmlspecialchars($post['last_name'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Father's / Husband's Name <span class="text-danger">*</span></label>
                <input type="text" name="father_husband_name" class="form-control" placeholder="Father or Husband Full Name" value="<?= htmlspecialchars($post['father_husband_name'] ?? '') ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Primary Mobile Number (WhatsApp) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-secondary font-monospace">+91</span>
                    <input type="tel" name="mobile" class="form-control font-monospace" placeholder="10-digit Mobile" pattern="[6-9][0-9]{9}" maxlength="10" value="<?= htmlspecialchars($post['mobile'] ?? '') ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Alternate Mobile (Optional)</label>
                <input type="tel" name="alt_mobile" class="form-control font-monospace" placeholder="Alternate Mobile" pattern="[6-9][0-9]{9}" maxlength="10" value="<?= htmlspecialchars($post['alt_mobile'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Email Address (Optional)</label>
                <input type="email" name="email" class="form-control" placeholder="customer@gmail.com" value="<?= htmlspecialchars($post['email'] ?? '') ?>">
            </div>
        </div>
    </div>

    <!-- SECTION 2: INSTALLATION ADDRESS & LOCATION -->
    <div class="card card-svpl bg-white border-0 shadow-sm p-4 mb-4 rounded-3">
        <h5 class="font-heading fw-bold text-navy mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
            <span class="badge bg-primary rounded-circle px-2 py-1 fs-6">2</span>
            Installation Address & Panchayat Details
        </h5>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-navy">State</label>
                <input type="text" name="state" class="form-control bg-light" value="Odisha" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-navy">District <span class="text-danger">*</span></label>
                <select name="district" id="selectAdvisorCustDistrict" class="form-select select-district" data-initial="<?= htmlspecialchars($post['district'] ?? $advisor['district'] ?? 'Khordha') ?>" required>
                    <option value="">-- Select District --</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-navy">Block / Subdivision <span class="text-danger">*</span></label>
                <select name="block" id="selectAdvisorCustBlock" class="form-select select-block" data-initial="<?= htmlspecialchars($post['block'] ?? $advisor['block'] ?? '') ?>" required>
                    <option value="">-- Select Block --</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-navy">Gram Panchayat <span class="text-danger">*</span></label>
                <input type="text" name="gram_panchayat" id="inputAdvisorCustGp" class="form-control select-gp" list="listAdvisorCustGps" placeholder="e.g. Benupur GP" data-initial="<?= htmlspecialchars($post['gram_panchayat'] ?? '') ?>" value="<?= htmlspecialchars($post['gram_panchayat'] ?? '') ?>" required>
                <datalist id="listAdvisorCustGps"></datalist>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Village / Town <span class="text-danger">*</span></label>
                <input type="text" name="village" id="inputAdvisorCustVillage" class="form-control select-village" list="listAdvisorCustVillages" placeholder="e.g. Hanspal Village" data-initial="<?= htmlspecialchars($post['village'] ?? '') ?>" value="<?= htmlspecialchars($post['village'] ?? '') ?>" required>
                <datalist id="listAdvisorCustVillages"></datalist>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-navy">Pincode <span class="text-danger">*</span></label>
                <input type="text" name="pincode" id="inputAdvisorCustPincode" class="form-control input-pincode font-monospace" placeholder="6-digit PIN" pattern="[0-9]{6}" maxlength="6" data-initial="<?= htmlspecialchars($post['pincode'] ?? $advisor['pincode'] ?? '') ?>" value="<?= htmlspecialchars($post['pincode'] ?? $advisor['pincode'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-navy">Full House / Plot / Street Address <span class="text-danger">*</span></label>
                <input type="text" name="address_line" class="form-control" placeholder="House No, Landmark, Street Name" value="<?= htmlspecialchars($post['address_line'] ?? '') ?>" required>
            </div>
        </div>
    </div>

    <!-- SECTION 3: ELECTRICITY CONNECTION & SOLAR CAPACITY -->
    <div class="card card-svpl bg-white border-0 shadow-sm p-4 mb-4 rounded-3">
        <h5 class="font-heading fw-bold text-navy mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
            <span class="badge bg-primary rounded-circle px-2 py-1 fs-6">3</span>
            DISCOM Electricity & Proposed Solar Plant
        </h5>
        <div class="row g-3">
            <?php 
                $discomProviders = \App\Models\Discom::getAllActive();
                $districtDiscomMap = \App\Models\Discom::getDistrictLookupMap();
            ?>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Assigned DISCOM Provider <span class="text-danger">*</span></label>
                <select name="discom_name" id="selectAdvisorCustDiscom" class="form-select fw-bold" required>
                    <option value="">-- Select DISCOM Provider --</option>
                    <?php foreach ($discomProviders as $dp): ?>
                        <option value="<?= htmlspecialchars($dp['code']) ?>" <?= (($post['discom_name'] ?? '') === $dp['code']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dp['name']) ?> (<?= htmlspecialchars($dp['code']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="mt-1" id="advisorDiscomAutoBadge">
                    <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Automatically detected when District is selected.</small>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Electricity Consumer / CA Number <span class="text-danger">*</span></label>
                <input type="text" name="consumer_number" class="form-control font-monospace fw-bold text-primary" placeholder="Found on Electricity Bill" value="<?= htmlspecialchars($post['consumer_number'] ?? '') ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">
                    Mobile No. as per Electricity Bill <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-secondary font-monospace">+91</span>
                    <input type="tel" name="electricity_bill_mobile" id="inputAdvisorBillMobile" class="form-control font-monospace" placeholder="10-digit Mobile on Bill" pattern="[6-9][0-9]{9}" maxlength="10" value="<?= htmlspecialchars($post['electricity_bill_mobile'] ?? $post['mobile'] ?? '') ?>" required>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyPrimaryMobileToBill()" title="Copy Primary Mobile">
                        <i class="bi bi-arrow-down-left-square"></i> Same
                    </button>
                </div>
                <div class="form-text small" style="font-size: 0.72rem;">Registered contact on DISCOM record.</div>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">
                    Date of Birth as per Electricity Bill <span class="text-danger">*</span>
                </label>
                <input type="date" name="electricity_bill_dob" id="inputAdvisorBillDob" class="form-control fw-semibold" value="<?= htmlspecialchars($post['electricity_bill_dob'] ?? $post['dob'] ?? '') ?>" required max="<?= date('Y-m-d', strtotime('-18 years')) ?>">
                <div class="form-text small" style="font-size: 0.72rem;">As recorded in official DISCOM connection.</div>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Existing Sanctioned Load (kW)</label>
                <select name="sanctioned_load_kw" class="form-select">
                    <option value="1.0">1 kW Single Phase</option>
                    <option value="2.0" selected>2 kW Single Phase</option>
                    <option value="3.0">3 kW Single Phase</option>
                    <option value="5.0">5 kW Three Phase</option>
                    <option value="10.0">10 kW Three Phase</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Average Monthly Bill (₹)</label>
                <input type="number" name="monthly_avg_bill" class="form-control" placeholder="e.g. 2500" value="<?= htmlspecialchars($post['monthly_avg_bill'] ?? '2500') ?>">
            </div>

            <!-- Package Selection (All Vendor Brands) -->
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
            <div class="col-12">
                <label class="form-label small fw-bold text-navy">
                    <i class="bi bi-box-seam-fill text-warning me-1"></i> Choose Solar Package & Vendor Brand <span class="text-danger">*</span>
                </label>
                <select name="package_id" id="selectAdvisorCustPackage" class="form-select form-select-lg fw-bold text-navy" required onchange="onAdvisorPackageChange(this)">
                    <option value="">-- Select Manufacturer & Package --</option>
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
            <div class="col-12" id="advisorPkgLiveCard">
                <div class="p-3 bg-light rounded-3 border border-warning shadow-sm">
                    <div class="row align-items-center g-2">
                        <div class="col-md-7">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-warning text-dark fw-bold" id="advCardPkgBrand">Brand</span>
                                <span class="badge bg-primary" id="advCardPkgType">On-Grid</span>
                                <span class="badge bg-secondary" id="advCardPkgCap">3 kW</span>
                            </div>
                            <h6 class="fw-bold text-navy mb-1" id="advCardPkgTitle">Selected System Package</h6>
                            <div class="small text-secondary" id="advCardPkgSpecs">
                                <span id="advCardPkgPanels"></span> • <span id="advCardPkgInverter"></span>
                            </div>
                            <div class="small text-dark mt-1 fst-italic" id="advCardPkgFeatures"></div>
                        </div>
                        <div class="col-md-5 text-md-end border-start-md ps-md-3">
                            <div class="small text-secondary">Gross System Cost: <span class="text-decoration-line-through text-dark fw-semibold" id="advCardPkgGross">₹0</span></div>
                            <div class="small text-success fw-bold"><i class="bi bi-gift-fill me-1"></i> PM Surya Ghar Subsidy: <span id="advCardPkgSub">- ₹0</span></div>
                            <hr class="my-1">
                            <div class="fw-bold text-navy">Net Customer Payable: <span class="fs-5 text-success" id="advCardPkgNet">₹0</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Proposed Capacity (kW)</label>
                <input type="number" step="0.5" name="proposed_solar_kw" id="advInputProposedKw" class="form-control fw-bold text-success" value="3.0" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Average Monthly Electricity Bill (₹)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">₹</span>
                    <input type="number" name="monthly_avg_bill" class="form-control" placeholder="e.g. 2500" value="<?= htmlspecialchars($post['monthly_avg_bill'] ?? '2500') ?>">
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Roof Structure Type</label>
                <select name="roof_type" class="form-select">
                    <option value="RCC Concrete Roof" selected>RCC Flat Concrete Roof</option>
                    <option value="Slanted Tin / Sheet Roof">Slanted Tin / Metal Sheet</option>
                    <option value="Elevated Rooftop Structure">Elevated Rooftop Structure</option>
                    <option value="Ground Mounted / Boundary">Ground Mounted / Boundary Space</option>
                </select>
            </div>

            <div class="col-12">
                <div class="form-check p-3 bg-light rounded border">
                    <input class="form-check-input ms-0 me-2" type="checkbox" id="billHolderCheck" name="bill_holder_verified" value="1" required checked>
                    <label class="form-check-label small fw-bold text-navy" for="billHolderCheck">
                        <i class="bi bi-shield-check text-success me-1"></i> Electricity Bill Holder Confirmation:
                    </label>
                    <div class="small text-secondary ps-4">
                        I confirm that the Electricity Bill is in the Customer's name (or solar applicant is the legal owner/occupant of this property) and the meter is active.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: DIGITAL CUSTOMER DOCUMENT UPLOADS (DIRECT SUBMISSION) -->
    <div class="card card-svpl bg-white border-0 shadow-sm p-4 mb-4 rounded-3">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
            <h5 class="font-heading fw-bold text-navy mb-0 d-flex align-items-center gap-2">
                <span class="badge bg-primary rounded-circle px-2 py-1 fs-6">4</span>
                Customer KYC & Technical Document Uploads
            </h5>
            <span class="badge bg-warning text-dark small">
                <i class="bi bi-info-circle me-1"></i> Accepted formats: PDF, JPG, PNG (Max 5MB each)
            </span>
        </div>
        <p class="text-secondary small mb-3">
            Advisors can upload all customer documents directly here. No WhatsApp transmission required.
        </p>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <label class="form-label small fw-bold text-navy">
                        <i class="bi bi-receipt text-primary me-1"></i> 1. Latest Electricity Bill <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="doc_electricity_bill" class="form-control form-control-sm" accept="image/*,.pdf" capture="environment">
                    <div class="form-text small" style="font-size: 0.72rem;">Clear scan or camera photo showing Consumer Number & Address.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <label class="form-label small fw-bold text-navy">
                        <i class="bi bi-person-badge text-danger me-1"></i> 2. Customer Aadhaar Card (Front & Back) <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="doc_aadhaar_card" class="form-control form-control-sm" accept="image/*,.pdf" capture="environment">
                    <div class="form-text small" style="font-size: 0.72rem;">Customer Identity and address proof for PM Surya Ghar DBT linkage.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <label class="form-label small fw-bold text-navy">
                        <i class="bi bi-person-bounding-box text-info me-1"></i> 3. Customer Passport Size Photo <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="doc_passport_photo" class="form-control form-control-sm" accept="image/*" capture="user">
                    <div class="form-text small" style="font-size: 0.72rem;">Click camera selfie/portrait photo of customer for official records.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <label class="form-label small fw-bold text-navy">
                        <i class="bi bi-bank text-success me-1"></i> 4. Bank Passbook / Cancelled Cheque <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="doc_bank_passbook" class="form-control form-control-sm" accept="image/*,.pdf" capture="environment">
                    <div class="form-text small" style="font-size: 0.72rem;">Account where central DBT subsidy (₹78k) & state subsidy (₹60k) will be credited.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <label class="form-label small fw-bold text-navy">
                        <i class="bi bi-camera-fill text-primary me-1"></i> 5. Rooftop / Site Photograph <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="doc_roof_photo" class="form-control form-control-sm" accept="image/*,.pdf" capture="environment">
                    <div class="form-text small" style="font-size: 0.72rem;">Snap camera photo of customer's roof showing shadow-free installation area.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <label class="form-label small fw-bold text-navy">
                        <i class="bi bi-house text-warning me-1"></i> 6. Land Patta / Ownership / Holding Tax Document
                    </label>
                    <input type="file" name="doc_land_patta" class="form-control form-control-sm" accept="image/*,.pdf" capture="environment">
                    <div class="form-text small" style="font-size: 0.72rem;">Proof of building ownership or Municipal holding tax receipt.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <label class="form-label small fw-bold text-navy">
                        <i class="bi bi-card-text text-secondary me-1"></i> 7. Customer PAN Card (Optional)
                    </label>
                    <input type="file" name="doc_pan_card" class="form-control form-control-sm" accept="image/*,.pdf" capture="environment">
                    <div class="form-text small" style="font-size: 0.72rem;">Required for bank loan disbursement and larger installations.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <label class="form-label small fw-bold text-navy">
                        <i class="bi bi-file-earmark-plus text-secondary me-1"></i> 8. Other Supporting Documents (Optional)
                    </label>
                    <input type="file" name="doc_other" class="form-control form-control-sm" accept="image/*,.pdf" capture="environment">
                    <div class="form-text small" style="font-size: 0.72rem;">Additional site photos, NOC, or supporting discom documents.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 5: CUSTOMER E-SIGNATURE & VISIBLE 4-PAGE AGREEMENT (ANNEXURE 2) -->
    <div class="card card-svpl bg-white border-0 shadow-sm p-4 mb-4 rounded-3 border-start border-4 border-primary">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
            <div>
                <h5 class="font-heading fw-bold text-navy mb-0 d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-circle px-2 py-1 fs-6">5</span>
                    Customer E-Signature & PM Surya Ghar Model Draft Agreement (Annexure 2)
                </h5>
                <span class="text-secondary small">Mandatory legal contract executed between consumer and Dhwajja Solar India Pvt. Ltd.</span>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAgreementPreview">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Fullscreen Agreement Modal
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Signature Pad & Consent Gate -->
            <div class="col-lg-5">
                <div class="p-3 bg-light rounded-3 border h-100 d-flex flex-column justify-content-between">
                    <div>
                        <label class="form-label small fw-bold text-navy d-flex justify-content-between align-items-center mb-2">
                            <span><i class="bi bi-pen-fill text-primary me-1"></i> Capture Customer E-Signature <span class="text-danger">*</span></span>
                            <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="clearSignatureCanvas()">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Clear / Redo
                            </button>
                        </label>
                        
                        <div class="border rounded-3 p-2 bg-white text-center position-relative shadow-sm">
                            <canvas id="advisorSignaturePad" style="width: 100%; height: 160px; background: #ffffff; border: 1px dashed #94a3b8; border-radius: 6px; cursor: crosshair; touch-action: none;"></canvas>
                            <div class="d-flex justify-content-between align-items-center text-muted small mt-1 px-1" style="font-size: 0.72rem;">
                                <span><i class="bi bi-fingerprint text-primary me-1"></i> Touch / Stylus / Mouse</span>
                                <span class="text-success fw-semibold" id="sigDrawnStatusBadge"><i class="bi bi-pencil me-1"></i> Sign above</span>
                            </div>
                        </div>
                        <input type="hidden" name="customer_signature_base64" id="inputAdvisorSignatureBase64" value="">

                        <div class="alert alert-info py-2 px-3 mt-3 mb-3 small d-flex align-items-start gap-2" style="font-size: 0.78rem;">
                            <i class="bi bi-info-circle-fill text-primary fs-6 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>Live Agreement Stamping:</strong> The signature drawn above is stamped in real-time on <strong>all 4 pages</strong> of the legal agreement displayed on the right.
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-top">
                        <div class="form-check p-2 bg-white rounded border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="agreement_accepted" id="checkAgreementAccepted" value="1" required checked>
                            <label class="form-check-label small fw-bold text-navy" for="checkAgreementAccepted">
                                I confirm that the consumer has read/accepted the 4-page PM Surya Ghar Model Draft Agreement (Annexure 2) and authorized this registration with digital signature. <span class="text-danger">*</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Live Visible 4-Page Agreement Viewer -->
            <div class="col-lg-7">
                <div class="card border border-2 shadow-sm rounded-3 overflow-hidden h-100" style="border-color: #cbd5e1 !important;">
                    <div class="card-header bg-navy text-white px-3 py-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-text-fill text-warning"></i>
                            <span class="fw-bold small font-outfit">Live Agreement Document Preview (Annexure 2)</span>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle py-1" style="font-size: 0.68rem;">
                            <i class="bi bi-check-circle-fill me-1"></i> Auto-Populated
                        </span>
                    </div>

                    <div class="card-body p-3 overflow-auto" style="max-height: 400px; background-color: #f8fafc; font-family: 'Times New Roman', serif; font-size: 11.5px; line-height: 1.45; color: #1e293b;">
                        
                        <!-- Page 1 Preview -->
                        <div class="p-3 bg-white border rounded shadow-sm mb-3 position-relative">
                            <div class="text-center mb-2">
                                <strong style="font-size: 13px;">Annexure 2</strong><br>
                                <strong class="text-uppercase" style="font-size: 11px;">Model Draft Agreement between Consumer & Vendor for installation of grid connected rooftop solar (RTS) project under PM Surya Ghar: Muft Bijli Yojana</strong>
                            </div>
                            <p class="mb-2">
                                This agreement is executed on <strong><?= date('d/m/Y') ?></strong> for design, supply, installation, commissioning and 5-year comprehensive maintenance of RTS project/system along with warranty under PM Surya Ghar: Muft Bijli Yojana.
                            </p>
                            <div class="text-center fw-bold my-1">Between</div>
                            <div class="mb-2 p-2 bg-light rounded border">
                                <div><strong class="adv-preview-custname text-primary">[CUSTOMER NAME]</strong> S/O, W/O, D/O <strong class="adv-preview-careof">[FATHER/HUSBAND NAME]</strong></div>
                                <div>AT - <strong class="adv-preview-village">[VILLAGE]</strong>, PO - <strong class="adv-preview-block">[BLOCK]</strong>, DIST - <strong class="adv-preview-district text-uppercase">[DISTRICT]</strong>, PIN - <strong class="adv-preview-pin">[PINCODE]</strong>, ODISHA</div>
                                <div>Existing Consumer Number: <strong class="adv-preview-consumerno text-primary">[CONSUMER NO]</strong> | Notification No: <strong>PMSGY/OD/<?= date('Y') ?>/PENDING</strong></div>
                            </div>
                            <div class="text-center fw-bold my-1">AND</div>
                            <div class="mb-2">
                                COMPANY - <strong>DHWAJJA SOLAR INDIA PRIVATE LIMITED</strong>, MIG-84, Pokhariput, BDA Colony, Phase-1, Pokhariput, Bhubaneswar, Khordha - 751020, Odisha (Second Party i.e. Vendor).
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2 small text-muted" style="font-size: 10px;">
                                <span>Guidelines for PM-Surya Ghar: Muft Bijli Yojana</span>
                                <div class="text-end">
                                    <div class="adv-live-esign-badge border p-1 rounded bg-light d-inline-block text-center mb-1">
                                        <img class="live-agreement-esign-img" src="" style="display:none; max-height: 28px; max-width: 90px;" alt="E-Sign">
                                        <span class="live-agreement-esign-placeholder text-secondary" style="font-size: 9px;">[Consumer E-Sign Pending]</span>
                                    </div>
                                    <div>Page 1 of 4</div>
                                </div>
                            </div>
                        </div>

                        <!-- Page 2 Preview -->
                        <div class="p-3 bg-white border rounded shadow-sm mb-3 position-relative">
                            <div class="fw-bold mb-1">Undertakings & Obligations:</div>
                            <p class="mb-1"><strong>First Party (Consumer) Undertakes:</strong> 1. Online application on National Portal, 2. Secure material storage at site, 3. Rooftop access for installation, 4. Electricity & water for testing/cleaning, 5. 5-Year malfunction reporting, 6. Timely milestone payment.</p>
                            <p class="mb-1"><strong>Second Party (Dhwajja Solar) Undertakes:</strong> 1. Compliance with MNRE & DISCOM safety standards, 2. Detailed roof feasibility & shadow study, 3. Design & SLD engineering, 4. ALMM/MNRE approved Mono-PERC panels & Inverters.</p>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2 small text-muted" style="font-size: 10px;">
                                <span>Central Financial Assistance to Residential RTS</span>
                                <div class="text-end">
                                    <div class="adv-live-esign-badge border p-1 rounded bg-light d-inline-block text-center mb-1">
                                        <img class="live-agreement-esign-img" src="" style="display:none; max-height: 28px; max-width: 90px;" alt="E-Sign">
                                        <span class="live-agreement-esign-placeholder text-secondary" style="font-size: 9px;">[Consumer E-Sign Pending]</span>
                                    </div>
                                    <div>Page 2 of 4</div>
                                </div>
                            </div>
                        </div>

                        <!-- Page 3 Preview -->
                        <div class="p-3 bg-white border rounded shadow-sm mb-3 position-relative">
                            <div class="fw-bold mb-1">Technical Specifications & 5-Year O&M (Clauses 5 to 17):</div>
                            <p class="mb-1"><strong>Clause 9 Warranty:</strong> Complete 5-Year Comprehensive System Warranty from date of DISCOM synchronization.</p>
                            <p class="mb-1"><strong>Clause 10 Net Metering:</strong> Net meter procurement, testing, and DISCOM grid integration in scope of vendor.</p>
                            <p class="mb-1"><strong>Clause 12 O&M:</strong> 5 Years comprehensive maintenance, wear & tear overhaul, and consumer training.</p>
                            <p class="mb-1"><strong>Clause 17 Subsidy:</strong> Vendor assistance for swift National Portal DBT subsidy claim release.</p>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2 small text-muted" style="font-size: 10px;">
                                <span>PM Surya Ghar Model Draft Agreement</span>
                                <div class="text-end">
                                    <div class="adv-live-esign-badge border p-1 rounded bg-light d-inline-block text-center mb-1">
                                        <img class="live-agreement-esign-img" src="" style="display:none; max-height: 28px; max-width: 90px;" alt="E-Sign">
                                        <span class="live-agreement-esign-placeholder text-secondary" style="font-size: 9px;">[Consumer E-Sign Pending]</span>
                                    </div>
                                    <div>Page 3 of 4</div>
                                </div>
                            </div>
                        </div>

                        <!-- Page 4 Preview: Payment & Execution Blocks -->
                        <div class="p-3 bg-white border rounded shadow-sm position-relative">
                            <div class="fw-bold mb-1">Clause 19: Mutually Agreed Terms of Payment:</div>
                            <p class="mb-1">a. After Supply of materials at site – <strong>90% of Project Cost</strong></p>
                            <p class="mb-2">b. After Installation & DISCOM Commissioning – <strong>10% Final Value</strong></p>
                            
                            <div class="row g-2 border border-dark p-2 mt-2 bg-light">
                                <div class="col-6 border-end pe-2">
                                    <div class="fw-bold">First Party (Consumer):</div>
                                    <div class="adv-preview-custname text-primary fw-bold">[CUSTOMER NAME]</div>
                                    <div class="mt-1 d-flex align-items-center gap-1">
                                        <span>Sign:</span>
                                        <img class="live-agreement-esign-img" src="" style="display:none; max-height: 32px; max-width: 100px;" alt="E-Sign">
                                        <span class="live-agreement-esign-placeholder text-secondary" style="font-size: 9px;">[Pending Signature]</span>
                                    </div>
                                    <div>Date: <?= date('d/m/Y') ?></div>
                                </div>
                                <div class="col-6 ps-2">
                                    <div class="fw-bold">Second Party (Vendor):</div>
                                    <div class="fw-bold text-navy">DHWAJJA SOLAR INDIA PVT LTD</div>
                                    <div class="mt-1 d-flex align-items-center gap-1">
                                        <span>Sign:</span>
                                        <img src="<?= function_exists('company_signature_url') ? company_signature_url() : '/assets/images/authorised_signatory.png' ?>" alt="Vendor Sign" style="max-height: 28px; max-width: 90px;">
                                    </div>
                                    <div>Date: <?= date('d/m/Y') ?></div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2 small text-muted" style="font-size: 10px;">
                                <span>Execution & Handover Block</span>
                                <div class="text-end">
                                    <div class="adv-live-esign-badge border p-1 rounded bg-light d-inline-block text-center mb-1">
                                        <img class="live-agreement-esign-img" src="" style="display:none; max-height: 28px; max-width: 90px;" alt="E-Sign">
                                        <span class="live-agreement-esign-placeholder text-secondary" style="font-size: 9px;">[Consumer E-Sign Pending]</span>
                                    </div>
                                    <div>Page 4 of 4</div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-light px-3 py-2 d-flex justify-content-between align-items-center">
                        <small class="text-muted"><i class="bi bi-shield-check text-success me-1"></i> Conforms to MNRE & OREDA Annexure 2 Guidelines</small>
                        <button type="button" class="btn btn-outline-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalAgreementPreview">
                            <i class="bi bi-arrows-fullscreen me-1"></i> Expand Fullscreen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SUBMISSION CARD -->
    <div class="card card-svpl bg-white border-0 shadow-sm p-4 mb-4 rounded-3 text-center">
        <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
            <a href="<?= url('/advisor/customers') ?>" class="btn btn-outline-secondary px-4 py-2">
                Cancel
            </a>
            <button type="submit" class="btn btn-svpl-solar btn-lg px-5 py-2 fw-bold shadow" id="btnSubmitAdvisorCustomer">
                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Submit Customer Application & Upload Documents
            </button>
        </div>
        <div class="small text-secondary mt-3">
            <i class="bi bi-lock-fill text-success me-1"></i> 
            Application will be submitted to the SVPL Document Verification desk under your Advisor account.
        </div>
    </div>
</form>

<!-- MODAL: FULLSCREEN 4-PAGE MODEL DRAFT AGREEMENT (ANNEXURE 2) - PLACED AT ROOT OUTSIDE FORM -->
<div class="modal fade" id="modalAgreementPreview" tabindex="-1" aria-labelledby="modalAgreementPreviewLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 950px; z-index: 1065;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="z-index: 1070; pointer-events: auto;">
            <div class="modal-header bg-navy text-white px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-heading fw-bold mb-0 text-white" id="modalAgreementPreviewLabel">
                            PM Surya Ghar Model Draft Agreement (Annexure 2)
                        </h5>
                        <span class="text-white-50 small" style="font-size: 0.75rem;">Official 4-Page Tripartite Consumer-Vendor Contract</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background-color: #f1f5f9; font-family: 'Times New Roman', Times, serif; font-size: 13px; line-height: 1.5; color: #000; max-height: calc(100vh - 170px); overflow-y: auto !important; -webkit-overflow-scrolling: touch;">
                
                <!-- Page 1 -->
                <div class="bg-white p-4 p-md-5 mb-4 rounded shadow-sm border">
                    <div class="text-center mb-4">
                        <h5 class="fw-bold mb-2">Annexure 2</h5>
                        <h5 class="fw-bold text-uppercase px-2" style="font-size: 14px; line-height: 1.4;">
                            Model Draft Agreement between Consumer & Vendor for installation of grid connected rooftop solar (RTS) project under PM Surya Ghar: Muft Bijli Yojana
                        </h5>
                    </div>
                    <p class="text-justify mb-4">
                        This agreement is executed on <strong><?= date('d') ?>/<?= date('m') ?>/<?= date('Y') ?></strong> for design, supply, installation, commissioning and 5-year comprehensive maintenance of RTS project/system along with warranty under PM Surya Ghar: Muft Bijli Yojana.
                    </p>
                    <div class="text-center fw-bold my-3" style="font-size: 14px;">Between</div>
                    <div class="mb-4 p-3 bg-light rounded border">
                        <div><strong class="adv-preview-custname text-primary">[CUSTOMER NAME]</strong> /O - <strong class="adv-preview-careof">[FATHER/HUSBAND NAME]</strong></div>
                        <div>AT - <strong class="adv-preview-village">[VILLAGE]</strong> PO - <strong class="adv-preview-block">[BLOCK]</strong></div>
                        <div>DIST - <strong class="adv-preview-district text-uppercase">[DISTRICT]</strong> PINCODE - <strong class="adv-preview-pin">[PINCODE]</strong> ODISHA</div>
                        <div>(Herein after called as 'The eligible consumer') Existing consumer number - <strong class="adv-preview-consumerno text-primary">[CONSUMER NO]</strong></div>
                        <div>& Notification number: <strong>PMSGY/OD/<?= date('Y') ?>/PENDING</strong></div>
                    </div>
                    <div class="text-center fw-bold my-3" style="font-size: 14px;">AND</div>
                    <div class="mb-4 text-justify">
                        COMPANY - <strong>DHWAJJA SOLAR INDIA PRIVATE LIMITED</strong>, MIG-84, POKHARIPUT, BDA COLONY, PHASE-1, Pokhariput, Bhubaneswar, Khorda - 751020, Odisha (Hereinafter referred to as Second Party i.e. Vendor/Contractor/System Integrator).
                    </div>
                    <div class="d-flex justify-content-between align-items-end pt-3 border-top mt-4 text-muted" style="font-size: 11px;">
                        <div>Guidelines for PM-Surya Ghar: Muft Bijli Yojana<br>Central Financial Assistance to Residential</div>
                        <div class="text-end">
                            <div class="adv-live-esign-badge border p-1 rounded bg-light d-inline-block text-center mb-1">
                                <img class="live-agreement-esign-img" src="" style="display:none; max-height: 36px; max-width: 120px;" alt="E-Sign">
                                <span class="live-agreement-esign-placeholder text-secondary" style="font-size: 9px;">[Consumer E-Sign Pending]</span>
                            </div>
                            <div class="fw-bold">1</div>
                        </div>
                    </div>
                </div>

                <!-- Page 2 -->
                <div class="bg-white p-4 p-md-5 mb-4 rounded shadow-sm border">
                    <div class="mb-3">
                        <div class="fw-bold mb-1">Whereas</div>
                        <div class="text-justify">First Party wishes to install a Grid Connected Rooftop Solar Plant on the rooftop of the residential building of the consumer under PM Surya Ghar: Muft Bijli Yojana;</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold mb-1">And whereas</div>
                        <div class="text-justify">Second Party has verified availability of appropriate roof and found it feasible to install a Grid Connected Rooftop Solar Plant and that the Second Party is willing to design, supply, install, test, commission and carry out operation and maintenance of the Rooftop Solar Plant for 5 year period. On this day, the First Party and Second Party agree to the following:</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold mb-1">The First Party hereby undertakes to perform the following activities:</div>
                        <p class="mb-1"><strong>1.</strong> Submission of online application on the National Portal for installation of RTS project/system, Net-Metering, system inspection, and uploading relevant documents.</p>
                        <p class="mb-1"><strong>2.</strong> Provide secure storage for RTS materials delivered at premises till handover of system.</p>
                        <p class="mb-1"><strong>3.</strong> Provide access to rooftop during installation, O&M, testing, and meter reading.</p>
                        <p class="mb-1"><strong>4.</strong> Provide electricity during plant installation and water for cleaning of panels.</p>
                        <p class="mb-1"><strong>5.</strong> Report any malfunctioning to vendor during warranty period.</p>
                        <p class="mb-1"><strong>6.</strong> Pay the amount as per the mutually agreed payment schedule.</p>
                    </div>
                    <div class="mb-2">
                        <div class="fw-bold mb-1">The Second Party hereby undertakes to perform the following activities:</div>
                        <p class="mb-1"><strong>1.</strong> Compliance with all standards and safety guidelines prescribed under state regulations and technical standards prescribed by MNRE.</p>
                        <p class="mb-1"><strong>2. Site Survey:</strong> Site visit, shadow analysis, and detailed project report preparation.</p>
                        <p class="mb-1"><strong>3. Design and Engineering:</strong> System engineering adhering to DISCOM/SERC/MNRE safety norms.</p>
                        <p class="mb-1"><strong>4. Module and Inverter:</strong> Domestic Content Requirement (DCR) Mono PERC modules and BIS inverters.</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-end pt-3 border-top mt-4 text-muted" style="font-size: 11px;">
                        <div>Guidelines for PM-Surya Ghar: Muft Bijli Yojana<br>Central Financial Assistance to Residential</div>
                        <div class="text-end">
                            <div class="adv-live-esign-badge border p-1 rounded bg-light d-inline-block text-center mb-1">
                                <img class="live-agreement-esign-img" src="" style="display:none; max-height: 36px; max-width: 120px;" alt="E-Sign">
                                <span class="live-agreement-esign-placeholder text-secondary" style="font-size: 9px;">[Consumer E-Sign Pending]</span>
                            </div>
                            <div class="fw-bold">2</div>
                        </div>
                    </div>
                </div>

                <!-- Page 3 -->
                <div class="bg-white p-4 p-md-5 mb-4 rounded shadow-sm border">
                    <p class="mb-2"><strong>5. Procurement and Supply:</strong> BIS/IS/IEC certified BoS components conforming to MNRE specifications.</p>
                    <p class="mb-2"><strong>6. Installation and Civil Work:</strong> Hot Dip Galvanized structure mounting with wind resistance up to 150 km/h.</p>
                    <p class="mb-2"><strong>7. Documentation:</strong> Providing technical catalogues, warranty cards, and SLD drawings to consumer.</p>
                    <p class="mb-2"><strong>8. Project Completion Report (PCR):</strong> Assisting consumer in filling and uploading signed documents on National Portal.</p>
                    <p class="mb-2"><strong>9. Warranty:</strong> Complete 5-Year Comprehensive System Warranty from DISCOM commissioning date.</p>
                    <p class="mb-2"><strong>10. Net Meter and Grid Connectivity:</strong> Supply, testing, and DISCOM grid synchronization in vendor scope.</p>
                    <p class="mb-2"><strong>11. Testing and Commissioning:</strong> Vendor presence during DISCOM joint inspection.</p>
                    <p class="mb-2"><strong>12. Operation & Maintenance:</strong> 5 Years comprehensive O&M including regular preventive maintenance.</p>
                    <p class="mb-2"><strong>13. Insurance:</strong> Material transit and storage insurance coverage prior to commissioning.</p>
                    <p class="mb-2"><strong>14. Standards:</strong> Rigorous adherence to MNRE and DISCOM benchmark technical specifications.</p>
                    <p class="mb-2"><strong>15. Payment Schedule:</strong> Mutual milestone payments as agreed in Clause 19.</p>
                    <p class="mb-2"><strong>16. Dispute:</strong> Mutual resolution between parties in accordance with applicable laws.</p>
                    <p class="mb-0"><strong>17. Subsidy Documentation:</strong> Vendor assistance for seamless direct benefit transfer (DBT).</p>
                    <div class="d-flex justify-content-between align-items-end pt-3 border-top mt-4 text-muted" style="font-size: 11px;">
                        <div>Guidelines for PM-Surya Ghar: Muft Bijli Yojana<br>Central Financial Assistance to Residential</div>
                        <div class="text-end">
                            <div class="adv-live-esign-badge border p-1 rounded bg-light d-inline-block text-center mb-1">
                                <img class="live-agreement-esign-img" src="" style="display:none; max-height: 36px; max-width: 120px;" alt="E-Sign">
                                <span class="live-agreement-esign-placeholder text-secondary" style="font-size: 9px;">[Consumer E-Sign Pending]</span>
                            </div>
                            <div class="fw-bold">3</div>
                        </div>
                    </div>
                </div>

                <!-- Page 4 -->
                <div class="bg-white p-4 p-md-5 rounded shadow-sm border">
                    <p class="mb-3"><strong>18. Performance of Plant:</strong> Minimum Performance Ratio (PR) of 75% at DISCOM commissioning and maintained across 5-year warranty.</p>
                    
                    <div class="mb-4">
                        <p class="fw-bold mb-1">19. Mutually Agreed Terms of Payment:</p>
                        <p class="mb-1 ms-3">a. After Supply of materials at premises – <strong>90% of Project Cost</strong></p>
                        <p class="mb-0 ms-3">b. After Installation and Commissioning of Project – <strong>10% of Final Value</strong></p>
                    </div>

                    <div class="row g-0 border border-dark p-3 bg-light mb-3">
                        <div class="col-6 border-end border-dark pe-3">
                            <div class="fw-bold mb-2">First Party (Consumer)</div>
                            <div>Name: <strong class="adv-preview-custname text-primary">[CUSTOMER NAME]</strong></div>
                            <div class="mt-1">Address: <strong class="adv-preview-village">[VILLAGE]</strong>, <strong class="adv-preview-block">[BLOCK]</strong>, <strong class="adv-preview-district text-uppercase">[DISTRICT]</strong></div>
                            <div class="mt-1">PIN: <strong class="adv-preview-pin">[PINCODE]</strong> (ODISHA)</div>
                            <div class="mt-3">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span>Sign:</span>
                                    <img class="live-agreement-esign-img" src="" style="display:none; max-height: 42px; max-width: 140px;" alt="E-Sign">
                                    <span class="live-agreement-esign-placeholder text-secondary">[Consumer E-Sign Pending]</span>
                                </div>
                                <div>Date: <?= date('d/m/Y') ?></div>
                            </div>
                        </div>
                        <div class="col-6 ps-3">
                            <div class="fw-bold mb-2">Second Party (Vendor)</div>
                            <div>Name: <strong>DHWAJJA SOLAR INDIA PVT LTD</strong></div>
                            <div class="mt-1">MIG-84, POKHARIPUT, BDA COLONY, PHASE-1, POKHARIPUT, BHUBANESWAR</div>
                            <div class="mt-1">PIN: 751020 (ODISHA)</div>
                            <div class="mt-3">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span>Sign:</span>
                                    <img src="<?= function_exists('company_signature_url') ? company_signature_url() : '/assets/images/authorised_signatory.png' ?>" alt="Authorised Signatory" style="max-height: 42px; max-width: 140px;">
                                </div>
                                <div>Date: <?= date('d/m/Y') ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-end pt-3 border-top mt-4 text-muted" style="font-size: 11px;">
                        <div>Guidelines for PM-Surya Ghar: Muft Bijli Yojana<br>Central Financial Assistance to Residential</div>
                        <div class="text-end">
                            <div class="adv-live-esign-badge border p-1 rounded bg-light d-inline-block text-center mb-1">
                                <img class="live-agreement-esign-img" src="" style="display:none; max-height: 36px; max-width: 120px;" alt="E-Sign">
                                <span class="live-agreement-esign-placeholder text-secondary" style="font-size: 9px;">[Consumer E-Sign Pending]</span>
                            </div>
                            <div class="fw-bold">4</div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-light px-4 py-2 d-flex justify-content-between">
                <span class="text-muted small"><i class="bi bi-patch-check-fill text-success me-1"></i> Digitally executed with captured consumer touch signature</span>
                <button type="button" class="btn btn-primary btn-sm fw-bold px-4" data-bs-dismiss="modal">
                    <i class="bi bi-check-lg me-1"></i> I Understand & Accept Agreement
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const DISTRICT_DISCOM_MAP = <?= json_encode($districtDiscomMap ?? \App\Models\Discom::getDistrictLookupMap()) ?>;

function onAdvisorDistrictChange(districtName) {
    if (!districtName) return;
    const cleanDist = districtName.trim();
    const discomSelect = document.getElementById('selectAdvisorCustDiscom');
    const badgeEl = document.getElementById('advisorDiscomAutoBadge');
    if (!discomSelect) return;

    let matchedCode = null;
    let matchedName = null;

    if (DISTRICT_DISCOM_MAP[cleanDist]) {
        matchedCode = DISTRICT_DISCOM_MAP[cleanDist].discom_code;
        matchedName = DISTRICT_DISCOM_MAP[cleanDist].discom_name;
    } else {
        const lower = cleanDist.toLowerCase();
        for (const [k, v] of Object.entries(DISTRICT_DISCOM_MAP)) {
            if (k.toLowerCase() === lower || k.toLowerCase().includes(lower) || lower.includes(k.toLowerCase())) {
                matchedCode = v.discom_code;
                matchedName = v.discom_name;
                break;
            }
        }
    }

    if (matchedCode) {
        discomSelect.value = matchedCode;
        if (badgeEl) {
            badgeEl.innerHTML = `<span class="badge bg-success text-white py-1 px-2 border"><i class="bi bi-check-circle-fill me-1"></i> Auto-selected: <strong>${matchedCode}</strong> (${matchedName})</span>`;
        }
    }
}

function copyPrimaryMobileToBill() {
    const primaryMobile = document.querySelector('input[name="mobile"]')?.value || '';
    const billMobileInput = document.getElementById('inputAdvisorBillMobile');
    if (billMobileInput) {
        billMobileInput.value = primaryMobile;
    }
}

function onAdvisorPackageChange(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (!opt || !opt.value) {
        document.getElementById('advisorPkgLiveCard').style.display = 'none';
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
    document.getElementById('advCardPkgBrand').innerText = brand;
    document.getElementById('advCardPkgType').innerText = type;
    document.getElementById('advCardPkgCap').innerText = parseInt(cap) + ' kW Capacity';
    document.getElementById('advCardPkgTitle').innerText = title;
    document.getElementById('advCardPkgPanels').innerText = panels;
    document.getElementById('advCardPkgInverter').innerText = inverter;
    document.getElementById('advCardPkgGross').innerText = '₹' + gross.toLocaleString('en-IN');
    document.getElementById('advCardPkgSub').innerText = sub > 0 ? ('- ₹' + sub.toLocaleString('en-IN')) : '₹0';
    document.getElementById('advCardPkgNet').innerText = '₹' + net.toLocaleString('en-IN');
    
    const featEl = document.getElementById('advCardPkgFeatures');
    if (features && features !== 'None') {
        featEl.innerText = '★ ' + features;
        featEl.style.display = 'block';
    } else {
        featEl.style.display = 'none';
    }

    // Update Proposed kW input
    document.getElementById('advInputProposedKw').value = parseFloat(cap).toFixed(1);
    document.getElementById('advisorPkgLiveCard').style.display = 'block';
}

// Signature Pad Implementation for Touch / Mobile Stylus / Mouse
let sigCanvas, sigCtx, isDrawing = false, hasSignatureDrawn = false;

function initSignaturePad() {
    sigCanvas = document.getElementById('advisorSignaturePad');
    if (!sigCanvas) return;
    
    // Handle High DPI displays
    const rect = sigCanvas.getBoundingClientRect();
    sigCanvas.width = rect.width || 400;
    sigCanvas.height = 160;

    sigCtx = sigCanvas.getContext('2d');
    sigCtx.strokeStyle = '#092c4c';
    sigCtx.lineWidth = 2.5;
    sigCtx.lineCap = 'round';
    sigCtx.lineJoin = 'round';

    function getPos(e) {
        const r = sigCanvas.getBoundingClientRect();
        if (e.touches && e.touches.length > 0) {
            return {
                x: e.touches[0].clientX - r.left,
                y: e.touches[0].clientY - r.top
            };
        }
        return {
            x: e.clientX - r.left,
            y: e.clientY - r.top
        };
    }

    function startDraw(e) {
        isDrawing = true;
        hasSignatureDrawn = true;
        const pos = getPos(e);
        sigCtx.beginPath();
        sigCtx.moveTo(pos.x, pos.y);
        e.preventDefault();
    }

    function draw(e) {
        if (!isDrawing) return;
        const pos = getPos(e);
        sigCtx.lineTo(pos.x, pos.y);
        sigCtx.stroke();
        e.preventDefault();
    }

    function updateLiveAgreementSignature(dataUrl) {
        const imgs = document.querySelectorAll('.live-agreement-esign-img');
        const placeholders = document.querySelectorAll('.live-agreement-esign-placeholder');
        const statusBadge = document.getElementById('sigDrawnStatusBadge');

        if (dataUrl) {
            imgs.forEach(img => {
                img.src = dataUrl;
                img.style.display = 'inline-block';
            });
            placeholders.forEach(el => el.style.display = 'none');
            if (statusBadge) {
                statusBadge.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i> <span class="text-success fw-bold">E-Signature Stamped ✓</span>';
            }
        } else {
            imgs.forEach(img => {
                img.src = '';
                img.style.display = 'none';
            });
            placeholders.forEach(el => el.style.display = 'inline-block');
            if (statusBadge) {
                statusBadge.innerHTML = '<i class="bi bi-pencil me-1"></i> Sign above';
            }
        }
    }

    function endDraw(e) {
        if (!isDrawing) return;
        isDrawing = false;
        sigCtx.closePath();
        const dataUrl = sigCanvas.toDataURL('image/png');
        document.getElementById('inputAdvisorSignatureBase64').value = dataUrl;
        updateLiveAgreementSignature(dataUrl);
    }

    sigCanvas.addEventListener('mousedown', startDraw);
    sigCanvas.addEventListener('mousemove', draw);
    sigCanvas.addEventListener('mouseup', endDraw);
    sigCanvas.addEventListener('mouseleave', endDraw);

    sigCanvas.addEventListener('touchstart', startDraw, { passive: false });
    sigCanvas.addEventListener('touchmove', draw, { passive: false });
    sigCanvas.addEventListener('touchcancel', endDraw, { passive: false });
    sigCanvas.addEventListener('touchend', endDraw, { passive: false });
}

function clearSignatureCanvas() {
    if (!sigCanvas || !sigCtx) return;
    sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    hasSignatureDrawn = false;
    document.getElementById('inputAdvisorSignatureBase64').value = '';
    
    const imgs = document.querySelectorAll('.live-agreement-esign-img');
    const placeholders = document.querySelectorAll('.live-agreement-esign-placeholder');
    const statusBadge = document.getElementById('sigDrawnStatusBadge');
    
    imgs.forEach(img => {
        img.src = '';
        img.style.display = 'none';
    });
    placeholders.forEach(el => el.style.display = 'inline-block');
    if (statusBadge) {
        statusBadge.innerHTML = '<i class="bi bi-pencil me-1"></i> Sign above';
    }
}

function syncAgreementFormDetails() {
    const fn = (document.querySelector('input[name="first_name"]')?.value || '').trim();
    const ln = (document.querySelector('input[name="last_name"]')?.value || '').trim();
    const careOf = (document.querySelector('input[name="father_husband_name"]')?.value || '').trim();
    const village = (document.querySelector('input[name="village"]')?.value || '').trim();
    const block = (document.querySelector('select[name="block"]')?.value || document.querySelector('input[name="block"]')?.value || '').trim();
    const district = (document.querySelector('select[name="district"]')?.value || document.querySelector('input[name="district"]')?.value || '').trim();
    const pin = (document.querySelector('input[name="pincode"]')?.value || '').trim();
    const cno = (document.querySelector('input[name="consumer_number"]')?.value || '').trim();

    const fullName = (fn + ' ' + ln).trim() || '[CUSTOMER NAME]';

    document.querySelectorAll('.adv-preview-custname').forEach(el => el.innerText = fullName.toUpperCase());
    document.querySelectorAll('.adv-preview-careof').forEach(el => el.innerText = careOf ? careOf.toUpperCase() : '[FATHER/HUSBAND NAME]');
    document.querySelectorAll('.adv-preview-village').forEach(el => el.innerText = village ? village.toUpperCase() : '[VILLAGE]');
    document.querySelectorAll('.adv-preview-block').forEach(el => el.innerText = block ? block.toUpperCase() : '[BLOCK]');
    document.querySelectorAll('.adv-preview-district').forEach(el => el.innerText = district ? district.toUpperCase() : '[DISTRICT]');
    document.querySelectorAll('.adv-preview-pin').forEach(el => el.innerText = pin || '[PINCODE]');
    document.querySelectorAll('.adv-preview-consumerno').forEach(el => el.innerText = cno ? cno.toUpperCase() : '[CONSUMER NO]');
}

// Trigger initial load
document.addEventListener('DOMContentLoaded', function() {
    initSignaturePad();

    // Listen to form input changes for live agreement updates
    const inputsToWatch = ['first_name', 'last_name', 'father_husband_name', 'village', 'block', 'district', 'pincode', 'consumer_number'];
    inputsToWatch.forEach(name => {
        const el = document.querySelector(`[name="${name}"]`);
        if (el) {
            el.addEventListener('input', syncAgreementFormDetails);
            el.addEventListener('change', syncAgreementFormDetails);
        }
    });

    // Initial sync
    syncAgreementFormDetails();

    const form = document.getElementById('advisorCustomerForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (hasSignatureDrawn && sigCanvas) {
                document.getElementById('inputAdvisorSignatureBase64').value = sigCanvas.toDataURL('image/png');
            }
        });
    }

    const pkgSelect = document.getElementById('selectAdvisorCustPackage');
    if (pkgSelect) {
        onAdvisorPackageChange(pkgSelect);
    }

    const distSelect = document.getElementById('selectAdvisorCustDistrict');
    if (distSelect) {
        distSelect.addEventListener('change', function() {
            onAdvisorDistrictChange(this.value);
            syncAgreementFormDetails();
        });
        setTimeout(function() {
            if (distSelect.value) {
                onAdvisorDistrictChange(distSelect.value);
                syncAgreementFormDetails();
            }
        }, 500);
    }

    // Ensure modal is directly attached to body to prevent stacking context/backdrop overlay issues
    const agreementModalEl = document.getElementById('modalAgreementPreview');
    if (agreementModalEl && agreementModalEl.parentElement !== document.body) {
        document.body.appendChild(agreementModalEl);
    }
});
</script>
