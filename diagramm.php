<?php
require_once 'session.php';
require_once 'config/db.php';

// Verfügbare Filteroptionen
$validTypes = ['temperature' => 'Temperatur', 'humidity' => 'Feuchtigkeit', 'Luftdruck' => 'Luftdruck'];

$typeFilter = $_GET['type'] ?? '';
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';

// Grundabfrage
$sql = "SELECT timestamp, temperature, humidity, additional_type, additional_value FROM project_measurements WHERE 1=1";
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

// Daten vorbereiten
$timestamps = [];
$temps = [];
$humid = [];
$pressure = [];

foreach ($data as $row) {
    $timestamps[] = $row['timestamp'];
    $temps[] = $row['temperature'];
    $humid[] = $row['humidity'];
    $pressure[] = ($row['additional_type'] === 'Luftdruck') ? (float)$row['additional_value'] : null;
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>📈 Temperatur & Feuchtigkeit</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background: #f9f9ff;
      margin: 0;
      padding: 2rem;
    }

    h2 {
      text-align: center;
      color: #4c1d95;
      font-size: 1.8rem;
    }

    form {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 10px;
      margin-bottom: 1.5rem;
    }

    select, input[type="date"], button {
      padding: 10px 14px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }

    button {
      background: #9333ea;
      color: white;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background: #7e22ce;
    }

    .scroll-box {
      overflow-x: auto;
      padding-bottom: 10px;
    }

    .chart-container {
      width: 1400px;
      height: 400px;
      margin: auto;
      background: white;
      border-radius: 15px;
      padding: 1rem;
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

<h2>📊 Temperatur & Feuchtigkeit</h2>

<!-- Filterformular -->
<form method="get">
  <select name="type">
    <option value="">Alle Typen</option>
    <option value="CO2" <?= $typeFilter == 'CO2' ? 'selected' : '' ?>>CO2</option>
    <option value="Licht" <?= $typeFilter == 'Licht' ? 'selected' : '' ?>>Licht</option>
    <option value="Spannung" <?= $typeFilter == 'Spannung' ? 'selected' : '' ?>>Spannung</option>
  </select>
  <input type="date" name="date_from" value="<?= htmlspecialchars($dateFrom) ?>">
  <input type="date" name="date_to" value="<?= htmlspecialchars($dateTo) ?>">
  <button type="submit">🔍 Filtern</button>
</form>

<!-- Diagramm -->
<div class="scroll-box">
  <div class="chart-container">
    <canvas id="chart"></canvas>
  </div>
</div>

<a href="index.php" class="back-link">⬅️ Zurück zur Startseite</a>

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
        borderColor: '#ef4444',
        backgroundColor: 'rgba(239,68,68,0.2)',
        tension: 0.3,
        pointRadius: 2
      },
      {
        label: '💧 Luftfeuchtigkeit (%)',
        data: <?= json_encode($humid) ?>,
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59,130,246,0.2)',
        tension: 0.3,
        pointRadius: 2
      },
      {
        label: '⏱ Luftdruck (hPa)',
        data: <?= json_encode($pressure) ?>,
        borderColor: '#a855f7',
        backgroundColor: 'rgba(168,85,247,0.2)',
        tension: 0.3,
        pointRadius: 2
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        labels: {
          font: { size: 14 }
        }
      }
    },
    scales: {
      x: {
        title: { display: true, text: 'Zeitstempel' },
        ticks: {
          autoSkip: true,
          maxTicksLimit: 10,
          maxRotation: 45,
          minRotation: 45
        }
      },
      y: {
        title: { display: true, text: 'Messwert' }
      }
    }
  }
});
</script>

</body>
</html>
