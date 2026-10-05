<?php
require_once 'config.php';
require_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['image'])) {
    echo json_encode(['error' => 'No image uploaded']);
    exit;
}

$file = $_FILES['image'];
$maxSize = 20 * 1024 * 1024;

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Upload error: ' . $file['error']]);
    exit;
}

if ($file['size'] > $maxSize) {
    echo json_encode(['error' => 'File too large (max 20MB)']);
    exit;
}

$mime = mime_content_type($file['tmp_name']);
$allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
if (!in_array($mime, $allowed)) {
    echo json_encode(['error' => 'Unsupported format. Use JPG, PNG, WebP or GIF.']);
    exit;
}

$slug = $_POST['slug'] ?? '';
$slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($slug));
if (empty($slug)) {
    $slug = pathinfo($file['name'], PATHINFO_FILENAME);
    $slug = preg_replace('/[^a-z0-9\-]/', '-', strtolower($slug));
    $slug = preg_replace('/-+/', '-', trim($slug, '-'));
}

$baseName = 'lg-' . $slug;
$imagesDir = __DIR__ . '/../images/';

$src = null;
switch ($mime) {
    case 'image/jpeg':
        $src = imagecreatefromjpeg($file['tmp_name']);
        break;
    case 'image/png':
        $src = imagecreatefrompng($file['tmp_name']);
        break;
    case 'image/webp':
        $src = imagecreatefromwebp($file['tmp_name']);
        break;
    case 'image/gif':
        $src = imagecreatefromgif($file['tmp_name']);
        break;
}

if (!$src) {
    echo json_encode(['error' => 'Could not read image']);
    exit;
}

$origW = imagesx($src);
$origH = imagesy($src);

$sizes = [
    'sm' => 400,
    'md' => 800,
    'lg' => 1600,
];

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
    $filepath = $imagesDir . $filename;
    imagewebp($resized, $filepath, 82);

    $paths[$label] = 'images/' . $filename;

    if ($resized !== $src) {
        imagedestroy($resized);
    }
}

// also save original as .jpg fallback (lg size)
$fallbackName = $baseName . '.jpg';
$fallbackPath = $imagesDir . $fallbackName;
if ($origW > 1600) {
    $targetH = intval($origH * (1600 / $origW));
    $fallback = imagecreatetruecolor(1600, $targetH);
    imagecopyresampled($fallback, $src, 0, 0, 0, 0, 1600, $targetH, $origW, $origH);
    imagejpeg($fallback, $fallbackPath, 85);
    imagedestroy($fallback);
} else {
    imagejpeg($src, $fallbackPath, 85);
}

imagedestroy($src);

echo json_encode([
    'success' => true,
    'slug' => $slug,
    'base' => $baseName,
    'base_path' => 'images/' . $baseName,
    'paths' => $paths,
    'fallback' => 'images/' . $fallbackName,
    'preview' => $paths['md'],
]);
