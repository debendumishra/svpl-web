<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Executive Admin Command Center Layout (Fixed Layout, Constant Header/Footer & Expandable Sidebar)
 */
$userRole = $_SESSION['user_role'] ?? 'SUPER_ADMIN';
$userName = $_SESSION['user_name'] ?? 'Debendu Mishra';
$activeUri = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Executive Admin Command Center | Surya Vistaara') ?></title>
    
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
            <!-- Top Status Live Ticker Bar (Desktop) -->
            <div class="live-ticker-bar px-3 d-none d-lg-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-white"><i class="bi bi-patch-check-fill text-warning me-1"></i> PM Surya Ghar Odisha Hub</span>
                        <span class="text-white"><i class="bi bi-gift-fill text-success me-1"></i> Central: <strong>₹78,000</strong></span>
                        <span class="text-white"><i class="bi bi-building-check text-warning me-1"></i> Odisha State: <strong>₹60,000</strong></span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold">Combined: ₹1,38,000 DBT</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 small">
                        <span class="badge bg-dark text-warning border border-warning" style="font-size: 0.68rem;">TPCODL | TPNODL | TPSODL | TPWODL</span>
                    </div>
                </div>
            </div>

            <!-- Primary App Bar with Mobile & Desktop Toggles -->
            <div class="px-2 px-md-3 py-2 d-flex justify-content-between align-items-center border-bottom bg-white">
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <!-- Mobile Hamburger Button (triggers Offcanvas) -->
                    <button class="btn btn-light border btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminMobileDrawer" aria-label="Open Mobile Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <!-- Desktop Sidebar Toggle Button -->
                    <button class="btn btn-light border btn-sm d-none d-lg-inline-block" id="sidebarToggleBtn" title="Toggle Sidebar Expand/Collapse">
                        <i class="bi bi-layout-sidebar-inset fs-5"></i>
                    </button>

                    <a href="<?= url('/admin/dashboard') ?>" class="d-flex align-items-center gap-2 text-decoration-none">
                        <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900; font-size: 1rem;">
                            ☀
                        </div>
                        <div>
                            <span class="font-heading fw-bold text-navy" style="font-size: 1.05rem; letter-spacing: -0.3px;">SURYA VISTAARA</span>
                            <span class="badge bg-warning text-dark ms-1 fw-bold d-none d-sm-inline-block" style="font-size: 0.65rem;">ADMIN</span>
                        </div>
                    </a>
                </div>

                <div class="d-flex align-items-center gap-1 gap-md-2">
                    <a href="<?= url('/register-customer') ?>" class="btn btn-svpl-solar btn-sm shadow-sm d-none d-sm-inline-flex">
                        <i class="bi bi-plus-circle-fill me-1"></i> + New Lead
                    </a>
                    <a href="<?= url('/') ?>" target="_blank" class="btn btn-light border btn-sm" title="Public Home">
                        <i class="bi bi-globe"></i>
                    </a>
                    <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm" title="Sign Out">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>
            </div>
        </header>

        <!-- MOBILE OFFCANVAS DRAWER MENU (BOOTSTRAP 5) -->
        <div class="offcanvas offcanvas-start offcanvas-svpl" tabindex="-1" id="adminMobileDrawer" aria-labelledby="adminMobileDrawerLabel">
            <div class="offcanvas-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900;">
                        ☀
                    </div>
                    <div>
                        <h6 class="offcanvas-title font-heading fw-bold text-white mb-0" id="adminMobileDrawerLabel">SURYA VISTAARA</h6>
                        <small class="text-warning fw-semibold" style="font-size: 0.7rem;">Executive Command</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            
            <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
                <div class="py-2">
                    <!-- Core Command Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#mobMenuCore" aria-expanded="true">
                        <span><i class="bi bi-cpu-fill text-warning me-1"></i> Core Command</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="mobMenuCore">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/dashboard') !== false ? 'active' : '' ?>" href="<?= url('/admin/dashboard') ?>">
                                    <i class="bi bi-speedometer2"></i> <span>Executive Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/leads') !== false ? 'active' : '' ?>" href="<?= url('/admin/leads') ?>">
                                    <i class="bi bi-kanban"></i> <span>10-Stage Pipeline</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Network & Citizens Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#mobMenuNetwork" aria-expanded="true">
                        <span><i class="bi bi-diagram-3-fill text-warning me-1"></i> Network & Citizens</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="mobMenuNetwork">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/advisors') !== false ? 'active' : '' ?>" href="<?= url('/admin/advisors') ?>">
                                    <i class="bi bi-person-badge"></i> <span>Advisor Registry</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/network-tree') !== false ? 'active' : '' ?>" href="<?= url('/admin/network-tree') ?>">
                                    <i class="bi bi-bezier2"></i> <span>9-Level Tree Visualizer</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/customers') !== false ? 'active' : '' ?>" href="<?= url('/admin/customers') ?>">
                                    <i class="bi bi-people"></i> <span>Customer Registry</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Operations & Supply Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#mobMenuOperations" aria-expanded="true">
                        <span><i class="bi bi-truck text-warning me-1"></i> Operations & Supply</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="mobMenuOperations">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/dispatches') !== false ? 'active' : '' ?>" href="<?= url('/admin/dispatches') ?>">
                                    <i class="bi bi-box-seam"></i> <span>Dispatches & Kits</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/commissions') !== false ? 'active' : '' ?>" href="<?= url('/admin/commissions') ?>">
                                    <i class="bi bi-cash-stack"></i> <span>9-Level Commissions</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($activeUri, '/admin/ledger') !== false || strpos($activeUri, '/manager/ledger') !== false) ? 'active' : '' ?>" href="<?= url('/admin/ledger') ?>">
                                    <i class="bi bi-journal-bookmark-fill"></i> <span>Account Books & Ledger</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/payments') !== false ? 'active' : '' ?>" href="<?= url('/admin/payments') ?>">
                                    <i class="bi bi-receipt"></i> <span>Payments & Invoices</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/withdrawals') !== false ? 'active' : '' ?>" href="<?= url('/admin/withdrawals') ?>">
                                    <i class="bi bi-bank text-success"></i> <span>Bank Payouts & Withdrawals</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Governance & Security Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#mobMenuGovernance" aria-expanded="true">
                        <span><i class="bi bi-shield-check text-warning me-1"></i> Governance</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="mobMenuGovernance">
                        <ul class="nav flex-column mb-2">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/reports') !== false ? 'active' : '' ?>" href="<?= url('/admin/reports') ?>">
                                    <i class="bi bi-graph-up-arrow"></i> <span>MIS Reports</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/settings') !== false ? 'active' : '' ?>" href="<?= url('/admin/settings') ?>">
                                    <i class="bi bi-sliders"></i> <span>System Settings</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/audit-logs') !== false ? 'active' : '' ?>" href="<?= url('/admin/audit-logs') ?>">
                                    <i class="bi bi-shield-lock"></i> <span>Audit & Logs</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- User Profile & Sign Out in Drawer -->
                <div class="p-3 border-top border-secondary bg-dark bg-opacity-50">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 34px; height: 34px; border-radius: 50%; background: #0B2545; border: 2px solid var(--svpl-gold); color: #FFF; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem;">
                                <?= strtoupper(substr($userName, 0, 2)) ?>
                            </div>
                            <div>
                                <div class="text-white fw-bold small text-truncate" style="max-width: 140px;"><?= htmlspecialchars($userName) ?></div>
                                <div class="text-success small" style="font-size: 0.68rem;"><i class="bi bi-circle-fill text-success" style="font-size: 0.45rem;"></i> Super Admin</div>
                            </div>
                        </div>
                        <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm" title="Logout">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- APP BODY CONTAINER (DESKTOP SIDEBAR + INDEPENDENT SCROLL) -->
        <div class="app-body-container">
            
            <!-- DESKTOP EXPANDABLE PORTAL SIDEBAR -->
            <aside class="portal-sidebar d-none d-lg-flex" id="appSidebar">
                <div class="py-2 flex-grow-1">
                    
                    <!-- Core Command Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#menuCore">
                        <span><i class="bi bi-cpu-fill text-warning me-1"></i> Core Command</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="menuCore">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/dashboard') !== false ? 'active' : '' ?>" href="<?= url('/admin/dashboard') ?>">
                                    <i class="bi bi-speedometer2"></i> <span class="sidebar-text">Executive Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/leads') !== false ? 'active' : '' ?>" href="<?= url('/admin/leads') ?>">
                                    <i class="bi bi-kanban"></i> <span class="sidebar-text">10-Stage Pipeline</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Network & Citizens Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#menuNetwork">
                        <span><i class="bi bi-diagram-3-fill text-warning me-1"></i> Network & Citizens</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="menuNetwork">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/advisors') !== false ? 'active' : '' ?>" href="<?= url('/admin/advisors') ?>">
                                    <i class="bi bi-person-badge"></i> <span class="sidebar-text">Advisor Registry</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/network-tree') !== false ? 'active' : '' ?>" href="<?= url('/admin/network-tree') ?>">
                                    <i class="bi bi-bezier2"></i> <span class="sidebar-text">9-Level Tree Visualizer</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/customers') !== false ? 'active' : '' ?>" href="<?= url('/admin/customers') ?>">
                                    <i class="bi bi-people"></i> <span class="sidebar-text">Customer Registry</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Operations & Supply Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#menuOperations">
                        <span><i class="bi bi-truck text-warning me-1"></i> Operations & Supply</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="menuOperations">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/dispatches') !== false ? 'active' : '' ?>" href="<?= url('/admin/dispatches') ?>">
                                    <i class="bi bi-box-seam"></i> <span class="sidebar-text">Dispatches & Kits</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/commissions') !== false ? 'active' : '' ?>" href="<?= url('/admin/commissions') ?>">
                                    <i class="bi bi-cash-stack"></i> <span class="sidebar-text">9-Level Commissions</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($activeUri, '/admin/ledger') !== false || strpos($activeUri, '/manager/ledger') !== false) ? 'active' : '' ?>" href="<?= url('/admin/ledger') ?>">
                                    <i class="bi bi-journal-bookmark-fill"></i> <span class="sidebar-text">Account Books & Ledger</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/payments') !== false ? 'active' : '' ?>" href="<?= url('/admin/payments') ?>">
                                    <i class="bi bi-receipt"></i> <span class="sidebar-text">Payments & Invoices</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/withdrawals') !== false ? 'active' : '' ?>" href="<?= url('/admin/withdrawals') ?>">
                                    <i class="bi bi-bank text-success"></i> <span class="sidebar-text">Bank Payouts & Withdrawals</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Governance & Security Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#menuGovernance">
                        <span><i class="bi bi-shield-check text-warning me-1"></i> Governance</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="menuGovernance">
                        <ul class="nav flex-column mb-2">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/boe') !== false ? 'active' : '' ?>" href="<?= url('/admin/boe') ?>">
                                    <i class="bi bi-person-badge-fill text-warning"></i> <span class="sidebar-text">BOE Staff Management</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/reports') !== false ? 'active' : '' ?>" href="<?= url('/admin/reports') ?>">
                                    <i class="bi bi-graph-up-arrow"></i> <span class="sidebar-text">MIS Reports</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/boe/reports') !== false ? 'active' : '' ?>" href="<?= url('/admin/boe/reports') ?>">
                                    <i class="bi bi-file-earmark-bar-graph"></i> <span class="sidebar-text">BOE Performance Reports</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/settings') !== false ? 'active' : '' ?>" href="<?= url('/admin/settings') ?>">
                                    <i class="bi bi-sliders"></i> <span class="sidebar-text">System Settings</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/database') !== false ? 'active' : '' ?>" href="<?= url('/admin/database') ?>">
                                    <i class="bi bi-database-fill-gear text-warning"></i> <span class="sidebar-text">Database & Backup</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/audit-logs') !== false ? 'active' : '' ?>" href="<?= url('/admin/audit-logs') ?>">
                                    <i class="bi bi-shield-lock"></i> <span class="sidebar-text">Audit & Logs</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

                <!-- User Profile Tile at bottom of sidebar -->
                <div class="user-profile-tile d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: #0B2545; border: 2px solid var(--svpl-gold); color: #FFF; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">
                            <?= strtoupper(substr($userName, 0, 2)) ?>
                        </div>
                        <div class="user-info-text">
                            <div class="text-white fw-bold small text-truncate" style="max-width: 140px;"><?= htmlspecialchars($userName) ?></div>
                            <div class="text-success small" style="font-size: 0.7rem;"><i class="bi bi-circle-fill text-success" style="font-size: 0.45rem;"></i> Active Super Admin</div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- INDEPENDENTLY SCROLLABLE MAIN CONTENT AREA -->
            <main class="app-main-content">
                <?= $content ?>
            </main>
        </div>

        <!-- CONSTANT FOOTER (STICKY BOTTOM, DESKTOP ONLY) -->
        <footer class="app-footer text-muted d-none d-md-flex justify-content-between align-items-center">
            <div>© <?= date('Y') ?> <strong>Surya Vistaara Pvt. Ltd. (SVPL)</strong> — Authorized Corporate Promoter for Dhwajja Solar India.</div>
            <div>Bhubaneswar HQ | PM Surya Ghar Odisha Ecosystem</div>
        </footer>

        <!-- MOBILE-FIRST NATIVE BOTTOM NAVIGATION BAR (FIXED TOUCH BAR) -->
        <nav class="svpl-mobile-bottom-nav d-lg-none">
            <a href="<?= url('/admin/dashboard') ?>" class="mob-nav-item <?= strpos($activeUri, '/admin/dashboard') !== false ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?= url('/admin/leads') ?>" class="mob-nav-item <?= strpos($activeUri, '/admin/leads') !== false ? 'active' : '' ?>">
                <i class="bi bi-kanban"></i>
                <span>Pipeline</span>
            </a>
            <a href="<?= url('/admin/advisors') ?>" class="mob-nav-item <?= strpos($activeUri, '/admin/advisors') !== false ? 'active' : '' ?>">
                <i class="bi bi-person-badge"></i>
                <span>Advisors</span>
            </a>
            <a href="<?= url('/admin/network-tree') ?>" class="mob-nav-item <?= strpos($activeUri, '/admin/network-tree') !== false ? 'active' : '' ?>">
                <i class="bi bi-bezier2"></i>
                <span>9-Tree</span>
            </a>
            <button type="button" class="mob-nav-item" data-bs-toggle="offcanvas" data-bs-target="#adminMobileDrawer" aria-label="Open Full Menu">
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
