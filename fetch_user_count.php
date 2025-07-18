<?php
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode([]);
    exit();
}
// Get user count for the last 10 days
$data = [];
$labels = [];
for ($i = 9; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $labels[] = $date;
    $res = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE DATE(created_at) <= '$date'");
    $row = $res ? $res->fetch_assoc() : ['cnt' => 0];
    $data[] = (int)$row['cnt'];
}
$conn->close();
echo json_encode(['labels' => $labels, 'data' => $data]); 