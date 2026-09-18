<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin & Manager — Solar Installation Instruments & BOS Inventory Items Management
 * (Solar Luminary Design System)
 */
$title = "Solar Installation Instruments & BOS Kit — SVPL Admin";
$items = $items ?? [];
$activeCount = 0;
foreach ($items as $it) {
    if (!empty($it['is_active'])) $activeCount++;
}
$isManager = strpos($_SERVER['REQUEST_URI'] ?? '', '/manager') !== false;
$baseRoute = $isManager ? '/manager/instruments' : '/admin/instruments';
$dispatchesRoute = $isManager ? '/manager/dispatches' : '/admin/dispatches';
?>

<div class="container-fluid px-3 px-md-4 py-4 animate-fade-in">

    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="<?= url($isManager ? '/manager/dashboard' : '/admin/dashboard') ?>" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url($dispatchesRoute) ?>" class="text-decoration-none">Dispatches & Kits</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Instruments Management</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h3 class="font-heading fw-bold mb-0 text-navy">
                    <i class="bi bi-tools text-warning me-1"></i> Solar Instruments & BOS Kit Items Management
                </h3>
                <span class="badge bg-navy text-warning fw-bold px-2 py-1" style="font-size: 0.75rem;">Stage 6 Inventory</span>
            </div>
            <p class="text-secondary small mb-0">Add, modify, delete, and configure default quantities and specifications for the standard solar rooftop installation BOM.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= url($dispatchesRoute) ?>" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="bi bi-truck me-1"></i> Back to Dispatches Desk
            </a>
            <button type="button" class="btn btn-svpl-solar btn-sm shadow-sm fw-bold px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalAddInstrument">
                <i class="bi bi-plus-circle-fill me-1"></i> + Add New Instrument / BOS Item
            </button>
        </div>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
            <div><?= htmlspecialchars($success) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
            <div><?= htmlspecialchars($error) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- SUMMARY METRICS -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100">
                <span class="text-secondary small fw-bold text-uppercase">Total Standard Items</span>
                <h3 class="fw-bold text-navy mb-0 mt-1"><?= count($items) ?></h3>
                <div class="small text-muted" style="font-size: 0.72rem;">Configured in Master List</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-success border-3">
                <span class="text-secondary small fw-bold text-uppercase">Active in Dispatch BOM</span>
                <h3 class="fw-bold text-success mb-0 mt-1"><?= $activeCount ?> Items</h3>
                <div class="small text-success" style="font-size: 0.72rem;">Loaded in Stage 6 Modal</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-warning border-3">
                <span class="text-secondary small fw-bold text-uppercase">Standard System Size</span>
                <h3 class="fw-bold text-warning-emphasis mb-0 mt-1">3.0 kW</h3>
                <div class="small text-muted" style="font-size: 0.72rem;">Baseline Rooftop Kit</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-primary border-3">
                <span class="text-secondary small fw-bold text-uppercase">Quick Actions</span>
                <div class="mt-2">
                    <button type="button" class="btn btn-outline-primary btn-sm w-100 py-1 fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddInstrument">
                        <i class="bi bi-plus-lg me-1"></i> Add Item
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- INSTRUMENTS TABLE CARD -->
    <div class="card card-svpl p-3 p-md-4 bg-white border shadow-sm rounded-3">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold text-navy mb-0 font-heading">
                    <i class="bi bi-list-check text-primary me-1"></i> Master Instruments & Hardware Specification List
                </h5>
                <small class="text-secondary">Changes here automatically reflect in the Stage 6 Instrument Despatch selection desk.</small>
            </div>
            <input type="text" id="filterInstrumentSearch" class="form-control form-control-sm" style="max-width: 260px;" placeholder="🔍 Search item, spec, make..." onkeyup="filterInstrumentsTable()">
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle small mb-0" id="tableInstruments">
                <thead class="table-light">
                    <tr class="text-secondary text-uppercase" style="font-size: 0.75rem;">
                        <th style="width: 50px;" class="text-center">S.No.</th>
                        <th style="width: 90px;">Code</th>
                        <th style="min-width: 220px;">Item Description</th>
                        <th style="min-width: 320px;">Specifications / Make</th>
                        <th style="width: 140px;" class="text-center">Default Qty</th>
                        <th style="width: 90px;" class="text-center">Unit</th>
                        <th style="min-width: 130px;">Category</th>
                        <th style="width: 90px;" class="text-center">Status</th>
                        <th style="width: 140px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-tools fs-1 d-block mb-2 text-secondary"></i>
                                No instruments configured yet. Click <strong>"+ Add New Instrument"</strong> to populate your catalog.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($items as $idx => $it): ?>
                            <tr class="inst-row <?= empty($it['is_active']) ? 'table-secondary opacity-75' : '' ?>">
                                <td class="text-center text-muted fw-bold"><?= $it['sort_order'] ?? ($idx + 1) ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace fw-bold"><?= htmlspecialchars($it['item_code'] ?? 'INST-' . ($idx + 1)) ?></span>
                                </td>
                                <td>
                                    <strong class="text-navy fs-6"><?= htmlspecialchars($it['item_name']) ?></strong>
                                </td>
                                <td class="text-secondary">
                                    <?= htmlspecialchars($it['specifications']) ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-6 font-monospace px-3 py-1">
                                        <?= (float)$it['default_qty'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($it['unit']) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark border" style="font-size: 0.7rem;">
                                        <?= htmlspecialchars($it['category'] ?? 'BOS & Hardware') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($it['is_active'])): ?>
                                        <a href="<?= url($baseRoute . '/toggle/' . $it['id']) ?>" class="badge bg-success text-white text-decoration-none py-1 px-2" title="Click to Deactivate">
                                            <i class="bi bi-check-circle me-1"></i> Active
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= url($baseRoute . '/toggle/' . $it['id']) ?>" class="badge bg-secondary text-white text-decoration-none py-1 px-2" title="Click to Activate">
                                            <i class="bi bi-dash-circle me-1"></i> Inactive
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2" 
                                                onclick="openEditInstrumentModal(<?= htmlspecialchars(json_encode($it), ENT_QUOTES, 'UTF-8') ?>)" 
                                                title="Edit Item & Quantity">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm py-1 px-2" 
                                                onclick="confirmDeleteInstrument(<?= $it['id'] ?>, '<?= htmlspecialchars(addslashes($it['item_name']), ENT_QUOTES, 'UTF-8') ?>')" 
                                                title="Delete Item">
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

<!-- ========================================================================= -->
<!-- MODAL: ADD NEW INSTRUMENT / BOS ITEM -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalAddInstrument" tabindex="-1" aria-labelledby="modalAddInstrumentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form method="POST" action="<?= url($baseRoute . '/create') ?>">
                <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #061528 0%, #0f2d59 100%) !important; color: #ffffff !important; border-bottom: 2px solid #f59e0b;">
                    <h5 class="modal-title font-heading fw-bold text-white mb-0" id="modalAddInstrumentLabel">
                        <i class="bi bi-plus-circle-fill text-warning me-2"></i> Add Solar Instrument / BOS Item
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-navy">Item Description / Name *</label>
                            <input type="text" name="item_name" class="form-control" placeholder="e.g. Solar PV Modules / Solar Inverter / Earthing Electrodes" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Item Code</label>
                            <input type="text" name="item_code" class="form-control font-monospace" placeholder="e.g. INST-21">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-navy">Specifications / Make Details *</label>
                            <textarea name="specifications" class="form-control" rows="2" placeholder="e.g. 540Wp–550Wp Mono PERC / TOPCon Half-Cut Panels or 3 kW On-Grid Tie String Inverter" required></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Default Standard Quantity *</label>
                            <input type="number" step="0.1" min="0.1" name="default_qty" class="form-control font-monospace fw-bold text-primary" value="1.0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Unit of Measurement *</label>
                            <select name="unit" class="form-select fw-semibold" required>
                                <option value="Nos." selected>Nos. (Number)</option>
                                <option value="No.">No. (Single Unit)</option>
                                <option value="Sets">Sets</option>
                                <option value="Set">Set</option>
                                <option value="Meters">Meters</option>
                                <option value="Pairs">Pairs</option>
                                <option value="Lengths">Lengths</option>
                                <option value="Bags">Bags</option>
                                <option value="Packet">Packet</option>
                                <option value="Lot">Lot</option>
                                <option value="Kg">Kg</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Category</label>
                            <select name="category" class="form-select">
                                <option value="Generation & Panels">Generation & Panels</option>
                                <option value="Power Conditioning">Power Conditioning</option>
                                <option value="Structural & Mounting">Structural & Mounting</option>
                                <option value="Structural Hardware">Structural Hardware</option>
                                <option value="Fasteners">Fasteners</option>
                                <option value="Electrical Protection">Electrical Protection</option>
                                <option value="Cabling & Wiring">Cabling & Wiring</option>
                                <option value="Connectors">Connectors</option>
                                <option value="Conduits & Enclosures">Conduits & Enclosures</option>
                                <option value="Earthing & Safety">Earthing & Safety</option>
                                <option value="Accessories">Accessories</option>
                                <option value="Signage & Safety">Signage & Safety</option>
                                <option value="BOS & Hardware" selected>BOS & Hardware</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Sort Order / S.No Position</label>
                            <input type="number" name="sort_order" class="form-control font-monospace" placeholder="Leave empty for auto-increment">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top p-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-solar fw-bold px-4 shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Instrument Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: EDIT INSTRUMENT / BOS ITEM -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalEditInstrument" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form method="POST" id="formEditInstrument" action="">
                <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #061528 0%, #0f2d59 100%) !important; color: #ffffff !important; border-bottom: 2px solid #f59e0b;">
                    <h5 class="modal-title font-heading fw-bold text-white mb-0">
                        <i class="bi bi-pencil-square text-warning me-2"></i> Modify Solar Instrument & Default Quantity
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-navy">Item Description / Name *</label>
                            <input type="text" name="item_name" id="edit_item_name" class="form-control fw-bold" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Item Code</label>
                            <input type="text" name="item_code" id="edit_item_code" class="form-control font-monospace">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-navy">Specifications / Make Details *</label>
                            <textarea name="specifications" id="edit_specifications" class="form-control" rows="2" required></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Default Standard Quantity *</label>
                            <input type="number" step="0.1" min="0.1" name="default_qty" id="edit_default_qty" class="form-control font-monospace fw-bold text-primary" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Unit of Measurement *</label>
                            <select name="unit" id="edit_unit" class="form-select fw-semibold" required>
                                <option value="Nos.">Nos. (Number)</option>
                                <option value="No.">No. (Single Unit)</option>
                                <option value="Sets">Sets</option>
                                <option value="Set">Set</option>
                                <option value="Meters">Meters</option>
                                <option value="Pairs">Pairs</option>
                                <option value="Lengths">Lengths</option>
                                <option value="Bags">Bags</option>
                                <option value="Packet">Packet</option>
                                <option value="Lot">Lot</option>
                                <option value="Kg">Kg</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-navy">Category</label>
                            <select name="category" id="edit_category" class="form-select">
                                <option value="Generation & Panels">Generation & Panels</option>
                                <option value="Power Conditioning">Power Conditioning</option>
                                <option value="Structural & Mounting">Structural & Mounting</option>
                                <option value="Structural Hardware">Structural Hardware</option>
                                <option value="Fasteners">Fasteners</option>
                                <option value="Electrical Protection">Electrical Protection</option>
                                <option value="Cabling & Wiring">Cabling & Wiring</option>
                                <option value="Connectors">Connectors</option>
                                <option value="Conduits & Enclosures">Conduits & Enclosures</option>
                                <option value="Earthing & Safety">Earthing & Safety</option>
                                <option value="Accessories">Accessories</option>
                                <option value="Signage & Safety">Signage & Safety</option>
                                <option value="BOS & Hardware">BOS & Hardware</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Sort Order / S.No Position</label>
                            <input type="number" name="sort_order" id="edit_sort_order" class="form-control font-monospace">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-navy">Status in Dispatch Checklist</label>
                            <select name="is_active" id="edit_is_active" class="form-select fw-bold">
                                <option value="1">Active (Included in Stage 6 BOM)</option>
                                <option value="0">Inactive (Hidden from Stage 6 BOM)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top p-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-solar fw-bold px-4 shadow-sm">
                        <i class="bi bi-check2-square me-1"></i> Update Instrument
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DELETE CONFIRMATION FORM (POST) -->
<form id="formDeleteInstrument" method="POST" action="" style="display: none;"></form>

<script>
function openEditInstrumentModal(data) {
    document.getElementById('edit_item_name').value = data.item_name || '';
    document.getElementById('edit_item_code').value = data.item_code || '';
    document.getElementById('edit_specifications').value = data.specifications || '';
    document.getElementById('edit_default_qty').value = data.default_qty || 1;
    document.getElementById('edit_unit').value = data.unit || 'Nos.';
    document.getElementById('edit_category').value = data.category || 'BOS & Hardware';
    document.getElementById('edit_sort_order').value = data.sort_order || 1;
    document.getElementById('edit_is_active').value = data.is_active !== undefined ? data.is_active : 1;

    document.getElementById('formEditInstrument').action = '<?= url($baseRoute . '/update/') ?>' + data.id;

    const modal = new bootstrap.Modal(document.getElementById('modalEditInstrument'));
    modal.show();
}

function confirmDeleteInstrument(id, name) {
    if (confirm("Are you sure you want to delete '" + name + "' from the standard instruments catalog?")) {
        const form = document.getElementById('formDeleteInstrument');
        form.action = '<?= url($baseRoute . '/delete/') ?>' + id;
        form.submit();
    }
}

function filterInstrumentsTable() {
    const input = document.getElementById('filterInstrumentSearch').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.inst-row');
    rows.forEach(r => {
        const text = r.innerText.toLowerCase();
        if (text.includes(input)) {
            r.style.display = '';
        } else {
            r.style.display = 'none';
        }
    });
}
</script>
