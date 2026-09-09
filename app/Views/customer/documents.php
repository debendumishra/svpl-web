<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Customer Documents Upload & Management View
 */
$title = "My Documents — SVPL";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Upload KYC & Verification Documents</h3>
        <p class="text-muted small mb-0">Electricity bills, Aadhaar card, and rooftop photos for DISCOM synchronization</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm">
            <h5 class="fw-bold mb-3" style="color: #0B2545;">Upload New Document</h5>
            <form action="<?= url('/customer/upload-document') ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Select Document Type *</label>
                    <select name="document_type" class="form-select" required>
                        <option value="ELECTRICITY_BILL">Latest Electricity Bill (Mandatory)</option>
                        <option value="AADHAAR">Aadhaar Card (Front/Back)</option>
                        <option value="PAN">PAN Card</option>
                        <option value="BANK_PASSBOOK">Bank Passbook / Cancelled Cheque (For Subsidy DBT)</option>
                        <option value="ROOFTOP_PHOTO">Rooftop / House Photograph</option>
                        <option value="OTHER">Other Supporting Document</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Select File (PDF, JPG, PNG - Max 5MB) *</label>
                    <input type="file" name="file" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <button type="submit" class="btn btn-svpl-navy w-100 py-2">
                    <i class="bi bi-upload me-1"></i> Upload Document
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm">
            <h5 class="fw-bold mb-3" style="color: #0B2545;">Uploaded Document Registry</h5>
            <?php if (empty($documents)): ?>
                <div class="text-center py-4 text-muted small">No documents uploaded yet.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>Document Type</th>
                                <th>Upload Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $doc): ?>
                                <tr>
                                    <td>
                                        <i class="bi bi-file-earmark-pdf text-danger me-1"></i>
                                        <strong><?= htmlspecialchars($doc['document_title']) ?></strong>
                                    </td>
                                    <td><?= $doc['created_at'] ?></td>
                                    <td><span class="badge bg-success-subtle text-success border border-success-subtle"><?= htmlspecialchars($doc['status']) ?></span></td>
                                    <td>
                                        <a href="<?= url('/document/download?file=' . urlencode($doc['file_path'])) ?>" class="btn btn-outline-primary btn-sm py-0">
                                            Download
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
