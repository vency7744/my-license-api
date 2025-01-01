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
$newExpiryDate = $input['expiryDate'] ?? null;

if (!$licenseKey || !$newExpiryDate) {
    echo json_encode(["message" => "License key and new expiry date are required."]);
    exit;
}

foreach ($data as &$item) {
    if ($item['licenseKey'] === $licenseKey) {
        $item['expiryDate'] = $newExpiryDate;
        break;
    }
}

file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
echo json_encode(["message" => "License updated successfully."]);
