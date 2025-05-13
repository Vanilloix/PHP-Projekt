<?php
require_once 'session.php';
require_once 'config/db.php';

// Header für CSV-Download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="messdaten_export.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// 👉 UTF-8 BOM AUSGEBEN – VOR fopen()
echo "\xEF\xBB\xBF";  // ⬅️ Hier: wichtig für Excel & Umlaute

// CSV öffnen
$output = fopen('php://output', 'w');

// Kopfzeile schreiben
fputcsv($output, ['ID', 'Timestamp', 'Temperatur (°C)', 'Luftfeuchtigkeit (%)', 'Zusatztyp', 'Zusatzwert', 'Beschreibung'], ';');

// Daten abrufen
$stmt = $pdo->query("SELECT * FROM project_measurements ORDER BY timestamp DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Werte als Text formatieren
    $row['temperature'] = "'" . $row['temperature'];
    $row['humidity'] = "'" . $row['humidity'];
    $row['additional_value'] = "'" . $row['additional_value'];

    fputcsv($output, $row, ';');
}


fclose($output);
exit;
