<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Admin access check
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login_page.php");
    exit();
}

// --- Quick Stats ---
$total_users = 0;
$active_today = 0;
$new_this_week = 0;
$pending_approvals = 0; // No approval system implemented

// New users today
$res = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE DATE(last_login) = CURDATE()");
if ($res) { $row = $res->fetch_assoc(); $active_today = (int)$row['cnt']; }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - SkillSynergy</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;400&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', Arial, sans-serif;
            background: linear-gradient(120deg, #e0f7fa 0%, #b2ebf2 100%);
            min-height: 100vh;
            background-image: url('SSbg.jpg'), linear-gradient(120deg, #e0f7fa 0%, #b2ebf2 100%);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            overflow: hidden;
        }
        .dashboard-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            width: 220px;
            background: rgba(255,255,255,0.7);
            box-shadow: 2px 0 16px rgba(0,0,0,0.07);
            border-radius: 30px;
            margin: 24px 0 24px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px 30px 20px;
            position: relative;
        }
        .sidebar .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 40px;
            width: 100%;
        }
        .sidebar .logo-img {
            width: 400px;
            height: 140px;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: block;
            margin: 0 auto;
        }
        .sidebar .logo-text {
            font-size: 2.2rem;
            font-weight: 700;
            color: #222;
            letter-spacing: 1px;
            line-height: 1.1;
        }
        .sidebar .greeting {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 30px;
            color: #222;
        }
        .sidebar .side-btn {
            width: 100%;
            padding: 14px 0;
            margin-bottom: 18px;
            background: #00bcd4;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 2px 8px rgba(0,188,212,0.08);
            gap: 10px;
        }
        .sidebar .side-btn:hover {
            background: #0097a7;
        }
        .sidebar .logout {
            margin-top: auto;
            font-style: italic;
            font-size: 1.1rem;
            color: #222;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }
        .main-content {
            flex: 1;
            margin: 24px 24px 24px 0;
            display: flex;
            flex-direction: column;
        }
        .top-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 18px;
        }
        .top-bar .date, .top-bar .time {
            font-size: 1.2rem;
            font-weight: 700;
            margin-left: 30px;
            color: #222;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: 220px 1fr;
            gap: 24px;
            height: 100%;
            margin-left:20px;
        }
        .card {
            background: rgba(255,255,255,0.85);
            border-radius: 22px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            padding: 28px 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            
        }
        .card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 18px;
            color: #222;
            
        }
        .skills-ranking {
            grid-column: 1 / span 3;
            min-height: 320px;
            display: flex;
            
            position: relative;
        }
        .skills-ranking-canvas {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            min-height: 340px;
            margin-top: 10px;
            gap: 0;
            position: relative;
        }
        .skills-ranking-row {
            flex-direction: row;
        }
        .skills-pie-legend {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            font-size: 1.1rem;
            margin-right: 100px;
            text-align: left;
            min-width: 80px;
        }
        /* Reduce the gap between legend and chart */
        .skills-ranking-canvas .chartjs-legend {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
        .skills-ranking-canvas ul {
            margin: 0 8px 0 0 !important;
            padding: 0 !important;
        }
        .skills-ranking-canvas ul li {
            margin-bottom: 6px !important;
        }
        .skills-ranking-canvas .skills-pie-canvas {
            display: block;
            margin: 0 auto;
            width: 340px;
            height: 340px;
        }
        .skills-pie-canvas {
            width: 340px;
            height: 340px;
            max-width: 100%;
            max-height: 100%;
        }
        .dashboard-grid-3col {
          display: grid;
          grid-template-columns: 1.1fr 1fr 1fr;
          align-items: center;
          gap: 8px;
          width: 100%;
          padding: 18px 0;
          box-sizing: border-box;
        }
        .skills-pie-legend {
          display: grid;
          grid-template-columns: repeat(3, 1fr);
          gap: 8px 12px;
          margin-top: 0;
          margin-right: 10px;
        }
        .card.skills-ranking {
          margin-left: 15x;
          width: 90;

        }
        /* Responsive */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: 220px 1fr 1fr;
            }
            .skills-ranking {
                grid-column: 1 / span 2;
            }
        }
        @media (max-width: 900px) {
            .dashboard-container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                margin: 0 0 24px 0;
                border-radius: 0 0 30px 30px;
                
            }
            .main-content {
                margin: 0 12px 12px 12px;
            }
            .dashboard-grid {
                grid-template-columns: 1fr;
                grid-template-rows: repeat(5, auto);
            }
            .skills-ranking {
                grid-column: 1;
            }
            .skills-ranking-canvas {
                min-height: 200px;
                gap: 12px;
            }
            .skills-pie-canvas {
                width: 200px !important;
                height: 200px !important;
            }
        }
        @media (max-width: 600px) {
            .skills-ranking-canvas {
                min-height: 120px;
            }
            .skills-pie-canvas {
                width: 120px !important;
                height: 120px !important;
            }
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar">
        <div class="logo">
            <img src="S6.jpg" alt="Skill Synergy Logo" class="logo-img" onerror="this.style.display='none'">
        </div>
        <div class="greeting">Hello, Admin!</div>
        <button class="side-btn" onclick="window.location.href='admin_manage_accounts.php'">Manage Accounts</button>
        <button class="side-btn" onclick="window.location.href='admin_manage_skills.php'">Manage Skills & Categories</button>
        <button class="side-btn" onclick="window.location.href='admin_login_page.php'">Log out</button>
    </div>
    <div class="main-content">
        <div class="top-bar">
            <span class="date" id="current-date">Date</span>
            <span class="time" id="current-time">Time</span>
        </div>
        <div class="dashboard-grid">
            <div class="card">
                <h3>Quick Stats</h3>
                <div style="font-size:1.1rem;margin-bottom:8px;">Total Users: <strong id="stat-total-users"><?= $total_users ?></strong></div>
                <div style="font-size:1.1rem;margin-bottom:8px;">New Users Today: <strong id="stat-active-today"><?= $active_today ?></strong></div>
                <div style="font-size:1.1rem;">New This Week: <strong id="stat-new-this-week"><?= $new_this_week ?></strong></div>
            </div>
            <div class="card">
                <h3>Top Tutor</h3>
                <!-- Top Tutor Horizontal Bar Chart -->
                <canvas id="topTutorChart" height="120"></canvas>
            </div>
            <div class="card">
                <h3 style="font-style:italic;">User Count</h3>
                <!-- User Count Line Chart -->
                <canvas id="userCountChart" height="120"></canvas>
            </div>
            <div class="card skills-ranking">
                <h3 style="text-align:left; margin-left:0;">Skills Ranking</h3>
                <div class="dashboard-grid-3col">
                    <div class="skills-pie-legend" id="skillsPieLegend"></div>
                    <div class="skills-pie-center">
                        <canvas id="skillsPieChart" class="skills-pie-canvas" width="340" height="340"></canvas>
                    </div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Date and Time
function updateDateTime() {
    const now = new Date();
    document.getElementById('current-date').textContent = now.toLocaleDateString();
    document.getElementById('current-time').textContent = now.toLocaleTimeString();
}
setInterval(updateDateTime, 1000);
updateDateTime();

// --- Real-time Pie Chart for Skills Ranking ---
const skillsPieColors = ['rgba(230, 23, 185, 0.1)', 'rgba(81, 6, 243, 0.1)', 'rgba(175, 200, 32, 0.1)', 'rgba(58, 192, 225, 0.1)', 'rgba(30, 212, 58, 0.1)', 'rgba(221, 20, 20, 0.1)', 'rgba(255, 193, 7, 0.1)', 'rgba(0, 188, 212, 0.1)', 'rgba(255, 87, 34, 0.1)', 'rgba(76, 175, 80, 0.1)'];
const skillsPieCtx = document.getElementById('skillsPieChart').getContext('2d');
let skillsPieChart = new Chart(skillsPieCtx, {
    type: 'pie',
    data: {
        labels: [], // will be set dynamically
        datasets: [{
            data: [],
            backgroundColor: skillsPieColors,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
    }
});
// Fetch most offered skills and update the pie chart
fetch('fetch_skill_points.php')
  .then(response => response.json())
  .then(data => {
    const skillsPieLabels = data.map(item => item.skill);
    const skillsPieData = data.map(item => item.points);
    skillsPieChart.data.labels = skillsPieLabels;
    skillsPieChart.data.datasets[0].data = skillsPieData;
    skillsPieChart.update();
    // Update the legend
    renderSkillsPieLegend(skillsPieLabels, skillsPieColors);
  });

function renderSkillsPieLegend(labels, colors) {
  const legendContainer = document.getElementById('skillsPieLegend');
  if (!legendContainer) return;
  legendContainer.innerHTML = labels.map((label, i) => `
    <div style="display:flex;align-items:center;margin-bottom:8px;text-align:left;">
      <span style="display:inline-block;width:18px;height:18px;background:${colors[i % colors.length]};border-radius:3px;margin-right:10px;"></span>
      <span style="min-width:50px;text-align:left;font-size:1rem;">${label}</span>
    </div>
  `).join('');
}

// --- Real-time Line Chart for User Count (AJAX) ---
let userCountChart;
function fetchUserCountAndUpdateChart() {
    fetch('fetch_user_logins.php')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(row => row.date); // X-axis: login dates
            const counts = data.map(row => row.count); // Y-axis: login counts
            if (!userCountChart) {
                const ctx = document.getElementById('userCountChart').getContext('2d');
                userCountChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'User Logins',
                            data: counts,
                            borderColor: 'rgba(45, 206, 238, 0.7)',
                            backgroundColor: 'rgba(197, 15, 118, 0.1)',
                            fill: true,
                            tension: 0.3,
                        }]
                    },
                    options: {
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { title: { display: true, text: 'Login Date' } },
                            y: { beginAtZero: true, title: { display: true, text: 'Logins' } }
                        }
                    }
                });
            } else {
                userCountChart.data.labels = labels;
                userCountChart.data.datasets[0].data = counts;
                userCountChart.update();
            }
        });
}
fetchUserCountAndUpdateChart();
setInterval(fetchUserCountAndUpdateChart, 5000);

// --- Real-time Top Tutor Bar Chart (AJAX) ---
let topTutorChart;
function fetchTopTutorsAndUpdateChart() {
    fetch('fetch_top_tutors.php')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(t => t.name);
            const ratings = data.map(t => t.avg_rating);
            if (!topTutorChart) {
                const ctx = document.getElementById('topTutorChart').getContext('2d');
                topTutorChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Average Rating',
                            data: ratings,
                            backgroundColor: 'rgba(6, 15, 150, 0.1)',
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { beginAtZero: true, max: 5 }
                        }
                    }
                });
            } else {
                topTutorChart.data.labels = labels;
                topTutorChart.data.datasets[0].data = ratings;
                topTutorChart.update();
            }
        });
}
fetchTopTutorsAndUpdateChart();
setInterval(fetchTopTutorsAndUpdateChart, 5000);

// --- Real-time Quick Stats (AJAX) ---
function fetchQuickStatsAndUpdate() {
    fetch('fetch_quick_stats.php')
        .then(response => response.json())
        .then(data => {
            if (data.total_users !== undefined) document.getElementById('stat-total-users').textContent = data.total_users;
            if (data.active_today !== undefined) document.getElementById('stat-active-today').textContent = data.active_today;
            if (data.new_this_week !== undefined) document.getElementById('stat-new-this-week').textContent = data.new_this_week;
        });
}
fetchQuickStatsAndUpdate();
setInterval(fetchQuickStatsAndUpdate, 5000);

// --- Simulate Real-time Updates (for demo) ---
setInterval(() => {
    // Pie chart: randomize data
    skillsPieChart.data.datasets[0].data = skillsPieChart.data.datasets[0].data.map(v => Math.max(100, v + Math.floor(Math.random()*200-100)));
    skillsPieChart.update();
    // Line chart: shift and add new value
    // userCountChart.data.datasets[0].data.shift(); // This line is now handled by AJAX
    // userCountChart.data.datasets[0].data.push(userCountChart.data.datasets[0].data[userCountChart.data.datasets[0].data.length-1] + Math.floor(Math.random()*20-10)); // This line is now handled by AJAX
    // userCountChart.update();
    // Horizontal bar: randomize data
    // topTutorChart.data.datasets[0].data = topTutorChart.data.datasets[0].data.map(v => Math.max(1, v + Math.floor(Math.random()*3-1))); // This line is now handled by AJAX
    // topTutorChart.update();
}, 2000);
</script>
</body>
</html>
