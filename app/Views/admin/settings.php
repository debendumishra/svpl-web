<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin System Settings & Company Branding View
 */
$title = "System Settings & Company Branding — " . company_short_name() . " Admin";
$currentLogo = company_logo_url();
$currentFavicon = company_favicon_url();
$currentSignature = company_signature_url();
$hasCustomSignature = !empty(company_setting('company_signature'));
?>

<?php if (!empty($successMsg)): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($successMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($errorMsg)): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($errorMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="m-0 font-outfit fw-bold text-navy">
            <i class="bi bi-sliders2 me-2 text-primary"></i> System Settings & Company Branding
        </h4>
        <p class="text-muted small mb-0">Change company logo, brand names, registered address, authorized signature, and business rules across all portals and documents.</p>
    </div>
</div>

<form action="<?= url('/admin/settings') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        
        <!-- ========================================== -->
        <!-- 1. COMPANY BRANDING & LOGO IDENTITY       -->
        <!-- ========================================== -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-navy text-white py-3">
                    <h6 class="m-0 font-outfit fw-bold">
                        <i class="bi bi-palette-fill me-2 text-warning"></i> 1. Company Identity & Visual Branding
                    </h6>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Logo Upload Section -->
                    <div class="row g-4 mb-4 pb-4 border-bottom align-items-center">
                        <div class="col-lg-4 text-center">
                            <label class="form-label small fw-bold text-navy d-block mb-2">Active Company Logo</label>
                            
                            <!-- Dual Preview Container: Light & Dark Mode Simulation -->
                            <div class="border rounded-3 p-3 bg-light shadow-sm mb-2">
                                <div class="p-3 rounded-2 mb-2 d-flex align-items-center justify-content-center" style="background: #ffffff; min-height: 85px; border: 1px dashed #cbd5e1;">
                                    <?php if (!empty($currentLogo)): ?>
                                        <img id="logoPreviewLight" src="<?= htmlspecialchars($currentLogo) ?>" alt="Logo Preview" style="max-height: 60px; max-width: 100%; object-fit: contain;">
                                    <?php else: ?>
                                        <div id="defaultEmblemLight" class="d-flex align-items-center gap-2">
                                            <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900; font-size: 1.4rem;">
                                                ☀
                                            </div>
                                            <div class="text-start">
                                                <strong class="text-navy font-heading d-block" style="font-size: 1rem;"><?= htmlspecialchars(company_name()) ?></strong>
                                                <small class="text-muted" style="font-size: 0.7rem;">Default Solar Emblem</small>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="p-3 rounded-2 d-flex align-items-center justify-content-center" style="background: #0f2d59; min-height: 85px; border: 1px solid #1e3a8a;">
                                    <?php if (!empty($currentLogo)): ?>
                                        <img id="logoPreviewDark" src="<?= htmlspecialchars($currentLogo) ?>" alt="Logo Dark Preview" style="max-height: 60px; max-width: 100%; object-fit: contain;">
                                    <?php else: ?>
                                        <div id="defaultEmblemDark" class="d-flex align-items-center gap-2">
                                            <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900; font-size: 1.4rem;">
                                                ☀
                                            </div>
                                            <div class="text-start">
                                                <strong class="text-white font-heading d-block" style="font-size: 1rem;"><?= htmlspecialchars(company_name()) ?></strong>
                                                <small class="text-warning" style="font-size: 0.7rem;">Default Solar Emblem</small>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if (!empty($currentLogo)): ?>
                                <button type="button" class="btn btn-outline-danger btn-sm fw-bold w-100 mt-2" onclick="triggerRemoveLogo()">
                                    <i class="bi bi-trash3 me-1"></i> Remove Custom Logo (Reset to Emblem)
                                </button>
                                <input type="hidden" name="remove_logo" id="inputRemoveLogo" value="0">
                            <?php endif; ?>
                        </div>

                        <div class="col-lg-8">
                            <div class="p-3 bg-light rounded-3 border">
                                <h6 class="fw-bold text-navy mb-2"><i class="bi bi-cloud-arrow-up-fill text-primary me-2"></i> Upload New Company Logo</h6>
                                <p class="text-muted small mb-3">
                                    The uploaded logo will immediately appear across <strong>Public Website Navbars</strong>, <strong>Admin, BOE, Advisor & Customer Portals</strong>, and all <strong>Printable ID Cards, Receipts, Quotations, and Appointment Letters</strong>.
                                </p>
                                
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="form-label small fw-semibold text-navy">Select Logo Image File</label>
                                        <input type="file" name="company_logo" id="inputCompanyLogo" class="form-control form-control-sm" accept="image/png,image/svg+xml,image/jpeg,image/webp" onchange="previewLogoFile(this)">
                                        <small class="text-muted" style="font-size: 0.72rem;">Recommended: Transparent PNG or SVG (approx. 300 × 80 px, max 2MB).</small>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small fw-semibold text-navy">Or Capture via Webcam</label>
                                        <button type="button" class="btn btn-outline-success btn-sm w-100 fw-bold" onclick="openLogoCamera()">
                                            <i class="bi bi-camera-video me-1"></i> Capture via Camera
                                        </button>
                                        <input type="hidden" name="company_logo_base64" id="inputLogoBase64" value="">
                                    </div>
                                </div>

                                <div class="row g-3 mt-2 pt-2 border-top">
                                    <div class="col-md-7">
                                        <label class="form-label small fw-semibold text-navy"><i class="bi bi-globe2 text-info me-1"></i> Browser Favicon (.ico / .png / .svg)</label>
                                        <input type="file" name="company_favicon" class="form-control form-control-sm" accept="image/x-icon,image/png,image/svg+xml">
                                        <small class="text-muted" style="font-size: 0.72rem;">Displays in browser tab title bar (16×16 or 32×32 px).</small>
                                    </div>
                                    <?php if (!empty($currentFavicon)): ?>
                                        <div class="col-md-5 d-flex align-items-center gap-2 mt-4">
                                            <img src="<?= htmlspecialchars($currentFavicon) ?>" alt="Favicon" style="width: 24px; height: 24px; object-fit: contain;">
                                            <span class="small text-muted">Current Favicon Active</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Authorized Signatory Seal & Signature Section -->
                    <div class="row g-4 mb-4 pb-4 border-bottom align-items-center">
                        <div class="col-lg-4 text-center">
                            <label class="form-label small fw-bold text-navy d-block mb-2">Active Authorized Seal & Signature</label>
                            
                            <div class="border rounded-3 p-3 bg-light shadow-sm mb-2">
                                <div class="p-3 rounded-2 d-flex align-items-center justify-content-center" style="background: #ffffff; min-height: 85px; border: 1px dashed #cbd5e1;">
                                    <img id="signaturePreview" src="<?= htmlspecialchars($currentSignature) ?>" alt="Signature Preview" style="max-height: 55px; max-width: 100%; object-fit: contain;">
                                </div>
                            </div>

                            <?php if (!empty($hasCustomSignature)): ?>
                                <button type="button" class="btn btn-outline-danger btn-sm fw-bold w-100 mt-2" onclick="triggerRemoveSignature()">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset to Default Seal
                                </button>
                                <input type="hidden" name="remove_signature" id="inputRemoveSignature" value="0">
                            <?php endif; ?>
                        </div>

                        <div class="col-lg-8">
                            <div class="p-3 bg-light rounded-3 border">
                                <h6 class="fw-bold text-navy mb-2"><i class="bi bi-pen-fill text-primary me-2"></i> Update Authorized Signatory Seal & Signature</h6>
                                <p class="text-muted small mb-3">
                                    This signature seal image is automatically rendered on <strong>Advisor ID Cards</strong>, <strong>BOE Staff ID Cards</strong>, and official business certificates.
                                </p>
                                
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="form-label small fw-semibold text-navy">Select Signature Image File</label>
                                        <input type="file" name="company_signature" id="inputCompanySignature" class="form-control form-control-sm" accept="image/png,image/svg+xml,image/jpeg,image/webp" onchange="previewSignatureFile(this)">
                                        <small class="text-muted" style="font-size: 0.72rem;">Recommended: Transparent PNG or JPG (approx. 260 × 85 px with signature & seal text).</small>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small fw-semibold text-navy">Or Capture via Webcam</label>
                                        <button type="button" class="btn btn-outline-success btn-sm w-100 fw-bold" onclick="openSignatureCamera()">
                                            <i class="bi bi-camera-video me-1"></i> Capture via Camera
                                        </button>
                                        <input type="hidden" name="company_signature_base64" id="inputSignatureBase64" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Company Text Fields -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Full Legal Company Name *</label>
                            <input type="text" name="company_name" class="form-control fw-bold" value="<?= htmlspecialchars($settings['company_name'] ?? 'Surya Vistaara Pvt. Ltd.') ?>" required>
                            <small class="text-muted" style="font-size: 0.75rem;">Appears in all official documents, copyright notices, and receipts.</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-navy">Short Brand Name / Acronym *</label>
                            <input type="text" name="company_short_name" class="form-control fw-bold text-primary font-monospace" value="<?= htmlspecialchars($settings['company_short_name'] ?? 'SVPL') ?>" required>
                            <small class="text-muted" style="font-size: 0.75rem;">Used in navbar badges & codes.</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-navy">Promoter Entity Name</label>
                            <input type="text" name="promoter_entity" class="form-control fw-semibold" value="<?= htmlspecialchars($settings['promoter_entity'] ?? 'Dhwajja Solar India Pvt. Ltd.') ?>">
                            <small class="text-muted" style="font-size: 0.75rem;">Corporate partner entity.</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-navy">Brand Tagline / Website Slogan</label>
                            <input type="text" name="company_tagline" class="form-control" value="<?= htmlspecialchars($settings['company_tagline'] ?? 'PM Surya Ghar Odisha Rooftop Solar Scheme & Portal') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Corporate Registered Office Address</label>
                            <textarea name="head_office_address" class="form-control" rows="2"><?= htmlspecialchars($settings['head_office_address'] ?? 'MIG-84, Pokhariput, BDA Colony, Phase-1, Bhubaneswar, Khorda – 751020, Odisha') ?></textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-navy">Company GSTIN Number</label>
                            <input type="text" name="gstin" class="form-control font-monospace fw-bold" value="<?= htmlspecialchars($settings['gstin'] ?? '21AAMCD5948B1ZU') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-navy">Company CIN / Reg. No.</label>
                            <input type="text" name="cin" class="form-control font-monospace" value="<?= htmlspecialchars($settings['cin'] ?? '') ?>" placeholder="U40106OR2024PTC...">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 2. SUPPORT & CONTACT CHANNELS              -->
        <!-- ========================================== -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-outfit fw-bold text-navy">
                        <i class="bi bi-headset me-2 text-success"></i> 2. Public Support & Helplines
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-navy"><i class="bi bi-telephone-fill text-success me-1"></i> Central Helpline Phone Number</label>
                            <input type="text" name="support_phone" class="form-control fw-bold" value="<?= htmlspecialchars($settings['support_phone'] ?? '9040999899') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-navy"><i class="bi bi-whatsapp text-success me-1"></i> WhatsApp Support Number</label>
                            <input type="text" name="support_whatsapp" class="form-control" value="<?= htmlspecialchars($settings['support_whatsapp'] ?? '9040999899') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-navy"><i class="bi bi-envelope-fill text-primary me-1"></i> Official Support & Escalation Email</label>
                            <input type="email" name="support_email" class="form-control" value="<?= htmlspecialchars($settings['support_email'] ?? 'dhwajjasolarsupport@gmail.com') ?>" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 3. BUSINESS RULES & COMMISSION PARAMETERS  -->
        <!-- ========================================== -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-outfit fw-bold text-navy">
                        <i class="bi bi-calculator me-2 text-warning"></i> 3. Business Rules & Financial Slabs
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Advisor Joining Fee (₹)</label>
                            <input type="number" step="1" name="advisor_joining_fee" class="form-control font-monospace fw-bold text-navy" value="<?= htmlspecialchars($settings['advisor_joining_fee'] ?? '1500.00') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Qualification Target (Customers)</label>
                            <input type="number" name="advisor_required_customers" class="form-control fw-bold" value="<?= htmlspecialchars($settings['advisor_required_customers'] ?? '3') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Direct Customer Bonus (₹)</label>
                            <input type="number" step="1" name="direct_customer_bonus" class="form-control font-monospace fw-bold text-success" value="<?= htmlspecialchars($settings['direct_customer_bonus'] ?? '500.00') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Statutory TDS Deduction (%)</label>
                            <input type="number" step="0.1" name="tds_percentage" class="form-control font-monospace fw-bold" value="<?= htmlspecialchars($settings['tds_percentage'] ?? '5.00') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Solar Loan Interest Rate (% p.a.)</label>
                            <input type="number" step="0.01" name="solar_loan_interest_rate" class="form-control font-monospace" value="<?= htmlspecialchars($settings['solar_loan_interest_rate'] ?? '5.60') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Min. Wallet Withdrawal (₹)</label>
                            <input type="number" step="1" name="min_payout_threshold" class="form-control font-monospace" value="<?= htmlspecialchars($settings['min_payout_threshold'] ?? '500.00') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="col-12 text-end mb-4">
            <button type="submit" class="btn btn-primary btn-lg fw-bold px-5 shadow-sm">
                <i class="bi bi-save2-fill me-2"></i> Save & Apply Settings Everywhere
            </button>
        </div>

    </div>
</form>

<!-- Modal: Webcam Logo Capture -->
<div class="modal fade" id="modalLogoCamera" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white py-2">
                <h6 class="modal-title font-outfit fw-bold">
                    <i class="bi bi-camera-video-fill me-2 text-warning"></i> Capture Company Logo Asset
                </h6>
                <button type="button" class="btn-close btn-close-white" onclick="closeLogoCamera()"></button>
            </div>
            <div class="modal-body text-center p-3 bg-dark">
                <div style="width: 320px; height: 180px; margin: 0 auto; position: relative; overflow: hidden; border-radius: 8px; background: #000; border: 2px solid #38bdf8;">
                    <video id="logoWebcamVideo" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                </div>
                <div id="logoCameraStatusText" class="text-light small mt-2">
                    <span class="spinner-border spinner-border-sm me-1" role="status"></span> Accessing camera...
                </div>
            </div>
            <div class="modal-footer bg-light justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeLogoCamera()">Cancel</button>
                <button type="button" class="btn btn-success fw-bold px-4" id="btnSnapLogo" onclick="captureLogoSnapshot()" disabled>
                    <i class="bi bi-camera-fill me-1"></i> Capture Logo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Webcam Signature Capture -->
<div class="modal fade" id="modalSignatureCamera" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white py-2">
                <h6 class="modal-title font-outfit fw-bold">
                    <i class="bi bi-camera-video-fill me-2 text-warning"></i> Capture Authorized Seal & Signature
                </h6>
                <button type="button" class="btn-close btn-close-white" onclick="closeSignatureCamera()"></button>
            </div>
            <div class="modal-body text-center p-3 bg-dark">
                <div style="width: 320px; height: 180px; margin: 0 auto; position: relative; overflow: hidden; border-radius: 8px; background: #000; border: 2px solid #38bdf8;">
                    <video id="signatureWebcamVideo" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                </div>
                <div id="signatureCameraStatusText" class="text-light small mt-2">
                    <span class="spinner-border spinner-border-sm me-1" role="status"></span> Accessing camera...
                </div>
            </div>
            <div class="modal-footer bg-light justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeSignatureCamera()">Cancel</button>
                <button type="button" class="btn btn-success fw-bold px-4" id="btnSnapSignature" onclick="captureSignatureSnapshot()" disabled>
                    <i class="bi bi-camera-fill me-1"></i> Capture Signature
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let logoMediaStream = null;
let signatureMediaStream = null;

function previewLogoFile(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            updateLogoPreviews(e.target.result);
            document.getElementById('inputLogoBase64').value = '';
            const removeInput = document.getElementById('inputRemoveLogo');
            if (removeInput) removeInput.value = '0';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function updateLogoPreviews(src) {
    let previewL = document.getElementById('logoPreviewLight');
    let previewD = document.getElementById('logoPreviewDark');

    const defLight = document.getElementById('defaultEmblemLight');
    const defDark = document.getElementById('defaultEmblemDark');
    if (defLight) defLight.style.display = 'none';
    if (defDark) defDark.style.display = 'none';

    if (!previewL) {
        previewL = document.createElement('img');
        previewL.id = 'logoPreviewLight';
        previewL.style = 'max-height: 60px; max-width: 100%; object-fit: contain;';
        const parentL = document.querySelector('.border.rounded-3 .p-3.rounded-2.mb-2');
        if (parentL) parentL.appendChild(previewL);
    }
    if (!previewD) {
        previewD = document.createElement('img');
        previewD.id = 'logoPreviewDark';
        previewD.style = 'max-height: 60px; max-width: 100%; object-fit: contain;';
        const parentD = document.querySelector('.border.rounded-3 .p-3.rounded-2:not(.mb-2)');
        if (parentD) parentD.appendChild(previewD);
    }

    if (previewL) {
        previewL.src = src;
        previewL.style.display = 'block';
    }
    if (previewD) {
        previewD.src = src;
        previewD.style.display = 'block';
    }
}

function triggerRemoveLogo() {
    if (confirm('Are you sure you want to remove the custom logo and revert to the default solar brand emblem?')) {
        document.getElementById('inputRemoveLogo').value = '1';
        const previewL = document.getElementById('logoPreviewLight');
        const previewD = document.getElementById('logoPreviewDark');
        if (previewL) previewL.style.display = 'none';
        if (previewD) previewD.style.display = 'none';
        const defLight = document.getElementById('defaultEmblemLight');
        const defDark = document.getElementById('defaultEmblemDark');
        if (defLight) defLight.style.display = 'flex';
        if (defDark) defDark.style.display = 'flex';
        document.getElementById('inputCompanyLogo').value = '';
        document.getElementById('inputLogoBase64').value = '';
    }
}

function openLogoCamera() {
    const modalEl = document.getElementById('modalLogoCamera');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    const video = document.getElementById('logoWebcamVideo');
    const btnSnap = document.getElementById('btnSnapLogo');
    const statusText = document.getElementById('logoCameraStatusText');

    btnSnap.disabled = true;
    statusText.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Accessing camera...';

    navigator.mediaDevices.getUserMedia({
        video: { width: { ideal: 640 }, height: { ideal: 360 } },
        audio: false
    }).then(stream => {
        logoMediaStream = stream;
        video.srcObject = stream;
        video.onloadedmetadata = () => {
            video.play();
            btnSnap.disabled = false;
            statusText.innerHTML = '<i class="bi bi-check-circle text-success me-1"></i> Camera ready. Position logo and click Capture Logo.';
        };
    }).catch(err => {
        statusText.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-octagon me-1"></i> Camera error: ' + (err.message || 'Access denied') + '</span>';
    });
}

function closeLogoCamera() {
    if (logoMediaStream) {
        logoMediaStream.getTracks().forEach(track => track.stop());
        logoMediaStream = null;
    }
    const modalEl = document.getElementById('modalLogoCamera');
    const modalObj = bootstrap.Modal.getInstance(modalEl);
    if (modalObj) modalObj.hide();
}

function captureLogoSnapshot() {
    const video = document.getElementById('logoWebcamVideo');
    if (!video || !logoMediaStream) return;

    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 360;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    const base64Data = canvas.toDataURL('image/png');
    document.getElementById('inputLogoBase64').value = base64Data;
    document.getElementById('inputCompanyLogo').value = '';
    updateLogoPreviews(base64Data);

    const removeInput = document.getElementById('inputRemoveLogo');
    if (removeInput) removeInput.value = '0';

    closeLogoCamera();
}

// Signature Upload & Webcam Functions
function previewSignatureFile(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            updateSignaturePreview(e.target.result);
            document.getElementById('inputSignatureBase64').value = '';
            const removeInput = document.getElementById('inputRemoveSignature');
            if (removeInput) removeInput.value = '0';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function updateSignaturePreview(src) {
    const preview = document.getElementById('signaturePreview');
    if (preview) {
        preview.src = src;
    }
}

function triggerRemoveSignature() {
    if (confirm('Are you sure you want to reset the custom signature and revert to the default official seal?')) {
        const removeInput = document.getElementById('inputRemoveSignature');
        if (removeInput) removeInput.value = '1';
        document.getElementById('inputCompanySignature').value = '';
        document.getElementById('inputSignatureBase64').value = '';
        updateSignaturePreview('<?= url('/assets/images/authorised_signatory.png') ?>');
    }
}

function openSignatureCamera() {
    const modalEl = document.getElementById('modalSignatureCamera');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    const video = document.getElementById('signatureWebcamVideo');
    const btnSnap = document.getElementById('btnSnapSignature');
    const statusText = document.getElementById('signatureCameraStatusText');

    btnSnap.disabled = true;
    statusText.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Accessing camera...';

    navigator.mediaDevices.getUserMedia({
        video: { width: { ideal: 640 }, height: { ideal: 360 } },
        audio: false
    }).then(stream => {
        signatureMediaStream = stream;
        video.srcObject = stream;
        video.onloadedmetadata = () => {
            video.play();
            btnSnap.disabled = false;
            statusText.innerHTML = '<i class="bi bi-check-circle text-success me-1"></i> Camera ready. Position signature/seal and click Capture Signature.';
        };
    }).catch(err => {
        statusText.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-octagon me-1"></i> Camera error: ' + (err.message || 'Access denied') + '</span>';
    });
}

function closeSignatureCamera() {
    if (signatureMediaStream) {
        signatureMediaStream.getTracks().forEach(track => track.stop());
        signatureMediaStream = null;
    }
    const modalEl = document.getElementById('modalSignatureCamera');
    const modalObj = bootstrap.Modal.getInstance(modalEl);
    if (modalObj) modalObj.hide();
}

function captureSignatureSnapshot() {
    const video = document.getElementById('signatureWebcamVideo');
    if (!video || !signatureMediaStream) return;

    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 360;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    const base64Data = canvas.toDataURL('image/png');
    document.getElementById('inputSignatureBase64').value = base64Data;
    document.getElementById('inputCompanySignature').value = '';
    updateSignaturePreview(base64Data);

    const removeInput = document.getElementById('inputRemoveSignature');
    if (removeInput) removeInput.value = '0';

    closeSignatureCamera();
}
</script>
