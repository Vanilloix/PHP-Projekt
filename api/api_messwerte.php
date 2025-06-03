<?php
require_once '../config/db.php';

// URL-Parameter lesen (optional)
$type = $_GET['type'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

// Grundgerüst der Abfrage
$sql = "SELECT timestamp, temperature, humidity, additional_value 
        FROM project_measurements 
        WHERE 1=1";
$params = [];

// Typfilter (z. B. Luftdruck)
if ($type !== '') {
    $sql .= " AND additional_type = :type";
    $params[':type'] = $type;
}

// Zeitfilter "von"
if ($from !== '') {
    $sql .= " AND timestamp >= :from";
    $params[':from'] = $from . " 00:00:00";
}

// Zeitfilter "bis"
if ($to !== '') {
    $sql .= " AND timestamp <= :to";
    $params[':to'] = $to . " 23:59:59";
}

// Reihenfolge und Limit
$sql .= " ORDER BY timestamp DESC LIMIT 30";

// Ausführen und Daten holen
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Zeitformat anpassen für Diagramme (nur Uhrzeit)
foreach ($data as &$row) {
    $row['timestamp'] = date('H:i:s', strtotime($row['timestamp']));
}

// JSON ausgeben
header('Content-Type: application/json');
echo json_encode(array_reverse($data)); // ältere zuerst
