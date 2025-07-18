<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// Get current user ID
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($currentUserId);
$stmt->fetch();
$stmt->close();

if (!$currentUserId) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit();
}

// Get receiver ID from POST data
$receiver_id = $_POST['receiver_id'] ?? null;
if (!$receiver_id) {
    echo json_encode(['success' => false, 'message' => 'Receiver ID not provided']);
    exit();
}

// Check if a session message already exists between these users
$check_stmt = $conn->prepare("
    SELECT COUNT(*) as count 
    FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) 
       OR (sender_id = ? AND receiver_id = ?)
");
$check_stmt->bind_param("iiii", $currentUserId, $receiver_id, $receiver_id, $currentUserId);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$check_row = $check_result->fetch_assoc();
$check_stmt->close();

if ($check_row['count'] > 0) {
    echo json_encode(['success' => true, 'message' => 'Session already exists']);
    exit();
}

// Create the session message
$session_message = "🚀 Session started";
$current_time = date('Y-m-d H:i:s');

$insert_stmt = $conn->prepare("
    INSERT INTO messages (sender_id, receiver_id, message, timestamp) 
    VALUES (?, ?, ?, ?)
");
$insert_stmt->bind_param("iiss", $currentUserId, $receiver_id, $session_message, $current_time);

if ($insert_stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Session started successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create session']);
}

$insert_stmt->close();
$conn->close(); 