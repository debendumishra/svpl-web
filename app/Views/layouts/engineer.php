<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Solar Field Engineer Portal Layout — Mobile-First Responsive Design
 */

$userRole = $_SESSION['user_role'] ?? 'ENGINEER';
$userName = $_SESSION['user_name'] ?? 'Solar Engineer';
$engineerCode = $_SESSION['engineer_code'] ?? ($_SESSION['employee_code'] ?? 'SVPL-ENG');
$designation = $_SESSION['designation'] ?? 'Field Solar Engineer';

$currentUri = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Field Engineer Command — SVPL') ?></title>
    
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Dynamic Favicon -->
    <?php if ($favUrl = company_favicon_url()): ?>
        <link rel="icon" href="<?= htmlspecialchars($favUrl) ?>">
    <?php endif; ?>

    <meta name="base-url" content="<?= base_path_url() ?>">
    
    <!-- Bootstrap 5 CSS & Solar Theme with Safe Cache-Busting -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset_url('assets/css/solar-theme.css') ?>">
    
    <style>
        :root {
            --eng-primary: #D97706;
            --eng-dark: #B45309;
            --eng-navy: #0F172A;
            --eng-bg: #FFFBEB;
            --sidebar-width: 260px;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #1E293B;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Top Navbar & Header Guard */
        .top-navbar {
            background: #FFFFFF !important;
            border-bottom: 1px solid #E2E8F0 !important;
            min-height: 56px !important;
            height: 56px !important;
            max-height: 56px !important;
            padding: 0 16px !important;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            justify-content: space-between !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 1030 !important;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04) !important;
            box-sizing: border-box !important;
        }

        .app-header-left { display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; align-items: center !important; gap: 8px !important; min-width: 0 !important; flex: 1 1 auto !important; overflow: hidden !important; }
        .app-header-emblem { width: 36px !important; height: 36px !important; min-width: 36px !important; border-radius: 9px !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; font-weight: 800 !important; font-size: 1.1rem !important; flex-shrink: 0 !important; }
        .app-header-title-wrap { display: flex !important; flex-direction: column !important; justify-content: center !important; min-width: 0 !important; overflow: hidden !important; line-height: 1.2 !important; }
        .app-header-title-row { display: flex !important; align-items: center !important; gap: 6px !important; overflow: hidden !important; white-space: nowrap !important; }
        .app-header-title { font-family: 'Outfit', sans-serif !important; font-weight: 700 !important; font-size: 0.92rem !important; color: #0F172A !important; white-space: nowrap !important; overflow: hidden !important; text-overflow: ellipsis !important; margin: 0 !important; }
        .app-header-subtitle { font-size: 0.7rem !important; color: #64748B !important; white-space: nowrap !important; overflow: hidden !important; text-overflow: ellipsis !important; display: flex !important; align-items: center !important; gap: 4px !important; margin-top: 1px !important; }
        .app-header-btn { width: 36px; height: 36px; min-width: 36px; max-width: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 0.92rem; font-weight: 600; flex-shrink: 0; text-decoration: none; border: 1px solid #E2E8F0; background: #F8FAFC; color: #334155; }
        .app-header-btn.d-none { display: none !important; }
        @media (min-width: 992px) {
            .app-header-btn.d-lg-none { display: none !important; }
            .app-header-btn.d-none.d-lg-inline-flex,
            .app-header-btn.d-lg-inline-flex { display: inline-flex !important; }
        }
        @media (max-width: 991.98px) {
            .app-header-btn.d-none.d-lg-inline-flex { display: none !important; }
            .app-header-btn.d-lg-none { display: inline-flex !important; }
        }
        .app-header-logout { border: 1px solid #FCA5A5 !important; background-color: #FEF2F2 !important; color: #DC2626 !important; }
        .app-header-logout:hover { background-color: #DC2626 !important; color: #FFFFFF !important; border-color: #DC2626 !important; }

        .font-heading {
            font-family: 'Outfit', 'Inter', sans-serif;
        }
        .text-navy {
            color: #0F2D59 !important;
        }
        .bg-navy {
            background-color: #0F2D59 !important;
            color: #FFFFFF !important;
        }
        .btn-navy {
            background-color: #0F2D59;
            color: #FFFFFF;
            border-color: #0F2D59;
        }
        .btn-navy:hover {
            background-color: #0B2545;
            color: #FFFFFF;
        }
        .btn-outline-navy {
            color: #0F2D59;
            border-color: #0F2D59;
        }
        .btn-outline-navy:hover {
            background-color: #0F2D59;
            color: #FFFFFF;
        }
        .btn-svpl-solar {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: #061528 !important;
            font-weight: 700;
            border: none;
        }
        .btn-svpl-solar:hover {
            background: linear-gradient(135deg, #D97706 0%, #B45309 100%);
            color: #FFFFFF !important;
        }
        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        }
        .transition {
            transition: all 0.2s ease-in-out;
        }
        @media (min-width: 992px) {
            .border-end-lg {
                border-right: 1px solid #E2E8F0 !important;
            }
        }
        @media (min-width: 768px) {
            .border-end-md {
                border-right: 1px solid #E2E8F0 !important;
            }
        }

        /* Desktop Sidebar */
        .eng-sidebar {
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
        .eng-sidebar .brand-header {
            padding: 1.25rem 1.5rem;
            background: #D97706;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .eng-sidebar .nav-link {
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
        .eng-sidebar .nav-link:hover, 
        .eng-sidebar .nav-link.active {
            color: #FFFFFF;
            background: rgba(245, 158, 11, 0.1);
            border-left-color: #F59E0B;
        }
        .eng-sidebar .nav-link i {
            font-size: 1.15rem;
            color: #F59E0B;
        }

        /* Main Content */
        .eng-content {
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            min-height: calc(100vh - 56px);
            transition: margin-left 0.3s ease;
        }

        /* Responsive Mobile Layout */
        @media (max-width: 991.98px) {
            .eng-sidebar {
                display: none !important;
            }
            .top-navbar {
                margin-left: 0 !important;
                padding: 0.5rem 0.75rem !important;
                width: 100% !important;
            }
            .eng-content {
                margin-left: 0 !important;
                padding: 0.75rem 0.5rem 85px 0.5rem !important;
                width: 100% !important;
                overflow-x: hidden !important;
            }
        }

        /* Mobile Bottom Nav */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #0F172A;
            border-top: 1px solid #334155;
            display: flex;
            justify-content: space-around;
            padding: 0.5rem 0;
            z-index: 1030;
        }
        .mobile-bottom-nav a {
            color: #94A3B8;
            font-size: 0.72rem;
            text-align: center;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
        }
        .mobile-bottom-nav a.active, .mobile-bottom-nav a:hover {
            color: #F59E0B;
        }
        .mobile-bottom-nav i {
            font-size: 1.25rem;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR FOR DESKTOP -->
    <aside class="eng-sidebar d-none d-lg-flex flex-column justify-content-between">
        <div>
            <div class="brand-header d-flex align-items-center gap-2">
                <?php if ($logoUrl = company_logo_url()): ?>
                    <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo" style="max-height: 38px; width: auto;">
                <?php else: ?>
                    <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900;">
                        ⚡
                    </div>
                    <div>
                        <div class="fw-bold text-white font-heading" style="font-size: 1rem;"><?= htmlspecialchars(company_short_name()) ?></div>
                        <span class="badge bg-warning text-dark fw-bold px-2 py-0" style="font-size: 0.65rem;">ENGINEER PORTAL</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="px-3 py-3 border-bottom border-secondary border-opacity-25">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar-circle bg-warning text-dark fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 38px; height: 38px;">
                        <?= strtoupper(substr($userName, 0, 2)) ?>
                    </div>
                    <div class="overflow-hidden">
                        <div class="fw-bold text-white text-truncate small"><?= htmlspecialchars($userName) ?></div>
                        <div class="text-warning small font-monospace" style="font-size: 0.72rem;"><?= htmlspecialchars($engineerCode) ?></div>
                    </div>
                </div>
            </div>

            <nav class="nav flex-column mt-2">
                <div class="px-3 py-1 text-secondary text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Field Operations</div>
                <a class="nav-link <?= strpos($currentUri, '/engineer/dashboard') !== false ? 'active' : '' ?>" href="<?= url('/engineer/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Field Dashboard
                </a>
                <a class="nav-link <?= strpos($currentUri, '/engineer/installations') !== false ? 'active' : '' ?>" href="<?= url('/engineer/installations') ?>">
                    <i class="bi bi-tools"></i> Solar Installations
                </a>
                
                <div class="px-3 py-1 mt-3 text-secondary text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Resources & ID</div>
                <a class="nav-link <?= strpos($currentUri, '/operations-guide') !== false ? 'active' : '' ?>" href="<?= url('/operations-guide') ?>" target="_blank">
                    <i class="bi bi-play-circle-fill"></i> Operations Guide
                </a>
                <a class="nav-link <?= strpos($currentUri, '/manual') !== false ? 'active' : '' ?>" href="<?= url('/manual') ?>" target="_blank">
                    <i class="bi bi-book-half"></i> Technical SOP (PDF)
                </a>
                <a class="nav-link <?= strpos($currentUri, '/engineer/profile') !== false ? 'active' : '' ?>" href="<?= url('/engineer/profile') ?>">
                    <i class="bi bi-person-badge"></i> Profile & Password
                </a>
                <a class="nav-link <?= strpos($currentUri, '/engineer/id-card') !== false ? 'active' : '' ?>" href="<?= url('/engineer/id-card') ?>" target="_blank">
                    <i class="bi bi-person-vcard-fill"></i> Digital ID Card
                </a>
            </nav>
        </div>

        <div class="p-3 border-top border-secondary border-opacity-25">
            <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-box-arrow-right"></i> Sign Out
            </a>
        </div>
    </aside>

    <!-- TOP NAVBAR -->
    <header class="top-navbar shadow-sm sticky-top">
        <div class="app-header-left">
            <button class="btn btn-light border btn-sm app-header-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileEngineerDrawer" aria-label="Toggle Navigation">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="d-flex align-items-center gap-2 min-w-0 overflow-hidden">
                <div class="app-header-emblem" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: #061528;">
                    ⚡
                </div>
                <div class="app-header-title-wrap">
                    <span class="app-header-title"><?= htmlspecialchars($pageTitle ?? 'Field Engineer Desk') ?></span>
                    <span class="app-header-subtitle">
                        <span class="badge bg-warning-subtle text-dark border border-warning-subtle font-monospace px-1" style="font-size: 0.65rem;"><?= htmlspecialchars($engineerCode) ?></span>
                        <span class="d-none d-sm-inline text-muted">• <?= htmlspecialchars($designation ?? 'Field Engineer') ?></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="app-header-right">
            <div class="dropdown d-none d-md-inline-block">
                <button class="btn btn-light border btn-sm dropdown-toggle fw-semibold text-truncate d-flex align-items-center gap-1" style="height: 36px; max-width: 170px;" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle text-primary"></i> <span class="text-truncate"><?= htmlspecialchars($userName) ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><h6 class="dropdown-header"><?= htmlspecialchars($designation) ?></h6></li>
                    <li><a class="dropdown-item" href="<?= url('/engineer/profile') ?>"><i class="bi bi-person me-2"></i> My Profile</a></li>
                    <li><a class="dropdown-item" href="<?= url('/engineer/id-card') ?>" target="_blank"><i class="bi bi-person-vcard me-2"></i> ID Card</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= url('/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Sign Out</a></li>
                </ul>
            </div>
            <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm app-header-btn" title="Sign Out">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </header>

    <!-- MOBILE DRAWER -->
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="mobileEngineerDrawer">
        <div class="offcanvas-header border-bottom border-secondary">
            <h6 class="offcanvas-title fw-bold text-warning d-flex align-items-center gap-2">
                <i class="bi bi-sun-fill text-warning"></i> <?= htmlspecialchars(company_short_name()) ?> Engineer
            </h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="p-3 border-bottom border-secondary border-opacity-50">
                <div class="fw-bold text-white"><?= htmlspecialchars($userName) ?></div>
                <div class="small text-warning font-monospace"><?= htmlspecialchars($engineerCode) ?> • <?= htmlspecialchars($designation) ?></div>
            </div>
            <nav class="nav flex-column py-2">
                <a class="nav-link text-white py-2 px-3" href="<?= url('/engineer/dashboard') ?>"><i class="bi bi-speedometer2 text-warning me-2"></i> Dashboard</a>
                <a class="nav-link text-white py-2 px-3" href="<?= url('/engineer/installations') ?>"><i class="bi bi-tools text-warning me-2"></i> Solar Installations</a>
                <a class="nav-link text-white py-2 px-3" href="<?= url('/operations-guide') ?>" target="_blank"><i class="bi bi-play-circle-fill text-warning me-2"></i> Operations Guide</a>
                <a class="nav-link text-white py-2 px-3" href="<?= url('/manual') ?>" target="_blank"><i class="bi bi-book-half text-warning me-2"></i> Technical Manual</a>
                <a class="nav-link text-white py-2 px-3" href="<?= url('/engineer/profile') ?>"><i class="bi bi-person-badge text-warning me-2"></i> Profile & Password</a>
                <a class="nav-link text-white py-2 px-3" href="<?= url('/engineer/id-card') ?>" target="_blank"><i class="bi bi-person-vcard-fill text-warning me-2"></i> Digital ID Card</a>
                <div class="p-3 mt-4">
                    <a href="<?= url('/logout') ?>" class="btn btn-danger btn-sm w-100">Sign Out</a>
                </div>
            </nav>
        </div>
    </div>

    <!-- MAIN CONTENT INJECTION -->
    <main class="eng-content">
        <?= $content ?>
    </main>

    <!-- MOBILE BOTTOM NAVIGATION -->
    <nav class="mobile-bottom-nav d-lg-none">
        <a href="<?= url('/engineer/dashboard') ?>" class="<?= strpos($currentUri, '/engineer/dashboard') !== false ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="<?= url('/engineer/installations') ?>" class="<?= strpos($currentUri, '/engineer/installations') !== false ? 'active' : '' ?>">
            <i class="bi bi-tools"></i>
            <span>Installations</span>
        </a>
        <a href="<?= url('/operations-guide') ?>" target="_blank">
            <i class="bi bi-play-circle-fill"></i>
            <span>Guide</span>
        </a>
        <a href="<?= url('/engineer/profile') ?>" class="<?= strpos($currentUri, '/engineer/profile') !== false ? 'active' : '' ?>">
            <i class="bi bi-person-circle"></i>
            <span>Profile</span>
        </a>
    </nav>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
