<?php
session_start();

// Disable error display to prevent HTML in JSON response
error_reporting(0);
ini_set('display_errors', 0);

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
$stmt->bind_result($rater_id);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rated_user_id = $_POST['rated_user_id'] ?? null;
    $understanding_rating = $_POST['understanding_rating'] ?? null;
    $knowledge_sharing_rating = $_POST['knowledge_sharing_rating'] ?? null;
    $listening_rating = $_POST['listening_rating'] ?? null;
    $feedback = $_POST['feedback'] ?? '';

    if (!$rated_user_id || !$understanding_rating || !$knowledge_sharing_rating || !$listening_rating) {
        echo json_encode(['status' => 'error', 'message' => 'All ratings are required']);
        exit();
    }

    // Check if rating already exists
    $check_stmt = $conn->prepare("SELECT rating_id FROM user_ratings WHERE rater_id = ? AND rated_user_id = ?");
    $check_stmt->bind_param("ii", $rater_id, $rated_user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Update existing rating
        $update_stmt = $conn->prepare("
            UPDATE user_ratings 
            SET understanding_rating = ?, knowledge_sharing_rating = ?, listening_rating = ?, feedback = ?, updated_at = CURRENT_TIMESTAMP
            WHERE rater_id = ? AND rated_user_id = ?
        ");
        $update_stmt->bind_param("iiiiii", $understanding_rating, $knowledge_sharing_rating, $listening_rating, $feedback, $rater_id, $rated_user_id);
        
        if ($update_stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Rating updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update rating']);
        }
        $update_stmt->close();
    } else {
        // Insert new rating
        $insert_stmt = $conn->prepare("
            INSERT INTO user_ratings (rater_id, rated_user_id, understanding_rating, knowledge_sharing_rating, listening_rating, feedback) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $insert_stmt->bind_param("iiiiss", $rater_id, $rated_user_id, $understanding_rating, $knowledge_sharing_rating, $listening_rating, $feedback);
        
        if ($insert_stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Rating saved successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save rating']);
        }
        $insert_stmt->close();
    }
    
    $check_stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>
