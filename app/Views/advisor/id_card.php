<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Official ID Card Preview & Print (Exact Match to Gold Standard BOE ID Card Design)
 */
$title = "Official ID Card — SVPL Advisor";
$bloodGroup = !empty($advisor['blood_group']) ? $advisor['blood_group'] : 'O+ve';
$fullName = trim($advisor['first_name'] . ' ' . $advisor['last_name']);
$mobileNumbers = htmlspecialchars($advisor['mobile']) . (!empty($advisor['alt_mobile']) ? ' / ' . htmlspecialchars($advisor['alt_mobile']) : '');
$fullAddress = !empty($advisor['address_line']) 
    ? htmlspecialchars($advisor['address_line']) 
    : trim(($advisor['village'] ? $advisor['village'] . ', ' : '') . ($advisor['block'] ?? '') . ', ' . ($advisor['district'] ?? '') . ' – ' . ($advisor['pincode'] ?? ''));
if (empty($fullAddress)) {
    $fullAddress = 'MIG-84, Pokhariput, BDA Colony, Phase-1, Bhubaneswar, Khorda – 751020, Odisha';
}
$jurisdiction = !empty($advisor['jurisdiction']) 
    ? htmlspecialchars($advisor['jurisdiction']) 
    : trim((!empty($advisor['block']) ? $advisor['block'] . ' Block, ' : '') . (!empty($advisor['district']) ? $advisor['district'] . ' District' : ''));
if (empty($jurisdiction)) {
    $jurisdiction = 'Headquarters / All Odisha';
}
$advisorCode = htmlspecialchars($advisor['advisor_code'] ?? 'ADV-SVPL-101');
$qrUrl = !empty($qrUrl) ? $qrUrl : ('https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode(id_card_verify_url('ADVISOR', $advisorCode)));
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
        <p class="text-secondary small mb-0">Authorized Solar Advisor for <?= htmlspecialchars(company_name()) ?></p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= url('/print/id-card/' . $advisor['id']) ?>" target="_blank" class="btn btn-svpl-solar btn-sm shadow-sm fw-bold px-3">
            <i class="bi bi-printer-fill me-1"></i> Print / Download Duplex ID Card
        </a>
    </div>
</div>

<style>
.font-mono-num {
    font-family: 'Space Grotesk', monospace, sans-serif;
}
.preview-id-card {
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
.preview-lanyard-slot {
    width: 44px;
    height: 5.5px;
    background: #cbd5e1;
    border-radius: 9999px;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.25);
}
.preview-solar-bg-pattern {
    position: absolute;
    inset: 0;
    z-index: 0;
    background: 
        linear-gradient(135deg, rgba(15,45,89,0.03) 0%, rgba(14,165,233,0.03) 100%),
        repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(15,45,89,0.015) 10px, rgba(15,45,89,0.015) 20px);
    pointer-events: none;
}
.preview-card-header-full {
    position: relative;
    z-index: 10;
    padding: 16px 14px 8px 14px;
    background: linear-gradient(180deg, #ffffff 0%, rgba(255,255,255,0.95) 80%, rgba(255,255,255,0.7) 100%);
    border-bottom: 1px solid rgba(255, 98, 0, 0.7);
}
.preview-photo-frame {
    width: 120px;
    height: 144px;
    border-radius: 10px;
    border: 2px dashed rgba(15, 45, 89, 0.35);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
}
.preview-photo-corner {
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

.preview-grid-badge-box {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 6px 8px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.preview-bottom-wave-banner {
    width: 100%;
    height: 68px;
    position: relative;
    z-index: 20;
    overflow: hidden;
    margin-top: auto;
    margin-bottom: -1px;
}
</style>

<div class="row justify-content-center g-4 mb-4">
    <!-- FRONT SIDE PREVIEW -->
    <div class="col-auto">
        <div class="text-center mb-2">
            <span class="badge bg-navy text-white px-3 py-1 font-monospace" style="font-size: 0.72rem;">FRONT SIDE • CR80 PORTRAIT</span>
        </div>
        
        <div class="preview-id-card">
            <!-- Slot Punch Guide -->
            <div style="position: absolute; top: 6px; left: 50%; transform: translateX(-50%); z-index: 30;">
                <div class="preview-lanyard-slot"></div>
            </div>

            <!-- Photovoltaic Pattern Overlay -->
            <div class="preview-solar-bg-pattern"></div>

            <!-- Top Header -->
            <div class="preview-card-header-full">
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
                    <div style="flex: 1; min-width: 0; text-align: center; align-items: center;">
                        <div style="display: flex; align-items: baseline; gap: 4px; line-height: 1;">
                            <span style="font-size: 16px; font-weight: 900; color: #0f2d59; letter-spacing: -0.5px;"><?= htmlspecialchars(strtoupper(company_name())) ?></span>
                        </div>
                        <div style="margin-top: 3px; display: flex; align-items: center;">
                            <span style="font-size: 8px; font-weight: 900; color: #0f2d59; letter-spacing: 0.08em; text-transform: uppercase; background: #eff6ff; padding: 2px 6px; border-radius: 4px; border: 1px solid rgba(191, 219, 254, 0.8);">
                                <?= htmlspecialchars(strtoupper(company_tagline())) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Front Body -->
            <div style="padding: 4px 14px 2px 14px; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: space-between; position: relative; z-index: 10;">
                
                <!-- 1. Photo Frame -->
                <div style="margin: 1px 0;">
                    <div class="preview-photo-frame">
                        <div class="preview-photo-corner corner-tl"></div>
                        <div class="preview-photo-corner corner-tr"></div>
                        <div class="preview-photo-corner corner-bl"></div>
                        <div class="preview-photo-corner corner-br"></div>
                        
                        <?php 
                        $resolvedPhoto = !empty($advisor['photo_url']) ? resolve_photo_url($advisor['photo_url']) : null;
                        if (!empty($resolvedPhoto)): ?>
                            <img src="<?= htmlspecialchars($resolvedPhoto) ?>" alt="Advisor Photo" style="width: 100%; height: 100%; display: block;">
                        <?php else: ?>
                            <i class="bi bi-person-badge-fill" style="font-size: 26px; color: #94a3b8; margin-bottom: 2px;"></i>
                            <span style="font-size: 8px; font-weight: 800; color: #64748b; letter-spacing: 0.06em; text-transform: uppercase; line-height: 1;">OFFICIAL PHOTO</span>
                            <span style="font-size: 7px; font-weight: 600; color: #94a3b8; margin-top: 2px;">CR80 Compliant</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 2. Scaled Name, Designation, Phone & Address -->
                <div style="text-align: center; width: 100%;">
                    <h3 style="font-size: 18px; font-weight: 900; color: #0f2d59; text-transform: uppercase; letter-spacing: -0.3px; line-height: 1.15; margin-bottom: 2px;">
                        <?= htmlspecialchars($fullName) ?>
                    </h3>
                    <div style="display: inline-block; padding: 2px 10px; background: rgba(224, 242, 254, 0.95); border-radius: 9999px; border: 1px solid #7dd3fc;">
                        <p style="font-size: 12px; font-weight: 800; color: #0369a1; text-transform: uppercase; letter-spacing: 0.06em; line-height: 1; margin-bottom: 0;">
                            CERTIFIED SOLAR ADVISOR
                        </p>
                    </div>
                    <div style="margin-top: 2px; display: flex; align-items: center; justify-content: center; gap: 4px; color: #0f2d59;">
                        <i class="bi bi-telephone-fill" style="color: #0284c7; font-size: 16px;"></i>
                        <span class="font-mono-num" style="font-weight: 800; font-size: 16px; letter-spacing: -0.2px;">
                            <?= $mobileNumbers ?>
                        </span>
                    </div>
                    <?php if (!empty($fullAddress)): ?>
                    <div style="margin-top: 2px; font-size: 12px; font-weight: 600; color: #334155; line-height: 1.25; text-align: center; padding: 0 2px;">
                        <i class="bi bi-geo-alt-fill text-danger me-1" style="font-size: 18px; color: red;"></i><?= htmlspecialchars($fullAddress) ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- 3. Badges Grid -->
                <div style="width: 100%;" class="preview-grid-badge-box">
                    <div style="border-right: 1px solid #e2e8f0; padding-right: 4px; text-align: center;">
                        <span style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; display: block;">ADVISOR ID</span>
                        <span class="font-mono-num" style="font-size: 16px; font-weight: 900; color: #0f2d59; letter-spacing: 0.3px;">
                            <?= $advisorCode ?>
                        </span>
                    </div>
                    <div style="padding-left: 4px; text-align: center;">
                        <span style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; display: block;">BLOOD GROUP</span>
                        <span style="font-size: 16px; font-weight: 900; color: #e11d48; align-items: center; gap: 3px; line-height: 1.1;">
                            <i class="bi bi-droplet-fill" style="font-size: 11px;"></i> <?= htmlspecialchars($bloodGroup) ?>
                        </span>
                    </div>
                </div>

                <!-- 4. Jurisdiction Block -->
                <div style="width: 100%; text-align: left; background: rgba(255, 255, 255, 0.95); padding: 4px 8px; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; align-items: flex-start; z-index: 20;">
                    <div style="width: 16px; height: 16px; border-radius: 4px; background: #e0f2fe; display: flex; align-items: center; justify-content: center; margin-right: 6px; flex-shrink: 0; color: #0284c7; margin-top: 1px;">
                        <i class="bi bi-geo-alt-fill" style="font-size: 16px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <span style="font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; display: block;">JURISDICTION:</span>
                        <span style="font-size: 16px; font-weight: 700; line-height: 1.25; color: #0f2d59;">
                            <?= $jurisdiction ?>
                        </span>
                    </div>
                </div>

            </div>

            <!-- Wave Banner -->
            <div class="preview-bottom-wave-banner">
                <svg style="width: 100%; height: 100%; display: block;" preserveAspectRatio="none" viewBox="0 0 320 48">
                    <defs>
                        <linearGradient id="advPrevFrontWaveGrad" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop offset="0%" stop-color="#0284c7"/>
                            <stop offset="45%" stop-color="#0f2d59"/>
                            <stop offset="78%" stop-color="#1e3a8a"/>
                            <stop offset="100%" stop-color="#0d9488"/>
                        </linearGradient>
                        <linearGradient id="advPrevFrontGoldPinstripe" x1="0%" x2="100%" y1="0%" y2="0%">
                            <stop offset="0%" stop-color="#38bdf8"/>
                            <stop offset="50%" stop-color="#f59e0b"/>
                            <stop offset="100%" stop-color="#fde047"/>
                        </linearGradient>
                    </defs>
                    <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12 L320,48 L0,48 Z" fill="url(#advPrevFrontWaveGrad)"/>
                    <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12" fill="none" stroke="url(#advPrevFrontGoldPinstripe)" stroke-linecap="round" stroke-width="2.5"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- BACK SIDE PREVIEW -->
    <div class="col-auto">
        <div class="text-center mb-2">
            <span class="badge bg-emerald-800 text-white px-3 py-1 font-monospace" style="background: #065f46; font-size: 0.72rem;">BACK SIDE • CR80 PORTRAIT</span>
        </div>
        
        <div class="preview-id-card">
            <!-- Slot Punch Guide -->
            <div style="position: absolute; top: 6px; left: 50%; transform: translateX(-50%); z-index: 30;">
                <div class="preview-lanyard-slot"></div>
            </div>

            <!-- Photovoltaic Pattern Overlay -->
            <div class="preview-solar-bg-pattern"></div>

            <!-- Top Header -->
            <div class="preview-card-header-full">
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
                    <div style="flex: 1; min-width: 0; text-align: center; align-items: center;">
                        <div style="display: flex; align-items: baseline; gap: 4px; line-height: 1;">
                            <span style="font-size: 16px; font-weight: 900; color: #0f2d59; letter-spacing: -0.5px;"><?= htmlspecialchars(strtoupper(company_name())) ?></span>
                        </div>
                        <div style="margin-top: 3px; display: flex; align-items: center;">
                            <span style="font-size: 8px; font-weight: 900; color: #0f2d59; letter-spacing: 0.08em; text-transform: uppercase; background: #eff6ff; padding: 2px 6px; border-radius: 4px; border: 1px solid rgba(191, 219, 254, 0.8);">
                                <?= htmlspecialchars(strtoupper(company_tagline())) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Content Body -->
            <div style="padding: 8px 14px 4px 14px; flex: 1; display: flex; flex-direction: column; justify-content: space-between; text-align: left; position: relative; z-index: 10;">
                
                <!-- Company Info -->
                <div style="background: rgba(255, 255, 255, 0.95); border-radius: 8px; padding: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <div style="flex: 1; font-size: 12px; line-height: 1.35; color: #334155;">
                            <div style="margin-bottom: 2px;">
                                <span style="font-weight: 900; color: #0f2d59; font-size: 11.5px; text-transform: uppercase;">HEAD OFFICE:</span>
                                <span style="font-weight: 600;"><?= htmlspecialchars(company_address()) ?></span>
                            </div>
                            <div style="margin-bottom: 2px;">
                                <span style="font-weight: 900; color: #0f2d59; font-size: 11.5px; text-transform: uppercase;">HELPLINE:</span>
                                <span class="font-mono-num" style="font-weight: 900; font-size: 13px; color: #0f2d59;"><?= htmlspecialchars(company_phone()) ?></span>
                            </div>
                            <div>
                                <span style="font-weight: 700; color: #0891b2; font-size: 12px; word-break: break-all;"><?= htmlspecialchars(company_email()) ?></span>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 6px; padding-top: 4px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 12px; font-weight: 900; color: #0f2d59; text-transform: uppercase;">STAFF ROLE:</span>
                        <span class="font-mono-num" style="font-size: 12px; font-weight: 900; color: #0f2d59; background: #eff6ff; padding: 1px 6px; border-radius: 4px; border: 1px solid #bfdbfe;">
                            CERTIFIED SOLAR ADVISOR
                        </span>
                    </div>
                </div>

                <!-- Instructions / Security Warning Box -->
                <div style="background: rgba(254, 243, 199, 0.95); border-left: 3px solid #f59e0b; padding: 6px 8px; border-radius: 0 6px 6px 0; font-size: 11px; color: #451a03; line-height: 1.35;">
                    <p style="font-size: 10.5px; font-weight: 900; text-transform: uppercase; color: #78350f; margin-bottom: 2px;">
                        <i class="bi bi-shield-lock-fill me-1"></i> TERMS & CONDITIONS
                    </p>
                    <p style="font-weight: 600; margin-bottom: 0;">• This identity card is strictly non-transferable and remains company property.</p>
                    <p style="font-weight: 600; margin-top: 1px; margin-bottom: 0;">• If found, please return to <?= htmlspecialchars(company_name()) ?> Corporate Office.</p>
                </div>

                <div style="display: flex; gap: 8px; align-items: center;">
                    <!-- Digital QR Code -->
                    <div style="text-align: center; flex-shrink: 0; background: #f8fafc; padding: 4px; border-radius: 6px; border: 1px solid #cbd5e1;">
                        <img src="<?= $qrUrl ?>" alt="QR" style="width: 58px; height: 58px; display: block;">
                        <span style="font-size: 6px; font-weight: 700; color: #64748b; text-transform: uppercase;">Scan to Verify</span>
                    </div>
                    <!-- Signatures Section: Authorised Signatory Seal & Signature -->
                    <div style="padding-top: 4px; border-top: 1px solid #e2e8f0; display: flex; flex-direction: column; align-items: flex-end; justify-content: flex-end;">
                        <div style="text-align: center; display: flex; flex-direction: column; align-items: center;">
                            <img src="<?= htmlspecialchars(company_signature_url()) ?>" alt="Authorised Signatory" style="height: 55px; object-fit: contain; display: block;">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Wave Banner -->
            <div class="preview-bottom-wave-banner">
                <svg style="width: 100%; height: 100%; display: block;" preserveAspectRatio="none" viewBox="0 0 320 48">
                    <defs>
                        <linearGradient id="advPrevBackWaveGrad" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop offset="0%" stop-color="#0284c7"/>
                            <stop offset="45%" stop-color="#0f2d59"/>
                            <stop offset="78%" stop-color="#1e3a8a"/>
                            <stop offset="100%" stop-color="#0d9488"/>
                        </linearGradient>
                        <linearGradient id="advPrevBackGoldPinstripe" x1="0%" x2="100%" y1="0%" y2="0%">
                            <stop offset="0%" stop-color="#38bdf8"/>
                            <stop offset="50%" stop-color="#f59e0b"/>
                            <stop offset="100%" stop-color="#fde047"/>
                        </linearGradient>
                    </defs>
                    <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12 L320,48 L0,48 Z" fill="url(#advPrevBackWaveGrad)"/>
                    <path d="M0,19 C65,6 135,15 200,23 C245,28.5 285,24 320,12" fill="none" stroke="url(#advPrevBackGoldPinstripe)" stroke-linecap="round" stroke-width="2.5"/>
                </svg>
            </div>
        </div>
    </div>
</div>
