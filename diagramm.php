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
</head>
<body style="background: #f4f9ff; font-family: Arial, sans-serif; text-align: center;">

<h2>📈 Messwerte Diagramm</h2>

<!-- Filterformular -->
<form method="get" style="margin-bottom: 20px;">
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

<canvas id="chart" height="100"></canvas>

<script>
const ctx = document.getElementById('chart').getContext('2d');
const chart = new Chart(ctx, {
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
    }
});
</script>

<p style="margin-top: 30px;">
    <a href="index.php">🔙 Zurück zur Übersicht</a>
</p>

</body>
</html>
