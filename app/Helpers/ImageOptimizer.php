<?php

namespace App\Helpers;

use Intervention\Image\ImageManagerStatic as Image;

/**
 * Lightweight image optimizer for uploaded photos.
 *
 * Heavy phone photos (often 3-8 MB) are downscaled and re-encoded so the site
 * serves small, fast-loading images. Everything is wrapped in try/catch so a
 * failure to optimize never blocks an upload. Designed for shared hosting.
 */
class ImageOptimizer
{
    /**
     * Downscale (in place) so the longest edge is <= $maxEdge and re-encode
     * with the given quality. Keeps aspect ratio, never upsizes.
     */
    public static function optimize($path, $maxEdge = 1000, $quality = 78)
    {
        try {
            if (!is_file($path)) {
                return;
            }
            @ini_set('memory_limit', '512M');

            $img = Image::make($path);
            if ($img->width() > $maxEdge || $img->height() > $maxEdge) {
                $img->resize($maxEdge, $maxEdge, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            $img->save($path, $quality);
            $img->destroy();
        } catch (\Throwable $e) {
            // Optimization is best-effort; keep the original on failure.
        }
    }

    /**
     * Create a small square-ish thumbnail next to the source in a /thumbs
     * subfolder (same filename). Used for fast listing tables.
     */
    public static function thumbnail($srcPath, $size = 320, $quality = 70)
    {
        try {
            if (!is_file($srcPath)) {
                return;
            }
            @ini_set('memory_limit', '512M');

            $dir = dirname($srcPath) . '/thumbs';
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }
            $dest = $dir . '/' . basename($srcPath);

            $img = Image::make($srcPath);
            $img->resize($size, $size, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($dest, $quality);
            $img->destroy();
        } catch (\Throwable $e) {
            // best-effort
        }
    }

    /**
     * Optimize the main image and also produce a thumbnail in one call.
     */
    public static function process($path, $maxEdge = 1000, $quality = 78, $thumbSize = 320)
    {
        self::optimize($path, $maxEdge, $quality);
        self::thumbnail($path, $thumbSize, 70);
    }

    /**
     * Public URL for a contestant photo, preferring the small /thumbs version
     * (a few KB) when it exists so listings and carousels load fast. Falls back
     * to the full-size original if no thumbnail has been generated yet.
     */
    public static function employeeImageUrl($filename, $preferThumb = true)
    {
        $filename = (string) $filename;
        if ($preferThumb && $filename !== '') {
            $thumbRel = 'public/images/employee/thumbs/' . $filename;
            if (is_file(base_path($thumbRel))) {
                return url($thumbRel);
            }
        }
        return url('public/images/employee/' . $filename);
    }
}
