<?php
require_once 'config/db.php';

$username = 'admin';
$password_plain = 'geheim123';

// ✅ PHP 8: sicherer Hash
$password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO project_users (username, password_hash) VALUES (:username, :password)");
$stmt->execute([
    ':username' => $username,
    ':password' => $password_hash
]);

echo "✅ Benutzer '$username' wurde erfolgreich erstellt!";
?>
