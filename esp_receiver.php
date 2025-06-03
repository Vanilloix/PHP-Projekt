<?php
require_once 'config/db.php';

// Nur POST erlaubt
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Nur POST erlaubt');
}

// Token prüfen (Sicherheit)
$token = $_POST['token'] ?? '';
$allowedToken = 'cb92f73afcdb4719a1df36d62b8c02cc';

if ($token !== $allowedToken) {
    http_response_code(403);
    exit('❌ Zugriff verweigert');
}

// Eingabewerte auslesen oder mit Fallback
$temperature      = $_POST['temperature'] ?? null;
$humidity         = $_POST['humidity'] ?? null;
$additional_type  = $_POST['additional_type'] ?? '';
$additional_value = $_POST['additional_value'] ?? null;
$description      = $_POST['description'] ?? 'ESP-Gerät';

$timestamp = date('Y-m-d H:i:s');

// SQL-Insert vorbereiten
$sql = "INSERT INTO project_measurements 
        (timestamp, temperature, humidity, additional_type, additional_value, description)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $timestamp,
    $temperature,
    $humidity,
    $additional_type,
    $additional_value,
    $description
]);

echo '✅ Messung gespeichert';
