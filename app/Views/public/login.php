<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Unified Login Page
 */
$title = "Login — " . company_name();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-svpl p-4 shadow-sm border-0" style="border-radius: 16px;">
                <div class="text-center mb-4">
                    <?php if (company_logo_url()): ?>
                        <div class="mb-3">
                            <img src="<?= htmlspecialchars(company_logo_url()) ?>" alt="Company Logo" style="max-height: 55px; max-width: 180px; object-fit: contain;">
                        </div>
                    <?php else: ?>
                        <div style="background: linear-gradient(135deg, #0B2545 0%, #133E6E 100%); width: 50px; height: 50px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; color: #F59E0B; font-size: 1.5rem;" class="mb-2">
                            ☀
                        </div>
                    <?php endif; ?>
                    <h3 class="fw-bold" style="color: #0B2545;">Account Login</h3>
                    <p class="text-muted small">Sign in to access your <?= htmlspecialchars(company_short_name()) ?> Dashboard</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2 px-3 small d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <!-- Quick Role Fill Selector -->
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted d-block text-center mb-2">Quick Role Login Fill:</label>
                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                        <button type="button" class="btn btn-xs btn-outline-info text-dark font-sans py-1 px-2" style="font-size: 0.78rem;" onclick="fillLogin('boe1@suryavistaara.com', 'Password@123')">
                            <i class="bi bi-headset text-info me-1"></i> BOE Staff
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-primary text-dark font-sans py-1 px-2" style="font-size: 0.78rem;" onclick="fillLogin('admin@suryavistaara.com', 'Password@123')">
                            <i class="bi bi-shield-lock-fill text-primary me-1"></i> Admin
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-warning text-dark font-sans py-1 px-2" style="font-size: 0.78rem;" onclick="fillLogin('9437012345', 'Password@123')">
                            <i class="bi bi-person-badge-fill text-warning me-1"></i> Advisor
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-success text-dark font-sans py-1 px-2" style="font-size: 0.78rem;" onclick="fillLogin('9861011223', 'Password@123')">
                            <i class="bi bi-person-circle text-success me-1"></i> Customer
                        </button>
                    </div>
                </div>

                <form action="<?= url('/login') ?>" method="POST" id="loginForm">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Mobile Number or Email:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                            <input type="text" name="identifier" id="loginIdentifier" class="form-control" placeholder="e.g. boe1@suryavistaara.com or 9861000111" value="<?= htmlspecialchars($oldIdentifier ?? '') ?>" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Password:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="loginPassword" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <!-- Security Verification CAPTCHA -->
                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <label class="form-label small fw-bold text-dark d-flex justify-content-between align-items-center mb-2">
                            <span><i class="bi bi-shield-check text-primary me-1"></i> Security Code (CAPTCHA) *</span>
                            <span class="text-muted fw-normal" style="font-size: 0.75rem;">Case-insensitive</span>
                        </label>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img src="<?= url('/captcha') ?>?t=<?= time() ?>" id="loginCaptchaImg" alt="Security Code" style="height: 42px; border-radius: 6px; border: 1px solid #CBD5E1; cursor: pointer;" title="Click image to refresh" onclick="this.src='<?= url('/captcha') ?>?t='+new Date().getTime()">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('loginCaptchaImg').src='<?= url('/captcha') ?>?t='+new Date().getTime()" title="Get new code">
                                <i class="bi bi-arrow-clockwise"></i> Refresh
                            </button>
                        </div>
                        <input type="text" name="captcha" class="form-control text-uppercase font-monospace fw-bold" placeholder="Enter 5-character code" maxlength="6" required autocomplete="off">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label small text-muted" for="rememberMe">Remember me</label>
                        </div>
                        <a href="<?= url('/contact') ?>" class="small text-decoration-none">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-svpl-navy w-100 py-2 fw-bold mb-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Portal
                    </button>
                </form>

                <div class="border-top pt-3 text-center">
                    <p class="small text-muted mb-2">Don't have an account?</p>
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <a href="<?= url('/register-advisor') ?>" class="btn btn-outline-warning btn-sm text-dark fw-semibold">
                            <i class="bi bi-person-plus-fill me-1"></i> Become an Advisor
                        </a>
                        <a href="<?= url('/contact') ?>" class="btn btn-outline-secondary btn-sm fw-semibold">
                            <i class="bi bi-headset me-1"></i> Customer Inquiry
                        </a>
                    </div>
                </div>

                <div class="alert alert-info py-2 px-3 mt-4 mb-0 small" style="background: #EEF2F6; border: 1px solid #D0DCE8;">
                    <strong><i class="bi bi-info-circle me-1"></i> Demo Credentials:</strong><br>
                    • <strong>BOE Staff:</strong> <code>boe1@suryavistaara.com</code> / <code>Password@123</code><br>
                    • <strong>Admin:</strong> <code>admin@suryavistaara.com</code> / <code>Password@123</code><br>
                    • <strong>Advisor:</strong> <code>9437012345</code> / <code>Password@123</code><br>
                    • <strong>Customer:</strong> <code>9861011223</code> / <code>Password@123</code>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillLogin(identifier, password) {
    document.getElementById('loginIdentifier').value = identifier;
    document.getElementById('loginPassword').value = password;
}
</script>
