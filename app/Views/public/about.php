<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * About Us Page
 */
$title = "About Us — " . company_name();
?>

<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2">ABOUT <?= htmlspecialchars(strtoupper(company_short_name())) ?></span>
                <h1 class="fw-bold mb-3" style="color: #0B2545;">Lighting up Every Rooftop Across Odisha</h1>
                <p class="text-muted leading-relaxed">
                    <strong><?= htmlspecialchars(company_name()) ?> (<?= htmlspecialchars(company_short_name()) ?>)</strong> is a premier clean-energy promoter company incorporated in Bhubaneswar, Odisha. Operating as the dedicated corporate promoter for <strong><?= htmlspecialchars(company_promoter()) ?></strong>, we are on a mission to democratize renewable energy under the Government of India's flagship <em>PM Surya Ghar: Muft Bijli Yojana</em>.
                </p>
                <p class="text-muted">
                    We bridge the gap between technical rooftop solar engineering and grassroots rural/urban communities by training, certifying, and empowering thousands of local Solar Advisors.
                </p>
            </div>
            <div class="col-lg-6 text-center">
                <div class="p-4 bg-light rounded-4 border">
                    <?php if (company_logo_url()): ?>
                        <div class="mb-3">
                            <img src="<?= htmlspecialchars(company_logo_url()) ?>" alt="Company Logo" style="max-height: 60px; max-width: 200px; object-fit: contain;">
                        </div>
                    <?php endif; ?>
                    <h4 class="fw-bold mb-3" style="color: #0B2545;">Corporate Promoter Identity</h4>
                    <p class="mb-2"><strong>Company:</strong> <?= htmlspecialchars(company_name()) ?> (<?= htmlspecialchars(company_short_name()) ?>)</p>
                    <p class="mb-2"><strong>Promoter For:</strong> <?= htmlspecialchars(company_promoter()) ?></p>
                    <p class="mb-2"><strong>Operational Scope:</strong> All 30 Districts, Odisha</p>
                    <p class="mb-0"><strong>Central Office:</strong> <?= htmlspecialchars(company_address()) ?></p>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-md-4">
                <div class="stat-card h-100 p-4">
                    <div class="stat-icon bg-warning-subtle text-warning mb-3"><i class="bi bi-eye-fill"></i></div>
                    <h5 class="fw-bold" style="color: #0B2545;">Our Vision</h5>
                    <p class="text-muted small mb-0">To ensure 100,000+ homes and small businesses in Odisha transition to clean solar energy with zero monthly electricity bills by 2027.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card h-100 p-4">
                    <div class="stat-icon bg-success-subtle text-success mb-3"><i class="bi bi-bullseye"></i></div>
                    <h5 class="fw-bold" style="color: #0B2545;">Our Mission</h5>
                    <p class="text-muted small mb-0">To build the most transparent, reliable, and technologically advanced network of Solar Advisors backed by robust field surveys and prompt subsidy disbursement.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card h-100 p-4">
                    <div class="stat-icon bg-primary-subtle text-primary mb-3"><i class="bi bi-shield-check"></i></div>
                    <h5 class="fw-bold" style="color: #0B2545;">Our Core Values</h5>
                    <p class="text-muted small mb-0">Customer First, Total Financial Transparency, Multi-Tier Entrepreneurial Opportunity, and Highest Quality Tier-1 Solar Components.</p>
                </div>
            </div>
        </div>
    </div>
</section>
