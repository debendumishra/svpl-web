<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Server-Side Image Compressor Helper (PHP GD Fallback)
 * Minimizes uploaded image sizes to save storage space and bandwidth
 */

namespace App\Helpers;

class ImageCompressor
{
    /**
     * Resizes and compresses an uploaded image file on the server.
     * Max dimensions: 1600x1600 px, Quality: 80%.
     */
    public static function compressIfNeeded(string $filePath, int $maxWidth = 1600, int $maxHeight = 1600, int $quality = 80): bool
    {
        if (!file_exists($filePath) || !function_exists('imagecreatefromstring')) {
            return false;
        }

        $fileSize = filesize($filePath);
        // Skip if already under 200 KB
        if ($fileSize < 200 * 1024) {
            return true;
        }

        $imageInfo = @getimagesize($filePath);
        if (!$imageInfo) {
            return false;
        }

        $mime = $imageInfo['mime'] ?? '';
        if (!in_array($mime, ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {
            return false; // Skip non-image files (e.g. PDFs)
        }

        $origWidth = $imageInfo[0];
        $origHeight = $imageInfo[1];

        // Read image
        $imageContent = file_get_contents($filePath);
        $srcImage = @imagecreatefromstring($imageContent);
        if (!$srcImage) {
            return false;
        }

        // Calculate target dimensions
        $targetWidth = $origWidth;
        $targetHeight = $origHeight;

        if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
            if ($origWidth > $origHeight) {
                $targetWidth = $maxWidth;
                $targetHeight = (int)round(($origHeight * $maxWidth) / $origWidth);
            } else {
                $targetHeight = $maxHeight;
                $targetWidth = (int)round(($origWidth * $maxHeight) / $origHeight);
            }
        }

        // Resample canvas
        $dstImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // Preserve alpha transparency for PNG/WEBP
        if ($mime === 'image/png' || $mime === 'image/webp') {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
            $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
            imagefilledrectangle($dstImage, 0, 0, $targetWidth, $targetHeight, $transparent);
        }

        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $origWidth, $origHeight);

        // Save compressed image as JPEG or WebP
        if ($mime === 'image/webp' && function_exists('imagewebp')) {
            imagewebp($dstImage, $filePath, $quality);
        } else {
            imagejpeg($dstImage, $filePath, $quality);
        }

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return true;
    }
}
