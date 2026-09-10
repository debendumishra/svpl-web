<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Solar Equipment & Kit Dispatches View (Solar Luminary Design System)
 */
$title = "Equipment & Kit Dispatches — SVPL Admin";
$totalCount = count($dispatches ?? []);
$deliveredCount = 0;
$inTransitCount = 0;
foreach ($dispatches as $d) {
    if ($d['status'] === 'Delivered') $deliveredCount++;
    else $inTransitCount++;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">Solar Equipment & Advisor Kit Fulfillment</h3>
            <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.72rem;">Logistics & Dispatch</span>
        </div>
        <p class="text-secondary small mb-0">Track shipments for advisor welcome induction kits, solar panels, inverters, and BOS packages</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-svpl-solar btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNewDispatch">
            <i class="bi bi-box-seam-fill me-1"></i> + Create New Dispatch
        </button>
        <a href="<?= url('/admin/export/csv?type=dispatches') ?>" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
        </a>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
        <div><?= htmlspecialchars($success) ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
        <div><?= htmlspecialchars($error) ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- 3 SUMMARY STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="p-3 bg-white rounded-3 shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small fw-bold text-uppercase">Total Dispatches</span>
                <h4 class="fw-bold text-navy mb-0 mt-1"><?= $totalCount ?></h4>
            </div>
            <div class="p-3 bg-primary-subtle text-primary rounded-circle fs-4">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-white rounded-3 shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small fw-bold text-uppercase">In Transit / Dispatched</span>
                <h4 class="fw-bold text-warning-emphasis mb-0 mt-1"><?= $inTransitCount ?></h4>
            </div>
            <div class="p-3 bg-warning-subtle text-warning rounded-circle fs-4">
                <i class="bi bi-truck"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-white rounded-3 shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small fw-bold text-uppercase">Delivered & Completed</span>
                <h4 class="fw-bold text-success mb-0 mt-1"><?= $deliveredCount ?></h4>
            </div>
            <div class="p-3 bg-success-subtle text-success rounded-circle fs-4">
                <i class="bi bi-check2-all"></i>
            </div>
        </div>
    </div>
</div>

<!-- DISPATCHES TABLE -->
<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle small mb-0">
            <thead class="table-light">
                <tr class="text-secondary text-uppercase">
                    <th>Tracking #</th>
                    <th>Type & Purpose</th>
                    <th>Recipient (Advisor / Lead)</th>
                    <th>Courier Partner</th>
                    <th>Items Included</th>
                    <th>Dispatch Date</th>
                    <th>Status</th>
                    <th class="text-end">Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dispatches)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam fs-1 d-block mb-2 text-secondary"></i>
                            No equipment dispatches recorded yet. Click <strong>"+ Create New Dispatch"</strong> to add one.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($dispatches as $d): ?>
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace fw-bold"><?= htmlspecialchars($d['tracking_number']) ?></span>
                            </td>
                            <td>
                                <?php if ($d['dispatch_type'] === 'ADVISOR_KIT'): ?>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                        <i class="bi bi-person-badge"></i> Advisor Induction Kit
                                    </span>
                                <?php elseif ($d['dispatch_type'] === 'SOLAR_EQUIPMENT'): ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        <i class="bi bi-sun"></i> Solar Equipment
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border">
                                        <?= htmlspecialchars($d['dispatch_type']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($d['advisor_name'])): ?>
                                    <div class="fw-bold text-navy"><?= htmlspecialchars($d['advisor_name']) ?></div>
                                    <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.68rem;"><?= htmlspecialchars($d['advisor_code']) ?></span>
                                <?php elseif (!empty($d['lead_code'])): ?>
                                    <div class="fw-bold text-navy">Lead Customer</div>
                                    <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.68rem;"><?= htmlspecialchars($d['lead_code']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">Direct SVPL</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-semibold text-navy"><?= htmlspecialchars($d['courier_partner']) ?></div>
                            </td>
                            <td style="max-width: 250px;">
                                <span class="text-truncate d-inline-block" style="max-width: 240px;" title="<?= htmlspecialchars($d['items_included'] ?? '') ?>">
                                    <?= htmlspecialchars($d['items_included'] ?? 'Standard Kit') ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($d['dispatch_date'] ?? 'N/A') ?></td>
                            <td>
                                <?php if ($d['status'] === 'Delivered'): ?>
                                    <span class="badge bg-success text-white"><i class="bi bi-check-circle me-1"></i> Delivered</span>
                                    <?php if (!empty($d['delivery_date'])): ?>
                                        <div class="text-muted" style="font-size: 0.68rem;"><?= htmlspecialchars($d['delivery_date']) ?></div>
                                    <?php endif; ?>
                                <?php elseif ($d['status'] === 'In Transit'): ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-truck me-1"></i> In Transit</span>
                                <?php elseif ($d['status'] === 'Out for Delivery'): ?>
                                    <span class="badge bg-info text-dark"><i class="bi bi-geo-alt me-1"></i> Out for Delivery</span>
                                <?php else: ?>
                                    <span class="badge bg-primary-subtle text-primary border"><i class="bi bi-box-seam me-1"></i> <?= htmlspecialchars($d['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <form method="POST" action="<?= url('/admin/dispatches/update-status') ?>" class="d-inline-flex align-items-center gap-1">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="dispatch_id" value="<?= $d['id'] ?>">
                                    <select name="status" class="form-select form-select-sm py-0" style="font-size: 0.75rem; width: 110px;" onchange="this.form.submit()">
                                        <option value="Dispatched" <?= $d['status'] === 'Dispatched' ? 'selected' : '' ?>>Dispatched</option>
                                        <option value="In Transit" <?= $d['status'] === 'In Transit' ? 'selected' : '' ?>>In Transit</option>
                                        <option value="Out for Delivery" <?= $d['status'] === 'Out for Delivery' ? 'selected' : '' ?>>Out for Delivery</option>
                                        <option value="Delivered" <?= $d['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREATE NEW DISPATCH -->
<div class="modal fade" id="modalNewDispatch" tabindex="-1" aria-labelledby="modalNewDispatchLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="<?= url('/admin/dispatches/create') ?>">
                <?= csrf_field() ?>
                <div class="modal-header bg-navy text-white">
                    <h5 class="modal-title font-heading fw-bold" id="modalNewDispatchLabel">
                        <i class="bi bi-box-seam-fill text-warning me-2"></i> Create Equipment / Kit Dispatch
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Dispatch Type *</label>
                            <select name="dispatch_type" class="form-select" required>
                                <option value="ADVISOR_KIT">Advisor Welcome Induction Kit</option>
                                <option value="SOLAR_EQUIPMENT">Solar Panels & Inverter Package</option>
                                <option value="NET_METER">DISCOM Net Meter & BOS Kit</option>
                                <option value="MARKETING_MATERIAL">Canopy, Standee & Marketing Material</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Recipient Advisor (Optional)</label>
                            <select name="advisor_id" class="form-select">
                                <option value="">-- None / Customer Lead --</option>
                                <?php foreach (($advisors ?? []) as $adv): ?>
                                    <option value="<?= $adv['id'] ?>">
                                        <?= htmlspecialchars($adv['first_name'] . ' ' . $adv['last_name']) ?> (<?= htmlspecialchars($adv['advisor_code']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Recipient Customer Lead (Optional)</label>
                            <select name="lead_id" class="form-select">
                                <option value="">-- None / Advisor Kit --</option>
                                <?php foreach (($leads ?? []) as $ld): ?>
                                    <option value="<?= $ld['id'] ?>">
                                        <?= htmlspecialchars($ld['lead_code']) ?> - <?= htmlspecialchars($ld['first_name'] . ' ' . $ld['last_name']) ?> (<?= htmlspecialchars($ld['district']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Courier / Logistics Partner *</label>
                            <select name="courier_partner" class="form-select" required>
                                <option value="SVPL Dedicated Logistics Odisha">SVPL Dedicated Logistics Odisha</option>
                                <option value="DTDC Express">DTDC Express</option>
                                <option value="Blue Dart Express">Blue Dart Express</option>
                                <option value="India Post Speed Post">India Post Speed Post</option>
                                <option value="Trackon Courier">Trackon Courier</option>
                                <option value="Direct Office Handover">Direct Office Handover</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tracking / Consignment Number</label>
                            <input type="text" name="tracking_number" class="form-control font-monospace" placeholder="e.g. TRK-SVPL-202609-123 (Auto if blank)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Dispatch Date *</label>
                            <input type="date" name="dispatch_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold small">Items Included in Shipment *</label>
                            <textarea name="items_included" class="form-control" rows="2" placeholder="e.g. Official Photo ID Card, Appointment Letter, SVPL Bag, Doorstep QR Code, Marketing Flyers..." required>Official Photo ID Card, Appointment Letter, SVPL Bag, Doorstep QR Code, Marketing Flyers</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold small">Delivery Address</label>
                            <input type="text" name="delivery_address" class="form-control" placeholder="Recipient full delivery address">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-solar fw-bold px-4">
                        <i class="bi bi-truck me-1"></i> Save & Dispatch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
