<?php
session_start();

function ist_eingeloggt() {
    return isset($_SESSION['user_id']);
}

// Nur blockieren, wenn Login ZWINGEND erforderlich
$offen = ['index.php', 'diagramm.php'];

$current = basename($_SERVER['SCRIPT_NAME']);

if (!in_array($current, $offen) && !ist_eingeloggt()) {
    header('Location: login.php');
    exit;
}
