<?php
require_once 'config.php';
require_login();

$gd = gd_info();
echo '<pre>';
echo "GD Version: " . ($gd['GD Version'] ?? 'not installed') . "\n";
echo "WebP Support: " . ($gd['WebP Support'] ?? 'no') . "\n";
echo "JPEG Support: " . ($gd['JPEG Support'] ?? 'no') . "\n";
echo "PNG Support: " . ($gd['PNG Support'] ?? 'no') . "\n";
echo "\nMax upload: " . ini_get('upload_max_filesize') . "\n";
echo "Max POST: " . ini_get('post_max_size') . "\n";
echo '</pre>';
