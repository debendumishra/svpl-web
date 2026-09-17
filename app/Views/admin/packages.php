<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin & Manager — Solar Packages & Product Catalog Management
 */
$title = "Solar Packages & Product Catalog — SVPL Admin";
?>

<div class="container-fluid px-3 px-md-4 py-4">

    <!-- PAGE HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Solar Packages</li>
                </ol>
            </nav>
            <h1 class="h3 font-heading fw-bold text-navy mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-box-seam-fill text-warning"></i> Solar Packages & Product Catalog
            </h1>
            <p class="text-secondary small mb-0">Manage Tier-1 vendor packages, pricing slabs, PM Surya Ghar subsidies, and live catalog visibility.</p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="<?= url('/solar-solutions') ?>" target="_blank" class="btn btn-outline-navy btn-sm fw-bold">
                <i class="bi bi-globe me-1"></i> View Public Catalog
            </a>
            <button type="button" class="btn btn-svpl-solar btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddPackage">
                <i class="bi bi-plus-circle-fill me-1"></i> + Add New Package
            </button>
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

    <?php if (!empty($_SESSION['warning_msg'])): ?>
        <div class="alert alert-warning alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-warning"></i>
            <div><?= htmlspecialchars($_SESSION['warning_msg']) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['warning_msg']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error_msg'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-x-circle-fill fs-5 text-danger"></i>
            <div><?= htmlspecialchars($_SESSION['error_msg']) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_msg']); ?>
    <?php endif; ?>

    <!-- STATS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3 col-xl-2">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100">
                <div class="text-secondary small fw-semibold">Total Systems</div>
                <div class="fs-4 fw-bold text-navy mt-1"><?= (int)($stats['total'] ?? 0) ?></div>
                <div class="small text-muted" style="font-size: 0.75rem;">In database</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-2">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-success border-3">
                <div class="text-secondary small fw-semibold">Active Live</div>
                <div class="fs-4 fw-bold text-success mt-1"><?= (int)($stats['active'] ?? 0) ?></div>
                <div class="small text-success" style="font-size: 0.75rem;">Visible to public</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-2">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-secondary border-3">
                <div class="text-secondary small fw-semibold">Inactive / Hidden</div>
                <div class="fs-4 fw-bold text-secondary mt-1"><?= (int)($stats['inactive'] ?? 0) ?></div>
                <div class="small text-muted" style="font-size: 0.75rem;">Archived / draft</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100">
                <div class="text-secondary small fw-semibold">Partner Brands</div>
                <div class="fs-4 fw-bold text-navy mt-1"><?= (int)($stats['brands_count'] ?? 0) ?> Brands</div>
                <div class="small text-muted" style="font-size: 0.75rem;">Tata, Waaree, Adani, Loom, UTL, IYRO</div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-xl-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-warning border-3">
                <div class="text-secondary small fw-semibold">Average Package Rate</div>
                <div class="fs-4 fw-bold text-warning-emphasis mt-1">₹<?= number_format((float)($stats['avg_price'] ?? 0)) ?></div>
                <div class="small text-muted" style="font-size: 0.75rem;">Active systems average</div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH TOOLBAR -->
    <div class="card bg-white border shadow-sm p-3 mb-4 rounded-3">
        <form method="GET" action="<?= url('/admin/packages') ?>" class="row g-2 align-items-center">
            
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-secondary"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search brand, title, code, specs..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>
            </div>

            <div class="col-6 col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="ACTIVE" <?= ($filters['status'] ?? '') === 'ACTIVE' ? 'selected' : '' ?>>Active Only</option>
                    <option value="INACTIVE" <?= ($filters['status'] ?? '') === 'INACTIVE' ? 'selected' : '' ?>>Inactive Only</option>
                </select>
            </div>

            <div class="col-6 col-md-3">
                <select name="brand" class="form-select form-select-sm">
                    <option value="ALL">All Vendor Brands</option>
                    <?php foreach ($brands as $b): ?>
                        <option value="<?= htmlspecialchars($b) ?>" <?= ($filters['brand'] ?? '') === $b ? 'selected' : '' ?>>
                            <?= htmlspecialchars($b) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <select name="system_type" class="form-select form-select-sm">
                    <option value="ALL">All Systems</option>
                    <option value="On-Grid" <?= ($filters['system_type'] ?? '') === 'On-Grid' ? 'selected' : '' ?>>On-Grid</option>
                    <option value="Hybrid" <?= ($filters['system_type'] ?? '') === 'Hybrid' ? 'selected' : '' ?>>Hybrid</option>
                    <option value="Off-Grid" <?= ($filters['system_type'] ?? '') === 'Off-Grid' ? 'selected' : '' ?>>Off-Grid</option>
                </select>
            </div>

            <div class="col-6 col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-navy btn-sm w-100 fw-bold" title="Apply Filters">
                    <i class="bi bi-funnel-fill"></i>
                </button>
                <a href="<?= url('/admin/packages') ?>" class="btn btn-outline-secondary btn-sm" title="Clear Filters">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>

        </form>
    </div>

    <!-- PACKAGES TABLE CARD -->
    <div class="card bg-white border shadow-sm rounded-3 overflow-hidden">
        <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="fw-bold text-navy small d-flex align-items-center gap-2">
                <i class="bi bi-table text-warning"></i> Packages List 
                <span class="badge bg-navy text-white ms-1"><?= count($packages) ?> Packages</span>
            </div>
            <div class="small text-muted">
                Tip: Click <strong>Active/Inactive badge</strong> to toggle public visibility instantly.
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                <thead style="background: #0B2545; color: #ffffff;">
                    <tr>
                        <th class="ps-3 py-3" style="width: 90px;">Status</th>
                        <th class="py-3">Vendor / Brand & Title</th>
                        <th class="py-3 text-center">Capacity</th>
                        <th class="py-3">System Type</th>
                        <th class="py-3 text-end">Gross Amount</th>
                        <th class="py-3 text-end">Govt. Subsidy</th>
                        <th class="py-3 text-end">Net Cost</th>
                        <th class="py-3" style="min-width: 200px;">Key Features / Specs</th>
                        <th class="pe-3 py-3 text-end" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($packages)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-2 text-secondary"></i>
                                No solar packages found matching your criteria.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($packages as $pkg): ?>
                            <?php
                                $isActive = ($pkg['is_active'] == 1);
                                $pType = $pkg['system_type'] ?? 'On-Grid';
                                $isHybrid = stripos($pType, 'Hybrid') !== false;
                                $isOffGrid = stripos($pType, 'Off-Grid') !== false;
                                $leadsCount = (int)($pkg['leads_count'] ?? 0);
                            ?>
                            <tr class="<?= !$isActive ? 'table-light opacity-75' : '' ?>">
                                
                                <!-- Status Toggle -->
                                <td class="ps-3">
                                    <a href="<?= url('/admin/packages/toggle/' . $pkg['id']) ?>" 
                                       class="badge text-decoration-none <?= $isActive ? 'bg-success' : 'bg-secondary' ?> p-2 d-inline-block" 
                                       title="Click to <?= $isActive ? 'Deactivate' : 'Activate' ?>">
                                        <i class="bi <?= $isActive ? 'bi-check-circle-fill' : 'bi-dash-circle-fill' ?> me-1"></i>
                                        <?= $isActive ? 'Active' : 'Inactive' ?>
                                    </a>
                                </td>

                                <!-- Brand & Title -->
                                <td>
                                    <div class="fw-bold text-navy"><?= htmlspecialchars($pkg['brand'] ?? 'Dhwajja Solar') ?></div>
                                    <div class="text-dark small fw-semibold"><?= htmlspecialchars($pkg['title']) ?></div>
                                    <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.72rem;">
                                        <?= htmlspecialchars($pkg['package_code']) ?>
                                    </span>
                                    <?php if ($leadsCount > 0): ?>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle ms-1" style="font-size: 0.7rem;" title="<?= $leadsCount ?> customer leads using this package">
                                            <?= $leadsCount ?> Leads
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Capacity -->
                                <td class="text-center fw-bold">
                                    <span class="badge bg-secondary-subtle text-dark fs-6">
                                        <?= number_format((float)$pkg['capacity_kw'], 0) ?> kW
                                    </span>
                                </td>

                                <!-- System Type -->
                                <td>
                                    <span class="badge <?= $isHybrid ? 'bg-info text-dark' : ($isOffGrid ? 'bg-danger text-white' : 'bg-primary text-white') ?> fw-bold px-2 py-1">
                                        <i class="bi <?= $isHybrid ? 'bi-battery-charging' : ($isOffGrid ? 'bi-slash-circle' : 'bi-plug-fill') ?> me-1"></i>
                                        <?= htmlspecialchars($pType) ?>
                                    </span>
                                </td>

                                <!-- Gross Amount -->
                                <td class="text-end font-monospace fw-bold text-dark">
                                    ₹<?= number_format((float)$pkg['total_price']) ?>
                                </td>

                                <!-- Subsidy -->
                                <td class="text-end font-monospace fw-bold <?= (float)$pkg['estimated_subsidy'] > 0 ? 'text-success' : 'text-muted' ?>">
                                    <?= (float)$pkg['estimated_subsidy'] > 0 ? ('- ₹' . number_format((float)$pkg['estimated_subsidy'])) : '₹0' ?>
                                </td>

                                <!-- Net Cost -->
                                <td class="text-end font-monospace fw-bold text-navy fs-6">
                                    ₹<?= number_format((float)$pkg['net_customer_cost']) ?>
                                </td>

                                <!-- Key Features & Specs -->
                                <td class="small text-secondary">
                                    <?php if (!empty($pkg['key_features']) && $pkg['key_features'] !== 'None'): ?>
                                        <div class="text-dark fw-semibold mb-1"><?= htmlspecialchars($pkg['key_features']) ?></div>
                                    <?php endif; ?>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        <span><i class="bi bi-sun text-warning me-1"></i><?= htmlspecialchars($pkg['panel_type'] ?? '') ?></span>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        <span><i class="bi bi-cpu text-primary me-1"></i><?= htmlspecialchars($pkg['inverter_type'] ?? '') ?></span>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="pe-3 text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary btn-edit-pkg" 
                                                data-pkg='<?= htmlspecialchars(json_encode($pkg), ENT_QUOTES, 'UTF-8') ?>'
                                                title="Edit Package">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="<?= url('/admin/packages/toggle/' . $pkg['id']) ?>" 
                                           class="btn btn-outline-secondary" 
                                           title="<?= $isActive ? 'Deactivate / Hide' : 'Activate / Show' ?>">
                                            <i class="bi <?= $isActive ? 'bi-eye-slash' : 'bi-eye' ?>"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-delete-pkg" 
                                                data-id="<?= $pkg['id'] ?>"
                                                data-title="<?= htmlspecialchars($pkg['title']) ?>"
                                                data-code="<?= htmlspecialchars($pkg['package_code']) ?>"
                                                data-leads="<?= $leadsCount ?>"
                                                title="Delete Package">
                                            <i class="bi bi-trash"></i>
                                        </button>
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
<!-- MODAL: ADD NEW SOLAR PACKAGE -->
<!-- ========================================== -->
<div class="modal fade" id="modalAddPackage" tabindex="-1" aria-labelledby="modalAddPackageLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <form method="POST" action="<?= url('/admin/packages/create') ?>" id="formAddPackage">
                
                <div class="modal-header bg-navy text-white">
                    <h5 class="modal-title font-heading fw-bold" id="modalAddPackageLabel">
                        <i class="bi bi-plus-circle-fill text-warning me-1"></i> Add New Solar Package
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        
                        <!-- Vendor / Brand -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Vendor / Manufacturer Brand <span class="text-danger">*</span></label>
                            <input type="text" name="brand" list="listAddBrands" class="form-control" placeholder="e.g. Tata Power Solar, Waaree, Adani" required>
                            <datalist id="listAddBrands">
                                <?php foreach ($brands as $b): ?>
                                    <option value="<?= htmlspecialchars($b) ?>">
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <!-- System Title -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Package Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Tata Power Solar 3kW On-Grid Plant" required>
                        </div>

                        <!-- Package Code -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Package Code <small class="text-muted">(Optional, Auto-Generated)</small></label>
                            <input type="text" name="package_code" class="form-control font-monospace" placeholder="e.g. PKG-TATA-3KW-ONGRID">
                        </div>

                        <!-- Capacity in kW -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Capacity (kW) <span class="text-danger">*</span></label>
                            <input type="number" step="0.5" name="capacity_kw" id="addCapKw" class="form-control fw-bold" placeholder="e.g. 3.0" value="3.0" required oninput="autoCalculateAddNet()">
                        </div>

                        <!-- System Type -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">System Type <span class="text-danger">*</span></label>
                            <select name="system_type" id="addSystemType" class="form-select" required onchange="autoCalculateAddNet()">
                                <option value="On-Grid" selected>On-Grid (Net-Metered with Subsidy)</option>
                                <option value="Hybrid">Hybrid (Solar + Battery Storage)</option>
                                <option value="Off-Grid">Off-Grid (Self-Sustaining)</option>
                                <option value="On-Grid / Hybrid">On-Grid / Hybrid</option>
                            </select>
                        </div>

                        <!-- Gross Total Price -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Gross Total Price (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="1000" name="total_price" id="addTotalPrice" class="form-control fw-bold" placeholder="230000" required oninput="autoCalculateAddNet()">
                            </div>
                        </div>

                        <!-- Govt Subsidy -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">PM Surya Ghar Subsidy (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="1000" name="estimated_subsidy" id="addSubsidePrice" class="form-control fw-bold text-success" value="138000" oninput="autoCalculateAddNet()">
                            </div>
                        </div>

                        <!-- Net Customer Cost -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Net Customer Cost (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="1000" name="net_customer_cost" id="addNetPrice" class="form-control fw-bold text-primary" placeholder="Auto-calculated">
                            </div>
                        </div>

                        <!-- Panel Specs -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Solar Panel Specs</label>
                            <input type="text" name="panel_type" class="form-control" placeholder="e.g. Mono PERC / TOPCon DCR Tier-1" value="Mono PERC / DCR Tier-1">
                        </div>

                        <!-- Inverter Specs -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Inverter Specs</label>
                            <input type="text" name="inverter_type" class="form-control" placeholder="e.g. 3kW Smart Grid-Tied Inverter (Wi-Fi)" value="Smart Grid-Tied Inverter (Wi-Fi)">
                        </div>

                        <!-- Key Features / Notes -->
                        <div class="col-12">
                            <label class="form-label small fw-bold text-navy">Key Features & Battery Notes</label>
                            <textarea name="key_features" class="form-control" rows="2" placeholder="e.g. Offers Mono PERC & TOPCon panels; includes Lithium LFP battery bank."></textarea>
                        </div>

                        <!-- Checkboxes: Battery Included & Active Status -->
                        <div class="col-md-6">
                            <div class="form-check p-2 bg-light rounded border">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="battery_included" value="1" id="addBatteryInc">
                                <label class="form-check-label small fw-bold text-navy" for="addBatteryInc">
                                    <i class="bi bi-battery-charging text-info me-1"></i> Battery Storage Included
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check p-2 bg-light rounded border">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="is_active" value="1" id="addIsActive" checked>
                                <label class="form-check-label small fw-bold text-success" for="addIsActive">
                                    <i class="bi bi-check-circle-fill me-1"></i> Active (Immediately Live in Public Catalog)
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-solar btn-sm fw-bold">
                        <i class="bi bi-save-fill me-1"></i> Save Package
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: EDIT SOLAR PACKAGE -->
<!-- ========================================== -->
<div class="modal fade" id="modalEditPackage" tabindex="-1" aria-labelledby="modalEditPackageLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <form method="POST" action="" id="formEditPackage">
                
                <div class="modal-header bg-navy text-white">
                    <h5 class="modal-title font-heading fw-bold" id="modalEditPackageLabel">
                        <i class="bi bi-pencil-square text-warning me-1"></i> Modify Solar Package #<span id="editPkgCodeLabel"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        
                        <!-- Vendor / Brand -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Vendor / Manufacturer Brand <span class="text-danger">*</span></label>
                            <input type="text" name="brand" id="editBrand" list="listEditBrands" class="form-control" required>
                            <datalist id="listEditBrands">
                                <?php foreach ($brands as $b): ?>
                                    <option value="<?= htmlspecialchars($b) ?>">
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <!-- System Title -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Package Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="editTitle" class="form-control" required>
                        </div>

                        <!-- Package Code -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Package Code <span class="text-danger">*</span></label>
                            <input type="text" name="package_code" id="editPackageCode" class="form-control font-monospace" required>
                        </div>

                        <!-- Capacity in kW -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Capacity (kW) <span class="text-danger">*</span></label>
                            <input type="number" step="0.5" name="capacity_kw" id="editCapKw" class="form-control fw-bold" required oninput="autoCalculateEditNet()">
                        </div>

                        <!-- System Type -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">System Type <span class="text-danger">*</span></label>
                            <select name="system_type" id="editSystemType" class="form-select" required onchange="autoCalculateEditNet()">
                                <option value="On-Grid">On-Grid (Net-Metered with Subsidy)</option>
                                <option value="Hybrid">Hybrid (Solar + Battery Storage)</option>
                                <option value="Off-Grid">Off-Grid (Self-Sustaining)</option>
                                <option value="On-Grid / Hybrid">On-Grid / Hybrid</option>
                            </select>
                        </div>

                        <!-- Gross Total Price -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Gross Total Price (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="1000" name="total_price" id="editTotalPrice" class="form-control fw-bold" required oninput="autoCalculateEditNet()">
                            </div>
                        </div>

                        <!-- Govt Subsidy -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">PM Surya Ghar Subsidy (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="1000" name="estimated_subsidy" id="editSubsidePrice" class="form-control fw-bold text-success" oninput="autoCalculateEditNet()">
                            </div>
                        </div>

                        <!-- Net Customer Cost -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Net Customer Cost (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="1000" name="net_customer_cost" id="editNetPrice" class="form-control fw-bold text-primary">
                            </div>
                        </div>

                        <!-- Panel Specs -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Solar Panel Specs</label>
                            <input type="text" name="panel_type" id="editPanelType" class="form-control">
                        </div>

                        <!-- Inverter Specs -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Inverter Specs</label>
                            <input type="text" name="inverter_type" id="editInverterType" class="form-control">
                        </div>

                        <!-- Key Features / Notes -->
                        <div class="col-12">
                            <label class="form-label small fw-bold text-navy">Key Features & Battery Notes</label>
                            <textarea name="key_features" id="editKeyFeatures" class="form-control" rows="2"></textarea>
                        </div>

                        <!-- Checkboxes: Battery Included & Active Status -->
                        <div class="col-md-6">
                            <div class="form-check p-2 bg-light rounded border">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="battery_included" value="1" id="editBatteryInc">
                                <label class="form-check-label small fw-bold text-navy" for="editBatteryInc">
                                    <i class="bi bi-battery-charging text-info me-1"></i> Battery Storage Included
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check p-2 bg-light rounded border">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="is_active" value="1" id="editIsActive">
                                <label class="form-check-label small fw-bold text-success" for="editIsActive">
                                    <i class="bi bi-check-circle-fill me-1"></i> Active (Live in Catalog)
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">
                        <i class="bi bi-check-lg me-1"></i> Update Package
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: DELETE PACKAGE CONFIRMATION -->
<!-- ========================================== -->
<div class="modal fade" id="modalDeletePackage" tabindex="-1" aria-labelledby="modalDeletePackageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <form method="POST" action="" id="formDeletePackage">
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title font-heading fw-bold" id="modalDeletePackageLabel">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Confirm Delete Package
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 text-center">
                    <i class="bi bi-trash text-danger display-4 d-block mb-3"></i>
                    <h5 class="text-navy fw-bold" id="delPackageTitle"></h5>
                    <p class="text-muted font-monospace small" id="delPackageCode"></p>
                    
                    <div class="alert alert-warning text-start small mb-0 mt-3" id="delLeadsWarning" style="display: none;">
                        <i class="bi bi-info-circle-fill me-1"></i> <strong>Note:</strong> This package is linked to <span id="delLeadsCount" class="fw-bold"></span> active customer lead(s). It will be <strong>safely deactivated</strong> instead of permanently removed to protect contract data integrity.
                    </div>
                    
                    <p class="text-secondary small mt-2" id="delNormalWarning">
                        Are you sure you want to permanently remove this package from the database?
                    </p>
                </div>

                <div class="modal-footer bg-light justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm px-4 fw-bold">
                        <i class="bi bi-trash-fill me-1"></i> Yes, Delete Package
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
// Auto-calculation helper for Add Modal
function autoCalculateAddNet() {
    const gross = parseFloat(document.getElementById('addTotalPrice').value) || 0;
    const sub = parseFloat(document.getElementById('addSubsidePrice').value) || 0;
    const net = Math.max(0, gross - sub);
    document.getElementById('addNetPrice').value = net;
}

// Auto-calculation helper for Edit Modal
function autoCalculateEditNet() {
    const gross = parseFloat(document.getElementById('editTotalPrice').value) || 0;
    const sub = parseFloat(document.getElementById('editSubsidePrice').value) || 0;
    const net = Math.max(0, gross - sub);
    document.getElementById('editNetPrice').value = net;
}

// Populate Edit Modal
document.querySelectorAll('.btn-edit-pkg').forEach(btn => {
    btn.addEventListener('click', function() {
        const pkgData = JSON.parse(this.getAttribute('data-pkg'));
        
        document.getElementById('formEditPackage').action = '<?= url('/admin/packages/update/') ?>' + pkgData.id;
        document.getElementById('editPkgCodeLabel').innerText = pkgData.package_code;
        document.getElementById('editBrand').value = pkgData.brand || '';
        document.getElementById('editTitle').value = pkgData.title || '';
        document.getElementById('editPackageCode').value = pkgData.package_code || '';
        document.getElementById('editCapKw').value = pkgData.capacity_kw || '';
        document.getElementById('editSystemType').value = pkgData.system_type || 'On-Grid';
        document.getElementById('editTotalPrice').value = parseFloat(pkgData.total_price) || 0;
        document.getElementById('editSubsidePrice').value = parseFloat(pkgData.estimated_subsidy) || 0;
        document.getElementById('editNetPrice').value = parseFloat(pkgData.net_customer_cost) || 0;
        document.getElementById('editPanelType').value = pkgData.panel_type || '';
        document.getElementById('editInverterType').value = pkgData.inverter_type || '';
        document.getElementById('editKeyFeatures').value = pkgData.key_features || '';
        document.getElementById('editBatteryInc').checked = (pkgData.battery_included == 1);
        document.getElementById('editIsActive').checked = (pkgData.is_active == 1);

        const editModal = new bootstrap.Modal(document.getElementById('modalEditPackage'));
        editModal.show();
    });
});

// Populate Delete Modal
document.querySelectorAll('.btn-delete-pkg').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const title = this.getAttribute('data-title');
        const code = this.getAttribute('data-code');
        const leads = parseInt(this.getAttribute('data-leads')) || 0;

        document.getElementById('formDeletePackage').action = '<?= url('/admin/packages/delete/') ?>' + id;
        document.getElementById('delPackageTitle').innerText = title;
        document.getElementById('delPackageCode').innerText = code;

        if (leads > 0) {
            document.getElementById('delLeadsWarning').style.display = 'block';
            document.getElementById('delLeadsCount').innerText = leads;
            document.getElementById('delNormalWarning').style.display = 'none';
        } else {
            document.getElementById('delLeadsWarning').style.display = 'none';
            document.getElementById('delNormalWarning').style.display = 'block';
        }

        const delModal = new bootstrap.Modal(document.getElementById('modalDeletePackage'));
        delModal.show();
    });
});
</script>
