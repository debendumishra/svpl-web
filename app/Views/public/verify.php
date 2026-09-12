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
                    <p class="text-muted small">This identity has been authenticated against the <?= htmlspecialchars(company_name()) ?> Central Database.</p>
                    
                    <div class="bg-light p-3 p-md-4 rounded-3 text-start mb-4 border shadow-sm">
                        <?php if ($type === 'ADVISOR'): ?>
                            <div class="d-flex justify-content-between mb-2 pb-1 border-bottom">
                                <span class="text-muted">Advisor Name:</span>
                                <strong><?= htmlspecialchars(trim(($record['first_name'] ?? '') . ' ' . ($record['last_name'] ?? ''))) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2 pb-1 border-bottom">
                                <span class="text-muted">Advisor ID:</span>
                                <code class="fw-bold text-primary"><?= htmlspecialchars($record['advisor_code'] ?? '') ?></code>
                            </div>
                            <div class="d-flex justify-content-between mb-2 pb-1 border-bottom">
                                <span class="text-muted">Assigned Region:</span>
                                <span><?= htmlspecialchars(($record['block'] ? $record['block'] . ', ' : '') . ($record['district'] ?? '')) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 pb-1 border-bottom">
                                <span class="text-muted">Blood Group:</span>
                                <span class="badge bg-danger"><i class="bi bi-droplet-fill me-1"></i><?= htmlspecialchars($record['blood_group'] ?? 'O+ve') ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Current Status:</span>
                                <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i><?= htmlspecialchars($record['status'] ?? 'ACTIVE') ?></span>
                            </div>
                        <?php elseif ($type === 'STAFF' || $type === 'BOE'): ?>
                            <div class="d-flex justify-content-between mb-2 pb-1 border-bottom">
                                <span class="text-muted">Executive Name:</span>
                                <strong><?= htmlspecialchars($record['full_name'] ?? '') ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2 pb-1 border-bottom">
                                <span class="text-muted">Employee Code:</span>
                                <code class="fw-bold text-primary"><?= htmlspecialchars($record['employee_code'] ?? ('SVPL-BOE-' . $record['id'])) ?></code>
                            </div>
                            <div class="d-flex justify-content-between mb-2 pb-1 border-bottom">
                                <span class="text-muted">Designation:</span>
                                <span class="badge bg-info-subtle text-dark border"><?= htmlspecialchars($record['designation'] ?? 'Back Office Executive') ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 pb-1 border-bottom">
                                <span class="text-muted">Assigned Jurisdiction:</span>
                                <span class="fw-semibold text-dark"><?= htmlspecialchars($record['jurisdiction'] ?? 'Headquarters / All Odisha') ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 pb-1 border-bottom">
                                <span class="text-muted">Blood Group:</span>
                                <span class="badge bg-danger"><i class="bi bi-droplet-fill me-1"></i><?= htmlspecialchars($record['blood_group'] ?? 'O+ve') ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Employment Status:</span>
                                <span class="badge <?= !empty($record['is_active']) ? 'bg-success' : 'bg-danger' ?>">
                                    <i class="bi <?= !empty($record['is_active']) ? 'bi-patch-check-fill' : 'bi-x-circle-fill' ?> me-1"></i>
                                    <?= !empty($record['is_active']) ? 'AUTHENTICATED ACTIVE' : 'INACTIVE' ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="d-flex justify-content-between mb-2 pb-1 border-bottom">
                                <span class="text-muted">Record Code:</span>
                                <strong><?= htmlspecialchars($record['customer_code'] ?? $code) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Status:</span>
                                <span class="badge bg-primary"><?= htmlspecialchars($record['status'] ?? 'AUTHENTICATED') ?></span>
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
