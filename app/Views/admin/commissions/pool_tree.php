<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Pool Bonus Network Tree (PB1–PB11+) — Graphical Org Tree & File Explorer View
 * Featuring High-Contrast Dynamic Backgrounds, Multi-Color Level Nodes & Canvas Controls
 */
$title = $pageTitle ?? 'Pool Bonus Network Tree (PB1–PB11+)';
?>

<div class="container-fluid py-4">
    <!-- Top Header & Navigation -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h1 class="h3 fw-bold text-dark mb-0">
                    <i class="bi bi-diagram-3-fill text-success me-2"></i>Pool Bonus Network Tree (PB1–PB11+)
                </h1>
                <span class="badge bg-warning text-dark border border-warning fw-bold px-2 py-1">
                    3-Child Node Capacity
                </span>
            </div>
            <p class="text-muted small mb-0 mt-1">Dual Presentation: High-Contrast Graphical Org Tree & Collapsible File Explorer Directory</p>
        </div>
        <div class="d-flex gap-2 mt-2 mt-md-0">
            <a href="<?= url('/admin/commissions') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Commission Desk
            </a>
            <a href="<?= url('/admin/commissions/advisor-tree') ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-people me-1"></i>Advisor 9-Level Tree
            </a>
            <a href="<?= url('/admin/commissions/settings?tab=pool_bonus') ?>" class="btn btn-primary btn-sm shadow-sm">
                <i class="bi bi-sliders me-1"></i>Pool Rules
            </a>
        </div>
    </div>

    <!-- KPI Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-success">
                <div class="card-body p-3">
                    <div class="text-muted small fw-semibold text-uppercase">Enrolled Pool Members</div>
                    <div class="h3 fw-bold text-dark mt-2 mb-0"><?= count($poolMembers) ?> Advisors</div>
                    <div class="text-muted small mt-1"><i class="bi bi-shield-check text-success me-1"></i>Qualified &ge; 3 Adv + 3 Cust</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-primary">
                <div class="card-body p-3">
                    <div class="text-muted small fw-semibold text-uppercase">Max Pool Depth</div>
                    <div class="h3 fw-bold text-primary mt-2 mb-0">Level <?= (int)($poolStats['max_level'] ?? 1) ?> <span class="fs-6 fw-normal text-muted">/ <?= (int)($rule['max_pool_levels'] ?? 11) ?></span></div>
                    <div class="text-muted small mt-1">Breadth-first 3-child filling</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-warning">
                <div class="card-body p-3">
                    <div class="text-muted small fw-semibold text-uppercase">Total Pool Bonus Paid</div>
                    <div class="h3 fw-bold text-warning-emphasis mt-2 mb-0">₹<?= number_format((float)($poolStats['total_earnings'] ?? 0), 2) ?></div>
                    <div class="text-muted small mt-1">Settled to advisor wallets</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-4 border-info">
                <div class="card-body p-3">
                    <div class="text-muted small fw-semibold text-uppercase">Pool Payout Structure</div>
                    <div class="h5 fw-bold text-dark mt-2 mb-0">L1: ₹<?= number_format((float)($rule['level_1_amount'] ?? 1000), 0) ?> | L2–L11: ₹<?= number_format((float)($rule['subsequent_level_amount'] ?? 500), 0) ?></div>
                    <div class="text-muted small mt-1">5% TDS + 5% Admin deduction</div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Switcher Tabs -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-2">
            <ul class="nav nav-pills nav-fill gap-2" id="poolViewTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-semibold" id="tab-graph-btn" data-bs-toggle="pill" data-bs-target="#tab-graphical-view" type="button">
                        <i class="bi bi-diagram-3-fill me-2 text-success"></i>1. Graphical Org-Tree Diagram
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold" id="tab-explorer-btn" data-bs-toggle="pill" data-bs-target="#tab-explorer-view" type="button">
                        <i class="bi bi-folder2-open me-2 text-warning"></i>2. File Explorer Folder Tree
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold" id="tab-roster-btn" data-bs-toggle="pill" data-bs-target="#tab-roster-view" type="button">
                        <i class="bi bi-table me-2 text-primary"></i>3. Pool Members Roster Directory
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <?php if (empty($poolMembers)): ?>
        <div class="card border-0 shadow-sm rounded-3 p-5 text-center bg-white">
            <div class="py-4">
                <i class="bi bi-diagram-3 fs-1 text-muted d-block mb-3"></i>
                <h4 class="fw-bold text-dark">No Qualified Pool Members Yet</h4>
                <p class="text-muted mx-auto" style="max-width: 500px;">
                    Advisors enter the Pool Tree sequentially (<span class="font-monospace fw-bold text-success">PB1, PB2, PB3...</span>) automatically once they achieve <strong>&ge; 3 Direct Active Advisors</strong> and <strong>&ge; 3 Personal Customers</strong>.
                </p>
                <a href="<?= url('/admin/commissions/settings?tab=pool_bonus') ?>" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-sliders me-1"></i>View Pool Qualification Rules
                </a>
            </div>
        </div>
    <?php else: ?>

        <div class="tab-content" id="poolViewTabContent">
            
            <!-- ================================================================= -->
            <!-- TAB 1: GRAPHICAL ORG-TREE VIEW                                    -->
            <!-- ================================================================= -->
            <div class="tab-pane fade show active" id="tab-graphical-view" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Interactive Org-Chart</span>
                            <span class="text-muted small">Pan & Zoom, switch backgrounds, or click any node for instant profile drill-down</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <!-- Background Theme Selector -->
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-dark active" id="btnThemeBlueprint" title="Blueprint Cyber Matrix">
                                    <i class="bi bi-grid-3x3 me-1 text-info"></i>Matrix
                                </button>
                                <button type="button" class="btn btn-outline-dark" id="btnThemeAurora" title="Midnight Aurora">
                                    <i class="bi bi-stars me-1 text-warning"></i>Aurora
                                </button>
                                <button type="button" class="btn btn-outline-dark" id="btnThemeLight" title="Solar Light Studio">
                                    <i class="bi bi-sun me-1 text-primary"></i>Light
                                </button>
                            </div>

                            <!-- Zoom Controls -->
                            <div class="btn-group btn-group-sm ms-2">
                                <button type="button" class="btn btn-outline-secondary" id="btnZoomIn" title="Zoom In"><i class="bi bi-zoom-in"></i></button>
                                <button type="button" class="btn btn-outline-secondary fw-bold" id="btnZoomReset" title="Reset Zoom (100%)">100%</button>
                                <button type="button" class="btn btn-outline-secondary" id="btnZoomOut" title="Zoom Out"><i class="bi bi-zoom-out"></i></button>
                            </div>
                            
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btnExpandAllGraph" title="Expand All Branches">
                                <i class="bi bi-arrows-expand me-1"></i>Expand All
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="pool-tree-viewport theme-blueprint" id="poolTreeViewport">
                            <div class="pool-tree-content" id="poolTreeContent">
                                <div class="tree">
                                    <?php
                                    /**
                                     * Recursive PHP function to render Graphical Org Tree Nodes
                                     */
                                    function renderGraphicalPoolNode(array $node): void {
                                        $hasChildren = !empty($node['children']);
                                        $childCount = count($node['children'] ?? []);
                                        $lvl = (int)($node['pool_level'] ?? 1);
                                        $levelClass = 'node-level-' . min(6, $lvl);
                                        $nodeJson = htmlspecialchars(json_encode($node), ENT_QUOTES, 'UTF-8');
                                        
                                        // Initials
                                        $nameParts = explode(' ', trim($node['advisor_name']));
                                        $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                                        ?>
                                        <li>
                                            <div class="pool-node-card <?= $levelClass ?>" 
                                                 data-id="<?= $node['id'] ?>" 
                                                 data-node="<?= $nodeJson ?>">
                                                <div class="pool-node-header">
                                                    <span class="pb-badge"><?= htmlspecialchars($node['pool_label']) ?></span>
                                                    <span class="pool-node-level">Level <?= $lvl ?></span>
                                                </div>
                                                <div class="pool-node-body">
                                                    <div class="node-avatar-row">
                                                        <div class="node-avatar"><?= $initials ?></div>
                                                        <div class="overflow-hidden">
                                                            <div class="pool-node-name" title="<?= htmlspecialchars($node['advisor_name']) ?>">
                                                                <?= htmlspecialchars($node['advisor_name']) ?>
                                                            </div>
                                                            <div class="pool-node-code">
                                                                <?= htmlspecialchars($node['advisor_code']) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- 3-Slot Child Indicator -->
                                                    <div class="pool-slots-bar" title="<?= (int)$node['direct_pool_children_count'] ?> of 3 child slots occupied">
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
                                                    <div><i class="bi bi-people-fill me-1 text-primary"></i><strong><?= (int)$node['personal_customers'] ?></strong> Cust</div>
                                                    <div class="fw-bold text-success">₹<?= number_format((float)$node['pool_earnings'], 0) ?></div>
                                                </div>

                                                <?php if ($hasChildren): ?>
                                                    <button type="button" class="node-expander-btn btn-toggle-branch" title="Toggle <?= $childCount ?> child branch(es)">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>

                                            <?php if ($hasChildren): ?>
                                                <ul class="branch-group">
                                                    <?php foreach ($node['children'] as $child): ?>
                                                        <?php renderGraphicalPoolNode($child); ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                        <?php
                                    }

                                    // Render Root Nodes
                                    echo "<ul>";
                                    foreach ($nestedTree as $rootNode) {
                                        renderGraphicalPoolNode($rootNode);
                                    }
                                    echo "</ul>";
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================================================================= -->
            <!-- TAB 2: FILE EXPLORER FOLDER TREE VIEW                             -->
            <!-- ================================================================= -->
            <div class="tab-pane fade" id="tab-explorer-view" role="tabpanel">
                <div class="row g-3">
                    <!-- Left: File Explorer Tree Hierarchy -->
                    <div class="col-lg-7">
                        <div class="file-explorer-container">
                            <div class="explorer-search-bar d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <div class="input-group input-group-sm flex-grow-1" style="max-width: 320px;">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" id="feSearchInput" class="form-control border-start-0" placeholder="Filter by Name, Code, or PB Number (e.g. PB3)...">
                                </div>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnFeExpandAll">
                                        <i class="bi bi-folder-symlink me-1"></i>Expand All
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnFeCollapseAll">
                                        <i class="bi bi-folder me-1"></i>Collapse
                                    </button>
                                </div>
                            </div>

                            <div class="explorer-tree-view" id="feTreeContainer">
                                <?php
                                /**
                                 * Recursive function to render File Explorer Directory Tree
                                 */
                                function renderFileExplorerNode(array $node, bool $isRoot = false): void {
                                    $hasChildren = !empty($node['children']);
                                    $lvl = (int)($node['pool_level'] ?? 1);
                                    $nodeJson = htmlspecialchars(json_encode($node), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <li class="fe-node" data-id="<?= $node['id'] ?>" data-search="<?= strtolower($node['pool_label'] . ' ' . $node['advisor_name'] . ' ' . $node['advisor_code']) ?>">
                                        <div class="fe-row" data-node="<?= $nodeJson ?>">
                                            <span class="fe-toggle <?= $hasChildren ? 'expanded' : 'no-children' ?>">
                                                <i class="bi bi-chevron-right"></i>
                                            </span>
                                            <i class="bi <?= $hasChildren ? 'bi-folder2-open fe-icon open' : 'bi-person-badge-fill fe-icon text-primary' ?>"></i>
                                            <span class="fe-badge-pb <?= $lvl === 1 ? 'level-1' : '' ?>"><?= htmlspecialchars($node['pool_label']) ?></span>
                                            <span class="fe-name"><?= htmlspecialchars($node['advisor_name']) ?></span>
                                            <span class="fe-code">(<?= htmlspecialchars($node['advisor_code']) ?>)</span>
                                            
                                            <div class="fe-meta">
                                                <span class="badge bg-light text-secondary border">L<?= $lvl ?></span>
                                                <span title="Direct Children"><i class="bi bi-diagram-2 me-1 text-primary"></i><?= (int)$node['direct_pool_children_count'] ?>/3</span>
                                                <span class="fw-bold text-success">₹<?= number_format((float)$node['pool_earnings'], 0) ?></span>
                                            </div>
                                        </div>

                                        <?php if ($hasChildren): ?>
                                            <ul class="fe-branch">
                                                <?php foreach ($node['children'] as $child): ?>
                                                    <?php renderFileExplorerNode($child); ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                    <?php
                                }

                                echo '<ul class="fe-branch root-branch">';
                                foreach ($nestedTree as $rootNode) {
                                    renderFileExplorerNode($rootNode, true);
                                }
                                echo '</ul>';
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Node Details Inspector Card -->
                    <div class="col-lg-5">
                        <div class="inspector-card p-4" id="feInspector">
                            <div class="text-center py-5 text-muted" id="feInspectorEmpty">
                                <i class="bi bi-hand-index-thumb fs-1 text-secondary d-block mb-2"></i>
                                <h6 class="fw-bold text-dark">Click any Node to Inspect</h6>
                                <p class="small text-muted mb-0">Select an advisor node from the file explorer tree to view immediate upline parent, children slots, and earnings history.</p>
                            </div>

                            <div id="feInspectorContent" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="pb-badge fs-6" id="insPbLabel">PB1</span>
                                    <span class="badge bg-primary-subtle text-primary fw-bold" id="insLevel">Level 1</span>
                                </div>
                                <h5 class="fw-bold text-dark mb-1" id="insName">Advisor Name</h5>
                                <div class="text-muted font-monospace small mb-3" id="insCodeMobile">SVPL-ADV-0001 &bull; 9876543210</div>

                                <div class="p-3 bg-light rounded-3 mb-3 border">
                                    <div class="row g-2 small">
                                        <div class="col-6">
                                            <span class="text-muted d-block">District / Block:</span>
                                            <strong class="text-dark" id="insDistrictBlock">—</strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block">Enrolled Date:</span>
                                            <strong class="text-dark" id="insQualifiedAt">—</strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block">Personal Customers:</span>
                                            <strong class="text-dark" id="insCustomers">0</strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block">Direct Active Advisors:</span>
                                            <strong class="text-dark" id="insDirectAdvisors">0</strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- Parent Placement -->
                                <div class="mb-3">
                                    <label class="small text-muted fw-bold text-uppercase mb-1">Direct Pool Parent (Upline)</label>
                                    <div class="p-2 border rounded-2 d-flex justify-content-between align-items-center bg-white" id="insParentBox">
                                        <div id="insParentText"><span class="text-muted">Root Node (No Upline)</span></div>
                                    </div>
                                </div>

                                <!-- Financials -->
                                <div class="card border-success border-opacity-25 bg-success-subtle mb-3">
                                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-success small fw-bold text-uppercase">Total Pool Commissions</span>
                                            <h4 class="fw-bold text-success mb-0" id="insEarnings">₹0.00</h4>
                                        </div>
                                        <i class="bi bi-wallet2 fs-2 text-success opacity-75"></i>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="btnInsViewFullModal">
                                        <i class="bi bi-fullscreen me-1"></i>View Detailed Placement Dossier
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================================================================= -->
            <!-- TAB 3: ROSTER DIRECTORY TABLE                                     -->
            <!-- ================================================================= -->
            <div class="tab-pane fade" id="tab-roster-view" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-list-ol me-2 text-primary"></i>All Enrolled Pool Members (Sequential PB Order)</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light text-muted text-uppercase">
                                <tr>
                                    <th>PB Number</th>
                                    <th>Level</th>
                                    <th>Advisor</th>
                                    <th>Direct Parent (Upline)</th>
                                    <th>Personal Cust</th>
                                    <th>Direct Adv</th>
                                    <th>Children Placed</th>
                                    <th class="text-end">Total Earnings</th>
                                    <th>Qualified Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($poolMembers as $pm): ?>
                                    <tr>
                                        <td>
                                            <span class="pb-badge"><?= htmlspecialchars($pm['pool_label']) ?></span>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">Level <?= (int)$pm['pool_level'] ?></span></td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($pm['advisor_name']) ?></div>
                                            <div class="text-muted font-monospace small"><?= htmlspecialchars($pm['advisor_code']) ?> &bull; <?= htmlspecialchars($pm['mobile']) ?></div>
                                        </td>
                                        <td>
                                            <?php if (!empty($pm['parent_pool_label'])): ?>
                                                <span class="badge bg-success-subtle text-success border font-monospace"><?= htmlspecialchars($pm['parent_pool_label']) ?></span>
                                                <span class="text-dark ms-1"><?= htmlspecialchars($pm['parent_advisor_name']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small">Root Node</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="fw-bold text-dark"><?= (int)$pm['personal_customers'] ?></span></td>
                                        <td><span class="fw-bold text-dark"><?= (int)$pm['direct_advisors_count'] ?></span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <?php for ($s = 1; $s <= 3; $s++): ?>
                                                    <span class="slot-dot <?= $s <= (int)$pm['direct_pool_children_count'] ? 'filled' : '' ?>"></span>
                                                <?php endfor; ?>
                                                <span class="ms-1 fw-bold"><?= (int)$pm['direct_pool_children_count'] ?>/3</span>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold text-success fs-6">₹<?= number_format((float)$pm['pool_earnings'], 2) ?></td>
                                        <td><?= date('d-M-Y', strtotime($pm['qualified_at'])) ?></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-info btn-sm view-pool-modal-btn" data-node="<?= htmlspecialchars(json_encode($pm), ENT_QUOTES, 'UTF-8') ?>">
                                                <i class="bi bi-eye me-1"></i>View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>
</div>

<!-- ========================================================================= -->
<!-- NODE DETAILS DRILL-DOWN MODAL                                             -->
<!-- ========================================================================= -->
<div class="modal fade" id="poolNodeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <div class="d-flex align-items-center gap-2">
                    <span class="pb-badge" id="modalPbBadge">PB1</span>
                    <h5 class="modal-title h6 mb-0 text-white" id="modalNodeTitle">Pool Member Placement Details</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 h-100 border">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-2">Advisor Information</span>
                            <h5 class="fw-bold text-dark mb-1" id="mAdvName">Advisor Name</h5>
                            <div class="text-muted font-monospace small mb-2" id="mAdvCode">SVPL-ADV-0001</div>
                            <div class="small text-secondary" id="mAdvContact">Mobile: 9876543210 &bull; District: Khordha</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 h-100 border">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-2">Pool Hierarchy Placement</span>
                            <div class="d-flex justify-content-between mb-1 small">
                                <span class="text-muted">Placement Level:</span>
                                <strong class="text-primary" id="mPoolLevel">Level 1</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1 small">
                                <span class="text-muted">Parent Pool Node:</span>
                                <strong class="text-dark" id="mParentNode">Root</strong>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">Direct Children:</span>
                                <strong class="text-success" id="mChildSlots">0 / 3</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border shadow-none bg-white p-3 text-center">
                            <span class="text-muted small">Personal Customers</span>
                            <h4 class="fw-bold text-dark my-1" id="mCustomers">0</h4>
                            <span class="badge bg-success-subtle text-success">Qualified (&ge; 3)</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border shadow-none bg-white p-3 text-center">
                            <span class="text-muted small">Direct Active Advisors</span>
                            <h4 class="fw-bold text-dark my-1" id="mAdvisors">0</h4>
                            <span class="badge bg-success-subtle text-success">Qualified (&ge; 3)</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border shadow-none bg-success-subtle p-3 text-center">
                            <span class="text-success small fw-bold text-uppercase">Total Pool Earnings</span>
                            <h4 class="fw-bold text-success my-1" id="mEarnings">₹0.00</h4>
                            <span class="text-muted small">Dispatched to Wallet</span>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 d-flex align-items-center mb-0 small">
                    <i class="bi bi-info-circle-fill fs-5 me-2 text-info"></i>
                    <div>
                        Advisors in the Pool Tree receive <strong>Level 1 (₹<?= number_format((float)($rule['level_1_amount'] ?? 1000), 0) ?>)</strong> and <strong>Level 2–11 (₹<?= number_format((float)($rule['subsequent_level_amount'] ?? 500), 0) ?>)</strong> payouts whenever new qualified advisors join below their 3-child node.
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- JAVASCRIPT: PAN/ZOOM, THEME SWITCHER, BRANCH TOGGLE & FILE EXPLORER       -->
<!-- ========================================================================= -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentZoom = 1;
    const content = document.getElementById('poolTreeContent');
    const viewport = document.getElementById('poolTreeViewport');

    // Theme Switcher
    const btnThemeBlueprint = document.getElementById('btnThemeBlueprint');
    const btnThemeAurora = document.getElementById('btnThemeAurora');
    const btnThemeLight = document.getElementById('btnThemeLight');

    function setTheme(themeClass, activeBtn) {
        if (!viewport) return;
        viewport.className = 'pool-tree-viewport ' + themeClass;
        [btnThemeBlueprint, btnThemeAurora, btnThemeLight].forEach(b => {
            if (b) {
                b.classList.remove('btn-dark', 'btn-light', 'active');
                b.classList.add('btn-outline-dark');
            }
        });
        if (activeBtn) {
            activeBtn.classList.remove('btn-outline-dark');
            activeBtn.classList.add('btn-dark', 'active');
        }
    }

    if (btnThemeBlueprint) btnThemeBlueprint.addEventListener('click', () => setTheme('theme-blueprint', btnThemeBlueprint));
    if (btnThemeAurora) btnThemeAurora.addEventListener('click', () => setTheme('theme-aurora', btnThemeAurora));
    if (btnThemeLight) btnThemeLight.addEventListener('click', () => setTheme('theme-light', btnThemeLight));

    // Zoom Controls
    function setZoom(val) {
        currentZoom = Math.min(Math.max(val, 0.4), 1.8);
        if (content) {
            content.style.transform = `scale(${currentZoom})`;
            const resetBtn = document.getElementById('btnZoomReset');
            if (resetBtn) resetBtn.textContent = `${Math.round(currentZoom * 100)}%`;
        }
    }

    const btnZoomIn = document.getElementById('btnZoomIn');
    if (btnZoomIn) btnZoomIn.addEventListener('click', () => setZoom(currentZoom + 0.15));

    const btnZoomOut = document.getElementById('btnZoomOut');
    if (btnZoomOut) btnZoomOut.addEventListener('click', () => setZoom(currentZoom - 0.15));

    const btnZoomReset = document.getElementById('btnZoomReset');
    if (btnZoomReset) btnZoomReset.addEventListener('click', () => setZoom(1));

    // Expand / Collapse Branch Buttons in Graphical View
    document.querySelectorAll('.btn-toggle-branch').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const parentLi = this.closest('li');
            const subUl = parentLi ? parentLi.querySelector(':scope > ul.branch-group') : null;
            if (subUl) {
                if (subUl.style.display === 'none') {
                    subUl.style.display = 'flex';
                    this.innerHTML = '<i class="bi bi-dash"></i>';
                } else {
                    subUl.style.display = 'none';
                    this.innerHTML = '<i class="bi bi-plus"></i>';
                }
            }
        });
    });

    const btnExpandAllGraph = document.getElementById('btnExpandAllGraph');
    if (btnExpandAllGraph) {
        btnExpandAllGraph.addEventListener('click', function() {
            document.querySelectorAll('ul.branch-group').forEach(el => el.style.display = 'flex');
            document.querySelectorAll('.btn-toggle-branch').forEach(btn => btn.innerHTML = '<i class="bi bi-dash"></i>');
        });
    }

    // Modal Inspection Trigger
    let lastSelectedNode = null;
    function openNodeModal(nodeData) {
        lastSelectedNode = nodeData;
        document.getElementById('modalPbBadge').textContent = nodeData.pool_label || 'PB';
        document.getElementById('modalNodeTitle').textContent = `${nodeData.pool_label} — ${nodeData.advisor_name}`;
        document.getElementById('mAdvName').textContent = nodeData.advisor_name || 'N/A';
        document.getElementById('mAdvCode').textContent = nodeData.advisor_code || 'N/A';
        document.getElementById('mAdvContact').innerHTML = `Mobile: <strong>${nodeData.mobile || 'N/A'}</strong> &bull; District: <strong>${nodeData.district || 'Odisha'}</strong>`;
        
        document.getElementById('mPoolLevel').textContent = `Level ${nodeData.pool_level || 1}`;
        document.getElementById('mParentNode').textContent = nodeData.parent_pool_label ? `${nodeData.parent_pool_label} (${nodeData.parent_advisor_name || ''})` : 'Root (No Upline)';
        document.getElementById('mChildSlots').textContent = `${nodeData.direct_pool_children_count || 0} / 3 slots occupied`;

        document.getElementById('mCustomers').textContent = nodeData.personal_customers || 0;
        document.getElementById('mAdvisors').textContent = nodeData.direct_advisors_count || 0;
        document.getElementById('mEarnings').textContent = '₹' + Number(nodeData.pool_earnings || 0).toLocaleString('en-IN', {minimumFractionDigits: 2});

        const modal = new bootstrap.Modal(document.getElementById('poolNodeModal'));
        modal.show();
    }

    // Graphical Node Click
    document.querySelectorAll('.pool-node-card').forEach(function(card) {
        card.addEventListener('click', function() {
            document.querySelectorAll('.pool-node-card').forEach(c => c.classList.remove('active-selected'));
            this.classList.add('active-selected');
            const raw = this.getAttribute('data-node');
            if (raw) {
                try {
                    openNodeModal(JSON.parse(raw));
                } catch(e) {}
            }
        });
    });

    // Roster view buttons
    document.querySelectorAll('.view-pool-modal-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const raw = this.getAttribute('data-node');
            if (raw) {
                try {
                    openNodeModal(JSON.parse(raw));
                } catch(e) {}
            }
        });
    });

    // =========================================================================
    // FILE EXPLORER INTERACTIONS
    // =========================================================================
    // Folder Toggle
    document.querySelectorAll('.fe-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const parentLi = this.closest('.fe-node');
            const childUl = parentLi ? parentLi.querySelector(':scope > .fe-branch') : null;
            if (childUl) {
                const icon = parentLi.querySelector(':scope > .fe-row .fe-icon');
                if (childUl.style.display === 'none') {
                    childUl.style.display = 'block';
                    this.classList.add('expanded');
                    if (icon) icon.className = 'bi bi-folder2-open fe-icon open';
                } else {
                    childUl.style.display = 'none';
                    this.classList.remove('expanded');
                    if (icon) icon.className = 'bi bi-folder fe-icon';
                }
            }
        });
    });

    // Inspector Update on File Explorer Row Click
    function selectExplorerNode(nodeData, rowElement) {
        lastSelectedNode = nodeData;
        document.querySelectorAll('.fe-row').forEach(r => r.classList.remove('active-selected'));
        if (rowElement) rowElement.classList.add('active-selected');

        document.getElementById('feInspectorEmpty').style.display = 'none';
        document.getElementById('feInspectorContent').style.display = 'block';

        document.getElementById('insPbLabel').textContent = nodeData.pool_label || 'PB';
        document.getElementById('insLevel').textContent = `Level ${nodeData.pool_level || 1}`;
        document.getElementById('insName').textContent = nodeData.advisor_name || 'N/A';
        document.getElementById('insCodeMobile').textContent = `${nodeData.advisor_code || 'N/A'} • ${nodeData.mobile || 'N/A'}`;
        document.getElementById('insDistrictBlock').textContent = `${nodeData.district || 'Odisha'} / ${nodeData.block || '—'}`;
        document.getElementById('insQualifiedAt').textContent = nodeData.qualified_at ? nodeData.qualified_at.split(' ')[0] : '—';
        document.getElementById('insCustomers').textContent = nodeData.personal_customers || 0;
        document.getElementById('insDirectAdvisors').textContent = nodeData.direct_advisors_count || 0;
        document.getElementById('insEarnings').textContent = '₹' + Number(nodeData.pool_earnings || 0).toLocaleString('en-IN', {minimumFractionDigits: 2});

        const parentText = nodeData.parent_pool_label 
            ? `<span class="pb-badge me-2">${nodeData.parent_pool_label}</span> <strong>${nodeData.parent_advisor_name || ''}</strong> (${nodeData.parent_advisor_code || ''})`
            : '<span class="text-muted"><i class="bi bi-star-fill text-warning me-1"></i>Root Level Network Member (No Upline)</span>';
        document.getElementById('insParentBox').innerHTML = parentText;
    }

    document.querySelectorAll('.fe-row').forEach(function(row) {
        row.addEventListener('click', function() {
            const raw = this.getAttribute('data-node');
            if (raw) {
                try {
                    selectExplorerNode(JSON.parse(raw), this);
                } catch(e) {}
            }
        });
    });

    const btnInsViewFullModal = document.getElementById('btnInsViewFullModal');
    if (btnInsViewFullModal) {
        btnInsViewFullModal.addEventListener('click', function() {
            if (lastSelectedNode) openNodeModal(lastSelectedNode);
        });
    }

    // Expand All / Collapse All in File Explorer
    const btnFeExpandAll = document.getElementById('btnFeExpandAll');
    if (btnFeExpandAll) {
        btnFeExpandAll.addEventListener('click', function() {
            document.querySelectorAll('.fe-branch').forEach(el => el.style.display = 'block');
            document.querySelectorAll('.fe-toggle').forEach(t => t.classList.add('expanded'));
            document.querySelectorAll('.fe-icon').forEach(ic => {
                if (ic.classList.contains('bi-folder')) ic.className = 'bi bi-folder2-open fe-icon open';
            });
        });
    }

    const btnFeCollapseAll = document.getElementById('btnFeCollapseAll');
    if (btnFeCollapseAll) {
        btnFeCollapseAll.addEventListener('click', function() {
            document.querySelectorAll('.fe-branch:not(.root-branch)').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.fe-toggle').forEach(t => t.classList.remove('expanded'));
            document.querySelectorAll('.fe-icon').forEach(ic => {
                if (ic.classList.contains('bi-folder2-open')) ic.className = 'bi bi-folder fe-icon';
            });
        });
    }

    // Live Search Filter for File Explorer
    const feSearchInput = document.getElementById('feSearchInput');
    if (feSearchInput) {
        feSearchInput.addEventListener('input', function() {
            const q = this.value.trim().toLowerCase();
            const nodes = document.querySelectorAll('.fe-node');

            if (!q) {
                nodes.forEach(n => n.style.display = '');
                return;
            }

            nodes.forEach(function(node) {
                const searchStr = node.getAttribute('data-search') || '';
                if (searchStr.includes(q)) {
                    node.style.display = '';
                    // expand parents
                    let parent = node.parentElement;
                    while (parent && parent.classList.contains('fe-branch')) {
                        parent.style.display = 'block';
                        const parentToggle = parent.previousElementSibling ? parent.previousElementSibling.querySelector('.fe-toggle') : null;
                        if (parentToggle) parentToggle.classList.add('expanded');
                        parent = parent.parentElement.closest('.fe-branch');
                    }
                } else {
                    node.style.display = 'none';
                }
            });
        });
    }

    // Auto-select root node for inspector if available
    const firstRow = document.querySelector('.fe-row');
    if (firstRow) {
        const raw = firstRow.getAttribute('data-node');
        if (raw) {
            try {
                selectExplorerNode(JSON.parse(raw), firstRow);
            } catch(e) {}
        }
    }
});
</script>
