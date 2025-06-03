<?php
require_once '../session.php';
require_once '../config/db.php';

// Wenn keine ID übergeben wurde → zurück zur Übersicht
if (!isset($_GET['id'])) {
    header('Location: user_list.php');
    exit;
}

$id = (int) $_GET['id'];

// Schutz: Admin-Account darf nicht gelöscht werden
if ($id === 1) {
    header('Location: user_list.php');
    exit;
}

// Benutzer anhand der ID löschen
$stmt = $pdo->prepare("DELETE FROM project_users WHERE id = ?");
$stmt->execute([$id]);

// Danach: Zur Benutzerübersicht
header('Location: user_list.php');
exit;
?>
