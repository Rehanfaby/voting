<?php
/**
 * Add missing EN keys used on the frontend homepage.
 * Run: php scripts/sync_lang_en.php
 */

$enPath = __DIR__ . '/../resources/lang/en/file.php';
$en = include $enPath;

$extra = [
    'Grand Champions' => 'Grand Champions',
    'Our' => 'Our',
    'Winners' => 'Winners',
    'Casting Tour' => 'Casting Tour',
    'starts in' => 'starts in',
    'Darg' => 'Drag',
    'Switch Language' => 'Switch Language',
    'Login to validate ticket' => 'Login to validate ticket',
];

$merged = array_merge($en, $extra);

$lines = ["<?php\n", "return [\n"];
foreach ($merged as $key => $val) {
    $k = var_export($key, true);
    $v = var_export($val, true);
    $lines[] = "    {$k} => {$v},\n";
}
$lines[] = "];\n";

file_put_contents($enPath, implode('', $lines));
echo 'EN file updated: ' . count($merged) . " keys\n";
