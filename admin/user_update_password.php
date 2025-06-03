<?php
// Session starten und Datenbankverbindung laden
require_once '../session.php';
require_once '../config/db.php';

// Wenn keine ID übergeben wurde → Zurück zur Benutzerliste
if (!isset($_GET['id'])) {
    header("Location: user_list.php");
    exit;
}

$id = (int) $_GET['id']; // ID casten auf Integer
$meldung = ''; // Platz für Rückmeldungen

// Prüfen ob Formular abgeschickt wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $neues_passwort = $_POST['password'];

    if (!empty($neues_passwort)) {
        // Neues Passwort hashen und in DB speichern
        $hash = password_hash($neues_passwort, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE project_users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$hash, $id]);

        // Danach zurück zur Benutzerliste
        header("Location: user_list.php");
        exit;
    } else {
        $meldung = "Bitte ein neues Passwort eingeben.";
    }
}

// Benutzername für Anzeige holen
$stmt = $pdo->prepare("SELECT username FROM project_users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>🔑 Passwort ändern</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background: #f0f4ff;
      margin: 0;
      padding: 2rem;
    }

    .container {
      max-width: 400px;
      margin: auto;
      background: white;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }

    h2 {
      text-align: center;
      color: #4c1d95;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    button {
      margin-top: 20px;
      width: 100%;
      background: #d8b4fe;
      color: #4c1d95;
      border: none;
      padding: 12px;
      font-weight: bold;
      border-radius: 10px;
      cursor: pointer;
      transition: 0.2s;
    }

    button:hover {
      background: #c084fc;
    }

    .back-link {
      display: block;
      margin-top: 20px;
      text-align: center;
      color: #6b21a8;
      text-decoration: none;
      font-weight: bold;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    .error-msg {
      color: #b91c1c;
      background: #fee2e2;
      background: url('assets/img/bg_dashboard.jpg') no-repeat center center fixed;
      border: 1px solid #fecaca;
      padding: 10px;
      margin-top: 15px;
      border-radius: 10px;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="container">
  <!-- Zurück zur Startseite -->
  <a href="../startseite.php" class="back-link">⬅️ Zur Startseite</a>

  <!-- Titel mit Benutzername -->
  <h2>🔑 Passwort ändern für: <?= htmlspecialchars($user['username']) ?></h2>

  <!-- Fehlermeldung anzeigen -->
  <?php if ($meldung): ?>
    <div class="error-msg"><?= htmlspecialchars($meldung) ?></div>
  <?php endif; ?>

  <!-- Passwortformular -->
  <form method="post">
    <label>Neues Passwort:</label>
    <input type="password" name="password" required>
    <button type="submit">Speichern</button>
  </form>

  <!-- Link zurück zur Benutzerliste -->
  <a class="back-link" href="user_list.php">⬅️ Zur Benutzerübersicht</a>
</div>

</body>
</html>
