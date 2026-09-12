<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Edit Advisor View - Solar Luminary Design System
 */
$title = "Edit Advisor: " . htmlspecialchars($advisor['advisor_code']) . " — SVPL Admin";
$allAdvisors = $allAdvisors ?? [];
$success = $success ?? null;
$error = $error ?? null;
?>

<div class="container-fluid px-3 px-lg-4 py-3">
    <!-- PAGE HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="<?= url('/admin/advisors') ?>" class="btn btn-outline-secondary btn-sm py-0 px-2" title="Back to Advisor Directory">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="badge bg-navy text-warning fw-bold px-2 py-1">
                    <i class="bi bi-person-badge me-1"></i> Advisor Profile
                </span>
                <span class="badge bg-light text-dark font-monospace border fw-bold">
                    <?= htmlspecialchars($advisor['advisor_code']) ?>
                </span>
                <?php if ($advisor['status'] === 'ACTIVE'): ?>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">🟢 ACTIVE</span>
                <?php elseif ($advisor['status'] === 'PENDING_APPROVAL'): ?>
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">🟡 PENDING APPROVAL</span>
                <?php else: ?>
                    <span class="badge bg-secondary-subtle text-secondary border"><?= htmlspecialchars($advisor['status']) ?></span>
                <?php endif; ?>
            </div>
            <h3 class="font-heading fw-bold mb-0 text-navy">
                Modify Advisor Data: <span class="text-primary"><?= htmlspecialchars($advisor['first_name'] . ' ' . $advisor['last_name']) ?></span>
            </h3>
            <p class="text-secondary small mb-0">Update personal credentials, Odisha location, statutory KYC, bank account, and network hierarchy.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="<?= url('/print/id-card/' . $advisor['id']) ?>" target="_blank" class="btn btn-outline-primary btn-sm fw-semibold shadow-sm">
                <i class="bi bi-person-vcard me-1"></i> Print ID Card
            </a>
            <a href="<?= url('/admin/network-tree?root_id=' . $advisor['id']) ?>" class="btn btn-outline-success btn-sm fw-semibold shadow-sm">
                <i class="bi bi-diagram-3 me-1"></i> View 9-Tree
            </a>
            <a href="<?= url('/admin/advisors') ?>" class="btn btn-light border btn-sm text-secondary">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </a>
        </div>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div><strong>Success:</strong> <?= htmlspecialchars($success) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
            <div><strong>Error:</strong> <?= htmlspecialchars($error) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5">
            <form method="POST" action="<?= url('/admin/advisors/' . $advisor['id'] . '/edit') ?>" enctype="multipart/form-data">
                <?= function_exists('csrf_field') ? csrf_field() : '' ?>

                <!-- 1. PERSONAL INFORMATION & PHOTO -->
                <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom border-2" style="border-color: #E2E8F0 !important;">
                    <span class="badge bg-primary text-white rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                    <h5 class="fw-bold mb-0 text-navy font-heading">Personal Information & Official Photograph</h5>
                </div>

                <div class="row g-4 mb-4">
                    <!-- Photo Column -->
                    <div class="col-md-3 text-center">
                        <label class="form-label fw-semibold text-navy small d-block mb-2">Advisor Photo (ID Card)</label>
                        <div class="d-inline-block position-relative border-2 border-dashed border-primary rounded-3 p-1 bg-light shadow-sm mb-2" style="width: 120px; height: 145px; overflow: hidden;" id="photoPreviewContainer">
                            <?php if (!empty($advisor['photo_url'])): ?>
                                <img id="imgPhotoPreview" src="<?= url('/' . ltrim($advisor['photo_url'], '/')) ?>" alt="Advisor Photo" class="w-100 h-100 rounded-2" style="object-fit: cover;">
                            <?php else: ?>
                                <img id="imgPhotoPreview" src="" alt="Advisor Photo" class="w-100 h-100 rounded-2" style="object-fit: cover; display: none;">
                                <div id="placeholderPhotoText" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted small">
                                    <i class="bi bi-person-bounding-box fs-1 text-secondary mb-1"></i>
                                    <span style="font-size: 0.72rem;" class="fw-bold">NO PHOTO</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-center gap-1 mb-2">
                            <button type="button" class="btn btn-outline-warning btn-sm py-0 px-2 fw-semibold" style="font-size: 0.68rem;" onclick="triggerPhotoStudio('imgPhotoPreview', 'placeholderPhotoText', 'inputPhotoBase64', 'inputPhotoFile')">
                                <i class="bi bi-crop me-1"></i> Crop / BG
                            </button>
                            <span class="badge bg-secondary-subtle text-secondary align-self-center" style="font-size: 0.68rem;">3:4 Ratio</span>
                        </div>

                        <div>
                            <input type="file" name="advisor_photo" id="inputPhotoFile" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp" onchange="previewUploadedPhoto(this)">
                            <small class="text-muted d-block mt-1" style="font-size: 0.70rem;">Upload passport photo (Auto-opens Studio)</small>
                            
                            <button type="button" class="btn btn-outline-success btn-sm w-100 mt-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalLiveCamera" onclick="startLiveCamera()">
                                <i class="bi bi-camera-fill me-1"></i> Capture Live Photo
                            </button>
                        </div>
                        <input type="hidden" name="advisor_photo_base64" id="inputPhotoBase64" value="">
                        <small class="text-muted d-block mt-2" style="font-size: 0.70rem;">
                            <i class="bi bi-magic text-primary me-1"></i> Auto-crops 3:4 & replaces backdrop with 1-click Studio white or sky-blue.
                        </small>
                    </div>

                    <!-- Personal Inputs -->
                    <div class="col-md-9">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-navy small">First Name *</label>
                                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($advisor['first_name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-navy small">Last Name *</label>
                                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($advisor['last_name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-navy small">Father / Husband / Spouse Name</label>
                                <input type="text" name="father_spouse_name" class="form-control" value="<?= htmlspecialchars($advisor['father_spouse_name'] ?? '') ?>" placeholder="Father or Spouse Name">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-navy small">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="<?= htmlspecialchars($advisor['dob'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-navy small">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="Male" <?= ($advisor['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($advisor['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                                    <option value="Other" <?= ($advisor['gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-navy small"><i class="bi bi-droplet-fill text-danger me-1"></i> Blood Group *</label>
                                <select name="blood_group" class="form-select fw-semibold" required>
                                    <?php 
                                    $bgList = ['O+ve', 'A+ve', 'B+ve', 'AB+ve', 'O-ve', 'A-ve', 'B-ve', 'AB-ve'];
                                    $currentBg = $advisor['blood_group'] ?? 'O+ve';
                                    foreach ($bgList as $bg): ?>
                                        <option value="<?= $bg ?>" <?= $currentBg === $bg ? 'selected' : '' ?>><?= $bg ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-navy small">Advisor Code (System Generated)</label>
                                <input type="text" class="form-control font-monospace fw-bold bg-light" value="<?= htmlspecialchars($advisor['advisor_code'] ?? '') ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-navy small">Referral Code</label>
                                <input type="text" class="form-control font-monospace fw-bold bg-light text-primary" value="<?= htmlspecialchars($advisor['referral_code'] ?? '') ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. CONTACT & ODISHA LOCATION -->
                <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom border-2 mt-5" style="border-color: #E2E8F0 !important;">
                    <span class="badge bg-primary text-white rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                    <h5 class="fw-bold mb-0 text-navy font-heading">Contact & Odisha Location Details</h5>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-navy small">Mobile Number (Primary Login ID) *</label>
                        <input type="tel" name="mobile" class="form-control font-monospace fw-bold" value="<?= htmlspecialchars($advisor['mobile'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-navy small">Alternate Mobile Number</label>
                        <input type="tel" name="alt_mobile" class="form-control font-monospace" value="<?= htmlspecialchars($advisor['alt_mobile'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-navy small">Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($advisor['email'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-navy small">State *</label>
                        <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($advisor['state'] ?? 'Odisha') ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">District *</label>
                        <select name="district" class="form-select" required>
                            <?php 
                            $districts = ['Angul', 'Balangir', 'Balasore', 'Bargarh', 'Bhadrak', 'Boudh', 'Cuttack', 'Deogarh', 'Dhenkanal', 'Gajapati', 'Ganjam', 'Jagatsinghpur', 'Jajpur', 'Jharsuguda', 'Kalahandi', 'Kandhamal', 'Kendrapara', 'Kendujhar', 'Khordha', 'Koraput', 'Malkangiri', 'Mayurbhanj', 'Nabarangpur', 'Nayagarh', 'Nuapada', 'Puri', 'Rayagada', 'Sambalpur', 'Subarnapur', 'Sundargarh'];
                            $curDistrict = $advisor['district'] ?? 'Khordha';
                            foreach ($districts as $d): ?>
                                <option value="<?= $d ?>" <?= $curDistrict === $d ? 'selected' : '' ?>><?= $d ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Block / Municipality *</label>
                        <input type="text" name="block" class="form-control" value="<?= htmlspecialchars($advisor['block'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Gram Panchayat *</label>
                        <input type="text" name="gram_panchayat" class="form-control" value="<?= htmlspecialchars($advisor['gram_panchayat'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Village / Ward</label>
                        <input type="text" name="village" class="form-control" value="<?= htmlspecialchars($advisor['village'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Pincode (6-digit) *</label>
                        <input type="text" name="pincode" class="form-control font-monospace" value="<?= htmlspecialchars($advisor['pincode'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Full Postal Address</label>
                        <input type="text" name="address_line" class="form-control" value="<?= htmlspecialchars($advisor['address_line'] ?? '') ?>" placeholder="Plot No, Street, Landmark">
                    </div>
                </div>

                <!-- 3. STATUTORY KYC & BANK DETAILS -->
                <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom border-2 mt-5" style="border-color: #E2E8F0 !important;">
                    <span class="badge bg-primary text-white rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                    <h5 class="fw-bold mb-0 text-navy font-heading">Statutory KYC & Direct Bank Account</h5>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-navy small">Aadhaar Number (12-Digit)</label>
                        <input type="text" name="aadhaar_number" class="form-control font-monospace" maxlength="14" value="<?= htmlspecialchars($advisor['aadhaar_number'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-navy small">PAN Card Number (10-Characters)</label>
                        <input type="text" name="pan_number" class="form-control font-monospace text-uppercase" maxlength="10" value="<?= htmlspecialchars($advisor['pan_number'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="<?= htmlspecialchars($advisor['bank_name'] ?? '') ?>" placeholder="e.g. State Bank of India">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Bank Branch</label>
                        <input type="text" name="bank_branch" class="form-control" value="<?= htmlspecialchars($advisor['bank_branch'] ?? '') ?>" placeholder="e.g. Bhubaneswar Main">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Account Holder Name</label>
                        <input type="text" name="account_holder" class="form-control" value="<?= htmlspecialchars($advisor['account_holder'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-navy small">Bank Account Number</label>
                        <input type="text" name="account_number" class="form-control font-monospace" value="<?= htmlspecialchars($advisor['account_number'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-navy small">Bank IFSC Code</label>
                        <input type="text" name="ifsc_code" class="form-control font-monospace text-uppercase" value="<?= htmlspecialchars($advisor['ifsc_code'] ?? '') ?>" placeholder="e.g. SBIN0001234">
                    </div>
                </div>

                <!-- 4. ACCOUNT STATUS & HIERARCHY -->
                <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom border-2 mt-5" style="border-color: #E2E8F0 !important;">
                    <span class="badge bg-primary text-white rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">4</span>
                    <h5 class="fw-bold mb-0 text-navy font-heading">Operational Status, Onboarding Fee & Sponsoring Hierarchy</h5>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Account Status *</label>
                        <select name="status" class="form-select fw-bold">
                            <option value="ACTIVE" <?= ($advisor['status'] ?? '') === 'ACTIVE' ? 'selected' : '' ?>>🟢 ACTIVE (Allowed to log in & enroll)</option>
                            <option value="PENDING_APPROVAL" <?= ($advisor['status'] ?? '') === 'PENDING_APPROVAL' ? 'selected' : '' ?>>🟡 PENDING APPROVAL (Payment pending)</option>
                            <option value="QUALIFIED" <?= ($advisor['status'] ?? '') === 'QUALIFIED' ? 'selected' : '' ?>>⭐ QUALIFIED (3+ Direct installs completed)</option>
                            <option value="PAYMENT_REJECTED" <?= ($advisor['status'] ?? '') === 'PAYMENT_REJECTED' ? 'selected' : '' ?>>🔴 PAYMENT REJECTED</option>
                            <option value="SUSPENDED" <?= ($advisor['status'] ?? '') === 'SUSPENDED' ? 'selected' : '' ?>>⛔ SUSPENDED</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Onboarding Fee Status (₹<?= number_format((float)($advisor['joining_fee'] ?? advisor_joining_fee())) ?>) *</label>
                        <select name="joining_fee_paid" class="form-select fw-bold">
                            <option value="1" <?= ((int)($advisor['joining_fee_paid'] ?? 0)) === 1 ? 'selected' : '' ?>>✅ Paid & Reconciled (₹<?= number_format((float)($advisor['joining_fee'] ?? advisor_joining_fee())) ?>)</option>
                            <option value="0" <?= ((int)($advisor['joining_fee_paid'] ?? 0)) === 0 ? 'selected' : '' ?>>⏳ Fee Unpaid / Pending</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Sponsoring Mentor / Advisor</label>
                        <select name="sponsor_id" class="form-select">
                            <option value="">-- Direct SVPL Operations (HQ) --</option>
                            <?php foreach ($allAdvisors as $sp): ?>
                                <option value="<?= $sp['id'] ?>" <?= ((int)($advisor['sponsor_id'] ?? 0)) === (int)$sp['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($sp['advisor_code']) ?> — <?= htmlspecialchars($sp['first_name'] . ' ' . $sp['last_name']) ?> (<?= htmlspecialchars($sp['district']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex align-items-center justify-content-between pt-4 border-top">
                    <a href="<?= url('/admin/advisors') ?>" class="btn btn-light border px-4 py-2">
                        <i class="bi bi-arrow-left me-1"></i> Back to Advisor Directory
                    </a>
                    <button type="submit" class="btn btn-svpl-solar btn-lg px-5 py-2 fw-bold shadow-sm">
                        <i class="bi bi-save2-fill me-2"></i> Save Advisor Changes
                    </button>
                </div>
            </form>
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
                    Position the advisor's face straight within the frame with good lighting for the official SVPL Identity Card.
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

function triggerPhotoStudio(previewId, placeholderId, base64InputId, fileInputId) {
    const preview = document.getElementById(previewId);
    const fileInput = document.getElementById(fileInputId);
    let src = (preview && preview.src && preview.src.length > 50) ? preview.src : null;

    if (!src && fileInput && fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (typeof window.openPhotoStudio === 'function') {
                window.openPhotoStudio(e.target.result, previewId, placeholderId, base64InputId);
            }
        };
        reader.readAsDataURL(fileInput.files[0]);
        return;
    }

    if (src && typeof window.openPhotoStudio === 'function') {
        window.openPhotoStudio(src, previewId, placeholderId, base64InputId);
    } else {
        alert('Please choose or capture a photo first, then use Crop / BG to adjust.');
    }
}

function captureLiveSnapshot() {
    const video = document.getElementById('liveCameraVideo');
    const canvas = document.getElementById('liveCameraCanvas');
    if (!video || !video.videoWidth) {
        alert('Camera stream is not ready yet. Please wait a moment.');
        return;
    }

    const targetWidth = 480;
    const targetHeight = 640;
    canvas.width = targetWidth;
    canvas.height = targetHeight;

    const ctx = canvas.getContext('2d');
    
    if (currentFacingMode === 'user') {
        ctx.translate(targetWidth, 0);
        ctx.scale(-1, 1);
    }

    const vWidth = video.videoWidth;
    const vHeight = video.videoHeight;
    const vAspect = vWidth / vHeight;
    const targetAspect = targetWidth / targetHeight;

    let sx = 0, sy = 0, sWidth = vWidth, sHeight = vHeight;
    if (vAspect > targetAspect) {
        sWidth = vHeight * targetAspect;
        sx = (vWidth - sWidth) / 2;
    } else {
        sHeight = vWidth / targetAspect;
        sy = (vHeight - sHeight) / 2;
    }

    ctx.drawImage(video, sx, sy, sWidth, sHeight, 0, 0, targetWidth, targetHeight);

    const base64Data = canvas.toDataURL('image/jpeg', 0.92);
    document.getElementById('inputPhotoBase64').value = base64Data;
    
    const fileInput = document.getElementById('inputPhotoFile');
    if (fileInput) fileInput.value = '';

    const imgPreview = document.getElementById('imgPhotoPreview');
    const placeholderText = document.getElementById('placeholderPhotoText');
    imgPreview.src = base64Data;
    imgPreview.style.display = 'block';
    if (placeholderText) placeholderText.style.display = 'none';

    stopLiveCamera();

    const modalEl = document.getElementById('modalLiveCamera');
    const modalObj = bootstrap.Modal.getInstance(modalEl);
    if (modalObj) {
        modalObj.hide();
    } else {
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.hide();
    }

    // Auto-open Photo Studio to refine crop & background
    if (typeof window.openPhotoStudio === 'function') {
        setTimeout(() => {
            window.openPhotoStudio(base64Data, 'imgPhotoPreview', 'placeholderPhotoText', 'inputPhotoBase64');
        }, 300);
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

            // Auto-open Photo Studio for 3:4 cropping and background removal
            if (typeof window.openPhotoStudio === 'function') {
                window.openPhotoStudio(e.target.result, 'imgPhotoPreview', 'placeholderPhotoText', 'inputPhotoBase64');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
