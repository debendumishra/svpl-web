<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * 404 Not Found View
 */
$title = "404 Page Not Found — SVPL";
?>

<div class="container py-5 text-center">
    <div class="py-5">
        <h1 class="display-1 fw-bold text-muted mb-2">404</h1>
        <h3 class="fw-bold text-navy mb-3" style="color: #0B2545;">Page Not Found</h3>
        <p class="text-muted mb-4"><?= htmlspecialchars($message ?? 'The page you are looking for does not exist or has moved.') ?></p>
        <a href="<?= url('/') ?>" class="btn btn-svpl-navy">
            <i class="bi bi-house-door me-1"></i> Back to Homepage
        </a>
    </div>
</div>
