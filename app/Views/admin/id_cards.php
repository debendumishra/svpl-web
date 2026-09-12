<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin On-Demand ID Card Generator & Management View
 */
$title = "ID Card Generator & Governance — SVPL Admin";
$cards = $cards ?? [];
$search = $search ?? '';
$selectedType = $selectedType ?? 'ALL';
$nextBoeCode = $nextBoeCode ?? 'SVPL-BOE-101';
$nextAdvCode = $nextAdvCode ?? 'SVPL-ADV-101';
$nextOffCode = $nextOffCode ?? 'SVPL-OFF-101';
$success = $success ?? null;
$error = $error ?? null;
?>

<div class="container-fluid px-3 px-lg-4 py-3">
    <!-- PAGE HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-navy text-warning fw-bold px-2 py-1">
                    <i class="bi bi-person-vcard-fill me-1"></i> ID Card Governance
                </span>
                <span class="badge bg-light text-dark border font-monospace fw-semibold">
                    <?= count($cards) ?> Generated Cards
                </span>
            </div>
            <h3 class="font-heading fw-bold mb-0 text-navy">On-Demand ID Card Generator & Registry</h3>
            <p class="text-secondary small mb-0">Generate, crop & remove background, compress photo (low-KB), and print official CR80 duplex ID cards for BOE, Advisors, & Officers.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-svpl-solar fw-bold shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createIdCardModal">
                <i class="bi bi-plus-circle-fill"></i> Generate New ID Card
            </button>
        </div>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div><strong>Success:</strong> <?= htmlspecialchars($success) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
            <div><strong>Error:</strong> <?= htmlspecialchars($error) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- STATS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary small text-uppercase fw-bold">Total ID Cards</span>
                        <h3 class="fw-bold mb-0 text-navy mt-1"><?= count($cards) ?></h3>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 rounded-circle text-primary">
                        <i class="bi bi-person-vcard fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary small text-uppercase fw-bold">BOE Staff Cards</span>
                        <h3 class="fw-bold mb-0 text-navy mt-1">
                            <?= count(array_filter($cards, fn($c) => strtoupper($c['card_type']) === 'BOE')) ?>
                        </h3>
                    </div>
                    <div class="p-3 bg-info bg-opacity-10 rounded-circle text-info">
                        <i class="bi bi-person-workspace fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary small text-uppercase fw-bold">Advisor Cards</span>
                        <h3 class="fw-bold mb-0 text-navy mt-1">
                            <?= count(array_filter($cards, fn($c) => strtoupper($c['card_type']) === 'ADVISOR')) ?>
                        </h3>
                    </div>
                    <div class="p-3 bg-warning bg-opacity-10 rounded-circle text-warning">
                        <i class="bi bi-award-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary small text-uppercase fw-bold">Total Print Jobs</span>
                        <h3 class="fw-bold mb-0 text-navy mt-1">
                            <?= array_sum(array_column($cards, 'print_count')) ?>
                        </h3>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 rounded-circle text-success">
                        <i class="bi bi-printer-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEARCH & FILTER BAR -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-3">
            <form method="GET" action="<?= url('/admin/id-cards') ?>" class="row g-2 align-items-center">
                <div class="col-md-3 col-lg-2">
                    <select name="type" class="form-select fw-semibold" onchange="this.form.submit()">
                        <option value="ALL" <?= $selectedType === 'ALL' ? 'selected' : '' ?>>All Card Types</option>
                        <option value="BOE" <?= $selectedType === 'BOE' ? 'selected' : '' ?>>BOE Staff Cards</option>
                        <option value="ADVISOR" <?= $selectedType === 'ADVISOR' ? 'selected' : '' ?>>Advisor Cards</option>
                        <option value="OFFICER" <?= $selectedType === 'OFFICER' ? 'selected' : '' ?>>Officers / Executives</option>
                        <option value="ENGINEER" <?= $selectedType === 'ENGINEER' ? 'selected' : '' ?>>Project Engineers</option>
                        <option value="CUSTOM" <?= $selectedType === 'CUSTOM' ? 'selected' : '' ?>>Custom Cards</option>
                    </select>
                </div>
                <div class="col-md-6 col-lg-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" class="form-control border-start-0" placeholder="Search by ID Code (e.g. SVPL-BOE-101), Full Name, Mobile, Designation, or Circle..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>
                <div class="col-md-3 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-navy fw-bold flex-fill">
                        <i class="bi bi-funnel-fill me-1"></i> Filter
                    </button>
                    <?php if (!empty($search) || $selectedType !== 'ALL'): ?>
                        <a href="<?= url('/admin/id-cards') ?>" class="btn btn-light border" title="Reset Search">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- ID CARDS TABLE -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase tracking-wider">
                    <tr>
                        <th>Photo</th>
                        <th>ID Code & Category</th>
                        <th>Legal Name & Contact</th>
                        <th>Designation</th>
                        <th>Assigned Jurisdiction</th>
                        <th>Blood Group</th>
                        <th>Validity Period</th>
                        <th>Print Stats</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cards)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-person-vcard fs-1 d-block mb-2 text-secondary"></i>
                                No ID card records found. Click <strong>"Generate New ID Card"</strong> to create and print a card.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cards as $card): 
                            $photoUrl = !empty($card['photo_url']) ? resolve_photo_url($card['photo_url']) : null;
                            $initials = strtoupper(substr($card['full_name'] ?? 'S', 0, 1) . substr(strstr($card['full_name'] ?? '', ' ') ?: ($card['full_name'] ?? 'V'), 1, 1));
                            $typeBadgeClass = 'bg-primary text-white';
                            if (strtoupper($card['card_type']) === 'ADVISOR') $typeBadgeClass = 'bg-warning text-dark fw-bold';
                            elseif (strtoupper($card['card_type']) === 'OFFICER') $typeBadgeClass = 'bg-danger text-white';
                            elseif (strtoupper($card['card_type']) === 'ENGINEER') $typeBadgeClass = 'bg-success text-white';
                        ?>
                            <tr>
                                <td>
                                    <div class="position-relative" style="width: 42px; height: 52px; border-radius: 6px; overflow: hidden; background: linear-gradient(135deg, #0B2545 0%, #1E3A8A 100%); border: 1.5px solid #0f2d59; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center;">
                                        <?php if (!empty($photoUrl)): ?>
                                            <img src="<?= htmlspecialchars($photoUrl) ?>" alt="Photo" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <span class="text-white fw-bold" style="font-size: 0.78rem; display: none; align-items: center; justify-content: center; width: 100%; height: 100%; letter-spacing: 1px;"><?= htmlspecialchars($initials) ?></span>
                                        <?php else: ?>
                                            <span class="text-white fw-bold" style="font-size: 0.78rem; display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; letter-spacing: 1px;"><?= htmlspecialchars($initials) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark font-monospace border fw-bold px-2 py-1 fs-6">
                                        <?= htmlspecialchars($card['card_code']) ?>
                                    </span>
                                    <div class="mt-1">
                                        <span class="badge <?= $typeBadgeClass ?>" style="font-size: 0.68rem;">
                                            <?= htmlspecialchars($card['card_type']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-navy" style="font-size: 0.95rem;"><?= htmlspecialchars($card['full_name']) ?></div>
                                    <div class="text-muted small">
                                        <i class="bi bi-telephone text-success me-1"></i><?= htmlspecialchars($card['mobile']) ?>
                                    </div>
                                    <?php if (!empty($card['email'])): ?>
                                        <div class="text-secondary small" style="font-size: 0.72rem;">
                                            <i class="bi bi-envelope me-1"></i><?= htmlspecialchars($card['email']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold">
                                        <?= htmlspecialchars($card['designation']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($card['jurisdiction'] ?? 'Headquarters / All Odisha') ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-bold">
                                        <i class="bi bi-droplet-fill me-1"></i><?= htmlspecialchars($card['blood_group']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small text-muted font-monospace" style="font-size: 0.75rem;">
                                        <?= !empty($card['issue_date']) ? date('d-m-Y', strtotime($card['issue_date'])) : date('d-m-Y') ?>
                                    </div>
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.68rem;">
                                        Thru <?= htmlspecialchars($card['valid_thru'] ?? '31-12-2027') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold">
                                        <i class="bi bi-printer me-1"></i> <?= (int)$card['print_count'] ?> prints
                                    </span>
                                    <?php if (!empty($card['last_printed_at'])): ?>
                                        <div class="text-muted" style="font-size: 0.68rem;">
                                            <?= date('d M, h:i A', strtotime($card['last_printed_at'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-1">
                                        <a href="<?= url('/print/custom-id-card/' . $card['id']) ?>" target="_blank" class="btn btn-primary btn-sm fw-bold shadow-sm" title="Print Duplex CR80 Card">
                                            <i class="bi bi-printer-fill me-1"></i> Print
                                        </a>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" title="Edit Card Details" onclick='openEditCardModal(<?= json_encode($card, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form method="POST" action="<?= url('/admin/id-cards/delete/' . $card['id']) ?>" onsubmit="return confirm('Are you sure you want to delete ID Card <?= htmlspecialchars($card['card_code']) ?>?');" class="d-inline">
                                            <?= function_exists('csrf_field') ? csrf_field() : '' ?>
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete Card">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ======================================================= -->
<!-- MODAL: GENERATE NEW CUSTOM ID CARD                      -->
<!-- ======================================================= -->
<div class="modal fade" id="createIdCardModal" tabindex="-1" aria-labelledby="createIdCardModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= url('/admin/id-cards/create') ?>" method="POST" enctype="multipart/form-data">
                <?= function_exists('csrf_field') ? csrf_field() : '' ?>

                <div class="modal-header bg-navy text-white py-3">
                    <h5 class="modal-title font-outfit fw-bold" id="createIdCardModalLabel">
                        <i class="bi bi-person-vcard-fill me-2 text-warning"></i> Generate & Print New ID Card
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-4 bg-light">
                    <!-- 1. Category & ID Code -->
                    <div class="card p-3 border-0 shadow-sm rounded-3 mb-3 bg-white">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">ID Card Category / Type *</label>
                                <select name="card_type" id="createCardType" class="form-select fw-semibold" onchange="onCardTypeChange(this.value, 'createCardCode', 'createDesignation', 'createJurisdiction')">
                                    <option value="BOE" selected>Back Office Executive (BOE Staff)</option>
                                    <option value="ADVISOR">Certified Solar Advisor</option>
                                    <option value="OFFICER">District Officer / Manager</option>
                                    <option value="ENGINEER">Project Installation Engineer</option>
                                    <option value="CUSTOM">Custom Organization Card</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">Unique ID / Employee Code *</label>
                                <input type="text" name="card_code" id="createCardCode" class="form-control font-monospace fw-bold" value="<?= htmlspecialchars($nextBoeCode) ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Legal Name, Contact & Designation -->
                    <div class="card p-3 border-0 shadow-sm rounded-3 mb-3 bg-white">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">Full Legal Name *</label>
                                <input type="text" name="full_name" class="form-control" placeholder="e.g. Ramesh Chandra Das" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">Official Designation *</label>
                                <input type="text" name="designation" id="createDesignation" class="form-control" value="Back Office Executive" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">Mobile Number (Printed on Card) *</label>
                                <input type="tel" name="mobile" class="form-control" placeholder="10-digit mobile number" required pattern="[0-9]{10}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">Contact Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="official@suryavistaara.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy"><i class="bi bi-droplet-fill text-danger me-1"></i> Blood Group *</label>
                                <select name="blood_group" class="form-select fw-semibold" required>
                                    <option value="O+ve" selected>O +ve</option>
                                    <option value="A+ve">A +ve</option>
                                    <option value="B+ve">B +ve</option>
                                    <option value="AB+ve">AB +ve</option>
                                    <option value="O-ve">O -ve</option>
                                    <option value="A-ve">A -ve</option>
                                    <option value="B-ve">B -ve</option>
                                    <option value="AB-ve">AB -ve</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy"><i class="bi bi-geo-alt-fill text-primary me-1"></i> Assigned Jurisdiction / Circle *</label>
                                <input type="text" name="jurisdiction" id="createJurisdiction" class="form-control" list="circleList" value="Bhubaneswar & Cuttack Circle" required>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Address & Validity -->
                    <div class="card p-3 border-0 shadow-sm rounded-3 mb-3 bg-white">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-navy">Postal Address / Office Location (10px Center-Aligned on Card)</label>
                                <input type="text" name="address" class="form-control" placeholder="e.g. MIG-84, Pokhariput, BDA Colony, Phase-1, Bhubaneswar, Khorda – 751020, Odisha">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-navy">Issue Date</label>
                                <input type="date" name="issue_date" class="form-control" value="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-navy">Valid Thru / Expiry</label>
                                <input type="text" name="valid_thru" class="form-control font-monospace fw-bold" value="31-12-2027">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-navy">Emergency Helpline</label>
                                <input type="text" name="emergency_contact" class="form-control font-monospace" value="<?= htmlspecialchars(company_phone()) ?>">
                            </div>
                        </div>
                    </div>

                    <!-- 4. Passport Photo Upload & Live Camera Capture with Studio -->
                    <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                        <label class="form-label small fw-bold text-navy mb-2">
                            <i class="bi bi-camera-fill text-primary me-1"></i> Passport Photo (Auto-Crop 3:4, Background Remover & Low-KB Compression)
                        </label>
                        <div class="row g-3 align-items-center">
                            <div class="col-sm-3 text-center">
                                <div style="width: 86px; height: 104px; border: 2px dashed #0f2d59; border-radius: 8px; margin: 0 auto; overflow: hidden; background: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <img id="createCardPhotoPreview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    <div id="createCardPhotoPlaceholder" class="text-muted text-center p-1">
                                        <i class="bi bi-person fs-3"></i>
                                        <span class="d-block" style="font-size: 0.68rem;">Passport Size</span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-warning btn-sm mt-1 py-0 px-2 fw-semibold" style="font-size: 0.68rem;" onclick="triggerPhotoStudio('createCardPhotoPreview', 'createCardPhotoPlaceholder', 'createCardPhotoBase64', 'createCardFileInput')">
                                    <i class="bi bi-crop me-1"></i> Crop / BG
                                </button>
                            </div>
                            <div class="col-sm-9">
                                <div class="row g-2">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small text-muted mb-1">Option A: Upload Image File</label>
                                        <input type="file" name="card_photo" id="createCardFileInput" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp" onchange="previewCardPhoto(this, 'createCardPhotoPreview', 'createCardPhotoPlaceholder', 'createCardPhotoBase64')">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small text-muted mb-1">Option B: Capture via Webcam</label>
                                        <button type="button" class="btn btn-outline-success btn-sm w-100 fw-bold" onclick="openLiveCamera('createCardPhotoPreview', 'createCardPhotoPlaceholder', 'createCardPhotoBase64')">
                                            <i class="bi bi-camera-video me-1"></i> Take Live Webcam Snapshot
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="card_photo_base64" id="createCardPhotoBase64" value="">
                                <small class="text-muted mt-2 d-block" style="font-size: 0.72rem;">
                                    <i class="bi bi-magic text-primary me-1"></i> Built-in Photo Studio crops to 3:4 passport ratio, removes background with 1-click studio backdrop, and automatically compresses photo to low KB (30-65 KB).
                                </small>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-svpl-solar fw-bold px-4">
                        <i class="bi bi-check-lg me-1"></i> Save Record & Proceed to Print
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================= -->
<!-- MODAL: EDIT CUSTOM ID CARD                              -->
<!-- ======================================================= -->
<div class="modal fade" id="editIdCardModal" tabindex="-1" aria-labelledby="editIdCardModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="editIdCardForm" action="" method="POST" enctype="multipart/form-data">
                <?= function_exists('csrf_field') ? csrf_field() : '' ?>

                <div class="modal-header bg-navy text-white py-3">
                    <h5 class="modal-title font-outfit fw-bold" id="editIdCardModalLabel">
                        <i class="bi bi-pencil-square me-2 text-warning"></i> Modify ID Card Data
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-4 bg-light">
                    <!-- 1. Category & ID Code -->
                    <div class="card p-3 border-0 shadow-sm rounded-3 mb-3 bg-white">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">ID Card Category / Type *</label>
                                <select name="card_type" id="editCardType" class="form-select fw-semibold">
                                    <option value="BOE">Back Office Executive (BOE Staff)</option>
                                    <option value="ADVISOR">Certified Solar Advisor</option>
                                    <option value="OFFICER">District Officer / Manager</option>
                                    <option value="ENGINEER">Project Installation Engineer</option>
                                    <option value="CUSTOM">Custom Organization Card</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">ID / Employee Code *</label>
                                <input type="text" name="card_code" id="editCardCode" class="form-control font-monospace fw-bold bg-light" required>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Legal Name, Contact & Designation -->
                    <div class="card p-3 border-0 shadow-sm rounded-3 mb-3 bg-white">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">Full Legal Name *</label>
                                <input type="text" name="full_name" id="editFullName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">Official Designation *</label>
                                <input type="text" name="designation" id="editDesignation" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">Mobile Number *</label>
                                <input type="tel" name="mobile" id="editMobile" class="form-control" required pattern="[0-9]{10}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy">Contact Email Address</label>
                                <input type="email" name="email" id="editEmail" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy"><i class="bi bi-droplet-fill text-danger me-1"></i> Blood Group *</label>
                                <select name="blood_group" id="editBloodGroup" class="form-select fw-semibold" required>
                                    <option value="O+ve">O +ve</option>
                                    <option value="A+ve">A +ve</option>
                                    <option value="B+ve">B +ve</option>
                                    <option value="AB+ve">AB +ve</option>
                                    <option value="O-ve">O -ve</option>
                                    <option value="A-ve">A -ve</option>
                                    <option value="B-ve">B -ve</option>
                                    <option value="AB-ve">AB -ve</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-navy"><i class="bi bi-geo-alt-fill text-primary me-1"></i> Assigned Jurisdiction *</label>
                                <input type="text" name="jurisdiction" id="editJurisdiction" class="form-control" list="circleList" required>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Address & Validity -->
                    <div class="card p-3 border-0 shadow-sm rounded-3 mb-3 bg-white">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-navy">Postal Address / Office Location</label>
                                <input type="text" name="address" id="editAddress" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-navy">Issue Date</label>
                                <input type="date" name="issue_date" id="editIssueDate" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-navy">Valid Thru</label>
                                <input type="text" name="valid_thru" id="editValidThru" class="form-control font-monospace fw-bold">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-navy">Emergency Helpline</label>
                                <input type="text" name="emergency_contact" id="editEmergencyContact" class="form-control font-monospace">
                            </div>
                        </div>
                    </div>

                    <!-- 4. Update Photo -->
                    <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                        <label class="form-label small fw-bold text-navy mb-2">
                            <i class="bi bi-camera-fill text-primary me-1"></i> Update Passport Photo
                        </label>
                        <div class="row g-3 align-items-center">
                            <div class="col-sm-3 text-center">
                                <div style="width: 86px; height: 104px; border: 2px dashed #0f2d59; border-radius: 8px; margin: 0 auto; overflow: hidden; background: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <img id="editCardPhotoPreview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    <div id="editCardPhotoPlaceholder" class="text-muted text-center p-1">
                                        <i class="bi bi-person fs-3"></i>
                                        <span class="d-block" style="font-size: 0.68rem;">Passport Size</span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-warning btn-sm mt-1 py-0 px-2 fw-semibold" style="font-size: 0.68rem;" onclick="triggerPhotoStudio('editCardPhotoPreview', 'editCardPhotoPlaceholder', 'editCardPhotoBase64', 'editCardFileInput')">
                                    <i class="bi bi-crop me-1"></i> Crop / BG
                                </button>
                            </div>
                            <div class="col-sm-9">
                                <div class="row g-2">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small text-muted mb-1">Upload New Photo</label>
                                        <input type="file" name="card_photo" id="editCardFileInput" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp" onchange="previewCardPhoto(this, 'editCardPhotoPreview', 'editCardPhotoPlaceholder', 'editCardPhotoBase64')">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small text-muted mb-1">Capture via Webcam</label>
                                        <button type="button" class="btn btn-outline-success btn-sm w-100 fw-bold" onclick="openLiveCamera('editCardPhotoPreview', 'editCardPhotoPlaceholder', 'editCardPhotoBase64')">
                                            <i class="bi bi-camera-video me-1"></i> Retake via Webcam
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="card_photo_base64" id="editCardPhotoBase64" value="">
                                <small class="text-muted mt-1 d-block" style="font-size: 0.72rem;">Leave empty to keep current photo.</small>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: LIVE WEBCAM CAPTURE CAMERA          -->
<!-- ========================================== -->
<div class="modal fade" id="modalLiveCamera" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white py-2">
                <h6 class="modal-title font-outfit fw-bold">
                    <i class="bi bi-camera-video-fill me-2 text-warning"></i> Live Camera Capture
                </h6>
                <button type="button" class="btn-close btn-close-white" onclick="closeLiveCamera()"></button>
            </div>
            <div class="modal-body text-center p-3 bg-dark position-relative">
                <div style="width: 280px; height: 350px; margin: 0 auto; position: relative; overflow: hidden; border-radius: 12px; background: #000; border: 2px solid #38bdf8;">
                    <video id="webcamVideo" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                    <!-- Visual Guideline Oval for Face Framing -->
                    <div style="position: absolute; top: 12%; left: 16%; width: 68%; height: 72%; border: 2px dashed rgba(255,255,255,0.6); border-radius: 50%; pointer-events: none;"></div>
                </div>
                <div id="cameraStatusText" class="text-light small mt-2">
                    <span class="spinner-border spinner-border-sm me-1" role="status"></span> Accessing camera...
                </div>
            </div>
            <div class="modal-footer bg-light justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeLiveCamera()">Cancel</button>
                <button type="button" class="btn btn-success fw-bold px-4" id="btnSnapPhoto" onclick="captureLiveSnapshot()" disabled>
                    <i class="bi bi-camera-fill me-1"></i> Snap & Use Photo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Circles / Jurisdiction Datalist -->
<datalist id="circleList">
    <option value="Bhubaneswar & Cuttack Circle">
    <option value="Khordha, Puri & Nayagarh Circle">
    <option value="Ganjam, Gajapati & Berhampur Circle">
    <option value="Western Odisha (Sambalpur, Bargarh, Jharsuguda)">
    <option value="Northern Odisha (Balasore, Bhadrak, Mayurbhanj)">
    <option value="Southern Odisha (Koraput, Rayagada, Nabarangpur)">
    <option value="Central Odisha (Angul, Dhenkanal, Keonjhar)">
    <option value="Headquarters Operations / All Odisha">
</datalist>

<script>
let activePreviewImgId = null;
let activePlaceholderId = null;
let activeBase64InputId = null;
let mediaStream = null;

const nextCodes = {
    'BOE': '<?= $nextBoeCode ?>',
    'ADVISOR': '<?= $nextAdvCode ?>',
    'OFFICER': '<?= $nextOffCode ?>',
    'ENGINEER': 'SVPL-ENG-101',
    'CUSTOM': 'SVPL-ID-101'
};

const defaultDesignations = {
    'BOE': 'Back Office Executive',
    'ADVISOR': 'Certified Solar Advisor',
    'OFFICER': 'District Solar Officer',
    'ENGINEER': 'Solar Project Engineer',
    'CUSTOM': 'Official Representative'
};

function onCardTypeChange(type, codeInputId, desigInputId, jurisInputId) {
    if (nextCodes[type]) {
        document.getElementById(codeInputId).value = nextCodes[type];
    }
    if (defaultDesignations[type] && !document.getElementById(desigInputId).value) {
        document.getElementById(desigInputId).value = defaultDesignations[type];
    }
}

function triggerPhotoStudio(previewId, placeholderId, base64InputId, fileInputId) {
    const preview = document.getElementById(previewId);
    const fileInput = document.getElementById(fileInputId);
    let src = (preview && preview.src && preview.src.length > 50) ? preview.src : null;

    if (!src && fileInput && fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (typeof window.openPhotoStudio === 'function') {
                window.openPhotoStudio(e.target.result, previewId, placeholderId, base64InputId);
            }
        };
        reader.readAsDataURL(fileInput.files[0]);
        return;
    }

    if (src && typeof window.openPhotoStudio === 'function') {
        window.openPhotoStudio(src, previewId, placeholderId, base64InputId);
    } else {
        alert('Please select or capture a photo first, then use Crop / BG to adjust.');
    }
}

function previewCardPhoto(input, previewId, placeholderId, base64InputId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';

            if (typeof window.openPhotoStudio === 'function') {
                window.openPhotoStudio(e.target.result, previewId, placeholderId, base64InputId);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function openLiveCamera(previewId, placeholderId, base64InputId) {
    activePreviewImgId = previewId;
    activePlaceholderId = placeholderId;
    activeBase64InputId = base64InputId;

    const modalEl = document.getElementById('modalLiveCamera');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    const video = document.getElementById('webcamVideo');
    const btnSnap = document.getElementById('btnSnapPhoto');
    const statusText = document.getElementById('cameraStatusText');

    btnSnap.disabled = true;
    statusText.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Accessing camera...';

    navigator.mediaDevices.getUserMedia({
        video: { width: { ideal: 720 }, height: { ideal: 960 }, facingMode: "user" },
        audio: false
    }).then(stream => {
        mediaStream = stream;
        video.srcObject = stream;
        video.onloadedmetadata = () => {
            video.play();
            btnSnap.disabled = false;
            statusText.innerHTML = '<i class="bi bi-check-circle text-success me-1"></i> Camera active. Center face inside the guide and click Snap.';
        };
    }).catch(err => {
        statusText.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-octagon me-1"></i> Camera error: ' + (err.message || 'Access denied') + '. Please use file upload instead.</span>';
    });
}

function closeLiveCamera() {
    if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
        mediaStream = null;
    }
    const modalEl = document.getElementById('modalLiveCamera');
    const modalObj = bootstrap.Modal.getInstance(modalEl);
    if (modalObj) modalObj.hide();
}

function captureLiveSnapshot() {
    const video = document.getElementById('webcamVideo');
    if (!video || !mediaStream) return;

    const canvas = document.createElement('canvas');
    canvas.width = 480;
    canvas.height = 640;
    const ctx = canvas.getContext('2d');

    const vWidth = video.videoWidth || 640;
    const vHeight = video.videoHeight || 480;
    const targetAspect = 480 / 640;

    let sx, sy, sWidth, sHeight;
    if (vWidth / vHeight > targetAspect) {
        sWidth = vHeight * targetAspect;
        sHeight = vHeight;
        sx = (vWidth - sWidth) / 2;
        sy = 0;
    } else {
        sWidth = vWidth;
        sHeight = vWidth / targetAspect;
        sx = 0;
        sy = (vHeight - sHeight) / 2;
    }

    ctx.drawImage(video, sx, sy, sWidth, sHeight, 0, 0, 480, 640);
    const base64Data = canvas.toDataURL('image/jpeg', 0.92);

    if (activePreviewImgId) {
        const preview = document.getElementById(activePreviewImgId);
        const placeholder = document.getElementById(activePlaceholderId);
        preview.src = base64Data;
        preview.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
    }
    if (activeBase64InputId) {
        document.getElementById(activeBase64InputId).value = base64Data;
    }

    const previewId = activePreviewImgId;
    const placeholderId = activePlaceholderId;
    const base64Id = activeBase64InputId;

    closeLiveCamera();

    if (typeof window.openPhotoStudio === 'function') {
        setTimeout(() => {
            window.openPhotoStudio(base64Data, previewId, placeholderId, base64Id);
        }, 300);
    }
}

function openEditCardModal(card) {
    document.getElementById('editIdCardForm').action = '<?= url('/admin/id-cards/update/') ?>' + card.id;
    document.getElementById('editCardType').value = card.card_type || 'BOE';
    document.getElementById('editCardCode').value = card.card_code || '';
    document.getElementById('editFullName').value = card.full_name || '';
    document.getElementById('editDesignation').value = card.designation || '';
    document.getElementById('editMobile').value = card.mobile || '';
    document.getElementById('editEmail').value = card.email || '';
    document.getElementById('editBloodGroup').value = card.blood_group || 'O+ve';
    document.getElementById('editJurisdiction').value = card.jurisdiction || 'Headquarters / All Odisha';
    document.getElementById('editAddress').value = card.address || '';
    document.getElementById('editIssueDate').value = card.issue_date || '';
    document.getElementById('editValidThru').value = card.valid_thru || '31-12-2027';
    document.getElementById('editEmergencyContact').value = card.emergency_contact || '';
    document.getElementById('editCardPhotoBase64').value = '';

    const preview = document.getElementById('editCardPhotoPreview');
    const placeholder = document.getElementById('editCardPhotoPlaceholder');
    if (card.photo_url) {
        let photoSrc = card.photo_url;
        if (!photoSrc.startsWith('http') && !photoSrc.startsWith('data:')) {
            photoSrc = '<?= url('/') ?>' + (photoSrc.startsWith('/') ? photoSrc.substring(1) : photoSrc);
        }
        preview.src = photoSrc;
        preview.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
    } else {
        preview.src = '';
        preview.style.display = 'none';
        if (placeholder) placeholder.style.display = 'block';
    }

    const editModal = new bootstrap.Modal(document.getElementById('editIdCardModal'));
    editModal.show();
}
</script>
