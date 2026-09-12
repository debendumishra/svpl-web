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

    /**
     * Resizes and compresses a passport photo from base64 string or file path to high-efficiency low-KB output (30-65 KB).
     * Standard dimensions: 480x640 px (3:4 ratio), Quality: 75%.
     */
    public static function compressPassportPhoto(string $inputDataOrPath, string $targetDirectory, string $prefix = 'id_card_photo', int $quality = 75): ?string
    {
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }

        $imageData = null;
        if (strpos($inputDataOrPath, 'data:image') === 0) {
            $parts = explode(',', $inputDataOrPath);
            if (count($parts) === 2) {
                $imageData = base64_decode($parts[1]);
            }
        } elseif (file_exists($inputDataOrPath)) {
            $imageData = file_get_contents($inputDataOrPath);
        }

        if (!$imageData || !function_exists('imagecreatefromstring')) {
            return null;
        }

        $srcImage = @imagecreatefromstring($imageData);
        if (!$srcImage) {
            return null;
        }

        $origWidth = imagesx($srcImage);
        $origHeight = imagesy($srcImage);

        // Standard passport CR80 resolution: 480x640 px
        $targetWidth = 480;
        $targetHeight = 640;

        $dstImage = imagecreatetruecolor($targetWidth, $targetHeight);
        
        // Fill clean white background
        $white = imagecolorallocate($dstImage, 255, 255, 255);
        imagefilledrectangle($dstImage, 0, 0, $targetWidth, $targetHeight, $white);

        // Aspect ratio fit/crop calculation
        $origAspect = $origWidth / $origHeight;
        $targetAspect = $targetWidth / $targetHeight;

        if ($origAspect > $targetAspect) {
            $sWidth = (int)round($origHeight * $targetAspect);
            $sHeight = $origHeight;
            $sx = (int)round(($origWidth - $sWidth) / 2);
            $sy = 0;
        } else {
            $sWidth = $origWidth;
            $sHeight = (int)round($origWidth / $targetAspect);
            $sx = 0;
            $sy = (int)round(($origHeight - $sHeight) / 2);
        }

        imagecopyresampled($dstImage, $srcImage, 0, 0, $sx, $sy, $targetWidth, $targetHeight, $sWidth, $sHeight);

        $filename = $prefix . '_' . time() . '_' . rand(1000, 9999) . '.jpg';
        $fullPath = rtrim($targetDirectory, '/\\') . DIRECTORY_SEPARATOR . $filename;

        imagejpeg($dstImage, $fullPath, $quality);

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return $filename;
    }
}
