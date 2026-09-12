<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Edit Customer View - Solar Luminary Design System
 */
$title = "Edit Customer: " . htmlspecialchars($customer['customer_code']) . " — SVPL Admin";
$allAdvisors = $allAdvisors ?? [];
$success = $success ?? null;
$error = $error ?? null;
?>

<div class="container-fluid px-3 px-lg-4 py-3">
    <!-- PAGE HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="<?= url('/admin/customers') ?>" class="btn btn-outline-secondary btn-sm py-0 px-2" title="Back to Customer Registry">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="badge bg-navy text-warning fw-bold px-2 py-1">
                    <i class="bi bi-person-fill me-1"></i> Beneficiary Profile
                </span>
                <span class="badge bg-light text-dark font-monospace border fw-bold">
                    <?= htmlspecialchars($customer['customer_code']) ?>
                </span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold">
                    <?= htmlspecialchars($customer['status'] ?? 'New') ?>
                </span>
            </div>
            <h3 class="font-heading fw-bold mb-0 text-navy">
                Modify Beneficiary Data: <span class="text-primary"><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></span>
            </h3>
            <p class="text-secondary small mb-0">Update customer personal profile, DISCOM consumer linkage, solar technical sizing, DBT subsidy bank account, and assigned advisor.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="<?= url('/admin/customers') ?>" class="btn btn-light border btn-sm text-secondary">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </a>
        </div>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div><strong>Success:</strong> <?= htmlspecialchars($success) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
            <div><strong>Error:</strong> <?= htmlspecialchars($error) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5">
            <form method="POST" action="<?= url('/admin/customers/' . $customer['id'] . '/edit') ?>">
                <?= function_exists('csrf_field') ? csrf_field() : '' ?>

                <!-- 1. BENEFICIARY INFORMATION -->
                <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom border-2" style="border-color: #E2E8F0 !important;">
                    <span class="badge bg-primary text-white rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                    <h5 class="fw-bold mb-0 text-navy font-heading">Beneficiary & Primary Contact Information</h5>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">First Name *</label>
                        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($customer['first_name'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Last Name *</label>
                        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($customer['last_name'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Father / Husband / Spouse Name</label>
                        <input type="text" name="father_spouse_name" class="form-control" value="<?= htmlspecialchars($customer['father_spouse_name'] ?? '') ?>" placeholder="Father or Spouse Name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Primary Mobile Number *</label>
                        <input type="tel" name="mobile" class="form-control font-monospace fw-bold" value="<?= htmlspecialchars($customer['mobile'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Alternate Mobile Number</label>
                        <input type="tel" name="alt_mobile" class="form-control font-monospace" value="<?= htmlspecialchars($customer['alt_mobile'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email'] ?? '') ?>">
                    </div>
                </div>

                <!-- 2. ODISHA LOCATION & POSTAL ADDRESS -->
                <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom border-2 mt-5" style="border-color: #E2E8F0 !important;">
                    <span class="badge bg-primary text-white rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                    <h5 class="fw-bold mb-0 text-navy font-heading">Odisha Location & Postal Installation Address</h5>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">State *</label>
                        <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($customer['state'] ?? 'Odisha') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">District *</label>
                        <select name="district" id="selectEditCustDistrict" class="form-select select-district" data-initial="<?= htmlspecialchars($customer['district'] ?? '') ?>" required>
                            <option value="">Select District</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Block / Municipality *</label>
                        <select name="block" id="selectEditCustBlock" class="form-select select-block" data-initial="<?= htmlspecialchars($customer['block'] ?? '') ?>" required>
                            <option value="">Select Block</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Gram Panchayat *</label>
                        <input type="text" name="gram_panchayat" id="inputEditCustGp" class="form-control select-gp" list="listAdminCustomerGps" data-initial="<?= htmlspecialchars($customer['gram_panchayat'] ?? '') ?>" value="<?= htmlspecialchars($customer['gram_panchayat'] ?? '') ?>" required>
                        <datalist id="listAdminCustomerGps"></datalist>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Village / Ward</label>
                        <input type="text" name="village" id="inputEditCustVillage" class="form-control select-village" list="listAdminCustomerVillages" data-initial="<?= htmlspecialchars($customer['village'] ?? '') ?>" value="<?= htmlspecialchars($customer['village'] ?? '') ?>">
                        <datalist id="listAdminCustomerVillages"></datalist>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Pincode (6-digit) *</label>
                        <input type="text" name="pincode" id="inputEditCustPincode" class="form-control input-pincode font-monospace" data-initial="<?= htmlspecialchars($customer['pincode'] ?? '') ?>" value="<?= htmlspecialchars($customer['pincode'] ?? '') ?>" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-navy small">Full Postal / Site Address</label>
                        <input type="text" name="address_line" class="form-control" value="<?= htmlspecialchars($customer['address_line'] ?? '') ?>" placeholder="Plot No, Street, Landmark, House No">
                    </div>
                </div>

                <!-- 3. ELECTRICITY CONNECTION & SOLAR TECHNICAL DETAILS -->
                <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom border-2 mt-5" style="border-color: #E2E8F0 !important;">
                    <span class="badge bg-primary text-white rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                    <h5 class="fw-bold mb-0 text-navy font-heading">DISCOM Electricity Connection & Solar Technical Sizing</h5>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Odisha DISCOM *</label>
                        <select name="discom_name" class="form-select fw-semibold" required>
                            <option value="TPCODL" <?= ($customer['discom_name'] ?? '') === 'TPCODL' ? 'selected' : '' ?>>TPCODL (Central Odisha)</option>
                            <option value="TPNODL" <?= ($customer['discom_name'] ?? '') === 'TPNODL' ? 'selected' : '' ?>>TPNODL (Northern Odisha)</option>
                            <option value="TPSODL" <?= ($customer['discom_name'] ?? '') === 'TPSODL' ? 'selected' : '' ?>>TPSODL (Southern Odisha)</option>
                            <option value="TPWODL" <?= ($customer['discom_name'] ?? '') === 'TPWODL' ? 'selected' : '' ?>>TPWODL (Western Odisha)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Consumer / CA Number *</label>
                        <input type="text" name="consumer_number" class="form-control font-monospace fw-bold" value="<?= htmlspecialchars($customer['consumer_number'] ?? $customer['electricity_consumer_no'] ?? '') ?>" placeholder="10-12 digit Consumer No">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Sanctioned Load (kW) *</label>
                        <input type="number" step="0.1" min="0.5" max="100" name="sanctioned_load_kw" class="form-control" value="<?= htmlspecialchars($customer['sanctioned_load_kw'] ?? 2.0) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Proposed Solar Capacity (kW) *</label>
                        <select name="proposed_solar_kw" class="form-select fw-bold text-success" required>
                            <?php 
                            $capacities = [1.0, 2.0, 3.0, 3.3, 4.0, 5.0, 6.0, 7.0, 8.0, 9.0, 10.0, 15.0, 20.0];
                            $curCap = (float)($customer['proposed_solar_kw'] ?? 3.0);
                            foreach ($capacities as $cap): ?>
                                <option value="<?= $cap ?>" <?= $curCap == $cap ? 'selected' : '' ?>><?= $cap ?> kW On-Grid System</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Monthly Average Electricity Bill (₹)</label>
                        <input type="number" step="1" name="monthly_avg_bill" class="form-control" value="<?= htmlspecialchars($customer['monthly_avg_bill'] ?? '') ?>" placeholder="e.g. 2500">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Rooftop Type</label>
                        <select name="roof_type" class="form-select">
                            <option value="RCC Roof" <?= ($customer['roof_type'] ?? '') === 'RCC Roof' ? 'selected' : '' ?>>RCC Flat Concrete Roof</option>
                            <option value="Tin Shed" <?= ($customer['roof_type'] ?? '') === 'Tin Shed' ? 'selected' : '' ?>>Industrial / Tin Metal Shed</option>
                            <option value="Asbestos" <?= ($customer['roof_type'] ?? '') === 'Asbestos' ? 'selected' : '' ?>>Asbestos Sheet Roof</option>
                            <option value="Tiled" <?= ($customer['roof_type'] ?? '') === 'Tiled' ? 'selected' : '' ?>>Slanted Tiled Roof</option>
                            <option value="Ground Mounted" <?= ($customer['roof_type'] ?? '') === 'Ground Mounted' ? 'selected' : '' ?>>Ground Mounted Open Yard</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Approx. Shadow-Free Roof Area (Sq. Ft.)</label>
                        <input type="number" step="1" name="roof_area_sqft" class="form-control" value="<?= htmlspecialchars($customer['roof_area_sqft'] ?? 300) ?>" placeholder="e.g. 350">
                    </div>
                </div>

                <!-- 4. DBT BANK ACCOUNT DETAILS -->
                <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom border-2 mt-5" style="border-color: #E2E8F0 !important;">
                    <span class="badge bg-primary text-white rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">4</span>
                    <h5 class="fw-bold mb-0 text-navy font-heading">Direct Benefit Transfer (DBT) Subsidy Bank Account</h5>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="<?= htmlspecialchars($customer['bank_name'] ?? '') ?>" placeholder="e.g. State Bank of India">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Bank Branch</label>
                        <input type="text" name="bank_branch" class="form-control" value="<?= htmlspecialchars($customer['bank_branch'] ?? '') ?>" placeholder="e.g. Cuttack Main Branch">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-navy small">Account Holder Name (Matches Electricity Bill)</label>
                        <input type="text" name="account_holder" class="form-control" value="<?= htmlspecialchars($customer['account_holder'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-navy small">Bank Account Number</label>
                        <input type="text" name="account_number" class="form-control font-monospace" value="<?= htmlspecialchars($customer['account_number'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-navy small">Bank IFSC Code</label>
                        <input type="text" name="ifsc_code" class="form-control font-monospace text-uppercase" value="<?= htmlspecialchars($customer['ifsc_code'] ?? '') ?>" placeholder="e.g. SBIN0001234">
                    </div>
                </div>

                <!-- 5. ATTRIBUTION & LIFECYCLE STATUS -->
                <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom border-2 mt-5" style="border-color: #E2E8F0 !important;">
                    <span class="badge bg-primary text-white rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">5</span>
                    <h5 class="fw-bold mb-0 text-navy font-heading">Attribution & 10-Stage Project Lifecycle Status</h5>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-navy small">Assisting / Assigned Solar Advisor *</label>
                        <select name="advisor_id" class="form-select">
                            <option value="">-- Direct HQ / SVPL In-House Lead --</option>
                            <?php foreach ($allAdvisors as $adv): ?>
                                <option value="<?= $adv['id'] ?>" <?= ((int)($customer['advisor_id'] ?? 0)) === (int)$adv['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($adv['advisor_code']) ?> — <?= htmlspecialchars($adv['first_name'] . ' ' . $adv['last_name']) ?> (<?= htmlspecialchars($adv['district']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-navy small">Customer Lifecycle Status / Stage *</label>
                        <select name="status" class="form-select fw-bold">
                            <option value="New" <?= ($customer['status'] ?? '') === 'New' ? 'selected' : '' ?>>1. New Beneficiary Lead</option>
                            <option value="DOCUMENTS_VERIFIED" <?= ($customer['status'] ?? '') === 'DOCUMENTS_VERIFIED' ? 'selected' : '' ?>>2. Documents Uploaded & Verified</option>
                            <option value="GOVT_PORTAL" <?= ($customer['status'] ?? '') === 'GOVT_PORTAL' ? 'selected' : '' ?>>3. PM Surya Ghar Govt Portal Submitted</option>
                            <option value="LOAN_APPLIED" <?= ($customer['status'] ?? '') === 'LOAN_APPLIED' ? 'selected' : '' ?>>4. Bank Solar Loan Applied</option>
                            <option value="LOAN_SANCTIONED" <?= ($customer['status'] ?? '') === 'LOAN_SANCTIONED' ? 'selected' : '' ?>>5. Bank Solar Loan Sanctioned</option>
                            <option value="INSTALLATION_COMMENCED" <?= ($customer['status'] ?? '') === 'INSTALLATION_COMMENCED' ? 'selected' : '' ?>>6. Installation Commenced On-Site</option>
                            <option value="INSTALLATION_COMPLETED" <?= ($customer['status'] ?? '') === 'INSTALLATION_COMPLETED' ? 'selected' : '' ?>>7. Solar Installation Completed</option>
                            <option value="JE_REPORT" <?= ($customer['status'] ?? '') === 'JE_REPORT' ? 'selected' : '' ?>>8. DISCOM JE Inspection & Net Meter</option>
                            <option value="SUBSIDY_APPLIED" <?= ($customer['status'] ?? '') === 'SUBSIDY_APPLIED' ? 'selected' : '' ?>>9. Subsidy Applied on National Portal</option>
                            <option value="SUBSIDY_RECEIVED" <?= ($customer['status'] ?? '') === 'SUBSIDY_RECEIVED' ? 'selected' : '' ?>>10. Subsidy Disbursed (Active Project)</option>
                        </select>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex align-items-center justify-content-between pt-4 border-top">
                    <a href="<?= url('/admin/customers') ?>" class="btn btn-light border px-4 py-2">
                        <i class="bi bi-arrow-left me-1"></i> Back to Customer Registry
                    </a>
                    <button type="submit" class="btn btn-svpl-solar btn-lg px-5 py-2 fw-bold shadow-sm">
                        <i class="bi bi-save2-fill me-2"></i> Save Beneficiary Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
