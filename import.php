<?php
require_once 'session.php';
require_once 'config/db.php';

if (!ist_eingeloggt()) {
    header("Location: login.php");
    exit;
}

$msg = '';

if (isset($_POST['import'])) {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $fileTmpPath = $_FILES['csv_file']['tmp_name'];
        $fileName = $_FILES['csv_file']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if (strtolower($fileExtension) === 'csv') {
            if (($handle = fopen($fileTmpPath, 'r')) !== FALSE) {
                $row = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $row++;
                    if ($row == 1) continue;

                    $stmt = $pdo->prepare("
                        INSERT INTO project_measurements 
                        (timestamp, temperature, humidity, additional_type, additional_value, description)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $data[0] ?? null,
                        $data[1] ?? null,
                        $data[2] ?? null,
                        $data[3] ?? null,
                        $data[4] ?? null,
                        $data[5] ?? null
                    ]);
                }
                fclose($handle);
                $msg = "✅ Import abgeschlossen: " . ($row - 1) . " Zeilen verarbeitet.";
            } else {
                $msg = "Fehler beim Öffnen der Datei.";
            }
        } else {
            $msg = "Nur CSV-Dateien erlaubt!";
        }
    } else {
        $msg = "Keine Datei hochgeladen oder Fehler beim Upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>📤 CSV Import</title>
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
  <h1>📤 CSV Datei importieren</h1>

  <form method="post" enctype="multipart/form-data">
    <input type="file" name="csv_file" accept=".csv" required>
    <button type="submit" name="import">Importieren</button>
  </form>

  <?php if ($msg): ?>
    <div class="msg"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <div class="back-link"><a href="index.php">⬅️ Zurück zur Startseite</a></div>
</div>

</body>
</html>
