<?php
session_start();


if (isset($_GET['clear_reset'])) {
    unset($_SESSION['reset_session']);
    unset($_SESSION['reset_email']);
    unset($_SESSION['reset_otp_sent_time']);
    unset($_SESSION['reset_otp_error']);
    unset($_SESSION['reset_email_error']);
    unset($_SESSION['reset_otp_verified']);
    unset($_SESSION['reset_verified_email']);
    unset($_SESSION['reset_verified_otp']);
    unset($_SESSION['reset_password_error']);
    header("Location: login.php");
    exit();
}

// Clear email error when user starts typing
if (isset($_GET['clear_email_error'])) {
    unset($_SESSION['reset_email_error']);
    header("Location: login.php");
    exit();
}

// Clear old signup session data
unset($_SESSION['email']);
unset($_SESSION['verified_email']);
unset($_SESSION['otp_sent_time']);
unset($_SESSION['otp_error']);
unset($_SESSION['signup_session']);

// Clear forgot password session if not active
if (!isset($_SESSION['reset_session'])) {
    unset($_SESSION['reset_email']);
    unset($_SESSION['reset_otp_sent_time']);
    unset($_SESSION['reset_otp_error']);
    // Don't clear reset_email_error here - let it be displayed
}

// Get error messages
$error_msg = $_SESSION['login_error'] ?? '';
$email_error = $_SESSION['email_error'] ?? '';

// Clear login errors after displaying
unset($_SESSION['login_error']);
unset($_SESSION['email_error']);

// Get forgot password error messages
$reset_email_error = $_SESSION['reset_email_error'] ?? '';
$reset_otp_error = $_SESSION['reset_otp_error'] ?? '';
$reset_password_error = $_SESSION['reset_password_error'] ?? '';

// Clear forgot password errors after displaying
unset($_SESSION['reset_otp_error']);
unset($_SESSION['reset_password_error']);
// Keep reset_email_error until user starts typing again

// Handle forgot password timer
$reset_email = $_SESSION['reset_email'] ?? '';
$reset_otp_sent_time = $_SESSION['reset_otp_sent_time'] ?? false;
$current_time = time();
$reset_time_left = 0;

if ($reset_otp_sent_time) {
    $elapsed = $current_time - $reset_otp_sent_time;
    $reset_time_left = max(90 - $elapsed, 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkillSynergy Log In</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    body {
      background: linear-gradient(to bottom right, #cce7ff, #e2e2ff);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background-image: url('S1.jpg');
      background-repeat: no-repeat;
      background-size: cover;
      margin: 0px;
    }

    ul {
      list-style-type: none;
      margin-left: 1160px;
      padding: 2px;
      overflow: hidden;
      background-color: #333;
      border-radius: 50px;
      margin-top: 90px;
    }

    li {
      float: left;
      border-right: 1px solid #fff;
    }

    li a {
      display: block;
      color: white;
      text-align: center;
      padding: 15px 16px;
      text-decoration: none;
    }

    li:last-child {
      border-right: none;
    }

    li a:hover:not(.active) {
      background-color: #111;
    }

    .active {
      background-color: #04AA6D;
    }

    .login-form {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      width: 350px;
      padding: 30px;
      margin-left: 1100px;
      margin-top: 160px;
    }

    .login-form input[type="text"],
    .login-form input[type="password"] {
      border: 1px solid #ccc;
      outline: none;
      padding: 12px 20px;
      font-size: 16px;
      border-radius: 50px;
      margin-bottom: 20px;
      width: 90%;
      border: 1px solid black;
    }

    .login-form button {
      background-color:rgb(169, 172, 171);
      border: none;
      padding: 12px 20px;
      border-radius: 50px;
      cursor: pointer;
      font-size: 16px;
      color: white;
      width: 60%;
      transition: background-color 0.3s ease;
    }

    .login-form button:hover {
      background-color: #039e62;
    }

    .error-message {
      color: red;
      font-size: 14px;
      margin-top: 10px;
      text-align: center;
    }

    .forgot-password-link {
      font-size: 14px;
      color: #333;
      text-decoration: none;
      margin-bottom: 20px;
      display: inline-block;
      transition: color 0.2s ease;
      cursor: pointer;
    }

    .forgot-password-link:hover {
      color: #04AA6D;
      text-decoration: underline;
    }

    /* Initially hide the toggle button */
    .toggle-password-btn {
      position: absolute;
      top: 50%;
      right: 20px;
      transform: translateY(-100%);
      cursor: pointer;
      color: #333;
      font-size: 18px;
      display: none; /* hidden by default */
    }

    /* To properly contain the input and icon together */
    .password-input-wrapper {
      position: relative;
      width: 100%;
      margin-bottom: 20px;
    }

    .password-input-wrapper input {
      width: 100%;
      padding-right: 45px;
      border: 1px solid black;
      outline: none;
      padding: 12px 20px;
      font-size: 16px;
      border-radius: 50px;
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 5% auto;
      padding: 30px;
      border-radius: 20px;
      width: 400px;
      max-width: 90%;
      position: relative;
    }



    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
      position: absolute;
      top: 10px;
      right: 20px;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
    }

    .modal h2 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }

    .modal-form {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      background: #fff;
      border-radius: 50px;
      padding: 5px;
      margin-bottom: 15px;
      border: 1px solid black;
    }

    .modal-form + .modal-form {
      margin-top: 20px;
    }

    .modal-form input[type="email"], 
    .modal-form input[type="text"] {
      border: none;
      outline: none;
      padding: 12px 20px;
      font-size: 16px;
      flex: 1;
      border-radius: 50px;
      margin-right: 10px;
    }

    .modal-form button, 
    .modal .btn {
      border: none;
      padding: 10px 18px;
      border-radius: 50px;
      cursor: pointer;
      font-size: 14px;
      color: white;
      transition: background-color 0.3s ease;
    }

    .modal .btn-primary {
      background-color: rgb(166, 168, 170);
    }

    .modal .btn-primary:hover {
      background-color: rgb(31, 148, 250);
    }

    .modal .btn-primary:disabled {
      background-color: #ccc;
      cursor: not-allowed;
      opacity: 0.6;
    }

    .modal .btn-success {
      background-color: #28a745;
    }

    .modal .btn-danger {
      background-color: #dc3545;
    }

    .modal .timer {
      font-size: 14px;
      margin-left: 10px;
      color: #333;
      font-weight: bold;
    }

    .modal .error-message {
      color: red;
      font-size: 13px;
      margin-top: 10px;
      margin-bottom: 10px;
      text-align: center;
    }

    .modal .success-message {
      color: green;
      font-size: 13px;
      margin-top: 10px;
      margin-bottom: 10px;
      text-align: center;
    }

    .reset-password-form {
      display: none;
      width: 100%;
    }

    .reset-password-form .password-input-wrapper {
      position: relative;
      width: 100%;
      margin-bottom: 15px;
    }

    .reset-password-form .password-input-wrapper input {
      width: 100%;
      padding-right: 45px;
      border: 1px solid black;
      outline: none;
      padding: 12px 20px;
      font-size: 16px;
      border-radius: 50px;
      box-sizing: border-box;
    }

    .reset-password-form .toggle-password-btn {
      position: absolute;
      top: 50%;
      right: 20px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #333;
      font-size: 18px;
      display: block;
    }

    .reset-password-form button {
      width: 100%;
      background-color: rgb(169, 172, 171);
      border: none;
      padding: 12px 20px;
      border-radius: 50px;
      cursor: pointer;
      font-size: 16px;
      color: white;
      transition: background-color 0.3s ease;
      box-sizing: border-box;
    }

    .reset-password-form button:hover {
      background-color: #039e62;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
      ul {
        margin-left: 50px;
        margin-top: 70px;
      }
      
      .login-form {
        margin-left: 50px;
        margin-top: 140px;
      }
    }

    @media (max-width: 768px) {
      body {
        background-size: cover;
        background-position: center;
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
      
      .login-form {
        margin-left: 20px;
        margin-top: 120px;
        width: 300px;
        padding: 25px;
      }
      
      .login-form input[type="text"],
      .login-form input[type="password"] {
        font-size: 14px;
        padding: 10px 16px;
      }
      
      .login-form button {
        font-size: 14px;
        padding: 10px 16px;
      }
      
      .modal-content {
        width: 350px;
        padding: 25px;
      }
    }

    @media (max-width: 480px) {
      ul {
        margin-left: 10px;
        margin-top: 50px;
        border-radius: 20px;
      }
      
      li a {
        padding: 10px 12px;
        font-size: 12px;
      }
      
      .login-form {
        margin-left: 10px;
        margin-top: 100px;
        width: 280px;
        padding: 20px;
      }
      
      .login-form input[type="text"],
      .login-form input[type="password"] {
        font-size: 13px;
        padding: 8px 14px;
        border-radius: 25px;
      }
      
      .login-form button {
        font-size: 13px;
        padding: 8px 14px;
        border-radius: 25px;
      }
      
      .modal-content {
        width: 300px;
        padding: 20px;
        margin: 10% auto;
      }
      
      .modal-form {
        flex-direction: column;
        gap: 10px;
      }
      
      .modal-form input[type="email"], 
      .modal-form input[type="text"] {
        margin-right: 0;
        margin-bottom: 10px;
      }
    }

    @media (max-width: 360px) {
      ul {
        margin-left: 5px;
        margin-top: 40px;
      }
      
      li a {
        padding: 8px 10px;
        font-size: 11px;
      }
      
      .login-form {
        margin-left: 5px;
        margin-top: 80px;
        width: 260px;
        padding: 15px;
      }
      
      .modal-content {
        width: 280px;
        padding: 15px;
      }
    }

  </style>
</head>
<body>

<ul>
  <li><a href="signup.php">Sign up</a></li>
  <li><a href="start.php">Home</a></li>
</ul>

<form class="login-form" action="process_login.php" method="POST">
  <input type="text" name="email" id="emailInput" placeholder="Username" required>

  <div class="password-input-wrapper">
    <input type="password" name="password" id="passwordInput" placeholder="Password" required>
    <span class="toggle-password-btn" onclick="toggleLoginPassword()">
      <i class="fa fa-eye"></i>
    </span>
  </div>

  <?php if (!empty($error_msg)) : ?>
    <div class="error-message"><?php echo htmlspecialchars($error_msg); ?></div>
  <?php endif; ?>

  <?php if (!empty($email_error)) : ?>
    <div class="error-message"><?php echo htmlspecialchars($email_error); ?></div>
  <?php endif; ?> <br>

  <button type="submit">Log In</button><br>

  <a href="#" class="forgot-password-link" onclick="openForgotPasswordModal()">Forgot Password?</a>
</form>

<!-- Forgot Password Modal -->
<div id="forgotPasswordModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeForgotPasswordModal()">&times;</span>
    <h2>Reset Password</h2>
    
    <!-- Email Form -->
    <form class="modal-form" id="resetEmailForm" onsubmit="return validateEmailForm(event)">
      <input type="email" id="resetEmailInput" name="email" placeholder="Enter your PLM Email" value="<?php echo htmlspecialchars($reset_email); ?>" required>
      <button type="submit" id="resetSendButton" class="btn btn-primary">
        Send OTP
      </button>
    </form>

    <?php if (!empty($reset_email_error)): ?>
      <div class="error-message reset-email-error"><?php echo $reset_email_error; ?></div>
    <?php endif; ?>

    <!-- OTP Verification Form -->
    <form class="modal-form" id="resetOtpForm">
      <input type="text" name="otp" id="resetOtpInput" placeholder="One Time Password" required>
      <button type="submit" id="resetVerifyButton" class="btn btn-primary">
        Verify
      </button>
      <span id="resetTimer" class="timer" style="display: none;"></span>
    </form>

    <?php if (!empty($reset_otp_error)): ?>
      <div class="error-message reset-otp-error"><?php echo $reset_otp_error; ?></div>
    <?php endif; ?>

    <!-- Reset Password Form (initially hidden) -->
    <form class="reset-password-form" id="resetPasswordForm">
      <div class="password-input-wrapper">
        <input type="password" name="new_password" id="newPasswordInput" placeholder="New Password" required>
        <span class="toggle-password-btn" onclick="toggleNewPassword()">
          <i class="fa fa-eye"></i>
        </span>
      </div>
      <div class="password-input-wrapper">
        <input type="password" name="confirm_password" id="confirmPasswordInput" placeholder="Confirm New Password" required>
        <span class="toggle-password-btn" onclick="toggleConfirmPassword()">
          <i class="fa fa-eye"></i>
        </span>
      </div>
      <button type="submit">Reset Password</button>
    </form>

    <?php if (!empty($reset_password_error)): ?>
      <div class="error-message reset-password-error"><?php echo $reset_password_error; ?></div>
    <?php endif; ?>

    <div class="success-message" id="resetSuccessMessage" style="display: none;">
      <div style="text-align: center; margin-bottom: 20px;">
        <i class="fa fa-check-circle" style="font-size: 48px; color: #28a745; margin-bottom: 15px;"></i>
        <h3 style="color: #28a745; margin-bottom: 10px;">Password Changed Successfully!</h3>
        <p style="color: #666; margin-bottom: 20px;">Your password has been updated. You can now login with your new password.</p>
        <button onclick="closeForgotPasswordModal()" style="background-color: #04AA6D; color: white; border: none; padding: 12px 30px; border-radius: 50px; cursor: pointer; font-size: 16px; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#039e62'" onmouseout="this.style.backgroundColor='#04AA6D'">
          Continue to Login
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  // Clear all error messages when typing new email or password
  const emailInput = document.getElementById('emailInput');
  const passwordInput = document.getElementById('passwordInput');

  function clearErrorMessages() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(msg => msg.remove());
  }

  emailInput.addEventListener('input', clearErrorMessages);
  passwordInput.addEventListener('input', clearErrorMessages);

  function toggleLoginPassword() {
  const input = document.getElementById("passwordInput");
  const icon = document.querySelector(".toggle-password-btn i");

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

// Show/hide toggle icon when user types in the password field
passwordInput.addEventListener('input', function () {
  const toggleBtn = document.querySelector(".toggle-password-btn");
  if (this.value.length > 0) {
    toggleBtn.style.display = 'block';
  } else {
    toggleBtn.style.display = 'none';
  }
});

// Modal functions
function openForgotPasswordModal() {
  document.getElementById('forgotPasswordModal').style.display = 'block';
}

function closeForgotPasswordModal() {
  document.getElementById('forgotPasswordModal').style.display = 'none';
  // Clear reset session when modal is closed
  <?php if (isset($_SESSION['reset_session'])): ?>
  window.location.href = 'login.php?clear_reset=1';
  <?php endif; ?>
}

// Prevent modal from closing when clicking outside
// Only allow closing via the X button
window.onclick = function(event) {
  // Do nothing when clicking outside - modal stays open
}

// Reset password form functionality
const resetEmailInput = document.getElementById('resetEmailInput');
const resetOtpInput = document.getElementById('resetOtpInput');
const resetVerifyButton = document.getElementById('resetVerifyButton');

resetOtpInput.addEventListener('input', () => {
  resetVerifyButton.textContent = 'Verify';
  resetVerifyButton.classList.remove('btn-danger', 'btn-secondary');
  resetVerifyButton.classList.add('btn-primary');
  const otpError = document.querySelector('.reset-otp-error');
  if (otpError) otpError.remove();
});

resetEmailInput.addEventListener('input', () => {
  const emailError = document.querySelector('.reset-email-error');
  if (emailError) emailError.remove();
  // Also clear the session error when user starts typing
  fetch('login.php?clear_email_error=1', {method: 'GET'});
});

// Clear password reset errors when typing in password fields
const newPasswordInput = document.getElementById('newPasswordInput');
const confirmPasswordInput = document.getElementById('confirmPasswordInput');

newPasswordInput.addEventListener('input', () => {
  const passwordError = document.querySelector('.reset-password-error');
  if (passwordError) passwordError.remove();
});

confirmPasswordInput.addEventListener('input', () => {
  const passwordError = document.querySelector('.reset-password-error');
  if (passwordError) passwordError.remove();
});

// Form validation function
function validateEmailForm(event) {
  const sendButton = document.getElementById('resetSendButton');
  if (sendButton.style.display === 'none') {
    event.preventDefault();
    event.stopPropagation();
    return false; // Completely stop form submission if button is hidden
  }
  return true; // Allow form submission
}

// Handle email form submission via AJAX
document.getElementById('resetEmailForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  // Check if button is hidden (timer is running)
  const sendButton = document.getElementById('resetSendButton');
  if (sendButton.style.display === 'none') {
    e.preventDefault();
    e.stopPropagation();
    return false; // Completely stop form submission
  }
  
  const email = document.getElementById('resetEmailInput').value;
  const formData = new FormData();
  formData.append('email', email);
  
  // Clear previous error messages
  const existingError = document.querySelector('.reset-email-error');
  if (existingError) existingError.remove();
  
  // Disable button and show loading
  const originalText = sendButton.textContent;
  sendButton.disabled = true;
  sendButton.textContent = 'Sending...';
  
  fetch('forgot_password_send_otp.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Show success message
      const successDiv = document.createElement('div');
      successDiv.className = 'success-message';
      successDiv.textContent = data.message;
      successDiv.style.color = 'green';
      successDiv.style.fontSize = '13px';
      successDiv.style.marginTop = '10px';
      successDiv.style.marginBottom = '10px';
      successDiv.style.textAlign = 'center';
      
      // Insert after the form
      document.getElementById('resetEmailForm').insertAdjacentElement('afterend', successDiv);
      
      // Remove success message after 3 seconds
      setTimeout(() => {
        if (successDiv.parentNode) {
          successDiv.remove();
        }
      }, 3000);
      
      // Enable OTP form
      document.getElementById('resetOtpForm').style.display = 'flex';
      
      // Start timer
      startResetTimer();
    } else {
      // Show error message
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error-message reset-email-error';
      errorDiv.textContent = data.message;
      
      // Insert after the form
      document.getElementById('resetEmailForm').insertAdjacentElement('afterend', errorDiv);
    }
  })
  .catch(error => {
    // Show error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message reset-email-error';
    errorDiv.textContent = 'An error occurred. Please try again.';
    
    // Insert after the form
    document.getElementById('resetEmailForm').insertAdjacentElement('afterend', errorDiv);
  })
  .finally(() => {
    // Re-enable button
    sendButton.disabled = false;
    sendButton.textContent = originalText;
  });
});

// Handle OTP form submission via AJAX
document.getElementById('resetOtpForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const otp = document.getElementById('resetOtpInput').value;
  const formData = new FormData();
  formData.append('otp', otp);
  
  // Clear previous error messages
  const existingError = document.querySelector('.reset-otp-error');
  if (existingError) existingError.remove();
  
  // Disable button and show loading
  const verifyButton = document.getElementById('resetVerifyButton');
  const originalText = verifyButton.textContent;
  verifyButton.disabled = true;
  verifyButton.textContent = 'Verifying...';
  
  fetch('forgot_password_verify_otp.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Show success message
      const successDiv = document.createElement('div');
      successDiv.className = 'success-message';
      successDiv.textContent = data.message;
      successDiv.style.color = 'green';
      successDiv.style.fontSize = '13px';
      successDiv.style.marginTop = '10px';
      successDiv.style.marginBottom = '10px';
      successDiv.style.textAlign = 'center';
      
      // Insert after the form
      document.getElementById('resetOtpForm').insertAdjacentElement('afterend', successDiv);
      
      // Remove success message after 3 seconds
      setTimeout(() => {
        if (successDiv.parentNode) {
          successDiv.remove();
        }
      }, 3000);
      
      // Hide email and OTP forms, show password form
      document.getElementById('resetEmailForm').style.display = 'none';
      document.getElementById('resetOtpForm').style.display = 'none';
      document.getElementById('resetPasswordForm').style.display = 'block';
    } else {
      // Show error message
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error-message reset-otp-error';
      errorDiv.textContent = data.message;
      
      // Insert after the form
      document.getElementById('resetOtpForm').insertAdjacentElement('afterend', errorDiv);
    }
  })
  .catch(error => {
    // Show error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message reset-otp-error';
    errorDiv.textContent = 'An error occurred. Please try again.';
    
    // Insert after the form
    document.getElementById('resetOtpForm').insertAdjacentElement('afterend', errorDiv);
  })
  .finally(() => {
    // Re-enable button
    verifyButton.disabled = false;
    verifyButton.textContent = originalText;
  });
});

// Handle password reset form submission via AJAX
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const newPassword = document.getElementById('newPasswordInput').value;
  const confirmPassword = document.getElementById('confirmPasswordInput').value;
  const formData = new FormData();
  formData.append('new_password', newPassword);
  formData.append('confirm_password', confirmPassword);
  
  // Clear previous error messages
  const existingError = document.querySelector('.reset-password-error');
  if (existingError) existingError.remove();
  
  // Disable button and show loading
  const resetButton = this.querySelector('button[type="submit"]');
  const originalText = resetButton.textContent;
  resetButton.disabled = true;
  resetButton.textContent = 'Updating...';
  
  fetch('update_password.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Show success message
      document.getElementById('resetEmailForm').style.display = 'none';
      document.getElementById('resetOtpForm').style.display = 'none';
      document.getElementById('resetPasswordForm').style.display = 'none';
      document.getElementById('resetSuccessMessage').style.display = 'block';
    } else {
      // Show error message
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error-message reset-password-error';
      errorDiv.textContent = data.message;
      
      // Insert after the form
      document.getElementById('resetPasswordForm').insertAdjacentElement('afterend', errorDiv);
    }
  })
  .catch(error => {
    // Show error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message reset-password-error';
    errorDiv.textContent = 'An error occurred. Please try again.';
    
    // Insert after the form
    document.getElementById('resetPasswordForm').insertAdjacentElement('afterend', errorDiv);
  })
  .finally(() => {
    // Re-enable button
    resetButton.disabled = false;
    resetButton.textContent = originalText;
  });
});

// Password toggle functions for reset password form
function toggleNewPassword() {
  const input = document.getElementById("newPasswordInput");
  const icon = input.parentElement.querySelector(".toggle-password-btn i");

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

function toggleConfirmPassword() {
  const input = document.getElementById("confirmPasswordInput");
  const icon = input.parentElement.querySelector(".toggle-password-btn i");

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

// Timer functionality
const resetSendButton = document.getElementById('resetSendButton');
const resetTimerElement = document.getElementById('resetTimer');
let resetTimeLeft = 60; // 40 seconds countdown
let resetTimer;

function startResetTimer() {
  resetTimeLeft = 60; // Reset to 40 seconds
  resetSendButton.style.display = 'none'; // Hide the button completely
  resetTimerElement.style.display = 'inline';
  resetTimerElement.textContent = `${resetTimeLeft}s`;
  
  resetTimer = setInterval(() => {
    resetTimeLeft--;
    resetTimerElement.textContent = `${resetTimeLeft}s`;
    if (resetTimeLeft <= 0) {
      clearInterval(resetTimer);
      resetTimerElement.style.display = 'none';
      resetSendButton.style.display = 'inline'; // Show the button again
      resetSendButton.disabled = false;
      resetSendButton.textContent = 'Resend OTP';
    }
  }, 1000);
}

// Initialize timer if there's already a session
<?php if ($reset_time_left > 0): ?>
resetTimeLeft = <?= $reset_time_left ?>;
resetSendButton.style.display = 'none'; // Hide button during active session
resetTimerElement.style.display = 'inline';
resetTimerElement.textContent = `${resetTimeLeft}s`;

resetTimer = setInterval(() => {
  resetTimeLeft--;
  resetTimerElement.textContent = `${resetTimeLeft}s`;
  if (resetTimeLeft <= 0) {
    clearInterval(resetTimer);
    resetTimerElement.style.display = 'none';
    resetSendButton.style.display = 'inline'; // Show button again
    resetSendButton.disabled = false;
    resetSendButton.textContent = 'Resend OTP';
  }
}, 1000);
<?php endif; ?>

// Open modal if forgot password process is active
<?php if (isset($_SESSION['reset_session']) && $_SESSION['reset_session'] && ($reset_otp_sent_time || isset($_SESSION['reset_otp_verified']))): ?>
document.addEventListener('DOMContentLoaded', function() {
  openForgotPasswordModal();
  
  // Show correct form based on current step
  <?php if (isset($_SESSION['reset_otp_verified']) && $_SESSION['reset_otp_verified']): ?>
    // Show password form after OTP verification
    document.getElementById('resetOtpForm').style.display = 'none';
    document.getElementById('resetEmailForm').style.display = 'none';
    document.getElementById('resetPasswordForm').style.display = 'block';
  <?php elseif ($reset_otp_sent_time): ?>
    // Show email and OTP forms together
    document.getElementById('resetEmailForm').style.display = 'flex';
    document.getElementById('resetOtpForm').style.display = 'flex';
    document.getElementById('resetPasswordForm').style.display = 'none';
  <?php endif; ?>
});
<?php endif; ?>

// Show success message after password reset
<?php if (isset($_SESSION['password_reset_success']) && $_SESSION['password_reset_success']): ?>
document.addEventListener('DOMContentLoaded', function() {
  openForgotPasswordModal();
  document.getElementById('resetOtpForm').style.display = 'none';
  document.getElementById('resetEmailForm').style.display = 'none';
  document.getElementById('resetPasswordForm').style.display = 'none';
  document.getElementById('resetSuccessMessage').style.display = 'block';
  
  // Clean up session data
  <?php 
  unset($_SESSION['reset_session']);
  unset($_SESSION['password_reset_success']);
  ?>
});
<?php endif; ?>
</script>

</body>
</html>



