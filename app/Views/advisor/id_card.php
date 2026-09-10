<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Official ID Card Preview & Print (Stitch Project 8666082930423354696 Design)
 */
$title = "Official ID Card — SVPL Advisor";
$bloodGroup = !empty($advisor['blood_group']) ? $advisor['blood_group'] : 'O+ve';
$fullName = trim($advisor['first_name'] . ' ' . $advisor['last_name']);
$mobileNumbers = htmlspecialchars($advisor['mobile']) . (!empty($advisor['alt_mobile']) ? ' / ' . htmlspecialchars($advisor['alt_mobile']) : '');
$fullAddress = !empty($advisor['address_line']) 
    ? $advisor['address_line'] 
    : trim(($advisor['village'] ? $advisor['village'] . ', ' : '') . ($advisor['block'] ?? '') . ', ' . ($advisor['district'] ?? '') . ' – ' . ($advisor['pincode'] ?? ''));
$qrUrl = !empty($qrUrl) ? $qrUrl : 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode(function_exists('url') ? url('/verify/advisor/' . ($advisor['advisor_code'] ?? '')) : 'https://suryavistaara.com/verify/' . ($advisor['advisor_code'] ?? ''));
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-navy text-warning fw-bold px-2 py-1">
                <i class="bi bi-person-badge-fill me-1"></i> Authorized Credentials
            </span>
            <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold">
                CR80 Smart PVC Card
            </span>
        </div>
        <h3 class="fw-bold mb-0 text-navy font-heading">My Official Solar Advisor Identity Card</h3>
        <p class="text-secondary small mb-0">Authorized Corporate Promoter for Dhwajja Solar India Pvt. Ltd. across Odisha</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= url('/print/id-card/' . $advisor['id']) ?>" target="_blank" class="btn btn-svpl-solar btn-sm shadow-sm fw-bold px-3">
            <i class="bi bi-printer-fill me-1"></i> Print / Download Duplex ID Card
        </a>
    </div>
</div>

<div class="row justify-content-center g-4 mb-4">
    <!-- FRONT SIDE PREVIEW -->
    <div class="col-auto">
        <div class="text-center mb-2">
            <span class="badge bg-navy text-white px-3 py-1 font-monospace" style="font-size: 0.72rem;">FRONT SIDE • CR80 PORTRAIT</span>
        </div>
        
        <div class="card border-0 shadow-lg" style="width: 320px; height: 507px; border-radius: 14px; overflow: hidden; position: relative; background: #FFFFFF; display: flex; flex-direction: column; justify-content: space-between;">
            
            <!-- Slot Punch Guide -->
            <div style="position: absolute; top: 6px; left: 50%; transform: translateX(-50%); z-index: 30;">
                <div style="width: 44px; height: 5.5px; background: #cbd5e1; border-radius: 9999px; box-shadow: inset 0 1px 2px rgba(0,0,0,0.25);"></div>
            </div>

            <!-- Top Header -->
            <div style="position: relative; z-index: 10; padding: 16px 14px 8px 14px; background: linear-gradient(180deg, #ffffff 0%, rgba(255,255,255,0.95) 80%, rgba(255,255,255,0.7) 100%); border-bottom: 1px solid rgba(224, 231, 255, 0.7);">
                <div style="display: flex; align-items: center; gap: 10px; margin-top: 4px;">
                    <div style="width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0f2d59 0%, #1e3a8a 100%); border-radius: 10px; color: #f59e0b; font-size: 1.6rem; font-weight: 900; box-shadow: 0 2px 8px rgba(15,45,89,0.25);">
                        ☀
                    </div>
                    <div style="flex: 1; min-width: 0; text-align: left;">
                        <div style="display: flex; align-items: baseline; gap: 4px; line-height: 1;">
                            <span style="font-size: 18px; font-weight: 900; color: #0f2d59; letter-spacing: -0.5px;">DHWAJJA</span>
                            <span style="font-size: 18px; font-weight: 900; color: #f59e0b; letter-spacing: -0.5px;">SOLAR</span>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 3px; line-height: 1;">
                            <h3 style="font-size: 11.5px; font-weight: 800; color: #0f2d59; text-transform: uppercase; letter-spacing: 0.5px;">INDIA (P) LTD.</h3>
                            <div style="display: flex; align-items: center; gap: 3px;">
                                <span style="height: 5px; width: 10px; background: #f59e0b; border-radius: 9999px;"></span>
                                <span style="height: 5px; width: 14px; background: #10b981; border-radius: 9999px;"></span>
                            </div>
                        </div>
                        <div style="margin-top: 3px; display: flex; align-items: center;">
                            <span style="font-size: 7.5px; font-weight: 900; color: #065f46; letter-spacing: 0.08em; text-transform: uppercase; background: #ecfdf5; padding: 2px 6px; border-radius: 4px; border: 1px solid rgba(167, 243, 208, 0.8);">
                                AUTHORIZED CORPORATE PROMOTER: SVPL
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div style="padding: 6px 14px 2px 14px; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: space-between; position: relative; z-index: 10;">
                
                <!-- Photo -->
                <div style="margin: 2px 0;">
                    <div style="width: 96px; height: 112px; border-radius: 10px; border: 2px dashed rgba(15, 45, 89, 0.35); background: rgba(255, 255, 255, 0.95); position: relative; overflow: hidden; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <?php if (!empty($advisor['photo_url'])): ?>
                            <img src="<?= asset($advisor['photo_url']) ?>" alt="Photo" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <i class="bi bi-person-fill" style="font-size: 28px; color: #94a3b8; margin-bottom: 2px;"></i>
                            <span style="font-size: 8px; font-weight: 800; color: #64748b; letter-spacing: 0.06em; text-transform: uppercase; line-height: 1;">PASTE PHOTO</span>
                            <span style="font-size: 7px; font-weight: 600; color: #94a3b8; margin-top: 2px;">CR80 Compliant</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Scaled Typography: Name, Designation & Phone -->
                <div style="text-align: center; width: 100%;">
                    <h3 style="font-size: 17px; font-weight: 900; color: #0f2d59; text-transform: uppercase; letter-spacing: -0.3px; line-height: 1.15; margin-bottom: 3px;">
                        <?= htmlspecialchars($fullName) ?>
                    </h3>
                    <div style="display: inline-block; padding: 3px 12px; background: rgba(209, 250, 229, 0.9); border-radius: 9999px; border: 1px solid #6ee7b7;">
                        <p style="font-size: 10px; font-weight: 800; color: #065f46; text-transform: uppercase; letter-spacing: 0.06em; line-height: 1; margin-bottom: 0;">
                            CERTIFIED SOLAR ADVISOR
                        </p>
                    </div>
                    <div style="margin-top: 4px; display: flex; align-items: center; justify-content: center; gap: 5px; color: #0f2d59;">
                        <i class="bi bi-telephone-fill" style="color: #047857; font-size: 11px;"></i>
                        <span style="font-weight: 800; font-size: 11.5px; font-family: monospace; letter-spacing: -0.2px;">
                            <?= $mobileNumbers ?>
                        </span>
                    </div>
                </div>

                <!-- Grid Badges -->
                <div style="width: 100%; background: rgba(255, 255, 255, 0.95); border: 1px solid #cbd5e1; border-radius: 8px; padding: 6px 8px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div style="border-right: 1px solid #e2e8f0; padding-right: 4px; text-align: left;">
                        <span style="font-size: 8px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; display: block;">ADVISOR ID</span>
                        <span style="font-size: 13.5px; font-weight: 900; color: #0f2d59; font-family: monospace; line-height: 1.1; display: block;">
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

                <!-- Address Block -->
                <div style="width: 100%; text-align: left; background: rgba(255, 255, 255, 0.95); padding: 5px 8px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: flex-start; z-index: 20;">
                    <div style="width: 16px; height: 16px; border-radius: 4px; background: #fef3c7; display: flex; align-items: center; justify-content: center; margin-right: 6px; flex-shrink: 0; color: #b45309; margin-top: 1px;">
                        <i class="bi bi-geo-alt-fill" style="font-size: 9px;"></i>
                    </div>
                    <span style="font-size: 8.5px; font-weight: 700; line-height: 1.25; color: #1e293b;">
                        <?= htmlspecialchars($fullAddress) ?>
                    </span>
                </div>

            </div>

            <!-- Wave Banner -->
            <div style="width: 100%; height: 48px; position: relative; z-index: 20; overflow: hidden; margin-top: auto; margin-bottom: -1px;">
                <svg style="width: 100%; height: 100%; display: block;" preserveAspectRatio="none" viewBox="0 0 320 48">
                    <defs>
                        <linearGradient id="prevFrontWaveGrad" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop offset="0%" stop-color="#0097b2"/>
                            <stop offset="45%" stop-color="#0284c7"/>
                            <stop offset="78%" stop-color="#0d9488"/>
                            <stop offset="100%" stop-color="#0f766e"/>
                        </linearGradient>
                        <linearGradient id="prevFrontGoldPinstripe" x1="0%" x2="100%" y1="0%" y2="0%">
                            <stop offset="0%" stop-color="#fef08a"/>
                            <stop offset="35%" stop-color="#f59e0b"/>
                            <stop offset="70%" stop-color="#fbbf24"/>
                            <stop offset="100%" stop-color="#fde047"/>
                        </linearGradient>
                    </defs>
                    <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12 L320,48 L0,48 Z" fill="url(#prevFrontWaveGrad)"/>
                    <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12" fill="none" stroke="url(#prevFrontGoldPinstripe)" stroke-linecap="round" stroke-width="2.5"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- BACK SIDE PREVIEW -->
    <div class="col-auto">
        <div class="text-center mb-2">
            <span class="badge bg-emerald-800 text-white px-3 py-1 font-monospace" style="background: #065f46; font-size: 0.72rem;">BACK SIDE • CR80 PORTRAIT</span>
        </div>
        
        <div class="card border-0 shadow-lg" style="width: 320px; height: 507px; border-radius: 14px; overflow: hidden; position: relative; background: #FFFFFF; display: flex; flex-direction: column; justify-content: space-between;">
            
            <!-- Slot Punch Guide -->
            <div style="position: absolute; top: 6px; left: 50%; transform: translateX(-50%); z-index: 30;">
                <div style="width: 44px; height: 5.5px; background: #cbd5e1; border-radius: 9999px; box-shadow: inset 0 1px 2px rgba(0,0,0,0.25);"></div>
            </div>

            <!-- Top Header -->
            <div style="position: relative; z-index: 10; padding: 16px 14px 8px 14px; background: linear-gradient(180deg, #ffffff 0%, rgba(255,255,255,0.95) 80%, rgba(255,255,255,0.7) 100%); border-bottom: 1px solid rgba(224, 231, 255, 0.7);">
                <div style="display: flex; align-items: center; gap: 10px; margin-top: 4px;">
                    <div style="width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0f2d59 0%, #1e3a8a 100%); border-radius: 10px; color: #f59e0b; font-size: 1.6rem; font-weight: 900; box-shadow: 0 2px 8px rgba(15,45,89,0.25);">
                        ☀
                    </div>
                    <div style="flex: 1; min-width: 0; text-align: left;">
                        <div style="display: flex; align-items: baseline; gap: 4px; line-height: 1;">
                            <span style="font-size: 18px; font-weight: 900; color: #0f2d59; letter-spacing: -0.5px;">DHWAJJA</span>
                            <span style="font-size: 18px; font-weight: 900; color: #f59e0b; letter-spacing: -0.5px;">SOLAR</span>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 3px; line-height: 1;">
                            <h3 style="font-size: 11.5px; font-weight: 800; color: #0f2d59; text-transform: uppercase; letter-spacing: 0.5px;">INDIA (P) LTD.</h3>
                            <div style="display: flex; align-items: center; gap: 3px;">
                                <span style="height: 5px; width: 10px; background: #f59e0b; border-radius: 9999px;"></span>
                                <span style="height: 5px; width: 14px; background: #10b981; border-radius: 9999px;"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Content Body -->
            <div style="padding: 8px 14px 4px 14px; flex: 1; display: flex; flex-direction: column; justify-content: space-between; text-align: left; position: relative; z-index: 10;">
                
                <!-- Company Info with QR -->
                <div style="background: rgba(255, 255, 255, 0.95); border-radius: 8px; padding: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <div style="flex: 1; font-size: 9px; line-height: 1.35; color: #334155;">
                            <div style="margin-bottom: 2px;">
                                <span style="font-weight: 900; color: #0f2d59; font-size: 8.5px; text-transform: uppercase;">OFFICE:</span>
                                <span style="font-weight: 600;">MIG-84, Pokhariput, BDA Colony, Phase-1, Bhubaneswar, Khorda – 751020, Odisha</span>
                            </div>
                            <div style="margin-bottom: 2px;">
                                <span style="font-weight: 900; color: #0f2d59; font-size: 8.5px; text-transform: uppercase;">PHONE:</span>
                                <span style="font-weight: 900; color: #0f2d59; font-family: monospace;">9040999899</span>
                            </div>
                            <div>
                                <span style="font-weight: 900; color: #0f2d59; font-size: 8.5px; text-transform: uppercase;">EMAIL:</span>
                                <span style="font-weight: 700; color: #0891b2; font-size: 8px; word-break: break-all;">dhwajasolaruserservices@gmail.com</span>
                            </div>
                        </div>
                        
                        <!-- QR Code -->
                        <div style="text-align: center; flex-shrink: 0; background: #f8fafc; padding: 4px; border-radius: 6px; border: 1px solid #cbd5e1;">
                            <img src="<?= $qrUrl ?>" alt="QR" style="width: 58px; height: 58px; display: block;">
                            <span style="font-size: 6px; font-weight: 700; color: #64748b; text-transform: uppercase;">Scan to Verify</span>
                        </div>
                    </div>

                    <div style="margin-top: 6px; padding-top: 4px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 8.5px; font-weight: 900; color: #0f2d59; text-transform: uppercase;">GSTIN:</span>
                        <span style="font-size: 9.5px; font-weight: 900; color: #0f2d59; font-family: monospace; background: #eff6ff; padding: 1px 6px; border-radius: 4px; border: 1px solid #bfdbfe;">
                            21AAMCD5948B1ZU
                        </span>
                    </div>
                </div>

                <!-- Instructions Box -->
                <div style="background: rgba(254, 243, 199, 0.95); border-left: 3px solid #f59e0b; padding: 6px 8px; border-radius: 0 6px 6px 0; font-size: 8px; color: #451a03; line-height: 1.35;">
                    <p style="font-size: 8.5px; font-weight: 900; text-transform: uppercase; color: #78350f; margin-bottom: 2px;">
                        <i class="bi bi-info-circle-fill me-1"></i> IMPORTANT INSTRUCTIONS
                    </p>
                    <p style="font-weight: 600; margin-bottom: 0;">• In case of emergency or project query, contact the helpline above.</p>
                    <p style="font-weight: 600; margin-top: 1px; margin-bottom: 0;">• If found, please return to Dhwajja Solar India / SVPL.</p>
                </div>

                <!-- Signatory -->
                <div style="padding-top: 4px; border-top: 1px solid #e2e8f0; display: flex; flex-direction: column; align-items: flex-end; justify-content: flex-end;">
                    <div style="width: 120px; text-align: center;">
                        <div style="font-family: cursive; font-size: 13px; font-weight: 800; color: #0f2d59; transform: rotate(-3deg); margin-bottom: 2px; letter-spacing: 0.5px;">
                            M. Biswal
                        </div>
                        <div style="height: 1px; border-bottom: 1.5px dashed #94a3b8; width: 100%; margin-bottom: 2px;"></div>
                        <span style="font-size: 8px; font-weight: 900; color: #0f2d59; text-transform: uppercase; letter-spacing: 0.05em; display: block;">
                            AUTHORISED SIGNATORY
                        </span>
                    </div>
                </div>

            </div>

            <!-- Wave Banner -->
            <div style="width: 100%; height: 48px; position: relative; z-index: 20; overflow: hidden; margin-top: auto; margin-bottom: -1px;">
                <svg style="width: 100%; height: 100%; display: block;" preserveAspectRatio="none" viewBox="0 0 320 48">
                    <defs>
                        <linearGradient id="prevBackWaveGrad" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop offset="0%" stop-color="#0097b2"/>
                            <stop offset="45%" stop-color="#0284c7"/>
                            <stop offset="78%" stop-color="#0d9488"/>
                            <stop offset="100%" stop-color="#0f766e"/>
                        </linearGradient>
                        <linearGradient id="prevBackGoldPinstripe" x1="0%" x2="100%" y1="0%" y2="0%">
                            <stop offset="0%" stop-color="#fef08a"/>
                            <stop offset="35%" stop-color="#f59e0b"/>
                            <stop offset="70%" stop-color="#fbbf24"/>
                            <stop offset="100%" stop-color="#fde047"/>
                        </linearGradient>
                    </defs>
                    <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12 L320,48 L0,48 Z" fill="url(#prevBackWaveGrad)"/>
                    <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12" fill="none" stroke="url(#prevBackGoldPinstripe)" stroke-linecap="round" stroke-width="2.5"/>
                </svg>
            </div>
        </div>
    </div>
</div>
