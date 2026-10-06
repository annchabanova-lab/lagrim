<?php
require_once 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

require_csrf();

$hero_image = trim($_POST['hero_image'] ?? '');
$intro_image = trim($_POST['intro_image'] ?? '');
$cta_image = trim($_POST['cta_image'] ?? '');

$fr_data = [
    'hero_label' => trim($_POST['fr_hero_label'] ?? ''),
    'hero_heading' => trim($_POST['fr_hero_heading'] ?? ''),
    'hero_image' => $hero_image,
    'intro_heading' => trim($_POST['fr_intro_heading'] ?? ''),
    'intro_text' => sanitize_html($_POST['fr_intro_text'] ?? ''),
    'intro_image' => $intro_image,
    'summary' => [],
    'comfort_heading' => trim($_POST['fr_comfort_heading'] ?? ''),
    'comfort_text' => sanitize_html($_POST['fr_comfort_text'] ?? ''),
    'cta_image' => $cta_image,
];

$en_data = [
    'hero_label' => trim($_POST['en_hero_label'] ?? ''),
    'hero_heading' => trim($_POST['en_hero_heading'] ?? ''),
    'hero_image' => $hero_image,
    'intro_heading' => trim($_POST['en_intro_heading'] ?? ''),
    'intro_text' => sanitize_html($_POST['en_intro_text'] ?? ''),
    'intro_image' => $intro_image,
    'summary' => [],
    'comfort_heading' => trim($_POST['en_comfort_heading'] ?? ''),
    'comfort_text' => sanitize_html($_POST['en_comfort_text'] ?? ''),
    'cta_image' => $cta_image,
];

if (!empty($_POST['fr_summary'])) {
    foreach ($_POST['fr_summary'] as $i => $card) {
        $items = [];
        if (!empty($card['items'])) {
            foreach ($card['items'] as $item) {
                $item = trim($item);
                if ($item !== '') $items[] = $item;
            }
        }
        if (!empty($card['title']) || !empty($items)) {
            $fr_data['summary'][] = [
                'title' => trim($card['title'] ?? ''),
                'items' => $items,
            ];
        }
    }
}

if (!empty($_POST['en_summary'])) {
    foreach ($_POST['en_summary'] as $i => $card) {
        $items = [];
        if (!empty($card['items'])) {
            foreach ($card['items'] as $item) {
                $item = trim($item);
                if ($item !== '') $items[] = $item;
            }
        }
        if (!empty($card['title']) || !empty($items)) {
            $en_data['summary'][] = [
                'title' => trim($card['title'] ?? ''),
                'items' => $items,
            ];
        }
    }
}

save_json('fr/la-maison.json', $fr_data);
save_json('en/the-house.json', $en_data);

header('Location: edit-maison.php?saved=1');
exit;