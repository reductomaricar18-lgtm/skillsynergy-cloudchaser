<?php
ob_start();
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Collect form inputs
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Check if email and password are not empty
if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = "Please enter both email and password.";
    header("Location: login.php");
    exit;
}

// Validate PLM email format
if (!preg_match("/^[a-zA-Z0-9._%+-]+@plm\.edu\.ph$/", $email)) {
    $_SESSION['email_error'] = 'Please enter a valid PLM email address (e.g., yourname@plm.edu.ph).';
    header("Location: login.php");
    exit;
}

// Check if the user exists
$stmt = $conn->prepare("SELECT user_id, email, password, account_type, profile_completed FROM users WHERE email = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Hashed password verification
    if (password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['email'] = $email;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['account_type'];

        // Redirect based on account type
        if ($user['account_type'] === 'admin') {
            header("Location: admin_dashboard.php");
            exit;
        }

        // Regular user flow
        if ($user['profile_completed'] == 1) {
            header("Location: dashboard.php");
        } else {
            header("Location: profile_setup.php");
        }
        exit;

    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: login.php");
        exit;
    }

} else {
    $_SESSION['email_error'] = "This email does not exist.";
    header("Location: login.php");
    exit;
}

$stmt->close();
$conn->close();
ob_end_flush();
?>
