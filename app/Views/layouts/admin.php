<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Executive Admin Command Center Layout (Solar Luminary Design System)
 */
$userRole = $_SESSION['user_role'] ?? 'SUPER_ADMIN';
$userName = $_SESSION['user_name'] ?? 'Debendu Mishra';
$userEmail = $_SESSION['user_email'] ?? 'admin@suryavistaara.com';
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
<body style="background-color: #F8FAFC;">

    <div class="d-flex min-vh-100">
        <!-- REDESIGNED EXECUTIVE PORTAL SIDEBAR -->
        <aside class="portal-sidebar d-flex flex-column shadow-lg">
            <!-- Sidebar Brand Header -->
            <div class="p-3 border-bottom d-flex align-items-center gap-3" style="border-color: rgba(255,255,255,0.08) !important; background: rgba(0,0,0,0.2);">
                <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900; font-size: 1.25rem; box-shadow: 0 0 15px rgba(245, 158, 11, 0.4);">
                    ☀
                </div>
                <div>
                    <div class="text-white font-heading fw-bold" style="font-size: 1.05rem; letter-spacing: -0.3px;">SURYA VISTAARA</div>
                    <div class="d-flex align-items-center gap-1 mt-1">
                        <span class="badge bg-warning text-dark fw-bold" style="font-size: 0.65rem; letter-spacing: 0.05em;">ADMIN SUITE</span>
                        <span class="badge bg-success text-white" style="font-size: 0.62rem;">ODISHA LIVE</span>
                    </div>
                </div>
            </div>

            <!-- Scrollable Navigation Items -->
            <div class="py-2 flex-grow-1 overflow-y-auto custom-scrollbar">
                
                <!-- Category: CORE COMMAND -->
                <div class="nav-category-header"><i class="bi bi-cpu-fill text-warning me-1"></i> Core Command</div>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($activeUri, '/admin/dashboard') !== false ? 'active' : '' ?>" href="<?= url('/admin/dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> <span>Executive Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($activeUri, '/admin/leads') !== false ? 'active' : '' ?>" href="<?= url('/admin/leads') ?>">
                            <i class="bi bi-kanban"></i> <span>Lead Pipeline (10 Stages)</span>
                        </a>
                    </li>
                </ul>

                <!-- Category: NETWORK & CITIZENS -->
                <div class="nav-category-header"><i class="bi bi-diagram-3-fill text-warning me-1"></i> Network & Citizens</div>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($activeUri, '/admin/advisors') !== false ? 'active' : '' ?>" href="<?= url('/admin/advisors') ?>">
                            <i class="bi bi-person-badge"></i> <span>Advisor Network</span>
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

                <!-- Category: OPERATIONS & SUPPLY -->
                <div class="nav-category-header"><i class="bi bi-truck text-warning me-1"></i> Operations & Supply</div>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($activeUri, '/admin/dispatches') !== false ? 'active' : '' ?>" href="<?= url('/admin/dispatches') ?>">
                            <i class="bi bi-box-seam"></i> <span>Dispatches & Welcome Kits</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($activeUri, '/admin/discom') !== false ? 'active' : '' ?>" href="<?= url('/admin/leads') ?>">
                            <i class="bi bi-lightning-charge"></i> <span>DISCOM Net-Metering</span>
                        </a>
                    </li>
                </ul>

                <!-- Category: FINANCE & SUBSIDY -->
                <div class="nav-category-header"><i class="bi bi-wallet2 text-warning me-1"></i> Finance & Subsidies</div>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($activeUri, '/admin/commissions') !== false ? 'active' : '' ?>" href="<?= url('/admin/commissions') ?>">
                            <i class="bi bi-cash-stack"></i> <span>9-Level Commissions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($activeUri, '/admin/payments') !== false ? 'active' : '' ?>" href="<?= url('/admin/payments') ?>">
                            <i class="bi bi-receipt"></i> <span>Payments & Invoices</span>
                        </a>
                    </li>
                </ul>

                <!-- Category: GOVERNANCE & SECURITY -->
                <div class="nav-category-header"><i class="bi bi-shield-check text-warning me-1"></i> Governance</div>
                <ul class="nav flex-column mb-3">
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($activeUri, '/admin/reports') !== false ? 'active' : '' ?>" href="<?= url('/admin/reports') ?>">
                            <i class="bi bi-graph-up-arrow"></i> <span>MIS Reports & Analytics</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($activeUri, '/admin/settings') !== false ? 'active' : '' ?>" href="<?= url('/admin/settings') ?>">
                            <i class="bi bi-sliders"></i> <span>System Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($activeUri, '/admin/audit-logs') !== false ? 'active' : '' ?>" href="<?= url('/admin/audit-logs') ?>">
                            <i class="bi bi-shield-lock"></i> <span>Audit & Security Logs</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Bottom Super Admin User Profile Tile -->
            <div class="user-profile-tile d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #0B2545 0%, #133E6E 100%); border: 2px solid var(--svpl-gold); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: 0.9rem;">
                        <?= strtoupper(substr($userName, 0, 2)) ?>
                    </div>
                    <div>
                        <div class="text-white fw-bold small text-truncate" style="max-width: 130px;"><?= htmlspecialchars($userName) ?></div>
                        <div class="text-success small" style="font-size: 0.7rem;"><i class="bi bi-circle-fill text-success" style="font-size: 0.5rem;"></i> Online</div>
                    </div>
                </div>
                <a href="<?= url('/logout') ?>" class="btn btn-sm btn-outline-danger" title="Sign Out">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </aside>

        <!-- MAIN EXECUTIVE WORKSPACE CONTENT -->
        <div class="flex-grow-1 d-flex flex-column overflow-x-hidden">
            <!-- Top Executive App Bar with Live Ticker -->
            <header class="bg-white border-bottom sticky-top shadow-sm">
                <!-- Top Status Banner -->
                <div class="live-ticker-bar px-4 d-none d-md-block">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-4">
                            <span class="live-ticker-item"><i class="bi bi-patch-check-fill text-warning"></i> PM Surya Ghar Odisha Scheme Live</span>
                            <span class="live-ticker-item text-white"><i class="bi bi-lightning-charge-fill text-success"></i> Central Subsidy: <strong>₹78,000</strong></span>
                            <span class="live-ticker-item text-white"><i class="bi bi-building-check text-warning"></i> Odisha State Subsidy: <strong>₹60,000</strong></span>
                            <span class="live-ticker-item text-success fw-bold">Combined Subsidy Benefit: ₹1,38,000</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="badge bg-light text-dark border">TPCODL | TPNODL | TPSODL | TPWODL</span>
                        </div>
                    </div>
                </div>

                <!-- Primary Action Navbar -->
                <div class="px-4 py-2 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <h5 class="m-0 font-heading fw-bold text-dark" style="color: #0B2545 !important;">
                            <?= htmlspecialchars($pageTitle ?? 'Executive Command Center') ?>
                        </h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="<?= url('/register-customer') ?>" class="btn btn-svpl-solar btn-sm shadow-sm">
                            <i class="bi bi-plus-circle-fill me-1"></i> + New Solar Lead
                        </a>
                        <a href="<?= url('/register-advisor') ?>" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-person-plus-fill me-1"></i> Register Advisor
                        </a>
                        <a href="<?= url('/') ?>" target="_blank" class="btn btn-light border btn-sm" title="Public Portal">
                            <i class="bi bi-globe"></i>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Main Dynamic Page Content View -->
            <main class="p-4 flex-grow-1">
                <?= $content ?>
            </main>

            <!-- Executive Sub-Footer -->
            <footer class="bg-white border-top py-2 px-4 text-muted small d-flex justify-content-between align-items-center">
                <div>© <?= date('Y') ?> <strong>Surya Vistaara Pvt. Ltd. (SVPL)</strong> — Authorized Corporate Promoter for Dhwajja Solar India.</div>
                <div>Server Time: <?= date('d M Y, h:i A') ?> (IST)</div>
            </footer>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <div class="mobile-bottom-nav">
        <a href="<?= url('/admin/dashboard') ?>" class="<?= strpos($activeUri, '/admin/dashboard') !== false ? 'active' : '' ?>">
            <i class="bi bi-speedometer2 fs-5"></i>
            <span>Dashboard</span>
        </a>
        <a href="<?= url('/admin/leads') ?>" class="<?= strpos($activeUri, '/admin/leads') !== false ? 'active' : '' ?>">
            <i class="bi bi-kanban fs-5"></i>
            <span>Leads</span>
        </a>
        <a href="<?= url('/admin/advisors') ?>" class="<?= strpos($activeUri, '/admin/advisors') !== false ? 'active' : '' ?>">
            <i class="bi bi-person-badge fs-5"></i>
            <span>Advisors</span>
        </a>
        <a href="<?= url('/admin/commissions') ?>" class="<?= strpos($activeUri, '/admin/commissions') !== false ? 'active' : '' ?>">
            <i class="bi bi-cash-stack fs-5"></i>
            <span>Commissions</span>
        </a>
        <a href="<?= url('/logout') ?>">
            <i class="bi bi-box-arrow-right fs-5"></i>
            <span>Logout</span>
        </a>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
