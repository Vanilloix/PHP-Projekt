<?php
session_start();
echo '<pre>Session: ';
print_r($_SESSION);
echo '</pre>';

if (!isset($_SESSION['user_id'])) {
    echo '🔒 Zugriff verweigert, redirect...';
    header('Location: login.php');
    exit;
}
