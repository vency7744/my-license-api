<?php
header('Content-Type: application/json');

$filePath = __DIR__ . '/licenses.json';
if (!file_exists($filePath)) {
    echo json_encode(["message" => "No licenses found."]);
    exit;
}

$data = json_decode(file_get_contents($filePath), true);
$input = json_decode(file_get_contents('php://input'), true);
$licenseKey = $input['licenseKey'] ?? null;

if (!$licenseKey) {
    echo json_encode(["message" => "License key is required."]);
    exit;
}

$data = array_filter($data, fn($item) => $item['licenseKey'] !== $licenseKey);
file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));

echo json_encode(["message" => "License deleted successfully."]);
