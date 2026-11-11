<?php

namespace App\Support;

use Exception;

/**
 * Resizes and re-encodes images with GD (no extra packages needed on the server).
 *
 * - Main images: longest side up to MAIN_MAX, WebP quality MAIN_QUALITY.
 * - Thumbnails: longest side up to THUMB_MAX, saved next to the image as "<name>_w600.webp".
 *   Used where images are shown small (product cards, cart, thumbnail strips): see thumb() in Helpers/General.php.
 */
class ImageOptimizer
{
    public const MAIN_MAX = 1600;
    public const MAIN_QUALITY = 78;
    public const THUMB_MAX = 600;
    public const THUMB_QUALITY = 72;
    public const THUMB_SUFFIX = '_w600.webp';

    /** "storage/products/1/123.webp" -> "storage/products/1/123_w600.webp" (any source extension). */
    public static function thumbPathFor(string $path): string
    {
        return preg_replace('/\.[A-Za-z0-9]+$/', '', $path) . self::THUMB_SUFFIX;
    }

    public static function isThumb(string $path): bool
    {
        return str_ends_with($path, self::THUMB_SUFFIX);
    }

    /** Load any GD-supported image; JPEG photos are turned upright using their EXIF orientation. */
    public static function load(string $file): \GdImage
    {
        $info = @getimagesize($file);
        if ($info === false) {
            throw new Exception('Invalid image file');
        }

        $image = match ($info[2]) {
            IMAGETYPE_JPEG, IMAGETYPE_JPEG2000 => @imagecreatefromjpeg($file),
            IMAGETYPE_PNG => @imagecreatefrompng($file),
            IMAGETYPE_GIF => @imagecreatefromgif($file),
            IMAGETYPE_BMP => @imagecreatefrombmp($file),
            IMAGETYPE_WEBP => @imagecreatefromwebp($file),
            IMAGETYPE_WBMP => @imagecreatefromwbmp($file),
            IMAGETYPE_XBM => @imagecreatefromxbm($file),
            default => throw new Exception('Unsupported image format: ' . ($info['mime'] ?? 'unknown')),
        };

        if (!$image) {
            throw new Exception('Failed to read image');
        }

        if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif = @exif_read_data($file);
            $rotate = match ((int) ($exif['Orientation'] ?? 1)) {
                3 => 180,
                6 => -90,
                8 => 90,
                default => 0,
            };
            if ($rotate) {
                $rotated = imagerotate($image, $rotate, 0);
                if ($rotated) {
                    imagedestroy($image);
                    $image = $rotated;
                }
            }
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        return $image;
    }

    /** Scale down (never up) so the longest side is at most $max. Returns the same image if it already fits. */
    public static function fit(\GdImage $image, int $max): \GdImage
    {
        $w = imagesx($image);
        $h = imagesy($image);
        if (max($w, $h) <= $max) {
            return $image;
        }

        $scale = $max / max($w, $h);
        $nw = max(1, (int) round($w * $scale));
        $nh = max(1, (int) round($h * $scale));

        $out = imagecreatetruecolor($nw, $nh);
        imagealphablending($out, false);
        imagesavealpha($out, true);
        imagefill($out, 0, 0, imagecolorallocatealpha($out, 0, 0, 0, 127));
        imagecopyresampled($out, $image, 0, 0, 0, 0, $nw, $nh, $w, $h);

        return $out;
    }

    public static function saveWebp(\GdImage $image, string $path, int $quality): void
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        if (!imagewebp($image, $path, $quality)) {
            throw new Exception('Failed to save image as WebP');
        }
    }

    /** Write the small copy for an image already on disk. Returns the thumbnail path. */
    public static function makeThumb(string $sourcePath, ?\GdImage $loaded = null): string
    {
        $image = $loaded ?? self::load($sourcePath);
        $small = self::fit($image, self::THUMB_MAX);
        $thumbPath = self::thumbPathFor($sourcePath);
        self::saveWebp($small, $thumbPath, self::THUMB_QUALITY);
        if ($small !== $image) {
            imagedestroy($small);
        }
        if (!$loaded) {
            imagedestroy($image);
        }

        return $thumbPath;
    }
}
