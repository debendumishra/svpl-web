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
    
    <!-- Dynamic Favicon -->
    <?php if ($favUrl = company_favicon_url()): ?>
        <link rel="icon" href="<?= htmlspecialchars($favUrl) ?>">
    <?php endif; ?>

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <meta name="base-url" content="<?= base_path_url() ?>">
    
    <!-- Scripts (Loaded in Head for Inline View Support) -->
    <script>window.SVPL_BASE_URL = '<?= rtrim(url(''), '/') ?>';</script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Solar Theme & Network CSS with Safe Cache-Busting -->
    <link rel="stylesheet" href="<?= asset_url('assets/css/solar-theme.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('assets/css/network-tree.css') ?>">

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
            <!-- Top Status Live Ticker Bar (Desktop Only) -->
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
            <div class="app-header-strip">
                <div class="app-header-left">
                    <!-- Mobile Hamburger Button (triggers Offcanvas) -->
                    <button class="app-header-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminMobileDrawer" aria-label="Open Mobile Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <!-- Desktop Sidebar Toggle Button -->
                    <button class="app-header-btn d-none d-lg-inline-flex" id="sidebarToggleBtn" title="Toggle Sidebar Expand/Collapse">
                        <i class="bi bi-layout-sidebar-inset fs-5"></i>
                    </button>

                    <a href="<?= url('/admin/dashboard') ?>" class="d-flex align-items-center gap-2 text-decoration-none min-w-0 overflow-hidden">
                        <?php if ($logoUrl = company_logo_url()): ?>
                            <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars(company_name()) ?>" style="max-height: 32px; width: auto; max-width: 140px; object-fit: contain;">
                            <span class="badge bg-warning text-dark fw-bold d-none d-sm-inline-block" style="font-size: 0.65rem; letter-spacing: 0.05em;">ADMIN</span>
                        <?php else: ?>
                            <div class="app-header-emblem" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: #061528;">
                                ☀
                            </div>
                            <div class="app-header-title-wrap">
                                <div class="app-header-title-row">
                                    <span class="app-header-title"><?= htmlspecialchars(company_short_name()) ?></span>
                                    <span class="badge bg-warning text-dark fw-bold" style="font-size: 0.62rem; letter-spacing: 0.05em;">ADMIN</span>
                                </div>
                                <span class="app-header-subtitle">Executive Command Center</span>
                            </div>
                        <?php endif; ?>
                    </a>
                </div>

                <div class="app-header-right">
                    <a href="<?= url('/register-customer') ?>" class="btn btn-svpl-solar btn-sm shadow-sm d-none d-md-inline-flex align-items-center gap-1 px-3" style="height: 36px; font-size: 0.82rem;">
                        <i class="bi bi-plus-circle-fill"></i> <span>+ Lead</span>
                    </a>
                    <a href="<?= url('/') ?>" target="_blank" class="app-header-btn" title="View Public Website">
                        <i class="bi bi-globe text-primary"></i>
                    </a>
                    <a href="<?= url('/logout') ?>" class="app-header-btn app-header-logout" title="Sign Out">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>
            </div>
        </header>

        <!-- MOBILE OFFCANVAS DRAWER MENU (BOOTSTRAP 5) -->
        <div class="offcanvas offcanvas-start offcanvas-svpl" tabindex="-1" id="adminMobileDrawer" aria-labelledby="adminMobileDrawerLabel">
            <div class="offcanvas-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <?php if ($logoUrl = company_logo_url()): ?>
                        <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars(company_name()) ?>" style="max-height: 32px; width: auto; max-width: 140px; object-fit: contain;">
                    <?php else: ?>
                        <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900;">
                            ☀
                        </div>
                        <div>
                            <h6 class="offcanvas-title font-heading fw-bold text-white mb-0" id="adminMobileDrawerLabel"><?= htmlspecialchars(company_short_name()) ?></h6>
                            <small class="text-warning fw-semibold" style="font-size: 0.7rem;">Executive Command</small>
                        </div>
                    <?php endif; ?>
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
                                    <i class="bi bi-kanban"></i> <span>15-Point Pipeline</span>
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
                                <a class="nav-link <?= strpos($activeUri, '/admin/packages') !== false ? 'active' : '' ?>" href="<?= url('/admin/packages') ?>">
                                    <i class="bi bi-box-seam-fill text-warning"></i> <span>Solar Packages Desk</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($activeUri, '/admin/discoms') !== false || strpos($activeUri, '/manager/discoms') !== false) ? 'active' : '' ?>" href="<?= url('/admin/discoms') ?>">
                                    <i class="bi bi-lightning-charge-fill text-warning"></i> <span>DISCOM Providers</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($activeUri, '/admin/dispatches') !== false || strpos($activeUri, '/manager/dispatches') !== false) ? 'active' : '' ?>" href="<?= url('/admin/dispatches') ?>">
                                    <i class="bi bi-box-seam"></i> <span>Dispatches & Kits</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($activeUri, '/admin/instruments') !== false || strpos($activeUri, '/manager/instruments') !== false) ? 'active' : '' ?>" href="<?= url('/admin/instruments') ?>">
                                    <i class="bi bi-tools text-warning"></i> <span>Instruments & BOS Items</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($activeUri, '/admin/engineers') !== false || strpos($activeUri, '/manager/engineers') !== false) ? 'active' : '' ?>" href="<?= url('/admin/engineers') ?>">
                                    <i class="bi bi-person-badge-fill text-warning"></i> <span>Engineers Desk</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($activeUri, '/admin/boe') !== false || strpos($activeUri, '/manager/boe') !== false) ? 'active' : '' ?>" href="<?= url('/admin/boe-management') ?>">
                                    <i class="bi bi-people-fill text-info"></i> <span>BOE Staff Desk</span>
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
                                <a class="nav-link <?= strpos($activeUri, '/admin/id-cards') !== false ? 'active' : '' ?>" href="<?= url('/admin/id-cards') ?>">
                                    <i class="bi bi-person-vcard-fill text-warning"></i> <span>ID Card Generator</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= url('/operations-guide') ?>" target="_blank">
                                    <i class="bi bi-play-circle-fill text-warning"></i> <span>Video & Voice Guide</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= url('/manual') ?>" target="_blank">
                                    <i class="bi bi-book-half text-info"></i> <span>Operations Manual (PDF)</span>
                                </a>
                            </li>
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
            <aside class="portal-sidebar" id="appSidebar">
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
                                <i class="bi bi-kanban"></i> <span class="sidebar-text">15-Point Pipeline</span>
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
                                <a class="nav-link <?= strpos($activeUri, '/admin/packages') !== false ? 'active' : '' ?>" href="<?= url('/admin/packages') ?>">
                                    <i class="bi bi-box-seam-fill text-warning"></i> <span class="sidebar-text">Solar Packages Desk</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($activeUri, '/admin/discoms') !== false || strpos($activeUri, '/manager/discoms') !== false) ? 'active' : '' ?>" href="<?= url('/admin/discoms') ?>">
                                    <i class="bi bi-lightning-charge-fill text-warning"></i> <span class="sidebar-text">DISCOM Providers</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($activeUri, '/admin/dispatches') !== false || strpos($activeUri, '/manager/dispatches') !== false) ? 'active' : '' ?>" href="<?= url('/admin/dispatches') ?>">
                                    <i class="bi bi-box-seam"></i> <span class="sidebar-text">Dispatches & Kits</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($activeUri, '/admin/instruments') !== false || strpos($activeUri, '/manager/instruments') !== false) ? 'active' : '' ?>" href="<?= url('/admin/instruments') ?>">
                                    <i class="bi bi-tools text-warning"></i> <span class="sidebar-text">Instruments & BOS Items</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($activeUri, '/admin/engineers') !== false || strpos($activeUri, '/manager/engineers') !== false) ? 'active' : '' ?>" href="<?= url('/admin/engineers') ?>">
                                    <i class="bi bi-person-badge-fill text-warning"></i> <span class="sidebar-text">Engineers Desk</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($activeUri, '/admin/boe') !== false || strpos($activeUri, '/manager/boe') !== false) ? 'active' : '' ?>" href="<?= url('/admin/boe-management') ?>">
                                    <i class="bi bi-people-fill text-info"></i> <span class="sidebar-text">BOE Staff Desk</span>
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
                                <a class="nav-link <?= strpos($activeUri, '/admin/id-cards') !== false ? 'active' : '' ?>" href="<?= url('/admin/id-cards') ?>">
                                    <i class="bi bi-person-vcard-fill text-warning"></i> <span class="sidebar-text">ID Card Generator</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= url('/operations-guide') ?>" target="_blank">
                                    <i class="bi bi-play-circle-fill text-warning"></i> <span class="sidebar-text">Video & Voice Guide</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= url('/manual') ?>" target="_blank">
                                    <i class="bi bi-book-half text-info"></i> <span class="sidebar-text">Operations Manual (PDF)</span>
                                </a>
                            </li>
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
    <script src="<?= asset('assets/js/app.js') ?>"></script>
    <script src="<?= asset('assets/js/image-compressor.js') ?>"></script>
    <script src="<?= asset('assets/js/photo-crop-studio.js') ?>"></script>
    <script src="<?= asset('assets/js/location-cascader.js') ?>"></script>
</body>
</html>
