<?php
require_once 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$cta_image = trim($_POST['cta_image'] ?? '');

$fr_data = [
    'intro_text' => $_POST['fr_intro_text'] ?? '',
    'see_do' => [],
    'ideas' => [],
    'access_address' => trim($_POST['fr_access_address'] ?? ''),
    'access_directions' => trim($_POST['fr_access_directions'] ?? ''),
    'cta_image' => $cta_image,
];
$en_data = [
    'intro_text' => $_POST['en_intro_text'] ?? '',
    'see_do' => [],
    'ideas' => [],
    'access_address' => trim($_POST['en_access_address'] ?? ''),
    'access_directions' => trim($_POST['en_access_directions'] ?? ''),
    'cta_image' => $cta_image,
];

if (!empty($_POST['fr_see_do'])) {
    foreach ($_POST['fr_see_do'] as $item) {
        $item = trim($item);
        if ($item !== '') $fr_data['see_do'][] = $item;
    }
}
if (!empty($_POST['en_see_do'])) {
    foreach ($_POST['en_see_do'] as $item) {
        $item = trim($item);
        if ($item !== '') $en_data['see_do'][] = $item;
    }
}

if (!empty($_POST['fr_ideas'])) {
    foreach ($_POST['fr_ideas'] as $item) {
        $item = trim($item);
        if ($item !== '') $fr_data['ideas'][] = $item;
    }
}
if (!empty($_POST['en_ideas'])) {
    foreach ($_POST['en_ideas'] as $item) {
        $item = trim($item);
        if ($item !== '') $en_data['ideas'][] = $item;
    }
}

save_json('fr/autour.json', $fr_data);
save_json('en/nearby.json', $en_data);

header('Location: edit-autour.php?saved=1');
exit;