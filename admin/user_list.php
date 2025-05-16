<?php
require_once '../session.php';
require_once '../config/db.php';

$stmt = $pdo->query("SELECT * FROM project_users ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Benutzerverwaltung</title>
    <style>
        body { font-family: Arial; padding: 30px; background: #f5f9ff; }
        table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #dbeeff; }
        a.btn { padding: 6px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        a.btn:hover { background: #0056b3; }
    </style>
</head>
<body>

<h2>👥 Benutzerverwaltung</h2>

<p><a class="btn" href="user_create.php">➕ Benutzer hinzufügen</a></p>

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
                <a class="btn" href="user_update_password.php?id=<?= $user['id'] ?>">🔑 Passwort ändern</a>
                <a class="btn" href="user_delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Benutzer wirklich löschen?')">🗑️ Löschen</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a class="btn" href="../index.php">⬅️ Zurück</a></p>

</body>
</html>
