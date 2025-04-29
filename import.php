<?php

require_once 'config/db.php'; // Datenbankverbindung einbinden

// Fehleranzeige aktivieren
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['import'])) {
    //Prüfen ob Datei hochgeladen wurde
    if(isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $fileTmpPath = $_FILES['csv_file']['tmp_name']; // Temporärer Dateipfad
        $fileName = $_FILES['csv_file']['name']; // Ursprünglicher Dateiname
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    
        // Nur CSV akzeptieren
        if (strtolower($fileExtension) === 'csv') {
            // Datei öffnen
            if (($handle = fopen($fileTmpPath, 'r')) !== FALSE) {
                $row = 0; //Zeilenzähler starten
                
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $row++;

                    // Erste Zeile überspringen (Header)
                    if ($row == 1) {
                        continue;
                    }

                    // CSV-Spalten in Variablen speichern
                    $timestamp        = isset($data[0]) ? $data[0] : null;
                    $temperature      = isset($data[1]) ? $data[1] : null;
                    $humidity         = isset($data[2]) ? $data[2] : null;
                    $additional_type  = isset($data[3]) ? $data[3] : null;
                    $additional_value = isset($data[4]) ? $data[4] : null;
                    $description      = isset($data[5]) ? $data[5] : null;

                    // SQL-Vorbereitung
                    $sql = "INSERT INTO project_measurements 
                        (timestamp, temperature, humidity, additional_type, additional_value, description) 
                        VALUES (:timestamp, :temperature, :humidity, :additional_type, :additional_value, :description)";
                    $stmt = $pdo->prepare($sql);

                    // Daten zuweisen
                    $stmt->execute([
                        ':timestamp' => $timestamp,
                        ':temperature' => $temperature,
                        ':humidity' => $humidity,
                        ':additional_type' => $additional_type,
                        ':additional_value' => $additional_value,
                        ':description' => $description
                    ]);


                }    

                fclose($handle); // Datei schließen
                
                echo "<p>✅ Import abgeschlossen. " . ($row - 1) . " Zeilen verarbeitet.</p>";
            
            } else {
                echo "Fehler beim Öffnen der Datei.";
            }
        } else {
            echo "Nur CSV-Dateien erlaubt!";
        }
    } else {
        echo "Keine Datei hochgeladen oder Fehler beim Upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>CSV importieren</title>
</head>
<body>

<h1>CSV Datei importieren</h1>

<form action="import.php" method="post" enctype="multipart/form-data">
    <label for="csv_file">Wähle eine CSV Datei:</label><br><br>
    <input type="file" name="csv_file" id="csv_file" accept=".csv" required><br><br>
    <button type="submit" name="import">Importieren</button>
</form>

</body>
</html>