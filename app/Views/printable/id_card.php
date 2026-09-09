<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Printable Official Advisor ID Card
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official ID Card - <?= htmlspecialchars($advisor['advisor_code']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #F1F5F9; margin: 0; padding: 20px; text-align: center; }
        .id-card {
            width: 320px;
            height: 500px;
            background: #FFFFFF;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            border: 2px solid #0B2545;
            display: inline-block;
            text-align: center;
            position: relative;
        }
        .card-top {
            background: linear-gradient(135deg, #061528 0%, #0B2545 100%);
            color: #FFFFFF;
            padding: 16px 10px;
        }
        @media print {
            body { background: #FFFFFF; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i> Print ID Card</button>
    </div>

    <div class="id-card">
        <div class="card-top">
            <h5 class="fw-bold text-warning mb-0">SURYA VISTAARA PVT. LTD.</h5>
            <small style="font-size: 0.7rem; letter-spacing: 1px;">CORPORATE PROMOTER | DHWAJJA SOLAR</small>
        </div>

        <div class="p-3">
            <div class="rounded-circle bg-light border mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem; color: #0B2545;">
                ☀
            </div>
            <h5 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($advisor['first_name'] . ' ' . $advisor['last_name']) ?></h5>
            <span class="badge bg-warning text-dark my-1">CERTIFIED SOLAR ADVISOR</span>
            
            <table class="table table-sm table-borderless text-start small mt-2 mb-2" style="font-size: 0.75rem;">
                <tr>
                    <td class="text-muted">Advisor ID:</td>
                    <td><strong><?= htmlspecialchars($advisor['advisor_code']) ?></strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Assigned Area:</td>
                    <td><?= htmlspecialchars($advisor['district'] . ', ' . $advisor['block']) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Mobile:</td>
                    <td><?= htmlspecialchars($advisor['mobile']) ?></td>
                </tr>
            </table>

            <div class="border p-2 rounded bg-light d-inline-block">
                <img src="<?= $qrUrl ?>" alt="QR" style="width: 100px; height: 100px;">
                <div style="font-size: 0.65rem; color: #64748B;">Scan to Authenticate</div>
            </div>
        </div>
    </div>
</body>
</html>
