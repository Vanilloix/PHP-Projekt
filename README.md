# 🌤 PHP-Projekt: Messdatenerfassung

Ein webbasiertes Tool zur Verwaltung, Anzeige und Analyse von Sensordaten (Temperatur, Luftfeuchtigkeit, Zusatzwerte) mit PHP, MySQL und ESP32.

---

## 🔧 Funktionen

- 📥 Import (CSV & XML)
- 📊 Filterbare Übersichtstabelle
- 📡 Echtzeitdaten vom ESP32 (via HTTP POST)
- 🔐 Benutzerverwaltung (Login, Registrierung, Passwort ändern)
- 🗑️ Messwert-Löschung (nur eingeloggt)
- 📤 Export (CSV & XML)
- 📈 Diagramme & Live-Visualisierung (Chart.js)
- 🎨 Modernes UI mit Animationen & Icons

---

## ⚙️ Verwendete Technologien

| Technologie  | Zweck                  |
|--------------|------------------------|
| PHP 8.x      | Backend-Logik & API    |
| MySQL        | Datenhaltung           |
| HTML/CSS     | Benutzeroberfläche     |
| Chart.js     | Diagramme              |
| ESP32        | Sensordatenerfassung   |
| Arduino IDE  | Microcontroller-Setup  |

---

## 🗃️ Datenbankstruktur

```sql
-- Tabelle: project_measurements
id                INT (PK)
timestamp         DATETIME
temperature       FLOAT
humidity          FLOAT
additional_type   VARCHAR(50)
additional_value  FLOAT
description       VARCHAR(255)

-- Tabelle: project_users
id                INT (PK)
username          VARCHAR(255) UNIQUE
password_hash     VARCHAR(255)
```

---

## 🧪 Test-Zugang

```txt
Benutzername: test
Passwort: test
```

---

## 🔗 ERM-Beziehung

```plaintext
[project_users] 1 -------- n [project_measurements]
```

---

## 🧭 Projektübersicht

- Start-Dashboard mit Kachelmenü
- Live-Diagramm + Online-Statusanzeige
- Dateiimport für CSV/XML
- Detailanzeige für Messdaten
- Benutzerverwaltung mit Admin-Schutz

---

## 👩‍💻 Projektdaten

- **Name**: Anna Tetzlaff  
- **Abgabe**: 03. Juni 2025  
- **Präsentation**: ab 23. Juni 2025  
- **Projektordner**: `/PHP-PROJEKT`

---

## 📌 Hinweise zur Nutzung

- `create_database.sql` ausführen  
- `http://localhost/PHP-PROJEKT/start.php` Startseite öffnen