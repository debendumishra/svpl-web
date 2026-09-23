<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Advisor Portal - Manage & Replace Customer Documents
 */
$title = "KYC Documents: {$customer['customer_code']} — SVPL Advisor";
$documents = $documents ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 animate-fade-in">
    <div>
        <a href="<?= url('/advisor/customers/' . $customer['id'] . '/edit') ?>" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Edit Application
        </a>
        <h3 class="font-heading fw-bold mb-1 text-navy">
            <i class="bi bi-folder-check text-warning me-2"></i> Manage & Rectify KYC Documents
        </h3>
        <p class="text-secondary small mb-0">
            Customer Code: <strong class="font-monospace text-navy"><?= htmlspecialchars($customer['customer_code']) ?></strong> | 
            Applicant: <strong class="text-navy"><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></strong>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/advisor/customers') ?>" class="btn btn-light border btn-sm">
            <i class="bi bi-people me-1"></i> All Customers
        </a>
    </div>
</div>

<?php if (!empty($successMsg)): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($successMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($errorMsg)): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($errorMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- CURRENT DOCUMENTS TABLE -->
<div class="card card-svpl border-0 shadow-sm p-4 mb-4 bg-white rounded-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="font-heading fw-bold text-navy mb-0">
            <i class="bi bi-files text-primary me-2"></i> Uploaded KYC & Site Documents (<?= count($documents) ?>)
        </h5>
        <button type="button" class="btn btn-svpl-solar btn-sm fw-bold shadow-sm" data-bs-toggle="collapse" data-bs-target="#uploadDocBox">
            <i class="bi bi-plus-circle-fill me-1"></i> Upload New Document
        </button>
    </div>

    <!-- COLLAPSIBLE UPLOAD NEW DOCUMENT FORM -->
    <div class="collapse mb-4" id="uploadDocBox">
        <div class="p-4 bg-light rounded-3 border">
            <h6 class="fw-bold text-navy mb-3"><i class="bi bi-cloud-arrow-up-fill text-primary me-1"></i> Upload New Customer Document</h6>
            <form action="<?= url('/advisor/customers/' . $customer['id'] . '/documents/upload') ?>" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-navy">Document Type <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            <option value="ELECTRICITY_BILL">Latest Electricity Bill</option>
                            <option value="AADHAAR_FRONT">Aadhaar Card (Front)</option>
                            <option value="AADHAAR_BACK">Aadhaar Card (Back)</option>
                            <option value="BANK_PASSBOOK">Bank Passbook / Cheque</option>
                            <option value="LAND_ROR">Land Record / RoR / House Tax</option>
                            <option value="PASSPORT_PHOTO">Customer Passport Photo</option>
                            <option value="ROOFTOP_PHOTO">Rooftop Area Photo</option>
                            <option value="SITE_PHOTO">Installation Site Photo</option>
                            <option value="OTHER">Other Supporting Document</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-navy">Document Title</label>
                        <input type="text" name="document_title" class="form-control" placeholder="e.g. June 2026 TPCODL Bill">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-navy">Select File (PDF, JPG, PNG) <span class="text-danger">*</span></label>
                        <input type="file" name="document_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.webp" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small fw-bold text-navy">Notes / Remarks for BOE</label>
                        <input type="text" name="remarks" class="form-control" placeholder="Optional notes for verification officer...">
                    </div>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-outline-secondary btn-sm me-2" data-bs-toggle="collapse" data-bs-target="#uploadDocBox">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">Upload & Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- DOCUMENTS LIST TABLE -->
    <div class="table-responsive">
        <table class="table table-hover align-middle small mb-0">
            <thead class="table-light text-secondary text-uppercase" style="font-size: 0.75rem;">
                <tr>
                    <th>#</th>
                    <th>Document Type / Title</th>
                    <th>Status</th>
                    <th>BOE Review & Remarks</th>
                    <th>Uploaded Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($documents)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-folder-x fs-3 d-block mb-1"></i>
                            No documents uploaded yet for this customer application.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($documents as $i => $doc): ?>
                        <?php
                        $status = $doc['status'] ?? 'Uploaded';
                        $statusBadge = 'bg-secondary';
                        if ($status === 'Verified') $statusBadge = 'bg-success';
                        elseif ($status === 'Action Required') $statusBadge = 'bg-warning text-dark fw-bold';
                        elseif ($status === 'Rejected') $statusBadge = 'bg-danger fw-bold';
                        elseif ($status === 'Uploaded') $statusBadge = 'bg-primary';
                        ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <strong class="text-navy"><?= htmlspecialchars($doc['document_title'] ?: $doc['document_type']) ?></strong>
                                <div class="text-muted" style="font-size: 0.72rem;"><?= htmlspecialchars($doc['document_type']) ?></div>
                            </td>
                            <td>
                                <span class="badge <?= $statusBadge ?> px-2 py-1"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($doc['remarks'])): ?>
                                    <div class="text-danger fw-semibold" style="font-size: 0.8rem;">
                                        <i class="bi bi-chat-square-text-fill me-1"></i> <?= htmlspecialchars($doc['remarks']) ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted">
                                <?= !empty($doc['created_at']) ? date('d M Y, h:i A', strtotime($doc['created_at'])) : '—' ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="<?= asset($doc['file_path']) ?>" target="_blank" class="btn btn-light btn-sm border" title="View Document">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <button type="button" class="btn btn-outline-warning btn-sm text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#replaceDocModal_<?= $doc['id'] ?>" title="Replace with Corrected Document">
                                        <i class="bi bi-arrow-repeat"></i> Replace
                                    </button>
                                </div>

                                <!-- REPLACE DOCUMENT MODAL -->
                                <div class="modal fade" id="replaceDocModal_<?= $doc['id'] ?>" tabindex="-1" aria-labelledby="replaceDocModalLabel_<?= $doc['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <div class="modal-header bg-warning-subtle">
                                                <h6 class="modal-title font-heading fw-bold text-navy" id="replaceDocModalLabel_<?= $doc['id'] ?>">
                                                    <i class="bi bi-arrow-repeat me-1"></i> Replace Document: <?= htmlspecialchars($doc['document_title']) ?>
                                                </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="<?= url('/advisor/customers/' . $customer['id'] . '/documents/' . $doc['id'] . '/replace') ?>" method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <?php if (!empty($doc['remarks'])): ?>
                                                        <div class="alert alert-warning p-2 small mb-3">
                                                            <strong>BOE Issue Raised:</strong> <?= htmlspecialchars($doc['remarks']) ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-navy">Select Corrected Document File <span class="text-danger">*</span></label>
                                                        <input type="file" name="document_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.webp" required>
                                                        <div class="form-text small">Accepted formats: JPG, PNG, WEBP, PDF (Max 10 MB).</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-navy">Rectification Notes for BOE</label>
                                                        <input type="text" name="remarks" class="form-control" placeholder="e.g. Re-uploaded clear electricity bill with consumer number visible..." value="Rectified and re-uploaded by Sponsor Advisor">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-warning text-dark fw-bold btn-sm px-3">
                                                        <i class="bi bi-cloud-upload-fill me-1"></i> Upload Replacement
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
