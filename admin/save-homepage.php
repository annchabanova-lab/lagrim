<?php
require_once 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$hero_image = trim($_POST['hero_image'] ?? '');
$intro_image = trim($_POST['intro_image'] ?? '');

$fr_data = [
    'hero' => [
        'label' => trim($_POST['fr_hero_label'] ?? ''),
        'heading' => trim($_POST['fr_hero_heading'] ?? ''),
        'subheading' => trim($_POST['fr_hero_subheading'] ?? ''),
        'tagline' => trim($_POST['fr_hero_tagline'] ?? ''),
        'image' => $hero_image,
        'cta_text' => trim($_POST['fr_hero_cta'] ?? ''),
    ],
    'intro' => [
        'text' => $_POST['fr_intro_text'] ?? '',
        'image' => $intro_image,
        'cta_text' => trim($_POST['fr_intro_cta'] ?? ''),
    ],
    'glance' => [],
    'why' => [],
    'cta' => [
        'heading' => trim($_POST['fr_cta_heading'] ?? ''),
        'text' => trim($_POST['fr_cta_text'] ?? ''),
    ],
];

$en_data = [
    'hero' => [
        'label' => trim($_POST['en_hero_label'] ?? ''),
        'heading' => trim($_POST['en_hero_heading'] ?? ''),
        'subheading' => trim($_POST['en_hero_subheading'] ?? ''),
        'tagline' => trim($_POST['en_hero_tagline'] ?? ''),
        'image' => $hero_image,
        'cta_text' => trim($_POST['en_hero_cta'] ?? ''),
    ],
    'intro' => [
        'text' => $_POST['en_intro_text'] ?? '',
        'image' => $intro_image,
        'cta_text' => trim($_POST['en_intro_cta'] ?? ''),
    ],
    'glance' => [],
    'why' => [],
    'cta' => [
        'heading' => trim($_POST['en_cta_heading'] ?? ''),
        'text' => trim($_POST['en_cta_text'] ?? ''),
    ],
];

if (!empty($_POST['fr_glance'])) {
    foreach ($_POST['fr_glance'] as $i => $card) {
        if (!empty($card['title'])) {
            $items = [];
            foreach (($card['items'] ?? []) as $item) {
                $item = trim($item);
                if ($item !== '') $items[] = $item;
            }
            $fr_data['glance'][] = ['title' => trim($card['title']), 'items' => $items];
        }
    }
}

if (!empty($_POST['en_glance'])) {
    foreach ($_POST['en_glance'] as $i => $card) {
        if (!empty($card['title'])) {
            $items = [];
            foreach (($card['items'] ?? []) as $item) {
                $item = trim($item);
                if ($item !== '') $items[] = $item;
            }
            $en_data['glance'][] = ['title' => trim($card['title']), 'items' => $items];
        }
    }
}

if (!empty($_POST['fr_why'])) {
    foreach ($_POST['fr_why'] as $item) {
        $item = trim($item);
        if ($item !== '') $fr_data['why'][] = $item;
    }
}

if (!empty($_POST['en_why'])) {
    foreach ($_POST['en_why'] as $item) {
        $item = trim($item);
        if ($item !== '') $en_data['why'][] = $item;
    }
}

save_json('fr/homepage.json', $fr_data);
save_json('en/homepage.json', $en_data);

header('Location: edit-homepage.php?saved=1');
exit;