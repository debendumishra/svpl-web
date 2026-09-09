<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Main Public Website Layout (XAMPP & Production Ready)
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Surya Vistaara Pvt. Ltd. | PM Surya Ghar Network & Lead Platform') ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <meta name="base-url" content="<?= base_path_url() ?>">
    
    <!-- Custom Solar Theme CSS -->
    <link rel="stylesheet" href="<?= asset('assets/css/solar-theme.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/network-tree.css') ?>">
</head>
<body>

    <!-- TOP PROMOTER BAR -->
    <div class="py-1 px-3 text-center text-white" style="background: #061528; font-size: 0.8rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
        <div class="container d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <i class="bi bi-shield-check text-warning me-1"></i> Corporate Promoter for <strong>Dhwajja Solar India Pvt. Ltd.</strong> | PM Surya Ghar Ecosystem
            </div>
            <div class="d-none d-md-flex align-items-center gap-3">
                <span><i class="bi bi-geo-alt-fill text-danger me-1"></i> Odisha, India</span>
                <span><i class="bi bi-telephone-fill text-success me-1"></i> +91 674 295 4800</span>
            </div>
        </div>
    </div>

    <!-- MAIN NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-svpl sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= url('/') ?>">
                <div style="background: linear-gradient(135deg, #F59E0B 0%, #10B981 100%); width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #0B2545; font-weight: 900; font-size: 1.2rem;">
                    ☀
                </div>
                <div>
                    <span style="font-weight: 800; font-size: 1.25rem; letter-spacing: -0.5px; color: #ffffff;">SURYA VISTAARA</span>
                    <span class="brand-badge ms-1">ODISHA</span>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="<?= url('/') ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/about') ?>">About SVPL</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/pm-surya-ghar') ?>">PM Surya Ghar</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/how-it-works') ?>">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/faq') ?>">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/contact') ?>">Contact</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <a href="<?= url('/register-advisor') ?>" class="btn btn-svpl-solar btn-sm">
                        <i class="bi bi-person-plus-fill me-1"></i> Become Advisor
                    </a>
                    <a href="<?= url('/login') ?>" class="btn btn-outline-light btn-sm px-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <main class="flex-grow-1">
        <?= $content ?>
    </main>

    <!-- CORPORATE FOOTER -->
    <footer class="footer-svpl">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="background: #F59E0B; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #0B2545; font-weight: 800;">
                            ☀
                        </div>
                        <h5 class="m-0 text-white fw-bold">SURYA VISTAARA PVT. LTD.</h5>
                    </div>
                    <p style="font-size: 0.85rem; line-height: 1.6; color: #94A3B8;">
                        Surya Vistaara Pvt. Ltd. (SVPL) is an authorized corporate promoter for <strong>Dhwajja Solar India Pvt. Ltd.</strong>, driving clean, green rooftop solar energy under the PM Surya Ghar: Muft Bijli Yojana across all 30 districts of Odisha.
                    </p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="https://api.whatsapp.com/send?phone=916742954800&text=Hello%20Surya%20Vistaara%20Team" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-whatsapp me-1"></i> WhatsApp Helpline
                        </a>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <h5>Navigation</h5>
                    <a href="<?= url('/') ?>">Home</a>
                    <a href="<?= url('/about') ?>">About SVPL</a>
                    <a href="<?= url('/pm-surya-ghar') ?>">PM Surya Ghar</a>
                    <a href="<?= url('/how-it-works') ?>">Workflow & Process</a>
                    <a href="<?= url('/faq') ?>">FAQ & Solar Facts</a>
                    <a href="<?= url('/contact') ?>">Contact Support</a>
                </div>

                <div class="col-lg-3 col-6">
                    <h5>Portals & Joining</h5>
                    <a href="<?= url('/register-advisor') ?>"><i class="bi bi-person-badge me-1 text-warning"></i> Register as Advisor</a>
                    <a href="<?= url('/register-customer') ?>"><i class="bi bi-house-door me-1 text-success"></i> Customer Registration</a>
                    <a href="<?= url('/login') ?>"><i class="bi bi-lock me-1"></i> Advisor Login</a>
                    <a href="<?= url('/login') ?>"><i class="bi bi-person me-1"></i> Customer Login</a>
                    <a href="<?= url('/verify') ?>"><i class="bi bi-patch-check me-1 text-info"></i> Verify Document / ID</a>
                </div>

                <div class="col-lg-3">
                    <h5>Office & Helpdesk</h5>
                    <p style="font-size: 0.85rem; line-height: 1.5; color: #94A3B8;">
                        <i class="bi bi-geo-alt text-warning me-1"></i> MIG-84, Pokhariput, BDA Colony, Phase-1 Pokhariput, Bhubaneswar, Odisha - 751020<br>
                        <i class="bi bi-telephone text-success me-1"></i> <strong><a href="tel:9040999899" class="text-white text-decoration-none">9040999899</a></strong><br>
                        <i class="bi bi-envelope text-info me-1"></i> dhwajjasolarsupport@gmail.com<br>
                        <i class="bi bi-patch-check text-warning me-1"></i> GSTIN: <code>21AAMCD5948B1ZU</code>
                    </p>
                    <div class="alert alert-dark p-2 text-white border-secondary" style="font-size: 0.75rem;">
                        <strong>DISCOM Coverage:</strong> TPCODL, TPNODL, TPSODL, TPWODL
                    </div>
                </div>
            </div>

            <!-- MANDATORY STATUTORY DISCLAIMER -->
            <div class="pt-3 border-top border-secondary text-center" style="font-size: 0.75rem; color: #64748B;">
                <p class="mb-1">
                    <strong>Statutory Disclaimer:</strong> Surya Vistaara Pvt. Ltd. (SVPL) is an independent corporate promoter for Dhwajja Solar India Pvt. Ltd. and is NOT a government agency or DISCOM. PM Surya Ghar subsidies, loan interest rates, and approval timelines are subject to Central Government and DISCOM / Bank policies. No guaranteed earnings or fixed returns are promised.
                </p>
                <div class="d-flex justify-content-center gap-3 mt-2 flex-wrap">
                    <a href="<?= url('/terms') ?>" class="text-secondary">Terms & Conditions</a>
                    <a href="<?= url('/privacy') ?>" class="text-secondary">Privacy Policy</a>
                    <a href="<?= url('/disclaimer') ?>" class="text-secondary">Disclaimer</a>
                </div>
                <div class="mt-2">
                    &copy; <?= date('Y') ?> Surya Vistaara Pvt. Ltd. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery & Bootstrap 5 Bundle JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
