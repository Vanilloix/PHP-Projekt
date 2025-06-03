<?php
require_once '../session.php';
require_once '../config/db.php';

// Prüfen ob eine ID übergeben wurde
if (!isset($_GET['id'])) {
    header('Location: user_list.php');
    exit;
}

$id = (int) $_GET['id'];

// Schutzmechanismus: Admin-Benutzer (ID 1) darf nicht gelöscht werden
if ($id === 1) {
    header('Location: user_list.php');
    exit;
}

// Benutzer aus der Datenbank löschen
$stmt = $pdo->prepare("DELETE FROM project_users WHERE id = ?");
$stmt->execute([$id]);

// Zurück zur Benutzerliste
header('Location: user_list.php');
exit;
