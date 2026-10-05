<?php
require_once 'config.php';
require_login();

$jsonPath = __DIR__ . '/../content/fr/homepage.json';
$data = json_decode(file_get_contents($jsonPath), true) ?: [];
$heroImg = $data['hero']['image'] ?? 'NOT SET';

// Fix action — both FR and EN
if (isset($_GET['fix'])) {
    $data['hero']['image'] = 'images/lg-photo-20261005-204410';
    file_put_contents($jsonPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    $enPath = __DIR__ . '/../content/en/homepage.json';
    $enData = json_decode(file_get_contents($enPath), true) ?: [];
    $enData['hero']['image'] = 'images/lg-photo-20261005-204410';
    file_put_contents($enPath, json_encode($enData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    header('Location: debug-hero.php?fixed=1');
    exit;
}

echo '<h3>JSON hero.image</h3>';
echo '<pre>' . htmlspecialchars($heroImg) . '</pre>';

echo '<h3>What the template builds</h3><pre>';
echo 'srcset: ' . $heroImg . '-sm.webp, ' . $heroImg . '-md.webp, ' . $heroImg . '-lg.webp' . "\n";
echo 'img src: ' . $heroImg . '.jpg' . "\n";
echo '</pre>';

echo '<h3>Do those files exist?</h3><pre>';
$base = __DIR__ . '/../';
foreach (['-sm.webp', '-md.webp', '-lg.webp', '.jpg'] as $suffix) {
    $full = $base . $heroImg . $suffix;
    echo $heroImg . $suffix . ' → ' . (file_exists($full) ? 'YES (' . round(filesize($full)/1024) . ' KB)' : 'NO') . "\n";
}
echo '</pre>';

// Show the actual rendered HTML the browser would see
echo '<h3>Rendered picture element</h3>';
echo '<picture>';
echo '<source type="image/webp" srcset="/' . $heroImg . '-sm.webp 400w, /' . $heroImg . '-md.webp 800w, /' . $heroImg . '-lg.webp 1600w" sizes="100vw">';
echo '<img src="/' . $heroImg . '.jpg" alt="test" style="max-width:400px">';
echo '</picture>';

if (isset($_GET['fixed'])) {
    echo '<p style="color:green;font-weight:bold;margin-top:1rem;">JSON updated!</p>';
}

echo '<p style="margin-top:1rem;"><a href="?fix=1" style="color:green;font-weight:bold;">Fix JSON → images/lg-photo-20261005-204410</a></p>';
echo '<p><a href="/" target="_blank">Open homepage</a></p>';
