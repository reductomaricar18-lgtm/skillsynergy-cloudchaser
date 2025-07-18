<?php
session_start();

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Initialize errors array if not set
if (!isset($_SESSION['errors'])) {
    $_SESSION['errors'] = array();
}

// Process form submission
if (isset($_POST['reg_user'])) {
    // Collect & sanitize inputs
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($name)) {
        $_SESSION['errors'][] = "Name is required.";
    }

    if (empty($email)) {
        $_SESSION['errors'][] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors'][] = "Invalid email format.";
    } elseif (!preg_match("/^[a-zA-Z0-9._%+-]+@plm\.edu\.ph$/", $email)) {
        $_SESSION['errors'][] = "Email must be a valid PLM email address.";
    }

    if (empty($password)) {
        $_SESSION['errors'][] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $_SESSION['errors'][] = "Password must be at least 6 characters long.";
    }

    // If no errors, proceed to register
    if (count($_SESSION['errors']) == 0) {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM admin_accounts WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['errors'][] = "Email is already registered.";
            $stmt->close();
            header("Location: admin_signup.php");
            exit();
        }

        $stmt->close();

        // Hash the password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert admin user
        $insert = $conn->prepare("INSERT INTO admin_accounts (name, email, password) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $name, $email, $hashedPassword);

        if ($insert->execute()) {
            $_SESSION['success_message'] = "Admin account created successfully!";
            $insert->close();
            $conn->close();
            header("Location: adminlogin.php");
            exit();
        } else {
            $_SESSION['errors'][] = "Failed to create admin account. Please try again.";
            $insert->close();
        }
    }

    // Redirect back to signup if errors
    header("Location: admin_signup.php");
    exit();
}
?>
