<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Official Delivery Challan & Site Gate Pass — Solar Rooftop Hardware & Instruments
 */

$companyName = "SURYA VISTAARA PRIVATE LIMITED";
$companyCin = "U40106OR2024PTC045123";
$companyGstin = "21AAKCS8912K1Z9";
$companyAddress = "Plot No. 102/B, Sector-A, Mancheswar Industrial Estate, Bhubaneswar, Khordha, Odisha - 751010";
$companyPhone = "+91 674-2987654 / +91 94370 12345";

$chlNo = $challanNumber ?? ($dispatch['tracking_number'] ?: ('CHL-' . date('Ymd') . '-' . $dispatch['id']));
$chlDate = !empty($dispatch['dispatch_date']) ? date('d/m/Y', strtotime($dispatch['dispatch_date'])) : date('d/m/Y');

$custName = trim(($dispatch['cust_first'] ?? '') . ' ' . ($dispatch['cust_last'] ?? ''));
if (empty($custName)) $custName = $dispatch['customer_name'] ?? 'Solar Rooftop Beneficiary';

$deliveryAddr = $dispatch['delivery_address'] ?? $dispatch['cust_address'] ?? 'Odisha';
$custMobile = $dispatch['cust_mobile'] ?? '';
$custDist = $dispatch['cust_dist'] ?? 'Khordha';
$discomCa = $dispatch['cust_ca'] ?? '';
$discomName = $dispatch['cust_discom'] ?? 'TPCODL';
$pkgBrand = $dispatch['package_brand'] ?? 'Tata Power Solar / Authorized OEM';
$pkgCap = $dispatch['proposed_capacity_kw'] ?? $dispatch['pkg_cap'] ?? '3.0';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Challan — <?= htmlspecialchars($chlNo) ?> — SVPL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f1f5f9; font-family: 'Segoe UI', Arial, sans-serif; color: #0f172a; font-size: 12px; }
        .challan-container { max-width: 860px; margin: 25px auto; background: #ffffff; border: 2px solid #0f2d59; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .challan-header { background: #0f2d59; color: #ffffff; padding: 16px 20px; border-bottom: 3px solid #f59e0b; }
        .section-bar { background: #e2e8f0; color: #0f2d59; font-weight: 700; padding: 5px 12px; font-size: 11.5px; text-transform: uppercase; border-top: 1px solid #cbd5e1; border-bottom: 1px solid #cbd5e1; }
        .table-challan { width: 100%; margin-bottom: 0; font-size: 12px; }
        .table-challan th { background-color: #f8fafc; color: #334155; font-weight: 600; border-bottom: 1px solid #cbd5e1; padding: 6px 8px; }
        .table-challan td { padding: 6px 8px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .meta-label { color: #64748b; font-size: 10.5px; text-transform: uppercase; font-weight: 600; }
        .meta-val { font-weight: 700; color: #0f172a; }
        @media print {
            body { background: #ffffff; padding: 0; margin: 0; }
            .challan-container { border: 1.5px solid #000; box-shadow: none; margin: 0; max-width: 100%; width: 100%; }
            .no-print { display: none !important; }
            .challan-header { background: #0f2d59 !important; -webkit-print-color-adjust: exact; color: #ffffff !important; }
            .section-bar { background: #e2e8f0 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="container-fluid no-print py-3 border-bottom bg-white sticky-top shadow-sm">
    <div class="max-w-860 mx-auto d-flex justify-content-between align-items-center flex-wrap gap-2" style="max-width: 860px;">
        <div class="d-flex align-items-center gap-2">
            <a href="javascript:window.close()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Close
            </a>
            <span class="badge bg-navy text-white px-3 py-2">Delivery Challan (Rule 55)</span>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url('/print/eway-bill/' . $dispatch['id']) ?>" class="btn btn-outline-warning btn-sm text-dark fw-bold">
                <i class="bi bi-truck me-1"></i> E-Way Bill
            </a>
            <a href="<?= url('/print/dispatch-invoice/' . $dispatch['id']) ?>" class="btn btn-outline-primary btn-sm fw-bold">
                <i class="bi bi-receipt-cutoff me-1"></i> GST Tax Invoice
            </a>
            <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm" onclick="window.print()">
                <i class="bi bi-printer-fill me-1"></i> Print Challan
            </button>
        </div>
    </div>
</div>

<div class="challan-container">
    
    <div class="challan-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0 fw-bold font-heading text-white"><?= $companyName ?></h4>
                <div class="small text-warning fw-bold">Delivery Challan & Site Gate Pass (Rule 55 of CGST Rules, 2017)</div>
                <div class="small text-white-50"><?= $companyAddress ?> | GSTIN: <?= $companyGstin ?></div>
            </div>
            <div class="text-end">
                <div class="badge bg-warning text-dark fs-6 px-3 py-1 fw-bold">DELIVERY CHALLAN</div>
                <div class="text-white-50 small mt-1">FOR SUPPLY IN TRANSIT</div>
            </div>
        </div>
    </div>

    <div class="p-3 border-bottom bg-white">
        <div class="row g-2 align-items-center small">
            <div class="col-sm-8">
                <div class="row g-2">
                    <div class="col-6">
                        <span class="meta-label">Challan / LR No:</span>
                        <div class="meta-val font-monospace text-primary fs-6"><?= htmlspecialchars($chlNo) ?></div>
                    </div>
                    <div class="col-6">
                        <span class="meta-label">Dispatch Date:</span>
                        <div class="meta-val"><?= $chlDate ?></div>
                    </div>
                    <div class="col-6">
                        <span class="meta-label">Vehicle Registration No:</span>
                        <div class="meta-val font-monospace text-primary"><?= htmlspecialchars($dispatch['vehicle_number'] ?? 'OD-02-AX-8912') ?></div>
                    </div>
                    <div class="col-6">
                        <span class="meta-label">Driver / Person with Vehicle:</span>
                        <div class="meta-val"><?= htmlspecialchars($dispatch['driver_name'] ?? 'Authorized Person') ?> (<?= htmlspecialchars($dispatch['driver_mobile'] ?? 'N/A') ?>)</div>
                    </div>
                    <div class="col-6">
                        <span class="meta-label">Vendor / OEM Supplied:</span>
                        <div class="meta-val"><?= htmlspecialchars($dispatch['vendor_name'] ?? 'Tata Power Solar / Waaree') ?></div>
                    </div>
                    <div class="col-6">
                        <span class="meta-label">Transporter / Fleet:</span>
                        <div class="meta-val"><?= htmlspecialchars($dispatch['courier_partner'] ?? 'SVPL Logistics Fleet') ?></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 text-end">
                <img src="<?= $qrUrl ?>" alt="Challan QR" style="width: 85px; height: 85px; border: 1px solid #cbd5e1; padding: 2px;">
                <div class="text-muted" style="font-size: 10px;">Scannable Gate Pass</div>
            </div>
        </div>
    </div>

    <div class="section-bar">
        <i class="bi bi-geo-alt-fill me-1 text-warning"></i> Destination Beneficiary & Site Location
    </div>

    <div class="p-3 border-bottom bg-white small">
        <div class="row g-3">
            <div class="col-6 border-end pe-3">
                <div class="meta-label">Customer Beneficiary Name:</div>
                <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($custName) ?></div>
                <div class="meta-label mt-1">Installation Site Address:</div>
                <div class="text-secondary"><?= htmlspecialchars($deliveryAddr) ?></div>
                <div class="text-secondary">Dist: <?= htmlspecialchars($custDist) ?>, Odisha</div>
            </div>
            <div class="col-6 ps-3">
                <div class="meta-label">DISCOM & CA No:</div>
                <div class="fw-bold text-primary font-monospace"><?= htmlspecialchars($discomCa ?: 'CA Pending') ?> (<?= htmlspecialchars($discomName) ?>)</div>
                <div class="meta-label mt-1">Customer Mobile:</div>
                <div class="text-dark font-monospace"><?= htmlspecialchars($custMobile ?: 'N/A') ?></div>
                <div class="meta-label mt-1">Plant Specification:</div>
                <div class="text-dark fw-bold"><?= $pkgCap ?> kW Solar Plant (<?= htmlspecialchars($pkgBrand) ?>)</div>
            </div>
        </div>
    </div>

    <div class="section-bar d-flex justify-content-between align-items-center">
        <span><i class="bi bi-boxes me-1"></i> Transported Solar Instruments & Kit List (Physical Verification)</span>
        <span class="badge bg-white text-dark"><?= count($items) ?> Items</span>
    </div>

    <div class="table-responsive">
        <table class="table-challan">
            <thead>
                <tr>
                    <th style="width: 35px;" class="text-center">#</th>
                    <th>Item Description</th>
                    <th>Specifications / Model</th>
                    <th class="text-center" style="width: 75px;">Qty</th>
                    <th class="text-center" style="width: 75px;">Unit</th>
                    <th class="text-center" style="width: 90px;">Site Check</th>
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
                ?>
                    <tr>
                        <td class="text-center text-muted fw-bold"><?= $sIdx++ ?></td>
                        <td><strong class="text-navy"><?= htmlspecialchars($itemName) ?></strong></td>
                        <td class="text-secondary small"><?= htmlspecialchars($itemSpec) ?></td>
                        <td class="text-center fw-bold text-primary font-monospace"><?= $qty ?></td>
                        <td class="text-center text-muted"><?= htmlspecialchars($unit) ?></td>
                        <td class="text-center"><span style="display: inline-block; width: 16px; height: 16px; border: 1.5px solid #64748b; border-radius: 3px;"></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($dispatch['remarks'])): ?>
        <div class="p-2 px-3 bg-light border-bottom small text-secondary">
            <strong>Remarks / Instructions:</strong> <?= htmlspecialchars($dispatch['remarks']) ?>
        </div>
    <?php endif; ?>

    <div class="p-3 bg-white">
        <div class="row pt-4 text-center small">
            <div class="col-4 border-top pt-2">
                <div class="fw-bold">Receiver / Customer</div>
                <div class="text-muted" style="font-size: 10px;">Materials Received in Good Order</div>
            </div>
            <div class="col-4 border-top pt-2">
                <div class="fw-bold">Driver / Transporter</div>
                <div class="text-muted" style="font-size: 10px;">Delivered to Site</div>
            </div>
            <div class="col-4 border-top pt-2">
                <div class="fw-bold">Authorized SVPL Signatory</div>
                <div class="text-muted" style="font-size: 10px;">Dispatch Verification Officer</div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
