<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>📡 Live-Diagramm</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background: linear-gradient(to right, #eef2ff, #f0f9ff);
      margin: 0;
      padding: 2rem;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      background: white;
      padding: 2rem;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    h2 {
      text-align: center;
      color: #4c1d95;
    }

    form {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 1rem;
    }

    select, input[type="date"], button {
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-family: inherit;
    }

    .timestamp-box {
      text-align: center;
      margin-top: 1rem;
      font-weight: bold;
      color: #666;
    }

    .btn-download {
      background: #9333ea;
      color: white;
      border: none;
      cursor: pointer;
    }

    .btn-download:hover {
      background: #7e22ce;
    }

    canvas {
      margin-top: 2rem;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>📈 Live-Messdaten mit Filter</h2>

  <form id="filterForm">
    <select name="type">
      <option value="">Alle Typen</option>
      <option value="CO2">CO2</option>
      <option value="Licht">Licht</option>
      <option value="Spannung">Spannung</option>
      <option value="Luftdruck">Luftdruck</option>
    </select>
    <input type="date" name="from">
    <input type="date" name="to">
    <button type="submit">🔍 Anwenden</button>
    <button class="btn-download" type="button" onclick="window.location.href='api/api_messwerte_export.php'">⬇️ CSV</button>
  </form>

  <canvas id="chart" height="100"></canvas>
  <div class="timestamp-box">Letztes Update: <span id="lastUpdate">–</span></div>
</div>

<script>
let chart;

const ctx = document.getElementById('chart').getContext('2d');
chart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: [],
    datasets: [
      {
        label: '🌡 Temperatur (°C)',
        data: [],
        borderColor: '#ef4444',
        backgroundColor: 'rgba(239,68,68,0.2)',
        tension: 0.4
      },
      {
        label: '💧 Feuchtigkeit (%)',
        data: [],
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59,130,246,0.2)',
        tension: 0.4
      },
      {
        label: '🔵 Luftdruck (hPa)',
        data: [],
        borderColor: '#10b981',
        backgroundColor: 'rgba(16,185,129,0.2)',
        tension: 0.4
      }
    ]
  },
  options: {
    responsive: true,
    animation: false,
    scales: {
      x: { title: { display: true, text: 'Zeit' }},
      y: { title: { display: true, text: 'Wert' }}
    }
  }
});

async function fetchData() {
  const form = document.getElementById("filterForm");
  const params = new URLSearchParams(new FormData(form));
  const res = await fetch("api/api_messwerte.php?" + params.toString());
  const data = await res.json();

  chart.data.labels = data.map(d => d.timestamp);
  chart.data.datasets[0].data = data.map(d => d.temperature);
  chart.data.datasets[1].data = data.map(d => d.humidity);
  chart.data.datasets[2].data = data.map(d => d.additional_value);

  document.getElementById("lastUpdate").textContent = new Date().toLocaleTimeString();
  chart.update();
}

document.getElementById("filterForm").addEventListener("submit", e => {
  e.preventDefault();
  fetchData();
});

fetchData();
setInterval(fetchData, 5000);
</script>
</body>
</html>
