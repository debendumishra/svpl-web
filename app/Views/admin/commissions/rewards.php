<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Advisor Lifetime Rewards Desk
 */
$title = $pageTitle ?? 'Advisor Lifetime Rewards Desk';
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-trophy text-warning me-2"></i>Advisor Lifetime Performance Rewards Desk
            </h1>
            <p class="text-muted small mb-0">Milestone Achievement Approvals, Cash Payouts & Product Deliveries</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url('/admin/commissions') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Commission Desk
            </a>
            <a href="<?= url('/admin/commissions/settings?tab=rewards') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-sliders me-1"></i>Configure Reward Slabs
            </a>
        </div>
    </div>

    <!-- Active Slabs Overview -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-award me-2 text-warning"></i>Active Lifetime Reward Slabs</h6>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                <?php foreach ($rewardSlabs as $rs): ?>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="card border shadow-none bg-light h-100 p-3 text-center">
                            <div class="badge bg-warning text-dark mb-2 align-self-center"><?= (int)$rs['customer_target'] ?> Customers</div>
                            <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($rs['reward_name']) ?></h6>
                            <div class="h5 fw-bold text-success mb-0">₹<?= number_format((float)$rs['total_reward_value'], 0) ?></div>
                            <div class="text-muted small mt-1"><?= htmlspecialchars($rs['reward_type']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Claims Table -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Advisor Reward Claims & Deliveries (<?= count($claims) ?> Total)</h6>
            <div class="btn-group btn-group-sm">
                <a href="<?= url('/admin/commissions/rewards') ?>" class="btn <?= empty($currentStatus) ? 'btn-primary' : 'btn-outline-secondary' ?>">All</a>
                <a href="<?= url('/admin/commissions/rewards?status=ACHIEVED') ?>" class="btn <?= $currentStatus === 'ACHIEVED' ? 'btn-primary' : 'btn-outline-secondary' ?>">Achieved</a>
                <a href="<?= url('/admin/commissions/rewards?status=PENDING_APPROVAL') ?>" class="btn <?= $currentStatus === 'PENDING_APPROVAL' ? 'btn-primary' : 'btn-outline-secondary' ?>">Pending Approval</a>
                <a href="<?= url('/admin/commissions/rewards?status=APPROVED') ?>" class="btn <?= $currentStatus === 'APPROVED' ? 'btn-primary' : 'btn-outline-secondary' ?>">Approved</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th>Claim ID</th>
                        <th>Advisor</th>
                        <th>Reward Milestone</th>
                        <th>Target</th>
                        <th>Snapshot Custs</th>
                        <th class="text-end">Reward Value</th>
                        <th>Claim Choice</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php if (empty($claims)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-trophy fs-1 d-block mb-2 text-secondary"></i>
                                No reward claims found matching status filter.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($claims as $c): ?>
                            <tr>
                                <td class="font-monospace fw-bold text-dark">#REW-<?= $c['id'] ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($c['advisor_name']) ?></div>
                                    <div class="text-muted font-monospace small"><?= htmlspecialchars($c['advisor_code']) ?> &bull; <?= htmlspecialchars($c['advisor_mobile']) ?></div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($c['reward_name']) ?></span>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= (int)$c['customer_target'] ?> Customers</span></td>
                                <td class="font-monospace"><?= (int)$c['customer_count_snapshot'] ?></td>
                                <td class="text-end fw-bold text-success fs-6">₹<?= number_format((float)$c['total_reward_value'], 2) ?></td>
                                <td>
                                    <span class="badge bg-info-subtle text-info fw-semibold"><?= htmlspecialchars($c['claim_type']) ?></span>
                                </td>
                                <td>
                                    <?php 
                                        $badgeClass = match($c['status']) {
                                            'APPROVED' => 'bg-success',
                                            'DELIVERED' => 'bg-primary',
                                            'PENDING_APPROVAL' => 'bg-warning text-dark',
                                            'ACHIEVED' => 'bg-info text-dark',
                                            default => 'bg-secondary'
                                        };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($c['status']) ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($c['status'] === 'ACHIEVED' || $c['status'] === 'PENDING_APPROVAL'): ?>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-success btn-sm btn-approve-reward" data-id="<?= $c['id'] ?>" data-action="CASH_PAYOUT" title="Credit Cash to Advisor Wallet">
                                                <i class="bi bi-wallet2 me-1"></i>Cash Payout
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm btn-approve-reward" data-id="<?= $c['id'] ?>" data-action="PRODUCT_DELIVERED" title="Mark Product / Vehicle Delivered">
                                                <i class="bi bi-box-seam me-1"></i>Delivered
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small"><i class="bi bi-check-all text-success"></i> Settled</span>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = '<?= rtrim(url(''), '/') ?>';

    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.btn-approve-reward');
        if (!btn) return;

        e.preventDefault();
        const id = btn.getAttribute('data-id');
        const action = btn.getAttribute('data-action');
        let deliveryRef = '';

        if (action === 'PRODUCT_DELIVERED') {
            deliveryRef = prompt('Enter delivery challan / courier / vehicle registration reference:');
            if (!deliveryRef) return;
        } else {
            if (!confirm('Credit net cash reward amount directly to the Advisor wallet ledger?')) return;
        }

        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

        try {
            const formData = new URLSearchParams();
            formData.append('claim_id', id);
            formData.append('action_type', action);
            if (deliveryRef) formData.append('delivery_ref', deliveryRef);

            const res = await fetch(baseUrl + '/admin/commissions/rewards/approve', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData.toString()
            }).then(r => r.json());

            if (res.status) {
                alert(res.message || 'Reward processed successfully!');
                window.location.reload();
            } else {
                alert('Approval Failed: ' + (res.message || 'Unknown error'));
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        } catch(err) {
            alert('Request failed: ' + err.message);
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    });
});
</script>
</div>
