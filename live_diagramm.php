<?php
require_once 'session.php';
require_once 'config/db.php';

// Letzter Timestamp prüfen (innerhalb 90 Sekunden gilt als "online")
$stmt = $pdo->query("SELECT MAX(timestamp) as last FROM project_measurements");
$row = $stmt->fetch();
$lastTimestamp = strtotime($row['last'] ?? '1970-01-01 00:00:00');
$isOnline = (time() - $lastTimestamp) <= 90;

// Neuste 20 Werte abrufen
$stmt = $pdo->query("SELECT timestamp, temperature, humidity, additional_type, additional_value 
                     FROM project_measurements 
                     ORDER BY timestamp DESC LIMIT 20");
$data = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));

$timestamps = array_column($data, 'timestamp');
$temperature = array_column($data, 'temperature');
$humidity = array_column($data, 'humidity');
$pressure = array_map(function($d) {
  return $d['additional_type'] === 'Luftdruck' ? (float)$d['additional_value'] : null;
}, $data);
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>📡 Live Diagramm – Wetterportal</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      margin: 0;
      padding: 2rem;
      background: #f0f4ff;
    }
    h1 {
      text-align: center;
      color: #4c1d95;
    }
    .status {
      text-align: center;
      margin-bottom: 1rem;
      font-weight: bold;
    }
    .status span {
      padding: 6px 12px;
      border-radius: 8px;
      color: white;
    }
    .online {
      background-color: #10b981;
    }
    .offline {
      background-color: #ef4444;
    }
    .chart-container {
      max-width: 1000px;
      margin: auto;
      background: white;
      padding: 1.5rem;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0,0,0,0.08);
    }
    canvas {
      width: 100% !important;
      height: 400px !important;
    }
    .nav-links {
      text-align: center;
      margin-top: 1.5rem;
    }
    .nav-links a {
      margin: 0 10px;
      color: #4c1d95;
      font-weight: bold;
      text-decoration: none;
    }
    .nav-links a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<h1>📡 Live Diagramm</h1>

<div class="status">
  ESP Status: 
  <span class="<?= $isOnline ? 'online' : 'offline' ?>">
    <?= $isOnline ? '🟢 Online' : '🔴 Offline' ?>
  </span>
</div>

<div class="chart-container">
  <canvas id="chart"></canvas>
</div>

<div class="nav-links">
  <a href="index.php">⬅️ Zur Startseite</a>
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
        data: <?= json_encode($temperature) ?>,
        borderColor: '#ef4444',
        backgroundColor: 'rgba(239,68,68,0.2)',
        tension: 0.3,
        pointRadius: 5,
        hoverRadius: 6
      },
      {
        label: '💧 Luftfeuchtigkeit (%)',
        data: <?= json_encode($humidity) ?>,
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59,130,246,0.2)',
        tension: 0.3,
        pointRadius: 5,
        hoverRadius: 6
      },
      {
        label: '📊 Luftdruck (hPa)',
        data: <?= json_encode($pressure) ?>,
        borderColor: '#8b5cf6',
        backgroundColor: 'rgba(139,92,246,0.2)',
        tension: 0.3,
        pointRadius: 5,
        hoverRadius: 6,
        hidden: <?= in_array(null, $pressure) ? 'true' : 'false' ?>
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { labels: { font: { size: 14 } } }
    },
    scales: {
      x: {
        title: { display: true, text: 'Zeitstempel' },
        ticks: {
          autoSkip: true,
          maxTicksLimit: 20,
          maxRotation: 45,
          minRotation: 45
        }
      },
      y: {
        title: { display: true, text: 'Wert' }
      }
    }
  }
});
</script>

</body>
</html>
