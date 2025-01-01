<?php
header('Content-Type: application/json');

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Muat file .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$secret = $_ENV['SECRET_KEY'] ?? 'default-secret-key'; // Ambil secret dari environment variable

function generateLicense($username, $secret) {
    $timestamp = time();
    $rawData = $username . "-" . $timestamp;
    $licenseKey = strtoupper(substr(hash_hmac('sha256', $rawData, $secret), 0, 20));

    return [
        "username" => $username,
        "licenseKey" => $licenseKey,
        "createdAt" => date("Y-m-d H:i:s"),
        "expiryDate" => date("Y-m-d H:i:s", strtotime("+30 days"))
    ];
}

$filePath = __DIR__ . '/licenses.json';
$data = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];

$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? null;

if (!$username) {
    echo json_encode(["message" => "Username is required."]);
    exit;
}

$newLicense = generateLicense($username, $secret);
$data[] = $newLicense;

file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
echo json_encode($newLicense);
