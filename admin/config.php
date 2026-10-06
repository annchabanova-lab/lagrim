<?php
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/admin/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict',
]);
if (!session_start()) {
    http_response_code(500);
    exit('Administration unavailable');
}

$secretFile = dirname(__DIR__, 2) . '/admin-secret.php';
if (!file_exists($secretFile)) {
    if (basename($_SERVER['SCRIPT_NAME']) !== 'setup-password.php') {
        http_response_code(503);
        exit('Administration unavailable — run setup first');
    }
    define('ADMIN_PASSWORD_HASH', '');
} else {
    $secret = require $secretFile;
    $hash = $secret['password_hash'] ?? '';
    $info = is_string($hash) ? password_get_info($hash) : [];
    if (!is_string($hash) || $hash === '' || empty($info['algo'])) {
        http_response_code(503);
        exit('Administration unavailable — invalid configuration');
    }
    define('ADMIN_PASSWORD_HASH', $hash);
}

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

function csrf_token(): string {
    $token = $_SESSION['csrf_token'] ?? null;
    if (!is_string($token) || strlen($token) !== 64) {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
    }
    return $token;
}

function require_csrf(bool $json = false): void {
    $expected = $_SESSION['csrf_token'] ?? null;
    $provided = $_POST['csrf_token'] ?? null;
    if (!is_string($expected) || strlen($expected) !== 64 ||
        !is_string($provided) || strlen($provided) !== 64 ||
        !hash_equals($expected, $provided)) {
        http_response_code(403);
        if ($json) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid CSRF token']);
        } else {
            echo 'Invalid CSRF token';
        }
        exit;
    }
}

function sanitize_html(string $html): string {
    $allowed = '<p><br><strong><em><ul><ol><li><h2><h3>';
    return strip_tags($html, $allowed);
}

function load_json($file) {
    $path = CONTENT_DIR . $file;
    if (!file_exists($path)) return [];
    $data = json_decode(file_get_contents($path), true);
    return is_array($data) ? $data : [];
}

function save_json($file, $data) {
    $path = CONTENT_DIR . $file;
    $dir = dirname($path);
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) return false;

    $tmp = $path . '.tmp.' . bin2hex(random_bytes(4));
    $written = file_put_contents($tmp, $json, LOCK_EX);
    if ($written === false || $written !== strlen($json)) {
        @unlink($tmp);
        return false;
    }
    if (!rename($tmp, $path)) {
        @unlink($tmp);
        return false;
    }
    return true;
}

function check_login_throttle(): bool {
    $file = sys_get_temp_dir() . '/lagrim_login_attempts.json';
    $data = [];
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true) ?: [];
    }
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $key = 'ip_' . md5($ip);
    $now = time();
    $attempts = $data[$key] ?? [];
    $attempts = array_values(array_filter($attempts, fn($t) => $now - $t < 900));
    return count($attempts) < 5;
}

function record_login_attempt(): void {
    $file = sys_get_temp_dir() . '/lagrim_login_attempts.json';
    $data = [];
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true) ?: [];
    }
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $key = 'ip_' . md5($ip);
    $now = time();
    $data[$key] = array_values(array_filter($data[$key] ?? [], fn($t) => $now - $t < 900));
    $data[$key][] = $now;
    file_put_contents($file, json_encode($data), LOCK_EX);
}
