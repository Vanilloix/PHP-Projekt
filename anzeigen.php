<?php
require_once 'config/db.php'; // DB-Verbindung einbinden

// Fehleranzeige aktivieren
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Alle Messwerte abrufen
$sql = "SELECT * FROM project_measurements ORDER BY timestamp DESC";
$stmt = $pdo->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Messwerte anzeigen</title>
    <style>
        body { font-family: Arial; background: #f4f9ff; padding 20px; }
        h1 { color: #333; }
        table { border-collapse: collapse; width 100%; background: white; box-shadow 0 0 10px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: center; }
        th { background-color: #d8ecff; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .back-link { margin-top: 20px; display: inline-block; }
    </style>
</head>
<body>

<h1>Gespeicherte Messdaten</h1>

<?php if (count($data) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Timestamp</th>
                <th>Temperatur (°C)</th>
                <th>Luftfeuchte (%)</th>
                <th>Zusatztyp</th>
                <th>Zusatzwert</th>
                <th>Beschreibung</th>
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
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Keine Messdaten gefunden.</p>
<?php endif; ?>

<a class="back-link" href="import.php">Zurueck zum Import</a>

</body>
</html>