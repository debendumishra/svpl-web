<?php
/**
 * Dhwajja Solar India Pvt. Ltd. / Surya Vistaara Pvt. Ltd.
 * Printable Advisor Commission Wallet & Ledger Statement
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
    <title>Commission Wallet Statement - <?= htmlspecialchars($advCode) ?></title>
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
                <span class="badge bg-primary fw-bold me-2"><i class="bi bi-file-earmark-pdf me-1"></i> WALLET STATEMENT</span>
                <strong class="text-navy">Advisor Commission Ledger Statement</strong>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= url('/advisor/wallet/export-ledger?format=csv') ?>" class="btn btn-outline-success btn-sm fw-bold">
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
                    <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900; font-size: 1.2rem;">
                        ☀
                    </div>
                    <div>
                        <h5 class="font-heading fw-bold text-navy mb-0"><?= htmlspecialchars($companyName) ?></h5>
                        <div class="text-muted small" style="font-size: 9.5px;">Advisor Partner Commission & Financial Ledger Statement</div>
                    </div>
                </div>
                <div class="mt-2 text-secondary" style="font-size: 9px; line-height: 1.3;">
                    <?= htmlspecialchars($companyAddress) ?> | <strong>GSTIN:</strong> <?= htmlspecialchars($companyGstin) ?>
                </div>
            </div>
            <div class="col-4 text-end">
                <div class="p-2 border rounded bg-light text-start d-inline-block">
                    <div class="text-uppercase fw-bold text-primary" style="font-size: 8.5px;">STATEMENT PERIOD</div>
                    <div class="small fw-bold text-navy">All Time (As of <?= date('d M Y') ?>)</div>
                    <div class="text-muted" style="font-size: 8.5px;">Generated: <?= date('d/m/Y h:i A') ?></div>
                </div>
            </div>
        </div>

        <!-- ADVISOR & WALLET SUMMARY -->
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
                    <div class="text-uppercase fw-bold text-secondary mb-1" style="font-size: 8.5px;">Financial Summary:</div>
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <span class="text-muted small" style="font-size: 8px;">Available Balance</span>
                            <div class="fw-bold text-success font-monospace" style="font-size: 11px;">₹<?= number_format((float)($wallet['balance'] ?? 0), 2) ?></div>
                        </div>
                        <div class="col-4">
                            <span class="text-muted small" style="font-size: 8px;">Total Earned</span>
                            <div class="fw-bold text-navy font-monospace" style="font-size: 11px;">₹<?= number_format((float)($wallet['total_earned'] ?? 0), 2) ?></div>
                        </div>
                        <div class="col-4">
                            <span class="text-muted small" style="font-size: 8px;">TDS (5%)</span>
                            <div class="fw-bold text-danger font-monospace" style="font-size: 11px;">₹<?= number_format((float)(($wallet['total_earned'] ?? 0) * 0.05), 2) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TRANSACTIONS TABLE -->
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-hover w-100 mb-0">
                <thead class="table-light text-uppercase" style="font-size: 9px;">
                    <tr>
                        <th style="width: 14%;">Date & Time</th>
                        <th style="width: 16%;">Txn Reference</th>
                        <th style="width: 12%;">Type</th>
                        <th style="width: 32%;">Description</th>
                        <th class="text-end" style="width: 13%;">Amount (₹)</th>
                        <th class="text-end" style="width: 13%;">Balance (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No ledger transactions found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $t): ?>
                            <?php 
                            $isCredit = ($t['transaction_type'] ?? '') === 'CREDIT';
                            ?>
                            <tr>
                                <td class="text-muted"><?= date('d/m/Y h:i A', strtotime($t['created_at'])) ?></td>
                                <td class="font-monospace text-dark fw-semibold"><?= htmlspecialchars($t['transaction_code'] ?? 'TXN-' . $t['id']) ?></td>
                                <td>
                                    <span class="badge <?= $isCredit ? 'bg-success' : 'bg-danger' ?>" style="font-size: 8px;">
                                        <?= htmlspecialchars($t['transaction_type']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="text-navy"><?= htmlspecialchars($t['description'] ?? 'Ledger entry') ?></div>
                                    <?php if (!empty($t['reference_type'])): ?>
                                        <small class="text-muted" style="font-size: 8px;">Ref: <?= htmlspecialchars($t['reference_type']) ?> #<?= htmlspecialchars($t['reference_id'] ?? '') ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end font-monospace fw-bold <?= $isCredit ? 'text-success' : 'text-danger' ?>">
                                    <?= $isCredit ? '+ ₹' . number_format((float)$t['amount'], 2) : '- ₹' . number_format((float)$t['amount'], 2) ?>
                                </td>
                                <td class="text-end font-monospace fw-bold text-navy">
                                    ₹<?= number_format((float)($t['balance_after'] ?? 0), 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="d-flex justify-content-between align-items-center pt-3 border-top text-muted" style="font-size: 8.5px;">
            <div>© <?= date('Y') ?> <?= htmlspecialchars($companyName) ?> — Computer Generated Statement.</div>
            <div>Statutory Compliance: Section 194H Indian Income Tax Act</div>
        </div>
    </div>

</body>
</html>
