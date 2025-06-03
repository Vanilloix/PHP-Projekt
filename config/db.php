<?php
// Konfiguration für Datenbankverbindung
$host = 'localhost';            // Datenbank-Host
$db = 'messdatenerfassung';     // Name der Datenbank
$user = 'root';                 // Datenbankbenutzer
$pass = '';                     // Datenbankpasswort (leer für local)

try {
    // PDO-Verbindung zur MySQL-Datenbank (UTF-8 aktiviert)
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Fehlerbehandlung aktivieren

    // Fehler sollen als Ausnahmen (Exceptions) geworfen werden
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Fehlerausgabe falls keine Verbindung möglich ist
    die("Verbindung fehlgeschlagen: " . $e->getMessage());
}
?>
