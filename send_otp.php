<?php
session_start();
require 'PHPMailer/PHPMailer/src/PHPMailer.php';
require 'PHPMailer/PHPMailer/src/SMTP.php';
require 'PHPMailer/PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$email = $_POST['email'] ?? '';
if (!preg_match('/^[a-zA-Z0-9._%+-]+@plm\.edu\.ph$/', $email)) {
    $_SESSION['email_error'] = 'Please enter a valid PLM email address (e.g., yourname@plm.edu.ph)';
    header("Location: signup.php");
    exit();
}

// Check if email already exists in users table
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $_SESSION['email_error'] = "This email already has an account.";
    header("Location: signup.php");
    exit();
}
$stmt->close();

$_SESSION['email'] = $email;

// Set send time as UNIX timestamp for countdown reference
$_SESSION['otp_sent_time'] = time();

// Clear any previous error
unset($_SESSION['otp_error']);

// Generate 6-digit OTP
$otp = rand(100000, 999999);
$_SESSION['otp'] = $otp;

// Save OTP to email_verification table
$stmt = $conn->prepare("INSERT INTO email_verification (email, otp, created_at) VALUES (?, ?, NOW())");
$stmt->bind_param("ss", $email, $otp);
$stmt->execute();
$stmt->close();

// Send OTP email using PHPMailer with SendGrid SMTP
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.sendgrid.net';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'apikey';
    $mail->Password = getenv('SENDGRID_API_KEY');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];

    $mail->setFrom('mpreducto2022@plm.edu.ph', 'SkillSynergy System');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Your SkillSynergy OTP Code';
    $mail->Body    = "<h3>Your OTP code is: <strong>$otp</strong></h3><p>Use this to complete your signup.</p>";

    $mail->send();

    header("Location: signup.php");
    exit();
} catch (Exception $e) {
    $_SESSION['email_error'] = "Failed to send OTP. Please try again.";
    header("Location: signup.php");
    exit();
}

$conn->close();
?>











