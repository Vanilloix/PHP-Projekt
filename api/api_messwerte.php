<?php
// Verbindung zur Datenbank
require_once '../config/db.php';

// Filterparameter aus der URL
$type = $_GET['type'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

// Start der SQL-Abfrage
$sql = "SELECT timestamp, temperature, humidity, additional_value 
        FROM project_measurements 
        WHERE 1=1";
$params = [];

// Optional: Filter nach Sensortyp
if ($type !== '') {
    $sql .= " AND additional_type = :type";
    $params[':type'] = $type;
}

// Optional: Zeitraum "ab"
if ($from !== '') {
    $sql .= " AND timestamp >= :from";
    $params[':from'] = $from . " 00:00:00";
}

// Optional: Zeitraum "bis"
if ($to !== '') {
    $sql .= " AND timestamp <= :to";
    $params[':to'] = $to . " 23:59:59";
}

// Sortierung + Limit
$sql .= " ORDER BY timestamp DESC LIMIT 30";

// Abfrage ausführen
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Uhrzeitformat anpassen (z. B. 13:45:22)
foreach ($data as &$row) {
    $row['timestamp'] = date('H:i:s', strtotime($row['timestamp']));
}

// JSON zurückgeben (älteste zuerst)
header('Content-Type: application/json');
echo json_encode(array_reverse($data)); // ältere zuerst
