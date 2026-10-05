<?php
require_once 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }

$instagram = trim($_POST['instagram'] ?? '@lagrimflow');

$fr = [
    'hero_heading' => 'Nous contacter',
    'intro_text' => $_POST['fr_intro_text'] ?? '',
    'form_hint' => trim($_POST['fr_form_hint'] ?? ''),
    'address' => trim($_POST['fr_address'] ?? ''),
    'distance' => trim($_POST['fr_distance'] ?? ''),
    'checkin' => trim($_POST['fr_checkin'] ?? ''),
    'checkout' => trim($_POST['fr_checkout'] ?? ''),
    'cancellation' => trim($_POST['fr_cancellation'] ?? ''),
    'pets' => trim($_POST['fr_pets'] ?? ''),
    'instagram' => $instagram,
];

$en = [
    'hero_heading' => 'Get in touch',
    'intro_text' => $_POST['en_intro_text'] ?? '',
    'form_hint' => trim($_POST['en_form_hint'] ?? ''),
    'address' => trim($_POST['en_address'] ?? ''),
    'distance' => trim($_POST['en_distance'] ?? ''),
    'checkin' => trim($_POST['en_checkin'] ?? ''),
    'checkout' => trim($_POST['en_checkout'] ?? ''),
    'cancellation' => trim($_POST['en_cancellation'] ?? ''),
    'pets' => trim($_POST['en_pets'] ?? ''),
    'instagram' => $instagram,
];

save_json('fr/contact.json', $fr);
save_json('en/contact.json', $en);

header('Location: edit-contact.php?saved=1');
exit;
