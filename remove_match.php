<?php
session_start();
header('Content-Type: application/json');

// ✅ Validate session
if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'unauthorized']);
    exit();
}

// ✅ Connect to database
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode(['status' => 'db_connection_failed']);
    exit();
}

$email = $_SESSION['email'];

// ✅ Get current user ID
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($currentUserId);
$stmt->fetch();
$stmt->close();

if (!$currentUserId) {
    echo json_encode(['status' => 'user_not_found']);
    $conn->close();
    exit();
}

// ✅ Validate target user ID
if (!isset($_POST['targetUserId']) || !is_numeric($_POST['targetUserId'])) {
    echo json_encode(['status' => 'missing_or_invalid_target']);
    $conn->close();
    exit();
}

$targetUserId = intval($_POST['targetUserId']);

// ✅ Delete mutual like records (remove both sides of the match)
$stmt = $conn->prepare("
    DELETE FROM user_likes
    WHERE (user_id = ? AND liked_user_id = ?)
       OR (user_id = ? AND liked_user_id = ?)
");
$stmt->bind_param("iiii", $currentUserId, $targetUserId, $targetUserId, $currentUserId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'removed']);
    } else {
        echo json_encode(['status' => 'no_match_found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
