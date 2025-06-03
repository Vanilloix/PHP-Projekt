<?php
// Verbindung zur Datenbank
require_once '../config/db.php';

// Neuesten Zeitstempel abfragen
$stmt = $pdo->query("SELECT MAX(timestamp) as last FROM project_measurements");
$row = $stmt->fetch();
$timestamp = $row['last'] ?? null;

// Entweder formatiert zurückgeben oder Hinweis ausgeben
echo $timestamp 
    ? date("d.m.Y H:i:s", strtotime($timestamp)) 
    : "Keine Daten";
