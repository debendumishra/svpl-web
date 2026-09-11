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
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <meta name="base-url" content="<?= base_path_url() ?>">
    
    <!-- Solar Theme CSS -->
    <link rel="stylesheet" href="<?= asset('assets/css/solar-theme.css') ?>">
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
                        <span class="text-white"><i class="bi bi-patch-check-fill text-warning me-1"></i> PM Surya Ghar: Muft Bijli Yojana Odisha</span>
                        <span class="text-success fw-bold d-none d-md-inline"><i class="bi bi-gift-fill me-1"></i> ₹1,38,000 Combined Central & Odisha State Subsidy</span>
                    </div>
                    <div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.7rem;">DISCOM Verified</span>
                    </div>
                </div>
            </div>

            <!-- Customer Header Strip -->
            <div class="px-2 px-md-3 py-2 d-flex justify-content-between align-items-center border-bottom bg-white">
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <!-- Mobile Hamburger Button -->
                    <button class="btn btn-light border btn-sm d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#customerMobileDrawer" aria-label="Open Mobile Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <div style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 900;">
                        ☀
                    </div>
                    <div>
                        <div class="fw-bold font-heading text-navy" style="font-size: 0.95rem; line-height: 1.2;">
                            <?= htmlspecialchars($customerName) ?>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.65rem;"><?= htmlspecialchars($customerCode) ?></span>
                            <span class="badge bg-primary-subtle text-primary" style="font-size: 0.62rem;">Dhwajja 3kW</span>
                        </div>
                    </div>
                </div>

                <!-- Desktop / Tablet Navigation Strip -->
                <div class="d-none d-md-flex align-items-center gap-2">
                    <a href="<?= url('/customer/dashboard') ?>" class="btn btn-sm <?= $activeUri === url('/customer/dashboard') ? 'btn-primary' : 'btn-light border' ?>">
                        <i class="bi bi-speedometer2 me-1"></i> Status Tracker
                    </a>
                    <a href="<?= url('/customer/quotation') ?>" class="btn btn-sm <?= strpos($activeUri, '/customer/quotation') !== false ? 'btn-primary' : 'btn-light border' ?>">
                        <i class="bi bi-file-earmark-text me-1"></i> Proposal
                    </a>
                    <a href="<?= url('/customer/documents') ?>" class="btn btn-sm <?= strpos($activeUri, '/customer/documents') !== false ? 'btn-primary' : 'btn-light border' ?>">
                        <i class="bi bi-folder-check me-1"></i> KYC Locker
                    </a>
                    <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm" title="Sign Out">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>

                <!-- Mobile Header Right Actions -->
                <div class="d-flex d-md-none align-items-center gap-1">
                    <a href="tel:18008897875" class="btn btn-outline-success btn-sm" title="Helpline">
                        <i class="bi bi-telephone-fill"></i>
                    </a>
                    <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm" title="Sign Out">
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

        <!-- INDEPENDENTLY SCROLLABLE MAIN CONTENT AREA -->
        <main class="app-main-content">
            <div class="container-fluid max-w-7xl">
                <?= $content ?>
            </div>
        </main>

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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
    <script src="<?= asset('assets/js/image-compressor.js') ?>"></script>
</body>
</html>
