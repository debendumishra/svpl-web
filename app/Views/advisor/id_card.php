<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Official ID Card Preview & Print
 */
$title = "Official ID Card — SVPL Advisor";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">My Official Advisor Identity Card</h3>
        <p class="text-muted small mb-0">Authorized Solar Advisor for Dhwajja Solar India Pvt. Ltd. in Odisha</p>
    </div>
    <a href="<?= url('/print/id-card/' . $advisor['id']) ?>" target="_blank" class="btn btn-svpl-navy btn-sm">
        <i class="bi bi-printer me-1"></i> Print ID Card
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card card-svpl p-4 text-center bg-white shadow border-0" style="border-radius: 20px;">
            <div class="p-3 text-white rounded-3 mb-3" style="background: linear-gradient(135deg, #061528 0%, #0B2545 100%);">
                <div class="fw-bold fs-5 text-warning mb-1">☀ SURYA VISTAARA</div>
                <div class="small opacity-75">PM Surya Ghar Network | Odisha</div>
            </div>

            <div class="mb-3">
                <div class="rounded-circle bg-light border d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 100px; height: 100px; font-size: 2.5rem; color: #0B2545;">
                    <i class="bi bi-person-circle"></i>
                </div>
            </div>

            <h4 class="fw-bold mb-1" style="color: #0B2545;"><?= htmlspecialchars($advisor['first_name'] . ' ' . $advisor['last_name']) ?></h4>
            <span class="badge bg-warning text-dark px-3 py-1 mb-2">CERTIFIED SOLAR ADVISOR</span>
            
            <div class="text-muted small mb-3">
                <strong>ID:</strong> <code><?= htmlspecialchars($advisor['advisor_code']) ?></code><br>
                <strong>District:</strong> <?= htmlspecialchars($advisor['district'] . ' (' . $advisor['block'] . ')') ?><br>
                <strong>Mobile:</strong> <?= htmlspecialchars($advisor['mobile']) ?>
            </div>

            <div class="p-3 bg-light rounded-3 border d-inline-block mx-auto mb-2">
                <img src="<?= $qrUrl ?>" alt="Verification QR" style="width: 130px; height: 130px;">
                <div class="small text-muted mt-1" style="font-size: 0.7rem;">Scan to Authenticate Profile</div>
            </div>
        </div>
    </div>
</div>
