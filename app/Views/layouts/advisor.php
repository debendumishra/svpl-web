<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Mobile-First Portal Layout (XAMPP Ready)
 */
$advisorName = $_SESSION['user_name'] ?? 'Advisor';
$advisorCode = $_SESSION['advisor_code'] ?? 'SVPL-ADV';
$referralCode = $_SESSION['referral_code'] ?? 'SVPL';
$qualificationStatus = $_SESSION['qualification_status'] ?? 'NEW';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Advisor Workspace | Surya Vistaara') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <meta name="base-url" content="<?= base_path_url() ?>">
    
    <link rel="stylesheet" href="<?= asset('assets/css/solar-theme.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/network-tree.css') ?>">
</head>
<body style="background-color: #F8FAFC;">

    <!-- TOP ADVISOR NAVBAR -->
    <nav class="navbar navbar-dark navbar-svpl sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= url('/advisor/dashboard') ?>">
                <div style="background: #F59E0B; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #0B2545; font-weight: 800;">
                    ☀
                </div>
                <div>
                    <span class="fw-bold" style="font-size: 1.1rem; color: #fff;">SURYA VISTAARA</span>
                    <span class="badge bg-warning text-dark ms-1">ADVISOR</span>
                </div>
            </a>

            <div class="d-flex align-items-center gap-2">
                <a href="<?= url('/register-customer?ref=' . urlencode($referralCode)) ?>" class="btn btn-svpl-solar btn-sm d-none d-sm-inline-block">
                    <i class="bi bi-plus-circle-fill me-1"></i> Add Customer
                </a>
                <a href="<?= url('/advisor/qr-code') ?>" class="btn btn-outline-light btn-sm" title="My QR Code">
                    <i class="bi bi-qr-code"></i>
                </a>
                <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- ADVISOR HEADER STRIP -->
    <div class="bg-white border-bottom py-2 shadow-sm">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="fw-bold text-primary" style="font-size: 0.95rem;">
                    <?= htmlspecialchars($advisorName) ?>
                </div>
                <span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($advisorCode) ?></span>
                <span class="badge <?= $qualificationStatus === 'QUALIFIED' ? 'bg-success' : 'bg-warning text-dark' ?>">
                    <?= htmlspecialchars($qualificationStatus) ?>
                </span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="small text-muted">My Referral Code:</span>
                <span class="badge bg-primary fs-6 font-monospace btn-copy cursor-pointer" data-copy="<?= htmlspecialchars($referralCode) ?>" title="Click to copy">
                    <?= htmlspecialchars($referralCode) ?> <i class="bi bi-clipboard ms-1"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="container py-4">
        <?= $content ?>
    </div>

    <!-- MOBILE BOTTOM NAVIGATION BAR -->
    <div class="mobile-bottom-nav">
        <a href="<?= url('/advisor/dashboard') ?>" class="<?= $_SERVER['REQUEST_URI'] === url('/advisor/dashboard') ? 'active' : '' ?>">
            <i class="bi bi-house-door fs-5"></i>
            <span>Home</span>
        </a>
        <a href="<?= url('/advisor/network') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], '/advisor/network') !== false ? 'active' : '' ?>">
            <i class="bi bi-diagram-3 fs-5"></i>
            <span>Network</span>
        </a>
        <a href="<?= url('/advisor/customers') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], '/advisor/customers') !== false ? 'active' : '' ?>">
            <i class="bi bi-people fs-5"></i>
            <span>Customers</span>
        </a>
        <a href="<?= url('/advisor/wallet') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], '/advisor/wallet') !== false ? 'active' : '' ?>">
            <i class="bi bi-wallet2 fs-5"></i>
            <span>Wallet</span>
        </a>
        <a href="<?= url('/advisor/qr-code') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], '/advisor/qr-code') !== false ? 'active' : '' ?>">
            <i class="bi bi-qr-code fs-5"></i>
            <span>My QR</span>
        </a>
    </div>

    <!-- jQuery & Bootstrap 5 Bundle JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
