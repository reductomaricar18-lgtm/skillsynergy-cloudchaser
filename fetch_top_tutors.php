<?php
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode([]);
    exit();
}
$sql = "
  SELECT u.user_id,
         CONCAT(p.first_name, ' ', p.last_name) as name,
         p.profile_pic,
         AVG((r.understanding_rating + r.knowledge_sharing_rating + r.listening_rating) / 3) as avg_rating,
         COUNT(r.rating_id) as rating_count
  FROM users u
  INNER JOIN users_profile p ON u.user_id = p.user_id
  INNER JOIN user_ratings r ON u.user_id = r.rated_user_id
  GROUP BY u.user_id, p.first_name, p.last_name, p.profile_pic
  HAVING rating_count >= 1
  ORDER BY avg_rating DESC
  LIMIT 10
";
$res = $conn->query($sql);
$tutors = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $tutors[] = [
            'user_id' => $row['user_id'],
            'name' => $row['name'],
            'profile_pic' => $row['profile_pic'],
            'avg_rating' => round((float)$row['avg_rating'], 2),
            'rating_count' => (int)$row['rating_count']
        ];
    }
}
$conn->close();
echo json_encode($tutors); 