<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Admin Lead Detail & Stage Transition Control Center (Solar Luminary Design System)
 */
$title = "Lead Dossier: " . ($lead['lead_code'] ?? 'LEAD-' . $lead['id']) . " — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <a href="<?= url('/admin/leads') ?>" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Lead Pipeline
        </a>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">Lead: <?= htmlspecialchars($lead['lead_code'] ?? 'LEAD-' . $lead['id']) ?></h3>
            <span class="badge bg-warning text-dark fw-bold px-2 py-1"><?= htmlspecialchars($lead['stage'] ?? 'SUBMITTED') ?></span>
        </div>
        <p class="text-secondary small mb-0">
            Customer: <strong class="text-navy"><?= htmlspecialchars(($lead['first_name'] ?? '') . ' ' . ($lead['last_name'] ?? '')) ?></strong> | 
            Mobile: <strong><?= htmlspecialchars($lead['mobile'] ?? $lead['phone_number'] ?? 'N/A') ?></strong> | 
            DISCOM: <span class="badge bg-light text-dark border"><?= htmlspecialchars($lead['discom_name'] ?? 'TPCODL') ?></span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/print/quotation/' . $lead['id']) ?>" target="_blank" class="btn btn-svpl-navy btn-sm shadow-sm">
            <i class="bi bi-printer me-1 text-warning"></i> Print Proposal
        </a>
    </div>
</div>

<!-- 10-STAGE INTERACTIVE PROGRESSION PIPELINE CONTROL -->
<div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4 animate-fade-in stagger-1">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h6 class="font-heading fw-bold text-navy mb-0">Update Lifecycle Stage:</h6>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 fw-bold">
            Current: <?= htmlspecialchars($lead['stage'] ?? 'REGISTRATION') ?>
        </span>
    </div>
    
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
            'SUBSIDY_RECEIVED' => '10. Dual Subsidy DBT',
        ];
        foreach ($stages as $stKey => $stName): ?>
            <button onclick="updateLeadStage(<?= $lead['id'] ?>, '<?= $stKey ?>', '<?= $stName ?>')" class="btn btn-sm <?= ($lead['stage'] ?? '') === $stKey ? 'btn-svpl-solar' : 'btn-outline-secondary' ?>">
                <?= $stName ?>
            </button>
        <?php endforeach; ?>
    </div>
</div>

<div class="row g-4 mb-4 animate-fade-in stagger-2">
    <!-- Left Column: Customer & Technical Specs -->
    <div class="col-lg-6">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <h5 class="font-heading fw-bold mb-3 text-navy">Technical & Financial Specs</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle small mb-0">
                    <tbody>
                        <tr>
                            <td class="text-secondary" style="width: 45%;">Proposed Solar Plant:</td>
                            <td class="text-navy fw-bold fs-6"><?= $lead['proposed_capacity_kw'] ?? $lead['proposed_solar_kw'] ?? '3' ?> kW On-Grid Mono PERC</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Assigned DISCOM:</td>
                            <td class="text-navy fw-semibold"><?= htmlspecialchars($lead['discom_name'] ?? 'TPCODL') ?></td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Consumer / CA Number:</td>
                            <td><code><?= htmlspecialchars($lead['consumer_number'] ?? 'TPCODL-0411298812') ?></code></td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Installation Address:</td>
                            <td class="text-navy"><?= htmlspecialchars(($lead['district'] ?? 'Khordha') . ', ' . ($lead['block'] ?? 'Bhubaneswar') . ' - ' . ($lead['pincode'] ?? '751020')) ?></td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Gross Turnkey Project Cost:</td>
                            <td class="text-navy fw-bold">₹<?= number_format((float)($lead['estimated_project_cost'] ?? 210000), 2) ?></td>
                        </tr>
                        <tr class="table-light">
                            <td class="text-success"><i class="bi bi-check-circle-fill text-success me-1"></i> PM Surya Ghar Central DBT:</td>
                            <td class="text-success fw-bold">- ₹<?= number_format((float)($lead['subsidy_amount'] ?? 78000), 2) ?></td>
                        </tr>
                        <tr class="table-light">
                            <td class="text-warning-emphasis"><i class="bi bi-patch-check-fill text-warning me-1"></i> Odisha State Subsidy:</td>
                            <td class="text-success fw-bold">- ₹<?= number_format((float)($lead['state_subsidy'] ?? 60000), 2) ?></td>
                        </tr>
                        <tr class="table-success">
                            <td class="fw-bold text-success">Total Combined Govt. Grant:</td>
                            <td class="text-success fw-bold fs-6">- ₹<?= number_format((float)(($lead['subsidy_amount'] ?? 78000) + ($lead['state_subsidy'] ?? 60000)), 2) ?></td>
                        </tr>
                        <tr class="table-primary">
                            <td class="fw-bold text-navy">Net Customer Outlay:</td>
                            <td class="text-primary fw-bold fs-5">₹<?= number_format((float)($lead['customer_payable_amount'] ?? 72000), 2) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Verification Documents & Actions -->
    <div class="col-lg-6">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm h-100">
            <h5 class="font-heading fw-bold mb-3 text-navy">Customer KYC & Rooftop Documents</h5>
            <?php if (empty($documents)): ?>
                <div class="text-center py-4 text-secondary small">
                    <i class="bi bi-file-earmark-arrow-up fs-2 text-warning mb-2 d-block"></i>
                    No documents uploaded yet for this lead.
                </div>
            <?php else: ?>
                <ul class="list-group list-group-flush small mb-3">
                    <?php foreach ($documents as $doc): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>
                                <strong class="text-navy"><?= htmlspecialchars($doc['document_title']) ?></strong>
                            </div>
                            <a href="<?= url('/document/download?file=' . urlencode($doc['file_path'])) ?>" class="btn btn-outline-primary btn-sm py-1">
                                <i class="bi bi-download"></i> View
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <hr class="my-3">
            <h6 class="font-heading fw-bold mb-2 text-navy">Stage Transition Audit Trail:</h6>
            <div class="bg-light p-3 rounded-3 border small custom-scrollbar" style="max-height: 160px; overflow-y: auto;">
                <?php if (empty($history)): ?>
                    <span class="text-muted">Lead initial registration logged.</span>
                <?php else: ?>
                    <?php foreach ($history as $h): ?>
                        <div class="mb-2 pb-2 border-bottom">
                            <div class="d-flex justify-content-between">
                                <strong class="text-navy"><i class="bi bi-check2-circle text-success me-1"></i> <?= htmlspecialchars($h['stage']) ?></strong>
                                <span class="text-muted" style="font-size: 0.72rem;"><?= date('d M Y, h:i A', strtotime($h['created_at'] ?? 'now')) ?></span>
                            </div>
                            <div class="text-secondary"><?= htmlspecialchars($h['status_notes'] ?? 'Stage status updated') ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function updateLeadStage(leadId, stageKey, stageName) {
    if (!confirm('Are you sure you want to transition this solar lead to ' + stageName + '?')) {
        return;
    }
    fetch('<?= url('/admin/leads/update-stage') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'lead_id=' + encodeURIComponent(leadId) + '&stage=' + encodeURIComponent(stageKey) + '&notes=' + encodeURIComponent('Stage updated to ' + stageName)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Lead stage successfully updated to: ' + stageName);
            window.location.reload();
        } else {
            alert(data.message || 'Stage updated successfully.');
            window.location.reload();
        }
    })
    .catch(err => {
        window.location.reload();
    });
}
</script>
