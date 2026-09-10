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
                <select name="district" class="form-select select-district" required>
                    <option value="">-- Select District --</option>
                    <?php
                    $odishaDistricts = [
                        "Angul", "Balangir", "Balasore", "Bargarh", "Bhadrak", "Boudh", "Cuttack", "Deogarh",
                        "Dhenkanal", "Gajapati", "Ganjam", "Jagatsinghpur", "Jajpur", "Jharsuguda", "Kalahandi",
                        "Kandhamal", "Kendrapara", "Kendujhar", "Khordha", "Koraput", "Malkangiri", "Mayurbhanj",
                        "Nabarangpur", "Nayagarh", "Nuapada", "Puri", "Rayagada", "Sambalpur", "Subarnapur", "Sundargarh"
                    ];
                    $selectedDist = $post['district'] ?? $advisor['district'] ?? 'Khordha';
                    foreach ($odishaDistricts as $d): ?>
                        <option value="<?= $d ?>" <?= $selectedDist === $d ? 'selected' : '' ?>><?= $d ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-navy">Block / Subdivision <span class="text-danger">*</span></label>
                <input type="text" name="block" class="form-control" placeholder="e.g. Balianta, Jatni, Sadar" value="<?= htmlspecialchars($post['block'] ?? '') ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-navy">Gram Panchayat <span class="text-danger">*</span></label>
                <input type="text" name="gram_panchayat" class="form-control" placeholder="e.g. Benupur GP" value="<?= htmlspecialchars($post['gram_panchayat'] ?? '') ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Village / Town <span class="text-danger">*</span></label>
                <input type="text" name="village" class="form-control" placeholder="e.g. Hanspal Village" value="<?= htmlspecialchars($post['village'] ?? '') ?>" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-navy">Pincode <span class="text-danger">*</span></label>
                <input type="text" name="pincode" class="form-control font-monospace" placeholder="6-digit PIN" pattern="[0-9]{6}" maxlength="6" value="<?= htmlspecialchars($post['pincode'] ?? '751020') ?>" required>
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
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Assigned DISCOM Provider <span class="text-danger">*</span></label>
                <select name="discom_name" class="form-select" required>
                    <option value="TPCODL" <?= ($post['discom_name'] ?? '') === 'TPCODL' ? 'selected' : '' ?>>TP Central Odisha (TPCODL - Khordha, Cuttack, Puri, Dhenkanal)</option>
                    <option value="TPNODL" <?= ($post['discom_name'] ?? '') === 'TPNODL' ? 'selected' : '' ?>>TP Northern Odisha (TPNODL - Balasore, Bhadrak, Mayurbhanj, Keonjhar)</option>
                    <option value="TPSODL" <?= ($post['discom_name'] ?? '') === 'TPSODL' ? 'selected' : '' ?>>TP Southern Odisha (TPSODL - Ganjam, Gajapati, Rayagada, Koraput)</option>
                    <option value="TPWODL" <?= ($post['discom_name'] ?? '') === 'TPWODL' ? 'selected' : '' ?>>TP Western Odisha (TPWODL - Sambalpur, Bargarh, Jharsuguda, Rourkela)</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Electricity Consumer / CA Number <span class="text-danger">*</span></label>
                <input type="text" name="consumer_number" class="form-control font-monospace fw-bold text-primary" placeholder="Found on Electricity Bill" value="<?= htmlspecialchars($post['consumer_number'] ?? '') ?>" required>
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
                <label class="form-label small fw-bold text-navy">Proposed Solar Rooftop Capacity <span class="text-danger">*</span></label>
                <select name="proposed_solar_kw" class="form-select fw-bold text-success" required>
                    <option value="1.0">1 kW Plant (Subsidy: ₹45,000)</option>
                    <option value="2.0">2 kW Plant (Subsidy: ₹90,000)</option>
                    <option value="3.0" selected>3 kW Plant (Max Central+State Subsidy: ₹1,38,000)</option>
                    <option value="4.0">4 kW Plant (Subsidy: ₹1,38,000)</option>
                    <option value="5.0">5 kW Plant (Subsidy: ₹1,38,000)</option>
                    <option value="10.0">10 kW Commercial / Institution (Subsidy: ₹1,38,000)</option>
                </select>
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
                    <input type="file" name="doc_electricity_bill" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text small" style="font-size: 0.72rem;">Clear scan/photo showing Consumer Number, Sanctioned Load, and Address.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <label class="form-label small fw-bold text-navy">
                        <i class="bi bi-person-badge text-danger me-1"></i> 2. Customer Aadhaar Card (Front & Back) <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="doc_aadhaar_card" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text small" style="font-size: 0.72rem;">Customer Identity and address proof for PM Surya Ghar DBT linkage.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <label class="form-label small fw-bold text-navy">
                        <i class="bi bi-card-text text-info me-1"></i> 3. Customer PAN Card (Optional for < 10kW)
                    </label>
                    <input type="file" name="doc_pan_card" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text small" style="font-size: 0.72rem;">Required for bank loan disbursement and larger installations.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <label class="form-label small fw-bold text-navy">
                        <i class="bi bi-bank text-success me-1"></i> 4. Bank Passbook / Cancelled Cheque <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="doc_bank_passbook" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text small" style="font-size: 0.72rem;">Account where central DBT subsidy (₹78k) & state subsidy (₹60k) will be credited.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <label class="form-label small fw-bold text-navy">
                        <i class="bi bi-house text-warning me-1"></i> 5. Land Patta / Ownership / Holding Tax Document
                    </label>
                    <input type="file" name="doc_land_patta" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text small" style="font-size: 0.72rem;">Proof of building ownership or Municipal holding tax receipt.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <label class="form-label small fw-bold text-navy">
                        <i class="bi bi-camera-fill text-primary me-1"></i> 6. Rooftop / Site Photograph <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="doc_roof_photo" class="form-control form-control-sm" accept=".jpg,.jpeg,.png">
                    <div class="form-text small" style="font-size: 0.72rem;">Photo of customer's roof showing shadow-free area available.</div>
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
            <button type="submit" class="btn btn-svpl-solar btn-lg px-5 py-2 fw-bold shadow">
                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Submit Customer Application & Upload Documents
            </button>
        </div>
        <div class="small text-secondary mt-3">
            <i class="bi bi-lock-fill text-success me-1"></i> 
            Application will be submitted to the SVPL Document Verification desk under your Advisor account.
        </div>
    </div>
</form>
