<?php
require_once 'session.php';
require_once 'config/db.php';

// Sicherstellen, dass nur eingeloggte Benutzer Zugriff haben
if (!ist_eingeloggt()) {
    header("Location: login.php");
    exit;
}

$msg = '';

// Formularverarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Werte mit Fallbacks
    $timestamp = $_POST['timestamp'] ?? date('Y-m-d H:i:s');
    $temperature = $_POST['temperature'] ?? null;
    $humidity = $_POST['humidity'] ?? null;
    $additional_type = $_POST['additional_type'] ?? null;
    $additional_value = $_POST['additional_value'] ?? null;
    $description = $_POST['description'] ?? '';

    // SQL-Anfrage vorbereiten
    $stmt = $pdo->prepare("
        INSERT INTO project_measurements 
        (timestamp, temperature, humidity, additional_type, additional_value, description)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    // Werte binden und ausführen
    $stmt->execute([
        $timestamp,
        $temperature,
        $humidity,
        $additional_type,
        $additional_value,
        $description
    ]);

    // Bestätigung anzeigen
    $msg = "✅ Datensatz wurde erfolgreich gespeichert!";
}
?>

<!-- HTML-Teil: Eingabeformular -->
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>➕ Messwert hinzufügen</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Quicksand', sans-serif;
      background: url('assets/img/bg_add.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      background: rgba(255,255,255,0.95);
      padding: 2rem;
      border-radius: 20px;
      max-width: 600px;
      width: 90%;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    h1 {
      text-align: center;
      color: #4c1d95;
      margin-bottom: 1.5rem;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    input, select {
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      width: 100%;
    }

    button {
      background: #10b981;
      background: url('assets/img/bg_dashboard.jpg') no-repeat center center fixed;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background: #059669;
    }

    .msg {
      text-align: center;
      margin-top: 1rem;
      color: #16a34a;
      font-weight: bold;
    }

    .back-link {
      margin-top: 1.5rem;
      text-align: center;
    }

    .back-link a {
      color: #4c1d95;
      text-decoration: none;
    }

    .back-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="container">
  <h1>➕ Messwert hinzufügen</h1>

  <form method="post">
    <input type="datetime-local" name="timestamp" value="<?= date('Y-m-d\TH:i') ?>">
    <input type="number" step="0.1" name="temperature" placeholder="🌡️ Temperatur (°C)">
    <input type="number" step="0.1" name="humidity" placeholder="💧 Luftfeuchtigkeit (%)">
    <select name="additional_type">
      <option value="">🔘 Zusatztyp auswählen</option>
      <option value="CO2">CO2</option>
      <option value="Licht">Licht</option>
      <option value="Spannung">Spannung</option>
    </select>
    <input type="number" step="0.1" name="additional_value" placeholder="📈 Zusatzwert">
    <input type="text" name="description" placeholder="📝 Beschreibung" required>
    <button type="submit">➕ Speichern</button>
  </form>

  <?php if ($msg): ?>
    <div class="msg"><?= $msg ?></div>
  <?php endif; ?>

  <div class="back-link"><a href="index.php">⬅️ Zurück zur Startseite</a></div>
</div>

</body>
</html>
