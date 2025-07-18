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

// Fetch user ratings
$query = "
    SELECT 
        AVG((understanding_rating + knowledge_sharing_rating + listening_rating) / 3) as avg_rating,
        AVG(understanding_rating) as understanding_avg,
        AVG(knowledge_sharing_rating) as knowledge_avg,
        AVG(listening_rating) as listening_avg,
        COUNT(*) as total_ratings,
        MAX(feedback) as recent_feedback
    FROM user_ratings 
    WHERE rated_user_id = ?
    GROUP BY rated_user_id
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
    $rating = $result->fetch_assoc();
    echo json_encode([
        'status' => 'success', 
        'rating' => [
            'avg_rating' => floatval($rating['avg_rating']),
            'understanding_avg' => floatval($rating['understanding_avg']),
            'knowledge_avg' => floatval($rating['knowledge_avg']),
            'listening_avg' => floatval($rating['listening_avg']),
            'total_ratings' => intval($rating['total_ratings']),
            'recent_feedback' => $rating['recent_feedback']
        ]
    ]);
} else {
    echo json_encode([
        'status' => 'success', 
        'rating' => null
    ]);
}

$stmt->close();
$conn->close();
?>
