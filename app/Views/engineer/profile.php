<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Field Engineer Profile & Password Settings
 */
$title = "Engineer Profile & Settings — SVPL";
?>

<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="<?= url('/engineer/dashboard') ?>" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profile & Security</li>
                </ol>
            </nav>
            <h1 class="h3 font-heading fw-bold text-navy mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-person-badge-fill text-warning"></i> Engineer Profile & Credentials
            </h1>
            <p class="text-secondary small mb-0">Update contact phone, educational qualifications, and login security credentials.</p>
        </div>

        <div>
            <a href="<?= url('/engineer/id-card') ?>" target="_blank" class="btn btn-warning text-dark fw-bold btn-sm shadow-sm">
                <i class="bi bi-person-vcard-fill me-1"></i> View Digital ID Card
            </a>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (!empty($_SESSION['success_msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div><?= htmlspecialchars($_SESSION['success_msg']) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_msg']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <!-- PROFILE SUMMARY CARD -->
        <div class="col-lg-4">
            <div class="card bg-white border shadow-sm rounded-3 p-4 text-center">
                <div class="avatar-circle bg-navy text-warning fw-bold d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3 shadow" style="width: 80px; height: 80px; font-size: 1.75rem;">
                    <?= strtoupper(substr($engineer['full_name'] ?? ($user['full_name'] ?? 'SE'), 0, 2)) ?>
                </div>

                <h5 class="fw-bold text-navy mb-1"><?= htmlspecialchars($engineer['full_name'] ?? ($user['full_name'] ?? 'Field Engineer')) ?></h5>
                <div class="badge bg-warning text-dark font-monospace px-3 py-1 mb-2 fw-bold">
                    <?= htmlspecialchars($engineer['engineer_code'] ?? 'SVPL-ENG') ?>
                </div>
                <div class="small text-secondary fw-semibold"><?= htmlspecialchars($engineer['designation'] ?? 'Field Solar Engineer') ?></div>

                <hr class="my-3">

                <div class="text-start small">
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Account Status:</span>
                        <span class="badge bg-success-subtle text-success">Active & Certified</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Experience:</span>
                        <span class="fw-bold text-dark"><?= (int)($engineer['experience_years'] ?? 0) ?> Years</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Aadhaar No:</span>
                        <span class="font-monospace text-dark"><?= htmlspecialchars($engineer['aadhaar_number'] ?: 'Verified on file') ?></span>
                    </div>
                    <div class="py-2">
                        <div class="text-muted mb-1">Operational Coverage:</div>
                        <div class="badge bg-light text-dark border text-wrap text-start w-100 p-2 font-monospace" style="font-size: 0.75rem;">
                            <?= htmlspecialchars($engineer['assigned_districts'] ?? 'All 30 Districts of Odisha') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PROFILE EDIT & SECURITY FORM -->
        <div class="col-lg-8">
            <div class="card bg-white border shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold text-navy mb-0"><i class="bi bi-sliders text-warning me-1"></i> Modify Profile & Password</h5>
                </div>
                <form method="POST" action="<?= url('/engineer/profile') ?>">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">Registered Mobile Number <span class="text-danger">*</span></label>
                                <input type="tel" name="mobile" class="form-control font-monospace" value="<?= htmlspecialchars($engineer['mobile'] ?? ($user['mobile'] ?? '')) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($engineer['email'] ?? ($user['email'] ?? '')) ?>" required>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-navy">Educational Qualification</label>
                                <input type="text" name="qualification" class="form-control" value="<?= htmlspecialchars($engineer['qualification'] ?? '') ?>" placeholder="e.g. B.Tech in Electrical & Electronics / Diploma in Solar PV">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-navy">Field Experience (Years)</label>
                                <input type="number" step="0.1" name="experience_years" class="form-control" value="<?= (float)($engineer['experience_years'] ?? 0) ?>" min="0" max="40">
                            </div>

                            <div class="col-12"><hr class="my-2"></div>

                            <div class="col-12">
                                <h6 class="fw-bold text-navy mb-1"><i class="bi bi-shield-lock-fill text-warning me-1"></i> Update Login Password (Optional)</h6>
                                <p class="small text-muted mb-2">Leave blank if you wish to keep your existing password unchanged.</p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">New Secure Password</label>
                                <input type="password" name="new_password" class="form-control" placeholder="Minimum 6 characters">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-3 text-end">
                        <button type="submit" class="btn btn-svpl-solar fw-bold px-4">
                            <i class="bi bi-check-circle-fill me-1"></i> Save Profile Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
