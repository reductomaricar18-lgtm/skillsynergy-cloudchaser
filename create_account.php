<?php
ob_start();
session_start();

// Redirect to signup if no verified session
if (!isset($_SESSION['verified_email']) || !isset($_SESSION['verified_otp'])) {
    header("Location: signup.php");
    exit();
}

// Connect to DB
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch verified email + otp for display
$email = $_SESSION['verified_email'];
$otp = $_SESSION['verified_otp'];

$query = $conn->prepare("SELECT otp FROM email_verification WHERE email = ? AND otp = ? AND created_at >= (NOW() - INTERVAL 5 MINUTE)");
$query->bind_param("ss", $email, $otp);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    header("Location: signup.php");
    exit();
}

$row = $result->fetch_assoc();
$passwordError = "";

// INSERT THE PASSWORD FORM HANDLER HERE  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        $passwordError = "Passwords do not match.";
    } else {
    // Check if email already exists in users table
    $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $passwordError = "An account already exists for this email.";
    } else {
      // Hash the password before storing
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      // Insert new user
      $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
      $stmt->bind_param("ss", $email, $hashedPassword);
      if ($stmt->execute()) {
          session_unset();
          session_destroy();
          header("Location: login.php");
          exit();
        } else {
          $passwordError = "Error creating account.";
        }
        $stmt->close();
    }
    $check->close();
  }
}

// Close main query & connection
$query->close();
$conn->close();
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SkillSynergy Create Account</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    body {
      background: linear-gradient(to bottom right, #cce7ff, #e2e2ff);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background-image: url('S.jpg');
      background-repeat: no-repeat;
      background-size: cover;
      margin: 0px;
    }
    form {
      display: flex;
      justify-content: space-between;
      width: 400px;
      background: #fff;
      border: 1px solid black;
      border-radius: 50px;
      padding: 5px;
      margin-bottom: 20px;
      margin-left: 1060px;
    }
    input[type="email"],
    input[type="text"],
    input[type="password"] {
      border: none;
      outline: none;
      padding: 12px 20px;
      font-size: 16px;
      flex: 1;
      border-radius: 50px;
      margin-right: 10px;
    }
    button {
      background-color: #ddd;
      border: none;
      padding: 10px 18px;
      border-radius: 50px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #b5cfff;
    }
    .btn-success {
      background-color: #28a745 !important;
      color: white;
    }
    .btn-danger {
      background-color: #dc3545 !important;
      color: white;
    }
    .timer {
      font-size: 14px;
      margin-left: 10px;
      color: #333;
      font-weight: bold;
    }

    .error {
      border: 1px solid red;
    }

    .error-message {
      color: red;
      font-size: 13px;
      margin-top: -10px;
      margin-bottom: 15px;
      text-align: left;
      width: 400px;
      margin-left: 1260px;
    }

    @media (max-width: 450px) {
      form {
        width: 90%;
        flex-direction: column;
      }
      input {
        margin: 10px 0;
      }
      button {
        width: 100%;
      }
    }

.inline-form {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 400px;
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 50px;
  padding: 5px;
  margin-bottom: 10px;
}

.inline-form input[type="password"] {
  border: none;
  outline: none;
  padding: 12px 20px;
  font-size: 16px;
  flex: 1;
  border-radius: 50px;
  margin-right: 10px;
}

.create-btn {
  background-color: #28a745;
  color: white;
  border: none;
  justify-content: center;
  align-items: center;
  padding: 10px 24px;
  border-radius: 50px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s ease;
  width: 100px;
}

.create-btn:hover {
  background-color: #218838;
}

ul {
  list-style-type: none;
  margin-left: 1300px;
  padding: 2px;
  overflow: hidden;
  background-color: #333;
  border-radius: 50px;
  margin-bottom: 300px;
  margin-top: 50px;
}

li {
  float: left;
}

li a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

li a:hover:not(.active) {
  background-color: #111;
}

.active {
  background-color: #04AA6D;
}

.toggle-btn {
  background-color: #ddd;
  border: none;
  padding: 10px 14px;
  border-radius: 50px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.toggle-btn:hover {
  background-color: #b5cfff;
}

.toggle-btn i {
  pointer-events: none;
}
  </style>
</head>
<body>
<ul>
  <li><a href="Login.php">Log in</a></li>
</ul>


<!-- Verified Email Field (disabled) -->
<form class="inline-form" onsubmit="return false;">
  <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['verified_email']); ?>" readonly disabled>
</form>

<!-- Verified OTP Field (disabled) -->
<form class="inline-form" onsubmit="return false;" style="margin-left: 1060px;">
  <input type="text" name="otp" value="<?php echo isset($_SESSION['verified_otp']) ? htmlspecialchars($_SESSION['verified_otp']) : ''; ?>" readonly disabled>
  <button type="button" class="btn btn-success" disabled>Verified</button>
</form>

  <div style="width: 400px; height: 1px; background-color: #ccc; margin: 10px 0; margin-left: 1060px;"></div>

<!-- Create Password Field -->
<form id="createPasswordForm" class="inline-form" onsubmit="return false;">
  <input type="password" id="password" name="password" placeholder="Create Password" required>
  <button type="button" class="toggle-btn" onclick="togglePassword('password', this)">
    <i class="fa-solid fa-eye"></i>
  </button>
</form>

<!-- Confirm Password Field -->
<form id="confirmPasswordForm" class="inline-form" onsubmit="return false;">
  <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
  <button type="button" class="toggle-btn" onclick="togglePassword('confirm_password', this)">
    <i class="fa-solid fa-eye"></i>
  </button>
</form>

<!-- Error Message Display -->
<div id="passwordError" class="error-message" style="display: none;"></div>
<?php if (!empty($passwordError)) : ?>
  <div class="error-message"><?php echo $passwordError; ?></div>
<?php endif; ?>

<!-- Create Account Button -->
<div class="button-container" style="margin-left: 1060px;">
  <button type="button" class="create-btn" onclick="validatePasswords()">Sign up</button>
</div>


<script>
function validatePasswords() {
  var password = document.getElementById("password").value.trim();
  var confirmPassword = document.getElementById("confirm_password").value.trim();
  var errorDiv = document.getElementById("passwordError");

  if (password === "" || confirmPassword === "") {
    errorDiv.innerHTML = "Please fill in both password fields.";
    errorDiv.style.display = "block";
    document.getElementById("password").classList.add("error");
    document.getElementById("confirm_password").classList.add("error");
    return; // Stop function execution if fields are empty
  }

  if (password !== confirmPassword) {
    errorDiv.innerHTML = "Passwords do not match.";
    errorDiv.style.display = "block";
    document.getElementById("password").classList.add("error");
    document.getElementById("confirm_password").classList.add("error");
  } else {
    errorDiv.style.display = "none";
    document.getElementById("password").classList.remove("error");
    document.getElementById("confirm_password").classList.remove("error");

    // Now create a hidden form to submit both values to PHP
    var form = document.createElement("form");
    form.method = "POST";
    form.action = "create_account.php";

    var inputPass = document.createElement("input");
    inputPass.type = "hidden";
    inputPass.name = "password";
    inputPass.value = password;
    form.appendChild(inputPass);

    var inputConfirm = document.createElement("input");
    inputConfirm.type = "hidden";
    inputConfirm.name = "confirm_password";
    inputConfirm.value = confirmPassword;
    form.appendChild(inputConfirm);

    document.body.appendChild(form);
    form.submit();
  }
}

function togglePassword(inputId, btn) {
  var input = document.getElementById(inputId);
  var icon = btn.querySelector("i");

  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  }
}
</script>

</body>
</html>
