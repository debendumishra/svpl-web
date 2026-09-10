<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Registration View with Sponsor Lookup & Cascading Odisha Locations
 */
$title = "Join as Solar Advisor — Surya Vistaara Pvt. Ltd.";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-svpl p-4 p-md-5 shadow-sm border-0" style="border-radius: 16px;">
                <div class="text-center mb-4">
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2">SOLAR ADVISOR ONBOARDING</span>
                    <h2 class="fw-bold" style="color: #0B2545;">Register as an Authorized SVPL Solar Advisor</h2>
                    <p class="text-muted">Earn direct commissions, build your multi-level network, and power Odisha's rooftop solar revolution.</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2 px-3 small d-flex align-items-center mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= url('/register-advisor') ?>" method="POST" id="formRegisterAdvisor">
                    
                    <!-- 1. Sponsor / Referral Information -->
                    <div class="p-3 bg-light rounded-3 mb-4 border">
                        <h6 class="fw-bold text-navy mb-2" style="color: #0B2545;">
                            <i class="bi bi-person-check-fill text-warning me-2"></i> Sponsor / Referral Details
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Referral / Sponsor Code (Optional):</label>
                                <input type="text" name="referral_code" id="inputReferralCode" class="form-control" placeholder="e.g. SVPL1001" value="<?= htmlspecialchars($ref ?? ($post['referral_code'] ?? '')) ?>">
                                <div id="referralFeedback" class="small mt-1"></div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <span class="text-muted small">If left blank, you will be assigned to SVPL Direct Operations Head.</span>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Personal Information -->
                    <h5 class="fw-bold mb-3" style="color: #0B2545; border-bottom: 2px solid #E2E8F0; padding-bottom: 8px;">
                        1. Personal Details
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">First Name *</label>
                            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($post['first_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($post['last_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Father's / Spouse Name</label>
                            <input type="text" name="father_spouse_name" class="form-control" value="<?= htmlspecialchars($post['father_spouse_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Date of Birth</label>
                            <input type="date" name="dob" class="form-control" value="<?= htmlspecialchars($post['dob'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- 3. Contact & Location Details -->
                    <h5 class="fw-bold mb-3" style="color: #0B2545; border-bottom: 2px solid #E2E8F0; padding-bottom: 8px;">
                        2. Contact & Odisha Location
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mobile Number (Login ID) *</label>
                            <input type="tel" name="mobile" class="form-control" placeholder="10-digit mobile number" value="<?= htmlspecialchars($post['mobile'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" value="<?= htmlspecialchars($post['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">District *</label>
                            <select name="district" id="selectDistrict" class="form-select" required>
                                <option value="">Select District</option>
                                <option value="Khordha" selected>Khordha</option>
                                <option value="Cuttack">Cuttack</option>
                                <option value="Puri">Puri</option>
                                <option value="Ganjam">Ganjam</option>
                                <option value="Sambalpur">Sambalpur</option>
                                <option value="Balasore">Balasore</option>
                                <option value="Bhadrak">Bhadrak</option>
                                <option value="Mayurbhanj">Mayurbhanj</option>
                                <option value="Sundargarh">Sundargarh</option>
                                <option value="Angul">Angul</option>
                                <option value="Dhenkanal">Dhenkanal</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Block *</label>
                            <select name="block" id="selectBlock" class="form-select" required>
                                <option value="">Select Block</option>
                                <option value="Bhubaneswar" selected>Bhubaneswar</option>
                                <option value="Jatni">Jatni</option>
                                <option value="Balianta">Balianta</option>
                                <option value="Baranga">Baranga</option>
                                <option value="Salepur">Salepur</option>
                                <option value="Pipili">Pipili</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Gram Panchayat</label>
                            <input type="text" name="gram_panchayat" class="form-control" placeholder="e.g. Mendhasala" value="<?= htmlspecialchars($post['gram_panchayat'] ?? 'Mendhasala') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Village</label>
                            <input type="text" name="village" class="form-control" placeholder="Village name" value="<?= htmlspecialchars($post['village'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Pincode *</label>
                            <input type="text" name="pincode" class="form-control" value="<?= htmlspecialchars($post['pincode'] ?? '751024') ?>" required>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label fw-semibold">Full Postal Address</label>
                            <input type="text" name="address_line" class="form-control" placeholder="Plot No, Street, Landmark" value="<?= htmlspecialchars($post['address_line'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- 3. Statutory KYC & Bank Details -->
                    <h5 class="fw-bold mb-3" style="color: #0B2545; border-bottom: 2px solid #E2E8F0; padding-bottom: 8px;">
                        3. Statutory KYC & Bank Account Details
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Aadhaar Number (12 Digits) *</label>
                            <input type="text" name="aadhaar_number" class="form-control font-monospace" placeholder="12-digit Aadhaar" maxlength="14" value="<?= htmlspecialchars($post['aadhaar_number'] ?? '') ?>">
                            <small class="text-muted" style="font-size: 0.72rem;">Encrypted & protected under SVPL Privacy Policy.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">PAN Card Number (10 Characters) *</label>
                            <input type="text" name="pan_number" class="form-control text-uppercase font-monospace" placeholder="e.g. ABCDE1234F" maxlength="10" value="<?= htmlspecialchars($post['pan_number'] ?? '') ?>">
                            <small class="text-muted" style="font-size: 0.72rem;">Required for 5% statutory TDS credit & Form 16.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Bank Name *</label>
                            <input type="text" name="bank_name" class="form-control" placeholder="e.g. State Bank of India" value="<?= htmlspecialchars($post['bank_name'] ?? 'State Bank of India') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Account Number *</label>
                            <input type="text" name="account_number" class="form-control font-monospace" placeholder="Bank Account Number" value="<?= htmlspecialchars($post['account_number'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">IFSC Code *</label>
                            <input type="text" name="ifsc_code" class="form-control text-uppercase font-monospace" placeholder="e.g. SBIN0001234" value="<?= htmlspecialchars($post['ifsc_code'] ?? 'SBIN0001234') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nominee Full Name</label>
                            <input type="text" name="nominee_name" class="form-control" placeholder="Full name of nominee" value="<?= htmlspecialchars($post['nominee_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nominee Relationship</label>
                            <select name="nominee_relationship" class="form-select">
                                <option value="Spouse">Spouse</option>
                                <option value="Father">Father</option>
                                <option value="Mother">Mother</option>
                                <option value="Son">Son</option>
                                <option value="Daughter">Daughter</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- 4. Registration Fee Confirmation & Security -->
                    <div class="p-3 bg-warning-subtle rounded-3 mb-4 border border-warning-subtle">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <span class="badge bg-warning text-dark fw-bold">ONBOARDING FEE</span>
                                <h6 class="font-heading fw-bold text-navy mb-0 mt-1">Advisor Registration & ID License: ₹2,700</h6>
                                <small class="text-secondary">Includes digital business license, printable photo ID card, doorstep QR code, and 9-level commission access.</small>
                            </div>
                            <span class="badge bg-success fs-6"><i class="bi bi-patch-check-fill me-1"></i> Instant Active</span>
                        </div>
                    </div>

                    <!-- 5. Security Password & CAPTCHA -->
                    <h5 class="fw-bold mb-3" style="color: #0B2545; border-bottom: 2px solid #E2E8F0; padding-bottom: 8px;">
                        4. Portal Security & CAPTCHA
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Account Password *</label>
                            <input type="password" name="password" class="form-control" placeholder="Create a secure password" value="Password@123" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Security Verification (CAPTCHA) *</label>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <img src="<?= url('/captcha') ?>?t=<?= time() ?>" id="advCaptchaImg" alt="Security Code" style="height: 40px; border-radius: 6px; border: 1px solid #CBD5E1; cursor: pointer;" title="Click to refresh" onclick="this.src='<?= url('/captcha') ?>?t='+new Date().getTime()">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('advCaptchaImg').src='<?= url('/captcha') ?>?t='+new Date().getTime()" title="Get new code">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                            <input type="text" name="captcha" class="form-control text-uppercase font-monospace fw-bold" placeholder="Enter 5-character code" maxlength="6" required autocomplete="off">
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="termsCheck" required checked>
                        <label class="form-check-label small text-muted" for="termsCheck">
                            I agree to the <a href="<?= url('/terms') ?>" target="_blank">SVPL Advisor Code of Ethics</a>, Terms & Conditions, and statutory TDS deduction policies.
                        </label>
                    </div>

                    <button type="submit" class="btn btn-svpl-solar btn-lg w-100 py-3 fw-bold">
                        <i class="bi bi-shield-lock-fill me-2"></i> Complete Advisor Registration & Generate ID Card
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
