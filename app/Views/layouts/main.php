<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Main Public Website Layout (High-Contrast, Modern Styling)
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Surya Vistaara | PM Surya Ghar Odisha Rooftop Solar Portal') ?></title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Dynamic Favicon -->
    <?php if ($favUrl = company_favicon_url()): ?>
        <link rel="icon" href="<?= htmlspecialchars($favUrl) ?>">
    <?php endif; ?>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <meta name="base-url" content="<?= base_path_url() ?>">
    
    <!-- Custom Solar Theme CSS -->
    <link rel="stylesheet" href="<?= asset('assets/css/solar-theme.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/network-tree.css') ?>">
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- TOP PROMOTER TICKER BAR -->
    <div class="live-ticker-bar px-3">
        <div class="container d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <i class="bi bi-shield-check text-warning me-1"></i> Corporate Promoter for <strong><?= htmlspecialchars(company_promoter()) ?></strong> | PM Surya Ghar Odisha Hub
            </div>
            <div class="d-none d-md-flex align-items-center gap-3">
                <span><i class="bi bi-geo-alt-fill text-danger me-1"></i> Bhubaneswar, Odisha</span>
                <span><i class="bi bi-telephone-fill text-success me-1"></i> <?= htmlspecialchars(company_phone()) ?></span>
            </div>
        </div>
    </div>

    <!-- MAIN NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-svpl sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= url('/') ?>">
                <?php if ($logoUrl = company_logo_url()): ?>
                    <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars(company_name()) ?>" style="max-height: 42px; width: auto; max-width: 170px; object-fit: contain;">
                <?php else: ?>
                    <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900; font-size: 1.25rem; box-shadow: 0 0 12px rgba(245, 158, 11, 0.4);">
                        ☀
                    </div>
                    <div>
                        <span style="font-weight: 800; font-size: 1.25rem; letter-spacing: -0.5px; color: #ffffff;" class="font-heading"><?= htmlspecialchars(company_short_name() ?: 'SURYA VISTAARA') ?></span>
                        <span class="badge bg-warning text-dark ms-1 fw-bold" style="font-size: 0.65rem;">ODISHA</span>
                    </div>
                <?php endif; ?>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link <?= $_SERVER['REQUEST_URI'] === url('/') ? 'active' : '' ?>" href="<?= url('/') ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/about') ?>">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/pm-surya-ghar') ?>">PM Surya Ghar</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/solar-solutions') ?>">Solar Solutions</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/how-it-works') ?>">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/become-advisor') ?>">Business Opportunity</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/faq') ?>">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/contact') ?>">Contact</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <a href="<?= url('/register-advisor') ?>" class="btn btn-svpl-solar btn-sm shadow-sm">
                        <i class="bi bi-person-plus-fill me-1"></i> Become Advisor
                    </a>
                    <a href="<?= url('/login') ?>" class="btn btn-outline-light btn-sm px-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Portal Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <main class="flex-grow-1">
        <?= $content ?>
    </main>

    <!-- CORPORATE FOOTER (DEEP NAVY, GUARANTEED VISIBLE TEXT) -->
    <footer class="footer-svpl mt-auto">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <?php if ($logoUrl = company_logo_url()): ?>
                            <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars(company_name()) ?>" style="max-height: 38px; width: auto; max-width: 160px; object-fit: contain;">
                        <?php else: ?>
                            <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900; font-size: 1.1rem;">
                                ☀
                            </div>
                            <h5 class="m-0 footer-brand-title"><?= htmlspecialchars(company_name()) ?></h5>
                        <?php endif; ?>
                    </div>
                    <p style="font-size: 0.88rem; line-height: 1.6; color: #CBD5E1;">
                        <?= htmlspecialchars(company_name()) ?> (<?= htmlspecialchars(company_short_name()) ?>) is an authorized corporate promoter for <strong><?= htmlspecialchars(company_promoter()) ?></strong>, driving clean rooftop solar energy under the PM Surya Ghar: Muft Bijli Yojana across all 30 districts of Odisha.
                    </p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="https://api.whatsapp.com/send?phone=91<?= preg_replace('/[^0-9]/', '', company_whatsapp()) ?>&text=Hello%20<?= urlencode(company_short_name()) ?>%20Team" target="_blank" class="btn btn-sm btn-outline-success" style="display: inline-flex; align-items: center;">
                            <i class="bi bi-whatsapp me-1"></i> WhatsApp Helpline
                        </a>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <h5>Quick Navigation</h5>
                    <a href="<?= url('/') ?>"><i class="bi bi-chevron-right me-1 text-warning small"></i> Home</a>
                    <a href="<?= url('/about') ?>"><i class="bi bi-chevron-right me-1 text-warning small"></i> About Us</a>
                    <a href="<?= url('/pm-surya-ghar') ?>"><i class="bi bi-chevron-right me-1 text-warning small"></i> PM Surya Ghar</a>
                    <a href="<?= url('/solar-solutions') ?>"><i class="bi bi-chevron-right me-1 text-warning small"></i> Solar Solutions</a>
                    <a href="<?= url('/how-it-works') ?>"><i class="bi bi-chevron-right me-1 text-warning small"></i> How It Works</a>
                    <a href="<?= url('/become-advisor') ?>"><i class="bi bi-chevron-right me-1 text-warning small"></i> Business Opportunity</a>
                    <a href="<?= url('/faq') ?>"><i class="bi bi-chevron-right me-1 text-warning small"></i> FAQ</a>
                    <a href="<?= url('/contact') ?>"><i class="bi bi-chevron-right me-1 text-warning small"></i> Contact</a>
                </div>

                <div class="col-lg-3 col-6">
                    <h5>Portals & Services</h5>
                    <a href="<?= url('/register-advisor') ?>"><i class="bi bi-person-badge-fill me-2 text-warning"></i> Register as Advisor</a>
                    <a href="<?= url('/contact') ?>"><i class="bi bi-headset me-2 text-success"></i> Customer Solar Inquiry</a>
                    <a href="<?= url('/login') ?>"><i class="bi bi-shield-lock-fill me-2 text-info"></i> Advisor / Admin Login</a>
                    <a href="<?= url('/login') ?>"><i class="bi bi-person-circle me-2 text-primary"></i> Customer Login</a>
                    <a href="<?= url('/verify') ?>"><i class="bi bi-patch-check-fill me-2 text-warning"></i> Verify Document / ID</a>
                </div>

                <div class="col-lg-3">
                    <h5>Office & Helpdesk</h5>
                    <p style="font-size: 0.88rem; line-height: 1.5; color: #CBD5E1;">
                        <i class="bi bi-geo-alt-fill text-warning me-1"></i> <?= htmlspecialchars(company_address()) ?><br>
                        <i class="bi bi-telephone-fill text-success me-1"></i> <strong><a href="tel:<?= preg_replace('/[^0-9]/', '', company_phone()) ?>" style="color: #FBBF24 !important; display: inline;"><?= htmlspecialchars(company_phone()) ?></a></strong><br>
                        <i class="bi bi-envelope-fill text-info me-1"></i> <?= htmlspecialchars(company_email()) ?><br>
                        <i class="bi bi-patch-check-fill text-warning me-1"></i> GSTIN: <code class="text-white"><?= htmlspecialchars(company_gstin()) ?></code>
                    </p>
                    <div class="p-2 rounded bg-dark border border-secondary text-white" style="font-size: 0.75rem;">
                        <strong class="text-warning">DISCOM Coverage:</strong> TPCODL, TPNODL, TPSODL, TPWODL
                    </div>
                </div>
            </div>

            <!-- MANDATORY STATUTORY DISCLAIMER -->
            <div class="pt-3 border-top border-secondary text-center" style="font-size: 0.75rem; color: #94A3B8;">
                <p class="mb-2">
                    <strong>Statutory Disclaimer:</strong> <?= htmlspecialchars(company_name()) ?> (<?= htmlspecialchars(company_short_name()) ?>) is an authorized corporate promoter for <?= htmlspecialchars(company_promoter()) ?> and is NOT a government department. PM Surya Ghar subsidies, loan interest rates, and DISCOM net-metering approvals are subject to Central Government and DISCOM guidelines.
                </p>
                <div class="d-flex justify-content-center gap-3 mt-2 flex-wrap">
                    <a href="<?= url('/terms') ?>" class="footer-legal-link">Terms & Conditions</a>
                    <span class="text-secondary">|</span>
                    <a href="<?= url('/privacy') ?>" class="footer-legal-link">Privacy Policy</a>
                    <span class="text-secondary">|</span>
                    <a href="<?= url('/disclaimer') ?>" class="footer-legal-link">Disclaimer</a>
                </div>
                <div class="mt-2 text-white-50">
                    &copy; <?= date('Y') ?> <?= htmlspecialchars(company_name()) ?>. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery & Bootstrap 5 Bundle JS -->
    <script>window.SVPL_BASE_URL = '<?= rtrim(url(''), '/') ?>';</script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
    <script src="<?= asset('assets/js/image-compressor.js') ?>"></script>
    <script src="<?= asset('assets/js/photo-crop-studio.js') ?>"></script>
    <script src="<?= asset('assets/js/location-cascader.js') ?>"></script>
</body>
</html>
