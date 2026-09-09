<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Contact Us Page
 */
$title = "Contact Us — Surya Vistaara Pvt. Ltd.";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2">GET IN TOUCH</span>
                <h1 class="fw-bold" style="color: #0B2545;">Connect with SVPL Central Office</h1>
                <p class="text-muted">We are here to assist customers, advisors, and corporate partners across Odisha.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-5">
                    <div class="card card-svpl p-4 h-100 bg-white border-0 shadow-sm">
                        <h4 class="fw-bold mb-4" style="color: #0B2545;">Dhwajja Solar & SVPL Head Office</h4>
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <i class="bi bi-geo-alt-fill text-danger fs-4"></i>
                            <div>
                                <strong class="d-block text-dark">Office Address:</strong>
                                <span class="text-muted small">MIG-84, Pokhariput, BDA Colony, Phase-1 Pokhariput, Bhubaneswar, Odisha - 751020</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <i class="bi bi-telephone-fill text-success fs-4"></i>
                            <div>
                                <strong class="d-block text-dark">Helpline Mobile:</strong>
                                <span class="text-muted small"><a href="tel:9040999899" class="text-decoration-none fw-bold text-success">9040999899</a></span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <i class="bi bi-envelope-fill text-primary fs-4"></i>
                            <div>
                                <strong class="d-block text-dark">Support Email:</strong>
                                <span class="text-muted small">dhwajjasolarsupport@gmail.com / support@suryavistaara.com</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-patch-check-fill text-warning fs-4"></i>
                            <div>
                                <strong class="d-block text-dark">GSTIN:</strong>
                                <span class="text-muted small"><code>21AAMCD5948B1ZU</code></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="card card-svpl p-4 h-100 bg-white border-0 shadow-sm">
                        <h4 class="fw-bold mb-3" style="color: #0B2545;">Send Us a Message</h4>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success py-2 px-3 small d-flex align-items-center mb-3">
                                <i class="bi bi-check-circle-fill me-2 fs-5"></i> <?= htmlspecialchars($success) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger py-2 px-3 small d-flex align-items-center mb-3">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= url('/contact') ?>" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Your Full Name *</label>
                                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($post['name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Mobile Number *</label>
                                    <input type="tel" name="mobile" class="form-control" placeholder="10-digit mobile" value="<?= htmlspecialchars($post['mobile'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label small fw-semibold">Your District in Odisha *</label>
                                    <select name="district" class="form-select" required>
                                        <option value="Khordha">Khordha</option>
                                        <option value="Cuttack">Cuttack</option>
                                        <option value="Puri">Puri</option>
                                        <option value="Ganjam">Ganjam</option>
                                        <option value="Sambalpur">Sambalpur</option>
                                        <option value="Balasore">Balasore</option>
                                        <option value="Bhadrak">Bhadrak</option>
                                        <option value="Mayurbhanj">Mayurbhanj</option>
                                        <option value="Sundargarh">Sundargarh</option>
                                        <option value="Other">Other District</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label small fw-semibold">Message / Query *</label>
                                    <textarea name="message" class="form-control" rows="3" placeholder="How can we help you regarding PM Surya Ghar Solar?" required><?= htmlspecialchars($post['message'] ?? '') ?></textarea>
                                </div>

                                <!-- Security Verification CAPTCHA -->
                                <div class="col-md-12">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <label class="form-label small fw-bold text-dark d-flex justify-content-between align-items-center mb-1">
                                            <span><i class="bi bi-shield-check text-primary me-1"></i> Security Verification (CAPTCHA) *</span>
                                            <span class="text-muted fw-normal" style="font-size: 0.75rem;">Case-insensitive</span>
                                        </label>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <img src="<?= url('/captcha') ?>?t=<?= time() ?>" id="contactCaptchaImg" alt="Security Code" style="height: 40px; border-radius: 6px; border: 1px solid #CBD5E1; cursor: pointer;" title="Click to refresh" onclick="this.src='<?= url('/captcha') ?>?t='+new Date().getTime()">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('contactCaptchaImg').src='<?= url('/captcha') ?>?t='+new Date().getTime()" title="Get new code">
                                                <i class="bi bi-arrow-clockwise"></i> Refresh
                                            </button>
                                        </div>
                                        <input type="text" name="captcha" class="form-control text-uppercase font-monospace fw-bold" placeholder="Enter 5-character security code" maxlength="6" required autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-svpl-navy w-100 py-2 fw-bold">
                                        Submit Query <i class="bi bi-send ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
