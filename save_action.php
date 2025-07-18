<?php
session_start();
if (!isset($_SESSION['email'])) { http_response_code(403); exit(); }

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) { die("DB connection failed"); }

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

$liked_user_id = $_POST['liked_user_id'];
$action = $_POST['action'];

$stmt = $conn->prepare("INSERT INTO user_likes (user_id, liked_user_id, action) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $liked_user_id, $action);
$stmt->execute();
$stmt->close();

echo "success";
?>