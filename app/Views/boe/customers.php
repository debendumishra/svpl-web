<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body py-2 py-md-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="w-100 overflow-x-auto pb-1 pb-md-0" style="-webkit-overflow-scrolling: touch;">
                <ul class="nav nav-pills gap-2 flex-nowrap text-nowrap" id="customerTabs">
                    <li class="nav-item">
                        <a class="nav-link <?= $tab === 'pool' ? 'active bg-warning text-dark fw-bold' : 'text-dark' ?>" href="<?= url('/boe/customers?tab=pool') ?>">
                            <i class="bi bi-inbox-fill me-1"></i> Stage 1 Pool <span class="badge bg-dark ms-1"><?= count($poolCustomers) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $tab === 'my' ? 'active bg-info text-dark fw-bold' : 'text-dark' ?>" href="<?= url('/boe/customers?tab=my') ?>">
                            <i class="bi bi-person-check-fill me-1"></i> My Claimed Leads <span class="badge bg-dark ms-1"><?= count($myCustomers) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $tab === 'all' ? 'active bg-primary text-white fw-bold' : 'text-dark' ?>" href="<?= url('/boe/customers?tab=all') ?>">
                            <i class="bi bi-collection-fill me-1"></i> All Applications <span class="badge bg-dark ms-1"><?= count($allCustomers) ?></span>
                        </a>
                    </li>
                </ul>
            </div>

            <form action="<?= url('/boe/customers') ?>" method="GET" class="d-flex gap-2 w-100 w-md-auto">
                <input type="hidden" name="tab" value="<?= htmlspecialchars($tab) ?>">
                <input type="text" name="search" class="form-control form-control-sm flex-grow-1" placeholder="Search Code, Name, District..." value="<?= htmlspecialchars($search) ?>" style="max-width: 280px;">
                <button type="submit" class="btn btn-sm btn-primary text-nowrap"><i class="bi bi-search"></i> Search</button>
            </form>
        </div>
    </div>
</div>

<?php 
$activeList = ($tab === 'my') ? $myCustomers : (($tab === 'all') ? $allCustomers : $poolCustomers);
?>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-outfit fw-bold text-navy">
            <?php if ($tab === 'my'): ?>
                <i class="bi bi-person-check-fill text-info me-2"></i> Customers Handled By You
            <?php elseif ($tab === 'all'): ?>
                <i class="bi bi-collection-fill text-primary me-2"></i> All Solar Panel Applications
            <?php else: ?>
                <i class="bi bi-inbox-fill text-warning me-2"></i> Unassigned Stage 1 Pool (Available to Process)
            <?php endif; ?>
        </h6>
    </div>
    <div class="card-body p-0">
        <?php if (empty($activeList)): ?>
            <div class="p-5 text-center text-muted">
                <i class="bi bi-folder-x fs-1 text-secondary"></i>
                <h5 class="mt-3 text-dark">No Customer Applications Found</h5>
                <p class="small mb-0">No solar panel applications match the selected view or search criteria.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Customer Name</th>
                            <th>Mobile & Email</th>
                            <th>District / GP</th>
                            <th>Stage & Status</th>
                            <th>Advisor Code</th>
                            <th>Handled By (BOE)</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activeList as $c): ?>
                        <tr>
                            <td><strong class="text-navy"><?= htmlspecialchars($c['customer_code']) ?></strong></td>
                            <td>
                                <strong><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></strong><br>
                                <small class="text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($c['village'] ?? $c['block']) ?></small>
                            </td>
                            <td>
                                <i class="bi bi-telephone text-success"></i> <?= htmlspecialchars($c['mobile']) ?><br>
                                <small class="text-muted"><?= htmlspecialchars($c['email'] ?? 'No email') ?></small>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($c['district']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($c['gram_panchayat']) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark fw-semibold"><?= htmlspecialchars($c['lead_stage'] ?? 'REGISTRATION') ?></span><br>
                                <small class="text-muted"><?= htmlspecialchars($c['lead_status'] ?? $c['status']) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><?= htmlspecialchars($c['advisor_code'] ?? 'DIRECT') ?></span>
                            </td>
                            <td>
                                <?php if (!empty($c['boe_name'])): ?>
                                    <span class="badge bg-info text-dark"><i class="bi bi-person-badge"></i> <?= htmlspecialchars($c['boe_name']) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Unassigned (Pool)</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= url('/boe/customers/' . $c['id']) ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-shield-check me-1"></i> Process & Manage
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
