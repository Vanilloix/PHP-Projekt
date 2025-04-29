<?php
// Stellt die Verbindung zur MySQL-Datenbank her

$host = 'localhost'; // Datenbankserver
$db = 'messdatenerfassung'; //Name der Datenbank
$user = 'root'; // Benutzername
$pass = ''; // Passwort

try {
    //Verbindet sich mit der Datenbank über PDO (PHP Data Objects)
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Fehler als Ausnahmen behandeln
} catch (PDOException $e) {
    // Falls etwas schiefläuft, abbrechen und Fehlermeldung anzeigen
    die("Verbindung fehlgeschlagen: " . $e->getMessage());
}
?>