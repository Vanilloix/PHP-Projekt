<?php
require_once '../config/db.php';

$stmt = $pdo->query("SELECT MAX(timestamp) as last FROM project_measurements");
$row = $stmt->fetch();
$timestamp = $row['last'] ?? null;

echo $timestamp ? date("d.m.Y H:i:s", strtotime($timestamp)) : "Keine Daten";
