<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Customer Upgrade to Certified Solar Advisor (Dedicated Full-Page View)
 */
$title = "Become a Certified Solar Advisor — " . company_name();
?>

<div class="container-fluid py-3 py-md-4">
    
    <!-- TOP NAVIGATION / BACK BAR -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="<?= url('/customer/dashboard') ?>" class="btn btn-outline-secondary btn-sm fw-bold d-inline-flex align-items-center gap-1 shadow-sm rounded-pill px-3">
            <i class="bi bi-arrow-left fs-6"></i> <span>Back to Dashboard</span>
        </a>
        <span class="badge bg-warning text-dark fw-bold px-3 py-2 fs-6 shadow-sm">
            <i class="bi bi-award-fill me-1"></i> 9-Level Solar Network
        </span>
    </div>

    <!-- FLASH ALERTS -->
    <?php if (!empty($_SESSION['error_msg'])): ?>
        <div class="alert alert-danger py-2 px-3 small d-flex align-items-center rounded-3 mb-3 border-0 shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 flex-shrink-0"></i> 
            <div><?= htmlspecialchars($_SESSION['error_msg']) ?></div>
        </div>
        <?php unset($_SESSION['error_msg']); ?>
    <?php endif; ?>

    <!-- HERO PROMOTION CARD -->
    <div class="card p-3 p-md-4 mb-4 border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #061528 0%, #0f2d59 100%); border-radius: 20px; border-bottom: 4px solid #f59e0b !important;">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-dark fw-bold px-2 py-1">Career Opportunity</span>
                    <span class="text-warning small fw-semibold">PM Surya Ghar Solar Mitra Program</span>
                </div>
                <h3 class="fw-bold font-heading mb-2 text-white" style="letter-spacing: -0.3px;">
                    Become a Certified Solar Advisor
                </h3>
                <p class="text-white-50 small mb-0" style="font-size: 0.88rem; line-height: 1.5;">
                    Help fellow residents adopt rooftop solar under PM Surya Ghar Muft Bijli Yojana Odisha. Earn up to <strong>₹10,000 per direct 3kW installation</strong> plus 9-level passive team overrides, monthly performance bonuses (up to ₹30,000/mo), and lifetime rewards.
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-20 d-inline-block text-center w-100 w-md-auto">
                    <div class="small text-white-50 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Onboarding & Kit Fee</div>
                    <div class="text-warning fw-bold fs-3 font-heading mb-0">₹<?= number_format(advisor_joining_fee()) ?></div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle mt-1" style="font-size: 0.68rem;">1-Time Induction Fee</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONVERSION FORM -->
    <form action="<?= url('/customer/convert-to-advisor') ?>" method="POST" enctype="multipart/form-data">
        <?= function_exists('csrf_field') ? csrf_field() : '' ?>

        <!-- STEP 1: COMPANY BANK & QR CODE PAYMENT -->
        <div class="card p-3 p-md-4 bg-white border-0 shadow-sm rounded-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 border-bottom pb-2">
                <div>
                    <h5 class="fw-bold text-navy mb-0 d-flex align-items-center gap-2">
                        <span class="badge bg-warning text-dark rounded-circle" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                        Step 1: Transfer ₹<?= number_format(advisor_joining_fee()) ?> Onboarding Fee
                    </h5>
                    <span class="text-muted small">Official Corporate Bank Account / UPI QR of Surya Vistaara Pvt. Ltd.</span>
                </div>
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 fs-6 shadow-sm">
                    Fee: ₹<?= number_format(advisor_joining_fee()) ?>
                </span>
            </div>

            <div class="row g-4 align-items-center">
                <!-- QR Code & UPI ID -->
                <div class="col-12 col-md-4 text-center border-end-md pb-3 pb-md-0">
                    <div class="p-3 bg-light rounded-4 border d-inline-block shadow-sm mb-2">
                        <div style="width: 150px; height: 150px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="bi bi-qr-code text-navy" style="font-size: 5.5rem;"></i>
                        </div>
                    </div>
                    <div class="font-monospace fw-bold text-navy fs-6 mb-1" style="word-break: break-all;">
                        suryavistaara@sbi
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.72rem;">
                        <i class="bi bi-shield-check me-1"></i> Verified Merchant UPI
                    </span>
                </div>

                <!-- Bank Account Details -->
                <div class="col-12 col-md-8">
                    <div class="p-3 p-md-4 bg-light rounded-3 border">
                        <div class="row g-2 small" style="font-size: 0.85rem;">
                            <div class="col-sm-5 text-muted">Account Name:</div>
                            <div class="col-sm-7 fw-bold text-navy">Surya Vistaara Pvt. Ltd.</div>

                            <div class="col-sm-5 text-muted">Bank & Branch:</div>
                            <div class="col-sm-7 fw-semibold text-dark">State Bank of India (Bhubaneswar Main)</div>

                            <div class="col-sm-5 text-muted">Current Account No:</div>
                            <div class="col-sm-7 fw-bold text-primary font-monospace fs-6">42180029381</div>

                            <div class="col-sm-5 text-muted">IFSC Code:</div>
                            <div class="col-sm-7 fw-bold text-dark font-monospace">SBIN0010250</div>

                            <div class="col-sm-5 text-muted">Assisting Advisor (Sponsor):</div>
                            <div class="col-sm-7 fw-semibold text-success">
                                <?= htmlspecialchars($customer['advisor_name'] ?? 'Direct Company HQ') ?> 
                                (<?= htmlspecialchars($customer['advisor_code'] ?? 'SVPL-HQ') ?>)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 2: PAYMENT TRANSACTION UTR & DETAILS -->
        <div class="card p-3 p-md-4 bg-white border-0 shadow-sm rounded-4 mb-4">
            <h5 class="fw-bold text-navy mb-3 d-flex align-items-center gap-2 border-bottom pb-2">
                <span class="badge bg-warning text-dark rounded-circle" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                Step 2: Enter Payment Confirmation (UTR / Transaction Ref)
            </h5>
            
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-navy mb-1">Payment Method *</label>
                    <select name="payment_method" class="form-select fw-semibold" required>
                        <option value="UPI" selected>UPI / Google Pay / PhonePe / Paytm</option>
                        <option value="BANK_TRANSFER">IMPS / NEFT / RTGS</option>
                        <option value="CASH">Cash Deposit at SVPL Office</option>
                        <option value="CARD">Debit / Credit Card</option>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-navy mb-1">Bank UTR / Transaction Ref No. *</label>
                    <input type="text" name="transaction_ref" class="form-control font-monospace fw-bold text-uppercase" placeholder="e.g. 425619882310" required>
                    <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">12-digit UPI ref or bank UTR number</small>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-navy mb-1">Payment Date *</label>
                    <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>
        </div>

        <!-- STEP 3: BANK DETAILS FOR RECEIVING COMMISSIONS -->
        <div class="card p-3 p-md-4 bg-white border-0 shadow-sm rounded-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2 border-bottom pb-2">
                <h5 class="fw-bold text-navy mb-0 d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark rounded-circle" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                    Step 3: Payout Bank Account for Solar Commissions
                </h5>
                <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.72rem;">Statutory 5% TDS Compliant</span>
            </div>
            <p class="text-muted small mb-3" style="font-size: 0.8rem;">Your earnings from customer solar referrals and team bonuses will be directly credited to this bank account.</p>
            
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-navy mb-1">Bank Name</label>
                    <input type="text" name="bank_name" class="form-control" value="<?= htmlspecialchars($customer['bank_name'] ?? '') ?>" placeholder="e.g. State Bank of India">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-navy mb-1">Branch Name</label>
                    <input type="text" name="bank_branch" class="form-control" value="<?= htmlspecialchars($customer['bank_branch'] ?? '') ?>" placeholder="e.g. Bhubaneswar Main">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-navy mb-1">Account Holder Name *</label>
                    <input type="text" name="account_holder" class="form-control" value="<?= htmlspecialchars($customer['account_holder'] ?? ($customer['first_name'] . ' ' . $customer['last_name'])) ?>" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-navy mb-1">Account Number</label>
                    <input type="text" name="account_number" class="form-control font-monospace" value="<?= htmlspecialchars($customer['account_number'] ?? '') ?>" placeholder="Bank account number">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-navy mb-1">IFSC Code</label>
                    <input type="text" name="ifsc_code" class="form-control font-monospace text-uppercase" value="<?= htmlspecialchars($customer['ifsc_code'] ?? '') ?>" placeholder="e.g. SBIN0010250">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-navy mb-1">PAN Number (for TDS @ 5%)</label>
                    <input type="text" name="pan_number" class="form-control font-monospace text-uppercase" placeholder="e.g. ABCDE1234F">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-bold text-navy mb-1">Aadhaar Number</label>
                    <input type="text" name="aadhaar_number" class="form-control font-monospace" value="<?= htmlspecialchars($customer['aadhaar_number'] ?? '') ?>" placeholder="12-digit Aadhaar number">
                </div>
            </div>
        </div>

        <!-- STEP 4: PASSPORT PHOTO FOR ADVISOR ID CARD -->
        <div class="card p-3 p-md-4 bg-white border-0 shadow-sm rounded-4 mb-4">
            <h5 class="fw-bold text-navy mb-3 d-flex align-items-center gap-2 border-bottom pb-2">
                <span class="badge bg-warning text-dark rounded-circle" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">4</span>
                Step 4: Passport Photo for Official Solar Mitra ID Card
            </h5>
            
            <div class="row g-3 align-items-center">
                <div class="col-4 col-sm-3 text-center">
                    <div style="width: 90px; height: 110px; border: 2px dashed #0f2d59; border-radius: 10px; margin: 0 auto; overflow: hidden; background: #f8fafc; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <?php if (!empty($customer['photo_url'])): ?>
                            <img id="convertPhotoPreview" src="<?= htmlspecialchars(resolve_photo_url($customer['photo_url'])) ?>" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                            <div id="convertPhotoPlaceholder" class="text-muted text-center p-1" style="display: none;">
                                <i class="bi bi-person fs-3"></i>
                            </div>
                        <?php else: ?>
                            <img id="convertPhotoPreview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                            <div id="convertPhotoPlaceholder" class="text-muted text-center p-1">
                                <i class="bi bi-person fs-3"></i>
                                <span class="d-block" style="font-size: 0.65rem;">Passport</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-8 col-sm-9">
                    <div class="row g-3">
                        <div class="col-12 col-md-7">
                            <label class="form-label small fw-bold text-navy mb-1">Upload Passport Photo</label>
                            <input type="file" name="advisor_photo" id="convertFileInput" class="form-control" accept="image/jpeg,image/png,image/webp">
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label small fw-bold text-navy mb-1">Blood Group</label>
                            <select name="blood_group" class="form-select fw-semibold">
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
                    </div>
                    <input type="hidden" name="advisor_photo_base64" id="convertPhotoBase64" value="">
                    <small class="text-muted mt-2 d-block" style="font-size: 0.75rem;">
                        <i class="bi bi-check-circle text-success me-1"></i> Your photo is printed on your official SVPL Solar Mitra Digital & Physical QR ID Card.
                    </small>
                </div>
            </div>
        </div>

        <!-- ACTION FOOTER BAR -->
        <div class="card p-3 p-md-4 bg-white border-0 shadow-sm rounded-4 d-flex flex-column-reverse flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-3">
            <a href="<?= url('/customer/dashboard') ?>" class="btn btn-outline-secondary px-4 py-2 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Cancel & Back to Dashboard
            </a>
            <button type="submit" class="btn btn-warning text-dark fw-bold px-4 py-3 shadow" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); border: 0; font-size: 1rem;">
                <i class="bi bi-check2-circle fs-5 me-1"></i> Submit ₹<?= number_format(advisor_joining_fee()) ?> Fee & Join as Advisor
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('convertFileInput');
    const photoPreview = document.getElementById('convertPhotoPreview');
    const placeholder = document.getElementById('convertPhotoPlaceholder');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    if (photoPreview) {
                        photoPreview.src = evt.target.result;
                        photoPreview.style.display = 'block';
                    }
                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
