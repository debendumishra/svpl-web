<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor My Customers View - Solar Luminary Design System
 */
$title = "My Direct Customers — SVPL Advisor";
$customers = $customers ?? [];

$stageMap = [
    'REGISTRATION'              => ['label' => '1. Lead Registered', 'badge' => 'bg-secondary text-white'],
    'DOCUMENTS'                 => ['label' => '2. Documents Verified', 'badge' => 'bg-info text-dark'],
    'GOVT_PORTAL'               => ['label' => '3. Govt Portal Submitted', 'badge' => 'bg-primary text-white'],
    'LOAN_APPLIED'              => ['label' => '4. Bank Loan Applied', 'badge' => 'bg-warning text-dark fw-bold'],
    'LOAN_SANCTIONED'           => ['label' => '5. Loan Sanctioned', 'badge' => 'bg-primary-subtle text-primary border border-primary-subtle fw-bold'],
    'INSTALLATION_COMMENCED'    => ['label' => '6. Installation Commenced', 'badge' => 'bg-dark text-warning fw-bold'],
    'INSTALLATION_COMPLETED'    => ['label' => '7. Installation Completed', 'badge' => 'bg-success text-white fw-bold'],
    'JE_REPORT'                 => ['label' => '8. DISCOM JE Inspection', 'badge' => 'bg-info-subtle text-dark border'],
    'SUBSIDY_APPLIED'           => ['label' => '9. Subsidy Applied', 'badge' => 'bg-primary text-white'],
    'SUBSIDY_RECEIVED'          => ['label' => '10. Subsidy Disbursed (Active)', 'badge' => 'bg-success text-white fw-bold'],
];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-navy font-heading">My Direct Customers</h3>
        <p class="text-muted small mb-0">Rooftop solar installations assisted directly by you across Odisha</p>
    </div>
    <a href="<?= url('/advisor/register-customer') ?>" class="btn btn-svpl-solar btn-sm shadow-sm fw-bold">
        <i class="bi bi-person-plus-fill me-1"></i> + Register New Customer
    </a>
</div>

<?php if (!empty($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
        <div>
            <strong>Customer Application Submitted!</strong> Customer Code <code><?= htmlspecialchars($_GET['code'] ?? '') ?></code> has been successfully registered and assigned to your Advisor account.
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle small mb-0">
            <thead class="table-light text-secondary small text-uppercase">
                <tr>
                    <th>Customer Code</th>
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    <th>DISCOM / Meter</th>
                    <th>Capacity</th>
                    <th>Live Project Stage</th>
                    <th>Handled By (BOE)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No direct customers enrolled yet. Click "+ Register New Customer" to submit customer leads.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $c): 
                        $currentStage = !empty($c['lead_stage']) ? $c['lead_stage'] : (!empty($c['status']) ? $c['status'] : 'REGISTRATION');
                        $stageInfo = $stageMap[$currentStage] ?? [
                            'label' => ucwords(strtolower(str_replace('_', ' ', $currentStage))),
                            'badge' => 'bg-secondary text-white'
                        ];
                        $custPhoto = !empty($c['photo_url']) ? resolve_photo_url($c['photo_url']) : null;
                        $cInitials = strtoupper(substr($c['first_name'] ?? 'C', 0, 1) . substr($c['last_name'] ?? 'S', 0, 1));
                    ?>
                        <tr>
                            <td><span class="badge bg-light text-dark font-monospace border fw-bold"><?= htmlspecialchars($c['customer_code']) ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative" style="width: 36px; height: 44px; border-radius: 6px; overflow: hidden; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1.5px solid #0f2d59; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center;">
                                        <?php if (!empty($custPhoto)): ?>
                                            <img src="<?= htmlspecialchars($custPhoto) ?>" alt="Customer Photo" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <span class="text-white fw-bold" style="font-size: 0.72rem; display: none; align-items: center; justify-content: center; width: 100%; height: 100%; letter-spacing: 1px;"><?= htmlspecialchars($cInitials) ?></span>
                                        <?php else: ?>
                                            <span class="text-white fw-bold" style="font-size: 0.72rem; display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; letter-spacing: 1px;"><?= htmlspecialchars($cInitials) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></div>
                                        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($c['district'] ?? 'Odisha') ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="font-monospace text-dark fw-semibold"><?= htmlspecialchars($c['mobile']) ?></td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border"><?= htmlspecialchars($c['discom_name'] ?? 'TPCODL') ?></span><br>
                                <code class="small text-navy"><?= htmlspecialchars($c['consumer_number'] ?? 'Not Linked') ?></code>
                            </td>
                            <td><span class="badge bg-warning bg-opacity-10 text-dark border border-warning fw-bold"><?= $c['proposed_solar_kw'] ?? 3.0 ?> kW</span></td>
                            <td>
                                <span class="badge <?= $stageInfo['badge'] ?> px-2 py-1" style="font-size: 0.76rem;">
                                    <i class="bi bi-record-circle me-1"></i> <?= htmlspecialchars($stageInfo['label']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($c['boe_name'])): ?>
                                    <span class="badge bg-info text-dark" title="Mobile: <?= htmlspecialchars($c['boe_mobile'] ?? 'N/A') ?>"><i class="bi bi-headset"></i> <?= htmlspecialchars($c['boe_name']) ?></span><br>
                                    <small class="text-muted"><i class="bi bi-telephone text-success me-1"></i><?= htmlspecialchars($c['boe_mobile'] ?? '') ?></small>
                                <?php else: ?>
                                    <span class="badge bg-light text-secondary border">Stage 1 Pool</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
