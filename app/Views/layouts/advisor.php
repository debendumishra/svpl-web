<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Advisor Partner Workspace Layout (Fixed Layout, Constant Header/Footer & Expandable Navigation)
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
<body>

    <!-- FIXED APPLICATION WRAPPER -->
    <div class="app-layout-wrapper">
        
        <!-- CONSTANT TOP HEADER (STICKY) -->
        <header class="app-header">
            <!-- Ticker -->
            <div class="live-ticker-bar px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-white"><i class="bi bi-shield-fill-check text-warning me-1"></i> Certified Solar Advisor Partner Portal</span>
                        <span class="d-none d-md-inline text-success fw-bold"><i class="bi bi-gift-fill me-1"></i> Central ₹78k + Odisha ₹60k = Total ₹1,38,000 Subsidy</span>
                    </div>
                    <div>
                        <span class="badge bg-dark text-warning border border-warning" style="font-size: 0.68rem;">Dhwajja Solar India</span>
                    </div>
                </div>
            </div>

            <!-- Primary Header Strip with User Details & Quick Actions -->
            <div class="px-3 py-2 d-flex justify-content-between align-items-center border-bottom bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900;">
                        ☀
                    </div>
                    <div>
                        <div class="fw-bold font-heading text-navy" style="font-size: 1.05rem; line-height: 1.2;">
                            <?= htmlspecialchars($advisorName) ?>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.68rem;"><?= htmlspecialchars($advisorCode) ?></span>
                            <span class="badge <?= $qualificationStatus === 'QUALIFIED' ? 'bg-success' : 'bg-warning text-dark' ?>" style="font-size: 0.62rem;">
                                <?= htmlspecialchars($qualificationStatus) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="d-none d-lg-flex align-items-center gap-2 bg-light p-1 px-3 rounded-pill border">
                        <span class="small text-secondary">Referral Code:</span>
                        <span class="badge bg-primary fs-6 font-monospace cursor-pointer btn-copy" data-copy="<?= htmlspecialchars($referralCode) ?>" title="Click to copy">
                            <?= htmlspecialchars($referralCode) ?> <i class="bi bi-clipboard ms-1"></i>
                        </span>
                    </div>
                    <a href="<?= url('/register-customer?ref=' . urlencode($referralCode)) ?>" class="btn btn-svpl-solar btn-sm shadow-sm d-none d-sm-inline-block">
                        <i class="bi bi-plus-circle-fill me-1"></i> + Register Customer
                    </a>
                    <a href="<?= url('/advisor/qr-code') ?>" class="btn btn-outline-dark btn-sm" title="Doorstep QR Code">
                        <i class="bi bi-qr-code"></i>
                    </a>
                    <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm" title="Sign Out">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Horizontal Tab Navigation Bar -->
            <div class="bg-light px-3 py-1 border-bottom d-flex gap-1 overflow-x-auto">
                <a href="<?= url('/advisor/dashboard') ?>" class="btn btn-sm <?= $activeUri === url('/advisor/dashboard') ? 'btn-primary' : 'btn-light border' ?>">
                    <i class="bi bi-speedometer2 me-1"></i> Command Center
                </a>
                <a href="<?= url('/advisor/customers') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/customers') !== false ? 'btn-primary' : 'btn-light border' ?>">
                    <i class="bi bi-people me-1"></i> My Customers
                </a>
                <a href="<?= url('/advisor/network') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/network') !== false ? 'btn-primary' : 'btn-light border' ?>">
                    <i class="bi bi-bezier2 me-1"></i> 9-Level Tree
                </a>
                <a href="<?= url('/advisor/wallet') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/wallet') !== false ? 'btn-primary' : 'btn-light border' ?>">
                    <i class="bi bi-wallet2 me-1"></i> Commission Wallet
                </a>
                <a href="<?= url('/advisor/id-card') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/id-card') !== false ? 'btn-primary' : 'btn-light border' ?>">
                    <i class="bi bi-person-vcard me-1"></i> ID Card & Letter
                </a>
                <a href="<?= url('/advisor/qr-code') ?>" class="btn btn-sm <?= strpos($activeUri, '/advisor/qr-code') !== false ? 'btn-primary' : 'btn-light border' ?>">
                    <i class="bi bi-qr-code-scan me-1"></i> Doorstep QR
                </a>
            </div>
        </header>

        <!-- INDEPENDENTLY SCROLLABLE MAIN CONTENT AREA -->
        <main class="app-main-content">
            <div class="container-fluid max-w-7xl">
                <?= $content ?>
            </div>
        </main>

        <!-- CONSTANT FOOTER (STICKY BOTTOM) -->
        <footer class="app-footer text-muted d-flex justify-content-between align-items-center">
            <div>© <?= date('Y') ?> <strong>Surya Vistaara Pvt. Ltd. (SVPL)</strong> — Advisor Partner Network.</div>
            <div class="d-none d-md-block">24x7 Advisor Support: 1800-889-SVPL</div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
