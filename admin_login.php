<?php
session_start();

// DB connection
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Initialize errors
if (!isset($_SESSION['errors'])) {
    $_SESSION['errors'] = array();
}

// Process login request
if (isset($_POST['login_user'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['errors'][] = "Email and Password are required.";
        header("Location: admin_login_page.php");
        exit();
    }

    // Validate PLM email format
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@plm\.edu\.ph$/", $email)) {
        $_SESSION['errors'][] = "Only PLM email addresses are allowed (e.g. example@plm.edu.ph).";
        header("Location: admin_login_page.php");
        exit();
    }

    // Check admin account — corrected table name here!
    $stmt = $conn->prepare("SELECT * FROM admin_accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_email'] = $user['email'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $_SESSION['errors'][] = "Incorrect password.";
            header("Location: admin_login_page.php");
            exit();
        }
    } else {
        $_SESSION['errors'][] = "No admin account found with that PLM email.";
        header("Location: admin_login_page.php");
        exit();
    }
}
?>
