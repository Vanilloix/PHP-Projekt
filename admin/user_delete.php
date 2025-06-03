<?php
// Session-Schutz und DB-Verbindung einbinden
require_once '../session.php';
require_once '../config/db.php';

// Wenn keine ID übergeben wurde → zurück zur Übersicht
if (!isset($_GET['id'])) {
    header('Location: user_list.php');
    exit;
}

$id = (int) $_GET['id']; // Übergabe absichern (nur Ganzzahlen)

// Schutzmechanismus: Admin (ID 1) darf nicht gelöscht werden
if ($id === 1) {
    header('Location: user_list.php');
    exit;
}

// Benutzer mit passender ID löschen
$stmt = $pdo->prepare("DELETE FROM project_users WHERE id = ?");
$stmt->execute([$id]);

// Nach dem Löschen: Zurück zur Benutzerliste
header('Location: user_list.php');
exit;
?>
