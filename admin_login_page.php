<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Log-in | SkillSynergy</title>
    <style>
        * { margin: 0; padding: 0; }
        body {
            font-family: Georgia, serif;
            background-image: url('S3.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        header {
            width: 100vw;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
            background: transparent;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logo-text {
            color: #000;
            font-size: 2.2rem;
            font-weight: 800;
            text-align: center;
            margin: 32px 0 0 0;
            text-shadow: 0 2px 8px rgba(255,255,255,0.7), 0 1px 2px rgba(0,0,0,0.08);
            background: rgba(255,255,255,0.85);
            display: inline-block;
            padding: 16px 36px;
            border-radius: 18px;
            transition: background 0.3s, color 0.3s;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .logo-text:hover {
            background:rgb(71, 75, 188);
            color: #fff;
            cursor: pointer;
        }
        .center-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            width: 100vw;
        }
        .login-box {
            position: relative;
            top: 0;
            left: 0;
            transform: none;
            margin: 0 auto;
            width: 600px;
            max-width: 95vw;
            padding: 40px;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 15px 25px rgba(0,0,0,.18);
            border-radius: 16px;
            backdrop-filter: blur(6px);
        }
        .login-box p:first-child {
            margin: 0 0 20px;
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            color:rgb(86, 86, 214);
        }
        .user-box {
            position: relative;
            margin-bottom: 25px;
        }
        .user-box input {
            width: 100%;
            padding: 10px 0;
            font-size: 16px;
            color:rgb(9, 9, 9);
            border: none;
            border-bottom: 1px solid #2f002c;
            background: transparent;
            outline: none;
        }
        .user-box label {
            position: absolute;
            top: 10px;
            left: 0;
            color:rgb(98, 111, 231);
            pointer-events: none;
            transition: .5s;
        }
        .user-box input:focus~label,
        .user-box input:valid~label {
            top: -20px;
            font-size: 12px;
            color:rgb(106, 132, 220);
        }
        button {
            background:rgb(115, 158, 238);
            color: #fff;
            padding: 10px 20px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        button:hover { background:rgb(7, 7, 7); }
        .signup-link {
            display: block;
            text-align: right;
            margin-top: 15px;
            color: #2d0252;
            text-decoration: none;
        }
        .signup-link:hover { text-decoration: underline; }
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
        @media (max-width: 700px) {
            .login-box {
                width: 95vw;
                padding: 18px 6vw;
            }
            .logo-text {
                font-size: 1.3rem;
                margin-top: 18px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
<header>
    <div class="logo-text">Admin Panel Log-in - SkillSynergy System</div>
</header>

<div class="center-container">
    <section>
        <div class="login-box">
            <p>Log-in</p>

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

            <form action="admin_login.php" method="post">
                <div class="user-box">
                    <input required type="email" name="email"
                           pattern="^[a-zA-Z0-9._%+-]+@plm\.edu\.ph$"
                           title="Only PLM email addresses allowed (e.g. example@plm.edu.ph)">
                    <label>Email</label>
                </div>
                <div class="user-box">
                    <input required type="password" name="password" id="password" style="width:100%;padding-right:38px;">
                    <label for="password">Password</label>
                    <span id="togglePassword" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#888;font-size:1.2em;">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
                <button type="submit" name="login_user">Login</button>
            </form>
            <a class="signup-link" href="admin_login_page.php"></a>
        </div>
    </section>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    var pwd = document.getElementById('password');
    var icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
</script>
</body>
</html>
