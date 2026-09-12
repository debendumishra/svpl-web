<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India (P) Ltd.
 * Official CR80 Duplex Printable Custom / Staff / Advisor ID Card
 * Pixel-Perfect CR80 Standard Layout (3.375" x 2.125" / 85.6mm x 54mm @ 300 DPI)
 */
$bloodGroup = !empty($card['blood_group']) ? $card['blood_group'] : 'O+ve';
$fullName = trim($card['full_name'] ?? '');
$mobileNumber = htmlspecialchars($card['mobile'] ?? '');
$designation = htmlspecialchars($card['designation'] ?? 'Official Representative');
$cardCode = htmlspecialchars($card['card_code'] ?? 'SVPL-ID-101');
$cardType = strtoupper($card['card_type'] ?? 'BOE');
$jurisdiction = !empty($card['jurisdiction']) ? htmlspecialchars($card['jurisdiction']) : 'Headquarters / All Odisha';
$fullAddress = !empty($card['address']) ? htmlspecialchars($card['address']) : company_address();
$issueDate = !empty($card['issue_date']) ? date('d-m-Y', strtotime($card['issue_date'])) : date('d-m-Y');
$validThru = !empty($card['valid_thru']) ? htmlspecialchars($card['valid_thru']) : '31-12-2027';
$emergencyContact = !empty($card['emergency_contact']) ? htmlspecialchars($card['emergency_contact']) : company_phone();

$qrVerifyUrl = function_exists('url') 
    ? url('/verify?type=' . urlencode($cardType) . '&code=' . urlencode($cardCode))
    : ('https://suryavistaara.com/verify?type=' . urlencode($cardType) . '&code=' . urlencode($cardCode));

$qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($qrVerifyUrl);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official ID Card - <?= $cardCode ?> - <?= htmlspecialchars($fullName) ?></title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Space Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            -webkit-font-smoothing: antialiased;
        }
        .font-mono-num {
            font-family: 'Space Grotesk', monospace;
        }
        
        /* Print Navigation Bar */
        .no-print-bar {
            max-width: 780px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }
        .btn-print {
            background: linear-gradient(135deg, #0f2d59 0%, #1e40af 100%);
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        .btn-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 45, 89, 0.3);
        }

        /* Container for Duplex Cards */
        .cards-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            justify-content: center;
            align-items: flex-start;
        }
        .card-column {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .side-badge {
            margin-bottom: 8px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 20px;
        }
        .badge-front {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .badge-back {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* Standard CR80 Card Dimensions: 53.98mm x 85.6mm (Portrait: 85.6mm Height x 53.98mm Width) */
        .id-card {
            width: 320px;
            height: 508px;
            background: #ffffff;
            border-radius: 16px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 16px 36px -8px rgba(15, 45, 89, 0.2), 0 0 0 1px rgba(15, 45, 89, 0.08);
            display: flex;
            flex-direction: column;
            border: 1px solid #cbd5e1;
        }

        /* Lanyard Slot Hole */
        .lanyard-slot {
            width: 38px;
            height: 6px;
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            margin: 0 auto;
        }

        /* Solar Background Watermark */
        .solar-bg-pattern {
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 100% 0%, rgba(2, 132, 199, 0.04) 0%, transparent 50%),
                radial-gradient(circle at 0% 100%, rgba(245, 158, 11, 0.05) 0%, transparent 45%);
            pointer-events: none;
            z-index: 1;
        }

        /* Full Width Corporate Header */
        .card-header-full {
            width: 100%;
            background: #ffffff;
            padding: 10px 14px 8px 14px;
            border-bottom: 2px solid #f59e0b;
            position: relative;
            z-index: 10;
        }

        /* Passport Photo Container */
        .photo-wrapper {
            position: relative;
            width: 86px;
            height: 104px;
            margin: 0 auto;
        }
        .photo-frame {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #0f2d59;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(15, 45, 89, 0.15);
            position: relative;
        }
        .photo-corner {
            position: absolute;
            width: 8px;
            height: 8px;
            border-color: #f59e0b;
            border-style: solid;
            pointer-events: none;
            z-index: 5;
        }
        .corner-tl { top: -2px; left: -2px; border-width: 2px 0 0 2px; border-top-left-radius: 4px; }
        .corner-tr { top: -2px; right: -2px; border-width: 2px 2px 0 0; border-top-right-radius: 4px; }
        .corner-bl { bottom: -2px; left: -2px; border-width: 0 0 2px 2px; border-bottom-left-radius: 4px; }
        .corner-br { bottom: -2px; right: -2px; border-width: 0 2px 2px 0; border-bottom-right-radius: 4px; }

        /* Badge Box Grid */
        .grid-badge-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            background: #f8fafc;
            padding: 4px 8px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        /* Bottom Dynamic Wave Ribbon */
        .bottom-wave-banner {
            width: 100%;
            height: 38px;
            position: relative;
            margin-top: auto;
            z-index: 10;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print-bar, .side-badge, .btn-print, .print-specification-note {
                display: none !important;
            }
            .cards-wrapper {
                gap: 20px !important;
            }
            .id-card {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                page-break-inside: avoid;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

    <!-- Print Navigation Bar -->
    <div class="no-print-bar">
        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="<?= url('/admin/id-cards') ?>" style="text-decoration: none; color: #0f2d59; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 4px;">
                <i class="bi bi-arrow-left"></i> Back to ID Cards
            </a>
            <span style="color: #cbd5e1;">|</span>
            <span style="font-weight: 800; color: #0f2d59; font-size: 0.95rem;">
                <?= $cardCode ?> • <?= htmlspecialchars($fullName) ?>
            </span>
            <span class="badge" style="background: #e0f2fe; color: #0369a1; font-size: 0.72rem; font-weight: 800; padding: 2px 8px; border-radius: 6px;">
                <?= $cardType ?>
            </span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <button class="btn-print" onclick="window.print()">
                <i class="bi bi-printer-fill"></i> Print ID Card (CR80)
            </button>
        </div>
    </div>

    <!-- Container for Duplex Cards -->
    <div class="cards-wrapper">

        <!-- ==================== FRONT SIDE ==================== -->
        <div class="card-column">
            <span class="side-badge badge-front">Front Side • CR80 Portrait</span>
            
            <div class="id-card">
                <!-- Slot Punch Guide -->
                <div style="position: absolute; top: 6px; left: 50%; transform: translateX(-50%); z-index: 30;">
                    <div class="lanyard-slot"></div>
                </div>

                <!-- Photovoltaic Pattern Overlay -->
                <div class="solar-bg-pattern"></div>

                <!-- Full Width Top Corporate Header -->
                <div class="card-header-full">
                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 4px;">
                        <?php if ($cLogo = company_logo_url()): ?>
                            <div style="max-width: 90px; max-height: 48px; display: flex; align-items: center; justify-content: center;">
                                <img src="<?= htmlspecialchars($cLogo) ?>" alt="Logo" style="max-height: 46px; max-width: 90px; object-fit: contain;">
                            </div>
                        <?php else: ?>
                            <div style="width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0f2d59 0%, #1e3a8a 100%); border-radius: 10px; color: #f59e0b; font-size: 1.6rem; font-weight: 900; box-shadow: 0 2px 8px rgba(15,45,89,0.25);">
                                ☀
                            </div>
                        <?php endif; ?>
                        <!-- Header Text & Tagline -->
                        <div style="flex: 1; min-width: 0; text-align: left;">
                            <div style="display: flex; align-items: baseline; gap: 4px; line-height: 1;">
                                <span style="font-size: 16px; font-weight: 900; color: #0f2d59; letter-spacing: -0.5px;"><?= htmlspecialchars(strtoupper(company_name())) ?></span>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 3px; line-height: 1;">
                                <h3 style="font-size: 10px; font-weight: 800; color: #0f2d59; text-transform: uppercase; letter-spacing: 0.5px;"><?= htmlspecialchars(company_promoter()) ?></h3>
                                <div style="display: flex; align-items: center; gap: 3px;">
                                    <span style="height: 5px; width: 10px; background: #0284c7; border-radius: 9999px;"></span>
                                    <span style="height: 5px; width: 14px; background: #ff7600; border-radius: 9999px;"></span>
                                </div>
                            </div>
                            <div style="margin-top: 3px; display: flex; align-items: center;">
                                <span style="font-size: 7.5px; font-weight: 900; color: #0f2d59; letter-spacing: 0.08em; text-transform: uppercase; background: #eff6ff; padding: 2px 6px; border-radius: 4px; border: 1px solid rgba(191, 219, 254, 0.8);">
                                    <?= htmlspecialchars(strtoupper(company_tagline())) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Front Content Body -->
                <div style="padding: 6px 14px 2px 14px; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: space-between; position: relative; z-index: 10;">
                    
                    <!-- 1. Official Passport Photograph -->
                    <div class="photo-wrapper">
                        <div class="photo-frame">
                            <div class="photo-corner corner-tl"></div>
                            <div class="photo-corner corner-tr"></div>
                            <div class="photo-corner corner-bl"></div>
                            <div class="photo-corner corner-br"></div>
                            
                            <?php 
                            $resolvedPhoto = !empty($card['photo_url']) ? resolve_photo_url($card['photo_url']) : null;
                            if (!empty($resolvedPhoto)): ?>
                                <img src="<?= htmlspecialchars($resolvedPhoto) ?>" alt="Photo" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            <?php else: ?>
                                <i class="bi bi-person-badge-fill" style="font-size: 26px; color: #94a3b8; margin-bottom: 2px;"></i>
                                <span style="font-size: 8px; font-weight: 800; color: #64748b; letter-spacing: 0.06em; text-transform: uppercase; line-height: 1;">OFFICIAL PHOTO</span>
                                <span style="font-size: 7px; font-weight: 600; color: #94a3b8; margin-top: 2px;">CR80 Compliant</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- 2. Scaled Name, Designation, Phone & Center-Aligned Address -->
                    <div style="text-align: center; width: 100%;">
                        <h3 style="font-size: 15px; font-weight: 900; color: #0f2d59; text-transform: uppercase; letter-spacing: -0.3px; line-height: 1.15; margin-bottom: 2px;">
                            <?= htmlspecialchars($fullName) ?>
                        </h3>
                        <div style="display: inline-block; padding: 2px 10px; background: rgba(224, 242, 254, 0.95); border-radius: 9999px; border: 1px solid #7dd3fc;">
                            <p style="font-size: 9px; font-weight: 800; color: #0369a1; text-transform: uppercase; letter-spacing: 0.06em; line-height: 1;">
                                <?= $designation ?>
                            </p>
                        </div>
                        <div style="margin-top: 2px; display: flex; align-items: center; justify-content: center; gap: 4px; color: #0f2d59;">
                            <i class="bi bi-telephone-fill" style="color: #0284c7; font-size: 12px;"></i>
                            <span class="font-mono-num" style="font-weight: 800; font-size: 14px; letter-spacing: -0.2px;">
                                <?= $mobileNumber ?>
                            </span>
                        </div>
                        <!-- Address just below mobile number, center aligned with 10px font size -->
                        <?php if (!empty($fullAddress)): ?>
                        <div style="margin-top: 2px; font-size: 10px; font-weight: 600; color: #334155; line-height: 1.25; text-align: center; padding: 0 2px;">
                            <i class="bi bi-geo-alt-fill text-danger me-1" style="font-size: 9px;"></i><?= htmlspecialchars($fullAddress) ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- 3. Badges Grid: ID Code & Blood Group -->
                    <div style="width: 100%;" class="grid-badge-box">
                        <div style="border-right: 1px solid #e2e8f0; padding-right: 4px; text-align: left;">
                            <span style="font-size: 8px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; display: block;">ID CODE</span>
                            <span class="font-mono-num" style="font-size: 13px; font-weight: 900; color: #0f2d59; letter-spacing: 0.3px; line-height: 1.1; display: block;">
                                <?= $cardCode ?>
                            </span>
                        </div>
                        <div style="padding-left: 4px; text-align: left;">
                            <span style="font-size: 8px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; display: block;">BLOOD GROUP</span>
                            <span style="font-size: 13px; font-weight: 900; color: #e11d48; display: flex; align-items: center; gap: 3px; line-height: 1.1;">
                                <i class="bi bi-droplet-fill" style="font-size: 11px;"></i> <?= htmlspecialchars($bloodGroup) ?>
                            </span>
                        </div>
                    </div>

                    <!-- 4. Jurisdiction Block -->
                    <div style="width: 100%; text-align: left; background: rgba(255, 255, 255, 0.95); padding: 4px 8px; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; align-items: flex-start; z-index: 20;">
                        <div style="width: 16px; height: 16px; border-radius: 4px; background: #e0f2fe; display: flex; align-items: center; justify-content: center; margin-right: 6px; flex-shrink: 0; color: #0284c7; margin-top: 1px;">
                            <i class="bi bi-geo-alt-fill" style="font-size: 9px;"></i>
                        </div>
                        <div style="flex: 1;">
                            <span style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; display: block;">ASSIGNED JURISDICTION:</span>
                            <span style="font-size: 12px; font-weight: 700; line-height: 1.25; color: #0f2d59;">
                                <?= $jurisdiction ?>
                            </span>
                        </div>
                    </div>

                </div>

                <!-- Flowing Wave Ribbon Banner (Front) -->
                <div class="bottom-wave-banner">
                    <svg style="width: 100%; height: 100%; display: block;" preserveAspectRatio="none" viewBox="0 0 320 48">
                        <defs>
                            <linearGradient id="cardFrontWaveGrad" x1="0%" x2="100%" y1="0%" y2="100%">
                                <stop offset="0%" stop-color="#0284c7"/>
                                <stop offset="45%" stop-color="#0f2d59"/>
                                <stop offset="78%" stop-color="#1e3a8a"/>
                                <stop offset="100%" stop-color="#0d9488"/>
                            </linearGradient>
                            <linearGradient id="cardFrontGoldPinstripe" x1="0%" x2="100%" y1="0%" y2="0%">
                                <stop offset="0%" stop-color="#38bdf8"/>
                                <stop offset="50%" stop-color="#f59e0b"/>
                                <stop offset="100%" stop-color="#fde047"/>
                            </linearGradient>
                        </defs>
                        <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12 L320,48 L0,48 Z" fill="url(#cardFrontWaveGrad)"/>
                        <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12" fill="none" stroke="url(#cardFrontGoldPinstripe)" stroke-linecap="round" stroke-width="2.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- ==================== BACK SIDE ==================== -->
        <div class="card-column">
            <span class="side-badge badge-back">Back Side • CR80 Portrait</span>
            
            <div class="id-card">
                <!-- Slot Punch Guide -->
                <div style="position: absolute; top: 6px; left: 50%; transform: translateX(-50%); z-index: 30;">
                    <div class="lanyard-slot"></div>
                </div>

                <!-- Photovoltaic Pattern Overlay -->
                <div class="solar-bg-pattern"></div>

                <!-- Top Header Matching Front -->
                <div class="card-header-full">
                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 4px;">
                        <?php if ($cLogo = company_logo_url()): ?>
                            <div style="max-width: 90px; max-height: 48px; display: flex; align-items: center; justify-content: center;">
                                <img src="<?= htmlspecialchars($cLogo) ?>" alt="Logo" style="max-height: 46px; max-width: 90px; object-fit: contain;">
                            </div>
                        <?php else: ?>
                            <div style="width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0f2d59 0%, #1e3a8a 100%); border-radius: 10px; color: #f59e0b; font-size: 1.6rem; font-weight: 900; box-shadow: 0 2px 8px rgba(15,45,89,0.25);">
                                ☀
                            </div>
                        <?php endif; ?>
                        <!-- Header Text & Tagline -->
                        <div style="flex: 1; min-width: 0; text-align: left;">
                            <div style="display: flex; align-items: baseline; gap: 4px; line-height: 1;">
                                <span style="font-size: 16px; font-weight: 900; color: #0f2d59; letter-spacing: -0.5px;"><?= htmlspecialchars(strtoupper(company_name())) ?></span>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 3px; line-height: 1;">
                                <h3 style="font-size: 10px; font-weight: 800; color: #0f2d59; text-transform: uppercase; letter-spacing: 0.5px;"><?= htmlspecialchars(company_promoter()) ?></h3>
                                <div style="display: flex; align-items: center; gap: 3px;">
                                    <span style="height: 5px; width: 10px; background: #0284c7; border-radius: 9999px;"></span>
                                    <span style="height: 5px; width: 14px; background: #ff7600; border-radius: 9999px;"></span>
                                </div>
                            </div>
                            <div style="margin-top: 3px; display: flex; align-items: center;">
                                <span style="font-size: 7.5px; font-weight: 900; color: #0f2d59; letter-spacing: 0.08em; text-transform: uppercase; background: #eff6ff; padding: 2px 6px; border-radius: 4px; border: 1px solid rgba(191, 219, 254, 0.8);">
                                    <?= htmlspecialchars(strtoupper(company_tagline())) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back Content Body -->
                <div style="padding: 8px 14px 4px 14px; flex: 1; display: flex; flex-direction: column; justify-content: space-between; text-align: left; position: relative; z-index: 10;">
                    
                    <!-- Company Information Block with QR -->
                    <div style="background: rgba(255, 255, 255, 0.95); border-radius: 8px; padding: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <div style="flex: 1; font-size: 9px; line-height: 1.35; color: #334155;">
                                <div style="margin-bottom: 2px;">
                                    <span style="font-weight: 900; color: #0f2d59; font-size: 8.5px; text-transform: uppercase;">HEAD OFFICE:</span>
                                    <span style="font-weight: 600;"><?= htmlspecialchars(company_address()) ?></span>
                                </div>
                                <div style="margin-bottom: 2px;">
                                    <span style="font-weight: 900; color: #0f2d59; font-size: 8.5px; text-transform: uppercase;">HELPLINE:</span>
                                    <span class="font-mono-num" style="font-weight: 900; color: #0f2d59;"><?= htmlspecialchars($emergencyContact) ?></span>
                                </div>
                                <div>
                                    <span style="font-weight: 900; color: #0f2d59; font-size: 8.5px; text-transform: uppercase;">SUPPORT:</span>
                                    <span style="font-weight: 700; color: #0891b2; font-size: 8px; word-break: break-all;"><?= htmlspecialchars(company_email()) ?></span>
                                </div>
                            </div>
                            
                            <!-- Digital QR Code -->
                            <div style="text-align: center; flex-shrink: 0; background: #f8fafc; padding: 4px; border-radius: 6px; border: 1px solid #cbd5e1;">
                                <img src="<?= $qrUrl ?>" alt="QR" style="width: 58px; height: 58px; display: block;">
                                <span style="font-size: 6px; font-weight: 700; color: #64748b; text-transform: uppercase;">Scan to Verify</span>
                            </div>
                        </div>

                        <div style="margin-top: 6px; padding-top: 4px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 8.5px; font-weight: 900; color: #0f2d59; text-transform: uppercase;">VALIDITY:</span>
                            <span class="font-mono-num" style="font-size: 9px; font-weight: 900; color: #0f2d59; background: #eff6ff; padding: 1px 6px; border-radius: 4px; border: 1px solid #bfdbfe;">
                                <?= $issueDate ?> TO <?= $validThru ?>
                            </span>
                        </div>
                    </div>

                    <!-- Instructions / Security Warning Box -->
                    <div style="background: rgba(254, 243, 199, 0.95); border-left: 3px solid #f59e0b; padding: 6px 8px; border-radius: 0 6px 6px 0; font-size: 8px; color: #451a03; line-height: 1.35;">
                        <p style="font-size: 8.5px; font-weight: 900; text-transform: uppercase; color: #78350f; margin-bottom: 2px;">
                            <i class="bi bi-shield-lock-fill me-1"></i> TERMS & CONDITIONS
                        </p>
                        <p style="font-weight: 600;">• This identity card is strictly non-transferable and remains company property.</p>
                        <p style="font-weight: 600; margin-top: 1px;">• If found, please return to <?= htmlspecialchars(company_name()) ?> Corporate Office.</p>
                    </div>

                    <!-- Signatures Section: Authorised Signatory Seal & Signature -->
                    <div style="padding-top: 4px; border-top: 1px solid #e2e8f0; display: flex; flex-direction: column; align-items: flex-end; justify-content: flex-end;">
                        <div style="text-align: center; min-width: 130px; display: flex; flex-direction: column; align-items: center;">
                            <img src="<?= htmlspecialchars(company_signature_url()) ?>" alt="Authorised Signatory" style="height: 38px; max-width: 135px; object-fit: contain; display: block;">
                        </div>
                    </div>

                </div>

                <!-- Flowing Wave Ribbon Banner (Back) -->
                <div class="bottom-wave-banner">
                    <svg style="width: 100%; height: 100%; display: block;" preserveAspectRatio="none" viewBox="0 0 320 48">
                        <defs>
                            <linearGradient id="cardBackWaveGrad" x1="0%" x2="100%" y1="0%" y2="100%">
                                <stop offset="0%" stop-color="#0284c7"/>
                                <stop offset="45%" stop-color="#0f2d59"/>
                                <stop offset="78%" stop-color="#1e3a8a"/>
                                <stop offset="100%" stop-color="#0d9488"/>
                            </linearGradient>
                            <linearGradient id="cardBackGoldPinstripe" x1="0%" x2="100%" y1="0%" y2="0%">
                                <stop offset="0%" stop-color="#38bdf8"/>
                                <stop offset="50%" stop-color="#f59e0b"/>
                                <stop offset="100%" stop-color="#fde047"/>
                            </linearGradient>
                        </defs>
                        <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12 L320,48 L0,48 Z" fill="url(#cardBackWaveGrad)"/>
                        <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12" fill="none" stroke="url(#cardBackGoldPinstripe)" stroke-linecap="round" stroke-width="2.5"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    <!-- Print Specification Note -->
    <div class="print-specification-note" style="margin-top: 24px; text-align: center; font-size: 0.75rem; color: #64748b;">
        <strong>Print Specification:</strong> CR80 Standard (85.6 mm × 54 mm) • 300 DPI Duplex PVC Card Printing • Full-Width Header & Scaled Typography
    </div>

</body>
</html>
