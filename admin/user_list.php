<?php
require_once '../session.php';
require_once '../config/db.php';

// Zugang nur für eingeloggte Benutzer
if (!ist_eingeloggt()) {
    header('Location: ../login.php');
    exit;
}

// Alle Benutzer laden (sortiert)
$stmt = $pdo->query("SELECT * FROM project_users ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>👥 Benutzerverwaltung</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Quicksand', sans-serif;
      background: url('assets/img/bg_dashboard.jpg') no-repeat center center fixed;
      background: #f0f4ff;
      padding: 2rem;
    }

    .container {
      max-width: 900px;
      margin: auto;
      background: white;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #4c1d95;
    }

    .btn {
      background: #ede9fe;
      color: #4c1d95;
      padding: 8px 16px;
      text-decoration: none;
      border-radius: 20px;
      font-weight: bold;
      transition: 0.2s;
      display: inline-block;
    }

    .btn:hover {
      background: #dcd0ff;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background: #f3e8ff;
      color: #4c1d95;
    }

    tr:nth-child(even) {
      background-color: #faf5ff;
    }

    .top-buttons {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .back-link {
      color: #6b21a8;
      text-decoration: none;
      font-weight: bold;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="top-buttons">
    <a href="../index.php" class="back-link">⬅️ Zur Startseite</a>
    <a class="btn" href="user_create.php">➕ Benutzer hinzufügen</a>
  </div>

  <h2>👥 Benutzerverwaltung</h2>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Benutzername</th>
        <th>Aktionen</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
      <tr>
        <td><?= $user['id'] ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td>
          <a class="btn" href="user_update_password.php?id=<?= $user['id'] ?>">🔑 Passwort</a>
          <?php if ($user['id'] !== 1): ?>
            <a class="btn" href="user_delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Benutzer wirklich löschen?')">🗑️ Löschen</a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
