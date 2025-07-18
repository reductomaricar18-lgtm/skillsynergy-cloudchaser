<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($to_user_id);
$stmt->fetch();
$stmt->close();

$query = "SELECT lr.id, lr.from_user_id, up.first_name, up.last_name, up.profile_pic, lr.skill, lr.proficiency, lr.created_at FROM learning_requests lr JOIN users_profile up ON lr.from_user_id = up.user_id WHERE lr.to_user_id = ? AND lr.status = 'pending' ORDER BY lr.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $to_user_id);
$stmt->execute();
$result = $stmt->get_result();
$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}
$stmt->close();
$conn->close();
echo json_encode(['status' => 'success', 'requests' => $requests]); 