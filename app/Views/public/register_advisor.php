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

                <form action="<?= url('/register-advisor') ?>" method="POST" id="formRegisterAdvisor" enctype="multipart/form-data">
                    
                    <!-- 1. Sponsor / Referral Information -->
                    <div class="p-4 bg-light rounded-3 mb-4 border">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <h6 class="fw-bold text-navy mb-0" style="color: #0B2545;">
                                <i class="bi bi-person-check-fill text-warning me-2"></i> Sponsor / Referral Details
                            </h6>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle small">
                                <i class="bi bi-shield-check me-1"></i> Automatic Network Attribution
                            </span>
                        </div>
                        
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-navy">
                                    Referral / Sponsor Code or Mobile Number:
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-person-badge text-warning"></i></span>
                                    <input type="text" name="referral_code" id="inputReferralCode" class="form-control font-monospace fw-bold" placeholder="e.g. REF1001, SB-A-00001, or Mobile" value="<?= htmlspecialchars($ref ?? ($post['referral_code'] ?? '')) ?>" autocomplete="off">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnVerifyReferral">
                                        <i class="bi bi-check2-circle"></i> Verify
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-secondary small">
                                    Entering an active Advisor's referral code links your onboarding to their 9-level mentor network. Leave blank for Direct SVPL enrollment.
                                </div>
                            </div>
                        </div>

                        <!-- LIVE VALIDATED SPONSOR DISPLAY CARD -->
                        <div id="referralFeedback" class="mt-3"></div>
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

                    <!-- 2B. Official ID Card Photograph (Passport Upload or Live Selfie) -->
                    <h5 class="fw-bold mb-3" style="color: #0B2545; border-bottom: 2px solid #E2E8F0; padding-bottom: 8px;">
                        2. Official ID Card Photograph (Passport / Live Camera)
                    </h5>
                    <div class="row g-3 mb-4 align-items-center">
                        <!-- Photo Preview Box -->
                        <div class="col-sm-4 col-md-3 text-center">
                            <div class="d-inline-block position-relative border-2 border-dashed border-primary rounded-3 p-1 bg-light shadow-sm" style="width: 110px; height: 135px; overflow: hidden;" id="photoPreviewContainer">
                                <img id="imgPhotoPreview" src="" alt="Photo Preview" class="w-100 h-100 rounded-2" style="object-fit: cover; display: none;">
                                <div id="placeholderPhotoText" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted small">
                                    <i class="bi bi-person-bounding-box fs-1 text-secondary mb-1"></i>
                                    <span style="font-size: 0.72rem;" class="fw-bold">ID PHOTO</span>
                                </div>
                            </div>
                            <div class="mt-1">
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.68rem;">CR80 3:4 Ratio</span>
                            </div>
                        </div>

                        <!-- Upload / Live Camera Actions -->
                        <div class="col-sm-8 col-md-9">
                            <div class="card p-3 bg-light border-0 rounded-3">
                                <div class="row g-2">
                                    <div class="col-12 col-lg-6">
                                        <label class="form-label small fw-semibold text-navy"><i class="bi bi-upload text-primary me-1"></i> Option A: Upload Passport Photo</label>
                                        <input type="file" name="advisor_photo" id="inputPhotoFile" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp" onchange="previewUploadedPhoto(this)">
                                        <small class="text-muted" style="font-size: 0.72rem;">JPG, PNG, or WEBP (Passport format).</small>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <label class="form-label small fw-semibold text-navy"><i class="bi bi-camera-fill text-success me-1"></i> Option B: Live Camera Capture</label>
                                        <div>
                                            <button type="button" class="btn btn-outline-success btn-sm w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#modalLiveCamera" onclick="startLiveCamera()">
                                                <i class="bi bi-camera-video me-1"></i> Take Live Selfie / Photo
                                            </button>
                                        </div>
                                        <small class="text-muted" style="font-size: 0.72rem;">Capture directly using your device webcam.</small>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="advisor_photo_base64" id="inputPhotoBase64" value="">

                                <div class="row g-2 mt-2 pt-2 border-top">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-navy"><i class="bi bi-droplet-fill text-danger me-1"></i> Blood Group *</label>
                                        <select name="blood_group" class="form-select form-select-sm fw-semibold" required>
                                            <option value="O+ve" selected>O +ve</option>
                                            <option value="A+ve">A +ve</option>
                                            <option value="B+ve">B +ve</option>
                                            <option value="AB+ve">AB +ve</option>
                                            <option value="O-ve">O -ve</option>
                                            <option value="A-ve">A -ve</option>
                                            <option value="B-ve">B -ve</option>
                                            <option value="AB-ve">AB -ve</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-navy">Assigned Designation</label>
                                        <input type="text" class="form-control form-control-sm bg-white fw-bold text-success" value="Certified Solar Advisor" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Contact & Location Details -->
                    <h5 class="fw-bold mb-3" style="color: #0B2545; border-bottom: 2px solid #E2E8F0; padding-bottom: 8px;">
                        3. Contact & Odisha Location
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

                    <!-- 4. Registration Fee Payment Details & UTR Entry -->
                    <div class="card p-4 rounded-3 mb-4 border border-warning bg-warning-subtle shadow-sm">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <div>
                                <span class="badge bg-warning text-dark fw-bold px-2 py-1">MANDATORY ONBOARDING FEE</span>
                                <h5 class="font-heading fw-bold text-navy mb-0 mt-1">Advisor Registration & ID License: ₹<?= number_format(advisor_joining_fee()) ?></h5>
                            </div>
                            <span class="badge bg-dark text-white px-3 py-2"><i class="bi bi-shield-lock-fill text-warning me-1"></i> Admin Verification Required</span>
                        </div>
                        
                        <p class="small text-secondary mb-3">
                            Please transfer the one-time registration fee of <strong>₹<?= number_format(advisor_joining_fee()) ?></strong> to the official <?= htmlspecialchars(company_short_name()) ?> corporate account below via UPI, IMPS, NEFT, or Cash, and submit the <strong>UTR / Transaction Reference Number</strong>. Your account will be activated by the Manager/Admin upon payment confirmation.
                        </p>

                        <!-- Official Payment Bank & UPI Details -->
                        <div class="row g-3 p-3 bg-white rounded-3 border mb-3">
                            <div class="col-md-6 border-end-md">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-bank fs-4 text-primary"></i>
                                    <strong class="text-navy"><?= htmlspecialchars(company_short_name()) ?> Official Bank Account</strong>
                                </div>
                                <div class="small">
                                    <div><strong>Account Name:</strong> <?= htmlspecialchars(company_name()) ?></div>
                                    <div><strong>Bank:</strong> State Bank of India (SBI)</div>
                                    <div><strong>Account No:</strong> <span class="font-monospace fw-bold text-primary">42398712345</span></div>
                                    <div><strong>IFSC Code:</strong> <span class="font-monospace fw-bold text-primary">SBIN0001234</span></div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Branch: Bhubaneswar Main, Odisha</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-qr-code fs-4 text-success"></i>
                                    <strong class="text-navy">UPI / QR Scan & Pay</strong>
                                </div>
                                <div class="small">
                                    <div><strong>Corporate UPI ID:</strong></div>
                                    <div class="p-2 bg-light rounded border font-monospace fw-bold text-success d-flex justify-content-between align-items-center mt-1">
                                        <span>suryavistaara@sbi</span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="navigator.clipboard.writeText('suryavistaara@sbi'); alert('UPI ID copied to clipboard!');">
                                            <i class="bi bi-clipboard"></i> Copy
                                        </button>
                                    </div>
                                    <div class="text-muted mt-1" style="font-size: 0.75rem;">Accepted: Google Pay, PhonePe, Paytm, BHIM, Cred, All UPI Apps</div>
                                </div>
                            </div>
                        </div>

                        <!-- Advisor Payment Entry Inputs -->
                        <h6 class="fw-bold text-navy mb-2"><i class="bi bi-receipt-cutoff text-primary me-1"></i> Enter Your Payment Transaction Details</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Payment Mode *</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="UPI" <?= ($post['payment_method'] ?? '') === 'UPI' ? 'selected' : '' ?>>UPI (GPay / PhonePe / Paytm / BHIM)</option>
                                    <option value="BANK_TRANSFER" <?= ($post['payment_method'] ?? '') === 'BANK_TRANSFER' ? 'selected' : '' ?>>Bank Transfer (IMPS / NEFT / RTGS)</option>
                                    <option value="CASH" <?= ($post['payment_method'] ?? '') === 'CASH' ? 'selected' : '' ?>>Cash Deposit / SVPL Office</option>
                                    <option value="CARD" <?= ($post['payment_method'] ?? '') === 'CARD' ? 'selected' : '' ?>>Debit / Credit Card</option>
                                    <option value="OTHER" <?= ($post['payment_method'] ?? '') === 'OTHER' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold small">UTR / Transaction ID / Reference No. *</label>
                                <input type="text" name="transaction_ref" class="form-control font-monospace fw-bold text-uppercase" placeholder="e.g. 425612348970 or UPI Ref" value="<?= htmlspecialchars($post['transaction_ref'] ?? '') ?>" required>
                                <small class="text-muted" style="font-size: 0.72rem;">12-digit UTR from UPI app receipt or bank transaction slip.</small>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Payment Date *</label>
                                <input type="date" name="payment_date" class="form-control" value="<?= htmlspecialchars($post['payment_date'] ?? date('Y-m-d')) ?>" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Payment Remarks (Optional)</label>
                                <input type="text" name="payment_remarks" class="form-control form-control-sm" placeholder="e.g. Paid via PhonePe from Mobile 98XXXXXX" value="<?= htmlspecialchars($post['payment_remarks'] ?? '') ?>">
                            </div>
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

<!-- Modal: Live Camera Capture -->
<div class="modal fade" id="modalLiveCamera" tabindex="-1" aria-labelledby="modalLiveCameraLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0 overflow-hidden">
            <div class="modal-header bg-navy text-white px-4 py-3" style="background: #0B2545;">
                <h5 class="modal-title font-heading fw-bold d-flex align-items-center gap-2" id="modalLiveCameraLabel">
                    <i class="bi bi-camera-video-fill text-warning"></i> Live Camera ID Capture
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="stopLiveCamera()"></button>
            </div>
            <div class="modal-body p-4 text-center bg-light">
                <p class="small text-muted mb-3">
                    Position your face straight within the frame with good lighting for your official SVPL Identity Card.
                </p>

                <!-- Camera Stream Viewport -->
                <div class="position-relative mx-auto rounded-3 overflow-hidden shadow border border-2 border-primary" style="width: 260px; height: 320px; background: #000;">
                    <video id="liveCameraVideo" autoplay playsinline class="w-100 h-100" style="object-fit: cover; transform: scaleX(-1);"></video>
                    <canvas id="liveCameraCanvas" style="display: none;"></canvas>
                    
                    <!-- Portrait Guide Overlay -->
                    <div class="position-absolute top-0 start-0 w-100 h-100 pointer-events-none d-flex flex-column align-items-center justify-content-between p-3" style="border: 2px dashed rgba(255,255,255,0.6); border-radius: 8px;">
                        <span class="badge bg-dark bg-opacity-75 text-white small">Face Area</span>
                        <span class="badge bg-dark bg-opacity-75 text-warning small">Passport CR80 Crop</span>
                    </div>
                </div>

                <div id="cameraStatusMsg" class="mt-2 text-primary small fw-semibold" style="min-height: 20px;"></div>

                <div class="d-flex justify-content-center gap-2 mt-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnSwitchCamera" onclick="switchLiveCamera()">
                        <i class="bi bi-arrow-repeat me-1"></i> Switch Camera
                    </button>
                </div>
            </div>
            <div class="modal-footer bg-white px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal" onclick="stopLiveCamera()">Cancel</button>
                <button type="button" class="btn btn-success fw-bold px-4" id="btnCapturePhoto" onclick="captureLiveSnapshot()">
                    <i class="bi bi-camera-fill me-1"></i> Capture Snapshot
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let cameraStream = null;
let currentFacingMode = 'user';

function startLiveCamera() {
    const video = document.getElementById('liveCameraVideo');
    const statusMsg = document.getElementById('cameraStatusMsg');
    statusMsg.innerText = 'Initializing camera feed...';

    if (cameraStream) {
        stopLiveCamera();
    }

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        statusMsg.innerText = 'Camera access is not supported by your browser. Please upload a photo instead.';
        statusMsg.className = 'mt-2 text-danger small fw-semibold';
        return;
    }

    const constraints = {
        video: {
            facingMode: currentFacingMode,
            width: { ideal: 640 },
            height: { ideal: 800 }
        },
        audio: false
    };

    navigator.mediaDevices.getUserMedia(constraints)
        .then(stream => {
            cameraStream = stream;
            video.srcObject = stream;
            video.play();
            statusMsg.innerText = 'Camera active. Align and click Capture Snapshot.';
            statusMsg.className = 'mt-2 text-success small fw-semibold';
        })
        .catch(err => {
            console.error('Camera access error:', err);
            statusMsg.innerText = 'Permission denied or no camera found. Please allow camera permissions or upload a photo.';
            statusMsg.className = 'mt-2 text-danger small fw-semibold';
        });
}

function stopLiveCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }
}

function switchLiveCamera() {
    currentFacingMode = (currentFacingMode === 'user') ? 'environment' : 'user';
    const video = document.getElementById('liveCameraVideo');
    if (currentFacingMode === 'environment') {
        video.style.transform = 'scaleX(1)';
    } else {
        video.style.transform = 'scaleX(-1)';
    }
    startLiveCamera();
}

function captureLiveSnapshot() {
    const video = document.getElementById('liveCameraVideo');
    const canvas = document.getElementById('liveCameraCanvas');
    if (!video || !video.videoWidth) {
        alert('Camera stream is not ready yet. Please wait a moment.');
        return;
    }

    // Set canvas dimensions to 3:4 aspect ratio (passport portrait)
    const targetWidth = 480;
    const targetHeight = 640;
    canvas.width = targetWidth;
    canvas.height = targetHeight;

    const ctx = canvas.getContext('2d');
    
    // Mirror if front camera
    if (currentFacingMode === 'user') {
        ctx.translate(targetWidth, 0);
        ctx.scale(-1, 1);
    }

    // Calculate crop for 3:4
    const vWidth = video.videoWidth;
    const vHeight = video.videoHeight;
    const vAspect = vWidth / vHeight;
    const targetAspect = targetWidth / targetHeight;

    let sx = 0, sy = 0, sWidth = vWidth, sHeight = vHeight;
    if (vAspect > targetAspect) {
        // Video is wider than target
        sWidth = vHeight * targetAspect;
        sx = (vWidth - sWidth) / 2;
    } else {
        // Video is taller than target
        sHeight = vWidth / targetAspect;
        sy = (vHeight - sHeight) / 2;
    }

    ctx.drawImage(video, sx, sy, sWidth, sHeight, 0, 0, targetWidth, targetHeight);

    const base64Data = canvas.toDataURL('image/jpeg', 0.90);
    
    // Set to hidden input and update preview
    document.getElementById('inputPhotoBase64').value = base64Data;
    
    // Clear file input so base64 takes precedence
    const fileInput = document.getElementById('inputPhotoFile');
    if (fileInput) fileInput.value = '';

    const imgPreview = document.getElementById('imgPhotoPreview');
    const placeholderText = document.getElementById('placeholderPhotoText');
    imgPreview.src = base64Data;
    imgPreview.style.display = 'block';
    if (placeholderText) placeholderText.style.display = 'none';

    stopLiveCamera();

    // Close modal via Bootstrap
    const modalEl = document.getElementById('modalLiveCamera');
    const modalObj = bootstrap.Modal.getInstance(modalEl);
    if (modalObj) {
        modalObj.hide();
    } else {
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.hide();
    }
}

function previewUploadedPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const imgPreview = document.getElementById('imgPhotoPreview');
            const placeholderText = document.getElementById('placeholderPhotoText');
            imgPreview.src = e.target.result;
            imgPreview.style.display = 'block';
            if (placeholderText) placeholderText.style.display = 'none';

            // Clear live camera base64 so file input is prioritized
            document.getElementById('inputPhotoBase64').value = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const inputRef = document.getElementById('inputReferralCode');
    const feedback = document.getElementById('referralFeedback');
    const btnVerify = document.getElementById('btnVerifyReferral');
    let debounceTimer = null;

    function verifyReferralCode() {
        const code = inputRef.value.trim();
        if (!code) {
            feedback.innerHTML = `
                <div class="alert alert-secondary py-2 px-3 mb-0 rounded-3 border d-flex align-items-center gap-2 small">
                    <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                    <div>
                        <strong>Direct Corporate Enrollment:</strong> You are registering directly under <strong>SVPL Direct Operations</strong>.
                    </div>
                </div>
            `;
            return;
        }

        feedback.innerHTML = `
            <div class="text-primary small py-1">
                <span class="spinner-border spinner-border-sm me-1" role="status"></span> Looking up sponsor details...
            </div>
        `;

        fetch('<?= url('/api/validate-referral') ?>?code=' + encodeURIComponent(code))
            .then(res => res.json())
            .then(data => {
                if (data.valid && data.advisor) {
                    feedback.innerHTML = `
                        <div class="alert alert-success py-2 px-3 mb-0 rounded-3 border border-success-subtle shadow-sm d-flex align-items-center gap-3">
                            <div style="background: #10B981; color: #FFFFFF; width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                                    <div class="fw-bold text-navy" style="font-size: 0.95rem;">
                                        Verified Sponsor: <span class="text-success">${data.advisor.name}</span>
                                    </div>
                                    <span class="badge bg-success" style="font-size: 0.68rem;">🟢 ${data.advisor.status || 'ACTIVE'}</span>
                                </div>
                                <div class="small text-secondary mt-1">
                                    Advisor Code: <strong class="font-monospace text-dark">${data.advisor.code}</strong> | 
                                    Referral Code: <strong class="font-monospace text-primary">${data.advisor.referral_code}</strong> | 
                                    Location: <strong class="text-dark">${data.advisor.district || 'Odisha'}</strong>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    feedback.innerHTML = `
                        <div class="alert alert-danger py-2 px-3 mb-0 rounded-3 border border-danger-subtle d-flex align-items-center gap-2 small">
                            <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                            <div>
                                <strong class="text-danger">Invalid Sponsor Code:</strong> ${data.message || 'No active advisor found matching "' + code + '"'}.
                                <div class="text-muted">Please check with your sponsor or leave blank to enroll under SVPL Direct Operations.</div>
                            </div>
                        </div>
                    `;
                }
            })
            .catch(err => {
                feedback.innerHTML = `
                    <div class="text-warning small"><i class="bi bi-exclamation-triangle me-1"></i> Unable to verify code at the moment.</div>
                `;
            });
    }

    if (inputRef) {
        inputRef.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(verifyReferralCode, 400);
        });

        btnVerify?.addEventListener('click', verifyReferralCode);

        // Auto verify if referral code is prefilled
        if (inputRef.value.trim()) {
            verifyReferralCode();
        }
    }
});
</script>
