<?php
session_start();
require_once 'config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($username && $password && $confirm) {
        if ($password !== $confirm) {
            $error = "⚠️ Die Passwörter stimmen nicht überein.";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM project_users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            if ($stmt->fetch()) {
                $error = "🚫 Benutzername existiert bereits.";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO project_users (username, password_hash) VALUES (:username, :password)");
                $stmt->execute([':username' => $username, ':password' => $hashed]);
                $success = "✅ Benutzer wurde erfolgreich registriert!";
            }
        }
    } else {
        $error = "❗ Bitte alle Felder ausfüllen.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>🧸 Registrieren</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Quicksand', sans-serif;
      background: url('assets/img/bg_login.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-box {
      background: rgba(255, 255, 255, 0.92);
      padding: 2rem;
      border-radius: 20px;
      max-width: 400px;
      width: 90%;
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #4c1d95;
    }

    input {
      width: 100%;
      padding: 12px;
      margin-bottom: 1rem;
      border-radius: 10px;
      border: 1px solid #ccc;
    }

    button {
      width: 100%;
      background-color: #9333ea;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color: #7e22ce;
    }

    .error-box, .success-box {
      padding: 10px;
      margin-bottom: 1rem;
      border-radius: 10px;
      text-align: center;
    }

    .error-box {
      background: #ffe6ea;
      color: #cc0033;
    }

    .success-box {
      background: #e6ffed;
      color: #198754;
    }

    .footer {
      text-align: center;
      margin-top: 1rem;
      font-size: 0.9em;
    }

    .footer a {
      color: #4c1d95;
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="login-box">
  <h2>🧸 Registrieren</h2>

  <?php if ($error): ?>
    <div class="error-box"><?= htmlspecialchars($error) ?></div>
  <?php elseif ($success): ?>
    <div class="success-box"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form method="post">
    <input type="text" name="username" placeholder="👤 Benutzername" required>
    <input type="password" name="password" placeholder="🔐 Passwort" required>
    <input type="password" name="confirm" placeholder="🔁 Passwort bestätigen" required>
    <button type="submit">🎀 Registrieren</button>
  </form>

  <div class="footer">
    <p>✨ Bereits registriert? <a href="login.php">Login</a></p>
    <p><a href="index.php">⬅️ Zur Startseite</a></p>
  </div>
</div>

</body>
</html>
