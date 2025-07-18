<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQs</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
     body {
      min-height: 100vh;
      background: linear-gradient(to bottom right, #cce7ff, #e2e2ff);
      background-image: url('S3.jpg');
      background-repeat: no-repeat;
      background-size: 100% 100%;
      background-position: center;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
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
    

    .faq-container {
      background: rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(12px);
       -webkit-backdrop-filter: blur(12px);
      border-radius: 20px;
      padding: 20px;
      box-shadow:
        5px 5px rgba(0, 0, 0, 0.4),
        -3px -5px rgba(255, 255, 255, 0.8);
      width: 1100px;
      margin-top: 30px;
      margin-left: 300px;
      height: 600px; /* <- Set the height */
      overflow-y: auto; /* <- Make scrollable inside */
    }

    .faq-container::-webkit-scrollbar {
      width: 8px;
    }

    .faq-container::-webkit-scrollbar-thumb {
      background-color: #007BFF;
      border-radius: 8px;
    }

    .faq-title {
      font-size: 28px;
      margin-bottom: 20px;
      text-align: center;
    }

    .faq-item {
      border-bottom: 1px solid #ccc;
    }

   .faq-question {
      position: relative;
      padding: 15px 40px 15px 20px;
      background: rgba(255,255,255,0.7);
      border-radius: 12px;
      margin-bottom: 10px;
      font-size: 18px;
      font-weight: 600;
      cursor: pointer;
    }

    .faq-question .dropdown-icon {
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 22px;
      transition: transform 0.3s ease;
      color: #007BFF;
    }

    /* Optional rotation effect */
    .faq-question.active .dropdown-icon {
      transform: translateY(-50%) rotate(180deg);
    }

    .faq-answer {
      padding: 10px 20px;
      background: rgba(255,255,255,0.5);
      border-radius: 8px;
      font-size: 16px;
      display: none;
      margin-bottom: 15px;
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
      margin-top: 10px;
      
      
    }

    .back-btn:hover {
      background: #00b4d8;
      transform: scale(1.05);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
      .sidebar {
        left: 20px;
        width: 200px;
      }
      
      .main-content {
        padding: 30px 30px;
        margin-left: 220px;
      }
      
      .back-btn {
        margin-left: 220px;
      }
    }

    @media (max-width: 768px) {
      body {
        background-size: cover;
        background-position: center;
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
      
      .main-content {
        margin-left: 0;
        padding: 20px 15px;
        width: 100%;
      }
      
      .back-btn {
        margin-left: 15px;
        margin-top: 20px;
        font-size: 14px;
        padding: 10px 20px;
      }
      
      .faq-container {
        padding: 15px;
        margin-top: 20px;
      }
      
      .faq-title {
        font-size: 24px;
        margin-bottom: 20px;
      }
      
      .faq-question {
        font-size: 16px;
        padding: 15px;
      }
      
      .faq-answer {
        font-size: 14px;
        padding: 8px 15px;
      }
      
      .profile-dropdown {
        right: 20px;
        top: 15px;
      }
    }

    @media (max-width: 480px) {
      .main-content {
        padding: 15px 10px;
      }
      
      .back-btn {
        margin-left: 10px;
        font-size: 12px;
        padding: 8px 16px;
      }
      
      .faq-container {
        padding: 12px;
      }
      
      .faq-title {
        font-size: 20px;
        margin-bottom: 15px;
      }
      
      .faq-question {
        font-size: 14px;
        padding: 12px;
      }
      
      .faq-answer {
        font-size: 13px;
        padding: 6px 12px;
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
      .faq-title {
        font-size: 18px;
      }
      
      .faq-question {
        font-size: 13px;
        padding: 10px;
      }
      
      .faq-answer {
        font-size: 12px;
        padding: 5px 10px;
      }
    }
  </style>
</head>
<body> 
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
  
  <a href="findmatch.php" class="<?= $current_page == 'find_match.php' ? 'active' : '' ?>" title="Find Match">
    <i class="fas fa-search"></i> Find Match
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

  

<div class="faq-container">
  <div class="faq-title">Frequently Asked Questions</div>

  <div class="faq-item">
    <div class="faq-question">What is this website about?
      <i class="fas fa-square-caret-down dropdown-icon"></i>
    </div> 
    <div class="faq-answer" style="display: none;">
      This website is a Skillsynergy platform where students can share and offer skills, collaborate, and learn from each other.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">How do I create an account?
      <i class="fas fa-square-caret-down dropdown-icon"></i>
    </div>
    <div class="faq-answer" style="display: none;">
      Click on the Sign Up button, Verify first in your PLM email to get started, Login your account and fill the required information in the Profile Setup.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">Is the platform free?
      <i class="fas fa-square-caret-down dropdown-icon"></i>
    </div>
    <div class="faq-answer" style="display: none;">
      Yes, all features are free to use for PLM CISTM registered students.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">How do I join a skill-sharing session?
      <i class="fas fa-square-caret-down dropdown-icon"></i>
    </div>
    <div class="faq-answer" style="display: none;">
      After completing your skill assessment, you can use the Find Match to view and join available sessions.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">Can I update my profile after registration?
      <i class="fas fa-square-caret-down dropdown-icon"></i>
    </div>
    <div class="faq-answer" style="display: none;">
      Absolutely. You can edit your profile anytime through the dashboard.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">What is the leaderboard for?
      <i class="fas fa-square-caret-down dropdown-icon"></i>
    </div>
    <div class="faq-answer" style="display: none;">
      The leaderboard tracks your session activity, skill ratings, and completed assessments to show your rank among other students.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">Do I need to take initial skill assessment?
      <i class="fas fa-square-caret-down dropdown-icon"></i>
    </div>
    <div class="faq-answer" style="display: none;">
      Yes. The initial skill assessment is required because it helps to match you with other students at a different skill level and offer that recommends relevant sessions.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">What happens after a session ends?
      <i class="fas fa-square-caret-down dropdown-icon"></i>
    </div>
    <div class="faq-answer" style="display: none;">
      Both users give ratings. You can then choose to take another assessment, find a new match, or start another session.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">Who can I contact for support?
      <i class="fas fa-square-caret-down dropdown-icon"></i>
    </div>
    <div class="faq-answer" style="display: none;">
      You can reach out to our support team through the Contact Us page or via email.
    </div>
  </div>
</div>

<script>
  const questions = document.querySelectorAll('.faq-question');

  questions.forEach(question => {
    question.addEventListener('click', () => {
      const icon = question.querySelector('.dropdown-icon');
      const answer = question.nextElementSibling;

      // Toggle active class for styling
      question.classList.toggle('active');

      // Toggle dropdown icon (down ↔ up)
      icon.classList.toggle('fa-square-caret-down');
      icon.classList.toggle('fa-square-caret-up');

      // Toggle answer visibility
      if (answer.style.display === "block") {
        answer.style.display = "none";
      } else {
        answer.style.display = "block";
      }
    });
  });
</script>


</script>


</body>
</html>
