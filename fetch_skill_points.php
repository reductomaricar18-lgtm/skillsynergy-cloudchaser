<?php
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'DB error']);
    exit;
}
header('Content-Type: application/json');

// Count how many times each skill is offered
$sql = "SELECT specific_skill as skill, COUNT(*) as total_offered FROM skills_offer WHERE specific_skill != '' GROUP BY specific_skill ORDER BY total_offered DESC";
$result = $conn->query($sql);

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'skill' => $row['skill'],
            'points' => (int)$row['total_offered']
        ];
    }
}
echo json_encode($data); 