<?php
require_once 'config.php';
require_login();

// Fix the corrupted hero image path
$path = __DIR__ . '/../content/fr/homepage.json';
$data = json_decode(file_get_contents($path), true) ?: [];

if (isset($_GET['fix'])) {
    $data['hero']['image'] = 'images/lg-P06-maison-nuit';
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo '<p style="color:green;font-size:1.5rem;">FIXED! Hero image reset to images/lg-P06-maison-nuit</p>';
    echo '<p><a href="../">Check homepage</a></p>';
    exit;
}

$heroImg = $data['hero']['image'] ?? 'NOT SET';

echo '<pre>';
echo "Hero image path in JSON: " . $heroImg . "\n\n";

$sizes = ['sm', 'md', 'lg'];
foreach ($sizes as $s) {
    $file = __DIR__ . '/../' . $heroImg . '-' . $s . '.webp';
    echo $heroImg . '-' . $s . '.webp: ' . (file_exists($file) ? 'EXISTS (' . filesize($file) . ' bytes)' : 'MISSING') . "\n";
}

$jpg = __DIR__ . '/../' . $heroImg . '.jpg';
echo $heroImg . '.jpg: ' . (file_exists($jpg) ? 'EXISTS (' . filesize($jpg) . ' bytes)' : 'MISSING') . "\n";
echo '</pre>';
echo '<p><a href="?fix=1" style="color:red;font-size:1.2rem;">Click here to fix → reset to original image</a></p>';
