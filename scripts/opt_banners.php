<?php
// One-off: compress heavy theme/banner images in place for faster LCP.
require __DIR__ . '/../vendor/autoload.php';

use Intervention\Image\ImageManagerStatic as Image;

@ini_set('memory_limit', '512M');

$files = [
    'public/frontend/images/top-banner2-en.jpg',
    'public/frontend/images/top-banner2-fr.jpg',
    'public/frontend/images/top-banner-en.jpg',
    'public/frontend/images/top-banner1-en.jpeg',
    'public/frontend/images/bottom-banner-en.jpeg',
    'public/frontend/images/bottom-banner-fr.jpeg',
    'public/frontend/images/ai.png',
    'public/frontend/images/event-event-bg-2.png',
    'public/frontend/images/team-team-01.jpg',
    'public/frontend/images/banner-thumb-01.jpg',
    'public/frontend/images/banner-banner-thumb-01.jpg',
    'public/frontend/images/banner-title-bg.jpg',
];

$root = realpath(__DIR__ . '/..');
$savedTotal = 0;
foreach ($files as $rel) {
    $p = $root . '/' . $rel;
    if (!is_file($p)) { echo "skip (missing): $rel\n"; continue; }
    $before = filesize($p);
    try {
        $img = Image::make($p);
        if ($img->width() > 1600 || $img->height() > 1600) {
            $img->resize(1600, 1600, function ($c) { $c->aspectRatio(); $c->upsize(); });
        }
        $img->save($p, 68);
        $img->destroy();
        clearstatcache();
        $after = filesize($p);
        $savedTotal += ($before - $after);
        printf("%-52s %6dKB -> %6dKB\n", basename($rel), $before / 1024, $after / 1024);
    } catch (\Throwable $e) {
        echo "ERR $rel: " . $e->getMessage() . "\n";
    }
}
printf("TOTAL SAVED: %d KB\n", $savedTotal / 1024);
