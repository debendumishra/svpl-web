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
            <!-- Top Status Live Ticker Bar -->
            <div class="live-ticker-bar px-3 d-none d-md-block">
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

            <!-- Primary App Bar with Sidebar Toggle -->
            <div class="px-3 py-2 d-flex justify-content-between align-items-center border-bottom bg-white">
                <div class="d-flex align-items-center gap-3">
                    <!-- Expand/Collapse Sidebar Toggle Button -->
                    <button class="btn btn-light border btn-sm" id="sidebarToggleBtn" title="Toggle Sidebar Expand/Collapse">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <div class="d-flex align-items-center gap-2">
                        <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900;">
                            ☀
                        </div>
                        <div>
                            <span class="font-heading fw-bold text-navy" style="font-size: 1.05rem;">SURYA VISTAARA</span>
                            <span class="badge bg-warning text-dark ms-1 fw-bold" style="font-size: 0.65rem;">ADMIN ENTERPRISE</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="<?= url('/register-customer') ?>" class="btn btn-svpl-solar btn-sm shadow-sm d-none d-sm-inline-block">
                        <i class="bi bi-plus-circle-fill me-1"></i> + New Lead
                    </a>
                    <a href="<?= url('/register-advisor') ?>" class="btn btn-outline-dark btn-sm d-none d-md-inline-block">
                        <i class="bi bi-person-plus-fill me-1"></i> Onboard Advisor
                    </a>
                    <a href="<?= url('/') ?>" target="_blank" class="btn btn-light border btn-sm" title="View Public Portal">
                        <i class="bi bi-globe"></i>
                    </a>
                    <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm" title="Sign Out">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>
            </div>
        </header>

        <!-- APP BODY CONTAINER (SIDEBAR & MAIN VIEW SCROLL INDEPENDENTLY) -->
        <div class="app-body-container">
            
            <!-- EXPANDABLE PORTAL SIDEBAR -->
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
                                <a class="nav-link <?= strpos($activeUri, '/admin/discom') !== false ? 'active' : '' ?>" href="<?= url('/admin/leads') ?>">
                                    <i class="bi bi-lightning-charge"></i> <span class="sidebar-text">DISCOM Net Metering</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Finance & Subsidies Accordion -->
                    <div class="nav-category-header" data-bs-toggle="collapse" data-bs-target="#menuFinance">
                        <span><i class="bi bi-wallet2 text-warning me-1"></i> Finance & Subsidies</span>
                        <i class="bi bi-chevron-down collapse-arrow"></i>
                    </div>
                    <div class="collapse show" id="menuFinance">
                        <ul class="nav flex-column mb-1">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/commissions') !== false ? 'active' : '' ?>" href="<?= url('/admin/commissions') ?>">
                                    <i class="bi bi-cash-stack"></i> <span class="sidebar-text">9-Level Commissions</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/payments') !== false ? 'active' : '' ?>" href="<?= url('/admin/payments') ?>">
                                    <i class="bi bi-receipt"></i> <span class="sidebar-text">Payments & Invoices</span>
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
                                <a class="nav-link <?= strpos($activeUri, '/admin/reports') !== false ? 'active' : '' ?>" href="<?= url('/admin/reports') ?>">
                                    <i class="bi bi-graph-up-arrow"></i> <span class="sidebar-text">MIS Reports</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($activeUri, '/admin/settings') !== false ? 'active' : '' ?>" href="<?= url('/admin/settings') ?>">
                                    <i class="bi bi-sliders"></i> <span class="sidebar-text">System Settings</span>
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

        <!-- CONSTANT FOOTER (STICKY BOTTOM) -->
        <footer class="app-footer text-muted d-flex justify-content-between align-items-center">
            <div>© <?= date('Y') ?> <strong>Surya Vistaara Pvt. Ltd. (SVPL)</strong> — Authorized Corporate Promoter for Dhwajja Solar India.</div>
            <div class="d-none d-md-block">Bhubaneswar HQ | PM Surya Ghar Odisha Ecosystem</div>
        </footer>
    </div>

    <!-- Scripts & Sidebar Toggle Logic -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebar = document.getElementById('appSidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });
        }
    });
    </script>
</body>
</html>
