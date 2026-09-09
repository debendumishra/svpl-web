<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * QR Code Verification View (Advisors, Proposals, Receipts)
 */
$title = "Official Verification — Surya Vistaara";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card card-svpl p-4 p-md-5 text-center bg-white border-0 shadow-sm" style="border-radius: 16px;">
                <?php if ($record): ?>
                    <div class="text-success mb-3">
                        <i class="bi bi-patch-check-fill display-3"></i>
                    </div>
                    <h3 class="fw-bold text-navy" style="color: #0B2545;">Verified Official Record</h3>
                    <p class="text-muted small">This identity has been authenticated against the SVPL Central Database.</p>
                    
                    <div class="bg-light p-3 rounded-3 text-start mb-4 border">
                        <?php if ($type === 'ADVISOR'): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Advisor Name:</span>
                                <strong><?= htmlspecialchars($record['first_name'] . ' ' . $record['last_name']) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Advisor ID:</span>
                                <code><?= htmlspecialchars($record['advisor_code']) ?></code>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Assigned Region:</span>
                                <span><?= htmlspecialchars($record['district'] . ', ' . $record['block']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Current Status:</span>
                                <span class="badge bg-success"><?= htmlspecialchars($record['status']) ?></span>
                            </div>
                        <?php else: ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Customer Code:</span>
                                <strong><?= htmlspecialchars($record['customer_code']) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Status:</span>
                                <span class="badge bg-primary"><?= htmlspecialchars($record['status']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="text-danger mb-3">
                        <i class="bi bi-x-circle-fill display-3"></i>
                    </div>
                    <h3 class="fw-bold text-danger">Verification Failed</h3>
                    <p class="text-muted">No authentic record was found matching the scanned QR code parameter: <code><?= htmlspecialchars($code) ?></code></p>
                <?php endif; ?>

                <a href="<?= url('/') ?>" class="btn btn-svpl-navy mt-2">
                    <i class="bi bi-house-door me-1"></i> Return to Homepage
                </a>
            </div>
        </div>
    </div>
</div>
