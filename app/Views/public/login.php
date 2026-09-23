<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Unified Mobile-First Sign-In Portal (Solar Luminary Design System v3.2)
 * Empowered by Stitch UI/UX Engine
 */
$title = "Portal Sign In — " . company_name() . " | PM Surya Ghar Odisha";
?>

<style>
/* ==========================================================
   MOBILE-FIRST SOLAR LUMINARY SIGN-IN PORTAL
   ========================================================== */
.login-page-wrapper {
    min-height: 88vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.25rem 0.75rem 2.5rem 0.75rem;
    background: radial-gradient(ellipse at 50% 0%, rgba(245, 158, 11, 0.1) 0%, rgba(11, 37, 69, 0.04) 50%, #F8FAFC 100%);
}

.login-card-container {
    width: 100%;
    max-width: 480px;
    margin: 0 auto;
}

.login-card-modern {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid rgba(226, 232, 240, 0.9);
    box-shadow: 0 10px 30px -5px rgba(11, 37, 69, 0.08), 0 4px 12px -2px rgba(11, 37, 69, 0.03);
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Role Selector Pill Bar */
.role-pills-bar {
    display: flex;
    gap: 4px;
    background: #F1F5F9;
    padding: 4px;
    border-radius: 14px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.role-pills-bar::-webkit-scrollbar {
    display: none;
}

.role-pill-item {
    flex: 1;
    min-width: 76px;
    padding: 8px 4px;
    border: none;
    background: transparent;
    border-radius: 10px;
    font-size: 0.74rem;
    font-weight: 700;
    color: #64748B;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2px;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
    cursor: pointer;
    user-select: none;
}
.role-pill-item i {
    font-size: 1.15rem;
    line-height: 1;
    transition: transform 0.2s ease;
}
.role-pill-item:hover {
    color: #0B2545;
    background: rgba(255, 255, 255, 0.6);
}
.role-pill-item.active {
    background: #FFFFFF;
    box-shadow: 0 2px 8px rgba(11, 37, 69, 0.12);
}
.role-pill-item.active i {
    transform: scale(1.1);
}

/* Active Pill Colors */
.role-pill-item.active[data-role="Customer"] { color: #059669; }
.role-pill-item.active[data-role="Customer"] i { color: #10B981; }

.role-pill-item.active[data-role="Advisor"] { color: #B45309; }
.role-pill-item.active[data-role="Advisor"] i { color: #F59E0B; }

.role-pill-item.active[data-role="BOE"] { color: #0284C7; }
.role-pill-item.active[data-role="BOE"] i { color: #0EA5E9; }

.role-pill-item.active[data-role="Engineer"] { color: #D97706; }
.role-pill-item.active[data-role="Engineer"] i { color: #EA580C; }

.role-pill-item.active[data-role="Admin"] { color: #1E3A8A; }
.role-pill-item.active[data-role="Admin"] i { color: #3B82F6; }

/* Dynamic Role Card Header */
.role-header-banner {
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
}

/* Quick Fill Demo Chips */
.demo-chip-btn {
    border: 1px solid #CBD5E1;
    background: #F8FAFC;
    color: #334155;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 8px;
    transition: all 0.15s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.demo-chip-btn:hover {
    background: #E2E8F0;
    color: #0F172A;
    border-color: #94A3B8;
}

/* Input Fields */
.login-input-wrap {
    border-radius: 12px;
    overflow: hidden;
    border: 1.5px solid #CBD5E1;
    background: #FFFFFF;
    transition: all 0.2s ease;
}
.login-input-wrap:focus-within {
    border-color: #F59E0B;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
}
.login-input-wrap .input-group-text {
    width: 44px;
    background: #F8FAFC;
    border: 0;
    border-right: 1.5px solid #E2E8F0;
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
}
.login-input-wrap .form-control {
    height: 48px;
    font-size: 0.95rem;
    font-weight: 500;
    border: 0;
    color: #0F172A;
    padding-left: 12px;
}
.login-input-wrap .form-control:focus {
    box-shadow: none;
    background: transparent;
}

/* Dynamic Submit Button */
.btn-submit-action {
    height: 50px;
    border-radius: 12px;
    border: 0;
    font-weight: 800;
    font-size: 0.98rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
}
.btn-submit-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.18);
}
.btn-submit-action:active {
    transform: translateY(0);
}

/* Role-specific Submit Button Themes */
.theme-btn-customer { background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: #FFFFFF; box-shadow: 0 4px 14px rgba(16,185,129,0.35); }
.theme-btn-advisor  { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: #061528; box-shadow: 0 4px 14px rgba(245,158,11,0.35); }
.theme-btn-boe      { background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%); color: #FFFFFF; box-shadow: 0 4px 14px rgba(2,132,199,0.35); }
.theme-btn-engineer { background: linear-gradient(135deg, #EA580C 0%, #C2410C 100%); color: #FFFFFF; box-shadow: 0 4px 14px rgba(234,88,12,0.35); }
.theme-btn-admin    { background: linear-gradient(135deg, #0F2D59 0%, #061528 100%); color: #FFFFFF; box-shadow: 0 4px 14px rgba(15,45,89,0.35); }

/* Captcha Mobile Box */
.captcha-container-box {
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 10px 12px;
}
.captcha-preview-img {
    height: 44px;
    width: 125px;
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
}
.captcha-preview-img img {
    height: 100%;
    width: 100%;
    object-fit: contain;
}

@media (max-width: 575.98px) {
    .login-page-wrapper {
        padding: 0.75rem 0.4rem 2rem 0.4rem;
    }
    .login-card-modern {
        border-radius: 16px;
        padding: 1.25rem 1rem !important;
    }
    .btn-submit-action {
        height: 48px;
    }
}
</style>

<div class="login-page-wrapper">
    <div class="login-card-container">
        
        <!-- TOP TRUST CREDENTIALS BAR -->
        <div class="d-flex justify-content-between align-items-center mb-2 px-1 text-muted" style="font-size: 0.74rem;">
            <a href="<?= url('/') ?>" class="text-decoration-none text-muted fw-semibold d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Public Portal
            </a>
            <div class="d-flex align-items-center gap-1 text-success fw-bold">
                <span class="spinner-grow spinner-grow-sm text-success" style="width: 8px; height: 8px;" role="status"></span>
                <span>DBT Net-Meter Live</span>
            </div>
            <a href="tel:<?= preg_replace('/[^0-9]/', '', company_phone()) ?>" class="text-decoration-none text-primary fw-semibold d-inline-flex align-items-center gap-1">
                <i class="bi bi-telephone-fill text-warning"></i> Help
            </a>
        </div>

        <div class="card login-card-modern p-3 p-sm-4">
            
            <!-- BRAND HEADER -->
            <div class="text-center mb-3">
                <a href="<?= url('/') ?>" class="d-inline-block text-decoration-none mb-1">
                    <?php if ($logo = company_logo_url()): ?>
                        <img src="<?= htmlspecialchars($logo) ?>" alt="<?= htmlspecialchars(company_name()) ?>" style="max-height: 42px; max-width: 160px; object-fit: contain;">
                    <?php else: ?>
                        <div style="background: linear-gradient(135deg, #0B2545 0%, #133E6E 100%); width: 44px; height: 44px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; color: #F59E0B; font-size: 1.4rem; box-shadow: 0 4px 10px rgba(245,158,11,0.25);">
                            ☀
                        </div>
                    <?php endif; ?>
                </a>
                <h4 class="fw-bold text-navy font-heading mb-0" id="loginHeaderTitle">
                    Customer Sign In
                </h4>
                
                <!-- DISCOM Badges Strip -->
                <div class="d-flex justify-content-center align-items-center gap-1 flex-wrap mt-1">
                    <span class="badge bg-light text-secondary border" style="font-size: 0.65rem;">TPCODL</span>
                    <span class="badge bg-light text-secondary border" style="font-size: 0.65rem;">TPNODL</span>
                    <span class="badge bg-light text-secondary border" style="font-size: 0.65rem;">TPSODL</span>
                    <span class="badge bg-light text-secondary border" style="font-size: 0.65rem;">TPWODL</span>
                </div>
            </div>

            <!-- FLASH ALERTS -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 px-3 small d-flex align-items-center rounded-3 mb-3 border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-6 flex-shrink-0"></i> 
                    <div><?= htmlspecialchars($error) ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success_msg'])): ?>
                <div class="alert alert-success py-2 px-3 small d-flex align-items-center rounded-3 mb-3 border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-6 flex-shrink-0"></i> 
                    <div><?= htmlspecialchars($_SESSION['success_msg']) ?></div>
                </div>
                <?php unset($_SESSION['success_msg']); ?>
            <?php endif; ?>

            <!-- 5-ROLE SEGMENTED SELECTOR -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1 px-1">
                    <span class="text-muted fw-bold" style="font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.5px;">Portal Access Role:</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold" id="roleBadgeTag" style="font-size: 0.62rem;">Beneficiary</span>
                </div>
                
                <div class="role-pills-bar">
                    <!-- 1. Customer -->
                    <button type="button" class="role-pill-item active" data-role="Customer" id="roleBtnCustomer" onclick="switchRole('Customer')">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Customer</span>
                    </button>
                    <!-- 2. Advisor -->
                    <button type="button" class="role-pill-item" data-role="Advisor" id="roleBtnAdvisor" onclick="switchRole('Advisor')">
                        <i class="bi bi-award-fill"></i>
                        <span>Advisor</span>
                    </button>
                    <!-- 3. BOE Staff -->
                    <button type="button" class="role-pill-item" data-role="BOE" id="roleBtnBOE" onclick="switchRole('BOE')">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>BOE Staff</span>
                    </button>
                    <!-- 4. Field Engineer -->
                    <button type="button" class="role-pill-item" data-role="Engineer" id="roleBtnEngineer" onclick="switchRole('Engineer')">
                        <i class="bi bi-tools"></i>
                        <span>Engineer</span>
                    </button>
                    <!-- 5. Admin / Manager -->
                    <button type="button" class="role-pill-item" data-role="Admin" id="roleBtnAdmin" onclick="switchRole('Admin')">
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>Admin</span>
                    </button>
                </div>
            </div>

            <!-- DYNAMIC ROLE HELPER BANNER -->
            <div class="role-header-banner mb-3" id="roleHelperBanner" style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46;">
                <i class="bi bi-info-circle-fill fs-5 flex-shrink-0" id="roleHelperIcon"></i>
                <div class="overflow-hidden">
                    <div class="fw-bold" id="roleHelperTitle" style="font-size: 0.82rem;">Beneficiary Portal</div>
                    <div class="text-truncate small" id="roleHelperText" style="font-size: 0.72rem; opacity: 0.9;">Track 15-stage rooftop solar installation, meter & ₹1,38,000 DBT subsidy.</div>
                </div>
            </div>

            <!-- 1-CLICK DEMO CREDENTIAL CHIPS -->
            <div class="mb-3 bg-light p-2 rounded-3 border">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted fw-bold" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i>1-Click Test Credentials:
                    </span>
                </div>
                <div class="d-flex flex-wrap gap-1" id="demoChipsContainer">
                    <!-- Populated by JavaScript -->
                </div>
            </div>

            <!-- LOGIN FORM -->
            <form action="<?= url('/login') ?>" method="POST" id="loginForm">
                <?= function_exists('csrf_field') ? csrf_field() : '' ?>

                <!-- IDENTIFIER INPUT -->
                <div class="mb-3">
                    <label class="form-label small fw-bold text-navy mb-1" id="labelIdentifier">
                        Consumer Number or Registered Mobile:
                    </label>
                    <div class="input-group login-input-wrap">
                        <span class="input-group-text" id="identifierIcon">
                            <i class="bi bi-phone"></i>
                        </span>
                        <input type="text" name="identifier" id="loginIdentifier" class="form-control" placeholder="9040237079 or DISCOM A/C" value="<?= htmlspecialchars($oldIdentifier ?? '9040237079') ?>" required autofocus autocomplete="username" inputmode="text">
                    </div>
                    <div class="text-muted mt-1" id="inputHintText" style="font-size: 0.7rem;">
                        <i class="bi bi-info-circle me-1"></i> Found on your electricity bill or PM Surya Ghar registration.
                    </div>
                </div>

                <!-- PASSWORD INPUT WITH EYE TOGGLE -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label small fw-bold text-navy mb-0" id="labelPassword">Password:</label>
                        <a href="<?= url('/contact') ?>" class="small text-muted text-decoration-none fw-semibold" style="font-size: 0.72rem;">Forgot Password?</a>
                    </div>
                    <div class="input-group login-input-wrap">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" name="password" id="loginPassword" class="form-control" placeholder="••••••••" value="Password@123" required autocomplete="current-password">
                        <button type="button" class="btn bg-transparent border-0 text-secondary px-3 d-flex align-items-center justify-content-center" onclick="togglePasswordVisibility()" aria-label="Toggle password visibility" style="min-width: 44px;">
                            <i class="bi bi-eye fs-6" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- SECURITY CAPTCHA CARD (MOBILE OPTIMIZED) -->
                <div class="captcha-container-box mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-bold text-navy" style="font-size: 0.75rem;">
                            <i class="bi bi-shield-check text-primary me-1"></i> Security Code
                        </span>
                        <button type="button" class="btn btn-link text-primary p-0 small text-decoration-none fw-semibold" style="font-size: 0.72rem;" onclick="refreshCaptcha()" title="Tap to refresh image">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                        </button>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="captcha-preview-img" onclick="refreshCaptcha()" title="Click to refresh security code">
                            <img src="<?= url('/captcha') ?>?t=<?= time() ?>" id="loginCaptchaImg" alt="Security Code">
                        </div>
                        <input type="text" name="captcha" id="inputCaptcha" class="form-control text-uppercase font-monospace fw-bold text-center border shadow-sm" style="height: 44px; font-size: 1.1rem; letter-spacing: 2px; border-radius: 10px;" placeholder="CODE" maxlength="6" required autocomplete="off">
                    </div>
                </div>

                <!-- SUBMIT ACTION BUTTON -->
                <button type="submit" class="btn btn-submit-action theme-btn-customer w-100 mb-3" id="btnSubmitLogin">
                    <i class="bi bi-box-arrow-in-right fs-5"></i>
                    <span id="btnSubmitText">Sign In as Beneficiary</span>
                </button>
            </form>

            <!-- FOOTER LINKS & QUICK ACTIONS -->
            <div class="border-top pt-3 text-center" id="footerActionLinks">
                <p class="small text-muted mb-2" style="font-size: 0.78rem;">New to PM Surya Ghar Muft Bijli Yojana?</p>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="<?= url('/register-customer') ?>" class="btn btn-outline-success btn-sm fw-semibold rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                        <i class="bi bi-sun-fill text-warning"></i> Register for Solar
                    </a>
                    <a href="<?= url('/register-advisor') ?>" class="btn btn-outline-dark btn-sm fw-semibold rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                        <i class="bi bi-award-fill text-warning"></i> Join as Advisor
                    </a>
                </div>
            </div>

        </div>

        <!-- STATUTORY DISCLAIMER FOOTER -->
        <div class="text-center mt-3 text-muted" style="font-size: 0.68rem; line-height: 1.5;">
            Authorized Channel Promoter for PM Surya Ghar: Muft Bijli Yojana Odisha.<br>
            Empaneled across TPCODL, TPNODL, TPSODL & TPWODL Discoms.
        </div>

    </div>
</div>

<script>
// Role Configuration Matrix
const roleMatrix = {
    Customer: {
        badge: 'Beneficiary',
        title: 'Customer Sign In',
        helperTitle: 'Beneficiary Portal',
        helperText: 'Track 15-stage rooftop solar installation, meter & ₹1,38,000 DBT subsidy.',
        bannerBg: '#ECFDF5',
        bannerBorder: '#A7F3D0',
        bannerColor: '#065F46',
        iconClass: 'bi bi-house-door-fill text-success',
        inputLabel: 'Consumer Number or Registered Mobile:',
        inputPlaceholder: '9040237079 or TPCODL A/C',
        inputHint: 'Found on your electricity bill or PM Surya Ghar registration.',
        inputIcon: '<i class="bi bi-phone text-success"></i>',
        btnClass: 'theme-btn-customer',
        btnText: 'Sign In as Beneficiary',
        demoList: [
            { label: '9040237079 (Active Customer)', id: '9040237079', pw: 'Password@123' },
            { label: '9861044912 (Cuttack 3kW)', id: '9861044912', pw: 'Password@123' }
        ]
    },
    Advisor: {
        badge: 'Solar Mitra',
        title: 'Advisor Partner Sign In',
        helperTitle: 'Advisor Partner Command',
        helperText: 'Manage consumer leads, commission wallet & 9-level MLM network.',
        bannerBg: '#FFFBEB',
        bannerBorder: '#FDE68A',
        bannerColor: '#92400E',
        iconClass: 'bi bi-award-fill text-warning',
        inputLabel: 'Advisor Referral Code or Mobile:',
        inputPlaceholder: '9040999899 or SVPL-ADV-8842',
        inputHint: 'Registered with Odisha DISCOM Solar Mitra Network.',
        inputIcon: '<i class="bi bi-award text-warning"></i>',
        btnClass: 'theme-btn-advisor',
        btnText: 'Access Advisor Command',
        demoList: [
            { label: '9040999899 (Zonal Partner)', id: '9040999899', pw: 'Password@123' },
            { label: 'SVPL-ADV-1001 (Advisor)', id: 'SVPL-ADV-1001', pw: 'Password@123' }
        ]
    },
    BOE: {
        badge: 'Staff Desk',
        title: 'BOE Staff Sign In',
        helperTitle: 'Back Office Executive Desk',
        helperText: 'DISCOM documentation, bank loan liaisons & DBT vetting desk.',
        bannerBg: '#F0F9FF',
        bannerBorder: '#BAE6FD',
        bannerColor: '#075985',
        iconClass: 'bi bi-person-badge-fill text-info',
        inputLabel: 'Staff Mobile or Employee ID:',
        inputPlaceholder: '9124589345 or SVPL-BOE-001',
        inputHint: 'Authorized corporate operations ID @ Bhubaneswar HQ.',
        inputIcon: '<i class="bi bi-headset text-info"></i>',
        btnClass: 'theme-btn-boe',
        btnText: 'Sign In to Back Office',
        demoList: [
            { label: '9124589345 (BOE Executive)', id: '9124589345', pw: 'Password@123' }
        ]
    },
    Engineer: {
        badge: 'Field Engineer',
        title: 'Field Engineer Sign In',
        helperTitle: 'Engineering & Commissioning',
        helperText: 'Site feasibility, structure inspection & DISCOM net-metering.',
        bannerBg: '#FFF7ED',
        bannerBorder: '#FFEDD5',
        bannerColor: '#9A3412',
        iconClass: 'bi bi-tools text-warning',
        inputLabel: 'Engineer Licensure / Mobile / ID:',
        inputPlaceholder: '9876543210 or SVPL-ENG-001',
        inputHint: 'Empaneled rooftop site inspector & electrical surveyor.',
        inputIcon: '<i class="bi bi-tools text-warning"></i>',
        btnClass: 'theme-btn-engineer',
        btnText: 'Launch Field Inspection Hub',
        demoList: [
            { label: '9876543210 (Site Engineer)', id: '9876543210', pw: 'Password@123' }
        ]
    },
    Admin: {
        badge: 'Executive HQ',
        title: 'Admin HQ Sign In',
        helperTitle: 'Executive Admin Command',
        helperText: 'Master state dashboard, revenue ledgers & regulatory audits.',
        bannerBg: '#EEF2FF',
        bannerBorder: '#C7D2FE',
        bannerColor: '#3730A3',
        iconClass: 'bi bi-shield-lock-fill text-primary',
        inputLabel: 'Master Admin Email / Username:',
        inputPlaceholder: 'admin@suryavistaara.com',
        inputHint: 'Hardware token protected 2FA executive access.',
        inputIcon: '<i class="bi bi-shield-lock text-primary"></i>',
        btnClass: 'theme-btn-admin',
        btnText: 'Unlock Executive Command',
        demoList: [
            { label: 'admin@suryavistaara.com (Director)', id: 'admin@suryavistaara.com', pw: 'Password@123' }
        ]
    }
};

function switchRole(roleName) {
    const config = roleMatrix[roleName] || roleMatrix.Customer;

    // 1. Update active tab pill
    document.querySelectorAll('.role-pill-item').forEach(btn => btn.classList.remove('active'));
    const targetPill = document.getElementById('roleBtn' + roleName);
    if (targetPill) targetPill.classList.add('active');

    // 2. Update Badge & Titles
    const badge = document.getElementById('roleBadgeTag');
    if (badge) badge.innerText = config.badge;

    const headerTitle = document.getElementById('loginHeaderTitle');
    if (headerTitle) headerTitle.innerText = config.title;

    // 3. Update Banner
    const banner = document.getElementById('roleHelperBanner');
    const bannerTitle = document.getElementById('roleHelperTitle');
    const bannerText = document.getElementById('roleHelperText');
    const bannerIcon = document.getElementById('roleHelperIcon');

    if (banner) {
        banner.style.background = config.bannerBg;
        banner.style.borderColor = config.bannerBorder;
        banner.style.color = config.bannerColor;
    }
    if (bannerTitle) bannerTitle.innerText = config.helperTitle;
    if (bannerText) bannerText.innerText = config.helperText;
    if (bannerIcon) bannerIcon.className = config.iconClass + ' fs-5 flex-shrink-0';

    // 4. Update Inputs
    const labelId = document.getElementById('labelIdentifier');
    const inputId = document.getElementById('loginIdentifier');
    const iconId = document.getElementById('identifierIcon');
    const hintId = document.getElementById('inputHintText');

    if (labelId) labelId.innerText = config.inputLabel;
    if (inputId) inputId.placeholder = config.inputPlaceholder;
    if (iconId) iconId.innerHTML = config.inputIcon;
    if (hintId) hintId.innerHTML = '<i class="bi bi-info-circle me-1"></i> ' + config.inputHint;

    // 5. Update Submit Button
    const submitBtn = document.getElementById('btnSubmitLogin');
    const submitText = document.getElementById('btnSubmitText');
    if (submitBtn) {
        submitBtn.className = 'btn btn-submit-action w-100 mb-3 ' + config.btnClass;
    }
    if (submitText) submitText.innerText = config.btnText;

    // 6. Update Demo Chips
    const chipsContainer = document.getElementById('demoChipsContainer');
    if (chipsContainer) {
        chipsContainer.innerHTML = '';
        config.demoList.forEach(item => {
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'demo-chip-btn';
            chip.innerHTML = '<i class="bi bi-key-fill text-warning"></i> ' + item.label;
            chip.onclick = function() {
                if (inputId) inputId.value = item.id;
                const pwdInput = document.getElementById('loginPassword');
                if (pwdInput) pwdInput.value = item.pw;
            };
            chipsContainer.appendChild(chip);
        });
        // Auto fill the first demo credentials
        if (config.demoList.length > 0 && inputId) {
            inputId.value = config.demoList[0].id;
        }
    }
}

function togglePasswordVisibility() {
    const pwdInput = document.getElementById('loginPassword');
    const icon = document.getElementById('togglePasswordIcon');
    if (pwdInput.type === 'password') {
        pwdInput.type = 'text';
        icon.className = 'bi bi-eye-slash fs-6 text-warning';
    } else {
        pwdInput.type = 'password';
        icon.className = 'bi bi-eye fs-6';
    }
}

function refreshCaptcha() {
    const img = document.getElementById('loginCaptchaImg');
    if (img) {
        img.src = '<?= url('/captcha') ?>?t=' + new Date().getTime();
        const capInput = document.getElementById('inputCaptcha');
        if (capInput) capInput.value = '';
    }
}

// Initialize Customer on load
document.addEventListener('DOMContentLoaded', function() {
    switchRole('Customer');
});
</script>
