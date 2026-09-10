<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Customer Solar Experience Portal Layout (Solar Luminary Design System)
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
<body style="background-color: #F8FAFC;">

    <!-- TOP BENEFICIARY NOTICE BAR -->
    <div class="live-ticker-bar px-3">
        <div class="container d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <span class="live-ticker-item text-white"><i class="bi bi-patch-check-fill text-warning"></i> PM Surya Ghar: Muft Bijli Yojana Odisha</span>
                <span class="live-ticker-item text-success fw-bold d-none d-md-inline"><i class="bi bi-gift-fill text-success"></i> Central ₹78k + State ₹60k = Total ₹1,38,000 Subsidy</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.7rem;">DISCOM Verified</span>
            </div>
        </div>
    </div>

    <!-- PRIMARY CUSTOMER NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-svpl sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= url('/customer/dashboard') ?>">
                <div style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 900; font-size: 1.2rem; box-shadow: 0 0 12px rgba(16, 185, 129, 0.4);">
                    ☀
                </div>
                <div>
                    <span class="fw-bold font-heading" style="font-size: 1.15rem; color: #ffffff;">SURYA VISTAARA</span>
                    <span class="badge bg-success ms-1" style="font-size: 0.68rem; font-weight: 800;">SOLAR CONSUMER</span>
                </div>
            </a>

            <div class="d-flex align-items-center gap-2">
                <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm" title="Logout">
                    <i class="bi bi-box-arrow-right me-1"></i> Sign Out
                </a>
            </div>
        </div>
    </nav>

    <!-- CUSTOMER HEADER STRIP & MENU TABS -->
    <div class="bg-white border-bottom shadow-sm">
        <div class="container py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 34px; height: 34px; border-radius: 50%; background: #0B2545; color: #10B981; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                        <?= strtoupper(substr($customerName, 0, 2)) ?>
                    </div>
                    <div>
                        <div class="fw-bold text-dark font-heading" style="font-size: 0.95rem; line-height: 1.2;">
                            <?= htmlspecialchars($customerName) ?>
                        </div>
                        <div class="d-flex align-items-center gap-1 mt-1">
                            <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.7rem;"><?= htmlspecialchars($customerCode) ?></span>
                            <span class="badge bg-primary-subtle text-primary" style="font-size: 0.65rem;">Dhwajja 3kW System</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="d-flex gap-2">
                    <a href="<?= url('/customer/dashboard') ?>" class="btn btn-sm <?= $activeUri === url('/customer/dashboard') ? 'btn-primary' : 'btn-outline-secondary' ?>">
                        <i class="bi bi-speedometer2 me-1"></i> Status Tracker
                    </a>
                    <a href="<?= url('/customer/quotation') ?>" class="btn btn-sm <?= strpos($activeUri, '/customer/quotation') !== false ? 'btn-primary' : 'btn-outline-secondary' ?>">
                        <i class="bi bi-file-earmark-text me-1"></i> Official Quotation
                    </a>
                    <a href="<?= url('/customer/documents') ?>" class="btn btn-sm <?= strpos($activeUri, '/customer/documents') !== false ? 'btn-primary' : 'btn-outline-secondary' ?>">
                        <i class="bi bi-folder-check me-1"></i> KYC Documents
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN PORTAL CONTAINER -->
    <main class="container py-4">
        <?= $content ?>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
