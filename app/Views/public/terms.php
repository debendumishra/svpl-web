<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Terms & Conditions Page
 */
$title = "Terms & Conditions — " . company_short_name();
?>
<div class="container py-5">
    <div class="card card-svpl p-4 p-md-5 bg-white border-0 shadow-sm">
        <h2 class="fw-bold mb-4" style="color: #0B2545;">Terms & Conditions and Advisor Code of Conduct</h2>
        <div class="text-muted small leading-relaxed">
            <h5 class="fw-bold text-dark mt-3">1. Nature of Relationship</h5>
            <p><?= htmlspecialchars(company_name()) ?> (<?= htmlspecialchars(company_short_name()) ?>) operates as the authorized Corporate Promoter for <?= htmlspecialchars(company_promoter()) ?>. Advisors registered with <?= htmlspecialchars(company_short_name()) ?> are independent clean energy promoters and do not represent full-time employment unless explicitly appointed under contract.</p>

            <h5 class="fw-bold text-dark mt-3">2. 9-Level Compensation & Qualification</h5>
            <p>Commissions and bonuses are credited based on actual completed rooftop solar installations verified by DISCOM junior engineers. Newly enrolled advisors achieve full QUALIFIED status upon completing 3 direct customer projects.</p>

            <h5 class="fw-bold text-dark mt-3">3. Tax Deductions (TDS)</h5>
            <p>In accordance with Section 194H of the Indian Income Tax Act, TDS @ 5% (or applicable statutory rate) is deducted at source on all advisory commission payouts.</p>
        </div>
    </div>
</div>
