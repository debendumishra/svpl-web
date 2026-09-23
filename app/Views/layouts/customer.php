<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Customer Solar Experience Portal Layout (Fixed Layout, Constant Header/Footer & Clean Navigation)
 */
$customerName = $_SESSION['user_name'] ?? 'Beneficiary Customer';
$customerCode = $_SESSION['customer_code'] ?? 'SVPL-CUS-1082';
$activeUri = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Customer Solar Portal | PM Surya Ghar Odisha') ?></title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Dynamic Favicon -->
    <?php if ($favUrl = company_favicon_url()): ?>
        <link rel="icon" href="<?= htmlspecialchars($favUrl) ?>">
    <?php endif; ?>

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <meta name="base-url" content="<?= base_path_url() ?>">
    
    <!-- Solar Theme CSS with Cache-Busting -->
    <?php 
    $cssFile = dirname(__DIR__, 3) . '/public/assets/css/solar-theme.css';
    $cssVer = file_exists($cssFile) ? filemtime($cssFile) : time();
    ?>
    <link rel="stylesheet" href="<?= asset('assets/css/solar-theme.css?v=' . $cssVer) ?>">

    <style>
        .app-header { position: sticky; top: 0; z-index: 1030; background: #FFFFFF; width: 100%; box-shadow: 0 1px 3px rgba(15,23,42,0.05); }
        .app-header-strip { display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; justify-content: space-between !important; align-items: center !important; width: 100% !important; height: 56px !important; min-height: 56px !important; max-height: 56px !important; padding: 0 12px !important; background: #FFFFFF !important; border-bottom: 1px solid #E2E8F0 !important; box-sizing: border-box !important; }
        .app-header-left { display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; align-items: center !important; gap: 8px !important; min-width: 0 !important; flex: 1 1 auto !important; overflow: hidden !important; }
        .app-header-emblem { width: 36px !important; height: 36px !important; min-width: 36px !important; border-radius: 9px !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; font-weight: 800 !important; font-size: 1.1rem !important; flex-shrink: 0 !important; }
        .app-header-title-wrap { display: flex !important; flex-direction: column !important; justify-content: center !important; min-width: 0 !important; overflow: hidden !important; line-height: 1.2 !important; }
        .app-header-title-row { display: flex !important; align-items: center !important; gap: 6px !important; overflow: hidden !important; white-space: nowrap !important; }
        .app-header-title { font-family: 'Outfit', sans-serif !important; font-weight: 700 !important; font-size: 0.92rem !important; color: #0F172A !important; white-space: nowrap !important; overflow: hidden !important; text-overflow: ellipsis !important; margin: 0 !important; }
        .app-header-subtitle { font-size: 0.7rem !important; color: #64748B !important; white-space: nowrap !important; overflow: hidden !important; text-overflow: ellipsis !important; display: flex !important; align-items: center !important; gap: 4px !important; margin-top: 1px !important; }
        .app-header-right { display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; align-items: center !important; gap: 6px !important; flex-shrink: 0 !important; margin-left: auto !important; }
        .app-header-btn { width: 36px; height: 36px; min-width: 36px; max-width: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 0.92rem; font-weight: 600; flex-shrink: 0; text-decoration: none; border: 1px solid #E2E8F0; background: #F8FAFC; color: #334155; }
        .app-header-btn.d-none { display: none !important; }
        @media (min-width: 992px) {
            .app-header-btn.d-lg-none { display: none !important; }
            .app-header-btn.d-none.d-lg-inline-flex,
            .app-header-btn.d-lg-inline-flex { display: inline-flex !important; }
            .portal-sidebar { display: flex !important; visibility: visible !important; }
        }
        @media (max-width: 991.98px) {
            .app-header-btn.d-none.d-lg-inline-flex { display: none !important; }
            .app-header-btn.d-lg-none { display: inline-flex !important; }
            .portal-sidebar { display: none !important; }
        }
        .app-header-logout { border: 1px solid #FCA5A5 !important; background-color: #FEF2F2 !important; color: #DC2626 !important; }
        .app-header-logout:hover { background-color: #DC2626 !important; color: #FFFFFF !important; border-color: #DC2626 !important; }
    </style>
</head>
<body>

    <!-- FIXED APPLICATION WRAPPER -->
    <div class="app-layout-wrapper">
        
        <!-- CONSTANT TOP HEADER (STICKY) -->
        <header class="app-header">
            <!-- Ticker (Desktop Only) -->
            <div class="live-ticker-bar px-3 d-none d-lg-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-white"><i class="bi bi-patch-check-fill text-warning me-1"></i> PM Surya Ghar: Muft Bijli Yojana Odisha</span>
                        <span class="text-success fw-bold d-none d-md-inline"><i class="bi bi-gift-fill me-1"></i> ₹1,38,000 Combined Central & Odisha State Subsidy</span>
                    </div>
                    <div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.7rem;">DISCOM Verified</span>
                    </div>
                </div>
            </div>

            <!-- Customer Header Strip -->
            <div class="app-header-strip">
                <div class="app-header-left">
                    <!-- Mobile Hamburger Button -->
                    <button class="app-header-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#customerMobileDrawer" aria-label="Open Mobile Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <!-- Desktop Sidebar Toggle Button -->
                    <button class="app-header-btn d-none d-lg-inline-flex" id="sidebarToggleBtn" title="Toggle Sidebar Expand/Collapse">
                        <i class="bi bi-layout-sidebar-inset fs-5"></i>
                    </button>

                    <div class="app-header-emblem" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: #FFFFFF;">
                        ☀
                    </div>
                    <div class="app-header-title-wrap">
                        <div class="app-header-title-row">
                            <span class="app-header-title"><?= htmlspecialchars($customerName) ?></span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold d-none d-sm-inline-block" style="font-size: 0.62rem;">Dhwajja 3kW</span>
                        </div>
                        <span class="app-header-subtitle">
                            <span class="badge bg-light text-dark border font-monospace px-1" style="font-size: 0.65rem;"><?= htmlspecialchars($customerCode) ?></span>
                            <span class="d-none d-sm-inline text-muted">• PM Surya Ghar</span>
                        </span>
                    </div>
                </div>

                <!-- Unified Header Right Actions -->
                <div class="app-header-right">
                    <a href="<?= url('/customer/dashboard') ?>" class="btn btn-sm <?= $activeUri === url('/customer/dashboard') ? 'btn-primary shadow-sm' : 'btn-light border' ?> d-none d-md-inline-flex align-items-center gap-1" style="height: 36px; font-size: 0.8rem;">
                        <i class="bi bi-speedometer2"></i> <span>Tracker</span>
                    </a>
                    <a href="<?= url('/customer/quotation') ?>" class="btn btn-sm <?= strpos($activeUri, '/customer/quotation') !== false ? 'btn-primary shadow-sm' : 'btn-light border' ?> d-none d-lg-inline-flex align-items-center gap-1" style="height: 36px; font-size: 0.8rem;">
                        <i class="bi bi-file-earmark-text"></i> <span>Proposal</span>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-success d-none d-md-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalConsumerAgreement" style="height: 36px; font-size: 0.8rem;" title="View Signed PM Surya Ghar Agreement">
                        <i class="bi bi-file-earmark-check-fill text-success"></i> <span>Agreement</span>
                    </button>
                    <a href="tel:18008897875" class="app-header-btn" style="color: #059669 !important;" title="Call Solar Helpline">
                        <i class="bi bi-telephone-fill"></i>
                    </a>
                    <a href="<?= url('/logout') ?>" class="app-header-btn app-header-logout" title="Sign Out">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>
            </div>
        </header>

        <!-- MOBILE OFFCANVAS DRAWER MENU (BOOTSTRAP 5) -->
        <div class="offcanvas offcanvas-start offcanvas-svpl" tabindex="-1" id="customerMobileDrawer" aria-labelledby="customerMobileDrawerLabel">
            <div class="offcanvas-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 900;">
                        ☀
                    </div>
                    <div>
                        <h6 class="offcanvas-title font-heading fw-bold text-white mb-0" id="customerMobileDrawerLabel"><?= htmlspecialchars($customerName) ?></h6>
                        <small class="text-success fw-semibold" style="font-size: 0.7rem;"><?= htmlspecialchars($customerCode) ?></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            
            <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
                <div class="py-2">
                    <div class="p-3 mx-2 mb-3 rounded-3 bg-dark bg-opacity-50 border border-secondary">
                        <div class="small text-secondary mb-1">Consumer Status:</div>
                        <div class="d-flex align-items-center justify-content-between">
                            <strong class="text-success font-monospace">Dhwajja 3kW Plant</strong>
                            <span class="badge bg-success">₹1,38,000 Subsidy</span>
                        </div>
                    </div>

                    <!-- Customer Workspace Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#custMobMenu" aria-expanded="true">
                        <span><i class="bi bi-sun-fill text-warning me-1"></i> Solar Journey</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="custMobMenu">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link <?= $activeUri === url('/customer/dashboard') ? 'active' : '' ?>" href="<?= url('/customer/dashboard') ?>">
                                    <i class="bi bi-speedometer2"></i> <span>Live Milestone Tracker</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/customer/quotation') !== false ? 'active' : '' ?>" href="<?= url('/customer/quotation') ?>">
                                    <i class="bi bi-file-earmark-text"></i> <span>Official Proposal & Cost</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/customer/documents') !== false ? 'active' : '' ?>" href="<?= url('/customer/documents') ?>">
                                    <i class="bi bi-folder-check"></i> <span>KYC & Document Locker</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white fw-semibold" href="#" data-bs-toggle="modal" data-bs-target="#modalConsumerAgreement" data-bs-dismiss="offcanvas">
                                    <i class="bi bi-file-earmark-check-fill text-success"></i> <span>Signed Solar Agreement</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/customer/become-advisor') !== false ? 'active' : '' ?> text-warning fw-semibold" href="<?= url('/customer/become-advisor') ?>">
                                    <i class="bi bi-award-fill text-warning"></i> <span>Become Solar Advisor</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#custMobSupport" aria-expanded="true">
                        <span><i class="bi bi-headset text-warning me-1"></i> Helpline & Support</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="custMobSupport">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link text-success" href="tel:18008897875">
                                    <i class="bi bi-telephone-fill text-success"></i> <span>Toll-Free: 1800-889-SVPL</span>
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

        <!-- APP BODY CONTAINER (DESKTOP SIDEBAR + MAIN CONTENT) -->
        <div class="app-body-container">
            
            <!-- DESKTOP EXPANDABLE PORTAL SIDEBAR -->
            <aside class="portal-sidebar" id="appSidebar">
                <div class="py-2 flex-grow-1">
                    <div class="p-3 mx-2 mb-3 rounded-3 bg-dark bg-opacity-50 border border-secondary border-opacity-50">
                        <div class="text-white-50 small mb-1">Consumer Plan</div>
                        <strong class="text-success font-monospace d-block">Dhwajja 3kW Plant</strong>
                        <span class="badge bg-success-subtle text-success border border-success-subtle mt-1" style="font-size: 0.65rem;">₹1,38,000 Subsidy</span>
                    </div>

                    <!-- Solar Journey Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#menuCustJourney">
                        <span><i class="bi bi-sun-fill text-warning me-1"></i> Solar Journey</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="menuCustJourney">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link <?= $activeUri === url('/customer/dashboard') ? 'active' : '' ?>" href="<?= url('/customer/dashboard') ?>">
                                    <i class="bi bi-speedometer2"></i> <span class="sidebar-text">Milestone Tracker</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/customer/quotation') !== false ? 'active' : '' ?>" href="<?= url('/customer/quotation') ?>">
                                    <i class="bi bi-file-earmark-text"></i> <span class="sidebar-text">Official Proposal</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/customer/documents') !== false ? 'active' : '' ?>" href="<?= url('/customer/documents') ?>">
                                    <i class="bi bi-folder-check"></i> <span class="sidebar-text">KYC Locker</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white fw-semibold" href="#" data-bs-toggle="modal" data-bs-target="#modalConsumerAgreement">
                                    <i class="bi bi-file-earmark-check-fill text-success"></i> <span class="sidebar-text">Signed Agreement</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/customer/become-advisor') !== false ? 'active' : '' ?> text-warning fw-semibold" href="<?= url('/customer/become-advisor') ?>">
                                    <i class="bi bi-award-fill text-warning"></i> <span class="sidebar-text">Become Solar Advisor</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Support Category -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#menuCustSupport">
                        <span><i class="bi bi-headset text-warning me-1"></i> Support</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="menuCustSupport">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link text-success" href="tel:18008897875">
                                    <i class="bi bi-telephone-fill text-success"></i> <span class="sidebar-text">1800-889-SVPL</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Consumer Profile Tile at bottom of sidebar -->
                <div class="user-profile-tile d-flex align-items-center justify-content-between p-3 border-top border-secondary border-opacity-50">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: #059669; color: #FFF; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">
                            <?= strtoupper(substr($customerName, 0, 2)) ?>
                        </div>
                        <div class="user-info-text overflow-hidden">
                            <div class="text-white fw-bold small text-truncate" style="max-width: 140px;"><?= htmlspecialchars($customerName) ?></div>
                            <div class="text-success small font-monospace" style="font-size: 0.7rem;"><?= htmlspecialchars($customerCode) ?></div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- INDEPENDENTLY SCROLLABLE MAIN CONTENT AREA -->
            <main class="app-main-content">
                <div class="container-fluid max-w-7xl">
                    <?= $content ?>
                </div>
            </main>
        </div>

        <!-- CONSTANT FOOTER (STICKY BOTTOM, DESKTOP ONLY) -->
        <footer class="app-footer text-muted d-none d-md-flex justify-content-between align-items-center">
            <div>© <?= date('Y') ?> <strong>Surya Vistaara Pvt. Ltd. (SVPL)</strong> — Beneficiary Support Desk.</div>
            <div>Helpline Toll-Free: 1800-889-SVPL</div>
        </footer>

        <!-- MOBILE-FIRST NATIVE BOTTOM NAVIGATION BAR (FIXED TOUCH BAR) -->
        <nav class="svpl-mobile-bottom-nav d-md-none">
            <a href="<?= url('/customer/dashboard') ?>" class="mob-nav-item <?= $activeUri === url('/customer/dashboard') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Tracker</span>
            </a>
            <a href="<?= url('/customer/quotation') ?>" class="mob-nav-item <?= strpos($activeUri, '/customer/quotation') !== false ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-text"></i>
                <span>Proposal</span>
            </a>
            <a href="<?= url('/customer/documents') ?>" class="mob-nav-item <?= strpos($activeUri, '/customer/documents') !== false ? 'active' : '' ?>">
                <i class="bi bi-folder-check"></i>
                <span>KYC Locker</span>
            </a>
            <button type="button" class="mob-nav-item" data-bs-toggle="offcanvas" data-bs-target="#customerMobileDrawer" aria-label="Open Menu">
                <i class="bi bi-grid-fill"></i>
                <span>Menu</span>
            </button>
        </nav>
    </div>

    <!-- PM Surya Ghar Agreement Modal Window -->
    <?php include __DIR__ . '/../customer/partials/agreement_modal.php'; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
    <script src="<?= asset('assets/js/image-compressor.js') ?>"></script>
</body>
</html>
