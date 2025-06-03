<?php
require_once 'config/db.php';

$stmt = $pdo->query("SELECT * FROM project_measurements ORDER BY timestamp DESC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>🌦️ Messwerte anzeigen</title>
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

    .empty {
      text-align: center;
      color: #888;
      margin-top: 2rem;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>🌦️ Gespeicherte Messwerte</h2>

  <?php if (count($data) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Datum</th>
          <th>Temperatur (°C)</th>
          <th>Feuchtigkeit (%)</th>
          <th>Typ</th>
          <th>Wert</th>
          <th>Beschreibung</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $row): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= date('d.m.Y H:i', strtotime($row['timestamp'])) ?></td>
            <td><?= $row['temperature'] ?></td>
            <td><?= $row['humidity'] ?></td>
            <td><?= $row['additional_type'] ?></td>
            <td><?= $row['additional_value'] ?></td>
            <td><?= $row['description'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="empty">Keine Messdaten gefunden.</p>
  <?php endif; ?>

  <a class="back-link" href="index.php">⬅️ Zurück zur Startseite</a>
</div>

</body>
</html>
