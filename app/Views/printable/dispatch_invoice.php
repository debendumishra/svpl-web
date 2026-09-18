<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Official GST Tax Invoice — Solar Rooftop Equipment & 20-Point BOS Bill of Materials
 */

$companyName = "SURYA VISTAARA PRIVATE LIMITED";
$companyCin = "U40106OR2024PTC045123";
$companyGstin = "21AAKCS8912K1Z9";
$companyPan = "AAKCS8912K";
$companyState = "21 - ODISHA";
$companyAddress = "Plot No. 102/B, Sector-A, Mancheswar Industrial Estate, Bhubaneswar, Khordha, Odisha - 751010";
$companyPhone = "+91 674-2987654 / +91 94370 12345";
$companyEmail = "accounts@suryavistaara.com / billing@suryavistaara.com";
$companyWeb = "www.suryavistaara.com";

$invNo = $invoiceNumber ?? ('SVPL/INV/' . date('Y') . '/' . str_pad((string)($dispatch['id'] ?? 1), 4, '0', STR_PAD_LEFT));
$invDate = !empty($dispatch['dispatch_date']) ? date('d/m/Y', strtotime($dispatch['dispatch_date'])) : date('d/m/Y');

$custName = trim(($dispatch['cust_first'] ?? '') . ' ' . ($dispatch['cust_last'] ?? ''));
if (empty($custName)) $custName = $dispatch['customer_name'] ?? 'Solar Rooftop Beneficiary';

$deliveryAddr = $dispatch['delivery_address'] ?? $dispatch['cust_address'] ?? 'Odisha';
$custMobile = $dispatch['cust_mobile'] ?? '';
$custDist = $dispatch['cust_dist'] ?? 'Khordha';
$custPin = $dispatch['cust_pin'] ?? '751001';
$discomCa = $dispatch['cust_ca'] ?? '';
$discomName = $dispatch['cust_discom'] ?? 'TPCODL';
$pkgBrand = $dispatch['package_brand'] ?? 'Tata Power Solar / Authorized OEM';
$pkgCap = $dispatch['proposed_capacity_kw'] ?? $dispatch['pkg_cap'] ?? '3.0';

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
    1  => 16500, // Panels
    2  => 32000, // Inverter
    3  => 4500,  // MMS
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

function numberToWords(float $number): string {
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? " and " . ($words[$decimal / 10 * 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise . " Only";
}

$totalTaxable = 0;
$totalCgst = 0;
$totalSgst = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GST Tax Invoice — <?= htmlspecialchars($invNo) ?> — SVPL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #0f172a;
            font-size: 12px;
        }
        .invoice-container {
            max-width: 900px;
            margin: 25px auto;
            background: #ffffff;
            border: 2px solid #0f2d59;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            position: relative;
        }
        .invoice-header {
            background: linear-gradient(135deg, #061528 0%, #0f2d59 100%);
            color: #ffffff;
            padding: 20px 25px;
            border-bottom: 3px solid #f59e0b;
        }
        .section-heading {
            background: #f1f5f9;
            color: #0f2d59;
            font-weight: 700;
            padding: 5px 10px;
            font-size: 11px;
            text-transform: uppercase;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
        }
        .table-invoice {
            width: 100%;
            margin-bottom: 0;
            font-size: 11.5px;
        }
        .table-invoice th {
            background-color: #0f2d59;
            color: #ffffff;
            font-weight: 600;
            padding: 6px 8px;
            text-transform: uppercase;
            font-size: 10.5px;
            border-color: #1e3a8a;
        }
        .table-invoice td {
            padding: 5px 8px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .meta-label {
            color: #64748b;
            font-size: 10.5px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .meta-val {
            font-weight: 700;
            color: #0f172a;
        }
        .bank-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px;
            font-size: 11px;
        }
        .stamp-box {
            border: 1.5px dashed #0f2d59;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
            background: #f8fafc;
            min-height: 90px;
        }
        @media print {
            body { background: #ffffff; padding: 0; margin: 0; }
            .invoice-container { border: 1px solid #000; box-shadow: none; margin: 0; max-width: 100%; width: 100%; }
            .no-print { display: none !important; }
            .invoice-header { background: #0f2d59 !important; -webkit-print-color-adjust: exact; color: #ffffff !important; }
            .table-invoice th { background-color: #0f2d59 !important; -webkit-print-color-adjust: exact; color: #ffffff !important; }
            .section-heading { background: #f1f5f9 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<!-- TOP ACTIONS TOOLBAR (HIDDEN IN PRINT) -->
<div class="container-fluid no-print py-3 border-bottom bg-white sticky-top shadow-sm">
    <div class="max-w-900 mx-auto d-flex justify-content-between align-items-center flex-wrap gap-2" style="max-width: 900px;">
        <div class="d-flex align-items-center gap-2">
            <a href="javascript:window.close()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Close
            </a>
            <span class="badge bg-navy text-white px-3 py-2">Tax Invoice (Rule 46)</span>
            <span class="text-muted small"><?= htmlspecialchars($invNo) ?></span>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url('/print/eway-bill/' . $dispatch['id']) ?>" class="btn btn-outline-warning btn-sm text-dark fw-bold">
                <i class="bi bi-truck me-1"></i> View E-Way Bill
            </a>
            <a href="<?= url('/print/dispatch-challan/' . $dispatch['id']) ?>" class="btn btn-outline-info btn-sm text-dark fw-bold">
                <i class="bi bi-file-earmark-text me-1"></i> Delivery Challan
            </a>
            <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm" onclick="window.print()">
                <i class="bi bi-printer-fill me-1"></i> Print / Download GST Invoice (PDF)
            </button>
        </div>
    </div>
</div>

<div class="invoice-container">
    
    <!-- CORPORATE HEADER -->
    <div class="invoice-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <span style="background: #f59e0b; color: #061528; width: 36px; height: 36px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1.2rem;">
                        <i class="bi bi-sun-fill"></i>
                    </span>
                    <div>
                        <h4 class="mb-0 fw-bold font-heading text-white tracking-wide"><?= $companyName ?></h4>
                        <div class="small text-warning" style="font-size: 11px;">Empowering Odisha with Clean Solar Energy | PM Surya Ghar Muft Bijli Yojana</div>
                    </div>
                </div>
                <div class="text-white-50 small mt-1" style="font-size: 10.5px;">
                    <?= $companyAddress ?> | CIN: <?= $companyCin ?>
                </div>
            </div>
            <div class="text-end">
                <span class="badge bg-warning text-dark fs-6 px-3 py-1 fw-bold">TAX INVOICE</span>
                <div class="text-white-50 small mt-1">ORIGINAL FOR RECIPIENT</div>
                <div class="text-white small font-monospace">State: 21 (Odisha)</div>
            </div>
        </div>
    </div>

    <!-- INVOICE META & QR -->
    <div class="p-3 border-bottom bg-white">
        <div class="row g-2 align-items-center">
            <div class="col-8">
                <div class="row g-2 small">
                    <div class="col-4">
                        <span class="meta-label">Invoice Number:</span>
                        <div class="meta-val text-primary font-monospace fs-6"><?= htmlspecialchars($invNo) ?></div>
                    </div>
                    <div class="col-4">
                        <span class="meta-label">Invoice Date:</span>
                        <div class="meta-val"><?= $invDate ?></div>
                    </div>
                    <div class="col-4">
                        <span class="meta-label">Place of Supply:</span>
                        <div class="meta-val"><?= $companyState ?></div>
                    </div>
                    <div class="col-4">
                        <span class="meta-label">Supplier GSTIN:</span>
                        <div class="meta-val font-monospace"><?= $companyGstin ?></div>
                    </div>
                    <div class="col-4">
                        <span class="meta-label">Supplier PAN:</span>
                        <div class="meta-val font-monospace"><?= $companyPan ?></div>
                    </div>
                    <div class="col-4">
                        <span class="meta-label">E-Way Bill No:</span>
                        <div class="meta-val font-monospace text-warning-emphasis">2118<?= str_pad((string)$dispatch['id'], 8, '0', STR_PAD_LEFT) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-4 text-end">
                <div class="d-inline-flex align-items-center gap-2">
                    <div class="text-end small">
                        <span class="meta-label">Challan / LR:</span>
                        <div class="fw-bold font-monospace"><?= htmlspecialchars($dispatch['tracking_number'] ?? 'N/A') ?></div>
                        <span class="meta-label mt-1">Vehicle No:</span>
                        <div class="fw-bold text-primary font-monospace"><?= htmlspecialchars($dispatch['vehicle_number'] ?? 'OD-02-AX-8912') ?></div>
                    </div>
                    <img src="<?= $qrUrl ?>" alt="GST Invoice QR Code" style="width: 85px; height: 85px; border: 1px solid #cbd5e1; padding: 2px;">
                </div>
            </div>
        </div>
    </div>

    <!-- BILLED TO & SHIPPED TO -->
    <div class="section-heading">
        <i class="bi bi-geo-alt-fill me-1 text-warning"></i> Customer Billing & Dispatch Details
    </div>
    
    <div class="p-3 border-bottom bg-white">
        <div class="row g-3">
            <div class="col-6 border-end pe-3">
                <div class="fw-bold text-navy mb-1 pb-1 border-bottom d-flex justify-content-between">
                    <span>BILLED TO (BUYER)</span>
                    <span class="badge bg-light text-dark border">Consumer</span>
                </div>
                <div class="small">
                    <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($custName) ?></div>
                    <div class="text-secondary"><?= htmlspecialchars($deliveryAddr) ?></div>
                    <div class="text-secondary">Dist: <?= htmlspecialchars($custDist) ?>, PIN: <?= htmlspecialchars($custPin) ?>, Odisha (21)</div>
                    <div class="mt-1"><strong>Mobile:</strong> <span class="font-monospace text-dark"><?= htmlspecialchars($custMobile ?: 'N/A') ?></span></div>
                    <div><strong>DISCOM Consumer No:</strong> <span class="font-monospace text-primary fw-bold"><?= htmlspecialchars($discomCa ?: 'CA Pending') ?></span> (<?= htmlspecialchars($discomName) ?>)</div>
                    <div><strong>GSTIN / Status:</strong> URP (Unregistered Person / Residential Consumer)</div>
                </div>
            </div>
            <div class="col-6 ps-3">
                <div class="fw-bold text-navy mb-1 pb-1 border-bottom d-flex justify-content-between">
                    <span>SHIPPED TO & SYSTEM PROFILE</span>
                    <span class="badge bg-success-subtle text-success border border-success"><?= $pkgCap ?> kW Plant</span>
                </div>
                <div class="small">
                    <div class="fw-bold text-dark fs-6">Solar Installation Site:</div>
                    <div class="text-secondary"><?= htmlspecialchars($deliveryAddr) ?></div>
                    <div class="mt-1"><strong>Proposed Capacity:</strong> <?= $pkgCap ?> kW On-Grid Rooftop Solar</div>
                    <div><strong>Authorized OEM / Make:</strong> <?= htmlspecialchars($pkgBrand) ?></div>
                    <div><strong>Transporter:</strong> <?= htmlspecialchars($dispatch['courier_partner'] ?? 'SVPL Logistics Fleet') ?></div>
                    <div><strong>Driver:</strong> <?= htmlspecialchars($dispatch['driver_name'] ?? 'Authorized Transporter') ?> (<?= htmlspecialchars($dispatch['driver_mobile'] ?? 'N/A') ?>)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- PRODUCT LIST / 20 BOM ITEMS TABLE -->
    <div class="section-heading d-flex justify-content-between align-items-center">
        <span><i class="bi bi-card-checklist me-1 text-primary"></i> 20-Point Bill of Materials & Technical Hardware Manifest</span>
        <span class="badge bg-white text-navy"><?= count($items) ?> Items Dispatched</span>
    </div>

    <div class="table-responsive">
        <table class="table-invoice">
            <thead>
                <tr>
                    <th style="width: 30px;" class="text-center">#</th>
                    <th>Item Description & Technical Specs</th>
                    <th class="text-center" style="width: 75px;">HSN</th>
                    <th class="text-center" style="width: 50px;">Qty</th>
                    <th class="text-center" style="width: 50px;">Unit</th>
                    <th class="text-end" style="width: 80px;">Rate (₹)</th>
                    <th class="text-end" style="width: 90px;">Taxable (₹)</th>
                    <th class="text-end" style="width: 60px;">CGST</th>
                    <th class="text-end" style="width: 60px;">SGST</th>
                    <th class="text-end" style="width: 95px;">Total (₹)</th>
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
                    $lineCgst = round($lineTaxable * 0.06, 2);
                    $lineSgst = round($lineTaxable * 0.06, 2);
                    $lineTotal = $lineTaxable + $lineCgst + $lineSgst;

                    $totalTaxable += $lineTaxable;
                    $totalCgst += $lineCgst;
                    $totalSgst += $lineSgst;
                ?>
                    <tr>
                        <td class="text-center text-muted fw-bold"><?= $sIdx++ ?></td>
                        <td>
                            <strong class="text-navy"><?= htmlspecialchars($itemName) ?></strong>
                            <?php if (!empty($itemSpec)): ?>
                                <div class="text-secondary" style="font-size: 10.5px;"><?= htmlspecialchars($itemSpec) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="text-center font-monospace text-secondary" style="font-size: 11px;"><?= $hsn ?></td>
                        <td class="text-center fw-bold text-primary font-monospace"><?= $qty ?></td>
                        <td class="text-center text-muted" style="font-size: 11px;"><?= htmlspecialchars($unit) ?></td>
                        <td class="text-end font-monospace"><?= number_format($unitRate, 2) ?></td>
                        <td class="text-end font-monospace fw-semibold"><?= number_format($lineTaxable, 2) ?></td>
                        <td class="text-end font-monospace text-muted"><?= number_format($lineCgst, 2) ?></td>
                        <td class="text-end font-monospace text-muted"><?= number_format($lineSgst, 2) ?></td>
                        <td class="text-end font-monospace fw-bold text-dark"><?= number_format($lineTotal, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <?php 
                $grandTotal = $totalTaxable + $totalCgst + $totalSgst;
                ?>
                <tr style="background: #f8fafc; font-weight: bold;">
                    <td colspan="6" class="text-end">Sub-Total (Taxable Amount):</td>
                    <td class="text-end font-monospace text-dark">₹ <?= number_format($totalTaxable, 2) ?></td>
                    <td class="text-end font-monospace text-dark">₹ <?= number_format($totalCgst, 2) ?></td>
                    <td class="text-end font-monospace text-dark">₹ <?= number_format($totalSgst, 2) ?></td>
                    <td class="text-end font-monospace text-primary">₹ <?= number_format($grandTotal, 2) ?></td>
                </tr>
                <tr style="background: #0f2d59; color: #ffffff; font-weight: bold;">
                    <td colspan="6" class="text-end text-white fs-6">INVOICE GRAND TOTAL (INR):</td>
                    <td colspan="4" class="text-end font-monospace text-warning fs-5">₹ <?= number_format($grandTotal, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- AMOUNT IN WORDS -->
    <div class="p-2 px-3 bg-light border-bottom small">
        <strong>Total Amount in Words:</strong> <span class="text-navy fw-bold"><?= numberToWords($grandTotal) ?></span>
    </div>

    <!-- BANK SETTLEMENT & TERMS -->
    <div class="p-3 bg-white">
        <div class="row g-3">
            <div class="col-7">
                <div class="bank-box">
                    <div class="fw-bold text-navy mb-1"><i class="bi bi-bank me-1 text-primary"></i> Company Bank Settlement Account:</div>
                    <div class="row g-1 small">
                        <div class="col-4 text-muted">Account Name:</div>
                        <div class="col-8 fw-bold">Surya Vistaara Private Limited</div>
                        <div class="col-4 text-muted">Bank Name:</div>
                        <div class="col-8 fw-bold">State Bank of India (SBI)</div>
                        <div class="col-4 text-muted">Account No:</div>
                        <div class="col-8 fw-bold font-monospace text-primary">419823471098</div>
                        <div class="col-4 text-muted">IFSC Code:</div>
                        <div class="col-8 fw-bold font-monospace">SBIN0006789</div>
                        <div class="col-4 text-muted">Branch:</div>
                        <div class="col-8">Mancheswar IE, Bhubaneswar, Odisha</div>
                    </div>
                </div>

                <div class="mt-2 text-muted" style="font-size: 10px;">
                    <strong>Terms & Conditions:</strong><br>
                    1. Goods once sold & dispatched are covered under standard manufacturer warranty (25 Years Performance Warranty on Solar PV Modules, 5 Years On-Site Warranty on Inverter & BOS).<br>
                    2. Certified for Central Subsidy under PM Surya Ghar Muft Bijli Yojana (Direct Benefit Transfer to Consumer Bank Account).<br>
                    3. Subject to Bhubaneswar / Odisha jurisdiction.
                </div>
            </div>

            <div class="col-5">
                <div class="stamp-box h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="fw-bold text-navy" style="font-size: 11px;">For <?= $companyName ?></div>
                        <div class="text-muted" style="font-size: 10px;">Authorized Solar Power Integrator</div>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 9.5px;">[Digitally Authenticated / Official Seal]</div>
                        <div class="fw-bold text-navy mt-1" style="font-size: 11px;">Authorised Signatory</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="p-2 text-center text-muted border-top bg-light" style="font-size: 10px;">
        This is a Computer Generated Tax Invoice issued in accordance with the Goods and Services Tax (GST) Act, 2017.
    </div>

</div>

</body>
</html>
