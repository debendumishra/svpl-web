<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Dashboard & Operational Layout (XAMPP Ready)
 */
$userRole = $_SESSION['user_role'] ?? 'ADMIN';
$userName = $_SESSION['user_name'] ?? 'Administrator';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin Command Center | SVPL') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <meta name="base-url" content="<?= base_path_url() ?>">
    
    <link rel="stylesheet" href="<?= asset('assets/css/solar-theme.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/network-tree.css') ?>">
</head>
<body style="background-color: #F1F5F9;">

    <div class="d-flex">
        <!-- SIDEBAR -->
        <aside class="portal-sidebar d-flex flex-column">
            <div class="p-3 border-bottom border-secondary d-flex align-items-center gap-2">
                <div style="background: #F59E0B; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #0B2545; font-weight: 800;">
                    ☀
                </div>
                <div>
                    <div class="text-white fw-bold" style="font-size: 0.95rem;">SURYA VISTAARA</div>
                    <div class="badge bg-danger" style="font-size: 0.65rem;"><?= htmlspecialchars($userRole) ?></div>
                </div>
            </div>

            <div class="py-3 flex-grow-1 overflow-y-auto">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false ? 'active' : '' ?>" href="<?= url('/admin/dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/advisors') !== false ? 'active' : '' ?>" href="<?= url('/admin/advisors') ?>">
                            <i class="bi bi-person-badge"></i> Advisors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/customers') !== false ? 'active' : '' ?>" href="<?= url('/admin/customers') ?>">
                            <i class="bi bi-people"></i> Customers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/leads') !== false ? 'active' : '' ?>" href="<?= url('/admin/leads') ?>">
                            <i class="bi bi-kanban"></i> Solar Leads
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/network-tree') !== false ? 'active' : '' ?>" href="<?= url('/admin/network-tree') ?>">
                            <i class="bi bi-diagram-3"></i> 9-Level Tree
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/commissions') !== false ? 'active' : '' ?>" href="<?= url('/admin/commissions') ?>">
                            <i class="bi bi-cash-stack"></i> Commissions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/payments') !== false ? 'active' : '' ?>" href="<?= url('/admin/payments') ?>">
                            <i class="bi bi-receipt"></i> Payments & Receipts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/dispatches') !== false ? 'active' : '' ?>" href="<?= url('/admin/dispatches') ?>">
                            <i class="bi bi-box-seam"></i> Welcome Kits
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/reports') !== false ? 'active' : '' ?>" href="<?= url('/admin/reports') ?>">
                            <i class="bi bi-graph-up-arrow"></i> Reports & Analytics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/settings') !== false ? 'active' : '' ?>" href="<?= url('/admin/settings') ?>">
                            <i class="bi bi-sliders"></i> Admin Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/audit-logs') !== false ? 'active' : '' ?>" href="<?= url('/admin/audit-logs') ?>">
                            <i class="bi bi-shield-lock"></i> Audit Logs
                        </a>
                    </li>
                </ul>
            </div>

            <div class="p-3 border-top border-secondary">
                <div class="d-flex align-items-center justify-content-between text-white">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: 700;">
                            <?= strtoupper(substr($userName, 0, 1)) ?>
                        </div>
                        <div class="overflow-hidden" style="max-width: 120px;">
                            <div class="text-truncate fw-bold" style="font-size: 0.8rem;"><?= htmlspecialchars($userName) ?></div>
                            <div class="text-muted" style="font-size: 0.7rem;"><?= htmlspecialchars($userRole) ?></div>
                        </div>
                    </div>
                    <a href="<?= url('/logout') ?>" class="text-danger" title="Logout"><i class="bi bi-box-arrow-right fs-5"></i></a>
                </div>
            </div>
        </aside>

        <!-- MAIN VIEW WRAPPER -->
        <div class="flex-grow-1 d-flex flex-column min-vh-100 overflow-x-hidden">
            <!-- TOP HEADER -->
            <header class="bg-white border-bottom py-2 px-4 d-flex justify-content-between align-items-center shadow-sm sticky-top">
                <div class="d-flex align-items-center gap-2">
                    <a href="<?= url('/') ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                        <i class="bi bi-globe me-1"></i> Visit Website
                    </a>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> System Operational</span>
                    <span class="text-muted small"><?= date('d M Y, h:i A') ?></span>
                    <a href="<?= url('/logout') ?>" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </a>
                </div>
            </header>

            <!-- MAIN CONTENT -->
            <main class="p-4 flex-grow-1">
                <?= $content ?>
            </main>
        </div>
    </div>

    <!-- jQuery & Bootstrap 5 Bundle JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
    <script src="<?= asset('assets/js/lead-pipeline.js') ?>"></script>
</body>
</html>
