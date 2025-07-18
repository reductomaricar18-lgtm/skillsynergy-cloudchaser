<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'unauthorized']);
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode(['status' => 'db_connection_failed']);
    exit();
}

$email = $_SESSION['email'];

// Fetch current user ID
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

if (
    !isset($_POST['to_user_id']) || !is_numeric($_POST['to_user_id']) ||
    empty($_POST['skill']) || empty($_POST['proficiency'])
) {
    echo json_encode(['status' => 'invalid_input']);
    $conn->close();
    exit();
}

$toUserId = intval($_POST['to_user_id']);
$skill = trim($_POST['skill']);
$proficiency = trim($_POST['proficiency']);

// Prevent sending a message if the same skill session already exists
$checkStmt = $conn->prepare("SELECT 1 FROM messages WHERE sender_id = ? AND receiver_id = ? AND message_text LIKE CONCAT('%', ?, '%') AND is_deleted = 0 LIMIT 1");
$checkSkillMsg = "The user wants to learn: $skill";
$checkStmt->bind_param("iis", $currentUserId, $toUserId, $checkSkillMsg);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(['status' => 'already_sent']);
    $checkStmt->close();
    $conn->close();
    exit();
}
$checkStmt->close();

// Insert skill choice as system message
$messageText = "The user wants to learn: $skill ($proficiency). Please respond to start the session.";

$stmt = $conn->prepare("
    INSERT INTO messages (sender_id, receiver_id, message_text, message_type, sent_at, is_read, is_deleted, is_end_session)
    VALUES (?, ?, ?, 'system', NOW(), 0, 0, 0)
");
$stmt->bind_param("iis", $currentUserId, $toUserId, $messageText);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>