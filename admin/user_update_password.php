<?php
require_once '../session.php';
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: user_list.php");
    exit;
}

$id = (int) $_GET['id'];
$meldung = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $neues_passwort = $_POST['password'];

    if (!empty($neues_passwort)) {
        $hash = password_hash($neues_passwort, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE project_users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$hash, $id]);

        header("Location: user_list.php");
        exit;
    } else {
        $meldung = "Bitte ein neues Passwort eingeben.";
    }
}

// Nutzer anzeigen
$stmt = $pdo->prepare("SELECT username FROM project_users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Passwort ändern</title>
    <style>
        body { font-family: Arial; padding: 30px; background: #f5f9ff; }
        form { background: #fff; padding: 20px; border-radius: 5px; width: 300px; }
        label, input { display: block; margin: 10px 0; width: 100%; }
        button { background: green; color: white; border: none; padding: 8px; cursor: pointer; }
        .back { margin-top: 15px; display: inline-block; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>

<h2>🔑 Passwort ändern für: <?= htmlspecialchars($user['username']) ?></h2>

<?php if ($meldung): ?>
    <p style="color: red;"><?= $meldung ?></p>
<?php endif; ?>

<form method="post">
    <label>Neues Passwort:</label>
    <input type="password" name="password" required>
    <button type="submit">Speichern</button>
</form>

<a class="back" href="user_list.php">⬅️ Zurück zur Übersicht</a>

</body>
</html>
