<?php
require_once '../session.php';
require_once '../config/db.php';

$meldung = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $passwort = $_POST['password'];

    if ($username && $passwort) {
        $hash = password_hash($passwort, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO project_users (username, password_hash) VALUES (?, ?)");
        if ($stmt->execute([$username, $hash])) {
            header('Location: user_list.php');
            exit;
        } else {
            $meldung = "⚠️ Benutzername bereits vergeben oder Fehler!";
        }
    } else {
        $meldung = "Bitte alle Felder ausfüllen.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Benutzer erstellen</title>
    <style>
        body { font-family: Arial; padding: 30px; background: #f5f9ff; }
        form { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 8px rgba(0,0,0,0.1); width: 300px; }
        label { display: block; margin: 10px 0 5px; }
        input { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { background: green; color: white; padding: 8px 12px; border: none; cursor: pointer; }
        .back { margin-top: 15px; display: inline-block; color: #007bff; text-decoration: none; }
        .back:hover { text-decoration: underline; }
    </style>
</head>
<body>

<h2>➕ Benutzer erstellen</h2>

<?php if ($meldung): ?>
<p style="color: red;"><?= $meldung ?></p>
<?php endif; ?>

<form method="post">
    <label>Benutzername:</label>
    <input type="text" name="username" required>

    <label>Passwort:</label>
    <input type="password" name="password" required>

    <button type="submit">Erstellen</button>
</form>

<a class="back" href="user_list.php">⬅️ Zurück zur Übersicht</a>

</body>
</html>
