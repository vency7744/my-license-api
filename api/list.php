<?php
header('Content-Type: application/json');

$filePath = __DIR__ . '/licenses.json';
if (!file_exists($filePath)) {
    echo json_encode([]);
    exit;
}

$data = json_decode(file_get_contents($filePath), true);
echo json_encode($data);
