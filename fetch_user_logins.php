<?php
// Connect to DB
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}
// Query: group logins by date from users table using login_time
$sql = "SELECT DATE(login_time) as date, COUNT(*) as count FROM users WHERE login_time IS NOT NULL GROUP BY DATE(login_time) ORDER BY DATE(login_time) DESC LIMIT 20";
$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
// Return oldest first
echo json_encode(array_reverse($data));
?>