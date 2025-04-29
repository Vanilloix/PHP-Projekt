-- Erstellt die Datenbank, falls sie noch nicht existiert
CREATE DATABASE IF NOT EXISTS messdatenerfassung;

-- Wählt die Datenbank aus, mit der gearbeitet wird
USE messdatenerfassung;

-- Erstellt die Benutzertabelle für Login & Verwaltung 
CREATE TABLE project_users (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Eindeutige ID für Benutzer
    username VARCHAR(255) NOT NULL UNIQUE, --Benutzername (einzigartig, Pflichtfeld)
    password_hash CHAR(64) NOT NULL -- Passwort-Hash (SHA256 ergibt 64 Zeichen)
);

-- Erstellt die Messwert-Tabelle für Sensoren und ESP32-Messungen
CREATE TABLE project_measurements (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Eindeutige ID für jede Messung 
    timestamp DATETIME NOT NULL, -- Zeitstempel wann die Messung gemacht wurde
    temperature FLOAT, -- Temperaturwert (°C), optional
    humidity FLOAT, -- Luftfeuchtigkeit (%), optional
    additional_type VARCHAR(50), -- Art der Zusatzmessung (z.B. CO², Licht), optional
    additional_value FLOAT, -- Wert der Zusatzmessung, optional
    description VARCHAR(255) NOT NULL -- Beschreibung des Sensors/Standorts oder Fehlermeldung
);