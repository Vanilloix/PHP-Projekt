<?php
require_once 'session.php';
require_once 'config/db.php';

if (!ist_eingeloggt()) {
    header("Location: login.php");
    exit;
}

$msg = '';

if (isset($_POST['import'])) {
    if (isset($_FILES['data_file']) && $_FILES['data_file']['error'] === 0) {
        $fileTmpPath = $_FILES['data_file']['tmp_name'];
        $fileName = $_FILES['data_file']['name'];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $imported = 0;

        if ($extension === 'csv') {
            if (($handle = fopen($fileTmpPath, 'r')) !== false) {
                $row = 0;
                while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                    $row++;

                    // Überspringe Header-Zeile oder unvollständige Zeilen
                    if ($row === 1 || count($data) < 6) continue;

                    // Alle Werte bereinigen (Leerzeichen entfernen)
                    $data = array_map('trim', $data);

                    // Zeitstempel korrekt formatieren (von z. B. "03.06.2025 09:41" → "2025-06-03 09:41:00")
                    $timestamp = date('Y-m-d H:i:s', strtotime(str_replace('.', '-', $data[0])));

                    $stmt = $pdo->prepare("
                        INSERT INTO project_measurements 
                        (timestamp, temperature, humidity, additional_type, additional_value, description)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $timestamp,
                        $data[1] ?? null,
                        $data[2] ?? null,
                        $data[3] ?? null,
                        $data[4] ?? null,
                        $data[5] ?? null
                    ]);
                    $imported++;
                }
                fclose($handle);
                $msg = "✅ CSV-Import abgeschlossen: $imported Zeilen.";
            } else {
                $msg = "❌ Fehler beim Öffnen der CSV-Datei.";
            }
        } elseif ($extension === 'json') {
            $jsonContent = file_get_contents($fileTmpPath);
            $jsonData = json_decode($jsonContent, true);

            if (is_array($jsonData)) {
                foreach ($jsonData as $row) {
                    $stmt = $pdo->prepare("
                        INSERT INTO project_measurements 
                        (timestamp, temperature, humidity, additional_type, additional_value, description)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $row['timestamp'] ?? null,
                        $row['temperature'] ?? null,
                        $row['humidity'] ?? null,
                        $row['additional_type'] ?? null,
                        $row['additional_value'] ?? null,
                        $row['description'] ?? null
                    ]);
                    $imported++;
                }
                $msg = "✅ JSON-Import abgeschlossen: $imported Messwerte übernommen.";
            } else {
                $msg = "❌ Fehler beim Parsen der JSON-Datei.";
            }
        } else {
            $msg = "❌ Nur CSV oder JSON erlaubt!";
        }
    } else {
        $msg = "❗ Keine Datei hochgeladen oder Fehler beim Upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>📤 Dateiimport</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Quicksand', sans-serif;
      background: url('assets/img/bg_import.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .container {
      background: rgba(255, 255, 255, 0.94);
      padding: 2rem;
      border-radius: 20px;
      max-width: 600px;
      width: 90%;
      box-shadow: 0 12px 25px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      color: #4c1d95;
      margin-bottom: 1.5rem;
    }
    input[type="file"] {
      display: block;
      margin: 1rem auto;
    }
    button {
      display: block;
      margin: 1rem auto;
      background-color: #9333ea;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
    }
    button:hover {
      background-color: #7e22ce;
    }
    .msg {
      text-align: center;
      margin-top: 1rem;
      color: #333;
      font-weight: bold;
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
  <h1>📤 Datei importieren (CSV)</h1>

  <form method="post" enctype="multipart/form-data">
    <input type="file" name="data_file" accept=".csv,.json" required>
    <button type="submit" name="import">🚀 Importieren</button>
  </form>

  <?php if ($msg): ?>
    <div class="msg"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <div class="back-link"><a href="index.php">⬅️ Zurück zur Startseite</a></div>
</div>

</body>
</html>
