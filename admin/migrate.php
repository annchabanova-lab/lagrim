<?php
require_once 'config.php';
require_login();

$defaults = [
    'fr/homepage.json' => ['nested' => true, 'val' => 'images/lg-P13-facade-jour-banc'],
    'en/homepage.json' => ['nested' => true, 'val' => 'images/lg-P13-facade-jour-banc'],
    'fr/la-maison.json' => ['nested' => false, 'val' => 'images/lg-P09-chambre-poutres-voilages'],
    'en/the-house.json' => ['nested' => false, 'val' => 'images/lg-P09-chambre-poutres-voilages'],
    'fr/chambres.json' => ['nested' => false, 'val' => 'images/lg-P13-facade-jour-banc'],
    'en/bedrooms.json' => ['nested' => false, 'val' => 'images/lg-P13-facade-jour-banc'],
    'fr/autour.json' => ['nested' => false, 'val' => 'images/lg-P05-arche-jardin'],
    'en/nearby.json' => ['nested' => false, 'val' => 'images/lg-P05-arche-jardin'],
    'fr/tarifs.json' => ['nested' => false, 'val' => 'images/lg-P06-maison-nuit'],
    'en/rates.json' => ['nested' => false, 'val' => 'images/lg-P06-maison-nuit'],
    'fr/faq.json' => ['nested' => false, 'val' => 'images/lg-P06-maison-nuit'],
    'en/faq.json' => ['nested' => false, 'val' => 'images/lg-P06-maison-nuit'],
];

$out = '';
foreach ($defaults as $file => $info) {
    $fp = CONTENT_DIR . $file;
    if (!file_exists($fp)) { $out .= "SKIP $file\n"; continue; }
    $data = json_decode(file_get_contents($fp), true);
    if (!is_array($data)) { $out .= "SKIP $file bad json\n"; continue; }

    $changed = false;
    if ($info['nested']) {
        if (!isset($data['cta'])) $data['cta'] = [];
        if (empty($data['cta']['image'])) { $data['cta']['image'] = $info['val']; $changed = true; }
    } else {
        if (empty($data['cta_image'])) { $data['cta_image'] = $info['val']; $changed = true; }
    }

    if ($changed) {
        file_put_contents($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $out .= "ADDED >> $file\n";
    } else {
        $out .= "OK $file\n";
    }
}

echo "<pre>" . htmlspecialchars($out) . "Done!</pre>";
echo "<p><a href='dashboard.php'>Back to dashboard</a></p>";
