<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India (P) Ltd.
 * Official CR80 Duplex Printable Advisor ID Card
 * Design: Full-Width Header & Scaled Typography (Stitch Project 8666082930423354696)
 */
$bloodGroup = !empty($advisor['blood_group']) ? $advisor['blood_group'] : 'O+ve';
$fullName = trim($advisor['first_name'] . ' ' . $advisor['last_name']);
$mobileNumbers = htmlspecialchars($advisor['mobile']) . (!empty($advisor['alt_mobile']) ? ' / ' . htmlspecialchars($advisor['alt_mobile']) : '');
$fullAddress = !empty($advisor['address_line']) 
    ? $advisor['address_line'] 
    : trim(($advisor['village'] ? $advisor['village'] . ', ' : '') . ($advisor['block'] ?? '') . ', ' . ($advisor['district'] ?? '') . ' – ' . ($advisor['pincode'] ?? ''));
$jurisdiction = !empty($advisor['jurisdiction']) 
    ? $advisor['jurisdiction'] 
    : trim((!empty($advisor['block']) ? $advisor['block'] . ' Block, ' : '') . (!empty($advisor['district']) ? $advisor['district'] . ' District' : ''));
if (empty($jurisdiction)) {
    $jurisdiction = 'All Odisha / Headquarters';
}
$qrUrl = !empty($qrUrl) ? $qrUrl : 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode(function_exists('url') ? url('/verify/advisor/' . ($advisor['advisor_code'] ?? '')) : 'https://suryavistaara.com/verify/' . ($advisor['advisor_code'] ?? ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Solar Advisor ID Card - <?= htmlspecialchars($advisor['advisor_code']) ?></title>
    
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
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 4px 12px;
            border-radius: 6px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.06);
        }
        .badge-front { background: #0f2d59; color: #ffffff; }
        .badge-back { background: #065f46; color: #ffffff; }

        /* CR80 Standard Proportions: 54mm x 85.6mm (Aspect Ratio approx 1 : 1.585) */
        .id-card {
            width: 320px;
            height: 507px;
            position: relative;
            overflow: hidden;
            border-radius: 14px;
            box-shadow: 0 16px 36px -8px rgba(15, 23, 42, 0.2), 0 0 0 1px rgba(15, 23, 42, 0.08);
            background: #FFFFFF;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            user-select: none;
        }

        .lanyard-slot {
            width: 44px;
            height: 5.5px;
            background: #cbd5e1;
            border-radius: 9999px;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.25);
        }

        .solar-bg-pattern {
            position: absolute;
            inset: 0;
            z-index: 0;
            background: 
                linear-gradient(135deg, rgba(15,45,89,0.03) 0%, rgba(245,158,11,0.03) 100%),
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(15,45,89,0.015) 10px, rgba(15,45,89,0.015) 20px);
            pointer-events: none;
        }

        /* Full Width Header */
        .card-header-full {
            position: relative;
            z-index: 10;
            padding: 16px 14px 8px 14px;
            background: linear-gradient(180deg, #ffffff 0%, rgba(255,255,255,0.95) 80%, rgba(255,255,255,0.7) 100%);
            border-bottom: 1px solid rgba(224, 231, 255, 0.7);
        }

        /* Photo Box */
        .photo-frame {
            width: 90px;
            height: 104px;
            border-radius: 10px;
            border: 2px dashed rgba(15, 45, 89, 0.35);
            background: rgba(255, 255, 255, 0.95);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }
        .photo-corner {
            position: absolute;
            width: 8px;
            height: 8px;
            border-color: #0f2d59;
            border-style: solid;
        }
        .corner-tl { top: 3px; left: 3px; border-width: 2px 0 0 2px; }
        .corner-tr { top: 3px; right: 3px; border-width: 2px 2px 0 0; }
        .corner-bl { bottom: 3px; left: 3px; border-width: 0 0 2px 2px; }
        .corner-br { bottom: 3px; right: 3px; border-width: 0 2px 2px 0; }

        /* Grid Badge */
        .grid-badge-box {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 6px 8px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        /* Wave SVG at bottom */
        .bottom-wave-banner {
            width: 100%;
            height: 48px;
            position: relative;
            z-index: 20;
            overflow: hidden;
            margin-top: auto;
            margin-bottom: -1px;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
                margin: 0;
            }
            .no-print-bar {
                display: none !important;
            }
            .cards-wrapper {
                gap: 20mm;
            }
            .id-card {
                box-shadow: none;
                border: 1px solid #cbd5e1;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Bar (Screen Only) -->
    <div class="no-print-bar">
        <div>
            <div style="font-weight: 800; color: #0f2d59; font-size: 1.05rem;">Dhwajja Solar India — Official Advisor ID Card</div>
            <div style="font-size: 0.78rem; color: #64748b;">Standard CR80 54 × 85.6 mm • Double Sided PVC Card Print Layout</div>
        </div>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn-print">
                <i class="bi bi-printer-fill"></i> Print ID Card (Duplex)
            </button>
        </div>
    </div>

    <!-- Cards Wrapper -->
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

                <!-- Top Full-Width Header -->
                <div class="card-header-full">
                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 4px;">
                        <?php if ($cLogo = company_logo_url()): ?>
                            <div style="max-width: 90px; max-height: 48px; display: flex; align-items: center; justify-content: center;">
                                <img src="<?= htmlspecialchars($cLogo) ?>" alt="Logo" style="max-height: 46px; max-width: 90px; object-fit: contain;">
                            </div>
                        <?php else: ?>
                            <!-- Logo Mark -->
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

                <!-- Front Side Body -->
                <div style="padding: 4px 14px 2px 14px; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: space-between; position: relative; z-index: 10;">
                    
                    <!-- 1. Photo Frame / Photo Display -->
                    <div style="margin: 1px 0;">
                        <div class="photo-frame">
                            <div class="photo-corner corner-tl"></div>
                            <div class="photo-corner corner-tr"></div>
                            <div class="photo-corner corner-bl"></div>
                            <div class="photo-corner corner-br"></div>
                            
                            <?php 
                            $resolvedPhoto = !empty($advisor['photo_url']) ? resolve_photo_url($advisor['photo_url']) : null;
                            if (!empty($resolvedPhoto)): ?>
                                <img src="<?= htmlspecialchars($resolvedPhoto) ?>" alt="Advisor Photo" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            <?php else: ?>
                                <i class="bi bi-person-fill" style="font-size: 26px; color: #94a3b8; margin-bottom: 2px;"></i>
                                <span style="font-size: 8px; font-weight: 800; color: #64748b; letter-spacing: 0.06em; text-transform: uppercase; line-height: 1;">PASTE PHOTO</span>
                                <span style="font-size: 7px; font-weight: 600; color: #94a3b8; margin-top: 2px;">CR80 Compliant</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- 2. Scaled Name, Designation, Phone & Center-Aligned Address -->
                    <div style="text-align: center; width: 100%;">
                        <h3 style="font-size: 15px; font-weight: 900; color: #0f2d59; text-transform: uppercase; letter-spacing: -0.3px; line-height: 1.15; margin-bottom: 2px;">
                            <?= htmlspecialchars($fullName) ?>
                        </h3>
                        <div style="display: inline-block; padding: 2px 10px; background: rgba(209, 250, 229, 0.9); border-radius: 9999px; border: 1px solid #6ee7b7;">
                            <p style="font-size: 9px; font-weight: 800; color: #065f46; text-transform: uppercase; letter-spacing: 0.06em; line-height: 1;">
                                CERTIFIED SOLAR ADVISOR
                            </p>
                        </div>
                        <div style="margin-top: 2px; display: flex; align-items: center; justify-content: center; gap: 4px; color: #0f2d59;">
                            <i class="bi bi-telephone-fill" style="color: #047857; font-size: 12px;"></i>
                            <span class="font-mono-num" style="font-weight: 800; font-size: 14px; letter-spacing: -0.2px;">
                                <?= $mobileNumbers ?>
                            </span>
                        </div>
                        <!-- Address just below mobile number, center aligned with 10px font size -->
                        <?php if (!empty($fullAddress)): ?>
                        <div style="margin-top: 2px; font-size: 10px; font-weight: 600; color: #334155; line-height: 1.25; text-align: center; padding: 0 2px;">
                            <i class="bi bi-geo-alt-fill text-danger me-1" style="font-size: 9px;"></i><?= htmlspecialchars($fullAddress) ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- 3. Badges Grid: Employee ID & Blood Group -->
                    <div style="width: 100%;" class="grid-badge-box">
                        <div style="border-right: 1px solid #e2e8f0; padding-right: 4px; text-align: left;">
                            <span style="font-size: 8px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; display: block;">ADVISOR ID</span>
                            <span class="font-mono-num" style="font-size: 13px; font-weight: 900; color: #0f2d59; letter-spacing: 0.3px; line-height: 1.1; display: block;">
                                <?= htmlspecialchars($advisor['advisor_code']) ?>
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
                                <?= htmlspecialchars($jurisdiction) ?>
                            </span>
                        </div>
                    </div>

                </div>

                <!-- Flowing Wave Ribbon Banner (Front) -->
                <div class="bottom-wave-banner">
                    <svg style="width: 100%; height: 100%; display: block;" preserveAspectRatio="none" viewBox="0 0 320 48">
                        <defs>
                            <linearGradient id="frontWaveGrad" x1="0%" x2="100%" y1="0%" y2="100%">
                                <stop offset="0%" stop-color="#0097b2"/>
                                <stop offset="45%" stop-color="#0284c7"/>
                                <stop offset="78%" stop-color="#0d9488"/>
                                <stop offset="100%" stop-color="#0f766e"/>
                            </linearGradient>
                            <linearGradient id="frontGoldPinstripe" x1="0%" x2="100%" y1="0%" y2="0%">
                                <stop offset="0%" stop-color="#fef08a"/>
                                <stop offset="35%" stop-color="#f59e0b"/>
                                <stop offset="70%" stop-color="#fbbf24"/>
                                <stop offset="100%" stop-color="#fde047"/>
                            </linearGradient>
                        </defs>
                        <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12 L320,48 L0,48 Z" fill="url(#frontWaveGrad)"/>
                        <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12" fill="none" stroke="url(#frontGoldPinstripe)" stroke-linecap="round" stroke-width="2.5"/>
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
                                    <span style="font-weight: 900; color: #0f2d59; font-size: 8.5px; text-transform: uppercase;">OFFICE:</span>
                                    <span style="font-weight: 600;"><?= htmlspecialchars(company_address()) ?></span>
                                </div>
                                <div style="margin-bottom: 2px;">
                                    <span style="font-weight: 900; color: #0f2d59; font-size: 8.5px; text-transform: uppercase;">PHONE:</span>
                                    <span class="font-mono-num" style="font-weight: 900; color: #0f2d59;"><?= htmlspecialchars(company_phone()) ?></span>
                                </div>
                                <div>
                                    <span style="font-weight: 900; color: #0f2d59; font-size: 8.5px; text-transform: uppercase;">EMAIL:</span>
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
                            <span style="font-size: 8.5px; font-weight: 900; color: #0f2d59; text-transform: uppercase;">GSTIN:</span>
                            <span class="font-mono-num" style="font-size: 9.5px; font-weight: 900; color: #0f2d59; background: #eff6ff; padding: 1px 6px; border-radius: 4px; border: 1px solid #bfdbfe;">
                                <?= htmlspecialchars(company_gstin()) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Instructions / Emergency Notice Box -->
                    <div style="background: rgba(254, 243, 199, 0.95); border-left: 3px solid #f59e0b; padding: 6px 8px; border-radius: 0 6px 6px 0; font-size: 8px; color: #451a03; line-height: 1.35;">
                        <p style="font-size: 8.5px; font-weight: 900; text-transform: uppercase; color: #78350f; margin-bottom: 2px;">
                            <i class="bi bi-info-circle-fill me-1"></i> IMPORTANT INSTRUCTIONS
                        </p>
                        <p style="font-weight: 600;">• In case of emergency or project query, contact the helpline above.</p>
                        <p style="font-weight: 600; margin-top: 1px;">• If found, please return to Dhwajja Solar India / SVPL.</p>
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
                            <linearGradient id="backWaveGrad" x1="0%" x2="100%" y1="0%" y2="100%">
                                <stop offset="0%" stop-color="#0097b2"/>
                                <stop offset="45%" stop-color="#0284c7"/>
                                <stop offset="78%" stop-color="#0d9488"/>
                                <stop offset="100%" stop-color="#0f766e"/>
                            </linearGradient>
                            <linearGradient id="backGoldPinstripe" x1="0%" x2="100%" y1="0%" y2="0%">
                                <stop offset="0%" stop-color="#fef08a"/>
                                <stop offset="35%" stop-color="#f59e0b"/>
                                <stop offset="70%" stop-color="#fbbf24"/>
                                <stop offset="100%" stop-color="#fde047"/>
                            </linearGradient>
                        </defs>
                        <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12 L320,48 L0,48 Z" fill="url(#backWaveGrad)"/>
                        <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12" fill="none" stroke="url(#backGoldPinstripe)" stroke-linecap="round" stroke-width="2.5"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    <!-- Print Specification Note -->
    <div style="margin-top: 24px; text-align: center; font-size: 0.75rem; color: #64748b;">
        <strong>Print Specification:</strong> CR80 Standard (85.6 mm × 54 mm) • 300 DPI Duplex PVC Card Printing • Full-Width Header & Scaled Typography
    </div>

</body>
</html>
