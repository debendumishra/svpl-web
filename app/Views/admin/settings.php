<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin System Settings View
 */
$title = "System Settings — SVPL Admin";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">Platform Settings & Business Rules</h3>
        <p class="text-muted small mb-0">Configure company info, qualification parameters, and commission slabs</p>
    </div>
</div>

<?php if (isset($_GET['saved'])): ?>
    <div class="alert alert-success py-2 px-3 small mb-4 d-flex align-items-center">
        <i class="bi bi-check-circle-fill me-2"></i> Settings updated successfully!
    </div>
<?php endif; ?>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <form action="<?= url('/admin/settings') ?>" method="POST">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Company Full Name:</label>
                <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($settings['company_name'] ?? 'Surya Vistaara Pvt. Ltd.') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Promoter Entity:</label>
                <input type="text" name="promoter_entity" class="form-control" value="<?= htmlspecialchars($settings['promoter_entity'] ?? 'Dhwajja Solar India Pvt. Ltd.') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Advisor Onboarding Fee (₹):</label>
                <input type="number" step="1" name="advisor_joining_fee" class="form-control font-monospace fw-bold text-navy" value="<?= htmlspecialchars($settings['advisor_joining_fee'] ?? '2700.00') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Qualification Target (Customers):</label>
                <input type="number" name="advisor_required_customers" class="form-control" value="<?= htmlspecialchars($settings['advisor_required_customers'] ?? '3') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Direct Customer Bonus (₹):</label>
                <input type="number" name="direct_customer_bonus" class="form-control" value="<?= htmlspecialchars($settings['direct_customer_bonus'] ?? '500.00') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Statutory TDS Deduction (%):</label>
                <input type="number" step="0.1" name="tds_percentage" class="form-control" value="<?= htmlspecialchars($settings['tds_percentage'] ?? '5.00') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Support Helpline Phone:</label>
                <input type="text" name="support_phone" class="form-control" value="<?= htmlspecialchars($settings['support_phone'] ?? '+91 674 295 4800') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Support Email:</label>
                <input type="email" name="support_email" class="form-control" value="<?= htmlspecialchars($settings['support_email'] ?? 'support@suryavistaara.com') ?>">
            </div>
            <div class="col-md-12 mt-4">
                <button type="submit" class="btn btn-svpl-navy px-4">
                    <i class="bi bi-save me-1"></i> Save System Configuration
                </button>
            </div>
        </div>
    </form>
</div>
