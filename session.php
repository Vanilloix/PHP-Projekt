<?php
session_start();

function ist_eingeloggt() {
    return isset($_SESSION['user_id']) || isset($_SESSION['is_guest']);
}

// Seiten, die auch ohne Login zugänglich sind
$offen = ['index.php', 'diagramm.php', 'messwerte.php'];

// Dateiname des aktuellen Skripts
$current = basename($_SERVER['SCRIPT_NAME']);

// Wenn nicht eingeloggt und die Seite ist geschützt
if (!in_array($current, $offen) && !ist_eingeloggt()) {
    // Prüfe, ob wir im /admin/ Verzeichnis sind
    $loginPfad = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) ? '../login.php' : 'login.php';
    
    header("Location: $loginPfad");
    exit;
}
