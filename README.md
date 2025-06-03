````markdown
##  PHP-Projekt zur Messdatenerfassung

Ein Tool zur Verwaltung und Visualisierung von Temperatur-, Feuchtigkeits- und Zusatzdaten mit PHP, MySQL und ESP32.

---

## 🔧 Funktionen

- 📥 CSV-Import von Messwerten
- 📊 Übersichtstabelle mit Filtern
- 📡 Anbindung eines ESP32 zur Live-Datenerfassung
- 🔐 Benutzerverwaltung (Login, Logout, Passwort ändern)
- 🗑️ Löschfunktion für Messwerte
- 📤 Export als CSV
- 💅 Einheitliches UI mit Icons & Hintergrund

---

## 📦 Verwendete Technologien

- PHP 8.x
- MySQL 
- HTML
- ESP32 (Funduino)
- Arduino IDE 2.x

---

## 🗃️ Datenbankstruktur

```sql
project_measurements
- id (int)
- timestamp (datetime)
- temperature (float)
- humidity (float)
- additional_type (varchar)
- additional_value (float)
- description (varchar)

project_users
- id (int)
- username (varchar)
- password_hash (varchar)
````

---

## 🔐 Beispiel-Login

```txt
Benutzer: admin
Passwort: geheim123
```


## 👩‍💻 Team / Autor

Name: \[Anna Tetzlaff]
Datum: 2025-06-02
Präsentation: 23. Juni 2025

````

---

## 🧠 ERM-Modell (Text-Form)

```plaintext
[project_users]
+----------------+     1
| id (PK)        |<-------------+
| username       |              |
| password_hash  |              |
+----------------+              |
                                | n
                                |
                                v
+-----------------------------+
|      project_measurements   |
+-----------------------------+
| id (PK)                     |
| timestamp                   |
| temperature                 |
| humidity                    |
| additional_type             |
| additional_value            |
| description                 |
+-----------------------------+
````
