<?php
require_once 'session.php';
require_once 'config/db.php';

$typeFilter = $_GET['type'] ?? '';
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';

$sql = "SELECT timestamp, temperature, humidity FROM project_measurements WHERE 1=1";
$params = [];

if ($typeFilter !== '') {
    $sql .= " AND additional_type = :type";
    $params[':type'] = $typeFilter;
}
if ($dateFrom !== '') {
    $sql .= " AND timestamp >= :from";
    $params[':from'] = $dateFrom . " 00:00:00";
}
if ($dateTo !== '') {
    $sql .= " AND timestamp <= :to";
    $params[':to'] = $dateTo . " 23:59:59";
}

$sql .= " ORDER BY timestamp ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$timestamps = [];
$temps = [];
$humid = [];

foreach ($data as $row) {
    $timestamps[] = $row['timestamp'];
    $temps[] = $row['temperature'];
    $humid[] = $row['humidity'];
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>📈 Messwerte Diagramm</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background: url('assets/img/bg_diagram.jpg') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      padding: 2rem;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      background: rgba(255, 255, 255, 0.93);
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 12px 25px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #4c1d95;
      margin-bottom: 1.5rem;
    }

    form {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 10px;
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
    }

    select, input {
      padding: 8px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    button {
      background: #9333ea;
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background: #7e22ce;
    }

    .chart-container {
      background: white;
      padding: 1rem;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.07);
    }

    .back-link {
      display: block;
      margin-top: 2rem;
      text-align: center;
      font-weight: bold;
      color: #4c1d95;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>📈 Messwerte Diagramm</h2>

  <form method="get">
    <label for="type">Typ:</label>
    <select name="type" id="type">
      <option value="">Alle</option>
      <option value="CO2" <?= $typeFilter == 'CO2' ? 'selected' : '' ?>>CO2</option>
      <option value="Licht" <?= $typeFilter == 'Licht' ? 'selected' : '' ?>>Licht</option>
      <option value="Spannung" <?= $typeFilter == 'Spannung' ? 'selected' : '' ?>>Spannung</option>
    </select>

    <label for="date_from">Von:</label>
    <input type="date" name="date_from" value="<?= htmlspecialchars($dateFrom) ?>">

    <label for="date_to">Bis:</label>
    <input type="date" name="date_to" value="<?= htmlspecialchars($dateTo) ?>">

    <button type="submit">Filtern</button>
  </form>

  <div class="chart-container">
    <canvas id="chart" height="100"></canvas>
  </div>

  <a href="index.php" class="back-link">⬅️ Zur Startseite</a>
</div>

<script>
const ctx = document.getElementById('chart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($timestamps) ?>,
        datasets: [
            {
                label: '🌡 Temperatur (°C)',
                data: <?= json_encode($temps) ?>,
                borderColor: '#f87171',
                backgroundColor: 'rgba(248,113,113,0.3)',
                tension: 0.3
            },
            {
                label: '💧 Luftfeuchtigkeit (%)',
                data: <?= json_encode($humid) ?>,
                borderColor: '#60a5fa',
                backgroundColor: 'rgba(96,165,250,0.3)',
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: { title: { display: true, text: 'Zeitstempel' } },
            y: { title: { display: true, text: 'Wert' } }
        }
    }
});
</script>

</body>
</html>
