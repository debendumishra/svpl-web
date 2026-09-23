<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin & Manager — Solar Equipment & Kit Dispatches (15-Point Pipeline & 20 Instruments BOM)
 */
$title = "Equipment & Kit Dispatches — SVPL Admin";
$totalCount = count($dispatches ?? []);
$deliveredCount = 0;
$inTransitCount = 0;
foreach ($dispatches as $d) {
    if ($d['status'] === 'Delivered') $deliveredCount++;
    else $inTransitCount++;
}
$loanSanctionedLeads = $loanSanctionedLeads ?? \App\Models\PackageDispatch::getLoanSanctionedLeads();
$standardInstruments = $standardInstruments ?? \App\Models\PackageDispatch::STANDARD_INSTRUMENTS;
?>

<div class="container-fluid px-3 px-md-4 py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dispatches & Kits</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h3 class="font-heading fw-bold mb-0 text-navy">
                    <i class="bi bi-truck text-warning me-1"></i> Solar Instruments & Equipment Dispatches
                </h3>
                <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.75rem;">Stage 6: Instrument Despatched</span>
            </div>
            <p class="text-secondary small mb-0">Dispatch solar plant hardware, 20-point BOS instruments kit, and track transit for customers at Loan Sanctioned stage.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= url('/admin/instruments') ?>" class="btn btn-outline-navy btn-sm fw-bold shadow-sm">
                <i class="bi bi-tools text-warning me-1"></i> Manage Items & Default Quantities
            </a>
            <button type="button" class="btn btn-svpl-solar btn-sm shadow-sm fw-bold px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalInstrumentDispatch">
                <i class="bi bi-box-seam-fill me-1"></i> + Despatch Solar Instruments (Stage 6)
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalNewDispatch">
                <i class="bi bi-plus-circle me-1"></i> + General / Advisor Kit Dispatch
            </button>
            <a href="<?= url('/admin/export/csv?type=dispatches') ?>" class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
            </a>
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

    <!-- SUMMARY STAT CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100">
                <span class="text-secondary small fw-bold text-uppercase">Total Dispatches</span>
                <h3 class="fw-bold text-navy mb-0 mt-1"><?= $totalCount ?></h3>
                <div class="small text-muted" style="font-size: 0.72rem;">Shipments registered</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-warning border-3">
                <span class="text-secondary small fw-bold text-uppercase">In Transit / Dispatched</span>
                <h3 class="fw-bold text-warning-emphasis mb-0 mt-1"><?= $inTransitCount ?></h3>
                <div class="small text-warning" style="font-size: 0.72rem;">En-route to customer site</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-success border-3">
                <span class="text-secondary small fw-bold text-uppercase">Delivered On-Site</span>
                <h3 class="fw-bold text-success mb-0 mt-1"><?= $deliveredCount ?></h3>
                <div class="small text-success" style="font-size: 0.72rem;">Installation commenced</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-svpl bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-primary border-3">
                <span class="text-secondary small fw-bold text-uppercase">Loan Sanctioned Leads</span>
                <h3 class="fw-bold text-primary mb-0 mt-1"><?= count($loanSanctionedLeads) ?> Leads</h3>
                <div class="small text-muted" style="font-size: 0.72rem;">Ready for instrument despatch</div>
            </div>
        </div>
    </div>

<style>
.dispatches-table-card {
    overflow: visible !important;
}
.dispatches-table-card .table-responsive {
    overflow: visible !important;
    min-height: 280px;
}
@media (max-width: 991.98px) {
    .dispatches-table-card .table-responsive {
        overflow-x: auto !important;
    }
}
.dropdown-menu {
    z-index: 1070 !important;
}
.btn-action-group {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
</style>

    <!-- DISPATCHES TABLE -->
    <div class="card card-svpl dispatches-table-card p-3 p-md-4 bg-white border shadow-sm rounded-3">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="fw-bold text-navy mb-0">
                <i class="bi bi-list-task text-primary me-1"></i> Dispatches & Shipment Records
            </h5>
            <input type="text" id="filterDispatchSearch" class="form-control form-control-sm" style="max-width: 250px;" placeholder="🔍 Search vehicle, customer, LR..." onkeyup="filterDispatchesTable()">
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle small mb-0" id="tableDispatches">
                <thead class="table-light">
                    <tr class="text-secondary text-uppercase" style="font-size: 0.75rem;">
                        <th>Dispatch / LR #</th>
                        <th>Type</th>
                        <th>Recipient Customer / Lead</th>
                        <th>Assigned Engineer</th>
                        <th>Transport & Driver</th>
                        <th>Supplier Vendor</th>
                        <th>Materials / BOM</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions & Documents</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dispatches)): ?>
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 d-block mb-2 text-secondary"></i>
                                No equipment dispatches recorded yet. Click <strong>"+ Despatch Solar Instruments"</strong> to dispatch materials.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dispatches as $d): ?>
                            <?php 
                                $itemsList = !empty($d['items_json']) ? json_decode($d['items_json'], true) : [];
                                $mediaList = !empty($d['media_urls']) ? json_decode($d['media_urls'], true) : [];
                                $encodedDispatch = htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr class="dispatch-row">
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace fw-bold"><?= htmlspecialchars($d['tracking_number']) ?></span>
                                    <?php if (!empty($itemsList)): ?>
                                        <div class="mt-1">
                                            <span class="badge bg-primary-subtle text-primary border" style="font-size: 0.68rem;">
                                                <i class="bi bi-card-checklist me-1"></i> <?= count($itemsList) ?> Items BOM
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($d['dispatch_type'] === 'SOLAR_EQUIPMENT'): ?>
                                        <span class="badge bg-primary text-white">
                                             <i class="bi bi-sun"></i> Solar Instruments
                                        </span>
                                    <?php elseif ($d['dispatch_type'] === 'ADVISOR_KIT'): ?>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-person-badge"></i> Advisor Kit
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary text-white">
                                            <?= htmlspecialchars($d['dispatch_type']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($d['customer_name']) || !empty($d['cust_first'])): ?>
                                        <div class="fw-bold text-navy"><?= htmlspecialchars($d['customer_name'] ?? ($d['cust_first'] . ' ' . $d['cust_last'])) ?></div>
                                        <div class="text-secondary" style="font-size: 0.72rem;">
                                            <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($d['cust_district'] ?? 'Odisha') ?>
                                            <?php if (!empty($d['cust_mobile'])): ?> • <i class="bi bi-telephone"></i> <?= htmlspecialchars($d['cust_mobile']) ?><?php endif; ?>
                                        </div>
                                        <?php if (!empty($d['lead_code'])): ?>
                                            <span class="badge bg-light text-primary border font-monospace" style="font-size: 0.68rem;"><?= htmlspecialchars($d['lead_code']) ?></span>
                                        <?php endif; ?>
                                    <?php elseif (!empty($d['advisor_name'])): ?>
                                        <div class="fw-bold text-navy"><?= htmlspecialchars($d['advisor_name']) ?></div>
                                        <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.68rem;"><?= htmlspecialchars($d['advisor_code']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Direct Logistics</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($d['engineer_name'])): ?>
                                        <div class="fw-bold text-navy" style="font-size: 0.82rem;">
                                            <i class="bi bi-person-badge-fill text-warning me-1"></i><?= htmlspecialchars($d['engineer_name']) ?>
                                        </div>
                                        <div class="text-secondary" style="font-size: 0.72rem;">
                                            <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.65rem;"><?= htmlspecialchars($d['engineer_code'] ?? '') ?></span>
                                            <?php if (!empty($d['engineer_mobile'])): ?>
                                                • <a href="tel:<?= htmlspecialchars($d['engineer_mobile']) ?>" class="text-decoration-none text-success"><i class="bi bi-telephone"></i> <?= htmlspecialchars($d['engineer_mobile']) ?></a>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">Unassigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($d['vehicle_number'])): ?>
                                        <div class="fw-bold text-navy font-monospace"><i class="bi bi-truck text-warning me-1"></i> <?= htmlspecialchars($d['vehicle_number']) ?></div>
                                        <div class="small text-secondary" style="font-size: 0.72rem;">
                                            <?= htmlspecialchars($d['driver_name'] ?: 'Driver') ?> <?= !empty($d['driver_mobile']) ? ('(' . htmlspecialchars($d['driver_mobile']) . ')') : '' ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="fw-semibold text-navy"><?= htmlspecialchars($d['courier_partner']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold text-navy"><?= htmlspecialchars($d['vendor_name'] ?? 'OEM / SVPL Store') ?></div>
                                </td>
                                <td style="max-width: 200px;">
                                    <?php if (!empty($itemsList) && is_array($itemsList)): ?>
                                        <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2 btn-view-bom shadow-sm" 
                                                data-dispatch="<?= $encodedDispatch ?>"
                                                style="font-size: 0.72rem;">
                                            <i class="bi bi-card-checklist me-1"></i> <?= count($itemsList) ?> Items BOM
                                        </button>
                                        <div class="text-truncate text-muted mt-1" style="font-size: 0.7rem; max-width: 190px;">
                                            <?= htmlspecialchars($d['items_included'] ?? '') ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-truncate d-inline-block" style="max-width: 190px;" title="<?= htmlspecialchars($d['items_included'] ?? '') ?>">
                                            <?= htmlspecialchars($d['items_included'] ?? 'Standard Kit') ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($d['dispatch_date'] ?? 'N/A') ?></td>
                                <td>
                                    <?php if ($d['status'] === 'Delivered'): ?>
                                        <span class="badge bg-success text-white"><i class="bi bi-check-circle me-1"></i> Delivered</span>
                                        <?php if (!empty($d['delivery_date'])): ?>
                                            <div class="text-muted" style="font-size: 0.68rem;"><?= htmlspecialchars($d['delivery_date']) ?></div>
                                        <?php endif; ?>
                                    <?php elseif ($d['status'] === 'In Transit'): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-truck me-1"></i> In Transit</span>
                                    <?php elseif ($d['status'] === 'Out for Delivery'): ?>
                                        <span class="badge bg-info text-dark"><i class="bi bi-geo-alt me-1"></i> Out for Delivery</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary-subtle text-primary border"><i class="bi bi-box-seam me-1"></i> <?= htmlspecialchars($d['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end text-nowrap">
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <!-- Split E-Way Bill Button with Documents Dropdown -->
                                        <div class="btn-group btn-group-sm shadow-sm" role="group">
                                            <a href="<?= url('/print/eway-bill/' . $d['id']) ?>" target="_blank" class="btn btn-warning btn-sm text-dark fw-bold" title="Generate & Print E-Way Bill (EWB-01)">
                                                <i class="bi bi-truck me-1"></i> E-Way Bill
                                            </a>
                                            <button type="button" class="btn btn-warning btn-sm dropdown-toggle dropdown-toggle-split text-dark px-2" data-bs-toggle="dropdown" aria-expanded="false" data-bs-popper-config='{"strategy":"fixed"}' title="More Dispatch Documents">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 py-2" style="font-size: 0.82rem; min-width: 240px; z-index: 99999;">
                                                <li><h6 class="dropdown-header text-uppercase fw-bold text-navy py-1" style="font-size: 0.72rem;"><i class="bi bi-file-earmark-text text-primary me-1"></i>Official Dispatch Documents</h6></li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= url('/print/eway-bill/' . $d['id']) ?>" target="_blank">
                                                        <i class="bi bi-truck text-warning fs-6"></i> <span>Print E-Way Bill (EWB-01)</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= url('/print/dispatch-invoice/' . $d['id']) ?>" target="_blank">
                                                        <i class="bi bi-receipt-cutoff text-primary fs-6"></i> <span>Print GST Tax Invoice & BOM</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= url('/print/dispatch-challan/' . $d['id']) ?>" target="_blank">
                                                        <i class="bi bi-file-earmark-text text-info fs-6"></i> <span>Print Delivery Challan / Gate Pass</span>
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider my-1"></li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 btn-view-bom" href="javascript:void(0)" data-dispatch="<?= $encodedDispatch ?>">
                                                        <i class="bi bi-card-checklist text-success fs-6"></i> <span>View Equipment Checklist</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Status update dropdown form -->
                                        <form method="POST" action="<?= (strpos($_SERVER['REQUEST_URI'] ?? '', '/manager') !== false) ? url('/manager/dispatches/update-status') : url('/admin/dispatches/update-status') ?>" class="d-inline-block m-0">
                                            <input type="hidden" name="dispatch_id" value="<?= $d['id'] ?>">
                                            <select name="status" class="form-select form-select-sm py-1 fw-medium" style="font-size: 0.75rem; width: 115px;" onchange="this.form.submit()" title="Change Dispatch Status">
                                                <option value="Dispatched" <?= $d['status'] === 'Dispatched' ? 'selected' : '' ?>>Dispatched</option>
                                                <option value="In Transit" <?= $d['status'] === 'In Transit' ? 'selected' : '' ?>>In Transit</option>
                                                <option value="Out for Delivery" <?= $d['status'] === 'Out for Delivery' ? 'selected' : '' ?>>Out for Delivery</option>
                                                <option value="Delivered" <?= $d['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                            </select>
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

<!-- ========================================================================= -->
<!-- MODAL 1: INSTRUMENT DESPATCH (STAGE 6 - LOAN SANCTIONED CUSTOMER CHOSEN) -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalInstrumentDispatch" tabindex="-1" aria-labelledby="modalInstrumentDispatchLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-xl-down modal-xl modal-dialog-scrollable" style="max-width: 96vw;">
        <form method="POST" action="<?= (strpos($_SERVER['REQUEST_URI'] ?? '', '/manager') !== false) ? url('/manager/dispatches/create') : url('/admin/dispatches/create') ?>" class="modal-content border-0 shadow-lg rounded-3">
            <input type="hidden" name="dispatch_type" value="SOLAR_EQUIPMENT">
            
            <!-- HIGH-VISIBILITY CONTRAST HEADER -->
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #061528 0%, #0f2d59 100%) !important; color: #ffffff !important; border-bottom: 2px solid #f59e0b; flex-shrink: 0;">
                <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2 pe-2">
                    <div class="d-flex align-items-center gap-3">
                        <div style="background: #f59e0b; color: #061528; width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: bold; flex-shrink: 0;">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="modal-title font-heading fw-bold text-white mb-0" id="modalInstrumentDispatchLabel">
                                    Stage 6: Despatch Solar Instruments & Equipment
                                </h5>
                                <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.75rem;">15-Point Progress</span>
                            </div>
                            <p class="text-white-50 small mb-0 mt-0">Select a Loan Sanctioned customer, enter transport & driver details, and verify the standard Bill of Materials (BOM).</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-navy fw-bold px-3 py-2 border shadow-sm" id="bomLiveCounterBadge">
                            <i class="bi bi-check2-circle text-success me-1"></i> 20 / 20 Items Selected
                        </span>
                        <button type="submit" class="btn btn-warning text-dark fw-bold px-3 py-2 shadow-sm d-inline-flex align-items-center gap-1 border-0" style="background: #f59e0b; font-size: 0.85rem;">
                            <i class="bi bi-send-check-fill"></i> Save & Despatch
                        </button>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-3 p-lg-4 bg-light" style="overflow-y: auto;">
                
                <div class="row g-3 g-lg-4 mb-3">
                    <!-- SECTION 1: CUSTOMER SELECTION -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                <h6 class="fw-bold text-navy mb-0 font-heading">
                                    <i class="bi bi-person-check-fill text-primary me-1"></i> 1. Select Customer (at Loan Sanctioned Stage) *
                                </h6>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.7rem;">Stage 5: Loan Sanctioned</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-navy">Select Beneficiary Lead *</label>
                                <select name="lead_id" id="selectInstrumentLead" class="form-select form-select-lg fw-bold text-navy border-2 border-primary" required onchange="onInstrumentLeadChange(this)">
                                    <option value="">-- Choose Loan Sanctioned Customer Lead --</option>
                                    <?php foreach ($loanSanctionedLeads as $ld): 
                                        $ldAddrParts = array_filter([
                                            !empty($ld['address_line']) ? trim($ld['address_line']) : '',
                                            !empty($ld['cust_vil']) ? 'Vill: ' . trim($ld['cust_vil']) : '',
                                            !empty($ld['cust_gp']) ? 'GP: ' . trim($ld['cust_gp']) : '',
                                            !empty($ld['cust_block']) ? 'Block: ' . trim($ld['cust_block']) : '',
                                            !empty($ld['cust_dist']) ? 'Dist: ' . trim($ld['cust_dist']) : '',
                                            !empty($ld['cust_pin']) ? 'PIN: ' . trim($ld['cust_pin']) : '',
                                            !empty($ld['cust_mobile']) ? 'Mob: ' . trim($ld['cust_mobile']) : ''
                                        ], fn($v) => !empty($v));
                                        $fullLdAddress = implode(', ', $ldAddrParts);
                                        $isSanctioned = ($ld['stage'] === 'LOAN_SANCTIONED');
                                    ?>
                                        <option value="<?= $ld['id'] ?>" 
                                                data-address="<?= htmlspecialchars($fullLdAddress) ?>"
                                                data-customer="<?= htmlspecialchars(($ld['cust_first'] ?? '') . ' ' . ($ld['cust_last'] ?? '')) ?>"
                                                data-mobile="<?= htmlspecialchars($ld['cust_mobile'] ?? '') ?>"
                                                data-cap="<?= htmlspecialchars($ld['proposed_capacity_kw'] ?? $ld['pkg_cap'] ?? '3') ?>"
                                                data-brand="<?= htmlspecialchars($ld['package_brand'] ?? 'Tata Power Solar') ?>"
                                                data-discom="<?= htmlspecialchars($ld['cust_discom'] ?? 'TPCODL') ?>"
                                                data-ca="<?= htmlspecialchars($ld['cust_ca'] ?? '') ?>">
                                            <?= htmlspecialchars($ld['lead_code']) ?> — <?= htmlspecialchars(($ld['cust_first'] ?? '') . ' ' . ($ld['cust_last'] ?? '')) ?> (<?= htmlspecialchars($ld['cust_dist'] ?? 'Odisha') ?>) | <?= $ld['proposed_capacity_kw'] ?? '3' ?> kW <?= htmlspecialchars($ld['package_brand'] ?? '') ?> [<?= $ld['stage'] ?>]
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="small text-muted mt-1" id="leadAutoInfoBadge">
                                    <i class="bi bi-info-circle me-1"></i> Selecting customer automatically populates delivery address and updates solar panel quantity.
                                </div>
                            </div>

                            <div>
                                <label class="form-label small fw-bold text-navy">Delivery Site Address (Auto-filled from customer profile) *</label>
                                <textarea name="delivery_address" id="inputInstrumentAddress" class="form-control font-monospace text-navy" rows="3" placeholder="Installation site address will appear here..." required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: TRANSPORT & VENDOR DETAILS -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                <h6 class="fw-bold text-navy mb-0 font-heading">
                                    <i class="bi bi-truck-front-fill text-warning me-1"></i> 2. Vehicle, Driver & Supplier Vendor Details *
                                </h6>
                                <span class="badge bg-warning-subtle text-dark border border-warning-subtle" style="font-size: 0.7rem;">Transit Manifest</span>
                            </div>

                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <label class="form-label small fw-bold text-navy mb-1">Date of Dispatch *</label>
                                    <input type="date" name="dispatch_date" class="form-control form-control-sm fw-semibold" value="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label small fw-bold text-navy mb-1">Vehicle Number *</label>
                                    <input type="text" name="vehicle_number" class="form-control form-control-sm text-uppercase font-monospace fw-bold text-primary" placeholder="e.g. OD-02-AX-8912" required>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label small fw-bold text-navy mb-1">Person with Vehicle Name *</label>
                                    <input type="text" name="driver_name" class="form-control form-control-sm" placeholder="Driver / Supervisor Full Name" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label small fw-bold text-navy mb-1">Driver / Transporter Mobile *</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light text-secondary font-monospace">+91</span>
                                        <input type="tel" name="driver_mobile" class="form-control font-monospace" placeholder="10-digit mobile" pattern="[6-9][0-9]{9}" maxlength="10" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label small fw-bold text-navy mb-1">Vendor Supplied the Instruments *</label>
                                    <input type="text" name="vendor_name" id="inputDispatchVendor" class="form-control form-control-sm fw-semibold" placeholder="e.g. Tata Power Solar / Waaree / Dhwajja" value="Tata Power Solar & Authorized OEM" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label small fw-bold text-navy mb-1">Challan / LR Consignment No.</label>
                                    <input type="text" name="tracking_number" class="form-control form-control-sm font-monospace" placeholder="e.g. LR-OD-<?= date('Ym') ?>-<?= rand(100, 999) ?>">
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label small fw-bold text-navy mb-1">Courier / Logistics Desk *</label>
                                    <input type="text" name="courier_partner" class="form-control form-control-sm" value="SVPL Dedicated Site Logistics Fleet" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label small fw-bold text-navy mb-1">Gate Pass / Special Remarks</label>
                                    <input type="text" name="remarks" class="form-control form-control-sm" placeholder="e.g. Handle with care, safety sealed.">
                                </div>

                                <div class="col-12 mt-2">
                                    <label class="form-label small fw-bold text-navy mb-1 d-flex align-items-center gap-1">
                                        <i class="bi bi-person-badge-fill text-warning"></i> Assigned Field Solar Engineer *
                                        <span class="badge bg-primary-subtle text-primary border ms-1" style="font-size: 0.68rem;">Site Engineer</span>
                                    </label>
                                    <select name="engineer_id" class="form-select form-select-sm fw-bold border-2 border-primary-subtle" required>
                                        <option value="">-- Choose Field Engineer for On-Site Installation --</option>
                                        <?php foreach (($engineers ?? []) as $eng): ?>
                                            <option value="<?= $eng['id'] ?>">
                                                <?= htmlspecialchars($eng['full_name']) ?> (<?= htmlspecialchars($eng['engineer_code']) ?>) — <?= htmlspecialchars($eng['designation']) ?> [Coverage: <?= htmlspecialchars($eng['assigned_districts'] ?: 'All Odisha') ?>]
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form-text text-muted" style="font-size: 0.72rem;">
                                        The assigned engineer will receive full customer, site, and dispatched equipment details on their Engineer Portal.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: STANDARD BILL OF MATERIALS TABLE -->
                <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
                    <div class="card-header py-3 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: #0f2d59 !important; color: #ffffff !important;">
                        <div>
                            <h6 class="fw-bold mb-0 text-white d-flex align-items-center gap-2">
                                <i class="bi bi-card-checklist text-warning fs-5"></i> 3. Complete List of Solar Instruments & Specifications (with Editable Quantities)
                            </h6>
                            <small class="text-white-50">Standard solar installation kit. Deselect items not transported or modify quantities as per site load.</small>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-warning btn-sm py-1 px-3 fw-bold text-dark shadow-sm" onclick="toggleAllBOM(true)">
                                <i class="bi bi-check-all me-1"></i> Select All
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm py-1 px-3 fw-semibold" onclick="toggleAllBOM(false)">
                                <i class="bi bi-x-lg me-1"></i> Deselect All
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                        <table class="table table-hover table-striped align-middle mb-0" id="tableBOMForm">
                            <thead class="sticky-top shadow-sm" style="background: #e2e8f0; color: #0f2d59; font-size: 0.8rem; z-index: 10;">
                                <tr class="text-uppercase fw-bold">
                                    <th style="width: 60px;" class="text-center py-2">Select</th>
                                    <th style="width: 60px;" class="text-center py-2">S.No.</th>
                                    <th style="min-width: 240px;" class="py-2">Item Description</th>
                                    <th style="min-width: 380px;" class="py-2">Specifications / Make</th>
                                    <th style="width: 140px;" class="text-center py-2">Quantity</th>
                                    <th style="width: 100px;" class="text-center py-2">Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($standardInstruments as $sNo => $item): ?>
                                    <tr id="bomRow_<?= $sNo ?>" class="bom-table-row">
                                        <td class="text-center py-2">
                                            <input class="form-check-input bom-checkbox" type="checkbox" name="items[<?= $sNo ?>][selected]" value="1" checked id="checkBom_<?= $sNo ?>" onchange="onBomCheckboxChange(<?= $sNo ?>)" style="width: 1.25rem; height: 1.25rem; cursor: pointer;">
                                        </td>
                                        <td class="text-center text-muted fw-bold py-2"><?= $sNo ?></td>
                                        <td class="fw-bold text-navy py-2">
                                            <label for="checkBom_<?= $sNo ?>" class="mb-0 cursor-pointer text-navy"><?= htmlspecialchars($item['item']) ?></label>
                                            <input type="hidden" name="items[<?= $sNo ?>][item]" value="<?= htmlspecialchars($item['item']) ?>">
                                        </td>
                                        <td class="text-secondary py-2" style="font-size: 0.85rem;">
                                            <?= htmlspecialchars($item['spec']) ?>
                                            <input type="hidden" name="items[<?= $sNo ?>][spec]" value="<?= htmlspecialchars($item['spec']) ?>">
                                        </td>
                                        <td class="py-2">
                                            <input type="number" step="0.1" min="0.1" name="items[<?= $sNo ?>][qty]" id="bomQty_<?= $sNo ?>" class="form-control form-control-sm font-monospace fw-bold text-center bom-qty text-primary bg-white border-2 border-primary-subtle" value="<?= $item['qty'] ?>" required>
                                        </td>
                                        <td class="text-center py-2">
                                            <span class="badge bg-light text-dark border font-monospace px-2 py-1"><?= htmlspecialchars($item['unit']) ?></span>
                                            <input type="hidden" name="items[<?= $sNo ?>][unit]" value="<?= htmlspecialchars($item['unit']) ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- HIGH-VISIBILITY STICKY FOOTER -->
            <div class="modal-footer border-top p-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: #ffffff !important; flex-shrink: 0; box-shadow: 0 -4px 12px rgba(0,0,0,0.05);">
                <button type="button" class="btn btn-outline-secondary px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>
                <div class="d-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-warning text-dark fw-bold px-4 py-2 shadow fs-6 d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%) !important; border: 2px solid #d97706 !important; color: #061528 !important; min-height: 44px;">
                        <i class="bi bi-send-check-fill fs-5"></i> <span>Save & Despatch Instruments (Stage 6)</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 2: GENERAL ADVISOR KIT / MISC DISPATCH MODAL -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalNewDispatch" tabindex="-1" aria-labelledby="modalNewDispatchLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="<?= (strpos($_SERVER['REQUEST_URI'] ?? '', '/manager') !== false) ? url('/manager/dispatches/create') : url('/admin/dispatches/create') ?>">
                <div class="modal-header bg-navy text-white">
                    <h5 class="modal-title font-heading fw-bold" id="modalNewDispatchLabel">
                        <i class="bi bi-box-seam-fill text-warning me-2"></i> Create Advisor Induction Kit / Misc Dispatch
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Dispatch Type *</label>
                            <select name="dispatch_type" class="form-select" required>
                                <option value="ADVISOR_KIT">Advisor Welcome Induction Kit</option>
                                <option value="MARKETING_MATERIAL">Canopy, Standee & Marketing Kit</option>
                                <option value="OFFICE_STATIONERY">Office ID Cards & Stationery</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Recipient Advisor</label>
                            <select name="advisor_id" id="selectGeneralAdvisor" class="form-select">
                                <option value="">-- Select Advisor --</option>
                                <?php foreach (($advisors ?? []) as $adv): 
                                    $addrParts = array_filter([
                                        !empty($adv['address_line']) ? trim($adv['address_line']) : '',
                                        !empty($adv['village']) ? 'Vill: ' . trim($adv['village']) : '',
                                        !empty($adv['block']) ? 'Block: ' . trim($adv['block']) : '',
                                        !empty($adv['district']) ? 'Dist: ' . trim($adv['district']) : '',
                                        !empty($adv['pincode']) ? 'PIN: ' . trim($adv['pincode']) : '',
                                        !empty($adv['mobile']) ? 'Mob: ' . trim($adv['mobile']) : ''
                                    ], fn($v) => !empty($v));
                                    $fullAdvAddress = implode(', ', $addrParts);
                                ?>
                                    <option value="<?= $adv['id'] ?>" data-address="<?= htmlspecialchars($fullAdvAddress) ?>">
                                        <?= htmlspecialchars($adv['first_name'] . ' ' . $adv['last_name']) ?> (<?= htmlspecialchars($adv['advisor_code']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Courier Partner *</label>
                            <input type="text" name="courier_partner" class="form-control" value="DTDC Express Odisha" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Date of Dispatch *</label>
                            <input type="date" name="dispatch_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Items Included *</label>
                            <input type="text" name="items_included" class="form-control" value="Official Photo ID Card, Welcome Kit, SVPL Bag, QR Kit, Marketing Flyers" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Delivery Address *</label>
                            <textarea name="delivery_address" id="inputGeneralAddress" class="form-control font-monospace" rows="2" placeholder="Recipient postal address..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-solar fw-bold px-4">Create Dispatch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 3: VIEW BILL OF MATERIALS (BOM) & DISPATCH DETAILS MODAL -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalViewBOM" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #061528 0%, #0f2d59 100%) !important; color: #ffffff !important; border-bottom: 2px solid #f59e0b;">
                <h5 class="modal-title font-heading fw-bold text-white mb-0" id="viewBomModalTitle">
                    <i class="bi bi-card-checklist text-warning me-2"></i> Solar Dispatch Bill of Materials & Manifest
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="viewBomModalBody">
                <!-- Injected via JavaScript -->
            </div>
            <div class="modal-footer bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-warning btn-sm fw-bold text-dark shadow-sm" onclick="openDispatchDoc('eway-bill')">
                        <i class="bi bi-truck me-1"></i> Print E-Way Bill (EWB-01)
                    </button>
                    <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm" onclick="openDispatchDoc('dispatch-invoice')">
                        <i class="bi bi-receipt-cutoff me-1"></i> Print GST Tax Invoice & BOM
                    </button>
                    <button type="button" class="btn btn-outline-navy btn-sm fw-bold shadow-sm" onclick="openDispatchDoc('dispatch-challan')">
                        <i class="bi bi-file-earmark-text me-1"></i> Delivery Challan
                    </button>
                </div>
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentViewingDispatch = null;

function openDispatchDoc(type) {
    if (!currentViewingDispatch || !currentViewingDispatch.id) {
        alert('Please select a valid dispatch record.');
        return;
    }
    const baseUrl = '<?= rtrim(url("/print"), "/") ?>';
    const printUrl = baseUrl + '/' + type + '/' + currentViewingDispatch.id;
    window.open(printUrl, '_blank');
}

// Delegate click handler for view BOM buttons & initialize dropdowns
document.addEventListener('DOMContentLoaded', function() {
    // Ensure all Bootstrap dropdowns inside tables float cleanly using fixed strategy
    if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(el) {
            bootstrap.Dropdown.getOrCreateInstance(el, {
                popperConfig: function() {
                    return { strategy: 'fixed' };
                }
            });
        });
    }

    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-view-bom');
        if (btn) {
            e.preventDefault();
            let data = btn.getAttribute('data-dispatch');
            if (data) {
                try {
                    data = JSON.parse(data);
                } catch(err) {
                    console.error('Error parsing dispatch data:', err);
                }
            }
            if (data && typeof data === 'object') {
                viewDispatchBOM(data);
            }
        }
    });
});

function updateBomLiveCounter() {
    const total = document.querySelectorAll('.bom-checkbox').length;
    const selected = document.querySelectorAll('.bom-checkbox:checked').length;
    const badge = document.getElementById('bomLiveCounterBadge');
    if (badge) {
        badge.innerHTML = `<i class="bi bi-check2-circle text-success me-1"></i> ${selected} / ${total} Items Selected`;
        if (selected === total) {
            badge.className = 'badge bg-success-subtle text-success fw-bold px-3 py-2 border border-success';
        } else {
            badge.className = 'badge bg-warning-subtle text-dark fw-bold px-3 py-2 border border-warning';
        }
    }
}

function onInstrumentLeadChange(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (!opt || !opt.value) return;

    const addr = opt.getAttribute('data-address') || '';
    const cap = parseFloat(opt.getAttribute('data-cap')) || 3.0;
    const brand = opt.getAttribute('data-brand') || '';
    const discom = opt.getAttribute('data-discom') || '';

    // Set delivery address
    const addrInput = document.getElementById('inputInstrumentAddress');
    if (addrInput) addrInput.value = addr;

    // Set supplier vendor if available
    const vendorInput = document.getElementById('inputDispatchVendor');
    if (vendorInput && brand) {
        vendorInput.value = brand + ' & Tier-1 OEMs';
    }

    // Adjust standard panel quantity based on capacity (e.g. 540W panels: 3kW=6, 5kW=10, etc.)
    const panelQtyInput = document.querySelector('input[name="items[1][qty]"]');
    if (panelQtyInput) {
        const estPanels = Math.ceil((cap * 1000) / 540);
        panelQtyInput.value = estPanels > 0 ? estPanels : 6;
    }

    const badgeEl = document.getElementById('leadAutoInfoBadge');
    if (badgeEl) {
        badgeEl.innerHTML = `<span class="badge bg-success text-white py-1 px-2"><i class="bi bi-check-circle-fill me-1"></i> Loaded: ${cap} kW ${brand} for ${discom} customer</span>`;
    }
}

function onBomCheckboxChange(sNo) {
    const cb = document.getElementById('checkBom_' + sNo);
    const row = document.getElementById('bomRow_' + sNo);
    const qtyInput = document.getElementById('bomQty_' + sNo);
    if (!cb || !row) return;

    if (cb.checked) {
        row.classList.remove('opacity-50', 'table-secondary');
        if (qtyInput) qtyInput.removeAttribute('disabled');
    } else {
        row.classList.add('opacity-50', 'table-secondary');
        if (qtyInput) qtyInput.setAttribute('disabled', 'disabled');
    }
    updateBomLiveCounter();
}

function toggleAllBOM(state) {
    document.querySelectorAll('.bom-checkbox').forEach(cb => {
        cb.checked = state;
        const idMatch = cb.id.match(/\d+/);
        if (idMatch) {
            const sNo = parseInt(idMatch[0]);
            const row = document.getElementById('bomRow_' + sNo);
            const qtyInput = document.getElementById('bomQty_' + sNo);
            if (row) {
                if (state) {
                    row.classList.remove('opacity-50', 'table-secondary');
                    if (qtyInput) qtyInput.removeAttribute('disabled');
                } else {
                    row.classList.add('opacity-50', 'table-secondary');
                    if (qtyInput) qtyInput.setAttribute('disabled', 'disabled');
                }
            }
        }
    });
    updateBomLiveCounter();
}

function viewDispatchBOM(data) {
    currentViewingDispatch = data;
    let items = [];
    try {
        items = typeof data.items_json === 'string' ? JSON.parse(data.items_json) : (data.items_json || []);
    } catch (e) {
        items = [];
    }

    let html = `
        <div id="printableBOMContent">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold text-navy mb-0 font-heading">SURYA VISTAARA PVT. LTD. (SVPL)</h5>
                    <small class="text-secondary">Official Equipment Dispatch Manifest & Delivery Challan</small>
                </div>
                <div class="text-end">
                    <span class="badge bg-warning text-dark font-monospace fs-6 px-3 py-1">${data.tracking_number || ''}</span>
                    <div class="small text-muted mt-1">Date: <strong>${data.dispatch_date || 'N/A'}</strong></div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded border">
                        <div class="small text-secondary fw-bold text-uppercase">Vehicle & Transporter Details</div>
                        <div class="small text-dark mt-1">Vehicle No: <strong class="font-monospace text-primary">${data.vehicle_number || 'N/A'}</strong></div>
                        <div class="small text-dark">Driver / Person: <strong>${data.driver_name || 'N/A'}</strong> (${data.driver_mobile || 'N/A'})</div>
                        <div class="small text-dark">Supplier Vendor: <strong>${data.vendor_name || 'N/A'}</strong></div>
                        <div class="small text-dark">Logistics Desk: <strong>${data.courier_partner || 'SVPL Logistics'}</strong></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded border">
                        <div class="small text-secondary fw-bold text-uppercase">Delivery Customer & Site Address</div>
                        <div class="fw-bold text-navy fs-6 mt-1">${data.customer_name || data.cust_first || 'Customer Lead'}</div>
                        <div class="small text-secondary font-monospace mt-1">${data.delivery_address || ''}</div>
                        <div class="small text-dark mt-1">Status: <span class="badge bg-primary-subtle text-primary border">${data.status || 'Dispatched'}</span></div>
                    </div>
                </div>
            </div>
    `;

    if (items.length > 0) {
        html += `
            <h6 class="fw-bold text-navy mb-2"><i class="bi bi-card-checklist text-success me-1"></i> Transported Solar Instruments Checklist (${items.length} Items)</h6>
            <div class="table-responsive border rounded mb-3">
                <table class="table table-sm table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-secondary text-uppercase">
                            <th style="width: 45px;" class="text-center">S.No.</th>
                            <th>Item Description</th>
                            <th>Specifications / Make</th>
                            <th class="text-center" style="width: 120px;">Quantity</th>
                            <th class="text-center" style="width: 90px;">Unit</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        items.forEach((it, idx) => {
            html += `
                <tr>
                    <td class="text-center text-muted small fw-bold">${idx + 1}</td>
                    <td class="fw-bold text-navy">${it.item || ''}</td>
                    <td class="small text-secondary">${it.spec || ''}</td>
                    <td class="text-center fw-bold text-primary font-monospace">${it.qty || 1}</td>
                    <td class="text-center small text-dark">${it.unit || 'Nos.'}</td>
                </tr>
            `;
        });
        html += `</tbody></table></div>`;
    } else {
        html += `<div class="alert alert-info small mb-3">${data.items_included || 'Standard Kit'}</div>`;
    }

    if (data.remarks) {
        html += `
            <div class="p-2 bg-light rounded border small text-secondary mb-3">
                <strong>Gate Pass Remarks:</strong> ${data.remarks}
            </div>
        `;
    }

    html += `</div>`; // Close printableBOMContent

    document.getElementById('viewBomModalBody').innerHTML = html;
    const modal = new bootstrap.Modal(document.getElementById('modalViewBOM'));
    modal.show();
}

function printDispatchBOM() {
    const content = document.getElementById('printableBOMContent');
    if (!content) return;

    const printWin = window.open('', '_blank', 'width=900,height=700');
    printWin.document.write(`
        <html>
            <head>
                <title>Delivery Challan — SVPL Solar</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { font-family: system-ui, -apple-system, sans-serif; padding: 25px; color: #1e293b; }
                    .table { font-size: 0.85rem; }
                    @media print {
                        .no-print { display: none; }
                        body { padding: 0; }
                    }
                </style>
            </head>
            <body>
                \${content.innerHTML}
                <div class="row mt-5 pt-4 text-center small">
                    <div class="col-4 border-top pt-2">Receiver / Customer Signature</div>
                    <div class="col-4 border-top pt-2">Driver / Transporter Signature</div>
                    <div class="col-4 border-top pt-2">Authorized SVPL Signatory</div>
                </div>
                <script>
                    window.onload = function() { window.print(); }
                <\/script>
            </body>
        </html>
    `);
    printWin.document.close();
}

function filterDispatchesTable() {
    const input = document.getElementById('filterDispatchSearch').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.dispatch-row');
    rows.forEach(r => {
        const text = r.innerText.toLowerCase();
        if (text.includes(input)) {
            r.style.display = '';
        } else {
            r.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const genAdv = document.getElementById('selectGeneralAdvisor');
    const genAddr = document.getElementById('inputGeneralAddress');
    if (genAdv && genAddr) {
        genAdv.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.dataset && opt.dataset.address) {
                genAddr.value = opt.dataset.address;
            }
        });
    }

    // Initialize all Bootstrap dropdowns with fixed popper strategy
    if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(btn) {
            bootstrap.Dropdown.getOrCreateInstance(btn, {
                popperConfig: { strategy: 'fixed' }
            });
        });
    }
});
</script>
