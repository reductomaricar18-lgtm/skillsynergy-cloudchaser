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
$session_id = $_POST['session_id'] ?? null;
if (!$session_id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing session_id']);
    exit();
}
$stmt = $conn->prepare("UPDATE sessions SET accepted = 1, status = 'active' WHERE session_id = ?");
$stmt->bind_param("i", $session_id);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to accept session']);
}
$stmt->close();
$conn->close(); 