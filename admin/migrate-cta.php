<?php
require_once 'config.php';
require_login();

$defaults = [
    'fr/homepage.json' => ['nested' => true, 'value' => 'images/lg-P13-facade-jour-banc'],
    'en/homepage.json' => ['nested' => true, 'value' => 'images/lg-P13-facade-jour-banc'],
    'fr/la-maison.json' => ['nested' => false, 'value' => 'images/lg-P09-chambre-poutres-voilages'],
    'en/the-house.json' => ['nested' => false, 'value' => 'images/lg-P09-chambre-poutres-voilages'],
    'fr/chambres.json' => ['nested' => false, 'value' => 'images/lg-P13-facade-jour-banc'],
    'en/bedrooms.json' => ['nested' => false, 'value' => 'images/lg-P13-facade-jour-banc'],
    'fr/autour.json' => ['nested' => false, 'value' => 'images/lg-P05-arche-jardin'],
    'en/nearby.json' => ['nested' => false, 'value' => 'images/lg-P05-arche-jardin'],
    'fr/tarifs.json' => ['nested' => false, 'value' => 'images/lg-P06-maison-nuit'],
    'en/rates.json' => ['nested' => false, 'value' => 'images/lg-P06-maison-nuit'],
    'fr/faq.json' => ['nested' => false, 'value' => 'images/lg-P06-maison-nuit'],
    'en/faq.json' => ['nested' => false, 'value' => 'images/lg-P06-maison-nuit'],
];

$out = '';
foreach ($defaults as $file => $info) {
    $fullPath = CONTENT_DIR . $file;
    if (!file_exists($fullPath)) {
        $out .= "SKIP " . $file . " - file not found\n";
        continue;
    }
    $data = json_decode(file_get_contents($fullPath), true);
    if (!is_array($data)) {
        $out .= "SKIP " . $file . " - invalid JSON\n";
        continue;
    }

    if ($info['nested']) {
        if (!isset($data['cta'])) $data['cta'] = [];
        if (!isset($data['cta']['image'])) {
            $data['cta']['image'] = $info['value'];
            $out .= "ADDED cta.image to " . $file . "\n";
        } else {
            $out .= "OK " . $file . " - cta.image exists\n";
        }
    } else {
        if (!isset($data['cta_image'])) {
            $data['cta_image'] = $info['value'];
            $out .= "ADDED cta_image to " . $file . "\n";
        } else {
            $out .= "OK " . $file . " - cta_image exists\n";
        }
    }

    file_put_contents($fullPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

echo "<pre>" . htmlspecialchars($out) . "\nDone!</pre>";
echo "<p><a href='dashboard.php'>Back to dashboard</a></p>";
