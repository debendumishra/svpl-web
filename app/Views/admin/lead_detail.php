<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Lead Detail & Stage Transition Control Center
 */
$title = "Lead: " . $lead['lead_code'] . " — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= url('/admin/leads') ?>" class="btn btn-outline-secondary btn-sm mb-2"><i class="bi bi-arrow-left"></i> Back to Pipeline</a>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Lead: <?= htmlspecialchars($lead['lead_code']) ?></h3>
        <p class="text-muted small mb-0">Customer: <strong><?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?></strong> | Mobile: <?= htmlspecialchars($lead['mobile']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/print/quotation/' . $lead['id']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-printer me-1"></i> Print Proposal
        </a>
    </div>
</div>

<!-- 10-Stage Visual Progression Pipeline Bar -->
<div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4">
    <h6 class="fw-bold text-navy mb-3" style="color: #0B2545;">Current Lifecycle Stage: <span class="badge bg-primary"><?= htmlspecialchars($lead['stage']) ?></span></h6>
    
    <div class="d-flex flex-wrap gap-2">
        <?php
        $stages = [
            'REGISTRATION' => '1. Registration',
            'DOCUMENTS' => '2. Documents',
            'GOVT_PORTAL' => '3. Govt Portal',
            'LOAN_APPLIED' => '4. Loan Applied',
            'LOAN_SANCTIONED' => '5. Loan Sanctioned',
            'INSTALLATION_COMMENCED' => '6. Installing',
            'INSTALLATION_COMPLETED' => '7. Completed',
            'JE_REPORT' => '8. JE Report',
            'SUBSIDY_APPLIED' => '9. Subsidy Applied',
            'SUBSIDY_RECEIVED' => '10. Subsidy Received',
        ];
        foreach ($stages as $stKey => $stName): ?>
            <button onclick="updateLeadStage(<?= $lead['id'] ?>, '<?= $stKey ?>', '<?= $stName ?>')" class="btn btn-sm <?= $lead['stage'] === $stKey ? 'btn-success fw-bold' : 'btn-outline-secondary' ?>">
                <?= $stName ?>
            </button>
        <?php endforeach; ?>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left Column: Customer & Technical Specs -->
    <div class="col-lg-6">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <h5 class="fw-bold mb-3" style="color: #0B2545;">Technical & Installation Specs</h5>
            <table class="table table-bordered small">
                <tr>
                    <td class="text-muted" style="width: 40%;">Proposed Capacity:</td>
                    <td><strong><?= $lead['proposed_capacity_kw'] ?> kW</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">DISCOM:</td>
                    <td><?= htmlspecialchars($lead['discom_name'] ?? 'TPCODL') ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Consumer Number:</td>
                    <td><code><?= htmlspecialchars($lead['consumer_number'] ?? 'N/A') ?></code></td>
                </tr>
                <tr>
                    <td class="text-muted">Installation Address:</td>
                    <td><?= htmlspecialchars($lead['district'] . ', ' . $lead['block'] . ' - ' . $lead['pincode']) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Estimated Benchmark Cost:</td>
                    <td>₹<?= number_format((float)$lead['estimated_project_cost'], 2) ?></td>
                </tr>
                <tr>
                    <td class="text-muted"><i class="bi bi-check-circle-fill text-success me-1"></i> Central DBT Subsidy:</td>
                    <td class="text-success fw-bold">- ₹<?= number_format((float)($lead['subsidy_amount'] ?? 78000), 2) ?></td>
                </tr>
                <tr>
                    <td class="text-muted"><i class="bi bi-patch-check-fill text-warning me-1"></i> Odisha State Subsidy:</td>
                    <td class="text-success fw-bold">- ₹<?= number_format((float)($lead['state_subsidy'] ?? 60000), 2) ?></td>
                </tr>
                <tr class="table-success small">
                    <td class="fw-bold text-success">Total Govt. Subsidy:</td>
                    <td class="text-success fw-bold">- ₹<?= number_format((float)(($lead['subsidy_amount'] ?? 78000) + ($lead['state_subsidy'] ?? 60000)), 2) ?></td>
                </tr>
                <tr>
                    <td class="text-muted fw-bold">Net Customer Cost:</td>
                    <td class="text-primary fw-bold">₹<?= number_format((float)$lead['customer_payable_amount'], 2) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Right Column: Verification Documents & Actions -->
    <div class="col-lg-6">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <h5 class="fw-bold mb-3" style="color: #0B2545;">Customer KYC & Rooftop Documents</h5>
            <?php if (empty($documents)): ?>
                <p class="text-muted small">No documents uploaded yet for this lead.</p>
            <?php else: ?>
                <ul class="list-group list-group-flush small">
                    <?php foreach ($documents as $doc): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <i class="bi bi-file-earmark-pdf text-danger me-1"></i>
                                <strong><?= htmlspecialchars($doc['document_title']) ?></strong>
                            </div>
                            <a href="<?= url('/document/download?file=' . urlencode($doc['file_path'])) ?>" class="btn btn-outline-primary btn-sm py-0">Download</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <hr class="my-3">
            <h6 class="fw-bold mb-2 text-dark">Stage History Log:</h6>
            <div class="bg-light p-2 rounded small" style="max-height: 120px; overflow-y: auto;">
                <?php foreach ($history as $h): ?>
                    <div class="mb-1 text-muted">
                        <i class="bi bi-clock me-1"></i> <strong><?= htmlspecialchars($h['stage']) ?></strong>: <?= htmlspecialchars($h['status_notes']) ?> (<?= $h['created_at'] ?>)
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
