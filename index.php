<?php
require_once 'config/db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Filter lesen
$typeFilter = isset($_GET['type']) ? $_GET['type'] : '';
$dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// SQL vorbereiten
$sql = "SELECT * FROM project_measurements WHERE 1=1";
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

$sql .= " ORDER BY timestamp DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Messwert-Übersicht</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f2f6fa; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #dbeeff; }
        form { margin-bottom: 20px; }
        label { margin-right: 10px; }
        input, select { padding: 5px 10px; margin-right: 10px; }
    </style>
</head>
<body>

<h1>📊 Messwerte Übersicht</h1>

<!-- Filterformular -->
<form method="get" action="index.php">
    <label for="type">Mess-Typ:</label>
    <select name="type" id="type">
        <option value="">Alle</option>
        <option value="CO2" <?= $typeFilter == 'CO2' ? 'selected' : '' ?>>CO2</option>
        <option value="Licht" <?= $typeFilter == 'Licht' ? 'selected' : '' ?>>Licht</option>
        <option value="Spannung" <?= $typeFilter == 'Spannung' ? 'selected' : '' ?>>Spannung</option>
    </select>

    <label for="date_from">Von:</label>
    <input type="date" name="date_from" id="date_from" value="<?= htmlspecialchars($dateFrom) ?>">

    <label for="date_to">Bis:</label>
    <input type="date" name="date_to" id="date_to" value="<?= htmlspecialchars($dateTo) ?>">

    <button type="submit">Filtern</button>
</form>

<!-- Tabelle -->
<?php if (count($data) > 0): ?>
<table>
    <thead>
        <tr>
            <th>Timestamp</th>
            <th>Temperatur (°C)</th>
            <th>Luftfeuchtigkeit (%)</th>
            <th>Typ</th>
            <th>Wert</th>
            <th>Beschreibung</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row): ?>
        <tr>
            <td><?= $row['timestamp'] ?></td>
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
    <p>⚠️ Keine Messdaten gefunden.</p>
<?php endif; ?>

</body>
</html>
