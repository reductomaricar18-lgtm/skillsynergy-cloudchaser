<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];

// Get current user info
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Get current user's skills
$userSkills = [];
$result = $conn->query("SELECT skill, proficiency FROM initial_assessment WHERE user_id = $user_id");
while ($row = $result->fetch_assoc()) {
    $userSkills[$row['skill']] = $row['proficiency'];
}

// --- SEARCH FILTER HANDLING ---
$searchKeyword = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// --- GET CANDIDATE USERS (excluding current user and already liked ones) ---
$query = "
    SELECT u.user_id, up.first_name, up.last_name, up.age, up.location, up.availability, ia.skill, ia.proficiency
    FROM users u
    JOIN users_profile up ON u.user_id = up.user_id
    JOIN initial_assessment ia ON u.user_id = ia.user_id
    WHERE u.user_id != $user_id
    AND u.user_id NOT IN (
        SELECT liked_user_id FROM user_likes WHERE user_id = $user_id
    )
";

if (!empty($searchKeyword)) {
    $query .= " AND (up.first_name LIKE '%$searchKeyword%' OR up.last_name LIKE '%$searchKeyword%' OR ia.skill LIKE '%$searchKeyword%')";
}

$res = $conn->query($query);

$candidates = [];
while ($row = $res->fetch_assoc()) {
    $uid = $row['user_id'];
    if (!isset($candidates[$uid])) {
        $candidates[$uid] = [
            'user_id' => $uid,
            'name' => $row['first_name'] . ' ' . $row['last_name'],
            'age' => $row['age'],
            'location' => $row['location'],
            'availability' => $row['availability'],
            'skills' => [],
        ];
    }
    $candidates[$uid]['skills'][$row['skill']] = $row['proficiency'];
}

$matches = [];
foreach ($candidates as $candidate) {
    $score = 0;
    foreach ($candidate['skills'] as $skill => $proficiency) {
        if (isset($userSkills[$skill])) {
            $score += ($userSkills[$skill] === $proficiency) ? 2 : 1;
        }
    }

    if (abs($candidate['age'] - $_SESSION['profile_age']) <= 5) $score++;
    if ($candidate['location'] === $_SESSION['profile_location']) $score++;
    if ($candidate['availability'] === $_SESSION['profile_availability']) $score++;

    if ($score > 2) {
        $matches[] = [
            'user_id' => $candidate['user_id'],
            'name' => $candidate['name'],
            'icon' => '👤',
            'score' => $score
        ];
    }
}

shuffle($matches);
echo json_encode($matches);
