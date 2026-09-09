<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Database Installer & Health Check View
 */
$title = "Database Setup & Health Check — SVPL";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-svpl p-4 p-md-5 bg-white border-0 shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="stat-icon bg-success-subtle text-success fs-3">
                        <i class="bi bi-database-check"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0" style="color: #0B2545;">Database Setup & Health Check</h3>
                        <p class="text-muted small mb-0">XAMPP / MySQL / SQLite Migration Status</p>
                    </div>
                </div>

                <?php if ($setupResult['status']): ?>
                    <div class="alert alert-success d-flex align-items-center mb-4">
                        <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                        <div>
                            <strong>Installation Successful!</strong> All tables and initial seed records are ready.
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger mb-4">
                        <strong>Installation Warning / Error:</strong>
                        <p class="small mb-0"><?= htmlspecialchars($setupResult['error'] ?? 'Unknown issue') ?></p>
                    </div>
                <?php endif; ?>

                <h6 class="fw-bold text-dark mb-2">Setup Execution Log:</h6>
                <div class="bg-light p-3 rounded-3 border mb-4 font-monospace small" style="max-height: 200px; overflow-y: auto;">
                    <?php foreach (($setupResult['messages'] ?? []) as $msg): ?>
                        <div><i class="bi bi-chevron-right text-muted me-1"></i> <?= htmlspecialchars($msg) ?></div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= url('/') ?>" class="btn btn-outline-secondary">Go to Homepage</a>
                    <a href="<?= url('/login') ?>" class="btn btn-svpl-navy">Sign In to Dashboard <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
