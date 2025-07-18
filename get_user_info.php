<?php
header('Content-Type: application/json');

if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    echo json_encode(['error' => 'Invalid user ID.']);
    exit();
}

$user_id = intval($_GET['user_id']);
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed.']);
    exit();
}

// Get main user info
$stmt = $conn->prepare("SELECT u.user_id, u.email, u.account_type, u.last_login, u.status, p.first_name, p.last_name, p.age, e.course, e.block, e.year FROM users u LEFT JOIN users_profile p ON u.user_id = p.user_id LEFT JOIN education e ON u.user_id = e.user_id WHERE u.user_id = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    echo json_encode(['error' => 'User not found.']);
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->bind_result($uid, $email, $account_type, $last_login, $status, $first_name, $last_name, $age, $course, $block, $year);
$stmt->fetch();
$stmt->close();

// Get skills
$skills = [];
$skills_stmt = $conn->prepare("SELECT specific_skill FROM skills_offer WHERE user_id = ? AND specific_skill != ''");
$skills_stmt->bind_param("i", $user_id);
$skills_stmt->execute();
$skills_result = $skills_stmt->get_result();
while ($row = $skills_result->fetch_assoc()) {
    $skills[] = $row['specific_skill'];
}
$skills_stmt->close();

// Compose name
$name = trim(($first_name ?? '') . ' ' . ($last_name ?? ''));

// Output JSON
$data = [
    'name' => $name ?: '-',
    'user_id' => $uid,
    'status' => $status ?: '-',
    'last_login' => $last_login ? date('M d, Y H:i', strtotime($last_login)) : 'Never',
    'email' => $email ?: '-',
    'age' => $age ?: '-',
    'course' => $course ?: '-',
    'block' => $block ?: '-',
    'year' => $year ?: '-',
    'skills' => $skills ? implode(', ', $skills) : '-',
];
echo json_encode($data);
$conn->close(); 