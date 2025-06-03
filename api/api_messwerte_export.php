<?php
// Verbindung zur Datenbank herstellen
require_once '../config/db.php';

// Header setzen, damit der Browser den Export als Datei behandelt
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=live_export.csv');

// UTF-8 BOM einfügen für Excel-Kompatibilität (z. B. bei Umlauten)
echo "\xEF\xBB\xBF";

// CSV-Ausgabe auf Standardausgabe umleiten
$output = fopen('php://output', 'w');

// Kopfzeile schreiben
fputcsv($output, ['Timestamp', 'Temperatur', 'Feuchtigkeit', 'Luftdruck'], ';');

// Abfrage: Die letzten 30 Messungen
$stmt = $pdo->query("
    SELECT timestamp, temperature, humidity, additional_value 
    FROM project_measurements 
    ORDER BY timestamp DESC 
    LIMIT 30
");

// Ergebnis holen
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Zeile für Zeile ausgeben
foreach ($data as $row) {
    fputcsv($output, [
        $row['timestamp'],
        $row['temperature'],
        $row['humidity'],
        $row['additional_value']
    ], ';');
}

// Stream schließen
fclose($output);
exit;
