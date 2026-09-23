<!-- PM SURYA GHAR CONSUMER AGREEMENT (ANNEXURE 2) MODAL WINDOW -->
<div class="modal fade" id="modalConsumerAgreement" tabindex="-1" aria-labelledby="modalConsumerAgreementLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 950px; z-index: 1065;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="z-index: 1070; pointer-events: auto;">
            <!-- Modal Header -->
            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #0B2545 0%, #134074 100%);">
                <div class="d-flex align-items-center gap-2">
                    <div style="background: rgba(16, 185, 129, 0.2); color: #34d399; width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-heading fw-bold mb-0 text-white" id="modalConsumerAgreementLabel">
                            Model Draft Agreement (Annexure 2)
                        </h5>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge bg-success-subtle text-success border border-success-subtle py-1" style="font-size: 0.72rem;">
                                <i class="bi bi-patch-check-fill me-1"></i> Consumer E-Signed & Verified
                            </span>
                            <span class="text-white-50 small" style="font-size: 0.75rem;">
                                PM Surya Ghar: Muft Bijli Yojana (4 Pages)
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-light btn-sm fw-semibold" onclick="printAgreementModalIframe()" title="Print this Agreement">
                        <i class="bi bi-printer-fill me-1"></i> Print
                    </button>
                    <a href="<?= url('/customer/agreement') ?>" target="_blank" class="btn btn-warning btn-sm text-dark fw-bold" title="Open in full browser window">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Fullscreen
                    </a>
                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <!-- Modal Body with Embedded Iframe -->
            <div class="modal-body p-0" style="background-color: #f8fafc; min-height: 520px; height: 75vh; overflow-y: auto !important; -webkit-overflow-scrolling: touch;">
                <div id="agreementModalLoader" class="d-flex flex-column align-items-center justify-content-center h-100 py-5 text-secondary">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 2.5rem; height: 2.5rem;">
                        <span class="visually-hidden">Loading agreement...</span>
                    </div>
                    <div class="fw-semibold text-navy">Loading Signed Consumer Agreement (Annexure 2)...</div>
                    <small class="text-muted">Rendering 4-page contract with digital e-signatures</small>
                </div>
                <iframe id="agreementIframe" 
                        src="<?= url('/customer/agreement') ?>" 
                        style="width: 100%; height: 100%; border: 0; display: none;" 
                        onload="document.getElementById('agreementModalLoader').style.display='none'; this.style.display='block';">
                </iframe>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light px-4 py-2 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    Digitally executed under Ministry of New & Renewable Energy (MNRE) guidelines.
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm px-3 fw-bold" onclick="printAgreementModalIframe()">
                        <i class="bi bi-printer-fill me-1"></i> Print 4-Page Agreement
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printAgreementModalIframe() {
    var iframe = document.getElementById('agreementIframe');
    if (iframe) {
        try {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        } catch (e) {
            window.open('<?= url('/customer/agreement') ?>', '_blank');
        }
    } else {
        window.open('<?= url('/customer/agreement') ?>', '_blank');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var custModal = document.getElementById('modalConsumerAgreement');
    if (custModal && custModal.parentElement !== document.body) {
        document.body.appendChild(custModal);
    }
});
</script>
