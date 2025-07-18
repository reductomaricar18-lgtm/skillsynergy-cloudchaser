<?php
session_start();

// Set header to return JSON
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

$email = $_SESSION['reset_email'] ?? '';
$enteredOtp = $_POST['otp'] ?? '';

// Check OTP validity (40 seconds)
$query = $conn->prepare("SELECT * FROM email_verification WHERE email = ? AND otp = ? AND created_at >= (NOW() - INTERVAL 40 SECOND)");
$query->bind_param("ss", $email, $enteredOtp);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    // OTP is valid
    $_SESSION['reset_otp_verified'] = true;
    $_SESSION['reset_verified_email'] = $email;
    $_SESSION['reset_verified_otp'] = $enteredOtp;
    $_SESSION['reset_session'] = true;

    // Clear stored OTP
    unset($_SESSION['reset_otp']);
    unset($_SESSION['reset_otp_sent_time']);

    echo json_encode(['success' => true, 'message' => 'OTP verified successfully!']);
    exit();
} else {
    // Invalid OTP
    $_SESSION['reset_otp_verified'] = false;

    echo json_encode(['success' => false, 'message' => 'Invalid or expired OTP. Please try again.']);
    exit();
}

$conn->close();
?> 