<?php
/**
 * Dhwajja Solar India Pvt. Ltd. / Surya Vistaara Pvt. Ltd.
 * Printable 2-Page Official Technical Quotation & Proposal
 */

$companyName = function_exists('company_name') ? company_name() : "DHWAJJA SOLAR INDIA PRIVATE LIMITED";
$companyGstin = function_exists('company_gstin') && company_gstin() ? company_gstin() : "21AAMCD5948B1ZU";
$companyAddress = function_exists('company_address') && company_address() ? company_address() : "MIG-84, POKHARIPUT, BDA COLONY, PHASE-1, Pokhariput, Bhubaneswar, Khorda - 751020, Orissa";
$companyEmail = function_exists('company_email') && company_email() ? company_email() : "dhwajjasolaruserservices@gmail.com";
$companyPhone = function_exists('company_phone') && company_phone() ? company_phone() : "9040999899";
$companySignature = function_exists('company_signature_url') ? company_signature_url() : "/assets/images/authorised_signatory.png";

// Customer & Lead details
$custName = strtoupper(trim(($customer['first_name'] ?? ($lead['first_name'] ?? '')) . ' ' . ($customer['last_name'] ?? ($lead['last_name'] ?? ''))));
if (empty($custName)) $custName = "VALUED CUSTOMER";

$custAddress = trim(($customer['village'] ?? '') ? "AT- " . $customer['village'] : ($customer['address_line'] ?? ''));
$custBlock = trim($customer['block'] ?? ($lead['block'] ?? ''));
$custDistrict = strtoupper(trim($customer['district'] ?? ($lead['district'] ?? 'Khordha')));
$custPin = trim($customer['pincode'] ?? ($lead['pincode'] ?? '751020'));
$custState = "Odisha";

$fullAddressLine = "";
if (!empty($customer['address_line'])) {
    $fullAddressLine = $customer['address_line'];
} else {
    $parts = [];
    if (!empty($customer['village'])) $parts[] = "AT-" . strtoupper($customer['village']);
    if (!empty($customer['gram_panchayat'])) $parts[] = strtoupper($customer['gram_panchayat']);
    if (!empty($customer['block'])) $parts[] = "P O-" . strtoupper($customer['block']);
    if (!empty($custDistrict)) $parts[] = "DIST-" . $custDistrict;
    $fullAddressLine = implode(', ', $parts);
}

$capacityKw = (float)($customer['proposed_solar_kw'] ?? ($lead['proposed_capacity_kw'] ?? 3.0));
if ($capacityKw <= 0) $capacityKw = 3.0;
$capacityKwFormatted = ($capacityKw == (int)$capacityKw) ? (int)$capacityKw : number_format($capacityKw, 1);

// Quotation Number & Date
$finYear = date('n') >= 4 ? date('Y') . '-' . substr(date('Y') + 1, 2) : (date('Y') - 1) . '-' . substr(date('Y'), 2);
$quoteRef = !empty($quotation['quotation_number']) ? $quotation['quotation_number'] : ("DSI/" . $finYear . "/" . str_pad((string)($customer['id'] ?? ($lead['id'] ?? 1)), 4, '0', STR_PAD_LEFT));
$quoteDate = !empty($quotation['created_at']) ? date('d.m.Y', strtotime($quotation['created_at'])) : date('d.m.Y');

// Pricing Calculations
$panelWatt = 545;
$panelQty = (int)ceil(($capacityKw * 1000) / $panelWatt);
if ($panelQty < 1) $panelQty = (int)ceil($capacityKw * 2);

// Total Project Cost & Pricing benchmark
$basePrice = (float)($lead['estimated_project_cost'] ?? 0);
if ($basePrice <= 0) {
    if ($capacityKw <= 1) $basePrice = 85000;
    elseif ($capacityKw <= 2) $basePrice = 155000;
    elseif ($capacityKw <= 3) $basePrice = 222200;
    else $basePrice = 222200 + ($capacityKw - 3) * 65000;
}

// Subsidies
$centralSubsidy = (float)($lead['subsidy_amount'] ?? 0);
if ($centralSubsidy <= 0) {
    if ($capacityKw <= 1) $centralSubsidy = 30000;
    elseif ($capacityKw <= 2) $centralSubsidy = 60000;
    else $centralSubsidy = 78000;
}

$stateSubsidy = (float)($lead['state_subsidy'] ?? 0);
if ($stateSubsidy <= 0) {
    if ($capacityKw <= 1) $stateSubsidy = 20000;
    elseif ($capacityKw <= 2) $stateSubsidy = 40000;
    else $stateSubsidy = 60000;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Quotation - <?= htmlspecialchars($quoteRef) ?> - <?= htmlspecialchars($custName) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 11px;
            color: #0f172a;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .page-container {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 20px auto;
            padding: 12mm 15mm;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            position: relative;
        }
        .font-heading { font-family: 'Outfit', sans-serif; }
        .text-navy { color: #092c4c; }
        
        /* Quotation Header */
        .qtn-header {
            border-bottom: 2px solid #092c4c;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .qtn-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .qtn-table th, .qtn-table td {
            border: 1px solid #1e293b;
            padding: 4px 6px;
            font-size: 9.5px;
            vertical-align: middle;
        }
        .qtn-table th {
            background-color: #f8fafc;
            font-weight: 700;
            text-align: center;
        }
        .page-2-box {
            border: 1px solid #0f172a;
            height: 100%;
            padding: 12px 14px;
            font-size: 10px;
            line-height: 1.5;
        }
        
        @media print {
            body {
                background: #fff !important;
                padding: 0 !important;
            }
            .page-container {
                width: 100% !important;
                min-height: 100% !important;
                margin: 0 !important;
                padding: 8mm 12mm !important;
                box-shadow: none !important;
                page-break-after: always;
            }
            .page-container:last-child {
                page-break-after: auto;
            }
            .no-print {
                display: none !important;
            }
            @page {
                size: A4 portrait;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- TOP ACTION BAR -->
    <div class="container no-print mb-3 text-center" style="max-width: 210mm;">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 shadow-sm border">
            <div>
                <h5 class="fw-bold font-heading mb-0 text-navy text-start">Solar Proposal & Quotation</h5>
                <span class="text-secondary small">Quotation Ref: <?= htmlspecialchars($quoteRef) ?></span>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-primary btn-sm fw-bold px-4">
                    <i class="bi bi-printer-fill me-1"></i> Print / Save as PDF
                </button>
                <button onclick="window.history.back()" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- PAGE 1: PRICE QUOTATION & TECHNICAL SPECIFICATIONS -->
    <!-- ========================================================================= -->
    <div class="page-container">
        
        <!-- Header: Logo & Company Contacts -->
        <div class="qtn-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <?php if (function_exists('company_logo_url') && company_logo_url()): ?>
                    <img src="<?= htmlspecialchars(company_logo_url()) ?>" alt="Dhwajja Logo" style="height: 52px; max-width: 160px; object-fit: contain;">
                <?php else: ?>
                    <div style="width: 46px; height: 46px; border-radius: 8px; background: #092c4c; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold;">☀</div>
                <?php endif; ?>
                <div>
                    <h3 class="font-heading fw-bold mb-0 text-navy" style="letter-spacing: 0.5px; font-size: 1.35rem;">
                        <?= htmlspecialchars(strtoupper($companyName)) ?>
                    </h3>
                    <div class="small text-secondary fw-semibold" style="font-size: 8.5px; letter-spacing: 1px;">
                        — HARNESSING SUNLIGHT, POWERING TOMORROW. —
                    </div>
                </div>
            </div>
            <div class="text-end" style="font-size: 9px; line-height: 1.35; max-width: 250px;">
                <div class="fw-bold text-navy"><i class="bi bi-shield-check text-success"></i> GST-<?= htmlspecialchars($companyGstin) ?></div>
                <div class="text-secondary"><i class="bi bi-geo-alt-fill text-danger"></i> <?= htmlspecialchars($companyAddress) ?></div>
                <div><i class="bi bi-envelope-fill text-primary"></i> <?= htmlspecialchars($companyEmail) ?></div>
                <div class="fw-bold"><i class="bi bi-telephone-fill text-success"></i> Mob-<?= htmlspecialchars($companyPhone) ?></div>
            </div>
        </div>

        <!-- Reference & Title Bar -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="fw-bold text-navy font-monospace" style="font-size: 11px;"><?= htmlspecialchars($quoteRef) ?></div>
            <div class="font-heading fw-bold text-navy text-center" style="font-size: 14px; text-decoration: underline; letter-spacing: 1px;">QUOTATION</div>
            <div class="fw-bold text-dark font-monospace" style="font-size: 11px;"><?= htmlspecialchars($quoteDate) ?></div>
        </div>

        <!-- Recipient Details -->
        <div class="mb-2" style="font-size: 10px; line-height: 1.35;">
            <div class="fw-bold">To,</div>
            <div class="fw-bold text-navy"><?= htmlspecialchars($custName) ?></div>
            <div><?= htmlspecialchars($fullAddressLine) ?></div>
            <div><?= htmlspecialchars($custState) ?>- <?= htmlspecialchars($custPin) ?></div>
        </div>

        <!-- Subject -->
        <div class="mb-2" style="font-size: 10px;">
            <div class="fw-bold text-navy">Sub: Price Quotation for <?= $capacityKwFormatted ?>KW Grid Tie Solar Power System.</div>
            <div class="mt-1">Madam/Sir,</div>
            <div>Hereby, we are submitting the Price Quotation and Technical Specifications for <?= $capacityKwFormatted ?> KW On-Grid Solar Power System.</div>
        </div>

        <!-- Technical Specification Table (7 Items Matching Uploaded Quotation PDF) -->
        <table class="qtn-table">
            <thead>
                <tr>
                    <th style="width: 5%;">SL NO.</th>
                    <th style="width: 15%;">Product Type</th>
                    <th style="width: 55%;">Product Description</th>
                    <th style="width: 10%;">Quantity</th>
                    <th style="width: 15%;">Price(Rs.)</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1. Solar Panel -->
                <tr>
                    <td class="text-center fw-bold">1</td>
                    <td class="fw-bold text-navy">Solar Panel</td>
                    <td>
                        <div class="fw-semibold">540-550Wp Mono Half-cut/Bi-Facial, DCR PV Module</div>
                        <div class="text-muted" style="font-size: 8.5px;">Make: (OEM APPROVED)</div>
                    </td>
                    <td class="text-center fw-bold"><?= $panelQty ?></td>
                    <td rowspan="10" class="text-center fw-bold fs-6 text-navy align-middle" style="background: #ffffff;">
                        <?= number_format($basePrice, 0) ?>
                    </td>
                </tr>

                <!-- 2. Inverter -->
                <tr>
                    <td class="text-center fw-bold">2</td>
                    <td class="fw-bold text-navy">Inverter</td>
                    <td>
                        <div class="fw-semibold">Solar Grid Tie PCU Inverter <?= $capacityKwFormatted ?>KW MPPT, 1Ph</div>
                        <div class="text-muted" style="font-size: 8.5px;">Make: (OEM APPROVED)</div>
                    </td>
                    <td class="text-center fw-bold">1</td>
                </tr>

                <!-- 3. Structure -->
                <tr>
                    <td class="text-center fw-bold">3</td>
                    <td class="fw-bold text-navy">Structure</td>
                    <td>
                        <div>Module mounting GI Rooftop Structure for <?= $capacityKwFormatted ?>KW</div>
                        <div class="text-muted" style="font-size: 8.5px;">(Height 3 to 4 ft as required)</div>
                    </td>
                    <td class="text-center fw-bold">1 Set</td>
                </tr>

                <!-- 4. Meter & DBs -->
                <tr>
                    <td class="text-center fw-bold" rowspan="3">4</td>
                    <td class="fw-bold text-navy" rowspan="3">Meter</td>
                    <td>
                        <div>Digital Energy Meter Generation and Import/Export</div>
                        <div class="text-muted" style="font-size: 8.5px;">(Secure Meter)</div>
                    </td>
                    <td class="text-center fw-bold">1</td>
                </tr>
                <tr>
                    <td>ACDB 1Ph with SPD/MCB <?= $capacityKwFormatted ?>KW</td>
                    <td class="text-center fw-bold">1</td>
                </tr>
                <tr>
                    <td>DCDB 1 in-1 out with SPD</td>
                    <td class="text-center fw-bold">1</td>
                </tr>

                <!-- 5. Cables & Accessories -->
                <tr>
                    <td class="text-center fw-bold" rowspan="4">5</td>
                    <td class="fw-bold text-navy" rowspan="4">Cable</td>
                    <td>Solar DC Cable 4 sq.mm</td>
                    <td class="text-center text-muted">As req.</td>
                </tr>
                <tr>
                    <td>2.5 sq.mm 2C Copper Cable for AC side</td>
                    <td class="text-center text-muted">As req.</td>
                </tr>
                <tr>
                    <td>PVC Conduit Pipe and accessories for cable laying</td>
                    <td class="text-center text-muted">As req.</td>
                </tr>
                <tr>
                    <td>MC4 Connector</td>
                    <td class="text-center text-muted">As req.</td>
                </tr>

                <!-- 6. Earthing & Protection -->
                <tr>
                    <td class="text-center fw-bold" rowspan="3">6</td>
                    <td class="fw-bold text-navy" rowspan="3">Earthing</td>
                    <td>Earthing – Chemical Earthing Kit | Quantity</td>
                    <td class="text-center fw-bold">03 Nos.</td>
                    <td rowspan="3" style="border-top: none;"></td>
                </tr>
                <tr>
                    <td>Lightning Arrester | Quantity</td>
                    <td class="text-center fw-bold">1</td>
                </tr>
                <tr>
                    <td>16 sq.mm Earthing Cable with saddle</td>
                    <td class="text-center text-muted">As req.</td>
                </tr>

                <!-- 7. Services & Installation -->
                <tr>
                    <td class="text-center fw-bold" rowspan="2">7</td>
                    <td class="fw-bold text-navy" rowspan="2">Services</td>
                    <td>Installation and Commissioning Cost</td>
                    <td class="text-center fw-bold"><?= $capacityKwFormatted ?>KW</td>
                    <td rowspan="2" style="border-top: none;"></td>
                </tr>
                <tr>
                    <td>Net Meter, Inverter Charge and all Departmental Cost</td>
                    <td class="text-center">-</td>
                </tr>

                <!-- GST Row -->
                <tr style="background: #f8fafc;">
                    <td colspan="4" class="text-end fw-bold">GST (5%)</td>
                    <td class="text-center fw-bold">Included</td>
                </tr>

                <!-- Total Cost Row -->
                <tr style="background: #f1f5f9; border-top: 2px solid #0f172a;">
                    <td colspan="4" class="text-end fw-bold fs-6 text-navy">TOTAL COST</td>
                    <td class="text-center fw-bold fs-6 text-navy"><?= number_format($basePrice, 0) ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Footnotes -->
        <div class="text-center fw-bold small mb-3" style="font-size: 9.5px;">
            <div>*All Taxes and Installation Charges included.</div>
            <div>*All Products are sourced from ALMM/MNRE approved Brands.</div>
        </div>

        <!-- Page 1 Signatory Block -->
        <div class="d-flex justify-content-end mt-4">
            <div class="text-center" style="min-width: 220px;">
                <div class="fw-bold text-navy" style="font-size: 10px;"><?= htmlspecialchars(strtoupper($companyName)) ?></div>
                <div class="my-1">
                    <img src="<?= htmlspecialchars($companySignature) ?>" alt="Authorised Signatory" style="height: 52px; object-fit: contain; max-width: 180px;">
                </div>
                <div class="fw-bold text-navy" style="font-size: 10.5px;">Authorised Signatory</div>
            </div>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- PAGE 2: TERMS, WARRANTY, SUBSIDY & COMPANY BANK DETAILS -->
    <!-- ========================================================================= -->
    <div class="page-container">
        
        <!-- Header: Logo & Company Contacts (Repeated for Page 2) -->
        <div class="qtn-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <?php if (function_exists('company_logo_url') && company_logo_url()): ?>
                    <img src="<?= htmlspecialchars(company_logo_url()) ?>" alt="Dhwajja Logo" style="height: 52px; max-width: 160px; object-fit: contain;">
                <?php else: ?>
                    <div style="width: 46px; height: 46px; border-radius: 8px; background: #092c4c; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold;">☀</div>
                <?php endif; ?>
                <div>
                    <h3 class="font-heading fw-bold mb-0 text-navy" style="letter-spacing: 0.5px; font-size: 1.35rem;">
                        <?= htmlspecialchars(strtoupper($companyName)) ?>
                    </h3>
                    <div class="small text-secondary fw-semibold" style="font-size: 8.5px; letter-spacing: 1px;">
                        — HARNESSING SUNLIGHT, POWERING TOMORROW. —
                    </div>
                </div>
            </div>
            <div class="text-end" style="font-size: 9px; line-height: 1.35; max-width: 250px;">
                <div class="fw-bold text-navy"><i class="bi bi-shield-check text-success"></i> GST-<?= htmlspecialchars($companyGstin) ?></div>
                <div class="text-secondary"><i class="bi bi-geo-alt-fill text-danger"></i> <?= htmlspecialchars($companyAddress) ?></div>
                <div><i class="bi bi-envelope-fill text-primary"></i> <?= htmlspecialchars($companyEmail) ?></div>
                <div class="fw-bold"><i class="bi bi-telephone-fill text-success"></i> Mob-<?= htmlspecialchars($companyPhone) ?></div>
            </div>
        </div>

        <!-- 2-Column Split Box Matching Uploaded PDF Page 2 -->
        <div class="row g-0 border border-dark mb-4" style="min-height: 180mm;">
            
            <!-- Left Column: Terms, Conditions, Warranty, Payment & Subsidy -->
            <div class="col-7 p-3 border-end border-dark" style="font-size: 9.5px; line-height: 1.55;">
                
                <div class="d-flex align-items-start gap-2 mb-2">
                    <span class="fw-bold">¤</span>
                    <div><strong>Transportation Cost</strong> – included</div>
                </div>

                <div class="d-flex align-items-start gap-2 mb-2">
                    <span class="fw-bold">¤</span>
                    <div>All civil works are in Client's scope.</div>
                </div>

                <div class="d-flex align-items-start gap-2 mb-2">
                    <span class="fw-bold">¤</span>
                    <div>1st Floor above Per floor @ 3500/- extra Chargeable.</div>
                </div>

                <div class="d-flex align-items-start gap-2 mb-2">
                    <span class="fw-bold">¤</span>
                    <div>The quotation price is valid for 15 days.</div>
                </div>

                <div class="d-flex align-items-start gap-2 mb-2">
                    <span class="fw-bold">¤</span>
                    <div>
                        <strong class="text-navy">Warranty</strong>
                        <div>• Solar Panel – 25 years</div>
                        <div>• Solar On-Grid Inverter – 10 years</div>
                        <div>• Solar Power System – 5 years</div>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-2 mb-2">
                    <span class="fw-bold">¤</span>
                    <div>
                        <strong class="text-navy">Payment Terms</strong>
                        <div>• 75% Advance along with Letter of Award (LOA) / Order Confirmation.</div>
                        <div>• 25% Balance payable upon completion of installation.</div>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-2 mb-2">
                    <span class="fw-bold">¤</span>
                    <div>
                        Financial facilities for Govt-approved portals (Jan Samarth Portal) are available.
                    </div>
                </div>

                <div class="d-flex align-items-start gap-2 mb-2">
                    <span class="fw-bold">¤</span>
                    <div>
                        <strong class="text-navy">Subsidy</strong>
                        <div>• Central - ₹<?= number_format($centralSubsidy, 2) ?></div>
                        <div>• State - ₹<?= number_format($stateSubsidy, 2) ?></div>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-2 mb-2">
                    <span class="fw-bold">¤</span>
                    <div>
                        Subsidy amount comes after completion of liaising work.
                    </div>
                </div>

                <div class="d-flex align-items-start gap-2 mb-2">
                    <span class="fw-bold">¤</span>
                    <div>
                        We take 1-2 weeks to commission the entire project after commercially clearing the order (exceptions during Emergencies).
                    </div>
                </div>

            </div>

            <!-- Right Column: Bank Details -->
            <div class="col-5 p-3" style="font-size: 10px; line-height: 1.6;">
                
                <div class="fw-bold text-navy font-heading mb-3" style="font-size: 12px; letter-spacing: 0.5px;">
                    BANK DETAILS:-
                </div>

                <div class="mb-3">
                    <div class="text-secondary small fw-bold">NAME-</div>
                    <div class="fw-bold text-navy"><?= htmlspecialchars(strtoupper($companyName)) ?></div>
                </div>

                <div class="mb-3">
                    <div class="text-secondary small fw-bold">BANK-</div>
                    <div class="fw-bold"><?= htmlspecialchars(company_setting('company_bank_name') ?: 'ODISHA GRAMEEN BANK') ?></div>
                </div>

                <div class="mb-3">
                    <div class="text-secondary small fw-bold">A/C NO-</div>
                    <div class="fw-bold font-monospace fs-6"><?= htmlspecialchars(company_setting('company_account_no') ?: '013432003000158') ?></div>
                </div>

                <div class="mb-3">
                    <div class="text-secondary small fw-bold">IFSC-</div>
                    <div class="fw-bold font-monospace"><?= htmlspecialchars(company_setting('company_ifsc') ?: 'IOBA0GB0134') ?></div>
                </div>

                <div class="mb-3">
                    <div class="text-secondary small fw-bold">BRANCH-</div>
                    <div class="fw-bold"><?= htmlspecialchars(strtoupper(company_setting('company_branch') ?: 'GANDAMUNDA, KHANDAGIRI')) ?></div>
                </div>

            </div>

        </div>

        <!-- Page 2 Signatory Block -->
        <div class="d-flex justify-content-end mt-4">
            <div class="text-center" style="min-width: 220px;">
                <div class="fw-bold text-navy" style="font-size: 10px;"><?= htmlspecialchars(strtoupper($companyName)) ?></div>
                <div class="my-1">
                    <img src="<?= htmlspecialchars($companySignature) ?>" alt="Authorised Signatory" style="height: 52px; object-fit: contain; max-width: 180px;">
                </div>
                <div class="fw-bold text-navy" style="font-size: 10.5px;">Authorised Signatory</div>
            </div>
        </div>

    </div>

</body>
</html>
