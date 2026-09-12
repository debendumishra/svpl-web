<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Official Disclaimer View
 */
$title = "Disclaimer — " . company_name();
?>
<div class="container py-5">
    <div class="card card-svpl p-4 p-md-5 bg-white border-0 shadow-sm">
        <h2 class="fw-bold mb-4" style="color: #0B2545;">Statutory & Government Disclaimer</h2>
        <div class="text-muted small leading-relaxed">
            <p><strong><?= htmlspecialchars(company_name()) ?> (<?= htmlspecialchars(company_short_name()) ?>)</strong> is a registered private corporate entity in Odisha acting as the designated promoter for <strong><?= htmlspecialchars(company_promoter()) ?></strong>.</p>
            <p>PM Surya Ghar: Muft Bijli Yojana is a scheme of the Ministry of New and Renewable Energy (MNRE), Government of India. Subsidy amounts, eligibility guidelines, and technical parameters are governed by national regulations and DISCOM policies in Odisha. <?= htmlspecialchars(company_short_name()) ?> facilitates seamless customer onboarding, rooftop installation execution, and subsidy documentation.</p>
        </div>
    </div>
</div>
