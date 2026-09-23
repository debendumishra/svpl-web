<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Dual-Tree Network View (9-Level Tree & Pool Tree)
 */
$title = "My Network Trees — SVPL";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">My Network & Downline Organization</h3>
        <p class="text-muted small mb-0">Total Network Size: <strong><?= $stats['total_downline'] ?> Advisors</strong> across Odisha</p>
    </div>
    <div>
        <a href="<?= url('/advisor/dashboard') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>
</div>

<!-- IMMEDIATE UPPER-LINE SPONSOR ADVISOR CARD -->
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #061528 0%, #0B2545 100%); color: #FFFFFF;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900; font-size: 1.5rem;">
                    <i class="bi bi-person-up"></i>
                </div>
                <div>
                    <span class="badge bg-warning text-dark fw-bold px-2 py-1 mb-1 font-monospace" style="font-size: 0.68rem;">
                        <i class="bi bi-shield-check me-1"></i> MY DIRECT SPONSOR (UPPER-LINE)
                    </span>
                    <?php if (!empty($sponsor)): ?>
                        <h4 class="fw-bold mb-1 font-heading text-white">
                            <?= htmlspecialchars(($sponsor['first_name'] ?? '') . ' ' . ($sponsor['last_name'] ?? '')) ?>
                        </h4>
                        <div class="text-white-50 small">
                            Advisor Code: <span class="badge bg-light text-dark font-monospace fw-bold"><?= htmlspecialchars($sponsor['advisor_code'] ?? 'N/A') ?></span> | 
                            District: <strong class="text-white"><?= htmlspecialchars($sponsor['district'] ?? 'Odisha') ?></strong>
                            <?php if (!empty($sponsor['block'])): ?>
                                (<?= htmlspecialchars($sponsor['block']) ?>)
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <h4 class="fw-bold mb-1 font-heading text-white">Direct Company Placement</h4>
                        <div class="text-white-50 small">Root Advisor Partner under Dhwajja Solar India Head Office.</div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($sponsor) && !empty($sponsor['mobile'])): ?>
                <div class="d-flex gap-2">
                    <a href="tel:<?= htmlspecialchars($sponsor['mobile']) ?>" class="btn btn-outline-light btn-sm fw-semibold">
                        <i class="bi bi-telephone-fill me-1 text-success"></i> Call Sponsor (<?= htmlspecialchars($sponsor['mobile']) ?>)
                    </a>
                    <a href="https://wa.me/91<?= preg_replace('/[^0-9]/', '', $sponsor['mobile']) ?>" target="_blank" class="btn btn-success btn-sm fw-bold">
                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Tabs Navigation for Dual Tree -->
<ul class="nav nav-pills mb-4" id="networkTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" id="tree9-tab" data-bs-toggle="pill" data-bs-target="#tab-tree9" type="button">
            <i class="bi bi-people me-1"></i>1. Advisor 9-Level Tree
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="pool-tab" data-bs-toggle="pill" data-bs-target="#tab-pool" type="button">
            <i class="bi bi-diagram-3 me-1"></i>2. Pool Bonus Tree (PB1–PB11+)
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- TAB 1: 9-LEVEL TREE -->
    <div class="tab-pane fade show active" id="tab-tree9">
        <!-- 9-Level Breakdown Grid -->
        <div class="row g-2 mb-4">
            <?php for ($lvl = 1; $lvl <= 9; $lvl++): ?>
                <div class="col">
                    <div class="card p-2 text-center bg-white border shadow-sm">
                        <span class="text-muted small" style="font-size: 0.75rem;">Level <?= $lvl ?></span>
                        <h5 class="fw-bold mb-0 <?= $lvl === 1 ? 'text-primary' : 'text-dark' ?>"><?= $stats['levels'][$lvl] ?? 0 ?></h5>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="card card-svpl p-4 bg-white border-0 shadow-sm">
            <h5 class="fw-bold mb-3" style="color: #0B2545;">9-Level Downline Members List</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle small">
                    <thead class="table-light text-uppercase">
                        <tr>
                            <th>Level</th>
                            <th>Advisor Code</th>
                            <th>Full Name</th>
                            <th>Mobile</th>
                            <th>District</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($downlines)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">You have no downline team members yet. Share your referral link to build your team!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($downlines as $dl): ?>
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary fw-bold font-monospace">Level <?= $dl['depth'] ?></span></td>
                                    <td><code><?= htmlspecialchars($dl['advisor_code']) ?></code></td>
                                    <td><strong><?= htmlspecialchars($dl['first_name'] . ' ' . $dl['last_name']) ?></strong></td>
                                    <td><?= htmlspecialchars($dl['mobile']) ?></td>
                                    <td><?= htmlspecialchars($dl['district'] ?? '—') ?></td>
                                    <td>
                                        <span class="badge <?= $dl['status'] === 'ACTIVE' || $dl['status'] === 'QUALIFIED' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                            <?= htmlspecialchars($dl['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: POOL BONUS TREE (GRAPHICAL & FILE EXPLORER) -->
    <div class="tab-pane fade" id="tab-pool">
        <div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4">
            <h5 class="fw-bold mb-2 text-navy"><i class="bi bi-diagram-3 text-success me-2"></i>My Pool Tree Organization & Network</h5>
            <?php if ($poolInfo): ?>
                <div class="alert alert-success border-0 d-flex flex-wrap justify-content-between align-items-center mb-4 p-3 rounded-3">
                    <div class="d-flex align-items-center">
                        <span class="pb-badge fs-5 me-3"><?= htmlspecialchars($poolInfo['pool_label']) ?></span>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Qualified Pool Member: <?= htmlspecialchars($poolInfo['pool_label']) ?></h6>
                            <span class="small text-muted">Placement Level <?= (int)$poolInfo['pool_level'] ?> &bull; Direct Children: <strong><?= (int)$poolInfo['direct_pool_children_count'] ?> / 3</strong> &bull; Total Pool Earnings: <strong>₹<?= number_format((float)$poolInfo['pool_earnings'], 2) ?></strong></span>
                        </div>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i>Active PB Qualified</span>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning border-0 d-flex align-items-center mb-4">
                    <i class="bi bi-hourglass-split fs-2 me-3 text-warning"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Pool Qualification in Progress</h6>
                        <span class="small">Sponsor 3 active direct advisors and refer 3 direct personal customers to automatically enroll in the Pool Tree and receive your PB Number!</span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Sub-tabs for Advisor Pool Tree View -->
            <ul class="nav nav-tabs mb-4" id="advPoolSubTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-semibold" id="adv-graph-tab" data-bs-toggle="tab" data-bs-target="#adv-tab-graph" type="button">
                        <i class="bi bi-diagram-3 me-1 text-success"></i>Graphical Org Tree
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold" id="adv-explorer-tab" data-bs-toggle="tab" data-bs-target="#adv-tab-explorer" type="button">
                        <i class="bi bi-folder2-open me-1 text-warning"></i>File Explorer Tree
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Graphical Org Tree for Advisor -->
                <div class="tab-pane fade show active" id="adv-tab-graph">
                    <div class="pool-tree-viewport">
                        <div class="pool-tree-content">
                            <div class="tree">
                                <?php
                                if (!function_exists('renderAdvPoolGraphicalNode')) {
                                    function renderAdvPoolGraphicalNode(array $node): void {
                                        $hasChildren = !empty($node['children']);
                                        $isRoot = ($node['pool_number'] == 1);
                                        ?>
                                        <li>
                                            <div class="pool-node-card <?= $isRoot ? 'root-node' : '' ?>">
                                                <div class="pool-node-header">
                                                    <span class="pb-badge"><?= htmlspecialchars($node['pool_label']) ?></span>
                                                    <span class="pool-node-level">Level <?= (int)$node['pool_level'] ?></span>
                                                </div>
                                                <div class="pool-node-body">
                                                    <div class="pool-node-name"><?= htmlspecialchars($node['advisor_name']) ?></div>
                                                    <div class="pool-node-code"><?= htmlspecialchars($node['advisor_code']) ?></div>
                                                    
                                                    <div class="pool-slots-bar">
                                                        <span class="text-muted fw-bold me-1">Slots:</span>
                                                        <div class="d-flex gap-1 align-items-center">
                                                            <?php for ($s = 1; $s <= 3; $s++): ?>
                                                                <span class="slot-dot <?= $s <= (int)$node['direct_pool_children_count'] ? 'filled' : '' ?>"></span>
                                                            <?php endfor; ?>
                                                        </div>
                                                        <span class="ms-auto fw-bold text-dark"><?= (int)$node['direct_pool_children_count'] ?>/3</span>
                                                    </div>
                                                </div>
                                                <div class="pool-node-footer text-muted">
                                                    <div><i class="bi bi-people me-1 text-primary"></i><?= (int)$node['personal_customers'] ?> Cust</div>
                                                    <div class="fw-bold text-success">₹<?= number_format((float)$node['pool_earnings'], 0) ?></div>
                                                </div>
                                            </div>

                                            <?php if ($hasChildren): ?>
                                                <ul class="branch-group">
                                                    <?php foreach ($node['children'] as $child): ?>
                                                        <?php renderAdvPoolGraphicalNode($child); ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                        <?php
                                    }
                                }

                                if (!empty($nestedPoolTree)) {
                                    echo '<ul>';
                                    foreach ($nestedPoolTree as $rNode) {
                                        renderAdvPoolGraphicalNode($rNode);
                                    }
                                    echo '</ul>';
                                } else {
                                    echo '<div class="text-center py-4 text-muted">No pool members enrolled yet.</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Explorer Tree for Advisor -->
                <div class="tab-pane fade" id="adv-tab-explorer">
                    <div class="file-explorer-container p-3">
                        <div class="explorer-tree-view">
                            <?php
                            if (!function_exists('renderAdvPoolExplorerNode')) {
                                function renderAdvPoolExplorerNode(array $node): void {
                                    $hasChildren = !empty($node['children']);
                                    ?>
                                    <li class="fe-node">
                                        <div class="fe-row">
                                            <span class="fe-toggle <?= $hasChildren ? 'expanded' : 'no-children' ?>">
                                                <i class="bi bi-chevron-right"></i>
                                            </span>
                                            <i class="bi <?= $hasChildren ? 'bi-folder2-open fe-icon open' : 'bi-person-badge fe-icon' ?>"></i>
                                            <span class="fe-badge-pb"><?= htmlspecialchars($node['pool_label']) ?></span>
                                            <span class="fe-name"><?= htmlspecialchars($node['advisor_name']) ?></span>
                                            <span class="fe-code">(<?= htmlspecialchars($node['advisor_code']) ?>)</span>
                                            <div class="fe-meta">
                                                <span class="badge bg-light text-secondary border">Level <?= (int)$node['pool_level'] ?></span>
                                                <span><i class="bi bi-diagram-2 me-1 text-primary"></i><?= (int)$node['direct_pool_children_count'] ?>/3</span>
                                                <span class="fw-bold text-success">₹<?= number_format((float)$node['pool_earnings'], 0) ?></span>
                                            </div>
                                        </div>

                                        <?php if ($hasChildren): ?>
                                            <ul class="fe-branch">
                                                <?php foreach ($node['children'] as $child): ?>
                                                    <?php renderAdvPoolExplorerNode($child); ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                    <?php
                                }
                            }

                            if (!empty($nestedPoolTree)) {
                                echo '<ul class="fe-branch root-branch">';
                                foreach ($nestedPoolTree as $rNode) {
                                    renderAdvPoolExplorerNode($rNode);
                                }
                                echo '</ul>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
