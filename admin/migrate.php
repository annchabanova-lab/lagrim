<?php
require_once 'config.php';
require_login();

$files = [
    'fr/footer.json' => [
        'title' => 'LA GRIMOUILLIÈRE',
        'description' => 'Location saisonnière — la petite maison',
        'address' => "3 route des Autels Saint-Bazile\n61200 Crouttes\nPays d'Auge, Normandie",
        'capacity' => "Jusqu'à 6 voyageurs · 2h15 de Paris-Porte Maillot",
        'instagram' => '@lagrimflow',
        'instagram_url' => 'https://www.instagram.com/lagrimflow',
        'info_title' => 'INFORMATIONS',
        'checkin' => 'Check-in : 16h',
        'checkout' => 'Check-out : 11h',
        'cancellation_text' => "Conditions d'annulation",
        'copyright' => '© 2026 SCI Five Stars — La Grimouillière',
        'legal_text' => 'Mentions légales',
    ],
    'en/footer.json' => [
        'title' => 'LA GRIMOUILLIÈRE',
        'description' => 'Seasonal rental — the little house',
        'address' => "3 route des Autels Saint-Bazile\n61200 Crouttes\nPays d'Auge, Normandy",
        'capacity' => 'Up to 6 guests · 2h15 from Paris-Porte Maillot',
        'instagram' => '@lagrimflow',
        'instagram_url' => 'https://www.instagram.com/lagrimflow',
        'info_title' => 'INFORMATION',
        'checkin' => 'Check-in: 4 pm',
        'checkout' => 'Check-out: 11 am',
        'cancellation_text' => 'Cancellation policy',
        'copyright' => '© 2026 SCI Five Stars — La Grimouillière',
        'legal_text' => 'Legal notice',
    ],
];

$out = '';
foreach ($files as $file => $defaults) {
    $fp = CONTENT_DIR . $file;
    $exists = file_exists($fp);
    $data = $exists ? (json_decode(file_get_contents($fp), true) ?: []) : [];

    $changed = false;
    foreach ($defaults as $key => $val) {
        if (empty($data[$key])) {
            $data[$key] = $val;
            $changed = true;
        }
    }

    if ($changed || !$exists) {
        $dir = dirname($fp);
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        file_put_contents($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $out .= ($exists ? "UPDATED" : "CREATED") . " $file\n";
    } else {
        $out .= "OK $file\n";
    }
}

echo "<pre>" . htmlspecialchars($out) . "Done!</pre>";
echo "<p><a href='edit-footer.php'>Edit footer</a> | <a href='dashboard.php'>Dashboard</a></p>";
