<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Solar Field Engineer Dashboard & Active Site Dispatch Stream
 */
$title = "Field Engineer Command Center — SVPL";
?>

<div class="container-fluid px-0">

    <!-- WELCOME BANNER -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0f2d59 0%, #1e3a8a 100%); color: #ffffff;">
        <div class="card-body p-4 p-md-4 position-relative">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-warning text-dark font-monospace fw-bold px-2 py-1">
                            <i class="bi bi-person-badge-fill me-1"></i> <?= htmlspecialchars($engineer['engineer_code'] ?? 'SVPL-ENG') ?>
                        </span>
                        <span class="badge bg-light text-navy fw-semibold">
                            <?= htmlspecialchars($engineer['designation'] ?? 'Field Solar Engineer') ?>
                        </span>
                    </div>
                    <h2 class="h3 font-heading fw-bold mb-1">
                        Welcome back, <?= htmlspecialchars($engineer['full_name'] ?? ($user['full_name'] ?? 'Engineer')) ?>! ⚡
                    </h2>
                    <p class="text-white-50 small mb-2">
                        Assigned Operational Districts: 
                        <strong class="text-warning"><?= htmlspecialchars($engineer['assigned_districts'] ?? 'All Odisha Districts') ?></strong>
                    </p>
                    <div class="d-flex flex-wrap gap-2 pt-1">
                        <a href="<?= url('/engineer/installations') ?>" class="btn btn-warning btn-sm fw-bold text-dark shadow-sm">
                            <i class="bi bi-tools me-1"></i> View All Installations (<?= (int)($stats['total_assigned'] ?? 0) ?>)
                        </a>
                        <a href="<?= url('/operations-guide') ?>" target="_blank" class="btn btn-outline-light btn-sm fw-semibold">
                            <i class="bi bi-play-circle me-1"></i> Technical Standard Operating Procedure
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end d-none d-lg-block">
                    <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10 text-start d-inline-block">
                        <div class="small text-white-50">Qualification & Experience</div>
                        <div class="fw-bold text-white"><?= htmlspecialchars($engineer['qualification'] ?? 'B.Tech / Diploma Electrical') ?></div>
                        <div class="small text-warning mt-1"><i class="bi bi-award-fill me-1"></i> <?= (int)($engineer['experience_years'] ?? 0) ?> Years Field Experience</div>
                    </div>
                </div>
            </div>
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

    <?php if (!empty($_SESSION['error_msg'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-x-circle-fill fs-5 text-danger"></i>
            <div><?= htmlspecialchars($_SESSION['error_msg']) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_msg']); ?>
    <?php endif; ?>

    <!-- STATS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-primary border-4">
                <div class="text-secondary small fw-semibold">Assigned Dispatches</div>
                <div class="fs-3 fw-bold text-navy mt-1"><?= (int)($stats['total_assigned'] ?? 0) ?></div>
                <div class="small text-muted" style="font-size: 0.75rem;">Customer site dispatches</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-warning border-4">
                <div class="text-secondary small fw-semibold">In Transit / On Way</div>
                <div class="fs-3 fw-bold text-warning-emphasis mt-1"><?= (int)($stats['in_transit'] ?? 0) ?></div>
                <div class="small text-muted" style="font-size: 0.75rem;">Materials on transport</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-info border-4">
                <div class="text-secondary small fw-semibold">Customer Acknowledged</div>
                <div class="fs-3 fw-bold text-info mt-1"><?= (int)($stats['acknowledged'] ?? 0) ?></div>
                <div class="small text-muted" style="font-size: 0.75rem;">Verified received on-site</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-white border shadow-sm p-3 rounded-3 h-100 border-start border-success border-4">
                <div class="text-secondary small fw-semibold">Commissioned / Done</div>
                <div class="fs-3 fw-bold text-success mt-1"><?= (int)($stats['completed'] ?? 0) ?></div>
                <div class="small text-muted" style="font-size: 0.75rem;">Installed & verified</div>
            </div>
        </div>
    </div>

    <!-- MAIN DISPATCH FEED: CUSTOMER & DISPATCH DETAILS -->
    <div class="card bg-white border shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="fw-bold text-navy mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-truck-front-fill text-warning"></i> Customer Site Dispatches & Installation Feed
                </h5>
                <small class="text-muted">Live dispatch details, customer location, transported solar kit BOM, and execution controls.</small>
            </div>
            <a href="<?= url('/engineer/installations') ?>" class="btn btn-outline-navy btn-sm fw-semibold">
                View All Records <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="card-body p-3 p-md-4">
            <?php if (empty($dispatches)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-box-seam fs-1 text-secondary opacity-50 d-block mb-2"></i>
                    <h6 class="fw-bold text-navy">No Dispatched Sites Assigned Yet</h6>
                    <p class="small text-secondary mb-0">When managers dispatch equipment and assign your engineer code, site details and 20-item BOM will flash here instantly.</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($dispatches as $d): 
                        $itemsList = !empty($d['items_json']) ? json_decode($d['items_json'], true) : [];
                        $isAcknowledged = (int)($d['customer_acknowledged'] ?? 0) === 1;
                        $customerName = !empty($d['customer_name']) ? $d['customer_name'] : trim(($d['cust_first'] ?? '') . ' ' . ($d['cust_last'] ?? ''));
                        if (empty($customerName)) $customerName = 'Solar Beneficiary';
                        $customerMobile = $d['cust_mobile'] ?? ($d['customer_mobile'] ?? '');
                        $customerAddress = $d['delivery_address'] ?? ($d['cust_address'] ?? 'Odisha Site');
                    ?>
                        <div class="col-12">
                            <div class="card border rounded-3 shadow-sm hover-shadow transition" style="border-left: 5px solid <?= $isAcknowledged ? '#10B981' : '#F59E0B' ?> !important;">
                                <div class="card-body p-3 p-md-4">
                                    <div class="row g-3">
                                        
                                        <!-- COLUMN 1: CUSTOMER & SITE DETAILS -->
                                        <div class="col-lg-4 border-end-lg">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span class="badge bg-light text-navy border font-monospace fw-bold">
                                                    LR: <?= htmlspecialchars($d['tracking_number'] ?? 'LR-SVPL') ?>
                                                </span>
                                                <span class="badge <?= $isAcknowledged ? 'bg-success' : 'bg-warning text-dark' ?> rounded-pill small">
                                                    <i class="bi <?= $isAcknowledged ? 'bi-check-circle-fill' : 'bi-hourglass-split' ?> me-1"></i>
                                                    <?= $isAcknowledged ? 'Customer Acknowledged' : 'Pending Customer Ack' ?>
                                                </span>
                                            </div>

                                            <h5 class="fw-bold text-navy mb-1"><?= htmlspecialchars($customerName) ?></h5>
                                            
                                            <div class="mb-2">
                                                <?php if (!empty($customerMobile)): ?>
                                                    <a href="tel:<?= htmlspecialchars($customerMobile) ?>" class="btn btn-outline-success btn-sm py-0 px-2 fw-bold text-decoration-none">
                                                        <i class="bi bi-telephone-fill me-1"></i> Call Customer (<?= htmlspecialchars($customerMobile) ?>)
                                                    </a>
                                                <?php endif; ?>
                                            </div>

                                            <div class="p-2 rounded bg-light small mb-2">
                                                <div class="text-secondary fw-semibold mb-1"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Installation Site Address:</div>
                                                <div class="text-dark font-monospace" style="font-size: 0.8rem;"><?= nl2br(htmlspecialchars($customerAddress)) ?></div>
                                                <?php if (!empty($d['cust_district'])): ?>
                                                    <div class="mt-1"><span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">Dist: <?= htmlspecialchars($d['cust_district']) ?></span></div>
                                                <?php endif; ?>
                                            </div>

                                            <?php if (!empty($d['discom_name']) || !empty($d['ca_number'])): ?>
                                                <div class="small text-muted">
                                                    <i class="bi bi-lightning-charge-fill text-warning"></i> DISCOM: <strong><?= htmlspecialchars($d['discom_name'] ?? 'TPCODL/TPSODL/TPNODL/TPWODL') ?></strong> 
                                                    <?= !empty($d['ca_number']) ? ('(CA: ' . htmlspecialchars($d['ca_number']) . ')') : '' ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- COLUMN 2: TRANSIT, DRIVER & VENDOR DETAILS -->
                                        <div class="col-lg-4 border-end-lg">
                                            <h6 class="fw-bold text-navy border-bottom pb-2 mb-2 d-flex align-items-center gap-1">
                                                <i class="bi bi-truck text-warning"></i> Logistics & Materials Transit
                                            </h6>

                                            <table class="table table-sm table-borderless small mb-2">
                                                <tr>
                                                    <td class="text-secondary ps-0" style="width: 120px;">Dispatch Date:</td>
                                                    <td class="fw-semibold text-dark"><?= htmlspecialchars($d['dispatch_date'] ?? 'N/A') ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-secondary ps-0">Vehicle Number:</td>
                                                    <td class="fw-bold font-monospace text-primary"><?= htmlspecialchars($d['vehicle_number'] ?: 'Direct Transit') ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-secondary ps-0">Driver / Transporter:</td>
                                                    <td>
                                                        <span class="fw-semibold"><?= htmlspecialchars($d['driver_name'] ?: 'SVPL Driver') ?></span>
                                                        <?php if (!empty($d['driver_mobile'])): ?>
                                                            <a href="tel:<?= htmlspecialchars($d['driver_mobile']) ?>" class="badge bg-success text-white text-decoration-none ms-1">
                                                                <i class="bi bi-telephone"></i> Call
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-secondary ps-0">Vendor / Make:</td>
                                                    <td class="fw-semibold text-navy"><?= htmlspecialchars($d['vendor_name'] ?? 'OEM / SVPL Central Store') ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-secondary ps-0">Current Status:</td>
                                                    <td>
                                                        <span class="badge bg-primary text-white font-monospace"><?= htmlspecialchars($d['status'] ?? 'Dispatched') ?></span>
                                                        <?php if (!empty($d['lead_stage'])): ?>
                                                            <span class="badge bg-light text-dark border"><?= htmlspecialchars($d['lead_stage']) ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </table>

                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm fw-bold w-100" 
                                                    onclick="viewBOMModal(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>)">
                                                    <i class="bi bi-card-checklist me-1"></i> Inspect 20-Item BOM List
                                                </button>
                                            </div>
                                        </div>

                                        <!-- COLUMN 3: ENGINEER ACTIONS & DOCUMENTS -->
                                        <div class="col-lg-4 d-flex flex-column justify-content-between">
                                            <div>
                                                <h6 class="fw-bold text-navy border-bottom pb-2 mb-2 d-flex align-items-center gap-1">
                                                    <i class="bi bi-file-earmark-text-fill text-primary"></i> Site Documents & Invoices
                                                </h6>

                                                <div class="d-grid gap-2 mb-3">
                                                    <a href="<?= url('/print/eway-bill/' . $d['id']) ?>" target="_blank" class="btn btn-outline-warning text-dark btn-sm fw-bold text-start d-flex align-items-center justify-content-between">
                                                        <span><i class="bi bi-truck text-warning me-2"></i> E-Way Bill (EWB-01)</span>
                                                        <i class="bi bi-box-arrow-up-right small"></i>
                                                    </a>
                                                    <a href="<?= url('/print/dispatch-invoice/' . $d['id']) ?>" target="_blank" class="btn btn-outline-navy btn-sm fw-bold text-start d-flex align-items-center justify-content-between">
                                                        <span><i class="bi bi-receipt text-primary me-2"></i> GST Equipment Tax Invoice</span>
                                                        <i class="bi bi-box-arrow-up-right small"></i>
                                                    </a>
                                                    <a href="<?= url('/print/dispatch-challan/' . $d['id']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm fw-bold text-start d-flex align-items-center justify-content-between">
                                                        <span><i class="bi bi-card-checklist text-secondary me-2"></i> Delivery Challan & BOM</span>
                                                        <i class="bi bi-box-arrow-up-right small"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="pt-2 border-top">
                                                <button type="button" class="btn btn-svpl-solar btn-sm fw-bold w-100 py-2 shadow-sm d-flex align-items-center justify-content-center gap-2"
                                                    onclick="openStageUpdateModal(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>)">
                                                    <i class="bi bi-gear-wide-connected fs-6"></i> Update Installation Stage
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- ==========================================
     MODAL: VIEW 20-ITEM BOM FOR ENGINEER
========================================== -->
<div class="modal fade" id="modalEngineerBOM" tabindex="-1" aria-labelledby="modalEngineerBOMLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-navy text-white py-3">
                <div>
                    <h5 class="modal-title font-heading fw-bold d-flex align-items-center gap-2" id="modalEngineerBOMLabel">
                        <i class="bi bi-card-checklist text-warning"></i> Site Solar Equipment Bill of Materials (BOM)
                    </h5>
                    <small class="text-white-50" id="bomModalSubtitle">Inspection & Physical Material Verification</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 p-md-4">
                
                <div class="alert alert-info py-2 px-3 small d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <i class="bi bi-shield-check text-primary me-1"></i> Certified engineers must physically verify these 20 standard components before rooftop mounting.
                    </div>
                    <div id="bomDocLinks" class="d-flex gap-2"></div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-dark small text-uppercase">
                            <tr>
                                <th style="width: 50px;" class="text-center">S.No</th>
                                <th style="min-width: 200px;">Item Description</th>
                                <th>Technical Specification / Make</th>
                                <th style="width: 120px;" class="text-center">Quantity</th>
                                <th style="width: 80px;" class="text-center">Unit</th>
                            </tr>
                        </thead>
                        <tbody id="bomModalTableBody" class="small">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     MODAL: STAGE PROGRESS UPDATE BY ENGINEER
========================================== -->
<div class="modal fade" id="modalStageUpdate" tabindex="-1" aria-labelledby="modalStageUpdateLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white py-3">
                <h5 class="modal-title font-heading fw-bold d-flex align-items-center gap-2" id="modalStageUpdateLabel">
                    <i class="bi bi-tools text-warning"></i> Record Site Installation Progress
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= url('/engineer/update-stage') ?>">
                <input type="hidden" name="dispatch_id" id="update_dispatch_id">
                <input type="hidden" name="lead_id" id="update_lead_id">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Customer Site</label>
                        <input type="text" id="update_customer_name" class="form-control bg-light fw-bold text-navy" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Dispatch Logistics Status</label>
                        <select name="dispatch_status" id="update_dispatch_status" class="form-select fw-semibold">
                            <option value="Dispatched">Dispatched from Central Store</option>
                            <option value="In Transit">In Transit / On the Road</option>
                            <option value="Out for Delivery">Out for Delivery (Reaching Site)</option>
                            <option value="Delivered">Delivered & Safely Received at Site</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Solar Execution Stage</label>
                        <select name="stage" id="update_stage" class="form-select fw-semibold border-2 border-primary-subtle">
                            <option value="INSTALLATION_COMMENCED">Stage 7: Installation Commenced (Structure & Inverter Setup)</option>
                            <option value="INSTALLATION_COMPLETED">Stage 8: Installation Completed & Ready for Inspection</option>
                            <option value="JE_REPORT">Stage 9: DISCOM JE Inspection & Net-Meter Setup</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-navy">Engineer Site Log / Technical Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="e.g. 8 panels mounted, earthing done, inverter connected. Ready for DISCOM inspection."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-solar fw-bold px-4">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Progress
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const defaultBOM = [
    { s_no: 1, name: 'Solar PV Modules', spec: 'Mono PERC / TopCon Half-Cut Tier-1 (540Wp - 580Wp)', qty: '6', unit: 'Nos' },
    { s_no: 2, name: 'Solar Grid-Tie Inverter', spec: 'Single Phase / 3-Phase MPPT Inverter with WiFi & Cloud Monitoring', qty: '1', unit: 'Set' },
    { s_no: 3, name: 'Module Mounting Structure (MMS)', spec: 'Hot Dip Galvanized (HDG) / Aluminium Elevated Rooftop Structure', qty: '1', unit: 'Set' },
    { s_no: 4, name: 'AC Distribution Box (ACDB)', spec: 'IP65 Enclosure with MCB, SPD (Type-II) & Energy Meter Provision', qty: '1', unit: 'Set' },
    { s_no: 5, name: 'DC Distribution Box (DCDB)', spec: 'IP65 Enclosure with 1000V DC Fuses, Isolator & Type-II SPD', qty: '1', unit: 'Set' },
    { s_no: 6, name: 'Solar DC Cable (Red & Black)', spec: '4 sq.mm / 6 sq.mm Electron Beam Cross-Linked XLPO Copper Cable', qty: '30', unit: 'Mtr' },
    { s_no: 7, name: 'AC Armoured Power Cable', spec: '4 sq.mm / 6 sq.mm 3-Core / 4-Core XLPE Aluminium/Copper Cable', qty: '20', unit: 'Mtr' },
    { s_no: 8, name: 'Earthing Electrodes / Chemical Rods', spec: 'Copper Bonded Heavy Duty 14mm/17mm x 2 Mtr Earthing Rods', qty: '3', unit: 'Sets' },
    { s_no: 9, name: 'B&C Chemical Earthing Compound', spec: 'Eco-Friendly Low Resistance Carbon/Bentonite Chemical Compound', qty: '3', unit: 'Bags' },
    { s_no: 10, name: 'Copper / GI Earthing Flat Strip', spec: '25x3 mm Galvanized Iron (GI) or Pure Copper Earthing Strip', qty: '25', unit: 'Mtr' },
    { s_no: 11, name: 'Lightning Arrester (LA)', spec: 'Early Streamer Emission (ESE) / Conventional Pure Copper 5-Prong LA', qty: '1', unit: 'Set' },
    { s_no: 12, name: 'MC4 Connectors (Male & Female)', spec: '1000V DC IP68 Waterproof UV-Resistant Snap-in Connectors', qty: '4', unit: 'Pairs' },
    { s_no: 13, name: 'Flexible PVC Conduit / Casing Pipes', spec: 'Heavy Gauge 25mm ISI Mark PVC Corrugated Flexible Conduits', qty: '15', unit: 'Mtr' },
    { s_no: 14, name: 'Anchor Fasteners & SS Hardware', spec: 'SS 304 Grade M10/M12 Anchor Fasteners, Bolts, Washers & Spring Nuts', qty: '1', unit: 'Kit' },
    { s_no: 15, name: 'Mid Clamps & End Clamps', spec: 'Anodized Aluminium Alloy 6063-T6 Clamps with SS304 Allen Bolts', qty: '12', unit: 'Nos' },
    { s_no: 16, name: 'Fire Extinguisher & Safety Signages', spec: 'ABC Type Dry Powder 2KG Extinguisher & Danger Solar Signage Plates', qty: '1', unit: 'Set' },
    { s_no: 17, name: 'WiFi / 4G Remote Monitoring Dongle', spec: 'Smart Data Logger for Real-Time Mobile App Generation Tracking', qty: '1', unit: 'No' },
    { s_no: 18, name: 'Bi-Directional Net Meter Enclosure Box', spec: 'Polycarbonate Transparent Tamper-Proof Discom Standard Meter Box', qty: '1', unit: 'Set' },
    { s_no: 19, name: 'Cable Ties, Lugs, Ferrules & Tapes', spec: 'UV Resistant 300mm Cable Ties, Copper Crimping Lugs, PVC Heat Shrink', qty: '1', unit: 'Kit' },
    { s_no: 20, name: 'Solar Danger Board & Warning Notices', spec: 'Reflective Acrylic Hindi/Odia/English Warning and Schematic Board', qty: '1', unit: 'Set' }
];

function viewBOMModal(dispatch) {
    const modal = new bootstrap.Modal(document.getElementById('modalEngineerBOM'));
    const tableBody = document.getElementById('bomModalTableBody');
    const subtitle = document.getElementById('bomModalSubtitle');
    const docLinks = document.getElementById('bomDocLinks');

    const custName = dispatch.customer_name || ((dispatch.cust_first || '') + ' ' + (dispatch.cust_last || ''));
    subtitle.innerHTML = `Customer: <strong>${custName}</strong> | LR: <span class="font-monospace">${dispatch.tracking_number || 'N/A'}</span>`;

    docLinks.innerHTML = `
        <a href="<?= url('/print/eway-bill/') ?>${dispatch.id}" target="_blank" class="btn btn-sm btn-warning text-dark fw-bold py-0"><i class="bi bi-truck me-1"></i> E-Way Bill</a>
        <a href="<?= url('/print/dispatch-invoice/') ?>${dispatch.id}" target="_blank" class="btn btn-sm btn-navy text-white fw-bold py-0"><i class="bi bi-receipt me-1"></i> GST Invoice</a>
    `;

    let items = [];
    if (dispatch.items_json) {
        try {
            items = JSON.parse(dispatch.items_json);
        } catch(e) {
            items = defaultBOM;
        }
    } else {
        items = defaultBOM;
    }

    let rowsHtml = '';
    items.forEach((item, index) => {
        rowsHtml += `
            <tr>
                <td class="text-center text-muted fw-bold">${item.s_no || (index + 1)}</td>
                <td class="fw-bold text-navy">${item.name || 'Component'}</td>
                <td class="text-secondary">${item.spec || 'Standard Specification'}</td>
                <td class="text-center font-monospace fw-bold text-primary">${item.qty || 1}</td>
                <td class="text-center"><span class="badge bg-light text-dark border">${item.unit || 'Nos'}</span></td>
            </tr>
        `;
    });

    tableBody.innerHTML = rowsHtml;
    modal.show();
}

function openStageUpdateModal(dispatch) {
    const modal = new bootstrap.Modal(document.getElementById('modalStageUpdate'));
    const custName = dispatch.customer_name || ((dispatch.cust_first || '') + ' ' + (dispatch.cust_last || ''));
    
    document.getElementById('update_dispatch_id').value = dispatch.id || '';
    document.getElementById('update_lead_id').value = dispatch.lead_id || '';
    document.getElementById('update_customer_name').value = `${custName} (LR: ${dispatch.tracking_number || ''})`;
    document.getElementById('update_dispatch_status').value = dispatch.status || 'Dispatched';
    
    if (dispatch.lead_stage && ['INSTALLATION_COMMENCED', 'INSTALLATION_COMPLETED', 'JE_REPORT'].includes(dispatch.lead_stage)) {
        document.getElementById('update_stage').value = dispatch.lead_stage;
    }

    modal.show();
}
</script>
