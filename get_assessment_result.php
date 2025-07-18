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

// Get the user ID from the request
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID']);
    exit;
}

// Fetch user's latest assessment result
$query = "
    SELECT score, max_score, percentage, created_at, answers
    FROM assessment_results 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 1
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed']);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $assessment = $result->fetch_assoc();
    echo json_encode([
        'status' => 'success', 
        'assessment' => [
            'score' => intval($assessment['score']),
            'max_score' => intval($assessment['max_score']),
            'percentage' => intval($assessment['percentage']),
            'created_at' => $assessment['created_at'],
            'answers' => $assessment['answers']
        ]
    ]);
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'No assessment found'
    ]);
}

$stmt->close();
$conn->close();
?>
