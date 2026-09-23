<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Lifetime Performance Rewards Portal
 */
$title = $pageTitle ?? 'My Lifetime Performance Rewards';
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-trophy text-warning me-2"></i>My Lifetime Performance Rewards
            </h1>
            <p class="text-muted small mb-0">Track Customer Milestone Achievements, Cash Payouts & Vehicle/Product Rewards</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url('/advisor/dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Personal Customers Summary Banner -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-primary text-white">
        <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <span class="badge bg-warning text-dark mb-1">Lifetime Progress</span>
                <h3 class="fw-bold mb-0">Total Verified Personal Customers: <?= (int)$rewardProgress['personal_customers'] ?></h3>
                <p class="text-white-50 small mb-0 mt-1">Directly generated & converted solar installations linked to your advisor code.</p>
            </div>
            <?php if (!empty($rewardProgress['next_target'])): ?>
                <?php $nt = $rewardProgress['next_target']; ?>
                <div class="text-end mt-3 mt-md-0">
                    <div class="small text-white-50 text-uppercase">Next Milestone Target</div>
                    <div class="h4 fw-bold text-warning mb-0"><?= htmlspecialchars($nt['reward_name']) ?> (<?= $nt['target'] ?> Custs)</div>
                    <div class="small text-white-50"><?= $nt['remaining'] ?> more customers to achieve ₹<?= number_format($nt['reward_value'], 0) ?> reward</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Reward Milestone Slabs Grid -->
    <div class="row g-4 mb-4">
        <?php foreach ($rewardProgress['rewards'] as $rew): ?>
            <?php 
                $isAchieved = ($rew['status'] === 'ACHIEVED');
                $isPending = ($rew['status'] === 'PENDING_APPROVAL');
                $isApproved = ($rew['status'] === 'APPROVED' || $rew['status'] === 'DELIVERED');
                $isLocked = ($rew['status'] === 'LOCKED');

                $borderClass = $isApproved ? 'border-success' : ($isAchieved || $isPending ? 'border-warning' : 'border-secondary border-opacity-25');
                $bgClass = $isApproved ? 'bg-success-subtle' : ($isAchieved ? 'bg-warning-subtle' : 'bg-white');
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-3 h-100 border-top border-4 <?= $borderClass ?> <?= $bgClass ?>">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-light text-dark border font-monospace"><?= (int)$rew['customer_target'] ?> Customers</span>
                            <?php if ($isApproved): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approved / Settled</span>
                            <?php elseif ($isPending): ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Claim Pending Admin Review</span>
                            <?php elseif ($isAchieved): ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-award me-1"></i>Milestone Achieved!</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><i class="bi bi-lock me-1"></i>Locked</span>
                            <?php endif; ?>
                        </div>

                        <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($rew['reward_name']) ?></h5>
                        <div class="h3 fw-bold text-success mb-2">₹<?= number_format((float)$rew['total_reward_value'], 2) ?></div>
                        <p class="text-muted small mb-3">
                            Reward Type: <strong><?= htmlspecialchars($rew['reward_type']) ?></strong>
                        </p>

                        <?php if ($isAchieved): ?>
                            <form method="POST" action="<?= url('/advisor/rewards/claim') ?>" class="mt-3 p-3 bg-white rounded border">
                                <input type="hidden" name="claim_id" value="<?= $rew['claim']['id'] ?? 0 ?>">
                                <label class="form-label small fw-bold text-dark">Choose Reward Claim Mode:</label>
                                <div class="form-check small mb-2">
                                    <input class="form-check-input" type="radio" name="claim_type" id="claimCash_<?= $rew['reward_id'] ?>" value="CASH" checked>
                                    <label class="form-check-label fw-semibold" for="claimCash_<?= $rew['reward_id'] ?>">
                                        Credit Direct Cash (₹<?= number_format($rew['total_reward_value'], 0) ?>) to Wallet
                                    </label>
                                </div>
                                <div class="form-check small mb-3">
                                    <input class="form-check-input" type="radio" name="claim_type" id="claimProd_<?= $rew['reward_id'] ?>" value="PRODUCT">
                                    <label class="form-check-label fw-semibold" for="claimProd_<?= $rew['reward_id'] ?>">
                                        Gift / Product / Vehicle Delivery
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold shadow-sm">
                                    <i class="bi bi-send-fill me-1"></i>Submit Reward Claim
                                </button>
                            </form>
                        <?php elseif ($isPending): ?>
                            <div class="alert alert-warning border-0 small mb-0 p-2">
                                <i class="bi bi-hourglass-split me-1"></i>Claim choice submitted (<?= htmlspecialchars($rew['claim']['claim_type'] ?? 'CASH') ?>). Awaiting Admin verification.
                            </div>
                        <?php elseif ($isApproved): ?>
                            <div class="alert alert-success border-0 small mb-0 p-2">
                                <i class="bi bi-check-circle me-1"></i>Reward verified and credited/delivered!
                            </div>
                        <?php else: ?>
                            <div class="text-muted small">
                                Progress: <?= (int)$rewardProgress['personal_customers'] ?> / <?= (int)$rew['customer_target'] ?> Customers
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: <?= min(100, ((int)$rewardProgress['personal_customers'] / (int)$rew['customer_target']) * 100) ?>%"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

