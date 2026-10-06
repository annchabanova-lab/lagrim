<?php
if (($_GET['key'] ?? '') !== 'install-2026') {
    http_response_code(403);
    exit('Forbidden');
}

$password = 'LaGrimLittleHouse2026!';
$hash = password_hash($password, PASSWORD_DEFAULT);
$secretPath = dirname(__DIR__, 2) . '/admin-secret.php';

$content = "<?php\nreturn ['password_hash' => " . var_export($hash, true) . "];\n";

if (file_put_contents($secretPath, $content) !== false) {
    echo '<h2 style="color:green;">Password configured successfully!</h2>';
    echo '<p>Hash stored at: ' . htmlspecialchars($secretPath) . '</p>';
    echo '<p><strong>Delete this file (setup-password.php) from the server now.</strong></p>';
    echo '<p><a href="index.php">Go to admin login</a></p>';
} else {
    echo '<h2 style="color:red;">Failed to write secret file</h2>';
    echo '<p>Tried: ' . htmlspecialchars($secretPath) . '</p>';
    echo '<p>Check directory permissions.</p>';
}
