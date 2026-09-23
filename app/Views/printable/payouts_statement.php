<?php
/**
 * Dhwajja Solar India Pvt. Ltd. / Surya Vistaara Pvt. Ltd.
 * Printable Advisor Bank Withdrawals & Payouts Statement
 */

$companyName = function_exists('company_name') ? company_name() : "DHWAJJA SOLAR INDIA PVT. LTD.";
$companyGstin = "21AAMCD5948B1ZU";
$companyAddress = "MIG-84, Pokhariput, BDA Colony, Phase-1 Pokhariput, Bhubaneswar, Odisha - 751020";

$advName = trim(($advisor['first_name'] ?? '') . ' ' . ($advisor['last_name'] ?? ''));
if (empty($advName)) $advName = $advisor['user_full_name'] ?? 'Authorized Solar Advisor';
$advCode = $advisor['advisor_code'] ?? ('SVPL-ADV-' . $advisor['id']);
$advMobile = $advisor['mobile'] ?? ($advisor['user_mobile'] ?? '');
$advDistrict = $advisor['district'] ?? 'Odisha';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Payouts Statement - <?= htmlspecialchars($advCode) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 11px;
            color: #1e293b;
            background-color: #f8fafc;
            padding: 20px 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .statement-card {
            background: #ffffff;
            max-width: 230mm;
            margin: 0 auto;
            padding: 15mm 18mm;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            border-radius: 8px;
        }
        .font-heading { font-family: 'Outfit', sans-serif; }
        .text-navy { color: #092c4c; }
        .table-bordered th, .table-bordered td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 10px;
        }
        @media print {
            body { background: #fff !important; padding: 0 !important; }
            .statement-card { width: 100% !important; max-width: 100% !important; box-shadow: none !important; padding: 5mm 8mm !important; }
            .no-print { display: none !important; }
            @page { size: A4 portrait; margin: 10mm; }
        }
    </style>
</head>
<body>

    <div class="container no-print mb-4 text-center" style="max-width: 230mm;">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 shadow-sm border">
            <div class="text-start">
                <span class="badge bg-success fw-bold me-2"><i class="bi bi-bank me-1"></i> PAYOUT STATEMENT</span>
                <strong class="text-navy">Advisor Bank Withdrawal & Payout History</strong>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= url('/advisor/wallet/export-payouts?format=csv') ?>" class="btn btn-outline-success btn-sm fw-bold">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel / CSV
                </a>
                <button onclick="window.print()" class="btn btn-primary btn-sm fw-bold px-3">
                    <i class="bi bi-printer-fill me-1"></i> Print / Save PDF
                </button>
            </div>
        </div>
    </div>

    <div class="statement-card">
        <!-- HEADER -->
        <div class="row align-items-center border-bottom pb-3 mb-3">
            <div class="col-8">
                <div class="d-flex align-items-center gap-2">
                    <div style="background: linear-gradient(135deg, #16A34A 0%, #15803D 100%); width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #FFFFFF; font-weight: 900; font-size: 1.2rem;">
                        <i class="bi bi-bank2"></i>
                    </div>
                    <div>
                        <h5 class="font-heading fw-bold text-navy mb-0"><?= htmlspecialchars($companyName) ?></h5>
                        <div class="text-muted small" style="font-size: 9.5px;">Advisor Direct Bank Payout & NEFT/IMPS Transfer Statement</div>
                    </div>
                </div>
                <div class="mt-2 text-secondary" style="font-size: 9px; line-height: 1.3;">
                    <?= htmlspecialchars($companyAddress) ?> | <strong>GSTIN:</strong> <?= htmlspecialchars($companyGstin) ?>
                </div>
            </div>
            <div class="col-4 text-end">
                <div class="p-2 border rounded bg-light text-start d-inline-block">
                    <div class="text-uppercase fw-bold text-success" style="font-size: 8.5px;">STATEMENT TYPE</div>
                    <div class="small fw-bold text-navy">Bank Disbursal Log</div>
                    <div class="text-muted" style="font-size: 8.5px;">Date: <?= date('d/m/Y h:i A') ?></div>
                </div>
            </div>
        </div>

        <!-- ADVISOR INFO -->
        <div class="row g-3 mb-3">
            <div class="col-6">
                <div class="p-2 border rounded bg-light">
                    <div class="text-uppercase fw-bold text-secondary mb-1" style="font-size: 8.5px;">Advisor Partner Profile:</div>
                    <h6 class="fw-bold text-navy mb-1"><?= htmlspecialchars($advName) ?></h6>
                    <div style="font-size: 9.5px; line-height: 1.4;">
                        <strong>Advisor Code:</strong> <span class="badge bg-dark font-monospace"><?= htmlspecialchars($advCode) ?></span> | 
                        <strong>Mobile:</strong> <?= htmlspecialchars($advMobile) ?><br>
                        <strong>District:</strong> <?= htmlspecialchars($advDistrict) ?>, Odisha
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="p-2 border rounded bg-light">
                    <div class="text-uppercase fw-bold text-secondary mb-1" style="font-size: 8.5px;">Registered Payout Bank Account:</div>
                    <div style="font-size: 9.5px; line-height: 1.4;">
                        <strong>Bank Name:</strong> <?= htmlspecialchars($advisor['bank_name'] ?? 'N/A') ?> (<?= htmlspecialchars($advisor['bank_branch'] ?? '') ?>)<br>
                        <strong>Account No:</strong> <span class="font-monospace fw-bold text-navy"><?= htmlspecialchars($advisor['account_number'] ?? 'N/A') ?></span><br>
                        <strong>IFSC Code:</strong> <span class="font-monospace text-primary"><?= htmlspecialchars($advisor['ifsc_code'] ?? 'N/A') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- WITHDRAWALS TABLE -->
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-hover w-100 mb-0">
                <thead class="table-light text-uppercase" style="font-size: 9px;">
                    <tr>
                        <th style="width: 15%;">Req Code</th>
                        <th style="width: 14%;">Date</th>
                        <th class="text-end" style="width: 12%;">Gross (₹)</th>
                        <th class="text-end" style="width: 10%;">TDS 5% (₹)</th>
                        <th class="text-end" style="width: 13%;">Net Payout (₹)</th>
                        <th style="width: 14%;">Status</th>
                        <th style="width: 22%;">Bank UTR / Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($withdrawals)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No bank payout requests found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($withdrawals as $w): ?>
                            <tr>
                                <td><span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($w['request_code']) ?></span></td>
                                <td class="text-muted"><?= date('d/m/Y h:i A', strtotime($w['requested_at'])) ?></td>
                                <td class="text-end font-monospace">₹<?= number_format((float)$w['amount'], 2) ?></td>
                                <td class="text-end font-monospace text-danger">- ₹<?= number_format((float)$w['tds_amount'], 2) ?></td>
                                <td class="text-end font-monospace fw-bold text-success">₹<?= number_format((float)$w['net_payable'], 2) ?></td>
                                <td>
                                    <?php if ($w['status'] === 'PAID' || $w['status'] === 'APPROVED'): ?>
                                        <span class="badge bg-success" style="font-size: 8px;">PAID / PROCESSED</span>
                                    <?php elseif ($w['status'] === 'REJECTED'): ?>
                                        <span class="badge bg-danger" style="font-size: 8px;">REJECTED</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark" style="font-size: 8px;">PENDING</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($w['utr_number'])): ?>
                                        <div class="fw-bold font-monospace text-success" style="font-size: 9px;">UTR: <?= htmlspecialchars($w['utr_number']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($w['admin_remarks'])): ?>
                                        <small class="text-muted"><?= htmlspecialchars($w['admin_remarks']) ?></small>
                                    <?php endif; ?>
                                    <?php if (empty($w['utr_number']) && empty($w['admin_remarks'])): ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="d-flex justify-content-between align-items-center pt-3 border-top text-muted" style="font-size: 8.5px;">
            <div>© <?= date('Y') ?> <?= htmlspecialchars($companyName) ?> — Electronic Payout Record.</div>
            <div>Automated NEFT / IMPS Banking System</div>
        </div>
    </div>

</body>
</html>
