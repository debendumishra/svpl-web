<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Advisor Partner Workspace Layout (Fixed Layout, Constant Header/Footer & Expandable Navigation)
 */
$advisorName = $_SESSION['user_name'] ?? 'Advisor Partner';
$advisorCode = $_SESSION['advisor_code'] ?? 'SVPL-ADV-8842';
$referralCode = $_SESSION['referral_code'] ?? 'SVPL';
$qualificationStatus = $_SESSION['qualification_status'] ?? 'QUALIFIED';
$activeUri = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Solar Advisor Partner Portal | Surya Vistaara') ?></title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <meta name="base-url" content="<?= base_path_url() ?>">
    
    <!-- Solar Theme & Network CSS -->
    <link rel="stylesheet" href="<?= asset('assets/css/solar-theme.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/network-tree.css') ?>">
</head>
<body>

    <!-- FIXED APPLICATION WRAPPER -->
    <div class="app-layout-wrapper">
        
        <!-- CONSTANT TOP HEADER (STICKY) -->
        <header class="app-header">
            <!-- Ticker (Desktop) -->
            <div class="live-ticker-bar px-3 d-none d-lg-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-white"><i class="bi bi-shield-fill-check text-warning me-1"></i> Certified Solar Advisor Partner Portal</span>
                        <span class="d-none d-md-inline text-success fw-bold"><i class="bi bi-gift-fill me-1"></i> Central ₹78k + Odisha ₹60k = Total ₹1,38,000 Subsidy</span>
                    </div>
                    <div>
                        <span class="badge bg-dark text-warning border border-warning" style="font-size: 0.68rem;">Dhwajja Solar India</span>
                    </div>
                </div>
            </div>

            <!-- Primary Header Strip with User Details & Quick Actions -->
            <div class="px-2 px-md-3 py-2 d-flex justify-content-between align-items-center border-bottom bg-white">
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <!-- Mobile Hamburger Button -->
                    <button class="btn btn-light border btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#advisorMobileDrawer" aria-label="Open Mobile Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900;">
                        ☀
                    </div>
                    <div>
                        <div class="fw-bold font-heading text-navy" style="font-size: 0.95rem; line-height: 1.2;">
                            <?= htmlspecialchars($advisorName) ?>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.65rem;"><?= htmlspecialchars($advisorCode) ?></span>
                            <span class="badge <?= $qualificationStatus === 'QUALIFIED' ? 'bg-success' : 'bg-warning text-dark' ?>" style="font-size: 0.62rem;">
                                <?= htmlspecialchars($qualificationStatus) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-1 gap-md-2">
                    <div class="d-none d-sm-flex align-items-center gap-1 bg-light p-1 px-2 rounded-pill border small">
                        <span class="text-secondary" style="font-size: 0.72rem;">Ref:</span>
                        <span class="badge bg-primary font-monospace cursor-pointer btn-copy" data-copy="<?= htmlspecialchars($referralCode) ?>" title="Click to copy">
                            <?= htmlspecialchars($referralCode) ?> <i class="bi bi-clipboard ms-1"></i>
                        </span>
                    </div>
                    <a href="<?= url('/advisor/register-customer') ?>" class="btn btn-svpl-solar btn-sm shadow-sm d-none d-md-inline-flex">
                        <i class="bi bi-plus-circle-fill me-1"></i> + Register Customer
                    </a>
                    <a href="<?= url('/advisor/qr-code') ?>" class="btn btn-outline-dark btn-sm" title="Doorstep QR Code">
                        <i class="bi bi-qr-code"></i>
                    </a>
                    <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm" title="Sign Out">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Horizontal Tab Navigation Bar (Desktop & Tablet) -->
            <div class="bg-light px-3 py-1 border-bottom d-none d-lg-flex gap-1 overflow-x-auto">
                <a href="<?= url('/advisor/dashboard') ?>" class="btn btn-sm <?= $activeUri === url('/advisor/dashboard') ? 'btn-primary' : 'btn-light border' ?>">
                    <i class="bi bi-speedometer2 me-1"></i> Command Center
                </a>
                <a href="<?= url('/advisor/register-customer') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/register-customer') !== false ? 'btn-svpl-solar' : 'btn-outline-warning text-dark border' ?> fw-bold">
                    <i class="bi bi-person-plus-fill me-1"></i> + Register Customer
                </a>
                <a href="<?= url('/advisor/customers') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/customers') !== false ? 'btn-primary' : 'btn-light border' ?>">
                    <i class="bi bi-people me-1"></i> My Customers
                </a>
                <a href="<?= url('/advisor/network') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/network') !== false ? 'btn-primary' : 'btn-light border' ?>">
                    <i class="bi bi-bezier2 me-1"></i> 9-Level Tree
                </a>
                <a href="<?= url('/advisor/wallet') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/wallet') !== false ? 'btn-primary' : 'btn-light border' ?>">
                    <i class="bi bi-wallet2 me-1"></i> Commission Wallet
                </a>
                <a href="<?= url('/advisor/id-card') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/id-card') !== false ? 'btn-primary' : 'btn-light border' ?>">
                    <i class="bi bi-person-vcard me-1"></i> ID Card & Letter
                </a>
                <a href="<?= url('/advisor/qr-code') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/qr-code') !== false ? 'btn-primary' : 'btn-light border' ?>">
                    <i class="bi bi-qr-code-scan me-1"></i> Doorstep QR
                </a>
            </div>
        </header>

        <!-- MOBILE OFFCANVAS DRAWER MENU (BOOTSTRAP 5) -->
        <div class="offcanvas offcanvas-start offcanvas-svpl" tabindex="-1" id="advisorMobileDrawer" aria-labelledby="advisorMobileDrawerLabel">
            <div class="offcanvas-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900;">
                        ☀
                    </div>
                    <div>
                        <h6 class="offcanvas-title font-heading fw-bold text-white mb-0" id="advisorMobileDrawerLabel"><?= htmlspecialchars($advisorName) ?></h6>
                        <small class="text-warning fw-semibold" style="font-size: 0.7rem;"><?= htmlspecialchars($advisorCode) ?></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            
            <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
                <div class="py-2">
                    <div class="p-3 mx-2 mb-2 rounded-3 bg-dark bg-opacity-50 border border-secondary">
                        <div class="small text-secondary mb-1">Your Referral Code:</div>
                        <div class="d-flex align-items-center justify-content-between">
                            <strong class="text-warning fs-5 font-monospace"><?= htmlspecialchars($referralCode) ?></strong>
                            <button class="btn btn-warning btn-sm py-0 px-2 fw-bold btn-copy" data-copy="<?= htmlspecialchars($referralCode) ?>">
                                Copy <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Command & Identity Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#advMobCommand" aria-expanded="true">
                        <span><i class="bi bi-speedometer2 text-warning me-1"></i> Core Workspace</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="advMobCommand">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/advisor/dashboard') !== false ? 'active' : '' ?>" href="<?= url('/advisor/dashboard') ?>">
                                    <i class="bi bi-speedometer2"></i> <span>Command Center</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/advisor/register-customer') !== false ? 'active text-warning fw-bold' : 'text-warning fw-bold' ?>" href="<?= url('/advisor/register-customer') ?>">
                                    <i class="bi bi-person-plus-fill text-warning"></i> <span>+ Register Customer</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/advisor/qr-code') !== false ? 'active' : '' ?>" href="<?= url('/advisor/qr-code') ?>">
                                    <i class="bi bi-qr-code-scan"></i> <span>Doorstep QR Code</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/advisor/id-card') !== false ? 'active' : '' ?>" href="<?= url('/advisor/id-card') ?>">
                                    <i class="bi bi-person-vcard"></i> <span>ID Card & Letter</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Customer & Downline Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#advMobCustNet" aria-expanded="true">
                        <span><i class="bi bi-diagram-3-fill text-warning me-1"></i> Customers & Network</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="advMobCustNet">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link text-warning fw-bold" href="<?= url('/advisor/register-customer') ?>">
                                    <i class="bi bi-plus-circle-fill text-warning"></i> <span>+ Register Customer</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/advisor/customers') !== false ? 'active' : '' ?>" href="<?= url('/advisor/customers') ?>">
                                    <i class="bi bi-people"></i> <span>My Direct Customers</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/advisor/network') !== false ? 'active' : '' ?>" href="<?= url('/advisor/network') ?>">
                                    <i class="bi bi-bezier2"></i> <span>9-Level Tree Visualizer</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Finance & Wallet Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#advMobFinance" aria-expanded="true">
                        <span><i class="bi bi-wallet2 text-warning me-1"></i> Earnings & Wallet</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="advMobFinance">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/advisor/wallet') !== false ? 'active' : '' ?>" href="<?= url('/advisor/wallet') ?>">
                                    <i class="bi bi-cash-stack"></i> <span>Commission Wallet</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Sign Out in Drawer -->
                <div class="p-3 border-top border-secondary bg-dark bg-opacity-50">
                    <a href="<?= url('/logout') ?>" class="btn btn-outline-danger w-100 btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Sign Out
                    </a>
                </div>
            </div>
        </div>

        <!-- INDEPENDENTLY SCROLLABLE MAIN CONTENT AREA -->
        <main class="app-main-content">
            <div class="container-fluid max-w-7xl">
                <?= $content ?>
            </div>
        </main>

        <!-- CONSTANT FOOTER (STICKY BOTTOM, DESKTOP ONLY) -->
        <footer class="app-footer text-muted d-none d-md-flex justify-content-between align-items-center">
            <div>© <?= date('Y') ?> <strong>Surya Vistaara Pvt. Ltd. (SVPL)</strong> — Advisor Partner Network.</div>
            <div>24x7 Advisor Support: 1800-889-SVPL</div>
        </footer>

        <!-- MOBILE-FIRST NATIVE BOTTOM NAVIGATION BAR (FIXED TOUCH BAR) -->
        <nav class="svpl-mobile-bottom-nav d-lg-none">
            <a href="<?= url('/advisor/dashboard') ?>" class="mob-nav-item <?= $activeUri === url('/advisor/dashboard') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Home</span>
            </a>
            <a href="<?= url('/advisor/customers') ?>" class="mob-nav-item <?= strpos($activeUri, '/advisor/customers') !== false ? 'active' : '' ?>">
                <i class="bi bi-people"></i>
                <span>Clients</span>
            </a>
            <a href="<?= url('/advisor/network') ?>" class="mob-nav-item <?= strpos($activeUri, '/advisor/network') !== false ? 'active' : '' ?>">
                <i class="bi bi-bezier2"></i>
                <span>9-Tree</span>
            </a>
            <a href="<?= url('/advisor/wallet') ?>" class="mob-nav-item <?= strpos($activeUri, '/advisor/wallet') !== false ? 'active' : '' ?>">
                <i class="bi bi-wallet2"></i>
                <span>Wallet</span>
            </a>
            <button type="button" class="mob-nav-item" data-bs-toggle="offcanvas" data-bs-target="#advisorMobileDrawer" aria-label="Open Menu">
                <i class="bi bi-grid-fill"></i>
                <span>Menu</span>
            </button>
        </nav>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
