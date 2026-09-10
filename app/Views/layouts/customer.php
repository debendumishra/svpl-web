<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Customer Solar Experience Portal Layout (Fixed Layout, Constant Header/Footer & Clean Navigation)
 */
$customerName = $_SESSION['user_name'] ?? 'Beneficiary Customer';
$customerCode = $_SESSION['customer_code'] ?? 'SVPL-CUS-1082';
$activeUri = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Customer Solar Portal | PM Surya Ghar Odisha') ?></title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <meta name="base-url" content="<?= base_path_url() ?>">
    
    <!-- Solar Theme CSS -->
    <link rel="stylesheet" href="<?= asset('assets/css/solar-theme.css') ?>">
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
                        <span class="text-white"><i class="bi bi-patch-check-fill text-warning me-1"></i> PM Surya Ghar: Muft Bijli Yojana Odisha</span>
                        <span class="text-success fw-bold d-none d-md-inline"><i class="bi bi-gift-fill me-1"></i> ₹1,38,000 Combined Central & Odisha State Subsidy</span>
                    </div>
                    <div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.7rem;">DISCOM Verified</span>
                    </div>
                </div>
            </div>

            <!-- Customer Header Strip -->
            <div class="px-3 py-2 d-flex justify-content-between align-items-center border-bottom bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 900;">
                        ☀
                    </div>
                    <div>
                        <div class="fw-bold font-heading text-navy" style="font-size: 1.05rem; line-height: 1.2;">
                            <?= htmlspecialchars($customerName) ?>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.68rem;"><?= htmlspecialchars($customerCode) ?></span>
                            <span class="badge bg-primary-subtle text-primary" style="font-size: 0.62rem;">Dhwajja 3kW Plant</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="<?= url('/customer/dashboard') ?>" class="btn btn-sm <?= $activeUri === url('/customer/dashboard') ? 'btn-primary' : 'btn-light border' ?>">
                        <i class="bi bi-speedometer2 me-1"></i> Status Tracker
                    </a>
                    <a href="<?= url('/customer/quotation') ?>" class="btn btn-sm <?= strpos($activeUri, '/customer/quotation') !== false ? 'btn-primary' : 'btn-light border' ?>">
                        <i class="bi bi-file-earmark-text me-1"></i> Proposal
                    </a>
                    <a href="<?= url('/customer/documents') ?>" class="btn btn-sm <?= strpos($activeUri, '/customer/documents') !== false ? 'btn-primary' : 'btn-light border' ?>">
                        <i class="bi bi-folder-check me-1"></i> KYC Locker
                    </a>
                    <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm" title="Sign Out">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>
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
            <div>© <?= date('Y') ?> <strong>Surya Vistaara Pvt. Ltd. (SVPL)</strong> — Beneficiary Support Desk.</div>
            <div class="d-none d-md-block">Helpline Toll-Free: 1800-889-SVPL</div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
