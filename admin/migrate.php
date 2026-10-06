<?php
require_once 'config.php';
require_login();

$migrations = [
    'fr/chambres.json' => ['hero_heading' => 'Chambres', 'hero_image' => 'images/lg-extra-chambre-large'],
    'en/bedrooms.json' => ['hero_heading' => 'Bedrooms', 'hero_image' => 'images/lg-extra-chambre-large'],
    'fr/autour.json' => ['hero_heading' => 'Autour de la maison', 'hero_image' => 'images/lg-extra-vaches-normandie'],
    'en/nearby.json' => ['hero_heading' => 'Around the house', 'hero_image' => 'images/lg-extra-vaches-normandie'],
    'fr/tarifs.json' => ['hero_heading' => "Tarifs & disponibilit\u{00e9}s", 'hero_image' => 'images/lg-P13-facade-jour-banc'],
    'en/rates.json' => ['hero_heading' => 'Rates & availability', 'hero_image' => 'images/lg-P13-facade-jour-banc'],
    'fr/faq.json' => ['hero_heading' => 'Questions frequentes', 'hero_image' => 'images/lg-P06-maison-nuit'],
    'en/faq.json' => ['hero_heading' => 'Frequently asked questions', 'hero_image' => 'images/lg-P06-maison-nuit'],
    'fr/la-maison.json' => ['hero_heading' => 'La petite maison', 'hero_image' => 'images/lg-P04-facade-hortensias'],
    'en/the-house.json' => ['hero_heading' => 'The little house', 'hero_image' => 'images/lg-P04-facade-hortensias'],
];

$out = '';
foreach ($migrations as $file => $fields) {
    $fp = CONTENT_DIR . $file;
    if (!file_exists($fp)) { $out .= "SKIP $file\n"; continue; }
    $data = json_decode(file_get_contents($fp), true);
    if (!is_array($data)) { $out .= "SKIP $file bad json\n"; continue; }

    $changed = false;
    foreach ($fields as $key => $val) {
        if (empty($data[$key])) {
            $data[$key] = $val;
            $out .= "ADDED $key to $file\n";
            $changed = true;
        } else {
            $out .= "OK $file $key\n";
        }
    }

    if ($changed) {
        file_put_contents($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

echo "<pre>" . htmlspecialchars($out) . "Done!</pre>";
echo "<p><a href='dashboard.php'>Back to dashboard</a></p>";
