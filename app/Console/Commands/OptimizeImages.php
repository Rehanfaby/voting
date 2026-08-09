<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\ImageOptimizer;

/**
 * One-time / repeatable pass to shrink existing heavy images on disk.
 * Usage: php artisan images:optimize
 */
class OptimizeImages extends Command
{
    protected $signature = 'images:optimize {--max=800 : Max longest edge in px} {--quality=68 : JPEG/PNG quality}';

    protected $description = 'Downscale and compress existing site images (and build thumbnails) for faster loading';

    public function handle()
    {
        @ini_set('memory_limit', '512M');

        $max = (int) $this->option('max');
        $quality = (int) $this->option('quality');

        // folder => [makeThumb, thumbSize]
        $targets = [
            public_path('images/employee') => [true, 240],
            public_path('employee/data')   => [true, 480],
            public_path('images/product')  => [true, 280],
            public_path('images/category') => [true, 280],
            public_path('uploads/gallery') => [true, 480],
            public_path('images/gallery')  => [true, 480],
        ];

        $exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $total = 0;
        $savedBytes = 0;

        foreach ($targets as $dir => $opts) {
            list($makeThumb, $thumbSize) = $opts;
            if (!is_dir($dir)) {
                $this->warn("Skip (missing): {$dir}");
                continue;
            }
            $files = scandir($dir);
            foreach ($files as $file) {
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (!is_file($path)) {
                    continue; // skips ., .., and the thumbs/ subfolder
                }
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (!in_array($ext, $exts)) {
                    continue;
                }

                $before = filesize($path);
                ImageOptimizer::optimize($path, $max, $quality);
                if ($makeThumb) {
                    ImageOptimizer::thumbnail($path, $thumbSize, 65);
                }
                clearstatcache(true, $path);
                $after = filesize($path);

                $diff = max(0, $before - $after);
                $savedBytes += $diff;
                $total++;

                if ($total % 25 === 0) {
                    $this->line("Processed {$total} images...");
                }
            }
        }

        $this->info("Done. Optimized {$total} images, saved " . round($savedBytes / 1048576, 2) . " MB.");
        return 0;
    }
}
