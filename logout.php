<?php
session_start();

// Session sicher beenden
session_unset();
session_destroy();

// Zurück zum Login mit Message
header("Location: login.php?logout=1");
exit;
