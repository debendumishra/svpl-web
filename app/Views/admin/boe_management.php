<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Back Office Executive (BOE) Staff Management
 */
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
        <h4 class="m-0 font-outfit fw-bold text-navy">Back Office Executive (BOE) Staff Management</h4>
        <p class="text-muted small mb-0">Register, modify jurisdiction, print official ID cards, and manage Back Office Executives.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#createBoeModal">
            <i class="bi bi-person-plus-fill me-1"></i> Add New BOE Staff
        </button>
        <a href="<?= url('/admin/boe/reports') ?>" class="btn btn-outline-info fw-bold">
            <i class="bi bi-file-earmark-bar-graph me-1"></i> Performance Reports
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="m-0 font-outfit fw-bold text-navy">
                <i class="bi bi-people-fill me-2 text-primary"></i> Registered Back Office Executives
            </h6>
            <span class="badge bg-light text-dark border fw-bold px-3 py-2">
                Total Staff: <?= count($boeList) ?>
            </span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead class="table-light">
                    <tr>
                        <th style="min-width: 220px;">Staff Profile</th>
                        <th>Employee Code</th>
                        <th>Designation & Jurisdiction</th>
                        <th>Contact Details</th>
                        <th>Assigned Workload</th>
                        <th>Status</th>
                        <th class="text-end" style="min-width: 260px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($boeList)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-person-x fs-1 text-muted d-block mb-2"></i>
                                No BOE staff registered yet. Click "<strong>Add New BOE Staff</strong>" above.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($boeList as $b): 
                            $photo = !empty($b['photo_url']) ? resolve_photo_url($b['photo_url']) : null;
                            $blood = !empty($b['blood_group']) ? $b['blood_group'] : 'O+ve';
                            $jurisdiction = !empty($b['jurisdiction']) ? $b['jurisdiction'] : 'All Odisha / Head Office';
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 48px; height: 56px; border-radius: 8px; overflow: hidden; background: #e2e8f0; flex-shrink: 0; border: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: center;">
                                        <?php if (!empty($photo)): ?>
                                            <img src="<?= htmlspecialchars($photo) ?>" alt="Photo" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <i class="bi bi-person-fill text-secondary fs-4"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <strong class="text-navy d-block" style="font-size: 0.95rem;"><?= htmlspecialchars($b['full_name']) ?></strong>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.72rem;">
                                                <i class="bi bi-droplet-fill"></i> <?= htmlspecialchars($blood) ?>
                                            </span>
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                Reg: <?= date('d M Y', strtotime($b['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong class="text-navy font-monospace px-2 py-1 bg-light rounded border">
                                    <?= htmlspecialchars($b['employee_code'] ?? ('SVPL-BOE-' . $b['id'])) ?>
                                </strong>
                            </td>
                            <td>
                                <div>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold mb-1">
                                        <?= htmlspecialchars($b['designation'] ?? 'Back Office Executive') ?>
                                    </span>
                                </div>
                                <small class="text-dark d-flex align-items-center gap-1" style="font-size: 0.78rem;">
                                    <i class="bi bi-geo-alt-fill text-danger"></i> <?= htmlspecialchars($jurisdiction) ?>
                                </small>
                            </td>
                            <td>
                                <div>
                                    <i class="bi bi-telephone-fill text-success small me-1"></i>
                                    <strong><?= htmlspecialchars($b['mobile']) ?></strong>
                                </div>
                                <small class="text-muted d-block"><?= htmlspecialchars($b['email'] ?? 'No email specified') ?></small>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle fw-semibold text-start">
                                        <i class="bi bi-people-fill me-1"></i> <?= $b['assigned_customers_count'] ?> Assigned
                                    </span>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold text-start">
                                        <i class="bi bi-check2-all me-1"></i> <?= $b['completed_stage_count'] ?> Completed
                                    </span>
                                </div>
                            </td>
                            <td>
                                <?php if ($b['is_active']): ?>
                                    <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle"></i> Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary px-2 py-1"><i class="bi bi-x-circle"></i> Deactivated</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Print Official ID Card -->
                                    <a href="<?= url('/print/boe-id-card/' . $b['id']) ?>" target="_blank" class="btn btn-outline-primary fw-bold" title="Generate & Print Official BOE ID Card">
                                        <i class="bi bi-person-vcard"></i> ID Card
                                    </a>
                                    <!-- Edit BOE Details -->
                                    <button type="button" class="btn btn-outline-secondary fw-bold" title="Edit BOE Information" onclick='openEditBoeModal(<?= json_encode($b) ?>)'>
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <!-- Toggle Active Status -->
                                    <a href="<?= url('/admin/boe/toggle/' . $b['id']) ?>" class="btn <?= $b['is_active'] ? 'btn-outline-danger' : 'btn-outline-success' ?>" title="Change status" onclick="return confirm('Are you sure you want to <?= $b['is_active'] ? 'deactivate' : 'activate' ?> this BOE staff member?');">
                                        <i class="bi <?= $b['is_active'] ? 'bi-lock' : 'bi-unlock' ?>"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: CREATE NEW BOE STAFF                -->
<!-- ========================================== -->
<div class="modal fade" id="createBoeModal" tabindex="-1" aria-labelledby="createBoeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="<?= url('/admin/boe/create') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="modal-header bg-navy text-white py-3">
                    <h5 class="modal-title font-outfit fw-bold" id="createBoeModalLabel">
                        <i class="bi bi-person-plus-fill me-2 text-warning"></i> Register New Back Office Executive (BOE)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-4">
                    <!-- 1. Basic Identifiers -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Employee Code *</label>
                            <input type="text" name="employee_code" class="form-control font-monospace fw-bold" value="<?= htmlspecialchars($nextCode) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Full Legal Name *</label>
                            <input type="text" name="full_name" class="form-control" placeholder="e.g. Ramesh Chandra Das" required>
                        </div>
                    </div>

                    <!-- 2. Contact Details -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Mobile Number (Login Mobile) *</label>
                            <input type="tel" name="mobile" class="form-control" placeholder="10-digit mobile number" required pattern="[0-9]{10}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Official / Personal Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="boe@suryavistaara.com">
                        </div>
                    </div>

                    <!-- 3. Designation, Blood Group & Jurisdiction -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Designation *</label>
                            <input type="text" name="designation" class="form-control" value="Back Office Executive" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy"><i class="bi bi-droplet-fill text-danger me-1"></i> Blood Group *</label>
                            <select name="blood_group" class="form-select fw-semibold" required>
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
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy"><i class="bi bi-geo-alt-fill text-primary me-1"></i> Assigned Jurisdiction *</label>
                            <input type="text" name="jurisdiction" class="form-control" list="jurisdictionOptions" placeholder="e.g. Bhubaneswar & Cuttack Circle" value="Bhubaneswar & Cuttack Circle" required>
                        </div>
                    </div>

                    <!-- 4. Address -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Postal Address / Office Posting Location</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="e.g. MIG-84, Pokhariput, BDA Colony, Phase-1, Bhubaneswar, Khorda – 751020, Odisha"></textarea>
                    </div>

                    <!-- 5. Passport Photo & Live Webcam Capture -->
                    <div class="card p-3 bg-light border-0 rounded-3 mb-3">
                        <label class="form-label small fw-bold text-navy mb-2">
                            <i class="bi bi-camera-fill text-primary me-1"></i> Passport Photo for Official ID Card
                        </label>
                        <div class="row g-3 align-items-center">
                            <div class="col-sm-3 text-center">
                                <div style="width: 86px; height: 104px; border: 2px dashed #0f2d59; border-radius: 8px; margin: 0 auto; overflow: hidden; background: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <img id="createPhotoPreview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    <div id="createPhotoPlaceholder" class="text-muted text-center p-1">
                                        <i class="bi bi-person fs-3"></i>
                                        <span class="d-block" style="font-size: 0.68rem;">Passport Size</span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-warning btn-sm mt-1 py-0 px-2 fw-semibold" style="font-size: 0.68rem;" onclick="triggerPhotoStudio('createPhotoPreview', 'createPhotoPlaceholder', 'createPhotoBase64', 'createFileInput')">
                                    <i class="bi bi-crop me-1"></i> Crop / BG
                                </button>
                            </div>
                            <div class="col-sm-9">
                                <div class="row g-2">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small text-muted mb-1">Option A: Upload Image File</label>
                                        <input type="file" name="boe_photo" id="createFileInput" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp" onchange="previewFilePhoto(this, 'createPhotoPreview', 'createPhotoPlaceholder', 'createPhotoBase64')">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small text-muted mb-1">Option B: Capture via Webcam</label>
                                        <button type="button" class="btn btn-outline-success btn-sm w-100 fw-bold" onclick="openLiveCamera('createPhotoPreview', 'createPhotoPlaceholder', 'createPhotoBase64')">
                                            <i class="bi bi-camera-video me-1"></i> Take Live Webcam Snapshot
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="boe_photo_base64" id="createPhotoBase64" value="">
                                <small class="text-muted mt-1 d-block" style="font-size: 0.72rem;">
                                    <i class="bi bi-magic text-primary me-1"></i> Auto-launches Photo Studio to crop (3:4 ratio) and remove background with 1-click studio backdrop.
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Password -->
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-navy">Initial Login Password *</label>
                        <input type="text" name="password" class="form-control" value="Password@123" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Executive can change their password anytime after logging in.</small>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-check-lg me-1"></i> Register BOE Executive
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: EDIT BOE INFORMATION               -->
<!-- ========================================== -->
<div class="modal fade" id="editBoeModal" tabindex="-1" aria-labelledby="editBoeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form id="editBoeForm" action="" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="modal-header bg-navy text-white py-3">
                    <h5 class="modal-title font-outfit fw-bold" id="editBoeModalLabel">
                        <i class="bi bi-pencil-square me-2 text-warning"></i> Edit Back Office Executive (BOE) Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-4">
                    <!-- 1. Identifiers -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Employee Code</label>
                            <input type="text" id="editEmpCode" class="form-control font-monospace fw-bold bg-light" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Full Legal Name *</label>
                            <input type="text" name="full_name" id="editFullName" class="form-control" required>
                        </div>
                    </div>

                    <!-- 2. Contact Details -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Mobile Number (Login ID) *</label>
                            <input type="tel" name="mobile" id="editMobile" class="form-control" required pattern="[0-9]{10}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Email Address</label>
                            <input type="email" name="email" id="editEmail" class="form-control">
                        </div>
                    </div>

                    <!-- 3. Designation, Blood Group & Jurisdiction -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Designation *</label>
                            <input type="text" name="designation" id="editDesignation" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy"><i class="bi bi-droplet-fill text-danger me-1"></i> Blood Group *</label>
                            <select name="blood_group" id="editBloodGroup" class="form-select fw-semibold" required>
                                <option value="O+ve">O +ve</option>
                                <option value="A+ve">A +ve</option>
                                <option value="B+ve">B +ve</option>
                                <option value="AB+ve">AB +ve</option>
                                <option value="O-ve">O -ve</option>
                                <option value="A-ve">A -ve</option>
                                <option value="B-ve">B -ve</option>
                                <option value="AB-ve">AB -ve</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy"><i class="bi bi-geo-alt-fill text-primary me-1"></i> Assigned Jurisdiction *</label>
                            <input type="text" name="jurisdiction" id="editJurisdiction" class="form-control" list="jurisdictionOptions" required>
                        </div>
                    </div>

                    <!-- 4. Address -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Postal Address / Office Posting Location</label>
                        <textarea name="address" id="editAddress" class="form-control" rows="2"></textarea>
                    </div>

                    <!-- 5. Passport Photo & Live Webcam Capture -->
                    <div class="card p-3 bg-light border-0 rounded-3 mb-3">
                        <label class="form-label small fw-bold text-navy mb-2">
                            <i class="bi bi-camera-fill text-primary me-1"></i> Update Passport Photo
                        </label>
                        <div class="row g-3 align-items-center">
                            <div class="col-sm-3 text-center">
                                <div style="width: 86px; height: 104px; border: 2px dashed #0f2d59; border-radius: 8px; margin: 0 auto; overflow: hidden; background: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <img id="editPhotoPreview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    <div id="editPhotoPlaceholder" class="text-muted text-center p-1">
                                        <i class="bi bi-person fs-3"></i>
                                        <span class="d-block" style="font-size: 0.68rem;">Passport Size</span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-warning btn-sm mt-1 py-0 px-2 fw-semibold" style="font-size: 0.68rem;" onclick="triggerPhotoStudio('editPhotoPreview', 'editPhotoPlaceholder', 'editPhotoBase64', 'editFileInput')">
                                    <i class="bi bi-crop me-1"></i> Crop / BG
                                </button>
                            </div>
                            <div class="col-sm-9">
                                <div class="row g-2">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small text-muted mb-1">Option A: Upload New Photo</label>
                                        <input type="file" name="boe_photo" id="editFileInput" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp" onchange="previewFilePhoto(this, 'editPhotoPreview', 'editPhotoPlaceholder', 'editPhotoBase64')">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small text-muted mb-1">Option B: Capture via Webcam</label>
                                        <button type="button" class="btn btn-outline-success btn-sm w-100 fw-bold" onclick="openLiveCamera('editPhotoPreview', 'editPhotoPlaceholder', 'editPhotoBase64')">
                                            <i class="bi bi-camera-video me-1"></i> Retake via Webcam
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="boe_photo_base64" id="editPhotoBase64" value="">
                                <small class="text-muted mt-1 d-block" style="font-size: 0.72rem;">
                                    <i class="bi bi-magic text-primary me-1"></i> Auto-launches Photo Studio to crop (3:4 ratio) and remove background with 1-click studio backdrop.
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Account Status & Password Change -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Account Status</label>
                            <select name="is_active" id="editIsActive" class="form-select fw-semibold">
                                <option value="1">Active (Can Login & Process Leads)</option>
                                <option value="0">Deactivated (Suspended)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Reset Password (Optional)</label>
                            <input type="text" name="password" class="form-control" placeholder="Leave blank to keep current password">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: LIVE WEBCAM CAPTURE CAMERA          -->
<!-- ========================================== -->
<div class="modal fade" id="modalLiveCamera" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white py-2">
                <h6 class="modal-title font-outfit fw-bold">
                    <i class="bi bi-camera-video-fill me-2 text-warning"></i> Live Camera Capture
                </h6>
                <button type="button" class="btn-close btn-close-white" onclick="closeLiveCamera()"></button>
            </div>
            <div class="modal-body text-center p-3 bg-dark position-relative">
                <div style="width: 280px; height: 350px; margin: 0 auto; position: relative; overflow: hidden; border-radius: 12px; background: #000; border: 2px solid #38bdf8;">
                    <video id="webcamVideo" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                    <!-- Visual Guideline Oval for Face Framing -->
                    <div style="position: absolute; top: 12%; left: 16%; width: 68%; height: 72%; border: 2px dashed rgba(255,255,255,0.6); border-radius: 50%; pointer-events: none;"></div>
                </div>
                <div id="cameraStatusText" class="text-light small mt-2">
                    <span class="spinner-border spinner-border-sm me-1" role="status"></span> Accessing camera...
                </div>
            </div>
            <div class="modal-footer bg-light justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeLiveCamera()">Cancel</button>
                <button type="button" class="btn btn-success fw-bold px-4" id="btnSnapPhoto" onclick="captureLiveSnapshot()" disabled>
                    <i class="bi bi-camera-fill me-1"></i> Snap & Use Photo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Datalist for Circle/Jurisdiction Autocomplete -->
<datalist id="jurisdictionOptions">
    <option value="Bhubaneswar & Cuttack Circle">
    <option value="Khordha, Puri & Nayagarh Circle">
    <option value="Ganjam, Gajapati & Berhampur Circle">
    <option value="Western Odisha (Sambalpur, Bargarh, Jharsuguda)">
    <option value="Northern Odisha (Balasore, Bhadrak, Mayurbhanj)">
    <option value="Southern Odisha (Koraput, Rayagada, Nabarangpur)">
    <option value="Central Odisha (Angul, Dhenkanal, Keonjhar)">
    <option value="Headquarters Operations / All Odisha">
</datalist>

<script>
let activePreviewImgId = null;
let activePlaceholderId = null;
let activeBase64InputId = null;
let mediaStream = null;

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

function previewFilePhoto(input, previewId, placeholderId, base64InputId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';

            // Automatically open Photo Studio for 3:4 crop and background removal
            if (typeof window.openPhotoStudio === 'function') {
                window.openPhotoStudio(e.target.result, previewId, placeholderId, base64InputId);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function openLiveCamera(previewId, placeholderId, base64InputId) {
    activePreviewImgId = previewId;
    activePlaceholderId = placeholderId;
    activeBase64InputId = base64InputId;

    const modalEl = document.getElementById('modalLiveCamera');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    const video = document.getElementById('webcamVideo');
    const btnSnap = document.getElementById('btnSnapPhoto');
    const statusText = document.getElementById('cameraStatusText');

    btnSnap.disabled = true;
    statusText.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Accessing camera...';

    navigator.mediaDevices.getUserMedia({
        video: { width: { ideal: 720 }, height: { ideal: 960 }, facingMode: "user" },
        audio: false
    }).then(stream => {
        mediaStream = stream;
        video.srcObject = stream;
        video.onloadedmetadata = () => {
            video.play();
            btnSnap.disabled = false;
            statusText.innerHTML = '<i class="bi bi-check-circle text-success me-1"></i> Camera active. Center face inside the guide and click Snap.';
        };
    }).catch(err => {
        statusText.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-octagon me-1"></i> Camera error: ' + (err.message || 'Access denied') + '. Please use file upload instead.</span>';
    });
}

function closeLiveCamera() {
    if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
        mediaStream = null;
    }
    const modalEl = document.getElementById('modalLiveCamera');
    const modalObj = bootstrap.Modal.getInstance(modalEl);
    if (modalObj) modalObj.hide();
}

function captureLiveSnapshot() {
    const video = document.getElementById('webcamVideo');
    if (!video || !mediaStream) return;

    const canvas = document.createElement('canvas');
    canvas.width = 480;
    canvas.height = 640;
    const ctx = canvas.getContext('2d');

    const vWidth = video.videoWidth || 640;
    const vHeight = video.videoHeight || 480;
    const targetAspect = 480 / 640;

    let sx, sy, sWidth, sHeight;
    if (vWidth / vHeight > targetAspect) {
        sWidth = vHeight * targetAspect;
        sHeight = vHeight;
        sx = (vWidth - sWidth) / 2;
        sy = 0;
    } else {
        sWidth = vWidth;
        sHeight = vWidth / targetAspect;
        sx = 0;
        sy = (vHeight - sHeight) / 2;
    }

    ctx.drawImage(video, sx, sy, sWidth, sHeight, 0, 0, 480, 640);
    const base64Data = canvas.toDataURL('image/jpeg', 0.92);

    if (activePreviewImgId) {
        const preview = document.getElementById(activePreviewImgId);
        const placeholder = document.getElementById(activePlaceholderId);
        preview.src = base64Data;
        preview.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
    }
    if (activeBase64InputId) {
        document.getElementById(activeBase64InputId).value = base64Data;
    }

    // Reset file input if any
    if (activePreviewImgId === 'createPhotoPreview') {
        const f = document.getElementById('createFileInput');
        if (f) f.value = '';
    } else if (activePreviewImgId === 'editPhotoPreview') {
        const f = document.getElementById('editFileInput');
        if (f) f.value = '';
    }

    const previewId = activePreviewImgId;
    const placeholderId = activePlaceholderId;
    const base64Id = activeBase64InputId;

    closeLiveCamera();

    // Auto-launch Photo Studio to refine crop & background
    if (typeof window.openPhotoStudio === 'function') {
        setTimeout(() => {
            window.openPhotoStudio(base64Data, previewId, placeholderId, base64Id);
        }, 300);
    }
}

function openEditBoeModal(b) {
    document.getElementById('editBoeForm').action = '<?= url('/admin/boe/update/') ?>' + b.id;
    document.getElementById('editEmpCode').value = b.employee_code || ('SVPL-BOE-' + b.id);
    document.getElementById('editFullName').value = b.full_name || '';
    document.getElementById('editMobile').value = b.mobile || '';
    document.getElementById('editEmail').value = b.email || '';
    document.getElementById('editDesignation').value = b.designation || 'Back Office Executive';
    document.getElementById('editBloodGroup').value = b.blood_group || 'O+ve';
    document.getElementById('editJurisdiction').value = b.jurisdiction || 'Bhubaneswar & Cuttack Circle';
    document.getElementById('editAddress').value = b.address || '';
    document.getElementById('editIsActive').value = b.is_active !== undefined ? b.is_active : 1;
    document.getElementById('editPhotoBase64').value = '';

    const preview = document.getElementById('editPhotoPreview');
    const placeholder = document.getElementById('editPhotoPlaceholder');
    if (b.photo_url) {
        let photoSrc = b.photo_url;
        if (!photoSrc.startsWith('http') && !photoSrc.startsWith('data:')) {
            photoSrc = '<?= url('/') ?>' + (photoSrc.startsWith('/') ? photoSrc.substring(1) : photoSrc);
        }
        preview.src = photoSrc;
        preview.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
    } else {
        preview.src = '';
        preview.style.display = 'none';
        if (placeholder) placeholder.style.display = 'block';
    }

    const editModal = new bootstrap.Modal(document.getElementById('editBoeModal'));
    editModal.show();
}
</script>
