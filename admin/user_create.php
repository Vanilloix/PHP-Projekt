<?php
// Session und Datenbankverbindung einbinden
require_once '../session.php';
require_once '../config/db.php';

$meldung = '';

// Prüfen ob das Formular abgeschickt wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Eingaben aus dem Formular übernehmen
    $username = trim($_POST['username']);
    $passwort = $_POST['password'];

    // Prüfen ob beide Felder ausgefüllt wurden
    if ($username && $passwort) {
        // Passwort verschlüsseln
        $hash = password_hash($passwort, PASSWORD_DEFAULT);

        // Eintrag in die Datenbank vorbereiten
        $stmt = $pdo->prepare("INSERT INTO project_users (username, password_hash) VALUES (?, ?)");

        // Wenn Einfügen erfolgreich war → weiterleiten
        if ($stmt->execute([$username, $hash])) {
            // Bei Erfolg: Weiterleitung zur Übersicht
            header('Location: user_list.php');
            exit;
        } else {
            // Fehler z. B. bei doppeltem Benutzernamen
            $meldung = "Benutzername bereits vergeben oder Fehler!";
        }
    } else {
        $meldung = "Bitte alle Felder ausfüllen.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>➕ Benutzer erstellen</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background: url('assets/img/bg_dashboard.jpg') no-repeat center center fixed;
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

    form label {
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
  <!-- Link zur Startseite -->
  <a href="../startseite.php" class="back-link">⬅️ Zur Startseite</a>

  <h2>➕ Benutzer erstellen</h2>

  <!-- Fehlermeldung anzeigen, falls vorhanden -->
  <?php if ($meldung): ?>
    <div class="error-msg"><?= htmlspecialchars($meldung) ?></div>
  <?php endif; ?>

  <!-- Formular zur Benutzererstellung -->
  <form method="post">
    <label>Benutzername:</label>
    <input type="text" name="username" required>

    <label>Passwort:</label>
    <input type="password" name="password" required>

    <button type="submit">Erstellen</button>
  </form>

  <!-- Zurück zur Benutzerliste -->
  <a class="back-link" href="user_list.php">⬅️ Zur Benutzerübersicht</a>
</div>

</body>
</html>
