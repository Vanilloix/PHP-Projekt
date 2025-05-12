<?php
session_start();
require_once 'config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM project_users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = '❌ Benutzername oder Passwort ist falsch.';
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: Arial; background: #f7faff; padding: 40px; }
        form { max-width: 300px; margin: auto; background: #fff; padding: 20px; box-shadow: 0 0 10px #ccc; border-radius: 5px; }
        h2 { text-align: center; }
        input { width: 100%; padding: 8px; margin: 8px 0; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>

<form method="POST" action="login.php">
    <h2>🔐 Login</h2>
    <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <input type="text" name="username" placeholder="Benutzername" required>
    <input type="password" name="password" placeholder="Passwort" required>
    <button type="submit">Anmelden</button>
</form>

</body>
</html>
