/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Universal Client-Side Image Compression & Mobile Camera Capture Engine
 * Automatically resizes & compresses high-resolution smartphone camera photos before upload
 */

document.addEventListener('DOMContentLoaded', function() {
    initUniversalImageCompressor();
});

function initUniversalImageCompressor() {
    document.addEventListener('change', function(e) {
        const input = e.target;
        if (!input || input.type !== 'file' || !input.files || input.files.length === 0) {
            return;
        }

        // Ignore database backup .sql files
        if (input.name === 'backup_file' || (input.accept && input.accept.includes('.sql'))) {
            return;
        }

        const file = input.files[0];
        if (!file || !file.type.startsWith('image/')) {
            return; // Only compress image files (PDFs passed through as-is)
        }

        // If file size is already very small (< 150 KB), skip compression
        if (file.size < 150 * 1024) {
            return;
        }

        compressImageFile(file, {
            maxWidth: 1600,
            maxHeight: 1600,
            quality: 0.78
        }, function(compressedFile, originalSize, compressedSize) {
            // Replace input file with compressed file using DataTransfer API
            try {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressedFile);
                input.files = dataTransfer.files;

                // Display user-friendly compression feedback badge
                showCompressionBadge(input, originalSize, compressedSize);
            } catch (err) {
                console.warn('DataTransfer input update not supported:', err);
            }
        });
    });
}

/**
 * Resizes and compresses an Image File using HTML5 Canvas
 */
function compressImageFile(file, options, callback) {
    const maxWidth = options.maxWidth || 1600;
    const maxHeight = options.maxHeight || 1600;
    const quality = options.quality || 0.78;

    const reader = new FileReader();
    reader.readAsDataURL(file);

    reader.onload = function(event) {
        const img = new Image();
        img.src = event.target.result;

        img.onload = function() {
            let width = img.width;
            let height = img.height;

            // Calculate proportional scaled dimensions
            if (width > height) {
                if (width > maxWidth) {
                    height = Math.round((height * maxWidth) / width);
                    width = maxWidth;
                }
            } else {
                if (height > maxHeight) {
                    width = Math.round((width * maxHeight) / height);
                    height = maxHeight;
                }
            }

            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            // Convert canvas to JPEG blob
            canvas.toBlob(function(blob) {
                if (!blob) {
                    callback(file, file.size, file.size);
                    return;
                }

                // If compressed blob is somehow larger than original, keep original
                if (blob.size >= file.size) {
                    callback(file, file.size, file.size);
                    return;
                }

                const compressedFileName = file.name.replace(/\.[^/.]+$/, "") + "_compressed.jpg";
                const compressedFile = new File([blob], compressedFileName, {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });

                callback(compressedFile, file.size, blob.size);
            }, 'image/jpeg', quality);
        };
    };
}

/**
 * Renders a visual compression feedback badge under the input element
 */
function showCompressionBadge(inputElement, origBytes, compBytes) {
    const origMB = (origBytes / (1024 * 1024)).toFixed(2);
    const compKB = (compBytes / 1024).toFixed(0);
    const savedPercent = Math.round(((origBytes - compBytes) / origBytes) * 100);

    let badge = inputElement.parentNode.querySelector('.image-compression-badge');
    if (!badge) {
        badge = document.createElement('div');
        badge.className = 'image-compression-badge mt-1';
        inputElement.parentNode.appendChild(badge);
    }

    badge.innerHTML = `<span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold" style="font-size: 0.72rem;">
        <i class="bi bi-file-earmark-zip me-1"></i> Auto-Compressed: <strong>${origMB} MB</strong> → <strong>${compKB} KB</strong> (${savedPercent}% saved)
    </span>`;
}
