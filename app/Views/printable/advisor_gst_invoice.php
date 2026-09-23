<?php
/**
 * Dhwajja Solar India Pvt. Ltd. / Surya Vistaara Pvt. Ltd.
 * Official GST Tax Invoice — Advisor Partner Registration & Induction Fee
 */

$companyName = function_exists('company_name') ? company_name() : "DHWAJJA SOLAR INDIA PVT. LTD.";
$companyGstin = "21AAMCD5948B1ZU";
$companyPan = "AAMCD5948B";
$companyState = "21 - ODISHA";
$companyAddress = "MIG-84, Pokhariput, BDA Colony, Phase-1 Pokhariput, Bhubaneswar, Odisha - 751020";
$companyPhone = "9040999899";
$companyEmail = "dhwajjasolarsupport@gmail.com";

$advName = trim(($advisor['first_name'] ?? '') . ' ' . ($advisor['last_name'] ?? ''));
if (empty($advName)) $advName = $advisor['user_full_name'] ?? 'Solar Advisor Partner';

$advCode = $advisor['advisor_code'] ?? ('SVPL-ADV-' . $advisor['id']);
$advAddress = $advisor['address_line'] ?? ($advisor['village'] ? $advisor['village'] . ', ' . $advisor['block'] : ($advisor['district'] ?? 'Odisha'));
$advDistrict = $advisor['district'] ?? 'Odisha';
$advPin = $advisor['pincode'] ?? '751024';
$advMobile = $advisor['mobile'] ?? ($advisor['user_mobile'] ?? '');
$advEmail = $advisor['email'] ?? ($advisor['user_email'] ?? '');

$totalPaid = (float)($payment['amount'] ?? ($advisor['joining_fee'] ?? (function_exists('advisor_joining_fee') ? advisor_joining_fee() : 1180.00)));
$taxableValue = round($totalPaid / 1.18, 2);
$cgst = round(($totalPaid - $taxableValue) / 2, 2);
$sgst = round($totalPaid - $taxableValue - $cgst, 2);

$invNo = 'DSIPL/ADV-INV/' . date('Y') . '/' . str_pad((string)($payment['id'] ?? $advisor['id']), 5, '0', STR_PAD_LEFT);
$invDate = !empty($payment['payment_date']) ? date('d/m/Y', strtotime($payment['payment_date'])) : (!empty($advisor['created_at']) ? date('d/m/Y', strtotime($advisor['created_at'])) : date('d/m/Y'));
$txnRef = $payment['transaction_ref'] ?? ('TXN-REG-' . $advisor['id'] . '-' . date('Ymd'));
$payMode = $payment['payment_method'] ?? 'Online UPI / Bank Transfer';

if (!function_exists('numberToWordsInv')) {
    function numberToWordsInv(float $number): string {
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
        $paise = ($decimal > 0) ? " and " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise . " Only";
    }
}
$amountWords = numberToWordsInv($totalPaid);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GST Tax Invoice - <?= htmlspecialchars($advCode) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bs-font-sans-serif: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        body {
            font-family: var(--bs-font-sans-serif);
            font-size: 11.5px;
            color: #1e293b;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .invoice-card {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 18mm 18mm 15mm 18mm;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            box-sizing: border-box;
            position: relative;
        }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        .text-navy { color: #0f172a; }
        .bg-navy { background-color: #0f172a; color: #fff; }
        .border-navy { border-color: #0f172a !important; }
        .table-bordered-dark { border: 1px solid #cbd5e1; }
        .table-bordered-dark th, .table-bordered-dark td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .table-bordered-dark thead th {
            background-color: #f8fafc;
            color: #0f172a;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }
        .qr-placeholder {
            width: 90px;
            height: 90px;
            border: 1px solid #e2e8f0;
            padding: 4px;
            background: #fff;
            display: inline-block;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 80px;
            font-weight: 900;
            color: rgba(15, 23, 42, 0.03);
            pointer-events: none;
            white-space: nowrap;
            z-index: 0;
            text-transform: uppercase;
        }
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .invoice-card {
                width: 100% !important;
                min-height: auto !important;
                box-shadow: none !important;
                padding: 10mm 12mm !important;
                margin: 0 !important;
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

    <!-- ACTION BUTTONS -->
    <div class="container no-print mb-4 text-center" style="max-width: 210mm;">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 shadow-sm border">
            <div class="text-start">
                <span class="badge bg-success fw-bold me-2"><i class="bi bi-patch-check-fill me-1"></i> PAID & VERIFIED</span>
                <strong class="text-navy">GST Tax Invoice #<?= htmlspecialchars($invNo) ?></strong>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-primary btn-sm fw-bold px-3">
                    <i class="bi bi-printer-fill me-1"></i> Print / Save PDF
                </button>
                <a href="<?= url('/advisor/dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- INVOICE SHEET -->
    <div class="invoice-card">
        <div class="watermark font-outfit">TAX INVOICE PAID</div>

        <!-- HEADER SECTION -->
        <div class="row align-items-center border-bottom pb-3 mb-3">
            <div class="col-8">
                <div class="d-flex align-items-center gap-3">
                    <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900; font-size: 1.5rem;">
                        ☀
                    </div>
                    <div>
                        <h4 class="font-outfit fw-bold text-navy mb-0" style="letter-spacing: -0.5px;"><?= htmlspecialchars($companyName) ?></h4>
                        <div class="text-muted small" style="font-size: 10.5px;">Authorized Channel Partner & Rooftop Solar EPC Promoter</div>
                    </div>
                </div>
                <div class="mt-2 text-secondary" style="font-size: 10px; line-height: 1.4;">
                    <?= htmlspecialchars($companyAddress) ?><br>
                    <strong>GSTIN:</strong> <span class="text-navy font-monospace fw-bold"><?= htmlspecialchars($companyGstin) ?></span> | 
                    <strong>PAN:</strong> <span class="text-navy font-monospace"><?= htmlspecialchars($companyPan) ?></span> | 
                    <strong>State:</strong> <?= htmlspecialchars($companyState) ?><br>
                    <strong>Email:</strong> <?= htmlspecialchars($companyEmail) ?> | <strong>Phone:</strong> <?= htmlspecialchars($companyPhone) ?>
                </div>
            </div>
            <div class="col-4 text-end">
                <div class="d-inline-block text-start p-2 rounded bg-light border">
                    <div class="text-uppercase fw-bold text-primary" style="font-size: 9.5px; letter-spacing: 0.5px;">ORIGINAL FOR RECIPIENT</div>
                    <h5 class="font-outfit fw-bold text-navy mb-1" style="font-size: 1.1rem;">TAX INVOICE</h5>
                    <div class="small"><strong>Invoice No:</strong> <span class="font-monospace text-navy fw-bold"><?= htmlspecialchars($invNo) ?></span></div>
                    <div class="small"><strong>Date:</strong> <?= htmlspecialchars($invDate) ?></div>
                </div>
            </div>
        </div>

        <!-- BILL TO & PAYMENT SUMMARY -->
        <div class="row g-3 mb-3">
            <div class="col-7">
                <div class="p-2 border rounded bg-light h-100">
                    <div class="text-uppercase fw-bold text-secondary mb-1" style="font-size: 9.5px;">Billed To (Authorized Solar Advisor):</div>
                    <h6 class="fw-bold text-navy mb-1"><?= htmlspecialchars($advName) ?></h6>
                    <div style="font-size: 10.5px; line-height: 1.4;">
                        <strong>Advisor Code:</strong> <span class="badge bg-navy font-monospace"><?= htmlspecialchars($advCode) ?></span><br>
                        <strong>Address:</strong> <?= htmlspecialchars($advAddress) ?><br>
                        <strong>District / State:</strong> <?= htmlspecialchars($advDistrict) ?>, Odisha - <?= htmlspecialchars($advPin) ?> (State Code: 21)<br>
                        <strong>Mobile:</strong> <?= htmlspecialchars($advMobile) ?> | <strong>Email:</strong> <?= htmlspecialchars($advEmail ?: 'N/A') ?>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="p-2 border rounded bg-light h-100">
                    <div class="text-uppercase fw-bold text-secondary mb-1" style="font-size: 9.5px;">Transaction & Payment Details:</div>
                    <table class="w-100" style="font-size: 10.5px; line-height: 1.5;">
                        <tr>
                            <td class="text-muted">Payment Mode:</td>
                            <td class="fw-semibold text-end"><?= htmlspecialchars($payMode) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">UTR / Txn Ref:</td>
                            <td class="font-monospace text-navy fw-bold text-end"><?= htmlspecialchars($txnRef) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Payment Status:</td>
                            <td class="text-end"><span class="badge bg-success" style="font-size: 9px;">CONFIRMED / PAID</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Supply Place:</td>
                            <td class="fw-semibold text-end">Odisha (21) — Intrastate</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- LINE ITEMS TABLE -->
        <div class="table-responsive mb-3">
            <table class="table table-bordered-dark w-100 mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%;">#</th>
                        <th style="width: 45%;">Service Description</th>
                        <th class="text-center" style="width: 12%;">SAC Code</th>
                        <th class="text-end" style="width: 12%;">Rate (₹)</th>
                        <th class="text-center" style="width: 8%;">Qty</th>
                        <th class="text-end" style="width: 18%;">Taxable Value (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>
                            <strong class="text-navy">Solar Advisor Partner Enrollment & Business Induction Fee</strong>
                            <div class="text-muted" style="font-size: 9.5px;">
                                Accreditation as Authorized Solar Advisor Partner under Dhwajja Solar India / SVPL Rooftop Solar Channel Network across Odisha. Includes digital kit, marketing collateral, portal credentials, and lifetime 9-level commission eligibility.
                            </div>
                        </td>
                        <td class="text-center font-monospace">998399</td>
                        <td class="text-end font-monospace"><?= number_format($taxableValue, 2) ?></td>
                        <td class="text-center">1</td>
                        <td class="text-end font-monospace fw-bold"><?= number_format($taxableValue, 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- TOTALS & TAX BREAKDOWN -->
        <div class="row g-3 mb-3">
            <div class="col-7">
                <div class="p-2 border rounded bg-light mb-2">
                    <div class="text-uppercase fw-bold text-secondary" style="font-size: 9px;">Amount in Words:</div>
                    <div class="fw-bold text-navy" style="font-size: 11px;"><?= htmlspecialchars($amountWords) ?></div>
                </div>

                <div class="p-2 border rounded" style="font-size: 9.5px; line-height: 1.4; background: #fff;">
                    <strong>Statutory Declarations & Terms:</strong>
                    <ul class="mb-0 ps-3 text-muted">
                        <li>This invoice is issued under the provisions of the Central Goods & Services Tax (CGST) and Odisha Goods & Services Tax (OGST) Act, 2017.</li>
                        <li>Supply of business support and agency enrollment services is strictly non-refundable once activated.</li>
                        <li>Applicable TDS on advisor channel payouts will be deducted under Section 194H of the Income Tax Act.</li>
                    </ul>
                </div>
            </div>

            <div class="col-5">
                <table class="table table-bordered-dark w-100 mb-0" style="font-size: 11px;">
                    <tr>
                        <td class="text-muted">Total Taxable Value:</td>
                        <td class="text-end font-monospace fw-bold">₹<?= number_format($taxableValue, 2) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Central GST (CGST @ 9%):</td>
                        <td class="text-end font-monospace">₹<?= number_format($cgst, 2) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">State GST (SGST @ 9%):</td>
                        <td class="text-end font-monospace">₹<?= number_format($sgst, 2) ?></td>
                    </tr>
                    <tr class="bg-navy text-white">
                        <td class="fw-bold text-white">Total Gross Paid (INR):</td>
                        <td class="text-end font-monospace fw-bold fs-6 text-warning">₹<?= number_format($totalPaid, 2) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- FOOTER & SIGNATURE SECTION -->
        <div class="row align-items-end pt-2 border-top mt-auto">
            <div class="col-4 text-center text-md-start">
                <?php if (!empty($qrUrl)): ?>
                    <img src="<?= htmlspecialchars($qrUrl) ?>" class="qr-placeholder" alt="QR Code">
                <?php else: ?>
                    <div class="qr-placeholder d-inline-flex align-items-center justify-content-center text-muted" style="font-size: 9px;">
                        [ Verified QR ]
                    </div>
                <?php endif; ?>
                <div class="text-muted small mt-1" style="font-size: 8.5px;">Scan to Verify Accreditation</div>
            </div>
            
            <div class="col-4 text-center">
                <div class="badge bg-light text-dark border p-2 mb-1" style="font-size: 9px;">
                    <i class="bi bi-shield-check text-success me-1"></i> Digitally Signed & Authenticated
                </div>
                <div class="text-muted" style="font-size: 8.5px;">Automated GST Document</div>
            </div>

            <div class="col-4 text-end">
                <div style="font-size: 10px;" class="fw-bold text-navy">For <?= htmlspecialchars($companyName) ?></div>
                <div class="my-2" style="height: 35px; display: flex; align-items: center; justify-content: flex-end;">
                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1" style="font-size: 9px; font-family: monospace;">
                        ✔ AUTHORIZED SIGNATORY
                    </span>
                </div>
                <div class="border-top pt-1 text-muted" style="font-size: 9px;">Accounts & Finance Division</div>
            </div>
        </div>

        <div class="text-center text-muted mt-3 pt-2 border-top" style="font-size: 8.5px;">
            This is a computer-generated tax invoice generated in accordance with GST Rule 46. No physical signature is required.
        </div>
    </div>

</body>
</html>
