<?php
require_once 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$fr_data = [
    'hero_label' => trim($_POST['fr_hero_label'] ?? ''),
    'hero_heading' => trim($_POST['fr_hero_heading'] ?? ''),
    'sections' => [],
];

$en_data = [
    'hero_label' => trim($_POST['en_hero_label'] ?? ''),
    'hero_heading' => trim($_POST['en_hero_heading'] ?? ''),
    'sections' => [],
];

if (!empty($_POST['fr_sections'])) {
    foreach ($_POST['fr_sections'] as $i => $sec) {
        if (!empty($sec['heading']) || !empty($sec['text'])) {
            $fr_data['sections'][] = [
                'heading' => trim($sec['heading'] ?? ''),
                'text' => $sec['text'] ?? '',
            ];
        }
    }
}

if (!empty($_POST['en_sections'])) {
    foreach ($_POST['en_sections'] as $i => $sec) {
        if (!empty($sec['heading']) || !empty($sec['text'])) {
            $en_data['sections'][] = [
                'heading' => trim($sec['heading'] ?? ''),
                'text' => $sec['text'] ?? '',
            ];
        }
    }
}

save_json('fr/mentions-legales.json', $fr_data);
save_json('en/legal.json', $en_data);

header('Location: edit-legal.php?saved=1');
exit;