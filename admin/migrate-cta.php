<?php
require_once 'config.php';
require_login();

$defaults = [
    'fr/homepage.json' => ['path' => 'cta.image', 'value' => 'images/lg-P13-facade-jour-banc'],
    'en/homepage.json' => ['path' => 'cta.image', 'value' => 'images/lg-P13-facade-jour-banc'],
    'fr/la-maison.json' => ['path' => 'cta_image', 'value' => 'images/lg-P09-chambre-poutres-voilages'],
    'en/the-house.json' => ['path' => 'cta_image', 'value' => 'images/lg-P09-chambre-poutres-voilages'],
    'fr/chambres.json' => ['path' => 'cta_image', 'value' => 'images/lg-P13-facade-jour-banc'],
    'en/bedrooms.json' => ['path' => 'cta_image', 'value' => 'images/lg-P13-facade-jour-banc'],
    'fr/autour.json' => ['path' => 'cta_image', 'value' => 'images/lg-P05-arche-jardin'],
    'en/nearby.json' => ['path' => 'cta_image', 'value' => 'images/lg-P05-arche-jardin'],
    'fr/tarifs.json' => ['path' => 'cta_image', 'value' => 'images/lg-P06-maison-nuit'],
    'en/rates.json' => ['path' => 'cta_image', 'value' => 'images/lg-P06-maison-nuit'],
    'fr/faq.json' => ['path' => 'cta_image', 'value' => 'images/lg-P06-maison-nuit'],
    'en/faq.json' => ['path' => 'cta_image', 'value' => 'images/lg-P06-maison-nuit'],
];

echo '<pre>';
foreach ($defaults as $file => $info) {
    $fullPath = CONTENT_DIR . $file;
    if (!file_exists($fullPath)) {
        echo "SKIP $file — not found\n";
        continue;
    }
    $data = json_decode(file_get_contents($fullPath), true) ?: [];

    if ($info['path'] === 'cta.image') {
        if (!isset($data['cta'])) $data['cta'] = [];
        if (!isset($data['cta']['image'])) {
            $data['cta']['image'] = $info['value'];
            echo "ADDED cta.image to $file\n";
        } else {
            echo "OK    cta.image already in $file\n";
        }
    } else {
        if (!isset($data[$info['path']])) {
            $data[$info['path']] = $info['value'];
            echo "ADDED {$info['path']} to $file\n";
        } else {
            echo "OK    {$info['path']} already in $file\n";
        }
    }

    file_put_contents($fullPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
echo "\nDone!\n</pre>";
echo '<p><a href="dashboard.php">Back to dashboard</a></p>';
