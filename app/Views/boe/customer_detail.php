<?php
$assignedBoeName = $customer['boe_name'] ?? null;
$assignedBoeCode = $customer['boe_code'] ?? null;
$assignedBoeMobile = $customer['boe_mobile'] ?? null;
$assignedBoeEmail = $customer['boe_email'] ?? null;
$assignedBoeDesg = $customer['boe_designation'] ?? 'Back Office Executive';

$stages = [
    'REGISTRATION' => 'Stage 1: Registration',
    'DOCUMENTS' => 'Stage 2: Documents Verification',
    'GOVT_PORTAL' => 'Stage 3: Govt Portal Submission',
    'LOAN_APPLIED' => 'Stage 4: Loan Applied',
    'LOAN_SANCTIONED' => 'Stage 5: Loan Sanctioned',
    'INSTALLATION_COMMENCED' => 'Stage 6: Installation Commenced',
    'INSTALLATION_COMPLETED' => 'Stage 7: Installation Completed',
    'JE_REPORT' => 'Stage 8: Joint Inspection (JE Report)',
    'SUBSIDY_APPLIED' => 'Stage 9: Subsidy Applied',
    'SUBSIDY_RECEIVED' => 'Stage 10: Subsidy Disbursed',
];

$requiredDocs = [
    'AADHAAR' => 'Aadhaar Card',
    'PAN' => 'PAN Card',
    'ELECTRICITY_BILL' => 'Electricity Bill',
    'ROOFTOP_PHOTO' => 'Rooftop Photo',
    'BANK_PASSBOOK' => 'Bank Passbook',
    'LAND_RECORD' => 'Land Record / Patta',
    'PASSPORT_PHOTO' => 'Passport Size Photo',
    'OTHER' => 'Other Supporting Documents',
];
?>

<?php if (!empty($successMsg)): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($successMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($errorMsg)): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($errorMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-3 g-md-4">
    <!-- Left Column: Customer Profile & Assignment -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-navy text-white py-3">
                <h6 class="m-0 font-outfit fw-bold"><i class="bi bi-person-vcard me-2 text-warning"></i> Customer Profile</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center border" style="width: 70px; height: 70px;">
                        <i class="bi bi-person-fill fs-1 text-secondary"></i>
                    </div>
                    <h5 class="mt-2 mb-0 font-outfit fw-bold"><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></h5>
                    <span class="badge bg-primary text-white mt-1"><?= htmlspecialchars($customer['customer_code']) ?></span>
                </div>

                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted"><i class="bi bi-telephone text-success me-1"></i> Mobile:</span>
                        <strong><?= htmlspecialchars($customer['mobile']) ?></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted"><i class="bi bi-envelope me-1"></i> Email:</span>
                        <span class="text-truncate ms-2" style="max-width: 160px;"><?= htmlspecialchars($customer['email'] ?? 'N/A') ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted"><i class="bi bi-geo-alt text-danger me-1"></i> District:</span>
                        <strong><?= htmlspecialchars($customer['district']) ?></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted"><i class="bi bi-building me-1"></i> DISCOM:</span>
                        <span><?= htmlspecialchars($customer['discom_name'] ?? 'TPCODL') ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted"><i class="bi bi-hash me-1"></i> Consumer No:</span>
                        <strong><?= htmlspecialchars($customer['consumer_number'] ?? 'N/A') ?></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted"><i class="bi bi-sun text-warning me-1"></i> Proposed Solar:</span>
                        <strong class="text-success"><?= htmlspecialchars($customer['proposed_solar_kw'] ?? 3.0) ?> kW</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted"><i class="bi bi-person-badge text-info me-1"></i> Advisor:</span>
                        <span class="text-end"><?= htmlspecialchars($customer['advisor_name'] ?? 'Direct Registration') ?> (<?= htmlspecialchars($customer['advisor_code'] ?? '-') ?>)</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Assigned BOE Card (Req 9 & 10) -->
        <div class="card border-0 shadow-sm rounded-3 border-start border-4 border-info mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-outfit fw-bold text-navy"><i class="bi bi-headset text-info me-2"></i> Assigned Back Office Executive</h6>
            </div>
            <div class="card-body">
                <?php if ($assignedBoeName): ?>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-info bg-opacity-10 text-info p-3">
                            <i class="bi bi-person-badge-fill fs-3"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="mb-0 fw-bold text-truncate"><?= htmlspecialchars($assignedBoeName) ?></h6>
                            <span class="badge bg-light text-dark border"><?= htmlspecialchars($assignedBoeCode) ?></span>
                            <div class="small text-muted mt-1"><?= htmlspecialchars($assignedBoeDesg) ?></div>
                            <div class="small text-success mt-1"><i class="bi bi-telephone-fill"></i> <?= htmlspecialchars($assignedBoeMobile ?? 'N/A') ?></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-3 text-muted">
                        <i class="bi bi-inbox fs-2 text-warning"></i>
                        <p class="small mb-2">This application is currently in the <strong>Stage 1 Shared Pool</strong>.</p>
                        <span class="badge bg-warning text-dark">Updating status will claim and assign to you</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Status Update, Document Management, Replacement & Audit Log -->
    <div class="col-12 col-lg-8">
        
        <!-- STAGE PROGRESSION FORM (Req 2 & 7) -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-outfit fw-bold text-navy"><i class="bi bi-sliders me-2 text-primary"></i> Application Pipeline Stage & Status Manager</h6>
            </div>
            <div class="card-body">
                <form action="<?= url('/boe/update-status') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">
                    <input type="hidden" name="lead_id" value="<?= $customer['lead_id'] ?? 0 ?>">

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold text-muted">Select Pipeline Stage</label>
                            <select name="stage" class="form-select" required>
                                <?php foreach ($stages as $key => $label): ?>
                                    <option value="<?= $key ?>" <?= ($customer['lead_stage'] ?? 'REGISTRATION') === $key ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold text-muted">Overall Status Label</label>
                            <input type="text" name="status" class="form-control" value="<?= htmlspecialchars($customer['lead_status'] ?? $customer['status'] ?? 'Documents Pending') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">Processing Remarks / Audit Notes</label>
                            <textarea name="remarks" class="form-control" rows="2" placeholder="Describe the reason for status change or stage progression..."></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary w-100 w-sm-auto px-4 fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> Update Stage & Log Audit Trail
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DOCUMENT MANAGER & REPLACEMENT SECTION (Req 4, 5 & 6) -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="m-0 font-outfit fw-bold text-navy"><i class="bi bi-file-earmark-check-fill me-2 text-success"></i> Document Checklist & Replacement Manager</h6>
                <button type="button" class="btn btn-sm btn-outline-warning text-dark w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#requestDocModal">
                    <i class="bi bi-send-fill me-1"></i> Request Doc from Advisor
                </button>
            </div>
            <div class="card-body">
                
                <!-- Upload / Add New Document Form -->
                <form action="<?= url('/boe/upload-document') ?>" method="POST" enctype="multipart/form-data" class="row g-2 align-items-end p-3 bg-light rounded-3 mb-4 border">
                    <?= csrf_field() ?>
                    <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-bold text-dark">Document Type</label>
                        <select name="document_type" class="form-select form-select-sm" required>
                            <?php foreach ($requiredDocs as $docKey => $docLabel): ?>
                                <option value="<?= $docKey ?>"><?= $docLabel ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-5">
                        <label class="form-label small fw-bold text-dark">Select File / Take Photo (JPG, PNG, PDF)</label>
                        <input type="file" name="document_file" class="form-control form-control-sm" accept="image/*,.pdf" capture="environment" required>
                    </div>

                    <div class="col-12 col-md-3">
                        <button type="submit" class="btn btn-sm btn-success w-100 fw-bold mt-2 mt-md-0">
                            <i class="bi bi-upload me-1"></i> Upload Document
                        </button>
                    </div>
                </form>

                <!-- Document Table with Replacement Actions (Req 5 & 6) -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Doc Type</th>
                                <th>File Name / Title</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($documents)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No documents uploaded yet for this customer.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($documents as $doc): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-dark text-warning border border-warning px-2 py-1"><i class="bi bi-file-earmark me-1"></i> <?= htmlspecialchars($doc['document_type']) ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= url('/document/download?file=' . urlencode($doc['file_path'])) ?>" target="_blank" class="fw-bold text-primary text-decoration-none">
                                            <?= htmlspecialchars($doc['document_title'] ?: basename($doc['file_path'])) ?>
                                        </a>
                                        <div class="small text-muted"><?= htmlspecialchars($doc['remarks'] ?? '') ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> <?= htmlspecialchars($doc['status']) ?></span>
                                    </td>
                                    <td><small class="text-muted"><?= htmlspecialchars(date('d M Y, h:i A', strtotime($doc['updated_at'] ?? $doc['created_at']))) ?></small></td>
                                    <td class="text-end">
                                        <a href="<?= url('/document/download?file=' . urlencode($doc['file_path'])) ?>" target="_blank" class="btn btn-sm btn-outline-secondary me-1">
                                            <i class="bi bi-eye"></i> View
                                        </a>

                                        <!-- Document Replacement Trigger (Req 5) -->
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#replaceModal_<?= $doc['id'] ?>">
                                            <i class="bi bi-arrow-repeat"></i> Replace
                                        </button>

                                        <!-- Replacement Modal -->
                                        <div class="modal fade" id="replaceModal_<?= $doc['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog text-start">
                                                <div class="modal-content">
                                                    <form action="<?= url('/boe/replace-document') ?>" method="POST" enctype="multipart/form-data">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="document_id" value="<?= $doc['id'] ?>">
                                                        <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">

                                                        <div class="modal-header bg-light">
                                                            <h6 class="modal-title font-outfit fw-bold"><i class="bi bi-arrow-repeat text-primary me-2"></i> Replace Document: <?= htmlspecialchars($doc['document_type']) ?></h6>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="small text-muted">Select a new file to replace the existing uploaded document for <strong><?= htmlspecialchars($doc['document_type']) ?></strong>.</p>
                                                             <div class="mb-3">
                                                                 <label class="form-label small fw-bold">New File / Snap Photo (JPG, PNG, PDF)</label>
                                                                 <input type="file" name="document_file" class="form-control" accept="image/*,.pdf" capture="environment" required>
                                                             </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">Reason / Note for Replacement</label>
                                                                <input type="text" name="remarks" class="form-control" placeholder="e.g. Replaced blurred Aadhaar photo with clear copy">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary btn-sm fw-bold">Replace File</button>
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
        </div>

        <!-- AUDIT TRAIL TIMELINE (Req 7) -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-outfit fw-bold text-navy"><i class="bi bi-journal-text me-2 text-danger"></i> Complete Status Change Audit Trail (Who Changed Status & Date/Time)</h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($auditHistory)): ?>
                    <div class="p-4 text-center text-muted">No status changes recorded in audit log yet.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Stage Transition</th>
                                    <th>Status Label</th>
                                    <th>Changed By User</th>
                                    <th>Designation / Role</th>
                                    <th>Audit Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($auditHistory as $log): ?>
                                <tr>
                                    <td><strong class="text-navy"><?= htmlspecialchars(date('d M Y, h:i:s A', strtotime($log['created_at']))) ?></strong></td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= htmlspecialchars($log['from_stage'] ?? 'START') ?></span>
                                        <i class="bi bi-arrow-right text-primary mx-1"></i>
                                        <span class="badge bg-primary text-white"><?= htmlspecialchars($log['to_stage']) ?></span>
                                    </td>
                                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($log['to_status'] ?? '-') ?></span></td>
                                    <td>
                                        <strong><?= htmlspecialchars($log['user_name'] ?? 'System') ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($log['user_code'] ?? 'SYSTEM') ?></small>
                                    </td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($log['user_designation'] ?? 'Staff') ?></span></td>
                                    <td><small class="text-muted"><?= htmlspecialchars($log['remarks'] ?? 'Status updated') ?></small></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- Modal: Request Missing Document from Advisor (Req 4) -->
<div class="modal fade" id="requestDocModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= url('/boe/request-document') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">

                <div class="modal-header bg-warning">
                    <h6 class="modal-title font-outfit fw-bold text-dark"><i class="bi bi-send-fill me-2"></i> Request Required Document from Advisor</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Missing Document Name</label>
                        <select name="missing_document" class="form-select" required>
                            <?php foreach ($requiredDocs as $docKey => $docLabel): ?>
                                <option value="<?= $docLabel ?>"><?= $docLabel ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Message / Instructions to Advisor</label>
                        <textarea name="message" class="form-control" rows="3" placeholder="Please upload clear front & back copy of Passport Size Photo / Electricity bill..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-bold">Send Request Notification</button>
                </div>
            </form>
        </div>
    </div>
</div>
