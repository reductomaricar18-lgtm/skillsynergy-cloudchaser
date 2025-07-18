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
$stmt->bind_result($from_user_id);
$stmt->fetch();
$stmt->close();

$to_user_id = $_POST['to_user_id'] ?? null;
$skill = $_POST['skill'] ?? null;
$proficiency = $_POST['proficiency'] ?? null;

if (!$to_user_id || !$skill || !$proficiency) {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
    exit();
}

// Check for existing pending request
$check = $conn->prepare("SELECT id FROM learning_requests WHERE from_user_id = ? AND to_user_id = ? AND skill = ? AND proficiency = ? AND status = 'pending'");
$check->bind_param("iiss", $from_user_id, $to_user_id, $skill, $proficiency);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo json_encode(['status' => 'exists', 'message' => 'Request already pending']);
    $check->close();
    $conn->close();
    exit();
}
$check->close();

$stmt = $conn->prepare("INSERT INTO learning_requests (from_user_id, to_user_id, skill, proficiency) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $from_user_id, $to_user_id, $skill, $proficiency);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Request sent']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to send request']);
}
$stmt->close();
$conn->close(); 