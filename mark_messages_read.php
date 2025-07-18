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

// Get current user ID
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($currentUserId);
$stmt->fetch();
$stmt->close();

if (!$currentUserId) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_POST['sender_id'] ?? null;
    
    if (!$sender_id) {
        echo json_encode(['status' => 'error', 'message' => 'Sender ID required']);
        exit();
    }
    
    // Mark messages as read for this sender
    $updateStmt = $conn->prepare("
        UPDATE messages 
        SET is_read = TRUE 
        WHERE receiver_id = ? AND sender_id = ? AND is_read = FALSE
    ");
    $updateStmt->bind_param("ii", $currentUserId, $sender_id);
    
    if ($updateStmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Messages marked as read']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to mark messages as read']);
    }
    
    $updateStmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?> 