<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'] ?? '';
$enteredOtp = $_POST['otp'] ?? '';

// OTP validity: 40 seconds (match frontend)
$query = $conn->prepare("SELECT * FROM email_verification WHERE email = ? AND otp = ? AND created_at >= (NOW() - INTERVAL 40 SECOND)");
$query->bind_param("ss", $email, $enteredOtp);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    // Valid match
    $_SESSION['verified_email'] = $email;
    $_SESSION['verified_otp'] = $enteredOtp;
    $_SESSION['otp_valid'] = true;

    // Clear session-stored OTP
    unset($_SESSION['otp']);
    unset($_SESSION['otp_sent']);

    header("Location: create_account.php");
    exit();
} else {
    // Invalid
    $_SESSION['otp_error'] = "Invalid or expired OTP. Please try again.";
    $_SESSION['otp_valid'] = false;

    // Allow resend immediately
    unset($_SESSION['otp_sent']);

    header("Location: signup.php");
    exit();
}

$conn->close();
?>
