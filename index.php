<?php
require_once 'session.php';
require_once 'config/db.php';

// Filter auslesen
$typeFilter = $_GET['type'] ?? '';
$dateFrom   = $_GET['date_from'] ?? '';
$dateTo     = $_GET['date_to'] ?? '';

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
  <title>🌤️ Wetterdaten Übersicht</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="main-bg">

<div class="container">
  <h1>🌸 Messwerte Übersicht</h1>

  <!-- Filterformular -->
  <form class="filter-box" method="get" action="index.php">
    <label>Typ:</label>
    <select name="type">
      <option value="">Alle</option>
      <option value="CO2"      <?= $typeFilter == 'CO2' ? 'selected' : '' ?>>CO2</option>
      <option value="Licht"    <?= $typeFilter == 'Licht' ? 'selected' : '' ?>>Licht</option>
      <option value="Spannung" <?= $typeFilter == 'Spannung' ? 'selected' : '' ?>>Spannung</option>
    </select>

    <label>Von:</label>
    <input type="date" name="date_from" value="<?= htmlspecialchars($dateFrom) ?>">

    <label>Bis:</label>
    <input type="date" name="date_to" value="<?= htmlspecialchars($dateTo) ?>">

    <button type="submit">🎯 Filtern</button>
  </form>

  <!-- Tabelle -->
  <?php if (count($data) > 0): ?>
    <table class="fancy-table">
      <thead>
        <tr>
          <th>⏱️ Zeit</th>
          <th>🌡️ Temperatur (°C)</th>
          <th>💧 Luftfeuchtigkeit (%)</th>
          <th>⚙️ Typ</th>
          <th>📈 Wert</th>
          <th>📝 Beschreibung</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $row): ?>
          <tr>
            <td><?= $row['timestamp'] ?></td>
            <td>
              <?php
                $temp = $row['temperature'];
                if ($temp < 10) {
                    echo "<span class='badge cold'>❄️ {$temp}°C</span>";
                } elseif ($temp <= 25) {
                    echo "<span class='badge mild'>🌼 {$temp}°C</span>";
                } else {
                    echo "<span class='badge hot'>🔥 {$temp}°C</span>";
                }
              ?>
            </td>
            <td><?= $row['humidity'] ?></td>
            <td><?= $row['additional_type'] ?></td>
            <td><?= $row['additional_value'] ?></td>
            <td><?= $row['description'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="empty-msg">❗ Keine Messdaten gefunden.</p>
  <?php endif; ?>

  <!-- Navigation -->
  <div class="nav-links">
    <a href="diagramm.php">📊 Diagramm</a>
    <a href="export.php">📥 Export</a>
    <a href="import.php">📤 Import</a>
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="admin/user_list.php">👥 Benutzer</a>
      <a href="logout.php">🔓 Logout</a>
    <?php else: ?>
      <a href="login.php">🔐 Login</a>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
