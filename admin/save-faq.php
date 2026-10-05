<?php
require_once 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$cta_image = trim($_POST['cta_image'] ?? '');

$fr_data = ['items' => [], 'cta_image' => $cta_image];
$en_data = ['items' => [], 'cta_image' => $cta_image];

if (!empty($_POST['fr_items'])) {
    foreach ($_POST['fr_items'] as $i => $item) {
        $q = trim($item['question'] ?? '');
        $a = trim($item['answer'] ?? '');
        if ($q !== '' || $a !== '') {
            $fr_data['items'][] = ['question' => $q, 'answer' => $a];
        }
    }
}

if (!empty($_POST['en_items'])) {
    foreach ($_POST['en_items'] as $i => $item) {
        $q = trim($item['question'] ?? '');
        $a = trim($item['answer'] ?? '');
        if ($q !== '' || $a !== '') {
            $en_data['items'][] = ['question' => $q, 'answer' => $a];
        }
    }
}

save_json('fr/faq.json', $fr_data);
save_json('en/faq.json', $en_data);

header('Location: edit-faq.php?saved=1');
exit;