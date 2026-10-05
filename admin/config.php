<?php
session_start();

define('ADMIN_PASSWORD_HASH', password_hash('lagrim2026!', PASSWORD_DEFAULT));
define('CONTENT_DIR', __DIR__ . '/../content/');

function is_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function load_json($file) {
    $path = CONTENT_DIR . $file;
    if (!file_exists($path)) return [];
    $data = json_decode(file_get_contents($path), true);
    return $data ?: [];
}

function save_json($file, $data) {
    $path = CONTENT_DIR . $file;
    $dir = dirname($path);
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
