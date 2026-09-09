<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Customer Portal Layout (XAMPP Ready)
 */
$customerName = $_SESSION['user_name'] ?? 'Customer';
$customerCode = $_SESSION['customer_code'] ?? 'SVPL-CUS';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Customer Solar Portal | Surya Vistaara') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <meta name="base-url" content="<?= base_path_url() ?>">
    
    <link rel="stylesheet" href="<?= asset('assets/css/solar-theme.css') ?>">
</head>
<body style="background-color: #F8FAFC;">

    <!-- TOP CUSTOMER NAVBAR -->
    <nav class="navbar navbar-dark navbar-svpl sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= url('/customer/dashboard') ?>">
                <div style="background: #10B981; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800;">
                    ☀
                </div>
                <div>
                    <span class="fw-bold" style="font-size: 1.1rem; color: #fff;">SURYA VISTAARA</span>
                    <span class="badge bg-success ms-1">SOLAR CONSUMER</span>
                </div>
            </a>

            <div class="d-flex align-items-center gap-2">
                <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm" title="Logout">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- CUSTOMER HEADER STRIP -->
    <div class="bg-white border-bottom py-2 shadow-sm">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="fw-bold text-dark"><?= htmlspecialchars($customerName) ?></div>
                <span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($customerCode) ?></span>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= url('/customer/dashboard') ?>" class="btn btn-sm <?= $_SERVER['REQUEST_URI'] === url('/customer/dashboard') ? 'btn-primary' : 'btn-outline-primary' ?>">Status Tracker</a>
                <a href="<?= url('/customer/quotation') ?>" class="btn btn-sm <?= $_SERVER['REQUEST_URI'] === url('/customer/quotation') ? 'btn-primary' : 'btn-outline-primary' ?>">Quotation</a>
                <a href="<?= url('/customer/documents') ?>" class="btn btn-sm <?= $_SERVER['REQUEST_URI'] === url('/customer/documents') ? 'btn-primary' : 'btn-outline-primary' ?>">Documents</a>
            </div>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="container py-4">
        <?= $content ?>
    </div>

    <!-- jQuery & Bootstrap 5 Bundle JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
