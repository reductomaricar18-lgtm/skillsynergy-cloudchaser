<?php
session_start();
header('Content-Type: application/json');

// DEBUG: Show all errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Get the user ID from the request
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID']);
    exit;
}

// Check if user has assessment results
$query = "SELECT COUNT(*) as count FROM assessment_results WHERE rated_user_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$has_assessment = $count > 0;

// Output JSON
echo json_encode([
    'status' => 'success',
    'has_assessment' => $has_assessment
]);

$stmt->close();
$conn->close();
?>
