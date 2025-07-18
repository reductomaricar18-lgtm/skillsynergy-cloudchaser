<?php
session_start();


// If user just completed signup, clear all signup-related session variables
if (isset($_SESSION['signup_completed'])) {
    unset($_SESSION['email']);
    unset($_SESSION['verified_email']);
    unset($_SESSION['otp_sent_time']);
    unset($_SESSION['otp_error']);
    unset($_SESSION['email_error']);
    unset($_SESSION['signup_session']);
    unset($_SESSION['signup_completed']);
}

// If not in signup session, clear old session vars
if (!isset($_SESSION['signup_session'])) {
    unset($_SESSION['email']);
    unset($_SESSION['verified_email']);
    unset($_SESSION['otp_sent_time']);
    unset($_SESSION['otp_error']);
    unset($_SESSION['email_error']);
}

// Start signup session tracker
$_SESSION['signup_session'] = true;

// Keep email input value if email was sent
$email = $_SESSION['email'] ?? '';

// Check verification status and error messages
$verified = $_SESSION['verified_email'] ?? false;
$error_msg = $_SESSION['otp_error'] ?? '';
$email_error = $_SESSION['email_error'] ?? '';

// Timer: check if OTP was sent
$otp_sent_time = $_SESSION['otp_sent_time'] ?? false;
$current_time = time();
$time_left = 0;

if ($otp_sent_time) {
    $elapsed = $current_time - $otp_sent_time;
    $time_left = max(60 - $elapsed, 0);
}

// Button class & label for Verify button
$btnClass = 'btn-primary';
$btnLabel = 'Verify';

if ($verified) {
    $btnClass = 'btn-success';
    $btnLabel = 'Verified';
} elseif (!empty($error_msg)) {
    $btnClass = 'btn-danger';
    $btnLabel = 'Invalid';
}

// If verified, clear signup-related session vars so user can proceed cleanly
if ($verified) {
    unset($_SESSION['email']);
    unset($_SESSION['verified_email']);
    unset($_SESSION['otp_sent_time']);
    unset($_SESSION['otp_error']);
    unset($_SESSION['email_error']);
    unset($_SESSION['signup_session']); // also remove the signup session tracker
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SkillSynergy Sign Up</title>
<style>
body {
    background: linear-gradient(to bottom right, #cce7ff, #e2e2ff);
    font-family: 'Segoe UI', sans-serif;
    background-image: url('S.jpg');
    background-repeat: no-repeat;
    background-size: cover;
    margin: 0px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
form {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 400px;
    background: #fff;
    border-radius: 50px;
    padding: 5px;
    margin-bottom: 5px;
    margin-left: 1050px;
    margin-top: 15px;
    border: 1px solid black;
}
input[type="email"], input[type="text"] {
    border: none;
    outline: none;
    padding: 12px 20px;
    font-size: 16px;
    flex: 1;
    border-radius: 50px;
    margin-right: 10px;
}
button, .btn {
    border: none;
    padding: 10px 18px;
    border-radius: 50px;
    cursor: pointer;
    font-size: 14px;
    color: white;
    transition: background-color 0.3s ease;
}
.btn-primary {
    background-color: rgb(166, 168, 170);
}
.btn-primary:hover {
    background-color: rgb(31, 148, 250);
}
.btn-success {
    background-color: #28a745;
}
.btn-danger {
    background-color: #dc3545;
}
.timer {
    font-size: 14px;
    margin-left: 10px;
    color: #333;
    font-weight: bold;
}
.error-message {
    color: red;
    font-size: 13px;
    margin-top: 20px;
    margin-bottom: 10px;
    text-align: left;
    width: 400px;
    margin-left: 1070px;
}
/* Responsive Design */
@media (max-width: 1200px) {
    form {
        margin-left: 50px;
        width: 350px;
    }
    
    ul {
        margin-left: 50px;
    }
    
    .error-message {
        margin-left: 50px;
        width: 350px;
    }
}

@media (max-width: 768px) {
    body {
        background-size: cover;
        background-position: center;
    }
    
    form {
        margin-left: 20px;
        width: 300px;
        padding: 8px;
    }
    
    ul {
        margin-left: 20px;
        margin-top: 60px;
        border-radius: 25px;
    }
    
    li a {
        padding: 12px 14px;
        font-size: 14px;
    }
    
    .error-message {
        margin-left: 20px;
        width: 300px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    form {
        margin-left: 10px;
        width: 280px;
        flex-direction: column;
        gap: 10px;
    }
    
    input {
        margin: 5px 0;
        margin-right: 0;
    }
    
    button {
        width: 100%;
    }
    
    ul {
        margin-left: 10px;
        margin-top: 50px;
        border-radius: 20px;
    }
    
    li a {
        padding: 10px 12px;
        font-size: 12px;
    }
    
    .error-message {
        margin-left: 10px;
        width: 280px;
        font-size: 11px;
    }
}

@media (max-width: 360px) {
    form {
        margin-left: 5px;
        width: 260px;
    }
    
    ul {
        margin-left: 5px;
        margin-top: 40px;
    }
    
    li a {
        padding: 8px 10px;
        font-size: 11px;
    }
    
    .error-message {
        margin-left: 5px;
        width: 260px;
    }
}
ul {
    list-style-type: none;
    margin-left: 1200px;
    padding: 2px;
    overflow: hidden;
    background-color: #333;
    border-radius: 50px;
    margin-bottom: 270px;
    margin-top: 70px;
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
</style>
</head>
<body>

<ul>
    <li><a href="Login.php">Log in</a></li>
</ul>

<!-- Email Form -->
<form action="send_otp.php" method="POST">
    <input type="email" id="emailInput" name="email" placeholder="Enter your PLM Email" value="<?php echo htmlspecialchars($email); ?>" required>
    <button type="submit" id="resendButton" class="btn btn-primary" <?php echo ($time_left > 0 ? 'disabled' : ''); ?>>
        <?php echo ($time_left > 0 ? 'Resend OTP' : 'Send OTP'); ?>
    </button>
</form>

<?php if (!empty($email_error)): ?>
    <div class="error-message email-error"><?php echo $email_error; ?></div>
<?php endif; ?>

<!-- OTP Verification Form -->
<form action="verify_otp.php" method="POST" id="otpForm">
    <input type="text" name="otp" id="otpInput" placeholder="One Time Password" required>
    <button type="submit" id="verifyButton" class="<?php echo $btnClass; ?>">
        <?php echo $btnLabel; ?>
    </button>
    <?php if ($time_left > 0): ?>
    <span id="timer" class="timer"><?php echo $time_left; ?>s</span>
    <?php endif; ?>
</form>

<?php if (!empty($error_msg)): ?>
    <div class="error-message otp-error"><?php echo $error_msg; ?></div>
<?php endif; ?>

<script>
// Force reload if navigating back
if (performance.navigation.type === 2) {
    location.reload(true);
}

const verifyButton = document.getElementById('verifyButton');
const otpInput = document.getElementById('otpInput');
const emailInput = document.querySelector('input[name="email"]');

otpInput.addEventListener('input', () => {
    verifyButton.textContent = 'Verify';
    verifyButton.classList.remove('btn-danger', 'btn-secondary');
    verifyButton.classList.add('btn-primary');
    const otpError = document.querySelector('.otp-error');
    if (otpError) otpError.remove();
});

emailInput.addEventListener('input', () => {
    const emailError = document.querySelector('.email-error');
    if (emailError) emailError.remove();
});

<?php if ($time_left > 0): ?>
const resendButton = document.getElementById('resendButton');
const timerElement = document.getElementById('timer');
let timeLeft = <?= $time_left ?>;
let timer;

function startTimer() {
    resendButton.disabled = true;
    timerElement.textContent = `${timeLeft}s`;
    timer = setInterval(() => {
        timeLeft--;
        timerElement.textContent = `${timeLeft}s`;
        if (timeLeft <= 0) {
            clearInterval(timer);
            timerElement.textContent = 'Expired';
            resendButton.disabled = false;
        }
    }, 1000);
}
startTimer();
<?php endif; ?>
</script>

<?php
// Clear errors after rendering
unset($_SESSION['otp_error']);
unset($_SESSION['email_error']);
?>
</body>
</html>