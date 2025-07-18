<?php
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed.']);
    exit();
}

// Total users
$total_users = 0;
$res = $conn->query("SELECT COUNT(*) as cnt FROM users");
if ($res) {
    $row = $res->fetch_assoc();
    $total_users = (int)$row['cnt'];
}

// New users today (active today) - use login_time
$active_today = 0;
$res = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE DATE(login_time) = CURDATE()");
if ($res) {
    $row = $res->fetch_assoc();
    $active_today = (int)$row['cnt'];
}

// New this week - use login_time
$new_this_week = 0;
$res = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE YEARWEEK(login_time, 1) = YEARWEEK(CURDATE(), 1)");
if ($res) {
    $row = $res->fetch_assoc();
    $new_this_week = (int)$row['cnt'];
}

$conn->close();
echo json_encode([
    'total_users' => $total_users,
    'active_today' => $active_today,
    'new_this_week' => $new_this_week
]); 