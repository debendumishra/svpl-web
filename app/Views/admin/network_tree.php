<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Multi-Level Genealogy Org-Tree Visualizer
 */
$title = "Multi-Level Network Tree — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Multi-Level Advisor Genealogy Visualizer</h3>
        <p class="text-muted small mb-0">Interactive 9-level hierarchical closure-tree representation</p>
    </div>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h5 class="fw-bold mb-0" style="color: #0B2545;">
            Active Hierarchy Root: <strong><?= htmlspecialchars($rootAdvisor['first_name'] . ' ' . $rootAdvisor['last_name']) ?></strong> (<code><?= htmlspecialchars($rootAdvisor['advisor_code']) ?></code>)
        </h5>
        <div>
            <span class="badge bg-success me-2"><i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> Qualified Advisor</span>
            <span class="badge bg-warning text-dark"><i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> New / Active</span>
        </div>
    </div>

    <!-- Tree Org Container -->
    <div class="tree-container">
        <div class="tree">
            <ul>
                <li>
                    <div class="tree-node-card <?= strtolower($rootAdvisor['status']) ?>">
                        <div class="node-code"><?= htmlspecialchars($rootAdvisor['advisor_code']) ?></div>
                        <div class="node-name"><?= htmlspecialchars($rootAdvisor['first_name'] . ' ' . $rootAdvisor['last_name']) ?></div>
                        <span class="node-badge <?= $rootAdvisor['status'] === 'QUALIFIED' ? 'bg-success text-white' : 'bg-warning text-dark' ?>">
                            <?= htmlspecialchars($rootAdvisor['status']) ?> (<?= $rootAdvisor['direct_customer_count'] ?>/3)
                        </span>
                    </div>

                    <?php
                    $directs = \App\Models\Genealogy::getDirectDownlines((int)$rootAdvisor['id']);
                    if (!empty($directs)): ?>
                        <ul>
                            <?php foreach ($directs as $d): ?>
                                <li>
                                    <div class="tree-node-card <?= strtolower($d['status']) ?>">
                                        <div class="node-code"><?= htmlspecialchars($d['advisor_code']) ?></div>
                                        <div class="node-name"><?= htmlspecialchars($d['first_name'] . ' ' . $d['last_name']) ?></div>
                                        <span class="node-badge <?= $d['status'] === 'QUALIFIED' ? 'bg-success text-white' : 'bg-warning text-dark' ?>">
                                            <?= htmlspecialchars($d['status']) ?> (<?= $d['direct_customer_count'] ?>/3)
                                        </span>
                                        <div class="mt-1">
                                            <a href="<?= url('/admin/network-tree?root_id=' . $d['id']) ?>" class="badge bg-light text-dark border text-decoration-none" title="Expand this branch">
                                                Branch <i class="bi bi-arrows-angle-expand"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <?php
                                    $subDirects = \App\Models\Genealogy::getDirectDownlines((int)$d['id']);
                                    if (!empty($subDirects)): ?>
                                        <ul>
                                            <?php foreach ($subDirects as $sd): ?>
                                                <li>
                                                    <div class="tree-node-card <?= strtolower($sd['status']) ?>">
                                                        <div class="node-code"><?= htmlspecialchars($sd['advisor_code']) ?></div>
                                                        <div class="node-name"><?= htmlspecialchars($sd['first_name'] . ' ' . $sd['last_name']) ?></div>
                                                        <span class="node-badge <?= $sd['status'] === 'QUALIFIED' ? 'bg-success text-white' : 'bg-warning text-dark' ?>">
                                                            <?= htmlspecialchars($sd['status']) ?> (<?= $sd['direct_customer_count'] ?>/3)
                                                        </span>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</div>
