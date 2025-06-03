<?php
require_once '../config/db.php';

// CSV-Header für Browser-Download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=live_export.csv');

// BOM für UTF-8 Kompatibilität in Excel
echo "\xEF\xBB\xBF";

// CSV-Ausgabe starten
$output = fopen('php://output', 'w');

// Überschriftenzeile schreiben
fputcsv($output, ['Timestamp', 'Temperatur', 'Feuchtigkeit', 'Luftdruck'], ';');

// Messwerte aus DB abrufen (letzte 30 Einträge)
$stmt = $pdo->query("
    SELECT timestamp, temperature, humidity, additional_value 
    FROM project_measurements 
    ORDER BY timestamp DESC 
    LIMIT 30
");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Jede Zeile als CSV schreiben
foreach ($data as $row) {
    fputcsv($output, [
        $row['timestamp'],
        $row['temperature'],
        $row['humidity'],
        $row['additional_value']
    ], ';');
}

fclose($output);
exit;
