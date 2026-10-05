<?php
require_once 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$fr_data = ['intro_text' => $_POST['fr_intro_text'] ?? '', 'rates' => [], 'info' => []];
$en_data = ['intro_text' => $_POST['en_intro_text'] ?? '', 'rates' => [], 'info' => []];

if (!empty($_POST['fr_rates'])) {
    foreach ($_POST['fr_rates'] as $rate) {
        if (!empty($rate['name']) || !empty($rate['price'])) {
            $fr_data['rates'][] = [
                'name' => trim($rate['name'] ?? ''),
                'price' => trim($rate['price'] ?? ''),
                'detail' => trim($rate['detail'] ?? ''),
            ];
        }
    }
}

if (!empty($_POST['en_rates'])) {
    foreach ($_POST['en_rates'] as $rate) {
        if (!empty($rate['name']) || !empty($rate['price'])) {
            $en_data['rates'][] = [
                'name' => trim($rate['name'] ?? ''),
                'price' => trim($rate['price'] ?? ''),
                'detail' => trim($rate['detail'] ?? ''),
            ];
        }
    }
}

if (!empty($_POST['fr_info'])) {
    foreach ($_POST['fr_info'] as $item) {
        $item = trim($item);
        if ($item !== '') $fr_data['info'][] = $item;
    }
}

if (!empty($_POST['en_info'])) {
    foreach ($_POST['en_info'] as $item) {
        $item = trim($item);
        if ($item !== '') $en_data['info'][] = $item;
    }
}

save_json('fr/tarifs.json', $fr_data);
save_json('en/rates.json', $en_data);

header('Location: edit-tarifs.php?saved=1');
exit;
