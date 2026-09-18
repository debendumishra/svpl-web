<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Field Engineer Official Printable ID Card
 */
$pageTitle = "Field Engineer ID Card — " . ($engineer['engineer_code'] ?? 'SVPL-ENG');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #e2e8f0;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .id-card-wrapper {
            width: 380px;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border: 2px solid #cbd5e1;
            position: relative;
        }
        .id-header {
            background: linear-gradient(135deg, #0f2d59 0%, #1e3a8a 100%);
            padding: 24px 20px 40px;
            text-align: center;
            color: #ffffff;
            position: relative;
        }
        .id-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 40px;
            background: #ffffff;
            border-radius: 50% 50% 0 0;
        }
        .id-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #f59e0b;
            color: #0f2d59;
            font-weight: 800;
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: -45px auto 10px;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            position: relative;
            z-index: 2;
        }
        .id-body {
            padding: 10px 24px 24px;
            text-align: center;
        }
        .id-badge {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 0.85rem;
            text-align: left;
        }
        .info-label {
            color: #64748b;
            font-weight: 500;
        }
        .info-val {
            color: #0f172a;
            font-weight: 700;
        }
        .id-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 12px;
            text-align: center;
            font-size: 0.72rem;
            color: #64748b;
        }
        @media print {
            body { background: transparent; padding: 0; }
            .no-print { display: none !important; }
            .id-card-wrapper { box-shadow: none; border: 1px solid #000; }
        }
    </style>
</head>
<body>

    <div>
        <div class="no-print text-center mb-3">
            <button onclick="window.print()" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">
                <i class="bi bi-printer-fill me-1"></i> Print / Save ID Card (PDF)
            </button>
            <a href="<?= url('/engineer/dashboard') ?>" class="btn btn-secondary btn-sm ms-2">Back</a>
        </div>

        <div class="id-card-wrapper">
            <div class="id-header">
                <div class="fw-bold tracking-wide" style="font-size: 1.15rem; letter-spacing: 0.5px;"><?= htmlspecialchars(company_name()) ?></div>
                <div class="small text-warning text-uppercase fw-bold" style="font-size: 0.72rem;">Certified Solar Installation Division</div>
            </div>

            <div class="id-avatar">
                <?= strtoupper(substr($engineer['full_name'] ?? 'SE', 0, 2)) ?>
            </div>

            <div class="id-body">
                <h5 class="fw-bold text-navy mb-0"><?= htmlspecialchars($engineer['full_name'] ?? 'Field Engineer') ?></h5>
                <div class="text-secondary small mb-2"><?= htmlspecialchars($engineer['designation'] ?? 'Field Solar Engineer') ?></div>

                <div class="id-badge font-monospace">
                    ID: <?= htmlspecialchars($engineer['engineer_code'] ?? 'SVPL-ENG-001') ?>
                </div>

                <div class="info-row">
                    <span class="info-label">Mobile:</span>
                    <span class="info-val font-monospace">+91 <?= htmlspecialchars($engineer['mobile'] ?? '') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Qualification:</span>
                    <span class="info-val"><?= htmlspecialchars($engineer['qualification'] ?? 'B.Tech / Solar PV') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Experience:</span>
                    <span class="info-val"><?= (int)($engineer['experience_years'] ?? 0) ?> Years</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Coverage:</span>
                    <span class="info-val text-truncate" style="max-width: 180px;"><?= htmlspecialchars($engineer['assigned_districts'] ?? 'Odisha') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Authorization:</span>
                    <span class="info-val text-success">Verified Solar Engineer</span>
                </div>
            </div>

            <div class="id-footer">
                <div>Surya Vistaara Private Limited • Odisha, India</div>
                <div class="font-monospace text-muted mt-1">PM Surya Ghar: Muft Bijli Yojana Partner</div>
            </div>
        </div>
    </div>

</body>
</html>
