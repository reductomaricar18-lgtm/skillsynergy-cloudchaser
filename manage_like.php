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
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// ✅ Validate POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'invalid_method']);
    exit();
}

$targetUserId = $_POST['targetUserId'] ?? null;
$actionType   = $_POST['actionType'] ?? null;

if (!$targetUserId || !$actionType || !is_numeric($targetUserId)) {
    echo json_encode(['status' => 'invalid_request']);
    exit();
}

// ✅ Helper: check if like exists
function hasLike($conn, $from, $to) {
    $stmt = $conn->prepare("SELECT 1 FROM user_likes WHERE user_id = ? AND liked_user_id = ? AND action = 'like'");
    $stmt->bind_param("ii", $from, $to);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

// ✅ Handle actions cleanly
switch ($actionType) {

    case 'add_like':
        // Add like if not already liked
        if (!hasLike($conn, $user_id, $targetUserId)) {
            $stmt = $conn->prepare("INSERT INTO user_likes (user_id, liked_user_id, action) VALUES (?, ?, 'like')");
            $stmt->bind_param("ii", $user_id, $targetUserId);
            if ($stmt->execute()) {
                // Check for mutual like
                if (hasLike($conn, $targetUserId, $user_id)) {
                    echo json_encode(['status' => 'match']);
                } else {
                    echo json_encode(['status' => 'like_recorded']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'already_liked']);
        }
        break;

    case 'remove_from_me':
        // Remove my like to someone
        $stmt = $conn->prepare("DELETE FROM user_likes WHERE user_id = ? AND liked_user_id = ?");
        $stmt->bind_param("ii", $user_id, $targetUserId);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'removed']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        }
        $stmt->close();
        break;

    case 'remove_to_me':
        // Remove their like to me
        $stmt = $conn->prepare("DELETE FROM user_likes WHERE user_id = ? AND liked_user_id = ?");
        $stmt->bind_param("ii", $targetUserId, $user_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'removed']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        }
        $stmt->close();
        break;

    case 'confirm_to_me':
        // Like them back if not already
        if (!hasLike($conn, $user_id, $targetUserId)) {
            $insert = $conn->prepare("INSERT INTO user_likes (user_id, liked_user_id, action) VALUES (?, ?, 'like')");
            $insert->bind_param("ii", $user_id, $targetUserId);
            $insert->execute();
            $insert->close();
        }

        // Now check for mutual match (no deletions!)
        if (hasLike($conn, $targetUserId, $user_id)) {
            // MATCH achieved
            echo json_encode(['status' => 'match']);
        } else {
            // No mutual, just confirm
            echo json_encode(['status' => 'confirm']);
        }
        break;

    case 'remove_match':
        // Remove both sides of a match relationship
        $stmt = $conn->prepare("
            DELETE FROM user_likes
            WHERE (user_id = ? AND liked_user_id = ?)
               OR (user_id = ? AND liked_user_id = ?)
        ");
        $stmt->bind_param("iiii", $user_id, $targetUserId, $targetUserId, $user_id);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        if ($affectedRows > 0) {
            echo json_encode(['status' => 'removed']);
        } else {
            echo json_encode(['status' => 'nothing_removed']);
        }
        break;

    default:
        echo json_encode(['status' => 'invalid_action']);
}

$conn->close();
?>
