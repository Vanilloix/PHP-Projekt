<?php
require_once 'session.php';
require_once 'config/db.php';

// Daten laden (letzte 20 Einträge)
$stmt = $pdo->query("SELECT timestamp, temperature, humidity FROM project_measurements ORDER BY timestamp DESC LIMIT 20");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Arrays für JS
$timestamps = array_reverse(array_column($data, 'timestamp'));
$temperatures = array_reverse(array_column($data, 'temperature'));
$humidities = array_reverse(array_column($data, 'humidity'));
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>📊 Diagramm</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial; padding: 30px; background: #f5f9ff; }
        canvas { max-width: 100%; height: auto; }
        h2 { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>

<h2>📊 Messwerte Diagramm</h2>
<canvas id="chart" width="800" height="400"></canvas>

<script>
const ctx = document.getElementById('chart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($timestamps) ?>,
        datasets: [
            {
                label: '🌡️ Temperatur (°C)',
                data: <?= json_encode($temperatures) ?>,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,0.2)',
                tension: 0.3,
                fill: false
            },
            {
                label: '💧 Luftfeuchtigkeit (%)',
                data: <?= json_encode($humidities) ?>,
                borderColor: 'rgba(54,162,235,1)',
                backgroundColor: 'rgba(54,162,235,0.2)',
                tension: 0.3,
                fill: false
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Zeitpunkt'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Wert'
                }
            }
        }
    }
});
</script>

</body>
</html>
