<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

$user1_id = isset($_GET['user1_id']) ? (int)$_GET['user1_id'] : 0;
$user2_id = isset($_GET['user2_id']) ? (int)$_GET['user2_id'] : 0;

if (!$user1_id || !$user2_id) {
    echo json_encode(['error' => 'Missing user IDs']);
    exit();
}

$stmt = $conn->prepare("SELECT session_id, skill_category, specific_skill, status, created_at, completed_at, thank_you_sent_at, accepted FROM sessions WHERE ((user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)) AND (status = 'active' OR status = 'pending') ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("iiii", $user1_id, $user2_id, $user2_id, $user1_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($session_id, $skill_category, $specific_skill, $status, $created_at, $completed_at, $thank_you_sent_at, $accepted, $user1_id, $user2_id);
    $stmt->fetch();
    echo json_encode([
        'session_id' => $session_id,
        'skill_category' => $skill_category,
        'specific_skill' => $specific_skill,
        'status' => $status,
        'created_at' => $created_at,
        'completed_at' => $completed_at,
        'thank_you_sent_at' => $thank_you_sent_at,
        'accepted' => $accepted,
        'user1_id' => $user1_id,
        'user2_id' => $user2_id
    ]);
} else {
    echo json_encode([]);
}
$stmt->close();
$conn->close(); 