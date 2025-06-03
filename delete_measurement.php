<?php
require_once 'session.php';
require_once 'config/db.php';

// Messwerte abrufen für die Anzeige
$stmt = $pdo->query("SELECT * FROM project_measurements ORDER BY timestamp DESC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Wenn Lösch-ID gesetzt ist
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Sicherheitscheck + Löschung durchführen
    $stmtDel = $pdo->prepare("DELETE FROM project_measurements WHERE id = ?");
    $stmtDel->execute([$id]);

    // Redirect nach Löschung mit Status
    header("Location: delete_measurement.php?deleted=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>🗑️ Messwert löschen</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background: linear-gradient(to bottom right, #fff7ed, #e0f2fe);
      background: url('assets/img/bg_dashboard.jpg') no-repeat center center fixed;
      margin: 0;
      padding: 2rem;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      background: white;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    h2 {
      text-align: center;
      color: #4c1d95;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }

    th, td {
      padding: 10px;
      border: 1px solid #eee;
      text-align: center;
    }

    th {
      background-color: #ede9fe;
    }

    .btn-del {
      background: #ef4444;
      color: white;
      padding: 6px 10px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      text-decoration: none;
    }

    .btn-del:hover {
      background: #dc2626;
    }

    .meldung {
      background: #ecfccb;
      border: 1px solid #bbf7d0;
      color: #166534;
      border-radius: 10px;
      padding: 10px;
      margin-bottom: 15px;
      text-align: center;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 30px;
      font-weight: bold;
      text-decoration: none;
      color: #4c1d95;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>🗑️ Messwerte löschen</h2>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="meldung">✅ Eintrag wurde gelöscht.</div>
  <?php endif; ?>

  <?php if (count($data) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Zeitstempel</th>
          <th>Temperatur</th>
          <th>Luftfeuchtigkeit</th>
          <th>Typ</th>
          <th>Wert</th>
          <th>Beschreibung</th>
          <th>Aktion</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $row): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['timestamp'] ?></td>
            <td><?= $row['temperature'] ?></td>
            <td><?= $row['humidity'] ?></td>
            <td><?= $row['additional_type'] ?></td>
            <td><?= $row['additional_value'] ?></td>
            <td><?= $row['description'] ?></td>
            <td>
              <a class="btn-del" href="delete_measurement.php?id=<?= $row['id'] ?>" onclick="return confirm('Diesen Eintrag wirklich löschen?')">Löschen</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>Keine Daten gefunden.</p>
  <?php endif; ?>

  <a class="back-link" href="index.php">⬅️ Zurück zur Startseite</a>
</div>

</body>
</html>
