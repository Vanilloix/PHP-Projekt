<?php
require_once 'session.php';
require_once 'config/db.php';

if (!ist_eingeloggt()) {
    header("Location: login.php");
    exit;
}

header('Content-Type: text/plain');

$type = $_GET['type'] ?? 'csv'; // csv oder xml
$start = $_GET['start'] ?? null;
$end = $_GET['end'] ?? null;
$filterType = $_GET['additional_type'] ?? null;

$query = "SELECT * FROM project_measurements WHERE 1";
$params = [];

if ($start) {
    $query .= " AND timestamp >= ?";
    $params[] = $start;
}
if ($end) {
    $query .= " AND timestamp <= ?";
    $params[] = $end;
}
if ($filterType) {
    $query .= " AND additional_type = ?";
    $params[] = $filterType;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($type === 'xml') {
    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename="messwerte_export.xml"');

    $xml = new SimpleXMLElement('<measurements/>');
    foreach ($data as $row) {
        $m = $xml->addChild('measurement');
        $m->addChild('timestamp', date("d.m.Y H:i \U\h\r", strtotime($row['timestamp'])));
        $m->addChild('temperature', $row['temperature']);
        $m->addChild('humidity', $row['humidity']);
        $m->addChild('additional_type', $row['additional_type']);
        $m->addChild('additional_value', $row['additional_value']);
        $m->addChild('description', htmlspecialchars($row['description']));
    }
    echo $xml->asXML();
} else {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="messwerte_export.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Zeitstempel', 'Temperatur', 'Feuchtigkeit', 'Typ', 'Wert', 'Beschreibung']);

    foreach ($data as $row) {
        fputcsv($output, [
            date("d.m.Y H:i \U\h\r", strtotime($row['timestamp'])),
            $row['temperature'],
            $row['humidity'],
            $row['additional_type'],
            $row['additional_value'],
            $row['description']
        ]);
    }

    fclose($output);
}
    exit;
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>📤 Messdaten Export</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background: url('assets/img/bg_export.jpg') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      padding: 2rem;
    }

    .container {
      max-width: 700px;
      margin: auto;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #4c1d95;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    label {
      font-weight: bold;
    }

    select, input {
      padding: 10px;
      border-radius: 10px;
      border: 1px solid #ccc;
    }

    button {
      background: #9333ea;
      color: white;
      border: none;
      padding: 12px;
      font-weight: bold;
      border-radius: 10px;
      cursor: pointer;
    }

    button:hover {
      background: #7e22ce;
    }

    .back-link {
      text-align: center;
      margin-top: 1.5rem;
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
  <h2>📤 Exportiere Messdaten</h2>

  <form method="post">
    <label for="type">Sensor-Typ:</label>
    <select name="type" id="type">
      <option value="">Alle</option>
      <option value="CO2">CO2</option>
      <option value="Licht">Licht</option>
      <option value="Spannung">Spannung</option>
      <option value="Luftdruck">Luftdruck</option>
    </select>

    <label for="from">Von:</label>
    <input type="date" name="from" id="from">

    <label for="to">Bis:</label>
    <input type="date" name="to" id="to">

    <label for="format">Format:</label>
    <select name="format" id="format">
      <option value="csv">CSV</option>
      <option value="xml">XML</option>
    </select>

    <button type="submit">📥 Export starten</button>
  </form>

  <div class="back-link">
    <a href="index.php">⬅️ Zurück zur Startseite</a>
  </div>
</div>

</body>
</html>
