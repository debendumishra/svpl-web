<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * 403 Forbidden View
 */
$title = "403 Access Denied — SVPL";
?>
<div class="container py-5 text-center">
    <div class="py-5">
        <h1 class="display-1 fw-bold text-danger mb-2">403</h1>
        <h3 class="fw-bold text-navy mb-3" style="color: #0B2545;">Access Restricted</h3>
        <p class="text-muted mb-4"><?= htmlspecialchars($message ?? 'You do not have permission to access this module.') ?></p>
        <a href="<?= url('/login') ?>" class="btn btn-svpl-navy">Sign In with Appropriate Role</a>
    </div>
</div>
