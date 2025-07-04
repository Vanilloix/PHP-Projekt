<?php
require_once 'session.php';
require_once 'config/db.php';

// Nur für eingeloggte Benutzer zugänglich
if (!ist_eingeloggt()) {
    header("Location: login.php");
    exit;
}

$msg = '';

// --- Einzelne Zeile löschen via GET-Parameter ---
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM project_measurements WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $msg = "Messwert mit ID #" . intval($_GET['id']) . " wurde gelöscht.";
}

// --- Mehrere Einträge gleichzeitig löschen via POST (Checkbox) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
    $ids = $_POST['delete_ids'];
    if (is_array($ids) && count($ids) > 0) {
        // Dynamisch Platzhalter erstellen für die IN()-Klausel
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("DELETE FROM project_measurements WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $msg = count($ids) . " Messwert(e) gelöscht.";
    } else {
        $msg = "⚠️ Keine Auswahl getroffen.";
    }
}

// Messdaten abrufen zur Anzeige
$stmt = $pdo->query("SELECT * FROM project_measurements ORDER BY timestamp DESC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>🗑️ Messwerte löschen</title>
  <!-- Design & Schrift -->
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background: url('assets/img/bg_dashboard.jpg') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      padding: 2rem;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      background: rgba(255, 255, 255, 0.95);
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #4c1d95;
      margin-bottom: 1.5rem;
    }

    form {
      margin-top: 1rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #ede9fe;
    }

    .btn {
      display: block;
      margin: 1rem auto;
      background-color: #e11d48;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
    }

    .btn:hover {
      background-color: #be123c;
    }

    .back-link {
      text-align: center;
      margin-top: 2rem;
    }

    .back-link a {
      color: #4c1d95;
      text-decoration: none;
      font-weight: bold;
    }

    .back-link a:hover {
      text-decoration: underline;
    }

    .msg {
      text-align: center;
      margin-top: 1rem;
      font-weight: bold;
      color: #444;
    }

    .delete-icon {
      text-decoration: none;
      font-size: 1.2rem;
      color: #dc2626;
    }

    .delete-icon:hover {
      text-decoration: underline;
      color: #b91c1c;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>🗑️ Messwerte löschen</h2>

  <!-- Erfolg / Fehlernachricht -->
  <?php if ($msg): ?>
    <div class="msg"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <form method="post">
    <table>
      <thead>
        <tr>
          <th>🧹</th>
          <th>ID</th>
          <th>Timestamp</th>
          <th>Temperatur (°C)</th>
          <th>Feuchtigkeit (%)</th>
          <th>Typ</th>
          <th>Wert</th>
          <th>Beschreibung</th>
          <th>Aktion</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $row): ?>
          <tr>
            <!-- Checkbox für Mehrfachlöschung -->
            <td><input type="checkbox" name="delete_ids[]" value="<?= $row['id'] ?>"></td>
            <td><?= $row['id'] ?></td>
            <td><?= $row['timestamp'] ?></td>
            <td><?= $row['temperature'] ?></td>
            <td><?= $row['humidity'] ?></td>
            <td><?= $row['additional_type'] ?></td>
            <td><?= $row['additional_value'] ?></td>
            <td><?= $row['description'] ?></td>
            <!-- Direktlösch-Link mit Bestätigung -->
            <td>
              <a href="?id=<?= $row['id'] ?>" 
                 class="delete-icon" 
                 onclick="return confirm('Diesen Eintrag wirklich löschen?')">🗑️</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <button type="submit" class="btn">🚨 Ausgewählte löschen</button>
  </form>

  <div class="back-link"><a href="index.php">⬅️ Zurück zur Startseite</a></div>
</div>

</body>
</html>
