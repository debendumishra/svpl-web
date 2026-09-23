<?php
/**
 * Dhwajja Solar India Pvt. Ltd. / Surya Vistaara Pvt. Ltd.
 * Printable Admin Bank Withdrawals & Payouts Report
 */

$companyName = function_exists('company_name') ? company_name() : "DHWAJJA SOLAR INDIA PVT. LTD.";
$companyGstin = "21AAMCD5948B1ZU";
$companyAddress = "MIG-84, Pokhariput, BDA Colony, Phase-1 Pokhariput, Bhubaneswar, Odisha - 751020";
$statusFilter = $statusFilter ?? 'ALL';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Withdrawals & Payouts Report — SVPL Admin</title>
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
        .report-card {
            background: #ffffff;
            max-width: 250mm;
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
            .report-card { width: 100% !important; max-width: 100% !important; box-shadow: none !important; padding: 5mm 8mm !important; }
            .no-print { display: none !important; }
            @page { size: A4 landscape; margin: 10mm; }
        }
    </style>
</head>
<body>

    <div class="container no-print mb-4 text-center" style="max-width: 250mm;">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 shadow-sm border">
            <div class="text-start">
                <span class="badge bg-primary fw-bold me-2"><i class="bi bi-bank me-1"></i> PAYOUT REPORT</span>
                <strong class="text-navy">Advisor Bank Withdrawals & Disbursal Master Log (Filter: <?= htmlspecialchars($statusFilter) ?>)</strong>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= url('/admin/withdrawals/export?format=csv&status=' . $statusFilter) ?>" class="btn btn-outline-success btn-sm fw-bold">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel / CSV
                </a>
                <button onclick="window.print()" class="btn btn-primary btn-sm fw-bold px-3">
                    <i class="bi bi-printer-fill me-1"></i> Print / Save PDF
                </button>
            </div>
        </div>
    </div>

    <div class="report-card">
        <!-- HEADER -->
        <div class="row align-items-center border-bottom pb-3 mb-3">
            <div class="col-8">
                <div class="d-flex align-items-center gap-2">
                    <div style="background: linear-gradient(135deg, #092C4C 0%, #0369A1 100%); width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #FFFFFF; font-weight: 900; font-size: 1.2rem;">
                        <i class="bi bi-bank"></i>
                    </div>
                    <div>
                        <h5 class="font-heading fw-bold text-navy mb-0"><?= htmlspecialchars($companyName) ?></h5>
                        <div class="text-muted small" style="font-size: 9.5px;">Master Banking Disbursal Register & 5% TDS Audit Statement</div>
                    </div>
                </div>
                <div class="mt-2 text-secondary" style="font-size: 9px; line-height: 1.3;">
                    <?= htmlspecialchars($companyAddress) ?> | <strong>GSTIN:</strong> <?= htmlspecialchars($companyGstin) ?>
                </div>
            </div>
            <div class="col-4 text-end">
                <div class="p-2 border rounded bg-light text-start d-inline-block">
                    <div class="text-uppercase fw-bold text-primary" style="font-size: 8.5px;">REPORT STATUS: <?= htmlspecialchars($statusFilter) ?></div>
                    <div class="small fw-bold text-navy">Total Records: <?= count($withdrawals) ?></div>
                    <div class="text-muted" style="font-size: 8.5px;">Generated: <?= date('d/m/Y h:i A') ?></div>
                </div>
            </div>
        </div>

        <!-- WITHDRAWALS TABLE -->
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-hover w-100 mb-0">
                <thead class="table-light text-uppercase" style="font-size: 9px;">
                    <tr>
                        <th style="width: 10%;">Req Code</th>
                        <th style="width: 16%;">Advisor Name & Code</th>
                        <th style="width: 11%;">Req Date</th>
                        <th class="text-end" style="width: 10%;">Gross (₹)</th>
                        <th class="text-end" style="width: 9%;">TDS 5% (₹)</th>
                        <th class="text-end" style="width: 11%;">Net Payout (₹)</th>
                        <th style="width: 18%;">Bank Details</th>
                        <th style="width: 8%;">Status</th>
                        <th style="width: 17%;">UTR / Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($withdrawals)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No withdrawal records matching criteria.</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $totalGross = 0; $totalTds = 0; $totalNet = 0;
                        foreach ($withdrawals as $w): 
                            $totalGross += (float)$w['amount'];
                            $totalTds += (float)$w['tds_amount'];
                            $totalNet += (float)$w['net_payable'];
                        ?>
                            <tr>
                                <td><span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($w['request_code']) ?></span></td>
                                <td>
                                    <strong class="text-navy"><?= htmlspecialchars($w['advisor_name'] ?? 'Advisor') ?></strong>
                                    <div class="text-muted font-monospace" style="font-size: 8px;"><?= htmlspecialchars($w['advisor_code'] ?? '') ?> (<?= htmlspecialchars($w['mobile'] ?? '') ?>)</div>
                                </td>
                                <td class="text-muted"><?= date('d/m/Y h:i A', strtotime($w['requested_at'])) ?></td>
                                <td class="text-end font-monospace">₹<?= number_format((float)$w['amount'], 2) ?></td>
                                <td class="text-end font-monospace text-danger">- ₹<?= number_format((float)$w['tds_amount'], 2) ?></td>
                                <td class="text-end font-monospace fw-bold text-success">₹<?= number_format((float)$w['net_payable'], 2) ?></td>
                                <td>
                                    <div class="fw-semibold" style="font-size: 8.5px;"><?= htmlspecialchars($w['bank_name'] ?? 'Bank') ?></div>
                                    <code style="font-size: 8px;"><?= htmlspecialchars($w['account_number'] ?? '') ?></code>
                                    <div class="text-muted" style="font-size: 7.5px;">IFSC: <?= htmlspecialchars($w['ifsc_code'] ?? '') ?></div>
                                </td>
                                <td>
                                    <?php if ($w['status'] === 'PAID' || $w['status'] === 'APPROVED'): ?>
                                        <span class="badge bg-success" style="font-size: 7.5px;">PAID</span>
                                    <?php elseif ($w['status'] === 'REJECTED'): ?>
                                        <span class="badge bg-danger" style="font-size: 7.5px;">REJECTED</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark" style="font-size: 7.5px;">PENDING</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($w['utr_number'])): ?>
                                        <div class="fw-bold font-monospace text-success" style="font-size: 8.5px;">UTR: <?= htmlspecialchars($w['utr_number']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($w['admin_remarks'])): ?>
                                        <small class="text-muted" style="font-size: 8px;"><?= htmlspecialchars($w['admin_remarks']) ?></small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-light fw-bold">
                            <td colspan="3" class="text-end text-uppercase">Grand Totals:</td>
                            <td class="text-end font-monospace">₹<?= number_format($totalGross, 2) ?></td>
                            <td class="text-end font-monospace text-danger">- ₹<?= number_format($totalTds, 2) ?></td>
                            <td class="text-end font-monospace text-success fs-6">₹<?= number_format($totalNet, 2) ?></td>
                            <td colspan="3"></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="d-flex justify-content-between align-items-center pt-3 border-top text-muted" style="font-size: 8.5px;">
            <div>© <?= date('Y') ?> <?= htmlspecialchars($companyName) ?> — Admin Financial Audit Document.</div>
            <div>Statutory Compliance: Section 194H Indian Income Tax Act</div>
        </div>
    </div>

</body>
</html>
