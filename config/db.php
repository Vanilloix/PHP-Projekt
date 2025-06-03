<?php
// Konfiguration für Datenbankverbindung
$host = 'localhost';            // Datenbank-Host
$db = 'messdatenerfassung';     // Name der Datenbank
$user = 'root';                 // Datenbankbenutzer
$pass = '';                     // Datenbankpasswort (leer für local)

try {
    // Verbindung herstellen mit PDO (sicher und objektorientiert)
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);

    // Fehler als Exception behandeln → bessere Fehlerdiagnose
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Bei Fehler: Script beenden und Fehlermeldung anzeigen
    die("Verbindung fehlgeschlagen: " . $e->getMessage());
}
?>
