<?php
session_start();
if (!isset($_SESSION['user'])) {
    echo "error: not logged in";
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_SESSION['user'];

// Get user_id
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Insert assessments
if (isset($_POST['skill_name']) && isset($_POST['rating'])) {
    $skills = $_POST['skill_name'];
    $ratings = $_POST['rating'];

    $stmt = $conn->prepare("INSERT INTO initial_assessment (user_id, skill_name, rating) VALUES (?, ?, ?)");

    for ($i = 0; $i < count($skills); $i++) {
        $stmt->bind_param("isi", $user_id, $skills[$i], $ratings[$i]);
        $stmt->execute();
    }
    $stmt->close();
    echo "success";
} else {
    echo "error: missing data";
}
?>