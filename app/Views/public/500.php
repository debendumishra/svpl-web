<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * 500 Server Error View
 */
$title = "500 Server Error — SVPL";
?>
<div class="container py-5 text-center">
    <div class="py-5">
        <h1 class="display-1 fw-bold text-danger mb-2">500</h1>
        <h3 class="fw-bold text-navy mb-3" style="color: #0B2545;">Internal Server Error</h3>
        <p class="text-muted mb-4"><?= htmlspecialchars($message ?? 'An unexpected system error occurred. Please contact administrator.') ?></p>
        <a href="<?= url('/') ?>" class="btn btn-svpl-navy">Return Home</a>
    </div>
</div>
