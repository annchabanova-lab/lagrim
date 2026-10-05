<?php
require_once 'config.php';
require_login();

$data = json_decode(file_get_contents(__DIR__ . '/../content/fr/homepage.json'), true) ?: [];
$heroImg = $data['hero']['image'] ?? 'NOT SET';

echo '<h3>JSON state</h3><pre>';
echo "hero.image = " . $heroImg . "\n";
echo '</pre>';

echo '<h3>File check</h3><pre>';
foreach (['sm', 'md', 'lg'] as $s) {
    $f = __DIR__ . '/../' . $heroImg . '-' . $s . '.webp';
    echo $heroImg . '-' . $s . '.webp → ' . (file_exists($f) ? 'OK (' . round(filesize($f)/1024) . ' KB)' : 'MISSING') . "\n";
}
$jpg = __DIR__ . '/../' . $heroImg . '.jpg';
echo $heroImg . '.jpg → ' . (file_exists($jpg) ? 'OK (' . round(filesize($jpg)/1024) . ' KB)' : 'MISSING') . "\n";
echo '</pre>';

echo '<h3>All lg-* files in images/</h3><pre>';
$files = glob(__DIR__ . '/../images/lg-*');
sort($files);
foreach ($files as $f) {
    echo basename($f) . ' (' . round(filesize($f)/1024) . ' KB)' . "\n";
}
echo '</pre>';

if (isset($_GET['fix'])) {
    $target = $_GET['fix'];
    $allowed = [
        'original' => 'images/lg-P06-maison-nuit',
        'uploaded' => 'images/lg-photo-20261005-204410',
    ];
    if (isset($allowed[$target])) {
        $data['hero']['image'] = $allowed[$target];
        file_put_contents(__DIR__ . '/../content/fr/homepage.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo '<p style="color:green;font-weight:bold;">Fixed! Set to ' . $allowed[$target] . '</p>';
    }
}
echo '<p><a href="?fix=original" style="color:blue;">Set to original image (lg-P06-maison-nuit)</a></p>';
echo '<p><a href="?fix=uploaded" style="color:green;">Set to uploaded image (lg-photo-20261005-204410)</a></p>';
