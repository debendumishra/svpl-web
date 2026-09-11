<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Customer Documents Upload & Management View (Solar Luminary Design System)
 */
$title = "My Documents — SVPL";
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">KYC & Verification Document Locker</h3>
            <span class="badge bg-primary-subtle text-primary px-2 py-1" style="font-size: 0.72rem;">DISCOM Sync Hub</span>
        </div>
        <p class="text-secondary small mb-0">Electricity bills, Aadhaar card, and rooftop photos for fast DISCOM net-metering synchronization</p>
    </div>
</div>

<div class="row g-4 animate-fade-in stagger-1">
    <!-- Left Column: Upload Form -->
    <div class="col-lg-5">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <h5 class="font-heading fw-bold mb-3 text-navy">Upload New Document</h5>
            <form action="<?= url('/customer/upload-document') ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Document Category *</label>
                    <select name="document_type" class="form-select" required>
                        <option value="PASSPORT_PHOTO">Passport Size Photo (Beneficiary Photo)</option>
                        <option value="ELECTRICITY_BILL">Latest Electricity Bill (Mandatory for DISCOM)</option>
                        <option value="AADHAAR">Aadhaar Card (Front/Back)</option>
                        <option value="PASSPORT_PHOTO">Passport Size Photo</option>
                        <option value="PAN">PAN Card</option>
                        <option value="BANK_PASSBOOK">Bank Passbook / Cheque (For ₹1.38L Subsidy DBT)</option>
                        <option value="ROOFTOP_PHOTO">Rooftop / House Camera Photograph</option>
                        <option value="OTHER">Other Supporting Documents</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Choose File / Snap Camera Photo (JPG, PNG, PDF) *</label>
                    <input type="file" name="file" class="form-control" required accept="image/*,.pdf" capture="environment">
                </div>
                <button type="submit" class="btn btn-svpl-solar w-100 py-2">
                    <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload & Submit Document
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: Document List -->
    <div class="col-lg-7">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <h5 class="font-heading fw-bold mb-3 text-navy">Verified Document Registry</h5>
            <?php if (empty($documents)): ?>
                <div class="text-center py-4 text-secondary small">
                    <i class="bi bi-file-earmark-arrow-up fs-2 text-warning mb-2 d-block"></i>
                    No documents uploaded yet. Upload your electricity bill and Aadhaar card above.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr class="text-secondary text-uppercase">
                                <th>Document Type</th>
                                <th>Uploaded Date</th>
                                <th>Verification Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $doc): ?>
                                <tr>
                                    <td>
                                        <i class="bi bi-file-earmark-check-fill text-primary me-2"></i>
                                        <strong class="text-navy"><?= htmlspecialchars($doc['document_title']) ?></strong>
                                    </td>
                                    <td class="text-secondary"><?= date('d M Y', strtotime($doc['created_at'] ?? 'now')) ?></td>
                                    <td><span class="badge bg-success-subtle text-success border border-success-subtle"><?= htmlspecialchars($doc['status']) ?></span></td>
                                    <td>
                                        <a href="<?= url('/document/download?file=' . urlencode($doc['file_path'])) ?>" target="_blank" class="btn btn-outline-primary btn-sm py-1">
                                            <i class="bi bi-eye-fill me-1"></i> View
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
