<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Get current user ID
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$stmt->bind_result($currentUserId);
$stmt->fetch();
$stmt->close();

// Get the rated user ID from the request
$rated_user_id = isset($_GET['rated_user_id']) ? intval($_GET['rated_user_id']) : 0;

if ($rated_user_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID']);
    exit;
}

// Check if current user has already rated the other user
$query = "SELECT COUNT(*) as count FROM user_ratings WHERE rater_id = ? AND rated_user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $currentUserId, $rated_user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$has_rated = $row['count'] > 0;

echo json_encode([
    'status' => 'success',
    'has_rated' => $has_rated
]);

$stmt->close();
$conn->close();
?>
