<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

if (!isset($_SESSION['reset_otp_verified']) || !$_SESSION['reset_otp_verified']) {
    echo json_encode(['success' => false, 'message' => 'Please verify your OTP first.']);
    exit();
}

$email = $_SESSION['reset_verified_email'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate inputs
if (empty($new_password) || empty($confirm_password)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all password fields.']);
    exit();
}

if ($new_password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
    exit();
}

if (strlen($new_password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long.']);
    exit();
}

// Hash the new password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update user password in DB
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $hashed_password, $email);

if ($stmt->execute()) {
    $_SESSION['password_reset_success'] = true;

    // Clear session reset data
    unset($_SESSION['reset_email']);
    unset($_SESSION['reset_otp_sent_time']);
    unset($_SESSION['reset_otp_error']);
    unset($_SESSION['reset_email_error']);
    unset($_SESSION['reset_otp_verified']);
    unset($_SESSION['reset_verified_email']);
    unset($_SESSION['reset_verified_otp']);
    unset($_SESSION['reset_password_error']);
    unset($_SESSION['reset_session']);

    echo json_encode(['success' => true, 'message' => 'Password updated successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update password. Please try again.']);
}

$stmt->close();
$conn->close();
?>
