<?php
require_once 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }

require_csrf();

$instagram = trim($_POST['instagram'] ?? '@lagrimflow');
$instagram_url = trim($_POST['instagram_url'] ?? 'https://www.instagram.com/lagrimflow');

$fr = [
    'title' => 'LA GRIMOUILLIÈRE',
    'description' => trim($_POST['fr_description'] ?? ''),
    'address' => trim($_POST['fr_address'] ?? ''),
    'capacity' => trim($_POST['fr_capacity'] ?? ''),
    'instagram' => $instagram,
    'instagram_url' => $instagram_url,
    'info_title' => 'INFORMATIONS',
    'checkin' => trim($_POST['fr_checkin'] ?? ''),
    'checkout' => trim($_POST['fr_checkout'] ?? ''),
    'cancellation_text' => trim($_POST['fr_cancellation_text'] ?? ''),
    'copyright' => trim($_POST['fr_copyright'] ?? ''),
    'legal_text' => trim($_POST['fr_legal_text'] ?? ''),
];

$en = [
    'title' => 'LA GRIMOUILLIÈRE',
    'description' => trim($_POST['en_description'] ?? ''),
    'address' => trim($_POST['en_address'] ?? ''),
    'capacity' => trim($_POST['en_capacity'] ?? ''),
    'instagram' => $instagram,
    'instagram_url' => $instagram_url,
    'info_title' => 'INFORMATION',
    'checkin' => trim($_POST['en_checkin'] ?? ''),
    'checkout' => trim($_POST['en_checkout'] ?? ''),
    'cancellation_text' => trim($_POST['en_cancellation_text'] ?? ''),
    'copyright' => trim($_POST['en_copyright'] ?? ''),
    'legal_text' => trim($_POST['en_legal_text'] ?? ''),
];

save_json('fr/footer.json', $fr);
save_json('en/footer.json', $en);

header('Location: edit-footer.php?saved=1');
exit;
