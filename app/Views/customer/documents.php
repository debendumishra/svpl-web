<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Customer Documents Upload & Management View (Solar Luminary Design System)
 */
$title = "My Documents & Scheme Reports — SVPL";
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">KYC, PM Surya Ghar Reports & Agreement Locker</h3>
            <span class="badge bg-primary-subtle text-primary px-2 py-1" style="font-size: 0.72rem;">DISCOM & Scheme Hub</span>
        </div>
        <p class="text-secondary small mb-0">Official PM Surya Ghar sanction letters, feasibility reports, electricity bills, and signed consumer agreement</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalConsumerAgreement">
            <i class="bi bi-file-earmark-text me-1 text-primary"></i> View Signed Agreement (Annexure 2)
        </button>
        <a href="<?= url('/customer/quotation') ?>" class="btn btn-svpl-navy btn-sm shadow-sm">
            <i class="bi bi-file-earmark-pdf me-1 text-warning"></i> View Proposal
        </a>
    </div>
</div>

<!-- PM SURYA GHAR SCHEME AUTO-GENERATED DOCUMENTS SECTION -->
<div class="card card-svpl border-0 shadow-sm rounded-4 p-4 bg-white mb-4 animate-fade-in stagger-1">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="font-heading fw-bold mb-0 text-navy">
                <i class="bi bi-patch-check-fill text-success me-2"></i> PM Surya Ghar National Portal & Scheme Sanctions
            </h5>
            <span class="text-secondary small">Official reports auto-generated and uploaded by your assigned Back Office Executive</span>
        </div>
        <?php if (!empty($customer['pm_surya_ghar_id'])): ?>
            <span class="badge bg-success text-white font-monospace px-3 py-2">
                PM ID: <?= htmlspecialchars($customer['pm_surya_ghar_id']) ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="row g-3">
        <?php
        $pmDocsList = [
            'FEASIBILITY_REPORT' => ['title' => 'DISCOM Technical Feasibility Report', 'desc' => 'Technical approval for rooftop net-metering synchronization', 'icon' => 'bi-file-earmark-check-fill', 'color' => 'success'],
            'BANK_CONGRATULATION_LETTER' => ['title' => 'Bank Loan Sanction & Congratulation Letter', 'desc' => 'Official loan sanction letter from bank (Jan Samarth / SBI / etc.)', 'icon' => 'bi-bank2', 'color' => 'primary'],
            'PM_SURYA_GHAR_CONGRATS' => ['title' => 'PM Surya Ghar Congratulation Page', 'desc' => 'National Portal registration & subsidy qualification certificate', 'icon' => 'bi-award-fill', 'color' => 'warning'],
            'APPLICATION_ACKNOWLEDGEMENT' => ['title' => 'Acknowledgement of Application', 'desc' => 'Scheme enrollment application receipt & tracking number', 'icon' => 'bi-receipt-cutoff', 'color' => 'info'],
        ];

        foreach ($pmDocsList as $pmType => $pmInfo):
            $foundPmDoc = null;
            if (!empty($documents)) {
                foreach ($documents as $d) {
                    if (($d['document_type'] ?? '') === $pmType) {
                        $foundPmDoc = $d;
                        break;
                    }
                }
            }
        ?>
        <div class="col-md-6">
            <div class="p-3 bg-light rounded-3 border h-100 d-flex justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle p-2 bg-<?= $pmInfo['color'] ?>-subtle text-<?= $pmInfo['color'] ?> fs-4">
                        <i class="bi <?= $pmInfo['icon'] ?>"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-navy mb-0" style="font-size: 0.92rem;"><?= $pmInfo['title'] ?></div>
                        <div class="text-secondary small"><?= $pmInfo['desc'] ?></div>
                        <?php if ($foundPmDoc): ?>
                            <div class="mt-1">
                                <span class="badge bg-success-subtle text-success border border-success-subtle py-1" style="font-size: 0.72rem;">
                                    <i class="bi bi-check-circle-fill me-1"></i> Available • Uploaded <?= date('d M Y', strtotime($foundPmDoc['created_at'])) ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="mt-1">
                                <span class="badge bg-secondary-subtle text-secondary py-1" style="font-size: 0.72rem;">
                                    <i class="bi bi-hourglass-split me-1"></i> Awaiting BOE Upload
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <?php if ($foundPmDoc): ?>
                        <a href="<?= url('/document/download?file=' . urlencode($foundPmDoc['file_path'])) ?>" target="_blank" class="btn btn-primary btn-sm px-3 fw-bold shadow-sm text-nowrap">
                            <i class="bi bi-eye-fill me-1"></i> View / Download
                        </a>
                    <?php else: ?>
                        <button class="btn btn-outline-secondary btn-sm px-3 text-nowrap" disabled>
                            Under Process
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- PM Surya Ghar Model Draft Agreement (Annexure 2) Card -->
        <div class="col-12">
            <div class="p-3 rounded-3 border h-100 d-flex justify-content-between align-items-center flex-wrap gap-3" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-color: #86efac !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-2 bg-success text-white fs-4 shadow-sm" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-success mb-0" style="font-size: 0.95rem;">Model Draft Agreement between Consumer & Vendor (Annexure 2)</div>
                        <div class="text-secondary small">Legally binding 4-page PM Surya Ghar contract executed with your verified touch/digital E-Signature</div>
                        <div class="mt-1">
                            <span class="badge bg-success text-white py-1" style="font-size: 0.72rem;">
                                <i class="bi bi-patch-check-fill me-1"></i> E-Signed on <?= !empty($customer['agreement_accepted_at']) ? date('d M Y, h:i A', strtotime($customer['agreement_accepted_at'])) : 'Registration' ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success btn-sm px-3 fw-bold shadow-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#modalConsumerAgreement">
                        <i class="bi bi-eye-fill me-1"></i> View Agreement Modal
                    </button>
                    <a href="<?= url('/customer/agreement') ?>" target="_blank" class="btn btn-outline-success btn-sm px-3 fw-bold text-nowrap">
                        <i class="bi bi-printer-fill me-1"></i> Print / New Tab
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 animate-fade-in stagger-2">
    <!-- Left Column: Upload Form -->
    <div class="col-lg-5">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <h5 class="font-heading fw-bold mb-3 text-navy">Upload Additional KYC Document</h5>
            <form action="<?= url('/customer/upload-document') ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Document Category *</label>
                    <select name="document_type" class="form-select" required>
                        <option value="ELECTRICITY_BILL">Latest Electricity Bill (Mandatory for DISCOM)</option>
                        <option value="AADHAAR">Aadhaar Card (Front/Back)</option>
                        <option value="PASSPORT_PHOTO">Passport Size Photo</option>
                        <option value="PAN">PAN Card</option>
                        <option value="BANK_PASSBOOK">Bank Passbook / Cheque (For Subsidy DBT)</option>
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
            <h5 class="font-heading fw-bold mb-3 text-navy">All Uploaded KYC Documents</h5>
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
                                        <?php if (!empty($doc['remarks'])): ?>
                                            <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($doc['remarks']) ?></div>
                                        <?php endif; ?>
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
