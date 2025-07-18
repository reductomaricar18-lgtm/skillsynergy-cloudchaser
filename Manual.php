<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MANUAL</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
  min-height: 100vh;
  background: linear-gradient(to bottom right, #cce7ff, #e2e2ff);
  background-image: url('S3.jpg');
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  font-family: 'Segoe UI', sans-serif;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-top: 100px; /* leave room for sidebar and back button */
  overflow: hidden;   /* prevent page scroll if manual-container scrolls */
}


    .sidebar {
      position: fixed;
      top: 80px;
      left: 75px;
      width: 230px;
      height: 80vh;
      background: rgba(206, 204, 204, 0.7);
      backdrop-filter: blur(10px);
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px 0;
      border-radius: 20px;
      box-shadow:
        5px 5px rgba(0, 0, 0, 0.4),
        -3px -5px rgba(255, 255, 255, 0.8);
    }

    .logo img {
      width: 100%;
      height: 100%;
      margin-top: 30px;
      object-fit: cover;
    }

    .sidebar a {
      color: #000;
      font-size: 20px;
      margin-top: 50px;
      margin: 10px 10px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 20px;
      width: 180px;
      border-radius: 12px;
      transition: background 0.3s, color 0.3s;
    }

    .sidebar a i {
      font-size: 22px;
    }

    .sidebar a:hover {
      background: #007BFF;
      color: #fff;
    }

    .sidebar a.active {
      position: relative;
      font-weight: bold;
      color: #007BFF;
    }

    .sidebar a.active::after {
      content: "";
      position: absolute;
      bottom: 5px;
      left: 20px;
      width: 80%;
      height: 3px;
      background-color: #007BFF;
      border-radius: 5px;
    }

    .profile-dropdown {
      position: fixed;
      top: 20px;
      right: 80px;
      text-align: center;
    }

    .profile-container {
      position: relative;
      width: 50px;
      height: 50px;
      cursor: pointer;
    }

    .profile-icon {
      width: 45px;
      height: 45px;
      background: #004466;
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      transition: 0.8s;
    }

    .profile-icon:hover {
      background: #007BFF;
    }

    .arrow-icon {
      position: absolute;
      bottom: 0px;
      right: 0px;
      background-color:rgb(7, 0, 0);
      color:rgb(12, 105, 199);
      border-radius: 50%;
      width: 16px;
      height: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      margin-top: 10px;
      background:rgba(218, 214, 214, 0.88);
      min-width: 120px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      border-radius: 8px;
      overflow: hidden;
      z-index: 1;
      box-shadow:
      5px 5px rgba(0, 0, 0, 0.4),    /* bottom-right black shadow */
      -3px -5px rgba(255, 255, 255, 0.8); /* top-left white glow */  
    }

    .dropdown-content a {
      padding: 12px 16px;
      display: block;
      text-decoration: none;
      color: #333;
      font-weight: bold;
    }

    .dropdown-content a:hover {
      background-color: #ddd;
    }

    .profile-dropdown:hover .dropdown-content {
      display: block;
    }

    .main-content {
      flex: 1;
      padding: 40px 50px;
      overflow-y: auto;
      width: 100vh;
      box-sizing: border-box;
    }

    .header {
      display: flex;
      justify-content: flex-end;
      align-items: center;
    }

    .profile-pic img {
      width: 65px;
      height: 65px;
      border-radius: 50%;
      border: 3px solid #fff;
      box-shadow: 0 0 8px rgba(0,0,0,0.2);
    }
    
.manual-container {
  background: rgba(255, 255, 255, 0.5);
  padding: 25px;
  margin: 0 auto;
  border-radius: 15px;
  width: 110%;
  font-size: 15px;
  line-height: 1.6;
  backdrop-filter: blur(8px);
  box-shadow: 5px 5px rgba(0, 0, 0, 0.2), -3px -3px rgba(255, 255, 255, 0.7);

  max-height: 600px;
  overflow-y: auto;
}

.main-wrapper {
  flex: 1;
  width: 100%;
  display: flex;
  justify-content: center;
  padding: 20px;
  box-sizing: border-box;
  margin-left: 110px;
}


   .tab-buttons {
  display: flex;
  justify-content: center;
  margin: 30px auto 10px;
  gap: 10px;
}

.tab-btn {
  padding: 10px 25px;
  font-size: 16px;
  border-radius: 20px;
  border: none;
  background: rgba(180, 180, 255, 0.4);
  color: #333;
  cursor: pointer;
  transition: background 0.3s, color 0.3s;
}

.tab-btn.active {
  background: #007BFF;
  color: white;
}

.tab-content {
  display: none;
}

.tab-content.active {
  display: block;
}

    .back-btn {
      padding: 12px 25px;
      background: #0077b6;
      color: #fff;
      border: none;
      border-radius: 12px;
      text-decoration: none;
      font-size: 16px;
      transition: background 0.3s, transform 0.3s;
      margin-left: 1200px;
      margin-top: -60px; 

      
    }

    .back-btn:hover {
      background: #00b4d8;
      transform: scale(1.05);
    }
    .manual-btn {
  padding: 12px 25px;
  background: #0077b6;
  color: white; /* White text */
  border: none;
  border-radius: 12px;
  font-size: 18px;
  font-weight: bold;
  cursor: not-allowed; /* Change cursor to indicate disabled */
  text-align: center;
  width: 300px;;
  transition: all 0.3s ease;
  margin: 20px 0;
  display: block;
  text-align: center;
  margin-right: 500px;
   margin-top: -30px;
}

.manual-btn:disabled {
  background-color: #0097d1; /* Lighter blue background for disabled state */
  color: #f0f0f0; /* Lighter text color */
}

/* Responsive Design */
@media (max-width: 1200px) {
  .sidebar {
    left: 20px;
    width: 200px;
  }
  
  .main-wrapper {
    margin-left: 220px;
    padding: 15px;
  }
  
  .back-btn {
    margin-left: 220px;
  }
  
  .manual-btn {
    margin-right: 200px;
  }
}

@media (max-width: 768px) {
  body {
    background-size: cover;
    background-position: center;
    padding-top: 80px;
  }
  
  .sidebar {
    position: fixed;
    top: 0;
    left: -100%;
    width: 250px;
    height: 100vh;
    z-index: 1000;
    transition: left 0.3s ease;
  }
  
  .sidebar.active {
    left: 0;
  }
  
  .main-wrapper {
    margin-left: 0;
    padding: 10px;
    width: 100%;
  }
  
  .back-btn {
    margin-left: 15px;
    margin-top: -40px;
    font-size: 14px;
    padding: 10px 20px;
  }
  
  .manual-btn {
    margin-right: 0;
    width: 250px;
    font-size: 16px;
    margin-top: -20px;
  }
  
  .manual-container {
    padding: 20px;
    width: 100%;
    font-size: 14px;
    max-height: 500px;
  }
  
  .profile-dropdown {
    right: 20px;
    top: 15px;
  }
}

@media (max-width: 480px) {
  .main-wrapper {
    padding: 5px;
  }
  
  .back-btn {
    margin-left: 10px;
    font-size: 12px;
    padding: 8px 16px;
  }
  
  .manual-btn {
    width: 200px;
    font-size: 14px;
  }
  
  .manual-container {
    padding: 15px;
    font-size: 13px;
    max-height: 400px;
  }
  
  .manual-container h2 {
    font-size: 18px;
  }
  
  .manual-container ol {
    padding-left: 20px;
  }
  
  .manual-container li {
    margin-bottom: 10px;
  }
  
  .profile-dropdown {
    right: 15px;
    top: 10px;
  }
  
  .profile-icon {
    width: 40px;
    height: 40px;
    font-size: 16px;
  }
}

@media (max-width: 360px) {
  .manual-container {
    padding: 12px;
    font-size: 12px;
  }
  
  .manual-container h2 {
    font-size: 16px;
  }
  
  .manual-btn {
    width: 180px;
    font-size: 13px;
  }
  
  .back-btn {
    font-size: 11px;
    padding: 6px 12px;
  }
}
</style>
</head>
<body> 
    <button class="manual-btn" disabled>MANUAL</button>

    <a href="dashboard.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
    <?php
  $current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
  <div class="logo">
    <img src="logo-profilepage.jpg" alt="Logo">
  </div>
  <br><br><br>

  <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>" title="Home">
    <i class="fas fa-home"></i> Home
  </a>
  
  <a href="#" class="<?= $current_page == 'find_match.php' ? 'active' : '' ?>" title="Find Match">
    <i class="fas fa-search"></i> Find Match
  </a>
  
  <a href="lesson_assessment.php" class="<?= $current_page == 'lesson_assessment.php' ? 'active' : '' ?>" title="Lesson & Assessment">
    <i class="fas fa-book"></i> Lesson & Assessment
  </a>
  
  <a href="notificationtab.php" class="<?= $current_page == 'notificationtab.php' ? 'active' : '' ?>" title="Notifications">
    <i class="fas fa-bell"></i> Notification
  </a>
  
  <a href="matched_tab.php" class="<?= $current_page == 'matched_tab.php' ? 'active' : '' ?>" title="Matched Users">
    <i class="fas fa-user-friends"></i> Matched
  </a>
  
  <a href="message.php" class="<?= $current_page == 'message.php' ? 'active' : '' ?>" title="Messages">
    <i class="fas fa-comment-dots"></i> Message
  </a>
</div>


<div class="main-wrapper">
  <div id="manual" class="tab-content active"> <!-- keep it active -->
    <div class="manual-container">
     <h2 style="text-align: center;">How to Use the Skillsynergy Platform?</h2>
      <ol>
  <li>When you open the page, click the <strong>Getting Started</strong> button.</li>
  <li>You will be redirected to the <strong>Sign Up</strong> page. Begin by verifying your account using your official PLM email.</li>
  <li>After verification, create your password to complete the sign-up process.</li>
  <li>You will then be redirected to the <strong>Login</strong> page. Enter your PLM email and the password you just created.</li>
  <li>After logging in, you will proceed to the <strong>Profile Setup</strong> page. Fill in all required personal and academic information.</li>
  <li>Before clicking the <strong>Submit</strong> button, you must complete the <strong>Initial Skill Assessment</strong>.</li>
  <li>Click the <strong>Submit</strong> button once you're done with the profile and assessment.</li>
  <li>You will then land on the <strong>Home</strong> page, which displays the <strong>Leaderboard</strong>.</li>
  <li>Click the <strong>Find Match</strong> tab to browse and connect with other students.</li>
  <li>If you like a user and they like you back, you’ll both appear in the <strong>Notification</strong> tab.</li>
  <li>The <strong>Notification</strong> tab shows users you liked and those who want to learn with you.</li>
  <li>Once matched, proceed to the <strong>Matched</strong> tab where you can start a session with the other user.</li>
  <li>Click <strong>Start Session</strong> to proceed to the <strong>Message</strong> tab.</li>
  <li>Join skill-sharing sessions through real-time chat and file sharing.</li>
  <li>To end a session, click the menu option inside the chat box and select <strong>End Session</strong>.</li>
  <li>Rate the session afterward. Your points and ratings will reflect in the <strong>Leaderboard</strong>.</li>
  <li>Log out from the platform when you’re done.</li>
</ol>

    </div>
  </div>
</div>


<script>
    function showTab(tabId) {
      document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
      document.querySelector(`[onclick="showTab('${tabId}')"]`).classList.add('active');
      document.getElementById(tabId).classList.add('active');
    }
  </script>
</body>
