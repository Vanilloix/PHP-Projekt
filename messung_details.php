<?php
require_once 'config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
  header("Location: messwerte.php");
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM project_measurements WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
  echo "⚠️ Messung nicht gefunden.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>🔍 Messung #<?= htmlspecialchars($id) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background: url('assets/img/bg_dashboard.jpg') no-repeat center center fixed;
      background-size: cover;
      padding: 2rem;
    }

    .container {
      max-width: 700px;
      margin: auto;
      background: rgba(255, 255, 255, 0.95);
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #4c1d95;
      margin-bottom: 2rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
    }

    td.label {
      font-weight: bold;
      background-color: #ede9fe;
      width: 40%;
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
  <h2>🔍 Messung #<?= htmlspecialchars($row['id']) ?></h2>
  <table>
    <tr><td class="label">Zeitstempel</td><td><?= $row['timestamp'] ?></td></tr>
    <tr><td class="label">Temperatur (°C)</td><td><?= $row['temperature'] ?></td></tr>
    <tr><td class="label">Luftfeuchtigkeit (%)</td><td><?= $row['humidity'] ?></td></tr>
    <tr><td class="label">Zusatztyp</td><td><?= $row['additional_type'] ?></td></tr>
    <tr><td class="label">Zusatzwert</td><td><?= $row['additional_value'] ?></td></tr>
    <tr><td class="label">Beschreibung</td><td><?= $row['description'] ?></td></tr>
  </table>

  <a class="back-link" href="messwerte.php">⬅️ Zurück</a>
</div>

</body>
</html>
