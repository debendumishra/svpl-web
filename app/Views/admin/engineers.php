<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin & Manager — Solar Field Engineers Master Desk
 */
$title = "Solar Engineers Master Desk — SVPL Admin";

$odishaDistricts = [
    'Angul', 'Balangir', 'Balasore', 'Bargarh', 'Bhadrak', 'Boudh', 'Cuttack', 'Deogarh',
    'Dhenkanal', 'Gajapati', 'Ganjam', 'Jagatsinghpur', 'Jajpur', 'Jharsuguda', 'Kalahandi',
    'Kandhamal', 'Kendrapara', 'Kendujhar', 'Khordha', 'Koraput', 'Malkangiri', 'Mayurbhanj',
    'Nabarangpur', 'Nayagarh', 'Nuapada', 'Puri', 'Rayagada', 'Sambalpur', 'Subarnapur', 'Sundargarh'
];
?>

<div class="container-fluid px-3 px-md-4 py-4">

    <!-- PAGE HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Engineers Master</li>
                </ol>
            </nav>
            <h1 class="h3 font-heading fw-bold text-navy mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-person-badge-fill text-warning"></i> Solar Field Engineers Master
            </h1>
            <p class="text-secondary small mb-0">Manage certified solar installation engineers, assigned operational districts, credentials, and field assignments.</p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="<?= url('/admin/dispatches') ?>" class="btn btn-outline-navy btn-sm fw-bold">
                <i class="bi bi-truck me-1"></i> Dispatches & BOM Desk
            </a>
            <button type="button" class="btn btn-svpl-solar btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddEngineer">
                <i class="bi bi-person-plus-fill me-1"></i> + Register Field Engineer
            </button>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    <?php 
    $successMsg = $_SESSION['success_msg'] ?? ($_GET['success'] ?? null);
    $warningMsg = $_SESSION['warning_msg'] ?? ($_GET['warning'] ?? null);
    $errorMsg = $_SESSION['error_msg'] ?? ($_GET['error'] ?? null);
    ?>
    <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div><?= htmlspecialchars($successMsg) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_msg']); ?>
    <?php endif; ?>

    <?php if (!empty($warningMsg)): ?>
        <div class="alert alert-warning alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-warning"></i>
            <div><?= htmlspecialchars($warningMsg) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['warning_msg']); ?>
    <?php endif; ?>

    <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-x-circle-fill fs-5 text-danger"></i>
            <div><?= htmlspecialchars($errorMsg) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_msg']); ?>
    <?php endif; ?>

    <!-- STATS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-navy border-3">
                <div class="text-secondary small fw-semibold">Total Engineers</div>
                <div class="fs-4 fw-bold text-navy mt-1"><?= (int)($stats['total'] ?? count($engineers ?? [])) ?></div>
                <div class="small text-muted" style="font-size: 0.75rem;">Registered in network</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-success border-3">
                <div class="text-secondary small fw-semibold">Active on Duty</div>
                <div class="fs-4 fw-bold text-success mt-1"><?= (int)($stats['active'] ?? 0) ?></div>
                <div class="small text-success" style="font-size: 0.75rem;">Available for dispatch</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-secondary border-3">
                <div class="text-secondary small fw-semibold">Inactive / On Leave</div>
                <div class="fs-4 fw-bold text-secondary mt-1"><?= (int)($stats['inactive'] ?? 0) ?></div>
                <div class="small text-muted" style="font-size: 0.75rem;">Temporarily disabled</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-warning border-3">
                <div class="text-secondary small fw-semibold">Operational Districts</div>
                <div class="fs-4 fw-bold text-warning-emphasis mt-1"><?= (int)($stats['districts_count'] ?? 30) ?> / 30</div>
                <div class="small text-muted" style="font-size: 0.75rem;">Full Odisha coverage</div>
            </div>
        </div>
    </div>

    <!-- ENGINEERS TABLE & SEARCH -->
    <div class="card bg-white border shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-people text-navy fs-5"></i>
                <h5 class="mb-0 fw-bold text-navy">Certified Field Engineers Roster</h5>
                <span class="badge bg-primary rounded-pill"><?= count($engineers ?? []) ?> Engineers</span>
            </div>

            <div class="d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0" style="min-width: 280px;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="engineerSearchInput" class="form-control bg-light border-start-0" placeholder="Search by name, code, district, phone...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="engineersTable">
                <thead class="table-light text-secondary small text-uppercase fw-semibold" style="letter-spacing: 0.5px;">
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th>Engineer Code & Name</th>
                        <th>Designation & Qualification</th>
                        <th>Contact Phone & Email</th>
                        <th>Login ID & Password</th>
                        <th>Assigned Districts</th>
                        <th class="text-center">Experience</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($engineers)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-1 text-secondary opacity-50 d-block mb-2"></i>
                                <div class="fw-semibold">No solar field engineers found.</div>
                                <div class="small text-muted">Click "+ Register Field Engineer" above to add certified engineers.</div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($engineers as $idx => $eng): 
                            $districts = !empty($eng['assigned_districts']) ? explode(',', $eng['assigned_districts']) : [];
                            $pass = !empty($eng['password_text']) ? $eng['password_text'] : 'Engineer@123';
                        ?>
                            <tr class="engineer-row" data-search="<?= strtolower(htmlspecialchars($eng['engineer_code'] . ' ' . $eng['full_name'] . ' ' . $eng['email'] . ' ' . $eng['mobile'] . ' ' . $eng['designation'] . ' ' . $eng['assigned_districts'])) ?>">
                                <td class="text-center text-muted fw-bold"><?= $idx + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle bg-navy text-warning fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 38px; height: 38px; font-size: 0.85rem; flex-shrink: 0;">
                                            <?= strtoupper(substr($eng['full_name'], 0, 2)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-navy"><?= htmlspecialchars($eng['full_name']) ?></div>
                                            <div class="badge bg-light text-dark border font-monospace px-2 py-0" style="font-size: 0.72rem;">
                                                <i class="bi bi-upc-scan me-1"></i><?= htmlspecialchars($eng['engineer_code']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark small"><?= htmlspecialchars($eng['designation'] ?? 'Solar Engineer') ?></div>
                                    <div class="text-secondary" style="font-size: 0.78rem;">
                                        <i class="bi bi-mortarboard me-1"></i><?= htmlspecialchars($eng['qualification'] ?? 'B.Tech / Diploma') ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="small text-dark fw-medium">
                                        <i class="bi bi-telephone-fill text-success me-1"></i><?= htmlspecialchars($eng['mobile']) ?>
                                    </div>
                                    <div class="text-secondary" style="font-size: 0.78rem;">
                                        <i class="bi bi-envelope me-1"></i><?= htmlspecialchars($eng['email']) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="small mb-1">
                                        <span class="text-secondary" style="font-size: 0.72rem;">User ID:</span> 
                                        <span class="font-monospace fw-bold text-navy"><?= htmlspecialchars($eng['mobile']) ?></span>
                                    </div>
                                    <div>
                                        <span class="badge bg-light text-dark border font-monospace px-2 py-1" title="Login Password">
                                            <i class="bi bi-key-fill text-warning me-1"></i><?= htmlspecialchars($pass) ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1" style="max-width: 250px;">
                                        <?php if (empty($districts)): ?>
                                            <span class="badge bg-secondary-subtle text-secondary small">All Odisha</span>
                                        <?php else: ?>
                                            <?php foreach (array_slice($districts, 0, 3) as $d): ?>
                                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle" style="font-size: 0.72rem;"><?= trim(htmlspecialchars($d)) ?></span>
                                            <?php endforeach; ?>
                                            <?php if (count($districts) > 3): ?>
                                                <span class="badge bg-light text-secondary border" style="font-size: 0.72rem;" title="<?= htmlspecialchars($eng['assigned_districts']) ?>">+<?= count($districts) - 3 ?> more</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border fw-bold"><?= (float)($eng['experience_years'] ?? 0) ?> Yrs</span>
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="<?= (strpos($_SERVER['REQUEST_URI'] ?? '', '/manager') !== false) ? url('/manager/engineers/toggle-status') : url('/admin/engineers/toggle-status') ?>" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $eng['id'] ?>">
                                        <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent" title="Click to <?= $eng['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                            <?php if ($eng['is_active']): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Active
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">
                                                    <i class="bi bi-x-circle-fill me-1"></i> Inactive
                                                </span>
                                            <?php endif; ?>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-navy btn-edit-engineer" 
                                            data-engineer='<?= htmlspecialchars(json_encode($eng), ENT_QUOTES, 'UTF-8') ?>'
                                            title="Edit Engineer Details">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form method="POST" action="<?= (strpos($_SERVER['REQUEST_URI'] ?? '', '/manager') !== false) ? url('/manager/engineers/delete') : url('/admin/engineers/delete') ?>" class="d-inline" onsubmit="return confirm('Are you sure you want to delete engineer <?= htmlspecialchars($eng['full_name']) ?>? This action cannot be undone.');">
                                            <input type="hidden" name="id" value="<?= $eng['id'] ?>">
                                            <button type="submit" class="btn btn-outline-danger" title="Delete Engineer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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

<!-- ==========================================
     MODAL: REGISTER NEW ENGINEER
========================================== -->
<div class="modal fade" id="modalAddEngineer" tabindex="-1" aria-labelledby="modalAddEngineerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white py-3">
                <h5 class="modal-title font-heading fw-bold d-flex align-items-center gap-2" id="modalAddEngineerLabel">
                    <i class="bi bi-person-plus-fill text-warning"></i> Register Certified Field Engineer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= (strpos($_SERVER['REQUEST_URI'] ?? '', '/manager') !== false) ? url('/manager/engineers/create') : url('/admin/engineers/create') ?>">
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <div>This creates both an <strong>Engineer Master Profile</strong> and an active <strong>User Login</strong> with the <code>ENGINEER</code> role.</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Engineer Code / Employee ID <span class="text-danger">*</span></label>
                            <input type="text" name="engineer_code" class="form-control" placeholder="e.g. SVPL-ENG-005" required value="SVPL-ENG-<?= str_pad((count($engineers ?? []) + 1), 3, '0', STR_PAD_LEFT) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Full Legal Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" placeholder="e.g. Deepak Kumar Swain" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Mobile Number (Login ID) <span class="text-danger">*</span></label>
                            <input type="tel" name="mobile" class="form-control" placeholder="10-digit mobile number" pattern="[0-9]{10}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="engineer@suryavistaara.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Login Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Enter secure password" value="Engineer@123" required>
                            <div class="form-text small">Default is <code>Engineer@123</code> (Engineer can change later).</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Designation <span class="text-danger">*</span></label>
                            <select name="designation" class="form-select" required>
                                <option value="Senior Solar Field Engineer">Senior Solar Field Engineer</option>
                                <option value="Field Solar Engineer" selected>Field Solar Engineer</option>
                                <option value="Solar Commissioning Specialist">Solar Commissioning Specialist</option>
                                <option value="QA & Inspection Engineer">QA & Inspection Engineer</option>
                                <option value="Trainee Solar Engineer">Trainee Solar Engineer</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Educational Qualification</label>
                            <input type="text" name="qualification" class="form-control" placeholder="e.g. B.Tech Electrical / Diploma in Solar PV">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-navy">Experience (Years)</label>
                            <input type="number" step="0.1" name="experience_years" class="form-control" value="2.5" min="0" max="40">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-navy">Aadhaar Number</label>
                            <input type="text" name="aadhaar_number" class="form-control" placeholder="12-digit Aadhaar" maxlength="14">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-navy d-flex justify-content-between">
                                <span>Assigned Operational Districts (Odisha)</span>
                                <span class="text-muted fw-normal small">Select districts covered by this engineer</span>
                            </label>
                            <div class="border rounded p-2 bg-light" style="max-height: 140px; overflow-y: auto;">
                                <div class="row g-2">
                                    <?php foreach ($odishaDistricts as $dist): ?>
                                        <div class="col-6 col-sm-4 col-md-3">
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="checkbox" name="assigned_districts[]" value="<?= $dist ?>" id="dist_add_<?= $dist ?>">
                                                <label class="form-check-label small" for="dist_add_<?= $dist ?>"><?= $dist ?></label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="add_is_active" checked>
                                <label class="form-check-label fw-bold small text-navy" for="add_is_active">Activate Engineer Account Immediately</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-solar fw-bold px-4">
                        <i class="bi bi-save me-1"></i> Save & Register Engineer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==========================================
     MODAL: EDIT ENGINEER
========================================== -->
<div class="modal fade" id="modalEditEngineer" tabindex="-1" aria-labelledby="modalEditEngineerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white py-3">
                <h5 class="modal-title font-heading fw-bold d-flex align-items-center gap-2" id="modalEditEngineerLabel">
                    <i class="bi bi-pencil-square text-warning"></i> Modify Field Engineer Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= (strpos($_SERVER['REQUEST_URI'] ?? '', '/manager') !== false) ? url('/manager/engineers/update') : url('/admin/engineers/update') ?>">
                <input type="hidden" name="id" id="edit_eng_id">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Engineer Code <span class="text-danger">*</span></label>
                            <input type="text" name="engineer_code" id="edit_engineer_code" class="form-control" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Full Legal Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="edit_full_name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" name="mobile" id="edit_mobile" class="form-control" pattern="[0-9]{10}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Login Password</label>
                            <input type="text" name="password" id="edit_password" class="form-control font-monospace" placeholder="e.g. Engineer@123">
                            <div class="form-text small">Visible to Admin. Change here to reset engineer's password.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Designation <span class="text-danger">*</span></label>
                            <select name="designation" id="edit_designation" class="form-select" required>
                                <option value="Senior Solar Field Engineer">Senior Solar Field Engineer</option>
                                <option value="Field Solar Engineer">Field Solar Engineer</option>
                                <option value="Solar Commissioning Specialist">Solar Commissioning Specialist</option>
                                <option value="QA & Inspection Engineer">QA & Inspection Engineer</option>
                                <option value="Trainee Solar Engineer">Trainee Solar Engineer</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Educational Qualification</label>
                            <input type="text" name="qualification" id="edit_qualification" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-navy">Experience (Years)</label>
                            <input type="number" step="0.1" name="experience_years" id="edit_experience_years" class="form-control" min="0" max="40">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-navy">Aadhaar Number</label>
                            <input type="text" name="aadhaar_number" id="edit_aadhaar_number" class="form-control" maxlength="14">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-navy d-flex justify-content-between">
                                <span>Assigned Operational Districts (Odisha)</span>
                                <span class="text-muted fw-normal small">Check all assigned districts</span>
                            </label>
                            <div class="border rounded p-2 bg-light" style="max-height: 140px; overflow-y: auto;">
                                <div class="row g-2">
                                    <?php foreach ($odishaDistricts as $dist): ?>
                                        <div class="col-6 col-sm-4 col-md-3">
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input edit-dist-check" type="checkbox" name="assigned_districts[]" value="<?= $dist ?>" id="dist_edit_<?= $dist ?>">
                                                <label class="form-check-label small" for="dist_edit_<?= $dist ?>"><?= $dist ?></label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_is_active">
                                <label class="form-check-label fw-bold small text-navy" for="edit_is_active">Active on Duty</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-solar fw-bold px-4">
                        <i class="bi bi-check2-circle me-1"></i> Update Engineer Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick search filter
    const searchInput = document.getElementById('engineerSearchInput');
    const rows = document.querySelectorAll('.engineer-row');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            rows.forEach(row => {
                const text = row.getAttribute('data-search') || '';
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // Edit modal population
    const editModal = new bootstrap.Modal(document.getElementById('modalEditEngineer'));
    document.querySelectorAll('.btn-edit-engineer').forEach(btn => {
        btn.addEventListener('click', function() {
            const rawData = this.getAttribute('data-engineer');
            if (!rawData) return;
            const data = JSON.parse(rawData);

            document.getElementById('edit_eng_id').value = data.id || '';
            document.getElementById('edit_engineer_code').value = data.engineer_code || '';
            document.getElementById('edit_full_name').value = data.full_name || '';
            document.getElementById('edit_mobile').value = data.mobile || '';
            document.getElementById('edit_email').value = data.email || '';
            document.getElementById('edit_password').value = data.password_text || 'Engineer@123';
            document.getElementById('edit_designation').value = data.designation || 'Field Solar Engineer';
            document.getElementById('edit_qualification').value = data.qualification || '';
            document.getElementById('edit_experience_years').value = data.experience_years || 0;
            document.getElementById('edit_aadhaar_number').value = data.aadhaar_number || '';
            document.getElementById('edit_is_active').checked = parseInt(data.is_active) === 1;

            // District checkboxes
            const assigned = (data.assigned_districts || '').split(',').map(s => s.trim().toLowerCase());
            document.querySelectorAll('.edit-dist-check').forEach(chk => {
                chk.checked = assigned.includes(chk.value.toLowerCase());
            });

            editModal.show();
        });
    });
});
</script>
