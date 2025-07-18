<?php
session_start();
if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

$receiver_id = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0;

if (!$receiver_id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid receiver ID']);
    exit();
}

// Fetch user skills
$stmt = $conn->prepare("
    SELECT skill, proficiency 
    FROM initial_assessment 
    WHERE user_id = ? 
    ORDER BY skill ASC
");

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Statement preparation failed: ' . $conn->error]);
    exit();
}

$stmt->bind_param("i", $receiver_id);
$stmt->execute();
$result = $stmt->get_result();

$skills = [];
while ($row = $result->fetch_assoc()) {
    $skills[] = [
        'skill' => $row['skill'],
        'proficiency' => $row['proficiency']
    ];
}

$stmt->close();
$conn->close();

echo json_encode([
    'status' => 'success',
    'skills' => $skills
]);
?>
