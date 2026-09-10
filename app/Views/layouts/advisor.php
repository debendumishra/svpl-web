<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Advisor Partner Workspace Layout (Solar Luminary Design System)
 */
$advisorName = $_SESSION['user_name'] ?? 'Advisor Partner';
$advisorCode = $_SESSION['advisor_code'] ?? 'SVPL-ADV-8842';
$referralCode = $_SESSION['referral_code'] ?? 'SVPL';
$qualificationStatus = $_SESSION['qualification_status'] ?? 'QUALIFIED';
$activeUri = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Solar Advisor Partner Portal | Surya Vistaara') ?></title>
    
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

    <!-- TOP PROMOTER BAR -->
    <div class="live-ticker-bar px-3">
        <div class="container d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <span class="live-ticker-item text-white"><i class="bi bi-shield-fill-check text-warning"></i> Certified Solar Advisor Partner Network</span>
                <span class="live-ticker-item d-none d-md-inline"><i class="bi bi-gift-fill text-success"></i> Central Subsidy ₹78k + Odisha State Subsidy ₹60k = <strong>₹1.38 Lakhs</strong></span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-dark text-warning border border-warning" style="font-size: 0.7rem;">Dhwajja Solar India</span>
            </div>
        </div>
    </div>

    <!-- PRIMARY ADVISOR HEADER NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-svpl sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= url('/advisor/dashboard') ?>">
                <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900; font-size: 1.2rem; box-shadow: 0 0 12px rgba(245, 158, 11, 0.4);">
                    ☀
                </div>
                <div>
                    <span class="fw-bold font-heading" style="font-size: 1.15rem; color: #ffffff;">SURYA VISTAARA</span>
                    <span class="badge bg-warning text-dark ms-1" style="font-size: 0.68rem; font-weight: 800;">ADVISOR SUITE</span>
                </div>
            </a>

            <!-- Advisor Quick Actions & Profile -->
            <div class="d-flex align-items-center gap-2">
                <a href="<?= url('/register-customer?ref=' . urlencode($referralCode)) ?>" class="btn btn-svpl-solar btn-sm d-none d-sm-inline-block">
                    <i class="bi bi-plus-circle-fill me-1"></i> + Register Customer
                </a>
                <a href="<?= url('/advisor/qr-code') ?>" class="btn btn-outline-light btn-sm" title="Doorstep QR Code">
                    <i class="bi bi-qr-code"></i>
                </a>
                <a href="<?= url('/advisor/id-card') ?>" class="btn btn-outline-light btn-sm d-none d-md-inline-block" title="My Solar ID Card">
                    <i class="bi bi-person-vcard"></i> ID Card
                </a>
                <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- ADVISOR COMMAND SUB-NAVBAR WITH EXPANDED MENU OPTIONS -->
    <div class="bg-white border-bottom shadow-sm">
        <div class="container py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <!-- User & Rank Details -->
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 34px; height: 34px; border-radius: 50%; background: #0B2545; color: #F59E0B; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                        <?= strtoupper(substr($advisorName, 0, 2)) ?>
                    </div>
                    <div>
                        <div class="fw-bold text-dark font-heading" style="font-size: 0.95rem; line-height: 1.2;">
                            <?= htmlspecialchars($advisorName) ?>
                        </div>
                        <div class="d-flex align-items-center gap-1 mt-1">
                            <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.7rem;"><?= htmlspecialchars($advisorCode) ?></span>
                            <span class="badge <?= $qualificationStatus === 'QUALIFIED' ? 'bg-success' : 'bg-warning text-dark' ?>" style="font-size: 0.65rem;">
                                <?= htmlspecialchars($qualificationStatus) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Instant Referral Link Banner -->
                <div class="d-flex align-items-center gap-2 bg-light p-1 px-3 rounded-pill border">
                    <span class="small text-muted d-none d-md-inline">My Referral Code:</span>
                    <span class="badge bg-primary fs-6 font-monospace cursor-pointer btn-copy" data-copy="<?= htmlspecialchars($referralCode) ?>" title="Click to copy">
                        <?= htmlspecialchars($referralCode) ?> <i class="bi bi-clipboard ms-1"></i>
                    </span>
                </div>
            </div>

            <!-- Horizontal Tab Navigation Bar -->
            <div class="d-flex gap-1 mt-2 pt-2 border-top overflow-x-auto pb-1">
                <a href="<?= url('/advisor/dashboard') ?>" class="btn btn-sm <?= $activeUri === url('/advisor/dashboard') ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    <i class="bi bi-speedometer2 me-1"></i> Command Center
                </a>
                <a href="<?= url('/advisor/customers') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/customers') !== false ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    <i class="bi bi-people me-1"></i> My Customers
                </a>
                <a href="<?= url('/advisor/network') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/network') !== false ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    <i class="bi bi-bezier2 me-1"></i> 9-Level Genealogy
                </a>
                <a href="<?= url('/advisor/wallet') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/wallet') !== false ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    <i class="bi bi-wallet2 me-1"></i> Commission Wallet
                </a>
                <a href="<?= url('/advisor/id-card') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/id-card') !== false ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    <i class="bi bi-person-vcard me-1"></i> ID Card & Letter
                </a>
                <a href="<?= url('/advisor/qr-code') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/qr-code') !== false ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    <i class="bi bi-qr-code-scan me-1"></i> Doorstep QR
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN PORTAL CONTENT -->
    <main class="container py-4">
        <?= $content ?>
    </main>

    <!-- MOBILE BOTTOM NAVIGATION -->
    <div class="mobile-bottom-nav">
        <a href="<?= url('/advisor/dashboard') ?>" class="<?= $activeUri === url('/advisor/dashboard') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2 fs-5"></i>
            <span>Center</span>
        </a>
        <a href="<?= url('/advisor/customers') ?>" class="<?= strpos($activeUri, '/advisor/customers') !== false ? 'active' : '' ?>">
            <i class="bi bi-people fs-5"></i>
            <span>Clients</span>
        </a>
        <a href="<?= url('/advisor/network') ?>" class="<?= strpos($activeUri, '/advisor/network') !== false ? 'active' : '' ?>">
            <i class="bi bi-bezier2 fs-5"></i>
            <span>Network</span>
        </a>
        <a href="<?= url('/advisor/wallet') ?>" class="<?= strpos($activeUri, '/advisor/wallet') !== false ? 'active' : '' ?>">
            <i class="bi bi-wallet2 fs-5"></i>
            <span>Wallet</span>
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
