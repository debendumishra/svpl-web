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

                    <!-- Solar Requirement -->
                    <h5 class="fw-bold mb-3" style="color: #0B2545; border-bottom: 2px solid #E2E8F0; padding-bottom: 8px;">
                        2. Solar Capacity & Rooftop Assessment
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Proposed Solar Capacity (kW) *</label>
                            <select name="proposed_solar_kw" class="form-select" required>
                                <option value="2.00">2 KW Plant (120 sq.ft. | ₹1,10,000 Subsidy | Net ₹50,000 | EMI: ₹545/mo)</option>
                                <option value="3.00" selected>3 KW Plant (180 sq.ft. | ₹1,38,000 Subsidy | Net ₹72,000 | EMI: ₹785/mo)</option>
                                <option value="4.00">4 KW Plant (210 sq.ft. | ₹1,38,000 Subsidy | Net ₹1,22,000 | EMI: ₹1,333/mo)</option>
                                <option value="5.00">5 KW Plant (270 sq.ft. | ₹1,38,000 Subsidy | Net ₹1,92,000 | EMI: ₹2,098/mo)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Current Sanctioned Load (kW)</label>
                            <input type="number" step="0.5" name="sanctioned_load_kw" class="form-control" value="2.0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Average Monthly Electricity Bill (₹)</label>
                            <input type="number" name="monthly_avg_bill" class="form-control" placeholder="e.g. 1500" value="1500">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Select Solar Package</label>
                            <select name="package_id" class="form-select">
                                <option value="3" selected>3 KW Dhwajja Solar On-Grid Package (Recommended)</option>
                                <option value="2">2 KW Dhwajja Solar On-Grid Package</option>
                                <option value="4">4 KW Dhwajja Solar On-Grid Package</option>
                                <option value="5">5 KW Dhwajja Solar On-Grid Package</option>
                            </select>
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
