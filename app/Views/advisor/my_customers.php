<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Advisor My Customers View - Direct Customers & Downline Network Customers with BOE Issue Visibility
 */
$title = "My Customers & Downline Network — SVPL Advisor";
$directCustomers = $directCustomers ?? [];
$teamCustomers = $teamCustomers ?? [];

$stageMap = [
    'REGISTRATION'              => ['label' => '1. Lead Registered', 'badge' => 'bg-secondary text-white'],
    'DOCUMENTS'                 => ['label' => '2. Documents Verification', 'badge' => 'bg-info text-dark'],
    'GOVT_PORTAL'               => ['label' => '3. Govt Portal Submitted', 'badge' => 'bg-primary text-white'],
    'LOAN_APPLIED'              => ['label' => '4. Bank Loan Applied', 'badge' => 'bg-warning text-dark fw-bold'],
    'LOAN_SANCTIONED'           => ['label' => '5. Loan Sanctioned', 'badge' => 'bg-primary-subtle text-primary border border-primary-subtle fw-bold'],
    'INSTRUMENT_DESPATCHED'     => ['label' => '6. Instrument Despatched', 'badge' => 'bg-warning text-dark fw-bold'],
    'INSTALLATION_COMMENCED'    => ['label' => '7. Installation Commenced', 'badge' => 'bg-dark text-warning fw-bold'],
    'INSTALLATION_COMPLETED'    => ['label' => '8. Installation Completed', 'badge' => 'bg-primary text-white fw-bold'],
    'JE_REPORT'                 => ['label' => '9. DISCOM JE Inspection', 'badge' => 'bg-info-subtle text-dark border'],
    'NET_METER'                 => ['label' => '10. Net Meter Installed', 'badge' => 'bg-info text-white fw-bold'],
    'INTIMATION_TO_MMG'         => ['label' => '11. Intimation to MMG', 'badge' => 'bg-secondary-subtle text-dark border'],
    'MMG_METER_REPORT'          => ['label' => '12. MMG Meter Report', 'badge' => 'bg-purple text-white bg-opacity-75'],
    'BANK_SECOND_INSTALLMENT'   => ['label' => '13. Bank 2nd Installment', 'badge' => 'bg-success-subtle text-success border fw-bold'],
    'SUBSIDY_APPLIED'           => ['label' => '14. Subsidy Applied', 'badge' => 'bg-primary text-white'],
    'SUBSIDY_RECEIVED'          => ['label' => '15. Subsidy Disbursed (Active)', 'badge' => 'bg-success text-white fw-bold'],
];
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 animate-fade-in">
    <div>
        <h3 class="fw-bold mb-1 text-navy font-heading">
            <i class="bi bi-people-fill text-warning me-2"></i> Solar Customer Portfolio
        </h3>
        <p class="text-secondary small mb-0">
            Track your direct customer applications and monitor downline advisors' installations across Odisha.
        </p>
    </div>
    <?php if (!empty($advisor['joining_fee_paid'])): ?>
        <a href="<?= url('/advisor/register-customer') ?>" class="btn btn-svpl-solar btn-sm shadow-sm fw-bold px-3">
            <i class="bi bi-person-plus-fill me-1"></i> + Register New Customer
        </a>
    <?php endif; ?>
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

<!-- NAVIGATION TABS: DIRECT VS DOWNLINE CUSTOMERS -->
<ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="customerTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold d-flex align-items-center gap-2" id="direct-tab" data-bs-toggle="tab" data-bs-target="#direct-customers" type="button" role="tab">
            <i class="bi bi-person-check-fill"></i>
            <span>My Direct Customers</span>
            <span class="badge bg-primary text-white rounded-pill ms-1"><?= count($directCustomers) ?></span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold d-flex align-items-center gap-2" id="team-tab" data-bs-toggle="tab" data-bs-target="#team-customers" type="button" role="tab">
            <i class="bi bi-diagram-3-fill"></i>
            <span>Downline Network Customers</span>
            <span class="badge bg-secondary text-white rounded-pill ms-1"><?= count($teamCustomers) ?></span>
        </button>
    </li>
</ul>

<div class="tab-content" id="customerTabsContent">
    
    <!-- TAB 1: MY DIRECT CUSTOMERS (FULL ACCESS + EDIT & DOCUMENT RECTIFICATION) -->
    <div class="tab-pane fade show active" id="direct-customers" role="tabpanel">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-heading fw-bold text-navy mb-0">
                    Direct Sponsored Solar Applications
                </h5>
                <span class="text-muted small">You can edit details and replace KYC documents for all your direct customers.</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle small mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th>Customer Code</th>
                            <th>Customer Name</th>
                            <th>Mobile</th>
                            <th>DISCOM / Meter</th>
                            <th>Capacity</th>
                            <th>Live Stage & Status</th>
                            <th>BOE Issue / Review Remarks</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($directCustomers)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-person-x fs-2 d-block mb-2 text-secondary"></i>
                                    No direct customer applications submitted yet.
                                    <?php if (!empty($advisor['joining_fee_paid'])): ?>
                                        <div class="mt-2">
                                            <a href="<?= url('/advisor/register-customer') ?>" class="btn btn-svpl-solar btn-sm fw-bold">
                                                <i class="bi bi-plus-circle me-1"></i> Register Your First Customer
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($directCustomers as $c): 
                                $currentStage = !empty($c['lead_stage']) ? $c['lead_stage'] : (!empty($c['status']) ? $c['status'] : 'REGISTRATION');
                                $stageInfo = $stageMap[$currentStage] ?? [
                                    'label' => ucwords(strtolower(str_replace('_', ' ', $currentStage))),
                                    'badge' => 'bg-secondary text-white'
                                ];
                                $hasIssue = in_array($c['lead_status'] ?? '', ['Rejected', 'Action Required', 'Documents Pending']) || !empty($c['issue_doc_count']);
                            ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark font-monospace border fw-bold"><?= htmlspecialchars($c['customer_code']) ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-navy"><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></div>
                                        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($c['district'] ?? 'Odisha') ?></small>
                                    </td>
                                    <td class="font-monospace text-dark fw-semibold"><?= htmlspecialchars($c['mobile']) ?></td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary border"><?= htmlspecialchars($c['discom_name'] ?? 'TPCODL') ?></span><br>
                                        <code class="small text-navy"><?= htmlspecialchars($c['consumer_number'] ?? 'Not Linked') ?></code>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning bg-opacity-10 text-dark border border-warning fw-bold"><?= $c['proposed_solar_kw'] ?? 3.0 ?> kW</span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $stageInfo['badge'] ?> px-2 py-1" style="font-size: 0.74rem;">
                                            <i class="bi bi-record-circle me-1"></i> <?= htmlspecialchars($stageInfo['label']) ?>
                                        </span>
                                        <?php if (!empty($c['lead_status'])): ?>
                                            <div class="mt-1">
                                                <span class="badge <?= $hasIssue ? 'bg-danger' : 'bg-light text-dark border' ?>" style="font-size: 0.7rem;">
                                                    <?= htmlspecialchars($c['lead_status']) ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($c['latest_boe_remark'])): ?>
                                            <div class="p-2 rounded bg-warning bg-opacity-10 border border-warning small text-danger fw-semibold" style="max-width: 220px; font-size: 0.75rem;">
                                                <i class="bi bi-exclamation-circle-fill me-1 text-warning"></i>
                                                <?= htmlspecialchars($c['latest_boe_remark']) ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small">In normal workflow</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="<?= url('/advisor/customers/' . $c['id'] . '/edit') ?>" class="btn btn-outline-primary btn-sm fw-semibold" title="Edit Customer Details">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <a href="<?= url('/advisor/customers/' . $c['id'] . '/documents') ?>" class="btn btn-outline-warning btn-sm text-dark fw-semibold" title="Manage KYC Documents">
                                                <i class="bi bi-folder2-open"></i> Docs (<?= $c['doc_count'] ?? 0 ?>)
                                            </a>
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

    <!-- TAB 2: DOWNLINE NETWORK CUSTOMERS (NAME ONLY, NO CONTACT INFO, BOE ISSUES VISIBLE) -->
    <div class="tab-pane fade" id="team-customers" role="tabpanel">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h5 class="font-heading fw-bold text-navy mb-0">
                        Downline Advisors' Customer Installations (9-Level Team)
                    </h5>
                    <div class="text-secondary small mt-1">
                        <i class="bi bi-shield-lock-fill text-warning me-1"></i> Customer contact details are protected. When a BOE issue is raised, contact the direct sponsor advisor below to resolve it.
                    </div>
                </div>
                <span class="badge bg-info-subtle text-info border px-3 py-2 fw-semibold">
                    <i class="bi bi-diagram-3 me-1"></i> Network Oversight
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle small mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th>Customer Code</th>
                            <th>Customer Name</th>
                            <th>District / Block</th>
                            <th>Capacity</th>
                            <th>Registered By (Direct Advisor)</th>
                            <th>Live Stage & Status</th>
                            <th>BOE Issue / Requirement</th>
                            <th class="text-end">Contact Advisor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($teamCustomers)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-diagram-2 fs-2 d-block mb-2 text-secondary"></i>
                                    No downline customer installations found in your 9-level tree yet.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($teamCustomers as $tc): 
                                $currentStage = !empty($tc['lead_stage']) ? $tc['lead_stage'] : 'REGISTRATION';
                                $stageInfo = $stageMap[$currentStage] ?? [
                                    'label' => ucwords(strtolower(str_replace('_', ' ', $currentStage))),
                                    'badge' => 'bg-secondary text-white'
                                ];
                                $hasIssue = in_array($tc['lead_status'] ?? '', ['Rejected', 'Action Required', 'Documents Pending']) || !empty($tc['latest_boe_remark']);
                            ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark font-monospace border fw-bold"><?= htmlspecialchars($tc['customer_code']) ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-navy"><?= htmlspecialchars($tc['first_name'] . ' ' . $tc['last_name']) ?></div>
                                        <small class="text-muted"><i class="bi bi-shield-fill-check text-success me-1"></i>Contact Protected</small>
                                    </td>
                                    <td>
                                        <div><?= htmlspecialchars($tc['district'] ?? 'Odisha') ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($tc['block'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning bg-opacity-10 text-dark border border-warning fw-bold"><?= $tc['proposed_solar_kw'] ?? 3.0 ?> kW</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary px-1 font-monospace" style="font-size: 0.68rem;">L<?= $tc['level_depth'] ?? 1 ?></span>
                                            <div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($tc['direct_advisor_name']) ?></div>
                                                <small class="text-muted font-monospace"><?= htmlspecialchars($tc['advisor_code']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?= $stageInfo['badge'] ?> px-2 py-1" style="font-size: 0.74rem;">
                                            <i class="bi bi-record-circle me-1"></i> <?= htmlspecialchars($stageInfo['label']) ?>
                                        </span>
                                        <?php if (!empty($tc['lead_status'])): ?>
                                            <div class="mt-1">
                                                <span class="badge <?= $hasIssue ? 'bg-danger' : 'bg-light text-dark border' ?>" style="font-size: 0.7rem;">
                                                    <?= htmlspecialchars($tc['lead_status']) ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($tc['latest_boe_remark'])): ?>
                                            <div class="p-2 rounded bg-danger bg-opacity-10 border border-danger small text-danger fw-semibold" style="max-width: 240px; font-size: 0.75rem;">
                                                <i class="bi bi-exclamation-triangle-fill me-1 text-danger"></i>
                                                <?= htmlspecialchars($tc['latest_boe_remark']) ?>
                                                <?php if (!empty($tc['boe_auditor_name'])): ?>
                                                    <div class="text-muted mt-1" style="font-size: 0.68rem;">— <?= htmlspecialchars($tc['boe_auditor_name']) ?></div>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small">Normal Processing</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if (!empty($tc['direct_advisor_mobile'])): ?>
                                            <a href="tel:<?= htmlspecialchars($tc['direct_advisor_mobile']) ?>" class="btn btn-outline-success btn-sm fw-semibold" title="Call Direct Advisor to Rectify Application">
                                                <i class="bi bi-telephone-fill me-1"></i> Call Advisor
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">Advisor ID #<?= $tc['direct_advisor_id'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
