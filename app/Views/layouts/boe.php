<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Back Office Executive (BOE) Portal Layout — Mobile-First Responsive Design
 */

$userRole = $_SESSION['user_role'] ?? 'BOE';
$userName = $_SESSION['user_name'] ?? 'BOE Staff';
$userCode = $_SESSION['employee_code'] ?? 'SVPL-BOE';
$userDesignation = $_SESSION['designation'] ?? 'Back Office Executive';

$currentUri = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($pageTitle ?? 'BOE Portal — SVPL') ?></title>
    
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Dynamic Favicon -->
    <?php if ($favUrl = company_favicon_url()): ?>
        <link rel="icon" href="<?= htmlspecialchars($favUrl) ?>">
    <?php endif; ?>

    <!-- Bootstrap 5 CSS & Solar Theme -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('assets/css/solar-theme.css') ?>">
    
    <style>
        :root {
            --boe-primary: #0284C7;
            --boe-dark: #0369A1;
            --boe-bg: #F0F9FF;
            --sidebar-width: 260px;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #1E293B;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Desktop Sidebar */
        .boe-sidebar {
            width: var(--sidebar-width);
            background: #0F172A;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            color: #F8FAFC;
            transition: all 0.3s ease;
        }
        .boe-sidebar .brand-header {
            padding: 1.25rem 1.5rem;
            background: #0284C7;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .boe-sidebar .nav-link {
            color: #94A3B8;
            padding: 0.85rem 1.5rem;
            font-weight: 500;
            font-size: 0.92rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }
        .boe-sidebar .nav-link:hover, 
        .boe-sidebar .nav-link.active {
            color: #FFFFFF;
            background: rgba(255,255,255,0.05);
            border-left-color: #38BDF8;
        }
        .boe-sidebar .nav-link i {
            font-size: 1.1rem;
        }

        /* Top Navbar */
        .top-navbar {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 0.85rem 2rem;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
        }

        /* Main Content */
        .boe-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: calc(100vh - 65px);
            transition: margin-left 0.3s ease;
        }

        .badge-boe {
            background: #E0F2FE;
            color: #0369A1;
            font-weight: 600;
            padding: 0.35em 0.7em;
            border-radius: 6px;
        }

        /* Mobile Offcanvas Drawer Customization */
        .offcanvas-boe {
            background: #0F172A;
            color: #F8FAFC;
            max-width: 280px;
        }
        .offcanvas-boe .offcanvas-header {
            background: #0284C7;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .offcanvas-boe .nav-link {
            color: #94A3B8;
            padding: 0.85rem 1.25rem;
            font-weight: 500;
            font-size: 0.92rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-left: 3px solid transparent;
        }
        .offcanvas-boe .nav-link.active,
        .offcanvas-boe .nav-link:hover {
            color: #FFFFFF;
            background: rgba(255,255,255,0.08);
            border-left-color: #38BDF8;
        }

        /* Mobile Bottom Touch Navigation Bar */
        .boe-mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: #FFFFFF;
            border-top: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            z-index: 1030;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }
        .boe-mobile-bottom-nav .mob-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #64748B;
            text-decoration: none;
            font-size: 0.72rem;
            font-weight: 600;
            flex: 1;
            padding: 4px 0;
            background: none;
            border: none;
        }
        .boe-mobile-bottom-nav .mob-nav-item i {
            font-size: 1.25rem;
            margin-bottom: 2px;
        }
        .boe-mobile-bottom-nav .mob-nav-item.active {
            color: #0284C7;
        }

        /* Mobile-First Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .boe-sidebar {
                display: none !important;
            }
            .top-navbar {
                margin-left: 0 !important;
                padding: 0.75rem 1rem !important;
            }
            .boe-content {
                margin-left: 0 !important;
                padding: 1rem 0.85rem 5rem 0.85rem !important;
            }
        }
        @media (min-width: 992px) {
            .boe-mobile-bottom-nav {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- DESKTOP SIDEBAR -->
    <aside class="boe-sidebar shadow d-none d-lg-block">
        <div class="brand-header d-flex align-items-center gap-2">
            <?php if ($logoUrl = company_logo_url()): ?>
                <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars(company_name()) ?>" style="max-height: 34px; width: auto; max-width: 140px; object-fit: contain;">
                <span class="badge bg-warning text-dark style-tag" style="font-size: 0.65rem; padding: 2px 6px;">BOE</span>
            <?php else: ?>
                <div style="background: #FFFFFF; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #0284C7; font-weight: 900; font-size: 1.1rem;">
                    ☀
                </div>
                <div>
                    <h6 class="m-0 text-white font-outfit fw-bold" style="letter-spacing: 0.5px;"><?= htmlspecialchars(company_short_name()) ?></h6>
                    <span class="badge bg-warning text-dark style-tag" style="font-size: 0.65rem; padding: 2px 6px;">BOE PORTAL</span>
                </div>
            <?php endif; ?>
        </div>

        <div class="px-3 py-3 border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-sky-100 text-sky-700 d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; background: #38BDF8; color: #0F172A;">
                    <?= strtoupper(substr($userName, 0, 2)) ?>
                </div>
                <div class="overflow-hidden">
                    <h6 class="mb-0 text-white small text-truncate"><?= htmlspecialchars($userName) ?></h6>
                    <span class="text-info small" style="font-size: 0.75rem;"><?= htmlspecialchars($userCode) ?></span>
                </div>
            </div>
        </div>

        <nav class="nav flex-column mt-2">
            <a class="nav-link <?= strpos($currentUri, '/boe/dashboard') !== false ? 'active' : '' ?>" href="<?= url('/boe/dashboard') ?>">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard Overview
            </a>
            <a class="nav-link <?= (strpos($currentUri, '/boe/customers') !== false && strpos($currentUri, 'tab=pool') !== false) || ($currentUri === url('/boe/customers')) ? 'active' : '' ?>" href="<?= url('/boe/customers?tab=pool') ?>">
                <i class="bi bi-inbox-fill text-warning"></i> Stage 1 Pool
            </a>
            <a class="nav-link <?= strpos($currentUri, 'tab=my') !== false ? 'active' : '' ?>" href="<?= url('/boe/customers?tab=my') ?>">
                <i class="bi bi-person-check-fill text-info"></i> My Claimed Leads
            </a>
            <a class="nav-link <?= strpos($currentUri, '/boe/reports') !== false ? 'active' : '' ?>" href="<?= url('/boe/reports') ?>">
                <i class="bi bi-file-earmark-bar-graph-fill"></i> Performance Reports
            </a>
            
            <?php if (in_array($userRole, ['ADMIN', 'SUPER_ADMIN'])): ?>
            <div class="px-3 mt-3 mb-1 text-uppercase text-secondary fw-bold" style="font-size: 0.7rem;">ADMIN TOOLS</div>
            <a class="nav-link" href="<?= url('/admin/boe') ?>">
                <i class="bi bi-shield-lock-fill text-warning"></i> Manage BOE Staff
            </a>
            <a class="nav-link" href="<?= url('/admin/dashboard') ?>">
                <i class="bi bi-speedometer"></i> Admin Portal
            </a>
            <?php endif; ?>

            <div class="mt-4 px-3">
                <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-right"></i> Sign Out
                </a>
            </div>
        </nav>
    </aside>

    <!-- MOBILE OFFCANVAS DRAWER MENU (BOOTSTRAP 5) -->
    <div class="offcanvas offcanvas-start offcanvas-boe" tabindex="-1" id="boeMobileDrawer" aria-labelledby="boeMobileDrawerLabel">
        <div class="offcanvas-header text-white d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <?php if ($logoUrl = company_logo_url()): ?>
                    <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars(company_name()) ?>" style="max-height: 30px; width: auto; max-width: 130px; object-fit: contain;">
                <?php else: ?>
                    <div style="background: #FFFFFF; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #0284C7; font-weight: 900; font-size: 1rem;">
                        ☀
                    </div>
                    <div>
                        <h6 class="offcanvas-title font-outfit fw-bold text-white mb-0" id="boeMobileDrawerLabel"><?= htmlspecialchars(company_short_name()) ?></h6>
                        <small class="badge bg-warning text-dark" style="font-size: 0.65rem;">BOE MOBILE</small>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
            <div class="py-2">
                <div class="px-3 py-3 border-bottom border-secondary border-opacity-25 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; background: #38BDF8; color: #0F172A;">
                            <?= strtoupper(substr($userName, 0, 2)) ?>
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="mb-0 text-white small text-truncate"><?= htmlspecialchars($userName) ?></h6>
                            <span class="text-info small" style="font-size: 0.75rem;"><?= htmlspecialchars($userCode) ?></span>
                        </div>
                    </div>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link <?= strpos($currentUri, '/boe/dashboard') !== false ? 'active' : '' ?>" href="<?= url('/boe/dashboard') ?>">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard Overview
                    </a>
                    <a class="nav-link <?= (strpos($currentUri, '/boe/customers') !== false && strpos($currentUri, 'tab=pool') !== false) ? 'active' : '' ?>" href="<?= url('/boe/customers?tab=pool') ?>">
                        <i class="bi bi-inbox-fill text-warning"></i> Stage 1 Shared Pool
                    </a>
                    <a class="nav-link <?= strpos($currentUri, 'tab=my') !== false ? 'active' : '' ?>" href="<?= url('/boe/customers?tab=my') ?>">
                        <i class="bi bi-person-check-fill text-info"></i> My Claimed Customers
                    </a>
                    <a class="nav-link <?= strpos($currentUri, 'tab=all') !== false ? 'active' : '' ?>" href="<?= url('/boe/customers?tab=all') ?>">
                        <i class="bi bi-collection-fill text-primary"></i> All Applications
                    </a>
                    <a class="nav-link <?= strpos($currentUri, '/boe/reports') !== false ? 'active' : '' ?>" href="<?= url('/boe/reports') ?>">
                        <i class="bi bi-file-earmark-bar-graph-fill"></i> Performance Reports
                    </a>

                    <?php if (in_array($userRole, ['ADMIN', 'SUPER_ADMIN'])): ?>
                    <div class="px-3 mt-3 mb-1 text-uppercase text-secondary fw-bold" style="font-size: 0.7rem;">ADMIN TOOLS</div>
                    <a class="nav-link" href="<?= url('/admin/boe') ?>">
                        <i class="bi bi-shield-lock-fill text-warning"></i> Manage BOE Staff
                    </a>
                    <a class="nav-link" href="<?= url('/admin/dashboard') ?>">
                        <i class="bi bi-speedometer"></i> Admin Portal
                    </a>
                    <?php endif; ?>
                </nav>
            </div>

            <div class="p-3 border-top border-secondary border-opacity-25">
                <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-right"></i> Sign Out
                </a>
            </div>
        </div>
    </div>

    <!-- TOP NAVBAR -->
    <header class="top-navbar d-flex justify-content-between align-items-center shadow-sm sticky-top">
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <!-- Mobile Hamburger Button -->
            <button class="btn btn-light border btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#boeMobileDrawer" aria-label="Toggle Navigation Menu">
                <i class="bi bi-list fs-5"></i>
            </button>

            <div class="d-flex align-items-center gap-2">
                <div class="d-lg-none" style="background: #0284C7; width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #FFF; font-size: 0.85rem; font-weight: bold;">
                    ☀
                </div>
                <h5 class="m-0 font-outfit fw-bold text-navy" style="font-size: 1.05rem;"><?= htmlspecialchars($pageTitle ?? 'BOE Workspace') ?></h5>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge-boe small"><i class="bi bi-badge-ad me-1"></i> <?= htmlspecialchars($userDesignation) ?></span>
            <span class="text-muted small d-none d-md-inline"><i class="bi bi-clock me-1"></i> <?= date('d M Y, h:i A') ?></span>
            <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm d-none d-sm-inline-flex" title="Sign Out">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="boe-content">
        <?= $content ?>
    </main>

    <!-- MOBILE-FIRST BOTTOM TOUCH NAVIGATION BAR -->
    <nav class="boe-mobile-bottom-nav d-lg-none">
        <a href="<?= url('/boe/dashboard') ?>" class="mob-nav-item <?= strpos($currentUri, '/boe/dashboard') !== false ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>
        <a href="<?= url('/boe/customers?tab=pool') ?>" class="mob-nav-item <?= (strpos($currentUri, '/boe/customers') !== false && strpos($currentUri, 'tab=pool') !== false) || ($currentUri === url('/boe/customers')) ? 'active' : '' ?>">
            <i class="bi bi-inbox-fill"></i>
            <span>Pool</span>
        </a>
        <a href="<?= url('/boe/customers?tab=my') ?>" class="mob-nav-item <?= strpos($currentUri, 'tab=my') !== false ? 'active' : '' ?>">
            <i class="bi bi-person-check-fill"></i>
            <span>My Leads</span>
        </a>
        <a href="<?= url('/boe/reports') ?>" class="mob-nav-item <?= strpos($currentUri, '/boe/reports') !== false ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-bar-graph-fill"></i>
            <span>Reports</span>
        </a>
        <button type="button" class="mob-nav-item" data-bs-toggle="offcanvas" data-bs-target="#boeMobileDrawer" aria-label="Open Full Menu">
            <i class="bi bi-grid-fill"></i>
            <span>Menu</span>
        </button>
    </nav>

    <!-- JS Scripts -->
    <script>window.SVPL_BASE_URL = '<?= rtrim(url(''), '/') ?>';</script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
    <script src="<?= asset('assets/js/image-compressor.js') ?>"></script>
    <script src="<?= asset('assets/js/photo-crop-studio.js') ?>"></script>
    <script src="<?= asset('assets/js/location-cascader.js') ?>"></script>
</body>
</html>

