<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Commission Control Center - 12-Tab Admin Rules & Settings Panel
 */
$title = $pageTitle ?? 'Commission Control Center';
$activeTab = $_GET['tab'] ?? 'joining';
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-sliders text-primary me-2"></i>Commission Control Center
            </h1>
            <p class="text-muted small mb-0">Completely Manageable Commission Engine — Zero Hardcoded Rules or Amounts</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url('/admin/commissions') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to Desk
            </a>
            <a href="<?= url('/admin/commissions/simulator') ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-calculator me-1"></i>Simulator
            </a>
        </div>
    </div>

    <!-- Alert / Notice -->
    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
        <i class="bi bi-info-circle-fill fs-4 me-3 text-info"></i>
        <div>
            <strong class="text-dark">Rule Versioning Active:</strong> Changing any rate or qualification rule below will apply to future calculations. Existing approved financial commissions and ledger records remain permanently immutable.
        </div>
    </div>

<style>
.comm-settings-sidebar {
    position: sticky;
    top: 1rem;
    z-index: 10;
}
.comm-nav-item {
    border: none !important;
    padding: 0.8rem 1rem !important;
    border-radius: 0.5rem !important;
    margin-bottom: 0.35rem !important;
    font-size: 0.9rem;
    font-weight: 500;
    color: #475569 !important;
    background-color: transparent;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
}
.comm-nav-item:hover {
    background-color: #f1f5f9 !important;
    color: #0d6efd !important;
    transform: translateX(3px);
}
.comm-nav-item.active {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%) !important;
    color: #ffffff !important;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
}
.comm-nav-item.active .nav-icon {
    color: #ffffff !important;
}
.comm-nav-item.active .chevron-icon {
    color: #ffffff !important;
    opacity: 0.9 !important;
    transform: translateX(2px);
}
.comm-nav-item .chevron-icon {
    transition: transform 0.2s ease;
    font-size: 0.75rem;
}
.comm-details-card {
    min-height: 600px;
}
</style>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'saved'): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
            <div><strong>Success!</strong> Commission configuration rule updated successfully.</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Master-Detail 2-Column Layout -->
    <div class="row g-4">
        
        <!-- LEFT SIDEBAR MENU -->
        <div class="col-lg-3 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 comm-settings-sidebar">
                <div class="card-header bg-white border-bottom py-3 px-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary-subtle text-primary p-2 me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bi bi-sliders fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Rule Categories</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">11 Configurable Modules</small>
                        </div>
                    </div>
                </div>
                <div class="list-group list-group-flush p-2" id="commSettingsNav" role="tablist">
                    <a class="list-group-item comm-nav-item <?= in_array($activeTab, ['joining']) ? 'active' : '' ?>" id="joining-tab" data-bs-toggle="tab" href="#tab-joining" role="tab" data-tab="joining">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-plus nav-icon me-2 text-primary"></i>
                            <span>1. Advisor Joining</span>
                        </div>
                        <i class="bi bi-chevron-right chevron-icon opacity-50"></i>
                    </a>
                    <a class="list-group-item comm-nav-item <?= in_array($activeTab, ['product']) ? 'active' : '' ?>" id="product-tab" data-bs-toggle="tab" href="#tab-product" role="tab" data-tab="product">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-box-seam nav-icon me-2 text-success"></i>
                            <span>2. Product Commission</span>
                        </div>
                        <i class="bi bi-chevron-right chevron-icon opacity-50"></i>
                    </a>
                    <a class="list-group-item comm-nav-item <?= in_array($activeTab, ['upline_matrix', 'matrix']) ? 'active' : '' ?>" id="matrix-tab" data-bs-toggle="tab" href="#tab-matrix" role="tab" data-tab="upline_matrix">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-diagram-3 nav-icon me-2 text-info"></i>
                            <span>3. Upline Qualification</span>
                        </div>
                        <i class="bi bi-chevron-right chevron-icon opacity-50"></i>
                    </a>
                    <a class="list-group-item comm-nav-item <?= in_array($activeTab, ['monthly_bonus', 'bonus']) ? 'active' : '' ?>" id="bonus-tab" data-bs-toggle="tab" href="#tab-bonus" role="tab" data-tab="monthly_bonus">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-check nav-icon me-2 text-warning"></i>
                            <span>4. Monthly Bonus</span>
                        </div>
                        <i class="bi bi-chevron-right chevron-icon opacity-50"></i>
                    </a>
                    <a class="list-group-item comm-nav-item <?= in_array($activeTab, ['pool_bonus', 'pool']) ? 'active' : '' ?>" id="pool-tab" data-bs-toggle="tab" href="#tab-pool" role="tab" data-tab="pool_bonus">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bezier2 nav-icon me-2 text-danger"></i>
                            <span>5. Pool Bonus (PB)</span>
                        </div>
                        <i class="bi bi-chevron-right chevron-icon opacity-50"></i>
                    </a>
                    <a class="list-group-item comm-nav-item <?= in_array($activeTab, ['rewards']) ? 'active' : '' ?>" id="rewards-tab" data-bs-toggle="tab" href="#tab-rewards" role="tab" data-tab="rewards">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-trophy nav-icon me-2 text-warning"></i>
                            <span>6. Lifetime Rewards</span>
                        </div>
                        <i class="bi bi-chevron-right chevron-icon opacity-50"></i>
                    </a>
                    <a class="list-group-item comm-nav-item <?= in_array($activeTab, ['customer_bonus', 'cust']) ? 'active' : '' ?>" id="cust-tab" data-bs-toggle="tab" href="#tab-cust" role="tab" data-tab="customer_bonus">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-gift nav-icon me-2 text-info"></i>
                            <span>7. Customer Bonus</span>
                        </div>
                        <i class="bi bi-chevron-right chevron-icon opacity-50"></i>
                    </a>
                    <a class="list-group-item comm-nav-item <?= in_array($activeTab, ['triggers', 'trigger']) ? 'active' : '' ?>" id="trigger-tab" data-bs-toggle="tab" href="#tab-trigger" role="tab" data-tab="triggers">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-lightning-charge nav-icon me-2 text-warning"></i>
                            <span>8. Trigger Events</span>
                        </div>
                        <i class="bi bi-chevron-right chevron-icon opacity-50"></i>
                    </a>
                    <a class="list-group-item comm-nav-item <?= in_array($activeTab, ['approval']) ? 'active' : '' ?>" id="approval-tab" data-bs-toggle="tab" href="#tab-approval" role="tab" data-tab="approval">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-shield-check nav-icon me-2 text-secondary"></i>
                            <span>9. Approval Mode</span>
                        </div>
                        <i class="bi bi-chevron-right chevron-icon opacity-50"></i>
                    </a>
                    <a class="list-group-item comm-nav-item <?= in_array($activeTab, ['wallet']) ? 'active' : '' ?>" id="wallet-tab" data-bs-toggle="tab" href="#tab-wallet" role="tab" data-tab="wallet">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-wallet2 nav-icon me-2 text-success"></i>
                            <span>10. Wallet Settings</span>
                        </div>
                        <i class="bi bi-chevron-right chevron-icon opacity-50"></i>
                    </a>
                    <a class="list-group-item comm-nav-item <?= in_array($activeTab, ['tax']) ? 'active' : '' ?>" id="tax-tab" data-bs-toggle="tab" href="#tab-tax" role="tab" data-tab="tax">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-receipt nav-icon me-2 text-dark"></i>
                            <span>11. Tax & TDS</span>
                        </div>
                        <i class="bi bi-chevron-right chevron-icon opacity-50"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT DETAILS CONTENT PANEL -->
        <div class="col-lg-9 col-md-8">
            <div class="card border-0 shadow-sm rounded-3 comm-details-card">
                <div class="card-body p-4">
                    <div class="tab-content" id="commTabContent">
                        
                        <!-- TAB 1: ADVISOR JOINING -->
                        <div class="tab-pane fade <?= in_array($activeTab, ['joining']) ? 'show active' : '' ?>" id="tab-joining">
                            <form method="POST" action="<?= url('/admin/commissions/settings') ?>">
                                <input type="hidden" name="tab" value="joining">
                                <h5 class="fw-bold text-dark mb-3">Advisor Network Registration & Joining Fee</h5>
                        <p class="text-muted small">Configure the joining fee paid by new advisors and the immediate direct sponsor bonus.</p>
                        
                        <div class="row g-3 col-lg-8">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Advisor Joining Fee (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="joining_fee" class="form-control" value="<?= htmlspecialchars($joiningSettings['joining_fee']) ?>" required>
                                </div>
                                <div class="form-text small">Default: ₹2,700.00</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Direct Sponsor Joining Commission (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="direct_commission" class="form-control" value="<?= htmlspecialchars($joiningSettings['direct_commission']) ?>" required>
                                </div>
                                <div class="form-text small">Default: ₹700.00 (Goes ONLY to direct sponsor)</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Upline Joining Commission (Level 2 to 9) (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="upline_commission" class="form-control" value="<?= htmlspecialchars($joiningSettings['upline_commission']) ?>" required>
                                </div>
                                <div class="form-text small">Default: ₹0.00 (Joining commission does not propagate upward)</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Joining Commission Status</label>
                                <select name="enabled" class="form-select">
                                    <option value="1" <?= $joiningSettings['enabled'] ? 'selected' : '' ?>>Enabled</option>
                                    <option value="0" <?= !$joiningSettings['enabled'] ? 'selected' : '' ?>>Disabled</option>
                                </select>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Joining Settings</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TAB 2: SOLAR PRODUCT COMMISSIONS -->
                <div class="tab-pane fade <?= in_array($activeTab, ['product']) ? 'show active' : '' ?>" id="tab-product">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-box-seam text-warning me-2"></i>Solar Package-Wise Commission Rules</h5>
                            <p class="text-muted small mb-0">Directly synchronized with Master Solar Packages (<?= count($productRules) ?> packages available across all Tier-1 brands & capacities).</p>
                        </div>
                        <button type="button" class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addProductRuleModal">
                            <i class="bi bi-plus-circle me-1"></i>Configure Package Commission
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle shadow-sm">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th>Solar Product / Brand</th>
                                    <th>Capacity & Type</th>
                                    <th class="text-end">Package Price</th>
                                    <th class="text-end text-success">L1 (Direct)</th>
                                    <th class="text-end">L2 (Sponsor)</th>
                                    <th class="text-end">L3–L9 (Uplines)</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <?php if (empty($productRules)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-2 d-block mb-1 text-secondary"></i>
                                            No solar packages found in packages table.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($productRules as $pr): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($pr['package_title'] ?? $pr['product_name'] ?? $pr['rule_name']) ?></div>
                                                <div class="d-flex gap-1 align-items-center mt-1">
                                                    <span class="badge bg-dark text-warning border" style="font-size: 0.7rem;"><?= htmlspecialchars($pr['brand'] ?? 'Dhwajja Solar') ?></span>
                                                    <span class="text-muted font-monospace small" style="font-size: 0.72rem;"><?= htmlspecialchars($pr['package_code'] ?? $pr['rule_code'] ?? '') ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary fw-bold"><?= (float)$pr['capacity_kw'] ?> kW</span>
                                                <span class="badge bg-secondary-subtle text-secondary"><?= htmlspecialchars($pr['system_type'] ?? $pr['connection_type'] ?? 'On-Grid') ?></span>
                                            </td>
                                            <td class="text-end">
                                                <div class="fw-semibold text-dark">₹<?= number_format((float)($pr['total_price'] ?? 0), 2) ?></div>
                                                <?php if (!empty($pr['net_customer_cost'])): ?>
                                                    <div class="text-muted small" style="font-size: 0.72rem;">Net: ₹<?= number_format((float)$pr['net_customer_cost'], 2) ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end fw-bold text-success fs-6">₹<?= number_format((float)($pr['levels'][1] ?? $pr['direct_commission']), 2) ?></td>
                                            <td class="text-end fw-semibold">₹<?= number_format((float)($pr['levels'][2] ?? 1000.0), 2) ?></td>
                                            <td class="text-end fw-semibold">₹<?= number_format((float)($pr['levels'][3] ?? 500.0), 2) ?></td>
                                            <td>
                                                <span class="badge <?= $pr['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                                    <?= $pr['is_active'] ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-outline-primary btn-sm edit-product-btn"
                                                        data-id="<?= $pr['id'] ?>"
                                                        data-package-id="<?= $pr['package_id'] ?? '' ?>"
                                                        data-name="<?= htmlspecialchars($pr['package_title'] ?? $pr['rule_name']) ?>"
                                                        data-product="<?= htmlspecialchars($pr['package_title'] ?? $pr['product_name'] ?? '') ?>"
                                                        data-capacity="<?= (float)$pr['capacity_kw'] ?>"
                                                        data-type="<?= htmlspecialchars($pr['system_type'] ?? $pr['connection_type'] ?? 'On-Grid') ?>"
                                                        data-price="<?= number_format((float)($pr['total_price'] ?? 0), 2, '.', '') ?>"
                                                        data-direct="<?= (float)($pr['levels'][1] ?? $pr['direct_commission']) ?>"
                                                        data-l2="<?= (float)($pr['levels'][2] ?? 1000.0) ?>"
                                                        data-l39="<?= (float)($pr['levels'][3] ?? 500.0) ?>"
                                                        data-active="<?= (int)$pr['is_active'] ?>">
                                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 3: UPLINE QUALIFICATION MATRIX -->
                <div class="tab-pane fade <?= in_array($activeTab, ['upline_matrix', 'matrix']) ? 'show active' : '' ?>" id="tab-matrix">
                    <form method="POST" action="<?= url('/admin/commissions/settings') ?>">
                        <input type="hidden" name="tab" value="upline_matrix">
                        <h5 class="fw-bold text-dark mb-2">Upline Customer Qualification Matrix</h5>
                        <p class="text-muted small mb-3">Upper-level advisors must have generated direct personal customers to qualify for upline referral commissions.</p>
                        
                        <div class="table-responsive col-lg-9">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light small text-uppercase">
                                    <tr>
                                        <th>Minimum Personal Lifetime Customers</th>
                                        <th>Maximum Eligible Commission Level</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($uplineQualifications as $idx => $uq): ?>
                                        <tr>
                                            <td width="200">
                                                <input type="number" name="min_customers[]" class="form-control form-control-sm" value="<?= (int)$uq['min_personal_customers'] ?>" required>
                                            </td>
                                            <td width="200">
                                                <select name="max_level[]" class="form-select form-select-sm">
                                                    <option value="0" <?= (int)$uq['max_eligible_level'] == 0 ? 'selected' : '' ?>>Level 0 (No upline comm)</option>
                                                    <option value="1" <?= (int)$uq['max_eligible_level'] == 1 ? 'selected' : '' ?>>Up to Level 1</option>
                                                    <option value="2" <?= (int)$uq['max_eligible_level'] == 2 ? 'selected' : '' ?>>Up to Level 2</option>
                                                    <option value="9" <?= (int)$uq['max_eligible_level'] >= 9 ? 'selected' : '' ?>>Up to Level 9 (Full)</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="description[]" class="form-control form-control-sm" value="<?= htmlspecialchars($uq['description'] ?? '') ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary mt-2"><i class="bi bi-save me-1"></i>Save Qualification Matrix</button>
                        </div>
                    </form>
                </div>

                <!-- TAB 4: MONTHLY BONUS SLABS -->
                <div class="tab-pane fade <?= in_array($activeTab, ['monthly_bonus', 'bonus']) ? 'show active' : '' ?>" id="tab-bonus">
                    <form method="POST" action="<?= url('/admin/commissions/settings') ?>">
                        <input type="hidden" name="tab" value="monthly_bonus">
                        <h5 class="fw-bold text-dark mb-2">Customer Special Monthly Performance Bonus</h5>
                        <p class="text-muted small mb-3">Additional monthly bonus awarded ONLY to the direct gatherer for customers generated within a calendar month.</p>
                        
                        <div class="table-responsive col-lg-8">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light small text-uppercase">
                                    <tr>
                                        <th>Target Monthly Customers</th>
                                        <th>Bonus Amount (₹)</th>
                                        <th>Calculation Mode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($monthlyBonusSlabs as $idx => $mbs): ?>
                                        <tr>
                                            <td width="200">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" name="min_customers[]" class="form-control" value="<?= (int)$mbs['min_customers'] ?>" required>
                                                    <span class="input-group-text">customers</span>
                                                </div>
                                            </td>
                                            <td width="250">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">₹</span>
                                                    <input type="number" step="0.01" name="bonus_amount[]" class="form-control" value="<?= (float)$mbs['bonus_amount'] ?>" required>
                                                </div>
                                            </td>
                                            <td>
                                                <select name="calculation_mode" class="form-select form-select-sm">
                                                    <option value="HIGHEST_SLAB" <?= ($mbs['calculation_mode'] ?? '') === 'HIGHEST_SLAB' ? 'selected' : '' ?>>Highest Achieved Slab Only</option>
                                                    <option value="CUMULATIVE" <?= ($mbs['calculation_mode'] ?? '') === 'CUMULATIVE' ? 'selected' : '' ?>>Cumulative Slabs</option>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary mt-2"><i class="bi bi-save me-1"></i>Save Monthly Bonus Slabs</button>
                        </div>
                    </form>
                </div>

                <!-- TAB 5: POOL BONUS (PB1-PB11+) -->
                <div class="tab-pane fade <?= in_array($activeTab, ['pool_bonus', 'pool']) ? 'show active' : '' ?>" id="tab-pool">
                    <form method="POST" action="<?= url('/admin/commissions/settings') ?>">
                        <input type="hidden" name="tab" value="pool_bonus">
                        <h5 class="fw-bold text-dark mb-2">Separate Pool Bonus Tree (PB1–PB11+)</h5>
                        <p class="text-muted small mb-3">Qualification-based 3-child pool network completely separate from the 9-level genealogy.</p>
                        
                        <div class="row g-3 col-lg-8">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Required Direct Joined Advisors</label>
                                <input type="number" name="min_direct_advisors" class="form-control" value="<?= (int)($poolBonusRule['min_direct_advisors'] ?? 3) ?>" required>
                                <div class="form-text small">Default: 3 direct advisors</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Required Direct Personal Customers</label>
                                <input type="number" name="min_personal_customers" class="form-control" value="<?= (int)($poolBonusRule['min_personal_customers'] ?? 3) ?>" required>
                                <div class="form-text small">Default: 3 personal customers</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Children Per Pool Member</label>
                                <input type="number" name="max_children_per_node" class="form-control" value="<?= (int)($poolBonusRule['max_children_per_node'] ?? 3) ?>" required>
                                <div class="form-text small">Default: 3 direct pool children</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Maximum Pool Levels</label>
                                <input type="number" name="max_pool_levels" class="form-control" value="<?= (int)($poolBonusRule['max_pool_levels'] ?? 11) ?>" required>
                                <div class="form-text small">Default: 11 pool levels</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pool Level 1 Payout (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="level_1_amount" class="form-control" value="<?= (float)($poolBonusRule['level_1_amount'] ?? 1000.0) ?>" required>
                                </div>
                                <div class="form-text small">Default: ₹1,000.00</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Subsequent Pool Levels Payout (Level 2 to 11) (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="subsequent_level_amount" class="form-control" value="<?= (float)($poolBonusRule['subsequent_level_amount'] ?? 500.0) ?>" required>
                                </div>
                                <div class="form-text small">Default: ₹500.00</div>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Pool Configuration</button>
                                <a href="<?= url('/admin/commissions/pool-tree') ?>" class="btn btn-outline-success ms-2"><i class="bi bi-diagram-3 me-1"></i>View Live Pool Tree</a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TAB 6: LIFETIME PERFORMANCE REWARDS -->
                <div class="tab-pane fade <?= in_array($activeTab, ['rewards']) ? 'show active' : '' ?>" id="tab-rewards">
                    <form method="POST" action="<?= url('/admin/commissions/settings') ?>">
                        <input type="hidden" name="tab" value="rewards">
                        <h5 class="fw-bold text-dark mb-2">Advisor Lifetime Business Performance Rewards</h5>
                        <p class="text-muted small mb-3">Configured customer milestones with cash, product or vehicle reward payouts.</p>
                        
                        <div class="table-responsive col-lg-10">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light small text-uppercase">
                                    <tr>
                                        <th>Milestone Name</th>
                                        <th>Target Customers</th>
                                        <th>Per Cust (₹)</th>
                                        <th>Total Reward Value (₹)</th>
                                        <th>Reward Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rewardSlabs as $idx => $rs): ?>
                                        <tr>
                                            <td>
                                                <input type="text" name="reward_name[]" class="form-control form-control-sm" value="<?= htmlspecialchars($rs['reward_name']) ?>" required>
                                            </td>
                                            <td width="150">
                                                <input type="number" name="customer_target[]" class="form-control form-control-sm text-end" value="<?= (int)$rs['customer_target'] ?>" required>
                                            </td>
                                            <td width="150">
                                                <input type="number" step="0.01" name="per_customer_amount[]" class="form-control form-control-sm text-end" value="<?= (float)$rs['per_customer_amount'] ?>" required>
                                            </td>
                                            <td width="200">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">₹</span>
                                                    <input type="number" step="0.01" name="total_reward_value[]" class="form-control text-end fw-bold" value="<?= (float)$rs['total_reward_value'] ?>" required>
                                                </div>
                                            </td>
                                            <td width="180">
                                                <select name="reward_type[]" class="form-select form-select-sm">
                                                    <option value="CASH_OR_PRODUCT" <?= $rs['reward_type'] === 'CASH_OR_PRODUCT' ? 'selected' : '' ?>>Cash / Product</option>
                                                    <option value="CASH_OR_VEHICLE" <?= $rs['reward_type'] === 'CASH_OR_VEHICLE' ? 'selected' : '' ?>>Cash / Vehicle</option>
                                                    <option value="CASH_ONLY" <?= $rs['reward_type'] === 'CASH_ONLY' ? 'selected' : '' ?>>Cash Only</option>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary mt-2"><i class="bi bi-save me-1"></i>Save Reward Slabs</button>
                            <a href="<?= url('/admin/commissions/rewards') ?>" class="btn btn-outline-warning ms-2"><i class="bi bi-trophy me-1"></i>Manage Reward Claims</a>
                        </div>
                    </form>
                </div>

                <!-- TAB 7: CUSTOMER SPECIAL BONUS -->
                <div class="tab-pane fade <?= in_array($activeTab, ['customer_bonus', 'cust']) ? 'show active' : '' ?>" id="tab-cust">
                    <form method="POST" action="<?= url('/admin/commissions/settings') ?>">
                        <input type="hidden" name="tab" value="customer_bonus">
                        <h5 class="fw-bold text-dark mb-2">Customer Special Monthly Declared Bonus</h5>
                        <p class="text-muted small mb-3">Admin declares variable monthly bonus for customers (up to ₹85,000 maximum).</p>
                        
                        <div class="row g-3 col-lg-8">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Declaration Month</label>
                                <select name="bonus_month" class="form-select">
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?= $m ?>" <?= $currentMonth == $m ? 'selected' : '' ?>><?= date('F', mktime(0,0,0,$m,10)) ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Declaration Year</label>
                                <input type="number" name="bonus_year" class="form-control" value="<?= $currentYear ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Declared Bonus Amount (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="bonus_amount" class="form-control" value="<?= (float)($customerSpecialBonus['bonus_amount'] ?? 50000.0) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Maximum Allowed Bonus (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="max_allowed_amount" class="form-control" value="<?= (float)($customerSpecialBonus['max_allowed_amount'] ?? 85000.0) ?>" required>
                                </div>
                                <div class="form-text small">Default: ₹85,000.00</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Eligibility Conditions / Terms</label>
                                <textarea name="eligibility_conditions" class="form-control" rows="2"><?= htmlspecialchars($customerSpecialBonus['eligibility_conditions'] ?? 'Applicable on verified company credit date within the calendar month') ?></textarea>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Declared Bonus</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TAB 8: TRIGGER EVENTS -->
                <div class="tab-pane fade <?= in_array($activeTab, ['triggers', 'trigger']) ? 'show active' : '' ?>" id="tab-trigger">
                    <form method="POST" action="<?= url('/admin/commissions/settings') ?>">
                        <input type="hidden" name="tab" value="triggers">
                        <h5 class="fw-bold text-dark mb-2">Commission Trigger Event</h5>
                        <p class="text-muted small mb-3">Defines when commission eligibility is triggered and which date establishes the commission month.</p>
                        
                        <div class="col-lg-6">
                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="trigger_event" id="trig1" value="COMPANY_CREDIT" <?= ($joiningSettings['trigger_event'] ?? 'COMPANY_CREDIT') === 'COMPANY_CREDIT' ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="trig1">
                                    Company Account Credit Date (Default & Recommended)
                                </label>
                                <div class="text-muted small mt-1">Commission month is strictly set by the verified bank credit date into the company's account.</div>
                            </div>
                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="trigger_event" id="trig2" value="LOAN_SANCTION" <?= ($joiningSettings['trigger_event'] ?? '') === 'LOAN_SANCTION' ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="trig2">
                                    Loan Sanction Date
                                </label>
                                <div class="text-muted small mt-1">Triggers when the loan application is officially sanctioned.</div>
                            </div>
                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="trigger_event" id="trig3" value="INSTALLATION" <?= ($joiningSettings['trigger_event'] ?? '') === 'INSTALLATION' ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="trig3">
                                    Installation Completed Date
                                </label>
                                <div class="text-muted small mt-1">Triggers when site commissioning and net-metering are verified.</div>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Trigger Policy</button>
                        </div>
                    </form>
                </div>

                <!-- TAB 9: APPROVAL MODE -->
                <div class="tab-pane fade <?= in_array($activeTab, ['approval']) ? 'show active' : '' ?>" id="tab-approval">
                    <form method="POST" action="<?= url('/admin/commissions/settings') ?>">
                        <input type="hidden" name="tab" value="approval">
                        <h5 class="fw-bold text-dark mb-2">Commission Approval Workflow</h5>
                        <p class="text-muted small mb-3">Choose whether commissions require manual admin authorization before wallet crediting.</p>
                        
                        <div class="col-lg-6">
                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="approval_mode" id="app1" value="MANUAL" <?= ($joiningSettings['approval_mode'] ?? 'MANUAL') === 'MANUAL' ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="app1">
                                    Manual Admin Review & Approval (Recommended)
                                </label>
                                <div class="text-muted small mt-1">Calculations enter PENDING status and only credit the wallet after explicit admin approval.</div>
                            </div>
                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="approval_mode" id="app2" value="AUTO" <?= ($joiningSettings['approval_mode'] ?? '') === 'AUTO' ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="app2">
                                    Automatic Approval & Instant Wallet Credit
                                </label>
                                <div class="text-muted small mt-1">Commissions credit the Advisor wallet ledger immediately upon payment verification.</div>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Approval Mode</button>
                        </div>
                    </form>
                </div>

                <!-- TAB 10: WALLET SETTINGS -->
                <div class="tab-pane fade <?= in_array($activeTab, ['wallet']) ? 'show active' : '' ?>" id="tab-wallet">
                    <form method="POST" action="<?= url('/admin/commissions/settings') ?>">
                        <input type="hidden" name="tab" value="wallet">
                        <h5 class="fw-bold text-dark mb-2">Advisor Wallet & Settlement Rules</h5>
                        
                        <div class="row g-3 col-lg-6">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Minimum Withdrawal Threshold (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="min_withdrawal" class="form-control" value="<?= htmlspecialchars($joiningSettings['min_withdrawal']) ?>" required>
                                </div>
                                <div class="form-text small">Default: ₹500.00</div>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Wallet Settings</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TAB 11: TAX & TDS -->
                <div class="tab-pane fade <?= in_array($activeTab, ['tax']) ? 'show active' : '' ?>" id="tab-tax">
                    <form method="POST" action="<?= url('/admin/commissions/settings') ?>">
                        <input type="hidden" name="tab" value="tax">
                        <h5 class="fw-bold text-dark mb-2">TDS & Administrative Deductions</h5>
                        
                        <div class="row g-3 col-lg-6">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">TDS Percentage (%)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="tds_percentage" class="form-control" value="<?= htmlspecialchars($joiningSettings['tds_percentage']) ?>" required>
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="form-text small">Default: 5.00%</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Administrative Deduction (%)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="admin_deduction_percentage" class="form-control" value="<?= htmlspecialchars($joiningSettings['admin_deduction_percentage']) ?>" required>
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="form-text small">Default: 0.00%</div>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Tax Deductions</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Add / Edit Product Rule Modal -->
<div class="modal fade" id="addProductRuleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="<?= url('/admin/commissions/product-rule/save') ?>">
            <input type="hidden" name="id" id="modalRuleId" value="">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title h6 mb-0" id="productModalTitle"><i class="bi bi-box-seam me-2"></i>Configure Solar Package Commission Rule</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <!-- Package Selector from packages table -->
                    <div class="card border shadow-none bg-white p-3 mb-3">
                        <label class="form-label fw-bold text-dark mb-1"><i class="bi bi-link-45deg text-primary me-1"></i>Select Master Solar Package (from Packages Table)</label>
                        <select name="package_id" id="modalPackageId" class="form-select form-select-sm" required>
                            <option value="">-- Choose Solar Package --</option>
                            <?php if (!empty($allPackages)): ?>
                                <?php foreach ($allPackages as $pkg): ?>
                                    <option value="<?= $pkg['id'] ?>" 
                                            data-title="<?= htmlspecialchars($pkg['title']) ?>"
                                            data-brand="<?= htmlspecialchars($pkg['brand']) ?>"
                                            data-code="<?= htmlspecialchars($pkg['package_code']) ?>"
                                            data-capacity="<?= (float)$pkg['capacity_kw'] ?>"
                                            data-type="<?= htmlspecialchars($pkg['system_type']) ?>"
                                            data-price="<?= number_format((float)$pkg['total_price'], 2) ?>"
                                            data-net="<?= number_format((float)$pkg['net_customer_cost'], 2) ?>">
                                        <?= htmlspecialchars($pkg['brand']) ?> &bull; <?= htmlspecialchars($pkg['title']) ?> (<?= (float)$pkg['capacity_kw'] ?> kW <?= htmlspecialchars($pkg['system_type']) ?> &bull; ₹<?= number_format((float)$pkg['total_price']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="form-text small text-muted">Selecting a package will automatically synchronize its brand, capacity, system type, and code from the master packages catalog.</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Package / Rule Name</label>
                            <input type="text" name="rule_name" id="modalRuleName" class="form-control form-control-sm" placeholder="e.g. Tata Power Solar 3kW On-Grid Plant" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Capacity (kW)</label>
                            <input type="number" step="0.1" name="capacity_kw" id="modalCapacity" class="form-control form-control-sm" placeholder="3.0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Connection Type</label>
                            <select name="connection_type" id="modalConnType" class="form-select form-select-sm">
                                <option value="On-Grid">On-Grid</option>
                                <option value="Hybrid">Hybrid</option>
                                <option value="Off-Grid">Off-Grid</option>
                                <option value="On-Grid / Hybrid">On-Grid / Hybrid</option>
                            </select>
                        </div>

                        <div class="col-12"><hr class="my-2"></div>
                        <div class="col-12">
                            <h6 class="small text-uppercase fw-bold text-muted mb-0"><i class="bi bi-bezier2 me-1 text-success"></i>Multi-Level Commission Payout Slabs (L1 to L9)</h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-success">Level 1 (Direct Referrer) ₹</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-success-subtle text-success fw-bold">₹</span>
                                <input type="number" step="0.01" name="direct_commission" id="modalDirect" class="form-control fw-bold text-success" placeholder="10000.00" required>
                            </div>
                            <div class="form-text small" style="font-size: 0.72rem;">Paid exclusively to direct sponsor</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-primary">Level 2 (Sponsor Upline) ₹</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">₹</span>
                                <input type="number" step="0.01" name="level_2" id="modalL2" class="form-control" value="1000.00" required>
                            </div>
                            <div class="form-text small" style="font-size: 0.72rem;">Default: ₹1,000.00</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">Level 3 to 9 (Each Upline) ₹</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">₹</span>
                                <input type="number" step="0.01" name="level_3_9" id="modalL39" class="form-control" value="500.00" required>
                            </div>
                            <div class="form-text small" style="font-size: 0.72rem;">Default: ₹500.00 per level</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Rule Status</label>
                            <select name="is_active" id="modalIsActive" class="form-select form-select-sm">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Effective Date</label>
                            <input type="date" name="effective_from" id="modalEffectiveFrom" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white py-2">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4"><i class="bi bi-save me-1"></i>Save Package Commission</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Keep URL in sync when switching tabs from the left sidebar
    $('#commSettingsNav a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        const tabKey = $(this).data('tab');
        if (tabKey) {
            const url = new URL(window.location);
            url.searchParams.set('tab', tabKey);
            window.history.replaceState({}, '', url);
        }
    });

    // Package Dropdown Selection Change
    $('#modalPackageId').on('change', function() {
        const selected = $(this).find('option:selected');
        const pkgId = $(this).val();
        if (pkgId && selected.length) {
            const title = selected.data('title') || '';
            const cap = parseFloat(selected.data('capacity')) || 3.0;
            const type = selected.data('type') || 'On-Grid';

            $('#modalRuleName').val(title);
            $('#modalCapacity').val(cap);
            $('#modalConnType').val(type);

            // Suggest default commission if empty
            if (!$('#modalDirect').val()) {
                if (cap <= 3.0) {
                    $('#modalDirect').val(type === 'Hybrid' ? '15000.00' : '10000.00');
                } else if (cap <= 5.0) {
                    $('#modalDirect').val(type === 'Hybrid' ? '20000.00' : '15000.00');
                } else {
                    $('#modalDirect').val(type === 'Hybrid' ? '30000.00' : '25000.00');
                }
            }
        }
    });

    // Edit Product Button Click
    $('.edit-product-btn').on('click', function() {
        const id = $(this).data('id');
        const pkgId = $(this).data('package-id');
        const name = $(this).data('name');
        const cap = $(this).data('capacity');
        const type = $(this).data('type');
        const direct = $(this).data('direct');
        const l2 = $(this).data('l2');
        const l39 = $(this).data('l39');
        const active = $(this).data('active');

        $('#modalRuleId').val(id || '');
        if (pkgId) {
            $('#modalPackageId').val(pkgId);
        }
        $('#modalRuleName').val(name);
        $('#modalCapacity').val(cap);
        $('#modalConnType').val(type);
        $('#modalDirect').val(direct);
        $('#modalL2').val(l2 || '1000.00');
        $('#modalL39').val(l39 || '500.00');
        $('#modalIsActive').val(active !== undefined ? active : 1);
        $('#productModalTitle').html('<i class="bi bi-pencil-square me-2"></i>Edit Package Commission: ' + (name || 'Solar Package'));

        const modal = new bootstrap.Modal(document.getElementById('addProductRuleModal'));
        modal.show();
    });

    $('#addProductRuleModal').on('hidden.bs.modal', function () {
        $('#modalRuleId').val('');
        $('#modalPackageId').val('');
        $('#modalRuleName').val('');
        $('#modalCapacity').val('');
        $('#modalDirect').val('');
        $('#modalL2').val('1000.00');
        $('#modalL39').val('500.00');
        $('#modalIsActive').val('1');
        $('#productModalTitle').html('<i class="bi bi-box-seam me-2"></i>Configure Solar Package Commission Rule');
    });
});
</script>

