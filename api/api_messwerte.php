<?php
require_once '../config/db.php';

// Filter-Parameter aus URL
$type = $_GET['type'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$sql = "SELECT timestamp, temperature, humidity, additional_value FROM project_measurements WHERE 1=1";
$params = [];

if ($type !== '') {
    $sql .= " AND additional_type = :type";
    $params[':type'] = $type;
}

if ($from !== '') {
    $sql .= " AND timestamp >= :from";
    $params[':from'] = $from . " 00:00:00";
}

if ($to !== '') {
    $sql .= " AND timestamp <= :to";
    $params[':to'] = $to . " 23:59:59";
}

$sql .= " ORDER BY timestamp DESC LIMIT 30";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Zeitformat anpassen
foreach ($data as &$row) {
    $row['timestamp'] = date('H:i:s', strtotime($row['timestamp']));
}

header('Content-Type: application/json');
echo json_encode(array_reverse($data));
