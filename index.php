<?php
require_once 'session.php';
require_once 'config/db.php';

// Userstatus prüfen
$isLoggedIn = ist_eingeloggt();
$username = $_SESSION['username'] ?? 'Gast';
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>🌤 Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Quicksand', sans-serif;
      background: url('assets/img/bg_dashboard.jpg') no-repeat center center fixed;
      background-size: cover;
      padding: 2rem;
    }

    .container {
      max-width: 1100px;
      margin: auto;
      background: rgba(255, 255, 255, 0.92);
      padding: 2.5rem;
      border-radius: 25px;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(3px);
    }

    h1 {
      text-align: center;
      font-size: 2.4rem;
      color: #4c1d95;
      margin-bottom: 0.5rem;
    }

    .welcome {
      text-align: center;
      font-size: 1.1rem;
      margin-bottom: 2rem;
    }

    .icon-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 1.5rem;
      justify-items: center;
    }

    .circle-card {
      background: #ede9fe;
      border-radius: 20px;
      padding: 1.2rem 1rem;
      text-align: center;
      color: #4c1d95;
      width: 140px;
      min-height: 160px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.06);
      transition: all 0.2s ease;
      text-decoration: none;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;

      /* Animation init */
      opacity: 0;
      animation: fadeInUp 0.6s ease-out forwards;
    }

    /* Wetter-Icons */
    .circle-card img {
      width: 50px;
      height: 50px;
      margin-bottom: 0.8rem;
    }

    .circle-card span {
      font-size: 0.95rem;
      font-weight: bold;
    }

    .circle-card:hover {
      background: #d8b4fe;
      transform: scale(1.05);
    }

    /* Animation */
    @keyframes fadeInUp {
      0% {
        transform: translateY(20px);
        opacity: 0;
      }
      100% {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .circle-card:nth-child(1) { animation-delay: 0.1s; }
    .circle-card:nth-child(2) { animation-delay: 0.2s; }
    .circle-card:nth-child(3) { animation-delay: 0.3s; }
    .circle-card:nth-child(4) { animation-delay: 0.4s; }
    .circle-card:nth-child(5) { animation-delay: 0.5s; }
    .circle-card:nth-child(6) { animation-delay: 0.6s; }
    .circle-card:nth-child(7) { animation-delay: 0.7s; }
    .circle-card:nth-child(8) { animation-delay: 0.8s; }

    .footer {
      text-align: center;
      margin-top: 2rem;
      font-size: 0.85rem;
      color: #666;
    }
  </style>
</head>
<body>

<div class="container">
  <h1>🌡️ PHP Projekt – Messdatenerfassung</h1>
  <p class="welcome">Willkommen, <strong><?= htmlspecialchars($username) ?></strong> 👋</p>

<div class="icon-grid">
  <a href="diagramm.php" class="circle-card">
    <img src="assets/icons/sun-chart.svg" alt="Diagramm">
    <span>Diagramm</span>
  </a>

  <a href="live_diagramm.php" class="circle-card">
    <img src="assets/icons/live.svg" alt="Live Diagramm">
    <span>Live</span>
  </a>

  <a href="import.php" class="circle-card">
    <img src="assets/icons/cloud-upload.svg" alt="Import">
    <span>Import</span>
  </a>

  <a href="export.php" class="circle-card">
    <img src="assets/icons/cloud-download.svg" alt="Export">
    <span>Export</span>
  </a>

  <a href="messwerte.php" class="circle-card">
    <img src="assets/icons/wind-data.svg" alt="Messwerte">
    <span>Messwerte</span>
  </a>

  <?php if ($isLoggedIn): ?>
  <a href="add_measurement.php" class="circle-card">
    <img src="assets/icons/thermo-plus.svg" alt="Hinzufügen">
    <span>Hinzufügen</span>
  </a>

  <a href="delete_measurement.php" class="circle-card">
    <img src="assets/icons/rain-trash.svg" alt="Löschen">
    <span>Löschen</span>
  </a>

  <a href="admin/user_list.php" class="circle-card">
    <img src="assets/icons/user-settings.svg" alt="Benutzer">
    <span>Benutzer</span>
  </a>
<?php endif; ?>

  <a href="start.php" class="circle-card">
    <img src="assets/icons/start.svg" alt="Startscreen">
    <span>Startscreen</span>
  </a>

  <?php if ($isLoggedIn): ?>
    <a href="logout.php" class="circle-card">
      <img src="assets/icons/sunset-logout.svg" alt="Logout">
      <span>Logout</span>
    </a>
  <?php else: ?>
    <a href="login.php" class="circle-card">
      <img src="assets/icons/lock-cloud.svg" alt="Login">
      <span>Login</span>
    </a>
  <?php endif; ?>
</div>

  <div class="footer">PHP Projekt – Messdatenerfassung 2025 - Anna Tetzlaff</div>
</div>

</body>
</html>
