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

if (!isset($_POST['targetUserId']) || !is_numeric($_POST['targetUserId'])) {
    echo json_encode(['status' => 'missing_or_invalid_target']);
    $conn->close();
    exit();
}

$targetUserId = intval($_POST['targetUserId']);

// Check mutual like first
$stmt = $conn->prepare("
    SELECT 1 
    FROM user_likes AS a
    JOIN user_likes AS b ON a.user_id = ? AND b.user_id = ? 
    WHERE a.liked_user_id = ? AND b.liked_user_id = ? 
    AND a.action = 'like' AND b.action = 'like'
");
$stmt->bind_param("iiii", $currentUserId, $targetUserId, $targetUserId, $currentUserId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(['status' => 'not_matched']);
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->close();

// Fetch target user info and skills
$userInfo = $conn->query("SELECT first_name, last_name FROM users_profile WHERE user_id = $targetUserId")->fetch_assoc();

$skillsResult = $conn->query("
    SELECT skill, proficiency 
    FROM initial_assessment 
    WHERE user_id = $targetUserId 
    AND proficiency IN ('Beginner', 'Intermediate', 'Advanced')
    ORDER BY FIELD(proficiency, 'Advanced', 'Intermediate', 'Beginner')
");

$skills = [];
while ($row = $skillsResult->fetch_assoc()) {
    $skills[] = [
        'skill' => $row['skill'],
        'proficiency' => $row['proficiency']
    ];
}

echo json_encode([
    'status' => 'show_skill_selection',
    'target_user' => [
        'id' => $targetUserId,
        'name' => $userInfo['first_name'] . ' ' . $userInfo['last_name'],
        'skills' => $skills
    ]
]);

$conn->close();
?>