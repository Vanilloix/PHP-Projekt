<?php
require_once '../config/db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=live_export.csv');
echo "\xEF\xBB\xBF";

$output = fopen('php://output', 'w');
fputcsv($output, ['Timestamp', 'Temperatur', 'Feuchtigkeit', 'Luftdruck'], ';');

$stmt = $pdo->query("SELECT timestamp, temperature, humidity, additional_value FROM project_measurements ORDER BY timestamp DESC LIMIT 30");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
