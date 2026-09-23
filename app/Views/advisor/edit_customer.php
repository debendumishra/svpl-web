<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Advisor Portal - Rectify / Edit Customer Application View
 */
$title = "Rectify Customer Application: {$customer['customer_code']} — SVPL Advisor";
$districts = $districts ?? \App\Models\Location::getDistricts();

$stageMap = [
    'REGISTRATION'              => '1. Lead Registered',
    'DOCUMENTS'                 => '2. Documents Verification',
    'GOVT_PORTAL'               => '3. Govt Portal Submission',
    'LOAN_APPLIED'              => '4. Bank Loan Applied',
    'LOAN_SANCTIONED'           => '5. Loan Sanctioned',
    'INSTRUMENT_DESPATCHED'     => '6. Instrument Despatched',
    'INSTALLATION_COMMENCED'    => '7. Installation Commenced',
    'INSTALLATION_COMPLETED'    => '8. Installation Completed',
    'JE_REPORT'                 => '9. DISCOM JE Inspection',
    'NET_METER'                 => '10. Net Meter Installed',
    'INTIMATION_TO_MMG'         => '11. Intimation to MMG',
    'MMG_METER_REPORT'          => '12. MMG Meter Report',
    'BANK_SECOND_INSTALLMENT'   => '13. Bank 2nd Installment',
    'SUBSIDY_APPLIED'           => '14. Subsidy Applied',
    'SUBSIDY_RECEIVED'          => '15. Subsidy Disbursed',
];
$currentStage = $customer['lead_stage'] ?? 'REGISTRATION';
$stageLabel = $stageMap[$currentStage] ?? $currentStage;
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 animate-fade-in">
    <div>
        <a href="<?= url('/advisor/customers') ?>" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Customers List
        </a>
        <h3 class="font-heading fw-bold mb-1 text-navy">
            <i class="bi bi-pencil-square text-warning me-2"></i> Rectify Customer Application
        </h3>
        <p class="text-secondary small mb-0">
            Customer Code: <strong class="font-monospace text-navy"><?= htmlspecialchars($customer['customer_code']) ?></strong> | 
            Applicant: <strong class="text-navy"><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></strong>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/advisor/customers/' . $customer['id'] . '/documents') ?>" class="btn btn-primary btn-sm fw-bold shadow-sm">
            <i class="bi bi-folder-check me-1"></i> Manage & Replace KYC Documents
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

<!-- CURRENT BOE STAGE & RECTIFICATION ALERTS -->
<div class="card card-svpl border-0 shadow-sm p-4 mb-4 bg-white rounded-3">
    <div class="row align-items-center g-3">
        <div class="col-md-7">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary px-2 py-1 font-monospace" style="font-size: 0.75rem;">STAGE: <?= htmlspecialchars($stageLabel) ?></span>
                <span class="badge <?= in_array($customer['lead_status'] ?? '', ['Rejected', 'Action Required', 'Documents Pending']) ? 'bg-danger' : 'bg-warning text-dark' ?> fw-semibold">
                    <?= htmlspecialchars($customer['lead_status'] ?? 'Processing') ?>
                </span>
            </div>
            <h5 class="fw-bold text-navy mb-1"><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></h5>
            <div class="small text-secondary">
                Assigned BOE Executive: <strong><?= htmlspecialchars($customer['boe_name'] ?? 'Processing Pool / Unassigned') ?></strong>
                <?php if (!empty($customer['boe_mobile'])): ?>
                    • <i class="bi bi-telephone-fill text-success"></i> <?= htmlspecialchars($customer['boe_mobile']) ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-5 text-md-end">
            <div class="p-3 bg-light rounded-3 border text-start">
                <div class="small text-muted fw-bold mb-1"><i class="bi bi-chat-left-dots-fill text-warning me-1"></i> Latest BOE Review & Remarks:</div>
                <div class="text-dark small" style="line-height: 1.4;">
                    <?php
                    $latestHistory = !empty($auditHistory[0]) ? $auditHistory[0] : null;
                    if (!empty($latestHistory['remarks'])) {
                        echo htmlspecialchars($latestHistory['remarks']);
                        echo '<div class="text-muted mt-1" style="font-size: 0.7rem;">— ' . htmlspecialchars($latestHistory['user_name'] ?? 'BOE') . ' (' . htmlspecialchars(date('d M Y, h:i A', strtotime($latestHistory['created_at']))) . ')</div>';
                    } else {
                        echo '<span class="text-muted italic">No specific issue remarks recorded yet. Update data below if needed.</span>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- EDIT CUSTOMER APPLICATION FORM -->
<form action="<?= url('/advisor/customers/' . $customer['id'] . '/edit') ?>" method="POST" class="animate-fade-in stagger-1">
    
    <!-- SECTION 1: APPLICANT PERSONAL DETAILS -->
    <div class="card card-svpl border-0 shadow-sm p-4 mb-4 bg-white rounded-3">
        <h5 class="font-heading fw-bold text-navy mb-3 pb-2 border-bottom">
            <i class="bi bi-person-lines-fill text-warning me-2"></i> 1. Applicant Personal Information
        </h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($customer['first_name'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($customer['last_name'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Father / Spouse Name</label>
                <input type="text" name="father_husband_name" class="form-control" value="<?= htmlspecialchars($customer['father_husband_name'] ?? ($customer['father_spouse_name'] ?? '')) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="<?= htmlspecialchars($customer['dob'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Mobile Number (Aadhaar Linked) <span class="text-danger">*</span></label>
                <input type="tel" name="mobile" class="form-control" value="<?= htmlspecialchars($customer['mobile'] ?? '') ?>" maxlength="10" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Alternate Mobile</label>
                <input type="tel" name="alt_mobile" class="form-control" value="<?= htmlspecialchars($customer['alt_mobile'] ?? '') ?>" maxlength="10">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Email Address</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email'] ?? '') ?>">
            </div>
        </div>
    </div>

    <!-- SECTION 2: INSTALLATION ADDRESS & DISCOM DETAILS -->
    <div class="card card-svpl border-0 shadow-sm p-4 mb-4 bg-white rounded-3">
        <h5 class="font-heading fw-bold text-navy mb-3 pb-2 border-bottom">
            <i class="bi bi-geo-alt-fill text-warning me-2"></i> 2. Installation Site & DISCOM Electricity Meter
        </h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">DISCOM Name <span class="text-danger">*</span></label>
                <select name="discom_name" class="form-select" required>
                    <option value="TPCODL" <?= ($customer['discom_name'] ?? '') === 'TPCODL' ? 'selected' : '' ?>>TPCODL (Central Odisha)</option>
                    <option value="TPNODL" <?= ($customer['discom_name'] ?? '') === 'TPNODL' ? 'selected' : '' ?>>TPNODL (Northern Odisha)</option>
                    <option value="TPSODL" <?= ($customer['discom_name'] ?? '') === 'TPSODL' ? 'selected' : '' ?>>TPSODL (Southern Odisha)</option>
                    <option value="TPWODL" <?= ($customer['discom_name'] ?? '') === 'TPWODL' ? 'selected' : '' ?>>TPWODL (Western Odisha)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">DISCOM Consumer Number <span class="text-danger">*</span></label>
                <input type="text" name="consumer_number" class="form-control font-monospace fw-bold" value="<?= htmlspecialchars($customer['consumer_number'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Sanctioned Load (kW)</label>
                <input type="number" step="0.5" name="sanctioned_load_kw" class="form-control" value="<?= htmlspecialchars($customer['sanctioned_load_kw'] ?? 2.0) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Proposed Solar Capacity (kW) <span class="text-danger">*</span></label>
                <select name="proposed_solar_kw" class="form-select fw-bold text-primary" required>
                    <option value="2.0" <?= (float)($customer['proposed_solar_kw'] ?? 0) == 2.0 ? 'selected' : '' ?>>2 kW (Indicative ₹1.60 Lakh • ₹1.10L Subsidy)</option>
                    <option value="3.0" <?= (float)($customer['proposed_solar_kw'] ?? 3.0) == 3.0 ? 'selected' : '' ?>>3 kW (Indicative ₹2.10 Lakh • ₹1.38L Subsidy)</option>
                    <option value="4.0" <?= (float)($customer['proposed_solar_kw'] ?? 0) == 4.0 ? 'selected' : '' ?>>4 kW (Indicative ₹2.60 Lakh • ₹1.38L Subsidy)</option>
                    <option value="5.0" <?= (float)($customer['proposed_solar_kw'] ?? 0) == 5.0 ? 'selected' : '' ?>>5 kW (Indicative ₹3.30 Lakh • ₹1.38L Subsidy)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Roof Type</label>
                <select name="roof_type" class="form-select">
                    <option value="RCC Roof" <?= ($customer['roof_type'] ?? '') === 'RCC Roof' ? 'selected' : '' ?>>RCC Concrete Roof</option>
                    <option value="Tin Shade" <?= ($customer['roof_type'] ?? '') === 'Tin Shade' ? 'selected' : '' ?>>Tin / Metal Shed</option>
                    <option value="Asbestos" <?= ($customer['roof_type'] ?? '') === 'Asbestos' ? 'selected' : '' ?>>Asbestos Sheet</option>
                    <option value="Tiled Roof" <?= ($customer['roof_type'] ?? '') === 'Tiled Roof' ? 'selected' : '' ?>>Tiled Roof</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Approx. Shadow-Free Roof Area (sq.ft.)</label>
                <input type="number" name="roof_area_sqft" class="form-control" value="<?= htmlspecialchars($customer['roof_area_sqft'] ?? 300) ?>">
            </div>

            <!-- Address Fields -->
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">District <span class="text-danger">*</span></label>
                <input type="text" name="district" class="form-control" value="<?= htmlspecialchars($customer['district'] ?? 'Khordha') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Block / Municipality <span class="text-danger">*</span></label>
                <input type="text" name="block" class="form-control" value="<?= htmlspecialchars($customer['block'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Gram Panchayat / Ward</label>
                <input type="text" name="gram_panchayat" class="form-control" value="<?= htmlspecialchars($customer['gram_panchayat'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Village / Town</label>
                <input type="text" name="village" class="form-control" value="<?= htmlspecialchars($customer['village'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Pincode <span class="text-danger">*</span></label>
                <input type="text" name="pincode" class="form-control" value="<?= htmlspecialchars($customer['pincode'] ?? '751024') ?>" maxlength="6" required>
            </div>
            <div class="col-md-8">
                <label class="form-label small fw-bold text-navy">Detailed House & Street Address</label>
                <input type="text" name="address_line" class="form-control" value="<?= htmlspecialchars($customer['address_line'] ?? '') ?>" placeholder="Plot / House No, Street, Landmark">
            </div>
        </div>
    </div>

    <!-- SECTION 3: BANK DETAILS FOR DBT SUBSIDY -->
    <div class="card card-svpl border-0 shadow-sm p-4 mb-4 bg-white rounded-3">
        <h5 class="font-heading fw-bold text-navy mb-3 pb-2 border-bottom">
            <i class="bi bi-bank text-warning me-2"></i> 3. Customer Bank Account (For Direct DBT Subsidy Disbursement)
        </h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Bank Name</label>
                <input type="text" name="bank_name" class="form-control" value="<?= htmlspecialchars($customer['bank_name'] ?? '') ?>" placeholder="e.g. State Bank of India">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Branch Name</label>
                <input type="text" name="bank_branch" class="form-control" value="<?= htmlspecialchars($customer['bank_branch'] ?? '') ?>" placeholder="e.g. Bhubaneswar Main">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-navy">Account Holder Name</label>
                <input type="text" name="account_holder" class="form-control" value="<?= htmlspecialchars($customer['account_holder'] ?? '') ?>" placeholder="As printed on Passbook">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-navy">Account Number</label>
                <input type="text" name="account_number" class="form-control font-monospace" value="<?= htmlspecialchars($customer['account_number'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-navy">IFSC Code</label>
                <input type="text" name="ifsc_code" class="form-control font-monospace text-uppercase" value="<?= htmlspecialchars($customer['ifsc_code'] ?? '') ?>" maxlength="11">
            </div>
        </div>
    </div>

    <!-- SECTION 4: RECTIFICATION NOTE FOR BOE -->
    <div class="card card-svpl border-0 shadow-sm p-4 mb-4 bg-white rounded-3 border-start border-4 border-warning">
        <h5 class="font-heading fw-bold text-navy mb-2">
            <i class="bi bi-card-checklist text-warning me-2"></i> 4. Rectification Notes & Remarks for BOE
        </h5>
        <p class="text-secondary small mb-3">
            Provide details of the corrections made (e.g. "Updated electricity consumer number to match latest electricity bill").
        </p>
        <textarea name="rectification_note" class="form-control" rows="3" placeholder="Enter notes for Back Office Executive regarding changes made..."></textarea>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="<?= url('/advisor/customers') ?>" class="btn btn-outline-secondary">
            Cancel
        </a>
        <button type="submit" class="btn btn-svpl-solar btn-lg shadow-sm px-4 fw-bold">
            <i class="bi bi-check-circle-fill me-1"></i> Save Corrections & Submit to BOE
        </button>
    </div>
</form>
