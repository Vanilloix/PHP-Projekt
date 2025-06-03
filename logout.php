<?php
session_start();

// Alle Session-Variablen entfernen & Session zerstören
session_unset();
session_destroy();

// Zurück zum Login mit optionaler Logout-Meldung
header("Location: login.php?logout=1");
exit;
