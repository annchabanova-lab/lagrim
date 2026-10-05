<?php
require_once 'config.php';
require_login();

$data = json_decode(file_get_contents(__DIR__ . '/../content/fr/homepage.json'), true) ?: [];
$heroImg = $data['hero']['image'] ?? 'NOT SET';

echo '<pre>';
echo "Hero image path in JSON: " . $heroImg . "\n\n";

$sizes = ['sm', 'md', 'lg'];
foreach ($sizes as $s) {
    $path = __DIR__ . '/../' . $heroImg . '-' . $s . '.webp';
    echo $heroImg . '-' . $s . '.webp: ' . (file_exists($path) ? 'EXISTS (' . filesize($path) . ' bytes)' : 'MISSING') . "\n";
}

$jpg = __DIR__ . '/../' . $heroImg . '.jpg';
echo $heroImg . '.jpg: ' . (file_exists($jpg) ? 'EXISTS (' . filesize($jpg) . ' bytes)' : 'MISSING') . "\n";
echo '</pre>';
