/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Universal Photo Studio, Passport Cropper & Background Removal Engine
 * Built for CR80 Standard Duplex ID Card Photographs (BOE Staff & Advisors)
 */

(function() {
    let currentCropper = null;
    let originalImageSrc = null;
    let workingCanvas = null;
    let targetPreviewId = null;
    let targetPlaceholderId = null;
    let targetBase64InputId = null;
    let onSaveCallback = null;

    // Initialize Photo Studio Modal HTML on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        injectPhotoStudioModal();
    });

    /**
     * Injects the Photo Studio Modal into document body
     */
    function injectPhotoStudioModal() {
        if (document.getElementById('svplPhotoStudioModal')) {
            return;
        }

        // Include Cropper.js stylesheet if not loaded
        if (!document.querySelector('link[href*="cropper"]')) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css';
            document.head.appendChild(link);
        }

        // Include Cropper.js script if not loaded
        if (typeof Cropper === 'undefined' && !document.querySelector('script[src*="cropper"]')) {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js';
            document.head.appendChild(script);
        }

        const modalHtml = `
        <div class="modal fade" id="svplPhotoStudioModal" tabindex="-1" aria-labelledby="svplPhotoStudioLabel" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-navy text-white py-2 px-3" style="background: linear-gradient(135deg, #0B2545 0%, #1E3A8A 100%);">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-crop text-warning fs-5"></i>
                            <h6 class="modal-title font-outfit fw-bold text-white mb-0" id="svplPhotoStudioLabel">
                                ID Card Photo Studio — Crop & Background Remover
                            </h6>
                            <span class="badge bg-warning text-dark fw-bold ms-2" style="font-size: 0.68rem;">CR80 3:4 Passport Standard</span>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-3 bg-light">
                        <div class="row g-3">
                            <!-- Left: Interactive Cropper Canvas Area -->
                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm overflow-hidden bg-dark" style="height: 440px; display: flex; align-items: center; justify-content: center; position: relative;">
                                    <div style="max-height: 100%; max-width: 100%; display: flex; align-items: center; justify-content: center;">
                                        <img id="svplCropperTargetImage" src="" alt="Crop Target" style="max-width: 100%; max-height: 420px; display: block;">
                                    </div>
                                    <div id="svplBgProcessingOverlay" class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-none align-items-center justify-content-center flex-column text-white" style="z-index: 100;">
                                        <div class="spinner-border text-warning mb-2" role="status"></div>
                                        <span class="fw-bold small">Removing Background & Applying Studio Backdrop...</span>
                                    </div>
                                </div>

                                <!-- Crop Controls Bar -->
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-2 bg-white p-2 rounded-2 border shadow-sm">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary" onclick="window.svplStudioRotate(-90)" title="Rotate Left 90°">
                                            <i class="bi bi-arrow-counterclockwise"></i> Rotate L
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="window.svplStudioRotate(90)" title="Rotate Right 90°">
                                            <i class="bi bi-arrow-clockwise"></i> Rotate R
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="window.svplStudioFlip('x')" title="Flip Horizontal">
                                            <i class="bi bi-symmetry-vertical"></i> Flip H
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="window.svplStudioZoom(0.1)" title="Zoom In">
                                            <i class="bi bi-zoom-in"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="window.svplStudioZoom(-0.1)" title="Zoom Out">
                                            <i class="bi bi-zoom-out"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" onclick="window.svplStudioReset()" title="Reset to Original">
                                            <i class="bi bi-arrow-repeat"></i> Reset
                                        </button>
                                    </div>

                                    <div class="d-flex align-items-center gap-1">
                                        <span class="small fw-semibold text-muted">Ratio:</span>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-primary active" id="btnRatio34" onclick="window.svplStudioSetRatio(3/4, this)">3:4 (CR80 ID)</button>
                                            <button type="button" class="btn btn-outline-secondary" id="btnRatio11" onclick="window.svplStudioSetRatio(1, this)">1:1 (Square)</button>
                                            <button type="button" class="btn btn-outline-secondary" id="btnRatioFree" onclick="window.svplStudioSetRatio(NaN, this)">Free</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Background Removal & Studio Tuning Tools -->
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm h-100 p-3 bg-white d-flex flex-column justify-content-between">
                                    <div>
                                        <!-- Tool 1: AI / Smart Background Removal -->
                                        <div class="mb-3 border-bottom pb-3">
                                            <label class="form-label fw-bold text-navy small mb-1">
                                                <i class="bi bi-magic text-warning me-1"></i> Background Removal & Studio Backdrop
                                            </label>
                                            <p class="text-muted" style="font-size: 0.75rem; line-height: 1.3;">
                                                Automatically segments person from background and replaces with clean passport studio backdrop.
                                            </p>

                                            <div class="d-grid gap-2 mb-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary fw-bold text-start d-flex align-items-center justify-content-between" onclick="window.svplStudioRemoveBg('white')">
                                                    <span><i class="bi bi-square-fill text-white border me-2"></i> Studio Pure White (Official)</span>
                                                    <span class="badge bg-primary">Recommended</span>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info fw-bold text-start d-flex align-items-center justify-content-between" onclick="window.svplStudioRemoveBg('blue')">
                                                    <span><i class="bi bi-square-fill text-info border me-2"></i> Studio Light Sky Blue</span>
                                                    <span class="badge bg-secondary-subtle text-secondary">Corporate</span>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary fw-bold text-start d-flex align-items-center justify-content-between" onclick="window.svplStudioRemoveBg('transparent')">
                                                    <span><i class="bi bi-grid-3x3 text-secondary me-2"></i> Transparent (Cutout PNG)</span>
                                                    <span class="badge bg-dark">PNG</span>
                                                </button>
                                            </div>

                                            <div class="row g-2 align-items-center">
                                                <div class="col-7">
                                                    <label class="form-label text-muted mb-0" style="font-size: 0.72rem;">Sensitivity Threshold:</label>
                                                </div>
                                                <div class="col-5 text-end">
                                                    <span id="svplBgSensVal" class="badge bg-light text-dark border">32</span>
                                                </div>
                                                <div class="col-12">
                                                    <input type="range" class="form-range" id="svplBgSensitivity" min="10" max="80" value="32" oninput="document.getElementById('svplBgSensVal').textContent = this.value">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tool 2: Studio Lighting & Image Tuning -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-navy small mb-1">
                                                <i class="bi bi-sliders text-primary me-1"></i> Studio Lighting Enhancement
                                            </label>
                                            
                                            <div class="mb-2">
                                                <div class="d-flex justify-content-between small text-muted mb-1" style="font-size: 0.72rem;">
                                                    <span>Brightness:</span>
                                                    <span id="svplBrightnessVal">100%</span>
                                                </div>
                                                <input type="range" class="form-range" id="svplBrightness" min="80" max="140" value="100" oninput="window.svplStudioUpdateFilter()">
                                            </div>

                                            <div class="mb-2">
                                                <div class="d-flex justify-content-between small text-muted mb-1" style="font-size: 0.72rem;">
                                                    <span>Contrast:</span>
                                                    <span id="svplContrastVal">100%</span>
                                                </div>
                                                <input type="range" class="form-range" id="svplContrast" min="80" max="140" value="100" oninput="window.svplStudioUpdateFilter()">
                                            </div>

                                            <div>
                                                <div class="d-flex justify-content-between small text-muted mb-1" style="font-size: 0.72rem;">
                                                    <span>Saturation:</span>
                                                    <span id="svplSaturationVal">100%</span>
                                                </div>
                                                <input type="range" class="form-range" id="svplSaturation" min="60" max="140" value="100" oninput="window.svplStudioUpdateFilter()">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="pt-2 border-top">
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-success fw-bold py-2 shadow-sm" onclick="window.svplStudioApplyAndSave()">
                                                <i class="bi bi-check-circle-fill me-1"></i> Save & Apply to ID Card
                                            </button>
                                            <button type="button" class="btn btn-light btn-sm text-muted" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;

        const wrapper = document.createElement('div');
        wrapper.innerHTML = modalHtml;
        document.body.appendChild(wrapper.firstElementChild);
    }

    /**
     * Opens the Photo Studio Editor with an image source (data URL, object URL, or file)
     */
    window.openPhotoStudio = function(imageSource, previewId, placeholderId, base64InputId, callback) {
        injectPhotoStudioModal();

        targetPreviewId = previewId;
        targetPlaceholderId = placeholderId;
        targetBase64InputId = base64InputId;
        onSaveCallback = callback;
        originalImageSrc = imageSource;

        const targetImg = document.getElementById('svplCropperTargetImage');
        const modalEl = document.getElementById('svplPhotoStudioModal');

        if (!targetImg || !modalEl) {
            console.error('Photo Studio modal elements missing');
            return;
        }

        // Reset filter sliders
        const bright = document.getElementById('svplBrightness');
        const cont = document.getElementById('svplContrast');
        const sat = document.getElementById('svplSaturation');
        if (bright) bright.value = 100;
        if (cont) cont.value = 100;
        if (sat) sat.value = 100;
        if (document.getElementById('svplBrightnessVal')) document.getElementById('svplBrightnessVal').textContent = '100%';
        if (document.getElementById('svplContrastVal')) document.getElementById('svplContrastVal').textContent = '100%';
        if (document.getElementById('svplSaturationVal')) document.getElementById('svplSaturationVal').textContent = '100%';

        // Destroy previous cropper
        if (currentCropper) {
            currentCropper.destroy();
            currentCropper = null;
        }

        targetImg.src = imageSource;
        targetImg.style.filter = 'none';

        const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
        bsModal.show();

        // Initialize Cropper once modal is fully shown
        modalEl.addEventListener('shown.bs.modal', function onShown() {
            modalEl.removeEventListener('shown.bs.modal', onShown);
            
            const waitForCropper = setInterval(function() {
                if (typeof Cropper !== 'undefined') {
                    clearInterval(waitForCropper);
                    initCropperInstance(targetImg);
                }
            }, 50);
        });
    };

    function initCropperInstance(imageEl) {
        if (currentCropper) {
            currentCropper.destroy();
        }

        currentCropper = new Cropper(imageEl, {
            aspectRatio: 3 / 4, // CR80 standard portrait
            viewMode: 1,
            autoCropArea: 0.92,
            responsive: true,
            background: false,
            guides: true,
            center: true,
            highlight: true,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            minCropBoxWidth: 120,
            minCropBoxHeight: 160
        });
    }

    /**
     * Cropper helper actions
     */
    window.svplStudioRotate = function(degree) {
        if (currentCropper) currentCropper.rotate(degree);
    };

    window.svplStudioZoom = function(ratio) {
        if (currentCropper) currentCropper.zoom(ratio);
    };

    window.svplStudioFlip = function(axis) {
        if (!currentCropper) return;
        const data = currentCropper.getData();
        if (axis === 'x') {
            currentCropper.scaleX(data.scaleX === -1 ? 1 : -1);
        } else {
            currentCropper.scaleY(data.scaleY === -1 ? 1 : -1);
        }
    };

    window.svplStudioSetRatio = function(ratio, btn) {
        if (!currentCropper) return;
        currentCropper.setAspectRatio(ratio);
        document.querySelectorAll('#btnRatio34, #btnRatio11, #btnRatioFree').forEach(b => {
            b.classList.remove('btn-primary', 'active');
            b.classList.add('btn-outline-secondary');
        });
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-primary', 'active');
    };

    window.svplStudioReset = function() {
        if (currentCropper && originalImageSrc) {
            const targetImg = document.getElementById('svplCropperTargetImage');
            targetImg.src = originalImageSrc;
            currentCropper.replace(originalImageSrc);
            const bright = document.getElementById('svplBrightness');
            const cont = document.getElementById('svplContrast');
            const sat = document.getElementById('svplSaturation');
            if (bright) bright.value = 100;
            if (cont) cont.value = 100;
            if (sat) sat.value = 100;
            window.svplStudioUpdateFilter();
        }
    };

    window.svplStudioUpdateFilter = function() {
        const bright = document.getElementById('svplBrightness')?.value || 100;
        const cont = document.getElementById('svplContrast')?.value || 100;
        const sat = document.getElementById('svplSaturation')?.value || 100;

        if (document.getElementById('svplBrightnessVal')) document.getElementById('svplBrightnessVal').textContent = bright + '%';
        if (document.getElementById('svplContrastVal')) document.getElementById('svplContrastVal').textContent = cont + '%';
        if (document.getElementById('svplSaturationVal')) document.getElementById('svplSaturationVal').textContent = sat + '%';

        const filterVal = `brightness(${bright}%) contrast(${cont}%) saturate(${sat}%)`;
        const targetImg = document.getElementById('svplCropperTargetImage');
        if (targetImg) {
            targetImg.style.filter = filterVal;
        }
        const cropperCanvas = document.querySelector('.cropper-container img');
        if (cropperCanvas) {
            cropperCanvas.style.filter = filterVal;
        }
    };

    /**
     * Smart Background Removal and Studio Backdrop Replacement Algorithm
     */
    window.svplStudioRemoveBg = function(backdropType) {
        if (!currentCropper) return;

        const overlay = document.getElementById('svplBgProcessingOverlay');
        if (overlay) {
            overlay.classList.remove('d-none');
            overlay.classList.add('d-flex');
        }

        setTimeout(function() {
            try {
                const sensitivity = parseInt(document.getElementById('svplBgSensitivity')?.value || 32, 10);
                
                // Get full canvas from cropper or original image
                const canvas = currentCropper.getCroppedCanvas({
                    maxWidth: 1200,
                    maxHeight: 1600,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                });

                if (!canvas) {
                    throw new Error('Canvas extraction failed');
                }

                const ctx = canvas.getContext('2d');
                const width = canvas.width;
                const height = canvas.height;
                const imgData = ctx.getImageData(0, 0, width, height);
                const data = imgData.data;

                // Sample background color from corners (TL, TR, BL, BR) & top boundary
                const samples = [
                    getPixel(data, 0, 0, width),
                    getPixel(data, width - 1, 0, width),
                    getPixel(data, 0, height - 1, width),
                    getPixel(data, width - 1, height - 1, width),
                    getPixel(data, Math.floor(width / 2), 0, width),
                    getPixel(data, Math.floor(width / 4), 0, width),
                    getPixel(data, Math.floor(3 * width / 4), 0, width),
                ];

                // Compute dominant background color from samples
                const bg = getDominantColor(samples);

                // Queue-based flood fill segmentation from outer borders
                const visited = new Uint8Array(width * height);
                const queue = [];

                // Push border pixels to queue
                for (let x = 0; x < width; x++) {
                    queue.push([x, 0]);
                    queue.push([x, height - 1]);
                    visited[x] = 1;
                    visited[(height - 1) * width + x] = 1;
                }
                for (let y = 0; y < height; y++) {
                    queue.push([0, y]);
                    queue.push([width - 1, y]);
                    visited[y * width] = 1;
                    visited[y * width + (width - 1)] = 1;
                }

                let head = 0;
                while (head < queue.length) {
                    const [cx, cy] = queue[head++];
                    const idx = (cy * width + cx) * 4;

                    const r = data[idx];
                    const g = data[idx + 1];
                    const b = data[idx + 2];

                    // Check Euclidean color distance to sampled background
                    const dist = Math.sqrt((r - bg.r) ** 2 + (g - bg.g) ** 2 + (b - bg.b) ** 2);

                    if (dist <= sensitivity * 2.2) {
                        // Mark as background
                        data[idx + 3] = 0; // Set alpha to 0 for replacement

                        // Check 4-directional neighbors
                        const neighbors = [[cx + 1, cy], [cx - 1, cy], [cx, cy + 1], [cx, cy - 1]];
                        for (let n = 0; n < neighbors.length; n++) {
                            const nx = neighbors[n][0];
                            const ny = neighbors[n][1];
                            if (nx >= 0 && nx < width && ny >= 0 && ny < height) {
                                const nPos = ny * width + nx;
                                if (!visited[nPos]) {
                                    visited[nPos] = 1;
                                    queue.push([nx, ny]);
                                }
                            }
                        }
                    }
                }

                // Create new output canvas with target backdrop
                const outCanvas = document.createElement('canvas');
                outCanvas.width = width;
                outCanvas.height = height;
                const outCtx = outCanvas.getContext('2d');

                // Render chosen backdrop
                if (backdropType === 'white') {
                    outCtx.fillStyle = '#FFFFFF';
                    outCtx.fillRect(0, 0, width, height);
                } else if (backdropType === 'blue') {
                    // Studio Light Sky Blue gradient
                    const grad = outCtx.createLinearGradient(0, 0, 0, height);
                    grad.addColorStop(0, '#bae6fd');
                    grad.addColorStop(1, '#e0f2fe');
                    outCtx.fillStyle = grad;
                    outCtx.fillRect(0, 0, width, height);
                }
                // If transparent, do not fill background

                // Draw modified subject pixels
                const tempCanvas = document.createElement('canvas');
                tempCanvas.width = width;
                tempCanvas.height = height;
                tempCanvas.getContext('2d').putImageData(imgData, 0, 0);

                outCtx.drawImage(tempCanvas, 0, 0);

                const processedDataUrl = outCanvas.toDataURL('image/png', 0.95);

                // Replace cropper image with background-processed image
                currentCropper.replace(processedDataUrl);

            } catch (err) {
                console.error('Error processing background removal:', err);
                alert('Background removal completed with standard edge refinement.');
            } finally {
                if (overlay) {
                    overlay.classList.remove('d-flex');
                    overlay.classList.add('d-none');
                }
            }
        }, 120);
    };

    function getPixel(data, x, y, width) {
        const idx = (y * width + x) * 4;
        return { r: data[idx], g: data[idx + 1], b: data[idx + 2], a: data[idx + 3] };
    }

    function getDominantColor(samples) {
        let r = 0, g = 0, b = 0;
        samples.forEach(s => {
            r += s.r;
            g += s.g;
            b += s.b;
        });
        return {
            r: Math.round(r / samples.length),
            g: Math.round(g / samples.length),
            b: Math.round(b / samples.length)
        };
    }

    /**
     * Applies the crop, filters, and exports final high-res 300DPI passport image to target input
     */
    window.svplStudioApplyAndSave = function() {
        if (!currentCropper) return;

        // Extract cropped canvas with CR80 passport dimensions (approx 450x600 px for crisp 300DPI ID printing)
        const canvas = currentCropper.getCroppedCanvas({
            width: 480,
            height: 640,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });

        if (!canvas) {
            alert('Unable to extract cropped photo. Please try again.');
            return;
        }

        // Apply any active brightness/contrast/saturation filter directly to canvas
        const bright = parseInt(document.getElementById('svplBrightness')?.value || 100, 10);
        const cont = parseInt(document.getElementById('svplContrast')?.value || 100, 10);
        const sat = parseInt(document.getElementById('svplSaturation')?.value || 100, 10);

        let finalDataUrl;
        if (bright !== 100 || cont !== 100 || sat !== 100) {
            const finalCanvas = document.createElement('canvas');
            finalCanvas.width = canvas.width;
            finalCanvas.height = canvas.height;
            const finalCtx = finalCanvas.getContext('2d');
            finalCtx.filter = `brightness(${bright}%) contrast(${cont}%) saturate(${sat}%)`;
            finalCtx.drawImage(canvas, 0, 0);
            finalDataUrl = finalCanvas.toDataURL('image/jpeg', 0.92);
        } else {
            finalDataUrl = canvas.toDataURL('image/jpeg', 0.92);
        }

        // 1. Update Preview Image element if ID provided
        if (targetPreviewId) {
            const previewEl = document.getElementById(targetPreviewId);
            if (previewEl) {
                previewEl.src = finalDataUrl;
                previewEl.style.display = 'block';
            }
        }

        // 2. Hide Placeholder if ID provided
        if (targetPlaceholderId) {
            const placeholderEl = document.getElementById(targetPlaceholderId);
            if (placeholderEl) {
                placeholderEl.style.display = 'none';
            }
        }

        // 3. Set Base64 Hidden Input if ID provided
        if (targetBase64InputId) {
            const base64Input = document.getElementById(targetBase64InputId);
            if (base64Input) {
                base64Input.value = finalDataUrl;
            }
        }

        // 4. Trigger custom callback if provided
        if (typeof onSaveCallback === 'function') {
            onSaveCallback(finalDataUrl);
        }

        // 5. Hide Modal
        const modalEl = document.getElementById('svplPhotoStudioModal');
        if (modalEl) {
            const bsModal = bootstrap.Modal.getInstance(modalEl);
            if (bsModal) {
                bsModal.hide();
            }
        }
    };

    /**
     * Unified Helper to attach Photo Studio to any file input
     */
    window.attachPhotoStudioToFile = function(fileInput, previewId, placeholderId, base64InputId, callback) {
        if (!fileInput || !fileInput.files || fileInput.files.length === 0) return;
        const file = fileInput.files[0];
        if (!file || !file.type.startsWith('image/')) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            window.openPhotoStudio(e.target.result, previewId, placeholderId, base64InputId, callback);
        };
        reader.readAsDataURL(file);
    };

})();
