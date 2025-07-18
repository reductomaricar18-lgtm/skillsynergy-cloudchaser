<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit();
}

$requestId = isset($_POST['request_id']) ? (int)$_POST['request_id'] : 0;

if ($requestId === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request ID']);
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

// Update the learning request status to 'accepted'
// IMPORTANT: We also verify that the current user is the recipient of the request
$stmt = $conn->prepare("UPDATE learning_requests SET status = 'accepted' WHERE id = ? AND to_user_id = ?");
$stmt->bind_param("ii", $requestId, $currentUserId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Learning request accepted.']);
    } else {
        // This means the request didn't exist or the user was not the recipient
        echo json_encode(['status' => 'error', 'message' => 'Could not accept request. You may not be the recipient.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to execute update.']);
}

$stmt->close();
$conn->close();
?>