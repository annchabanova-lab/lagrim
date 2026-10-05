<?php
require_once 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$lang = ($_POST['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';
$file = $lang === 'fr' ? 'fr/tarifs.json' : 'en/rates.json';

$data = [
    'intro_text' => $_POST['intro_text'] ?? '',
    'rates' => [],
    'info' => [],
];

if (!empty($_POST['rates'])) {
    foreach ($_POST['rates'] as $rate) {
        if (!empty($rate['name']) || !empty($rate['price'])) {
            $data['rates'][] = [
                'name' => trim($rate['name'] ?? ''),
                'price' => trim($rate['price'] ?? ''),
                'detail' => trim($rate['detail'] ?? ''),
            ];
        }
    }
}

if (!empty($_POST['info'])) {
    foreach ($_POST['info'] as $item) {
        $item = trim($item);
        if ($item !== '') {
            $data['info'][] = $item;
        }
    }
}

save_json($file, $data);

header("Location: edit-tarifs.php?lang={$lang}&saved=1");
exit;
