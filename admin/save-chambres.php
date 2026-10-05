<?php
require_once 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$hero_image = trim($_POST['hero_image'] ?? '');
$cta_image = trim($_POST['cta_image'] ?? '');

$fr_data = [
    'hero_heading' => trim($_POST['fr_hero_heading'] ?? ''),
    'hero_image' => $hero_image,
    'intro_text' => $_POST['fr_intro_text'] ?? '',
    'rooms' => [],
    'ideal_heading' => trim($_POST['fr_ideal_heading'] ?? ''),
    'ideal_text' => trim($_POST['fr_ideal_text'] ?? ''),
    'cta_image' => $cta_image,
];
$en_data = [
    'hero_heading' => trim($_POST['en_hero_heading'] ?? ''),
    'hero_image' => $hero_image,
    'intro_text' => $_POST['en_intro_text'] ?? '',
    'rooms' => [],
    'ideal_heading' => trim($_POST['en_ideal_heading'] ?? ''),
    'ideal_text' => trim($_POST['en_ideal_text'] ?? ''),
    'cta_image' => $cta_image,
];

if (!empty($_POST['fr_rooms'])) {
    foreach ($_POST['fr_rooms'] as $i => $room) {
        if (!empty($room['label']) || !empty($room['heading'])) {
            $en_room = $_POST['en_rooms'][$i] ?? [];
            $image = trim($room['image'] ?? '');
            $fr_data['rooms'][] = [
                'label' => trim($room['label'] ?? ''),
                'heading' => trim($room['heading'] ?? ''),
                'description' => trim($room['description'] ?? ''),
                'detail' => trim($room['detail'] ?? ''),
                'image' => $image,
            ];
            $en_data['rooms'][] = [
                'label' => trim($en_room['label'] ?? ''),
                'heading' => trim($en_room['heading'] ?? ''),
                'description' => trim($en_room['description'] ?? ''),
                'detail' => trim($en_room['detail'] ?? ''),
                'image' => $image,
            ];
        }
    }
}

save_json('fr/chambres.json', $fr_data);
save_json('en/bedrooms.json', $en_data);

header('Location: edit-chambres.php?saved=1');
exit;