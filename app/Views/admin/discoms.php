<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin & Manager — DISCOM Providers & District Mappings Management Desk
 */
$title = "DISCOM Providers & District Mappings — SVPL Admin";
?>

<div class="container-fluid px-3 px-md-4 py-4">

    <!-- PAGE HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">DISCOM Providers</li>
                </ol>
            </nav>
            <h1 class="h3 font-heading fw-bold text-navy mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-lightning-charge-fill text-warning"></i> DISCOM Providers & Odisha District Mappings
            </h1>
            <p class="text-secondary small mb-0">Manage Odisha power distribution utilities (TPCODL, TPNODL, TPSODL, TPWODL), helpline information, and 30-district automated mapping rules for customer registration.</p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-outline-navy btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalAssignDistrict">
                <i class="bi bi-geo-alt-fill me-1"></i> + Map / Reassign District
            </button>
            <button type="button" class="btn btn-svpl-solar btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddDiscom">
                <i class="bi bi-plus-circle-fill me-1"></i> + Add DISCOM Provider
            </button>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div><?= htmlspecialchars($success_msg) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($warning_msg)): ?>
        <div class="alert alert-warning alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-warning"></i>
            <div><?= htmlspecialchars($warning_msg) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-x-circle-fill fs-5 text-danger"></i>
            <div><?= htmlspecialchars($error_msg) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- STATS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100">
                <div class="text-secondary small fw-semibold">DISCOM Utilities</div>
                <div class="fs-4 fw-bold text-navy mt-1"><?= (int)($stats['total_providers'] ?? 0) ?> Providers</div>
                <div class="small text-muted" style="font-size: 0.75rem;">TPCODL, TPNODL, TPSODL, TPWODL</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-success border-3">
                <div class="text-secondary small fw-semibold">Active Utilities</div>
                <div class="fs-4 fw-bold text-success mt-1"><?= (int)($stats['active_providers'] ?? 0) ?> Active</div>
                <div class="small text-success" style="font-size: 0.75rem;">Ready for registration</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-primary border-3">
                <div class="text-secondary small fw-semibold">Mapped Districts</div>
                <div class="fs-4 fw-bold text-primary mt-1"><?= (int)($stats['mapped_districts'] ?? 0) ?> / 30</div>
                <div class="small text-muted" style="font-size: 0.75rem;">100% Odisha Statewide Coverage</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-warning border-3">
                <div class="text-secondary small fw-semibold">Auto-Detection</div>
                <div class="fs-4 fw-bold text-warning-emphasis mt-1">Enabled <i class="bi bi-cpu text-warning"></i></div>
                <div class="small text-muted" style="font-size: 0.75rem;">Auto-selects DISCOM on District select</div>
            </div>
        </div>
    </div>

    <!-- DISCOM PROVIDERS GRID -->
    <h5 class="fw-bold text-navy mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-building text-primary"></i> Registered Power Utilities
    </h5>
    
    <div class="row g-3 mb-5">
        <?php foreach ($providers as $p): ?>
            <?php
                // Distinct color scheme based on code
                $badgeBg = 'bg-primary';
                $borderClass = 'border-primary';
                if ($p['code'] === 'TPCODL') { $badgeBg = 'bg-dark'; $borderClass = 'border-dark'; }
                elseif ($p['code'] === 'TPNODL') { $badgeBg = 'bg-info text-dark'; $borderClass = 'border-info'; }
                elseif ($p['code'] === 'TPSODL') { $badgeBg = 'bg-success'; $borderClass = 'border-success'; }
                elseif ($p['code'] === 'TPWODL') { $badgeBg = 'bg-warning text-dark'; $borderClass = 'border-warning'; }
                
                // Get list of districts mapped to this provider
                $assignedDistricts = [];
                foreach ($mappings as $m) {
                    if ($m['discom_id'] == $p['id'] || $m['discom_code'] === $p['code']) {
                        $assignedDistricts[] = $m['district_name'];
                    }
                }
            ?>
            <div class="col-md-6 col-xl-3">
                <div class="card card-svpl bg-white border shadow-sm rounded-3 h-100 d-flex flex-column border-top border-4 <?= $borderClass ?>">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge <?= $badgeBg ?> fw-bold fs-6 mb-1"><?= htmlspecialchars($p['code']) ?></span>
                            <h6 class="fw-bold text-navy mb-0"><?= htmlspecialchars($p['short_name'] ?: $p['code']) ?></h6>
                        </div>
                        <div>
                            <?php if ($p['is_active']): ?>
                                <span class="badge bg-success-subtle text-success border border-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary">Inactive</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="p-3 flex-grow-1">
                        <div class="small text-secondary mb-2">
                            <strong>Full Name:</strong><br>
                            <?= htmlspecialchars($p['name']) ?>
                        </div>

                        <?php if (!empty($p['headquarters'])): ?>
                            <div class="small text-secondary mb-1">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> HQ: <?= htmlspecialchars($p['headquarters']) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($p['helpline'])): ?>
                            <div class="small text-secondary mb-1">
                                <i class="bi bi-telephone-fill text-success me-1"></i> Helpline: <a href="tel:<?= htmlspecialchars($p['helpline']) ?>" class="text-decoration-none fw-semibold"><?= htmlspecialchars($p['helpline']) ?></a>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($p['portal_url'])): ?>
                            <div class="small text-secondary mb-2">
                                <i class="bi bi-link-45deg text-primary me-1"></i> Portal: <a href="<?= htmlspecialchars($p['portal_url']) ?>" target="_blank" class="text-decoration-none"><?= htmlspecialchars(parse_url($p['portal_url'], PHP_URL_HOST) ?: $p['portal_url']) ?></a>
                            </div>
                        <?php endif; ?>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-bold text-navy"><i class="bi bi-map-fill text-warning me-1"></i> Covered Districts:</span>
                            <span class="badge bg-light text-dark border"><?= count($assignedDistricts) ?></span>
                        </div>

                        <div class="d-flex flex-wrap gap-1 mb-2" style="max-height: 90px; overflow-y: auto;">
                            <?php if (!empty($assignedDistricts)): ?>
                                <?php foreach ($assignedDistricts as $dName): ?>
                                    <span class="badge bg-light text-dark border fw-normal" style="font-size: 0.72rem;"><?= htmlspecialchars($dName) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="small text-muted fst-italic">No districts currently assigned</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- CARD FOOTER ACTIONS -->
                    <div class="p-2 bg-light border-top d-flex justify-content-between align-items-center gap-1">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-1"
                                onclick="openEditDiscomModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)"
                                title="Edit Details">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>

                        <div class="d-flex gap-1">
                            <a href="<?= url('/admin/discoms/toggle/' . $p['id']) ?>" 
                               class="btn btn-sm py-1 px-2 <?= $p['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>"
                               title="<?= $p['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                <i class="bi <?= $p['is_active'] ? 'bi-pause-fill' : 'bi-play-fill' ?>"></i> <?= $p['is_active'] ? 'Disable' : 'Enable' ?>
                            </a>

                            <button type="button" class="btn btn-outline-danger btn-sm py-1 px-2"
                                    onclick="confirmDeleteDiscom(<?= $p['id'] ?>, '<?= htmlspecialchars($p['code'], ENT_QUOTES) ?>')"
                                    title="Delete Provider">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- DISTRICT MAPPING TABLE -->
    <div class="card card-svpl bg-white border shadow-sm rounded-3">
        <div class="p-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold text-navy mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-table text-primary"></i> 30 Odisha Districts DISCOM Provider Mapping
                </h5>
                <span class="small text-secondary">Complete routing table used by customer registration and solar proposal generation.</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <input type="text" id="filterDistrictInput" class="form-control form-control-sm" placeholder="🔍 Search district..." onkeyup="filterDistrictTable()">
                <button type="button" class="btn btn-svpl-green btn-sm fw-bold text-nowrap" data-bs-toggle="modal" data-bs-target="#modalAssignDistrict">
                    <i class="bi bi-plus-lg me-1"></i> Assign District
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableDistrictMappings">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>District Name</th>
                        <th>Assigned DISCOM Code</th>
                        <th>Full Utility Provider Name</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mappings)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle me-1"></i> No district mappings found. Click "Map / Reassign District" to add mappings.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $idx = 1; foreach ($mappings as $m): ?>
                            <?php
                                $dBadge = 'bg-primary';
                                if ($m['discom_code'] === 'TPCODL') $dBadge = 'bg-dark';
                                elseif ($m['discom_code'] === 'TPNODL') $dBadge = 'bg-info text-dark';
                                elseif ($m['discom_code'] === 'TPSODL') $dBadge = 'bg-success';
                                elseif ($m['discom_code'] === 'TPWODL') $dBadge = 'bg-warning text-dark';
                            ?>
                            <tr class="district-row" data-name="<?= strtolower(htmlspecialchars($m['district_name'])) ?>">
                                <td class="text-muted small"><?= $idx++ ?></td>
                                <td class="fw-bold text-navy">
                                    <i class="bi bi-geo-alt text-danger me-1"></i> <?= htmlspecialchars($m['district_name']) ?>
                                </td>
                                <td>
                                    <span class="badge <?= $dBadge ?> fw-bold px-2 py-1"><?= htmlspecialchars($m['discom_code']) ?></span>
                                </td>
                                <td class="small text-secondary">
                                    <?= htmlspecialchars($m['discom_full_name'] ?? $m['discom_code']) ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($m['provider_active']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success small"><i class="bi bi-check2"></i> Live</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning small"><i class="bi bi-exclamation-circle"></i> Provider Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2"
                                            onclick="openReassignModal('<?= htmlspecialchars($m['district_name'], ENT_QUOTES) ?>', <?= (int)$m['discom_id'] ?>)"
                                            title="Change DISCOM for this District">
                                        <i class="bi bi-arrow-left-right me-1"></i> Reassign
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm py-1 px-2"
                                            onclick="confirmDeleteMapping(<?= $m['id'] ?>, '<?= htmlspecialchars($m['district_name'], ENT_QUOTES) ?>')"
                                            title="Remove Mapping">
                                        <i class="bi bi-trash"></i>
                                    </button>
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
<!-- MODAL 1: ADD NEW DISCOM PROVIDER -->
<!-- ========================================== -->
<div class="modal fade" id="modalAddDiscom" tabindex="-1" aria-labelledby="modalAddDiscomLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="<?= url('/admin/discoms/create') ?>" method="POST">
                <div class="modal-header bg-navy text-white">
                    <h5 class="modal-title font-heading fw-bold" id="modalAddDiscomLabel">
                        <i class="bi bi-plus-circle-fill text-warning me-2"></i> Add New DISCOM Utility Provider
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">DISCOM Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control text-uppercase font-monospace fw-bold" placeholder="e.g. TPCODL" required maxlength="20">
                            <div class="form-text small">Standard short identifier.</div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-navy">Full Legal Utility Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. TP Central Odisha Distribution Limited" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Short Name / Label</label>
                            <input type="text" name="short_name" class="form-control" placeholder="e.g. TP Central Odisha (TPCODL)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Headquarters City</label>
                            <input type="text" name="headquarters" class="form-control" placeholder="e.g. Bhubaneswar, Odisha">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Toll-Free Customer Helpline</label>
                            <input type="text" name="helpline" class="form-control font-monospace" placeholder="e.g. 1912 / 1800-345-7122">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Consumer Portal URL</label>
                            <input type="url" name="portal_url" class="form-control" placeholder="https://www.tpcentralodisha.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-navy">Coverage Summary / Geographical Scope</label>
                            <textarea name="coverage_summary" class="form-control" rows="2" placeholder="e.g. Central Odisha covering Khordha, Cuttack, Puri, Dhenkanal..."></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch p-2 bg-light rounded border">
                                <input class="form-check-input ms-1" type="checkbox" role="switch" name="is_active" id="addDiscomActive" value="1" checked>
                                <label class="form-check-label fw-semibold text-navy ms-2" for="addDiscomActive">
                                    Set Provider as Active (Visible across Registration Portals)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-solar fw-bold px-4">
                        <i class="bi bi-check2-circle me-1"></i> Save DISCOM Provider
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 2: EDIT DISCOM PROVIDER -->
<!-- ========================================== -->
<div class="modal fade" id="modalEditDiscom" tabindex="-1" aria-labelledby="modalEditDiscomLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form id="formEditDiscom" action="" method="POST">
                <div class="modal-header bg-navy text-white">
                    <h5 class="modal-title font-heading fw-bold" id="modalEditDiscomLabel">
                        <i class="bi bi-pencil-square text-warning me-2"></i> Edit DISCOM Utility Provider
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">DISCOM Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="editDiscomCode" class="form-control text-uppercase font-monospace fw-bold" required maxlength="20">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-navy">Full Legal Utility Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editDiscomName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Short Name / Label</label>
                            <input type="text" name="short_name" id="editDiscomShortName" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Headquarters City</label>
                            <input type="text" name="headquarters" id="editDiscomHq" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Toll-Free Customer Helpline</label>
                            <input type="text" name="helpline" id="editDiscomHelpline" class="form-control font-monospace">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Consumer Portal URL</label>
                            <input type="url" name="portal_url" id="editDiscomPortal" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-navy">Coverage Summary / Geographical Scope</label>
                            <textarea name="coverage_summary" id="editDiscomCoverage" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch p-2 bg-light rounded border">
                                <input class="form-check-input ms-1" type="checkbox" role="switch" name="is_active" id="editDiscomActive" value="1">
                                <label class="form-check-label fw-semibold text-navy ms-2" for="editDiscomActive">
                                    Provider is Active & Enabled
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-green fw-bold px-4">
                        <i class="bi bi-save2-fill me-1"></i> Update Provider
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 3: MAP / REASSIGN DISTRICT -->
<!-- ========================================== -->
<div class="modal fade" id="modalAssignDistrict" tabindex="-1" aria-labelledby="modalAssignDistrictLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?= url('/admin/discoms/assign-district') ?>" method="POST">
                <div class="modal-header bg-navy text-white">
                    <h5 class="modal-title font-heading fw-bold" id="modalAssignDistrictLabel">
                        <i class="bi bi-geo-alt-fill text-warning me-2"></i> Map District to DISCOM Provider
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Odisha District <span class="text-danger">*</span></label>
                        <select name="district_name" id="assignDistrictName" class="form-select" required>
                            <option value="">-- Choose or enter District --</option>
                            <?php foreach ($allDistricts as $d): ?>
                                <option value="<?= htmlspecialchars($d) ?>"><?= htmlspecialchars($d) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Assigned DISCOM Provider <span class="text-danger">*</span></label>
                        <select name="discom_id" id="assignDiscomId" class="form-select fw-bold" required>
                            <option value="">-- Choose DISCOM Utility --</option>
                            <?php foreach ($providers as $p): ?>
                                <option value="<?= $p['id'] ?>">
                                    <?= htmlspecialchars($p['code']) ?> — <?= htmlspecialchars($p['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="alert alert-info small mb-0">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        When customers or advisors pick this District in registration, the system will automatically pre-select this DISCOM provider.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-solar fw-bold px-4">
                        <i class="bi bi-check2-circle me-1"></i> Save District Mapping
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 4: DELETE CONFIRMATION -->
<!-- ========================================== -->
<div class="modal fade" id="modalDeleteDiscom" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <form id="formDeleteDiscom" action="" method="POST">
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-1 mb-2 d-block"></i>
                    <h6 class="fw-bold text-navy mb-2">Delete DISCOM Provider?</h6>
                    <p class="small text-secondary mb-3">
                        Are you sure you want to delete <strong id="deleteDiscomCodeText"></strong>? All district mappings linked to this provider will also be removed.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm px-3 fw-bold">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDeleteMapping" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <form id="formDeleteMapping" action="" method="POST">
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-trash-fill text-danger fs-1 mb-2 d-block"></i>
                    <h6 class="fw-bold text-navy mb-2">Remove District Mapping?</h6>
                    <p class="small text-secondary mb-3">
                        Remove DISCOM mapping for District <strong id="deleteMappingDistrictText"></strong>?
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm px-3 fw-bold">Remove</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditDiscomModal(data) {
    document.getElementById('formEditDiscom').action = '<?= url('/admin/discoms/update/') ?>' + data.id;
    document.getElementById('editDiscomCode').value = data.code || '';
    document.getElementById('editDiscomName').value = data.name || '';
    document.getElementById('editDiscomShortName').value = data.short_name || '';
    document.getElementById('editDiscomHq').value = data.headquarters || '';
    document.getElementById('editDiscomHelpline').value = data.helpline || '';
    document.getElementById('editDiscomPortal').value = data.portal_url || '';
    document.getElementById('editDiscomCoverage').value = data.coverage_summary || '';
    document.getElementById('editDiscomActive').checked = (data.is_active == 1);

    const modal = new bootstrap.Modal(document.getElementById('modalEditDiscom'));
    modal.show();
}

function openReassignModal(districtName, currentDiscomId) {
    document.getElementById('assignDistrictName').value = districtName;
    document.getElementById('assignDiscomId').value = currentDiscomId;

    const modal = new bootstrap.Modal(document.getElementById('modalAssignDistrict'));
    modal.show();
}

function confirmDeleteDiscom(id, code) {
    document.getElementById('formDeleteDiscom').action = '<?= url('/admin/discoms/delete/') ?>' + id;
    document.getElementById('deleteDiscomCodeText').innerText = code;

    const modal = new bootstrap.Modal(document.getElementById('modalDeleteDiscom'));
    modal.show();
}

function confirmDeleteMapping(id, districtName) {
    document.getElementById('formDeleteMapping').action = '<?= url('/admin/discoms/delete-district/') ?>' + id;
    document.getElementById('deleteMappingDistrictText').innerText = districtName;

    const modal = new bootstrap.Modal(document.getElementById('modalDeleteMapping'));
    modal.show();
}

function filterDistrictTable() {
    const input = document.getElementById('filterDistrictInput').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.district-row');
    rows.forEach(r => {
        const name = r.getAttribute('data-name') || '';
        if (name.includes(input)) {
            r.style.display = '';
        } else {
            r.style.display = 'none';
        }
    });
}
</script>
