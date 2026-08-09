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
    public static function optimize($path, $maxEdge = 800, $quality = 68)
    {
        try {
            if (!is_file($path)) {
                return;
            }
            // Re-encoding GIFs can break animation; leave them as uploaded.
            if (preg_match('/\.gif$/i', $path)) {
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
    public static function thumbnail($srcPath, $size = 240, $quality = 65)
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
    public static function process($path, $maxEdge = 800, $quality = 68, $thumbSize = 240)
    {
        self::optimize($path, $maxEdge, $quality);
        self::thumbnail($path, $thumbSize, 65);
    }

    /**
     * Run the right optimization profile after any image upload.
     * portrait = contestant/judge/ambassador (resize + thumbnail)
     * banner   = hero / popup (wide images + card thumb)
     * logo     = site logos and email headers
     * product  = tickets / shop
     */
    public static function afterUpload($path, $profile = 'portrait')
    {
        if (!is_file($path)) {
            return;
        }
        switch ($profile) {
            case 'banner':
                self::process($path, 1200, 65, 480);
                break;
            case 'product':
                self::process($path, 900, 68, 280);
                break;
            case 'logo':
                self::optimize($path, 600, 80);
                break;
            case 'portrait':
            default:
                self::process($path, 800, 68, 240);
                break;
        }
    }

    /**
     * Public URL for a contestant photo, preferring the small /thumbs version
     * (a few KB) when it exists so listings and carousels load fast. Falls back
     * to the full-size original if no thumbnail has been generated yet.
     */
    public static function employeeImageUrl($filename, $preferThumb = true)
    {
        static $urlCache = [];

        $filename = (string) $filename;
        if ($filename === '') {
            return url('public/images/employee/');
        }

        $cacheKey = ($preferThumb ? '1' : '0') . '|' . $filename;
        if (isset($urlCache[$cacheKey])) {
            return $urlCache[$cacheKey];
        }

        $rel = 'public/images/employee/' . $filename;
        if ($preferThumb) {
            $thumbRel = 'public/images/employee/thumbs/' . $filename;
            if (is_file(base_path($thumbRel))) {
                $rel = $thumbRel;
            }
        }

        $path = base_path($rel);
        // Avoid per-image filemtime on large mobile card grids; day bucket is enough for cache-bust.
        $version = is_file($path) ? date('Ymd', @filemtime($path) ?: time()) : date('Ymd');

        return $urlCache[$cacheKey] = url($rel) . '?v=' . $version;
    }

    /**
     * Prefer thumbs for any public/… image that has a sibling thumbs/ file.
     */
    public static function publicImageUrl($relativePath, $preferThumb = true)
    {
        $relativePath = ltrim((string) $relativePath, '/');
        if ($relativePath === '') {
            return url('public/');
        }
        if (strpos($relativePath, 'public/') !== 0) {
            $relativePath = 'public/' . $relativePath;
        }

        $rel = $relativePath;
        if ($preferThumb) {
            $dir = dirname($relativePath);
            $base = basename($relativePath);
            $thumbRel = $dir . '/thumbs/' . $base;
            if (is_file(base_path($thumbRel))) {
                $rel = $thumbRel;
            }
        }

        $path = base_path($rel);
        $version = is_file($path) ? date('Ymd', @filemtime($path) ?: time()) : date('Ymd');

        return url($rel) . '?v=' . $version;
    }
}
