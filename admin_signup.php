<?php
session_start();
include('includes/errors.php');

// Database connection
$conn = new mysqli("localhost", "root", "", "sia1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an admin already exists
$admin_count = 0;
$result = $conn->query("SELECT COUNT(*) as count FROM admin_accounts");
if ($result) {
    $row = $result->fetch_assoc();
    $admin_count = (int)$row['count'];
}

if ($admin_count > 0) {
    $_SESSION['errors'][] = "Admin account already exists. Please log in.";
    header("Location: admin_login_page.php");
    exit();
}

// Handle form submission
if (isset($_POST['reg_user'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $errors = [];

    // Validation
    if (empty($name)) { $errors[] = "Name is required"; }
    if (empty($email)) { $errors[] = "Email is required"; }
    if (empty($password)) { $errors[] = "Password is required"; }

    // Email pattern check (double-check server-side)
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@plm\\.edu\\.ph$/", $email)) {
        $errors[] = "Email must be a valid PLM email address (e.g. example@plm.edu.ph)";
    }

    // Check if email already exists
    $check_query = $conn->prepare("SELECT id FROM admin_accounts WHERE email = ?");
    $check_query->bind_param("s", $email);
    $check_query->execute();
    $check_query->store_result();
    if ($check_query->num_rows > 0) {
        $errors[] = "Email already exists";
    }
    $check_query->close();

    // If no errors, insert into database
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_query = $conn->prepare("INSERT INTO admin_accounts (name, email, password) VALUES (?, ?, ?)");
        $insert_query->bind_param("sss", $name, $email, $hashed_password);
        if ($insert_query->execute()) {
            $_SESSION['success'] = "Account created successfully!";
            header("Location: admin_login_page.php");
            exit();
        } else {
            $errors[] = "Failed to register admin account";
        }
        $insert_query->close();
    }

    // Save errors to session
    $_SESSION['errors'] = $errors;
}
?>

<!DOCTYPE html>
<html lang="en">
<title>Admin Sign-up</title>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Georgia, 'Times New Roman', Times, serif;
            background-image: url('S3.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }
        .signup-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .signup-box {
            background: rgba(255,255,255,0.95);
            padding: 40px 32px;
            border-radius: 18px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.10);
            width: 400px;
            max-width: 95vw;
        }
        h2 {
            color: rgb(86, 86, 214);
            text-align: center;
            margin-bottom: 18px;
        }
        .user-box {
            position: relative;
            margin-bottom: 22px;
        }
        .user-box input {
            width: 100%;
            padding: 10px 0;
            font-size: 16px;
            color: rgb(9, 9, 9);
            border: none;
            border-bottom: 1px solid #2f002c;
            background: transparent;
            outline: none;
        }
        .user-box label {
            position: absolute;
            top: 10px;
            left: 0;
            color: rgb(98, 111, 231);
            pointer-events: none;
            transition: .5s;
        }
        .user-box input:focus~label,
        .user-box input:valid~label {
            top: -20px;
            font-size: 12px;
            color: rgb(106, 132, 220);
        }
        button {
            background: rgb(115, 158, 238);
            color: #fff;
            padding: 10px 20px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: background 0.2s;
        }
        button:hover { background: rgb(7, 7, 7); }
        .error-message {
            color: red;
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
            padding: 5px 10px;
            background: #ffe6e6;
            border: 1px solid #ffb3b3;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="signup-container">
    <div class="signup-box">
        <h2>Create Admin Account</h2>
        <?php
        if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
            echo '<div class="error-message">';
            foreach ($_SESSION['errors'] as $error) {
                echo $error . "<br>";
            }
            echo '</div>';
            unset($_SESSION['errors']);
        }
        ?>
        <form action="admin_signup.php" method="post">
            <div class="user-box">
                <input required="true" name="name" type="text">
                <label>Name</label>
            </div>
            <div class="user-box">
                <input required="true" name="email" type="email"
                       pattern="^[a-zA-Z0-9._%+-]+@plm\.edu\.ph$"
                       title="Please enter a valid PLM email address (e.g. example@plm.edu.ph)">
                <label>Email</label>
            </div>
            <div class="user-box">
                <input required="true" name="password" type="password">
                <label>Password</label>
            </div>
            <button type="submit" name="reg_user">Create Account</button>
        </form>
    </div>
</div>
</body>
</html>