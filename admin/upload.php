<?php
require_once 'config.php';
require_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['image'])) {
    echo json_encode(['error' => 'No image uploaded']);
    exit;
}

require_csrf(true);

$file = $_FILES['image'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Upload error: ' . $file['error']]);
    exit;
}

if ($file['size'] > 20 * 1024 * 1024) {
    echo json_encode(['error' => 'File too large (max 20MB)']);
    exit;
}

$mime = mime_content_type($file['tmp_name']);
if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'])) {
    echo json_encode(['error' => 'Unsupported format. Use JPG, PNG, WebP or GIF.']);
    exit;
}

// Always generate a unique filename to avoid browser cache issues
$fieldName = trim($_POST['field'] ?? 'photo');
$fieldName = preg_replace('/[^a-z0-9-]/', '-', strtolower($fieldName));
$baseName = 'lg-' . $fieldName . '-' . date('Ymd-His') . '-' . bin2hex(random_bytes(2));

$imagesDir = __DIR__ . '/../images/';

$src = null;
switch ($mime) {
    case 'image/jpeg': $src = imagecreatefromjpeg($file['tmp_name']); break;
    case 'image/png':  $src = imagecreatefrompng($file['tmp_name']); break;
    case 'image/webp': $src = imagecreatefromwebp($file['tmp_name']); break;
    case 'image/gif':  $src = imagecreatefromgif($file['tmp_name']); break;
}

if (!$src) {
    echo json_encode(['error' => 'Could not read image']);
    exit;
}

$origW = imagesx($src);
$origH = imagesy($src);

if ($origW * $origH > 25000000) {
    imagedestroy($src);
    echo json_encode(['error' => 'Image too large (max 25 megapixels)']);
    exit;
}

$sizes = ['sm' => 400, 'md' => 800, 'lg' => 1600];
$paths = [];

foreach ($sizes as $label => $targetW) {
    if ($origW <= $targetW) {
        $resized = $src;
    } else {
        $targetH = intval($origH * ($targetW / $origW));
        $resized = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $src, 0, 0, 0, 0, $targetW, $targetH, $origW, $origH);
    }

    $filename = $baseName . '-' . $label . '.webp';
    $ok = imagewebp($resized, $imagesDir . $filename, 82);
    if (!$ok || !file_exists($imagesDir . $filename)) {
        imagedestroy($src);
        foreach ($paths as $p) @unlink(__DIR__ . '/../' . $p);
        echo json_encode(['error' => 'Failed to create ' . $label . ' variant']);
        exit;
    }
    $paths[$label] = 'images/' . $filename;

    if ($resized !== $src) imagedestroy($resized);
}

// JPG fallback
$fallbackName = $baseName . '.jpg';
if ($origW > 1600) {
    $targetH = intval($origH * (1600 / $origW));
    $fallback = imagecreatetruecolor(1600, $targetH);
    imagecopyresampled($fallback, $src, 0, 0, 0, 0, 1600, $targetH, $origW, $origH);
    imagejpeg($fallback, $imagesDir . $fallbackName, 85);
    imagedestroy($fallback);
} else {
    imagejpeg($src, $imagesDir . $fallbackName, 85);
}

imagedestroy($src);

echo json_encode([
    'success' => true,
    'base_path' => 'images/' . $baseName,
    'paths' => $paths,
    'preview' => $paths['md'],
]);
