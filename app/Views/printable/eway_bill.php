<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Official Electronic Way Bill (E-Way Bill EWB-01) — Solar Power System Transit Manifest
 */

$companyName = "SURYA VISTAARA PRIVATE LIMITED";
$companyGstin = "21AAKCS8912K1Z9";
$companyState = "21 - ODISHA";
$companyAddress = "Plot No. 102/B, Sector-A, Mancheswar Industrial Estate, Bhubaneswar, Khordha, Odisha - 751010";
$companyPhone = "+91 674-2987654 / +91 94370 12345";
$companyEmail = "logistics@suryavistaara.com";

$ewbNo = $ewbNumber ?? ('2118' . str_pad((string)($dispatch['id'] ?? 1), 8, '0', STR_PAD_LEFT));
$ewbDate = !empty($dispatch['dispatch_date']) ? date('d/m/Y 10:30 AM', strtotime($dispatch['dispatch_date'])) : date('d/m/Y h:i A');
$validUntil = !empty($dispatch['dispatch_date']) ? date('d/m/Y 11:59 PM', strtotime($dispatch['dispatch_date'] . ' + 3 days')) : date('d/m/Y 11:59 PM', strtotime('+3 days'));

$custName = trim(($dispatch['cust_first'] ?? '') . ' ' . ($dispatch['cust_last'] ?? ''));
if (empty($custName)) $custName = $dispatch['customer_name'] ?? 'Rooftop Beneficiary';

$deliveryAddr = $dispatch['delivery_address'] ?? $dispatch['cust_address'] ?? 'Odisha';
$custMobile = $dispatch['cust_mobile'] ?? '';
$custDist = $dispatch['cust_dist'] ?? 'Khordha';
$custPin = $dispatch['cust_pin'] ?? '751001';
$discomCa = $dispatch['cust_ca'] ?? '';
$discomName = $dispatch['cust_discom'] ?? 'TPCODL';

$docNo = 'SVPL/INV/' . date('Y') . '/' . str_pad((string)($dispatch['id'] ?? 1), 4, '0', STR_PAD_LEFT);
$docDate = !empty($dispatch['dispatch_date']) ? date('d/m/Y', strtotime($dispatch['dispatch_date'])) : date('d/m/Y');

// HSN Mapping
$hsnMap = [
    'Solar PV Modules'                => '85414300',
    'Solar Inverter'                  => '85044090',
    'Module Mounting Structure (MMS)' => '73089000',
    'Mid Clamps'                      => '76109000',
    'End Clamps'                      => '76109000',
    'Anchor Fasteners'                => '73181500',
    'Hardware Fasteners Set'          => '73181500',
    'DC Distribution Box (DCDB)'      => '85371000',
    'AC Distribution Box (ACDB)'      => '85371000',
    'DC Solar Cable (Red)'            => '85444990',
    'DC Solar Cable (Black)'          => '85444990',
    'AC Output Cable'                 => '85444990',
    'MC4 Connectors'                  => '85366990',
    'PVC Conduit Pipes & Fittings'    => '39172190',
    'Earthing Chemical Electrodes'    => '85359090',
    'Earthing Compound'               => '38249990',
    'Earthing Strip / Wire'           => '72123090',
    'Lightning Arrester (LA)'         => '85354030',
    'Cable Ties & Accessories'        => '39269099',
    'Warning Labels & Tags'           => '39199090'
];

$defaultRates = [
    1  => 16500, // Panels each
    2  => 32000, // Inverter
    3  => 4500,  // MMS set
    4  => 120,   // Mid Clamps
    5  => 120,   // End Clamps
    6  => 45,    // Fasteners
    7  => 850,   // Lot
    8  => 2800,  // DCDB
    9  => 2800,  // ACDB
    10 => 45,    // DC Red/m
    11 => 45,    // DC Black/m
    12 => 95,    // AC Cable/m
    13 => 150,   // MC4
    14 => 180,   // Pipes
    15 => 1400,  // Electrodes
    16 => 750,   // Compound
    17 => 65,    // Strip
    18 => 2200,  // LA
    19 => 350,   // Ties
    20 => 250    // Tags
];

$totalTaxable = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Way Bill — <?= htmlspecialchars($ewbNo) ?> — SVPL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #0f172a;
            font-size: 13px;
        }
        .ewb-container {
            max-width: 860px;
            margin: 25px auto;
            background: #ffffff;
            border: 2px solid #0f2d59;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .ewb-header {
            background: #0f2d59;
            color: #ffffff;
            padding: 14px 20px;
            border-bottom: 3px solid #f59e0b;
        }
        .section-bar {
            background: #e2e8f0;
            color: #0f2d59;
            font-weight: 700;
            padding: 6px 12px;
            font-size: 12px;
            text-transform: uppercase;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
        }
        .table-ewb {
            width: 100%;
            margin-bottom: 0;
            font-size: 12px;
        }
        .table-ewb th {
            background-color: #f8fafc;
            color: #334155;
            font-weight: 600;
            border-bottom: 1px solid #cbd5e1;
            padding: 6px 8px;
        }
        .table-ewb td {
            padding: 6px 8px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .meta-label {
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .meta-val {
            font-weight: 700;
            color: #0f172a;
        }
        .barcode-box {
            font-family: 'Courier New', monospace;
            letter-spacing: 5px;
            font-weight: 900;
            font-size: 1.25rem;
            color: #0f2d59;
            background: #f8fafc;
            padding: 4px 10px;
            border: 1px dashed #94a3b8;
            display: inline-block;
        }
        @media print {
            body { background: #ffffff; padding: 0; margin: 0; }
            .ewb-container { border: 1.5px solid #000; box-shadow: none; margin: 0; max-width: 100%; width: 100%; }
            .no-print { display: none !important; }
            .ewb-header { background: #0f2d59 !important; -webkit-print-color-adjust: exact; color: #ffffff !important; }
            .section-bar { background: #e2e8f0 !important; -webkit-print-color-adjust: exact; }
            .table-ewb th { background: #f8fafc !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<!-- TOP ACTION TOOLBAR (HIDDEN ON PRINT) -->
<div class="container-fluid no-print py-3 border-bottom bg-white sticky-top shadow-sm">
    <div class="max-w-860 mx-auto d-flex justify-content-between align-items-center flex-wrap gap-2" style="max-width: 860px;">
        <div class="d-flex align-items-center gap-2">
            <a href="javascript:window.close()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Close
            </a>
            <span class="badge bg-navy text-white px-3 py-2">E-Way Bill EWB-01</span>
            <span class="text-muted small">Dispatch #<?= htmlspecialchars($dispatch['tracking_number'] ?? $dispatch['id']) ?></span>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url('/print/dispatch-invoice/' . $dispatch['id']) ?>" class="btn btn-outline-primary btn-sm fw-bold">
                <i class="bi bi-receipt-cutoff me-1"></i> View GST Tax Invoice
            </a>
            <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm" onclick="window.print()">
                <i class="bi bi-printer-fill me-1"></i> Print / Download E-Way Bill (PDF)
            </button>
        </div>
    </div>
</div>

<div class="ewb-container">
    
    <!-- HEADER -->
    <div class="ewb-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0 fw-bold font-heading text-white">e-Way Bill System</h4>
                <div class="small text-warning fw-bold">Government of India / Government of Odisha Goods and Services Tax</div>
                <div class="small text-white-50">Rule 138 of the Central Goods and Services Tax Rules, 2017</div>
            </div>
            <div class="text-end">
                <div class="badge bg-warning text-dark fs-6 px-3 py-1 fw-bold">FORM GST EWB-01</div>
                <div class="text-white small mt-1 font-monospace">EWB GENERATED</div>
            </div>
        </div>
    </div>

    <!-- E-WAY BILL META -->
    <div class="p-3 border-bottom bg-white">
        <div class="row g-2 align-items-center">
            <div class="col-sm-8">
                <div class="d-flex align-items-baseline gap-2 mb-1">
                    <span class="meta-label">e-Way Bill No:</span>
                    <span class="barcode-box"><?= chunk_split($ewbNo, 4, ' ') ?></span>
                </div>
                <div class="row g-2 small mt-1">
                    <div class="col-6">
                        <span class="meta-label">Generated Date:</span>
                        <div class="meta-val"><?= $ewbDate ?></div>
                    </div>
                    <div class="col-6">
                        <span class="meta-label">Valid Until:</span>
                        <div class="meta-val text-danger"><?= $validUntil ?> (3 Days)</div>
                    </div>
                    <div class="col-6">
                        <span class="meta-label">Generated By:</span>
                        <div class="meta-val"><?= $companyGstin ?> (<?= $companyName ?>)</div>
                    </div>
                    <div class="col-6">
                        <span class="meta-label">Supply Type:</span>
                        <div class="meta-val text-primary">Outward / Solar Installation & BOS</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 text-center text-sm-end">
                <img src="<?= $qrUrl ?>" alt="E-Way Bill QR Code" style="width: 100px; height: 100px; border: 1px solid #cbd5e1; padding: 3px; background: #ffffff;">
                <div class="text-muted" style="font-size: 10px;">Scan to Verify Manifest</div>
            </div>
        </div>
    </div>

    <!-- PART A: TRANSACTION & ADDRESS DETAILS -->
    <div class="section-bar">
        <i class="bi bi-card-checklist me-1"></i> PART-A: Transaction & Party Details
    </div>
    
    <div class="p-3 border-bottom bg-white">
        <div class="row g-3">
            <div class="col-6 border-end pe-3">
                <div class="fw-bold text-navy mb-2 pb-1 border-bottom d-flex justify-content-between">
                    <span>1. FROM (CONSIGNOR / SUPPLIER)</span>
                    <span class="badge bg-light text-dark border">OD-21</span>
                </div>
                <div class="small">
                    <div class="meta-label">GSTIN & Name:</div>
                    <div class="fw-bold text-dark"><?= $companyGstin ?> — <?= $companyName ?></div>
                    <div class="meta-label mt-1">Dispatch Origin:</div>
                    <div class="text-secondary"><?= $companyAddress ?></div>
                    <div class="meta-label mt-1">Authorized Vendor / OEM:</div>
                    <div class="fw-semibold text-primary"><?= htmlspecialchars($dispatch['vendor_name'] ?? 'Tata Power Solar / Tier-1 OEMs') ?></div>
                </div>
            </div>
            <div class="col-6 ps-3">
                <div class="fw-bold text-navy mb-2 pb-1 border-bottom d-flex justify-content-between">
                    <span>2. TO (CONSIGNEE / BENEFICIARY)</span>
                    <span class="badge bg-light text-dark border">OD-21</span>
                </div>
                <div class="small">
                    <div class="meta-label">GSTIN / Status:</div>
                    <div class="fw-bold text-dark">URP (Consumer / Solar Beneficiary)</div>
                    <div class="meta-label mt-1">Beneficiary Name & CA:</div>
                    <div class="fw-bold text-dark"><?= htmlspecialchars($custName) ?> (<?= htmlspecialchars($discomCa ?: 'CA Pending') ?>)</div>
                    <div class="meta-label mt-1">Delivery Destination:</div>
                    <div class="text-secondary"><?= htmlspecialchars($deliveryAddr) ?></div>
                    <div class="text-secondary">Dist: <?= htmlspecialchars($custDist) ?>, PIN: <?= htmlspecialchars($custPin) ?>, State: Odisha (21)</div>
                    <div class="text-dark"><strong>Mobile:</strong> <?= htmlspecialchars($custMobile ?: 'N/A') ?></div>
                </div>
            </div>
        </div>

        <div class="row g-2 mt-2 pt-2 border-top small">
            <div class="col-3">
                <span class="meta-label">Document Type:</span>
                <div class="meta-val">Tax Invoice & Delivery Manifest</div>
            </div>
            <div class="col-3">
                <span class="meta-label">Document No:</span>
                <div class="meta-val font-monospace text-primary"><?= htmlspecialchars($docNo) ?></div>
            </div>
            <div class="col-3">
                <span class="meta-label">Document Date:</span>
                <div class="meta-val"><?= $docDate ?></div>
            </div>
            <div class="col-3">
                <span class="meta-label">Transaction Type:</span>
                <div class="meta-val">Regular / Direct Delivery</div>
            </div>
        </div>
    </div>

    <!-- PRODUCT LIST / GOODS DESCRIPTION -->
    <div class="section-bar d-flex justify-content-between align-items-center">
        <span><i class="bi bi-boxes me-1"></i> Goods & Equipment Bill of Materials (BOM)</span>
        <span class="badge bg-white text-dark"><?= count($items) ?> Line Items</span>
    </div>

    <div class="table-responsive">
        <table class="table-ewb">
            <thead>
                <tr>
                    <th style="width: 35px;" class="text-center">#</th>
                    <th>Product Description & Technical Specifications</th>
                    <th class="text-center" style="width: 90px;">HSN Code</th>
                    <th class="text-center" style="width: 70px;">Qty</th>
                    <th class="text-center" style="width: 70px;">Unit</th>
                    <th class="text-end" style="width: 100px;">Taxable (₹)</th>
                    <th class="text-center" style="width: 80px;">GST Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sIdx = 1;
                foreach ($items as $idx => $it): 
                    $itemName = trim($it['item'] ?? '');
                    $itemSpec = trim($it['spec'] ?? '');
                    $qty = (float)($it['qty'] ?? 1);
                    $unit = trim($it['unit'] ?? 'Nos.');
                    $numKey = (int)($it['item_no'] ?? $idx);
                    
                    $hsn = $hsnMap[$itemName] ?? '85414300';
                    $unitRate = $defaultRates[$numKey] ?? ($defaultRates[$sIdx] ?? 1000);
                    $lineTaxable = round($unitRate * $qty, 2);
                    $totalTaxable += $lineTaxable;
                ?>
                    <tr>
                        <td class="text-center text-muted fw-bold"><?= $sIdx++ ?></td>
                        <td>
                            <strong class="text-navy"><?= htmlspecialchars($itemName) ?></strong>
                            <?php if (!empty($itemSpec)): ?>
                                <div class="text-secondary" style="font-size: 11px;"><?= htmlspecialchars($itemSpec) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="text-center font-monospace text-secondary"><?= $hsn ?></td>
                        <td class="text-center fw-bold text-primary font-monospace"><?= $qty ?></td>
                        <td class="text-center text-muted"><?= htmlspecialchars($unit) ?></td>
                        <td class="text-end font-monospace"><?= number_format($lineTaxable, 2) ?></td>
                        <td class="text-center text-muted">12%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <?php 
                $cgst = round($totalTaxable * 0.06, 2);
                $sgst = round($totalTaxable * 0.06, 2);
                $grandTotal = $totalTaxable + $cgst + $sgst;
                ?>
                <tr style="background: #f8fafc; font-weight: bold;">
                    <td colspan="5" class="text-end">Total Taxable Amount:</td>
                    <td class="text-end font-monospace text-dark">₹ <?= number_format($totalTaxable, 2) ?></td>
                    <td></td>
                </tr>
                <tr style="background: #f8fafc; font-weight: bold;">
                    <td colspan="5" class="text-end">CGST (6%) + SGST (6%):</td>
                    <td class="text-end font-monospace text-dark">₹ <?= number_format($cgst + $sgst, 2) ?></td>
                    <td></td>
                </tr>
                <tr style="background: #0f2d59; color: #ffffff; font-weight: bold;">
                    <td colspan="5" class="text-end text-white">Total Consignment Value (INR):</td>
                    <td class="text-end font-monospace text-warning fs-6">₹ <?= number_format($grandTotal, 2) ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- PART B: VEHICLE & TRANSPORTER DETAILS -->
    <div class="section-bar">
        <i class="bi bi-truck me-1"></i> PART-B: Transportation & Vehicle Details
    </div>

    <div class="p-3 bg-white">
        <div class="row g-2 small">
            <div class="col-sm-4">
                <span class="meta-label">Mode of Transport:</span>
                <div class="meta-val"><i class="bi bi-truck text-warning me-1"></i> Road</div>
            </div>
            <div class="col-sm-4">
                <span class="meta-label">Vehicle Registration No:</span>
                <div class="meta-val font-monospace text-primary fs-6"><?= htmlspecialchars($dispatch['vehicle_number'] ?? 'OD-02-AX-8912') ?></div>
            </div>
            <div class="col-sm-4">
                <span class="meta-label">Approx Distance (KM):</span>
                <div class="meta-val">120 Kms (Within Odisha)</div>
            </div>
            <div class="col-sm-4">
                <span class="meta-label">Driver / Person in Vehicle:</span>
                <div class="meta-val"><?= htmlspecialchars($dispatch['driver_name'] ?? 'Authorized Transporter') ?></div>
            </div>
            <div class="col-sm-4">
                <span class="meta-label">Driver Mobile:</span>
                <div class="meta-val font-monospace"><?= htmlspecialchars($dispatch['driver_mobile'] ?? 'N/A') ?></div>
            </div>
            <div class="col-sm-4">
                <span class="meta-label">Transporter / Courier Desk:</span>
                <div class="meta-val"><?= htmlspecialchars($dispatch['courier_partner'] ?? 'SVPL Logistics Fleet') ?></div>
            </div>
            <div class="col-sm-6">
                <span class="meta-label">LR / Challan Tracking No:</span>
                <div class="meta-val font-monospace"><?= htmlspecialchars($dispatch['tracking_number'] ?? 'N/A') ?></div>
            </div>
            <div class="col-sm-6">
                <span class="meta-label">Gate Pass / Remarks:</span>
                <div class="meta-val text-muted"><?= htmlspecialchars($dispatch['remarks'] ?: 'Solar PV kit with safety packaging. Fragile glass modules.') ?></div>
            </div>
        </div>
    </div>

    <!-- FOOTER / SIGNATURES -->
    <div class="p-3 border-top bg-light text-muted small" style="font-size: 11px;">
        <div class="row align-items-center">
            <div class="col-8">
                <div>* This is a computer generated document authenticated under the GST E-Way Bill provisions of the Central Goods and Services Tax Rules, 2017.</div>
                <div>* Valid for transit of Solar PV modules, inverter, structure and electrical BOS instruments to beneficiary site in Odisha.</div>
            </div>
            <div class="col-4 text-end">
                <div class="fw-bold text-navy"><?= $companyName ?></div>
                <div style="margin-top: 25px; border-top: 1px dashed #94a3b8; display: inline-block; padding-top: 2px;">
                    Authorized Signatory / Dispatch Officer
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
