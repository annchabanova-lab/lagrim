<?php
require_once 'config.php';
require_login();
echo "Script loaded OK<br>";

$dir = CONTENT_DIR;
echo "Content dir: " . $dir . "<br>";
echo "Dir exists: " . (is_dir($dir) ? "yes" : "no") . "<br>";

$files = glob($dir . '*/*.json');
echo "JSON files found: " . count($files) . "<br>";

foreach ($files as $f) {
    echo basename(dirname($f)) . "/" . basename($f) . "<br>";
}
