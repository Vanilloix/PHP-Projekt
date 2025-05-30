<?php
require_once 'session.php';
require_once 'config/db.php';

if (!ist_eingeloggt()) {
    header("Location: login.php");
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="messdaten_export.csv"');
echo "\xEF\xBB\xBF"; // UTF-8 BOM für Excel

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Timestamp', 'Temperatur (°C)', 'Luftfeuchtigkeit (%)', 'Zusatztyp', 'Zusatzwert', 'Beschreibung'], ';');

$stmt = $pdo->query("SELECT * FROM project_measurements ORDER BY timestamp DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['id'],
        $row['timestamp'],
        $row['temperature'],
        $row['humidity'],
        $row['additional_type'],
        $row['additional_value'],
        $row['description']
    ], ';');
}

fclose($output);
exit;
