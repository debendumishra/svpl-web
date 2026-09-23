<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor 9-Level Network Tree Visualizer
 */
$title = $pageTitle ?? 'Advisor 9-Level Network Tree';
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-people text-primary me-2"></i>Advisor 9-Level Network Tree
            </h1>
            <p class="text-muted small mb-0">Interactive 9-Level Advisor Genealogy & Downline Hierarchy</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url('/admin/commissions') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Commission Desk
            </a>
            <a href="<?= url('/admin/commissions/pool-tree') ?>" class="btn btn-outline-success btn-sm">
                <i class="bi bi-diagram-3 me-1"></i>Pool Tree (PB1–PB11)
            </a>
        </div>
    </div>

    <!-- Advisor Selector -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="<?= url('/admin/commissions/advisor-tree') ?>" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <label class="small text-muted mb-1">Select Root Advisor</label>
                    <select name="advisor_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <?php foreach ($allAdvisors as $adv): ?>
                            <option value="<?= $adv['id'] ?>" <?= ($rootAdvisor['id'] ?? 0) == $adv['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($adv['advisor_code']) ?> — <?= htmlspecialchars($adv['first_name'] . ' ' . $adv['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Root Advisor Card -->
    <?php if ($rootAdvisor): ?>
        <div class="card border-0 shadow-sm rounded-3 mb-4 border-start border-4 border-primary">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-primary mb-2">Network Root</span>
                        <h4 class="fw-bold text-dark mb-1"><?= htmlspecialchars($rootAdvisor['first_name'] . ' ' . $rootAdvisor['last_name']) ?></h4>
                        <div class="text-muted small font-monospace">
                            Code: <?= htmlspecialchars($rootAdvisor['advisor_code']) ?> &bull; Mobile: <?= htmlspecialchars($rootAdvisor['mobile']) ?> &bull; District: <?= htmlspecialchars($rootAdvisor['district'] ?? '') ?>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success-subtle text-success fs-6 fw-semibold"><?= count($downlines) ?> Total Downlines</span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Downlines Tree Table -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-diagram-3 me-2 text-primary"></i>Downline Network by Level (Up to Level 9)</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th width="100">Level</th>
                        <th>Advisor Name & Code</th>
                        <th>Mobile</th>
                        <th>District / Block</th>
                        <th>Wallet Balance</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php if (empty($downlines)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                                No downline advisors found under this sponsor.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($downlines as $dl): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary fw-bold font-monospace">Level <?= (int)$dl['depth'] ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($dl['first_name'] . ' ' . $dl['last_name']) ?></div>
                                    <div class="text-muted font-monospace small"><?= htmlspecialchars($dl['advisor_code']) ?></div>
                                </td>
                                <td><?= htmlspecialchars($dl['mobile']) ?></td>
                                <td><?= htmlspecialchars($dl['district'] ?? '—') ?> / <?= htmlspecialchars($dl['block'] ?? '—') ?></td>
                                <td class="fw-semibold text-success">₹<?= number_format((float)($dl['wallet_balance'] ?? 0), 2) ?></td>
                                <td class="text-center">
                                    <a href="<?= url('/admin/commissions/advisor-tree?advisor_id=' . $dl['id']) ?>" class="btn btn-outline-primary btn-sm" title="Explore Sub-Tree">
                                        <i class="bi bi-diagram-2 me-1"></i>View Tree
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

