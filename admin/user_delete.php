<?php
require_once '../session.php';
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header('Location: user_list.php');
    exit;
}

$id = (int) $_GET['id'];

// Admin (ID 1) darf nicht gelöscht werden
if ($id === 1) {
    header('Location: user_list.php');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM project_users WHERE id = ?");
$stmt->execute([$id]);

header('Location: user_list.php');
exit;
