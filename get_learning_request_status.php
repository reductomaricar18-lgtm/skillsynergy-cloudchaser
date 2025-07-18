<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit();
}

$chatUserId = isset($_GET['chat_user_id']) ? (int)$_GET['chat_user_id'] : 0;
$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

// Use either chat_user_id or user_id parameter
$targetUserId = $chatUserId ?: $userId;

if ($targetUserId === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid chat user ID']);
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Get current user ID from email
$currentUserId = null;
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$stmt->bind_result($currentUserId);
$stmt->fetch();
$stmt->close();

if (!$currentUserId) {
    echo json_encode(['status' => 'error', 'message' => 'Could not find current user']);
    $conn->close();
    exit();
}

// Find the latest learning request between the two users
$learningRequest = null;
$stmt = $conn->prepare("SELECT id, from_user_id, to_user_id, status, created_at FROM learning_requests WHERE (from_user_id = ? AND to_user_id = ?) OR (from_user_id = ? AND to_user_id = ?) ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("iiii", $currentUserId, $targetUserId, $targetUserId, $currentUserId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $learningRequest = $result->fetch_assoc();
    
    // Determine if current user is the recipient of the request
    $isRecipient = ($currentUserId == $learningRequest['to_user_id']);
    
    // Add additional information for better client-side handling
    $learningRequest['is_recipient'] = $isRecipient;
    $learningRequest['can_chat'] = ($learningRequest['status'] === 'accepted');
    
    echo json_encode([
        'status' => 'success', 
        'learning_request' => $learningRequest,
        'chat_enabled' => ($learningRequest['status'] === 'accepted')
    ]);
} else {
    // If no request exists, chat should be disabled for both users
    echo json_encode([
        'status' => 'success', 
        'learning_request' => null,
        'chat_enabled' => false,
        'message' => 'No learning request found. Chat is disabled until a learning request is sent and accepted.'
    ]);
}

$stmt->close();
$conn->close();
?>