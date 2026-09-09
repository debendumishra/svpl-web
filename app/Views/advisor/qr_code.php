<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Referral QR Code Page
 */
$title = "Customer Referral QR Code — SVPL Advisor";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">My Customer Onboarding QR Code</h3>
        <p class="text-muted small mb-0">Share this QR code or link with Odisha households to auto-link leads to your referral</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-svpl p-4 p-md-5 text-center bg-white shadow-sm border-0" style="border-radius: 20px;">
            <div class="p-3 bg-light rounded-4 border d-inline-block mx-auto mb-4">
                <img src="<?= $qrUrl ?>" alt="Referral QR" class="img-fluid" style="width: 220px; height: 220px;">
            </div>

            <h4 class="fw-bold mb-2" style="color: #0B2545;">Scan to Apply for Solar</h4>
            <p class="text-muted small mb-4">Households scanning this QR will automatically register with your referral code <strong><?= htmlspecialchars($advisor['referral_code']) ?></strong>.</p>

            <div class="input-group mb-3">
                <input type="text" id="refLinkInput" class="form-control" value="<?= htmlspecialchars($refUrl) ?>" readonly>
                <button class="btn btn-svpl-navy" type="button" onclick="navigator.clipboard.writeText(document.getElementById('refLinkInput').value); alert('Referral link copied to clipboard!');">
                    <i class="bi bi-clipboard me-1"></i> Copy Link
                </button>
            </div>
        </div>
    </div>
</div>
