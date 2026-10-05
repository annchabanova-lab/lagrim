<?php
require_once 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$data = ['periods' => []];

if (!empty($_POST['periods'])) {
    foreach ($_POST['periods'] as $period) {
        if (!empty($period['name'])) {
            $data['periods'][] = [
                'name' => trim($period['name'] ?? ''),
                'start' => trim($period['start'] ?? ''),
                'end' => trim($period['end'] ?? ''),
                'status' => in_array($period['status'] ?? '', ['available', 'booked', 'on_request']) ? $period['status'] : 'available',
                'rate' => trim($period['rate'] ?? ''),
            ];
        }
    }
}

save_json('availability.json', $data);

header('Location: edit-availability.php?saved=1');
exit;
