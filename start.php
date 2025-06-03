<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>☁️ PHP Projekt – Messdatenerfassung</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      height: 100vh;
      font-family: 'Quicksand', sans-serif;
      background: url('assets/img/bg_login.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .start-box {
      background: rgba(255, 255, 255, 0.95);
      padding: 3rem 2rem;
      border-radius: 25px;
      max-width: 480px;
      width: 90%;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
      text-align: center;
    }

    h1 {
      font-size: 2rem;
      color: #4c1d95;
      margin-bottom: 1.2rem;
    }

    p {
      font-size: 1rem;
      margin-bottom: 2rem;
      color: #555;
    }

    .btn {
      display: block;
      width: 100%;
      margin: 0.6rem 0;
      padding: 12px;
      background-color: #9333ea;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 1rem;
      transition: background 0.2s ease;
    }

    .btn:hover {
      background-color: #7e22ce;
    }
  </style>
</head>
<body>

<div class="start-box">
  <h1>☁️ Willkommen im PHP Projekt – Messdatenerfassung</h1>
  <p>Bitte wähle deine Einstiegsmethode:</p>
  <form method="get">
    <button class="btn" formaction="login.php">🔐 Login</button>
    <button class="btn" formaction="index.php">👀 Als Gast fortfahren</button>
  </form>
</div>

</body>
</html>
