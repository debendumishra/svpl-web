<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Solar Field Engineer — All Assigned Installations
 */
$title = "Assigned Installations Registry — SVPL Engineer";
?>

<div class="container-fluid px-0">

    <!-- PAGE HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="<?= url('/engineer/dashboard') ?>" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Solar Installations</li>
                </ol>
            </nav>
            <h1 class="h3 font-heading fw-bold text-navy mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-tools text-warning"></i> Assigned Solar Installations
            </h1>
            <p class="text-secondary small mb-0">Full registry of rooftop solar systems dispatched for installation under your supervision.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="<?= url('/engineer/dashboard') ?>" class="btn btn-outline-navy btn-sm fw-bold">
                <i class="bi bi-speedometer2 me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="card bg-white border shadow-sm rounded-3 mb-4 p-3">
        <form method="GET" action="<?= url('/engineer/installations') ?>" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search by customer name, mobile, district, LR number, vehicle..." value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Filter by Dispatch Status --</option>
                    <option value="Dispatched" <?= ($statusFilter ?? '') === 'Dispatched' ? 'selected' : '' ?>>Dispatched</option>
                    <option value="In Transit" <?= ($statusFilter ?? '') === 'In Transit' ? 'selected' : '' ?>>In Transit</option>
                    <option value="Out for Delivery" <?= ($statusFilter ?? '') === 'Out for Delivery' ? 'selected' : '' ?>>Out for Delivery</option>
                    <option value="Delivered" <?= ($statusFilter ?? '') === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                    <option value="INSTALLATION_COMMENCED" <?= ($statusFilter ?? '') === 'INSTALLATION_COMMENCED' ? 'selected' : '' ?>>Stage 7: Commenced</option>
                    <option value="INSTALLATION_COMPLETED" <?= ($statusFilter ?? '') === 'INSTALLATION_COMPLETED' ? 'selected' : '' ?>>Stage 8: Completed</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-navy btn-sm fw-bold w-100">Filter</button>
                <a href="<?= url('/engineer/installations') ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- INSTALLATIONS TABLE -->
    <div class="card bg-white border shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase text-secondary">
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th>LR / Tracking</th>
                        <th>Customer & Site Location</th>
                        <th>Transport & Vehicle</th>
                        <th>Vendor / Make</th>
                        <th>Customer Ack</th>
                        <th>Status / Stage</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php if (empty($dispatches)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 text-secondary opacity-50 d-block mb-2"></i>
                                No installation dispatches matching the filter.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dispatches as $idx => $d): 
                            $customerName = !empty($d['customer_name']) ? $d['customer_name'] : trim(($d['cust_first'] ?? '') . ' ' . ($d['cust_last'] ?? ''));
                            if (empty($customerName)) $customerName = 'Solar Beneficiary';
                            $isAck = (int)($d['customer_acknowledged'] ?? 0) === 1;
                        ?>
                            <tr>
                                <td class="text-center text-muted fw-bold"><?= $idx + 1 ?></td>
                                <td>
                                    <span class="badge bg-light text-navy border font-monospace fw-bold"><?= htmlspecialchars($d['tracking_number'] ?? 'LR-SVPL') ?></span>
                                    <div class="text-muted" style="font-size: 0.72rem;"><?= htmlspecialchars($d['dispatch_date'] ?? '') ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold text-navy"><?= htmlspecialchars($customerName) ?></div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($d['cust_district'] ?? 'Odisha') ?>
                                        <?php if (!empty($d['cust_mobile'])): ?>
                                            • <a href="tel:<?= htmlspecialchars($d['cust_mobile']) ?>" class="text-success text-decoration-none"><i class="bi bi-telephone"></i> <?= htmlspecialchars($d['cust_mobile']) ?></a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold font-monospace text-primary"><?= htmlspecialchars($d['vehicle_number'] ?: 'Direct Transit') ?></div>
                                    <div class="text-muted" style="font-size: 0.72rem;"><?= htmlspecialchars($d['driver_name'] ?: 'Driver') ?> <?= !empty($d['driver_mobile']) ? ('(' . htmlspecialchars($d['driver_mobile']) . ')') : '' ?></div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-navy"><?= htmlspecialchars($d['vendor_name'] ?? 'OEM Store') ?></span>
                                </td>
                                <td>
                                    <?php if ($isAck): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-check-circle-fill me-1"></i> Received
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                            <i class="bi bi-hourglass-split me-1"></i> Pending
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-primary text-white"><?= htmlspecialchars($d['status'] ?? 'Dispatched') ?></span>
                                    <?php if (!empty($d['lead_stage'])): ?>
                                        <div class="text-muted font-monospace mt-1" style="font-size: 0.7rem;"><?= htmlspecialchars($d['lead_stage']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= url('/print/eway-bill/' . $d['id']) ?>" target="_blank" class="btn btn-outline-warning text-dark" title="Print E-Way Bill">
                                            <i class="bi bi-truck"></i> E-Way
                                        </a>
                                        <a href="<?= url('/print/dispatch-invoice/' . $d['id']) ?>" target="_blank" class="btn btn-outline-navy" title="Print GST Invoice">
                                            <i class="bi bi-receipt"></i> GST
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
