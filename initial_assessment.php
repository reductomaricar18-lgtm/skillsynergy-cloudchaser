<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Receive skill selections from POST
if (isset($_POST['category']) && isset($_POST['specific_skill'])) {
    $categories = $_POST['category'];
    $specific_skills = $_POST['specific_skill'];

    // Save them to session for continuity
    $_SESSION['categories'] = $categories;
    $_SESSION['specific_skills'] = $specific_skills;

    // Insert skill offers to database if not existing yet
    for ($i = 0; $i < count($categories); $i++) {
        $category = $conn->real_escape_string($categories[$i]);
        $skill = $conn->real_escape_string($specific_skills[$i]);

        // Check if already exists for this user
        $check = $conn->query("SELECT * FROM initial_assessment 
                               WHERE user_id = $user_id 
                               AND category = '$category' 
                               AND skill = '$skill'");

        if ($check->num_rows == 0) {
            $conn->query("INSERT INTO initial_assessment (user_id, category, skill) 
                          VALUES ($user_id, '$category', '$skill')");
        }
    }

} elseif (isset($_SESSION['categories']) && isset($_SESSION['specific_skills'])) {
    // No POST but already stored in session — retrieve them
    $categories = $_SESSION['categories'];
    $specific_skills = $_SESSION['specific_skills'];

} else {
    // Neither POST nor SESSION data exists — redirect back to skill selection
    header("Location: profile_setup.php?error=missing_skills");
    exit();
}

// Check if restore data is requested via GET
if (isset($_GET['restore_data']) && $_GET['restore_data'] == 1) {
    $_SESSION['profile-setup-form'] = $_SESSION['profile-setup-form'] ?? [];

    // Redirect back to profile setup
    header("Location: profile_setup.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Initial Skill Assessment Direction</title>
  <style>
    body {
      background: url('InitialAss.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
    }

    h3 {
      font-size: 30px;
      font-weight: 900;
      text-align: center;
      color: #007bff;
      margin-top: 30px;
      text-shadow: 1px 1px 2px #00000055;
      letter-spacing: 1px;
    }

    .assessment-content {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(15px);
      border-radius: 16px;
      border: 1px solid rgba(255, 255, 255, 0.18);
      margin: 10px auto;
      padding: 20px;
      width: 100%;
      max-width: 1310px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
      color: #333;
    }

    .assessment-body {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
    }

    .direction-text {
      font-size: 20px;
      font-weight: 600;
      text-align: justify;
      margin-top: 50px;
      margin-bottom: 30px;
      max-width: 1000px;
      background: rgba(255, 255, 255, 0.35);
      padding: 20px 30px;
      border-left: 6px solid #007bff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      line-height: 1.7;
    }

    .note-text {
      font-size: 15px;
      color: #333;
      margin-bottom: 35px;
      text-align: justify;
      max-width: 900px;
      background: rgba(255, 255, 255, 0.35);
      padding: 18px 30px;
      border-left: 6px solid #00c6ff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      line-height: 1.7;
    }

    .start-btn-container {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    a.start-btn {
      display: inline-block;
      padding: 10px 14px;
      font-size: 18px;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      background: linear-gradient(45deg, #007bff, #00c6ff);
      color: #fff;
      box-shadow: 0 4px 10px rgba(0, 123, 255, 0.4);
      text-align: center;
    }

    a.start-btn:hover {
      background: linear-gradient(45deg, #0056b3, #008cff);
      box-shadow: 0 6px 14px rgba(0, 123, 255, 0.5);
    }

    .assessment-footer {
      display: flex;
      justify-content: center;
      margin-top: 30px;
    }

    a.close-btn {
      display: inline-block;
      padding: 14px 32px;
      font-size: 18px;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      background: linear-gradient(45deg, #888, #aaa);
      color: #fff;
      box-shadow: 0 4px 10px rgba(100, 100, 100, 0.3);
      text-align: center;
    }

    a.close-btn:hover {
      background: linear-gradient(45deg, #666, #999);
      box-shadow: 0 6px 14px rgba(100, 100, 100, 0.5);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
      .assessment-content {
        max-width: 95%;
        margin: 10px auto;
      }
      
      .direction-text,
      .note-text {
        max-width: 90%;
      }
    }

    @media (max-width: 768px) {
      h3 {
        font-size: 24px;
        margin-top: 20px;
      }
      
      .assessment-content {
        max-width: 98%;
        padding: 15px;
        margin: 5px auto;
      }
      
      .direction-text {
        font-size: 16px;
        margin-top: 30px;
        margin-bottom: 20px;
        padding: 15px 20px;
        max-width: 95%;
      }
      
      .note-text {
        font-size: 14px;
        margin-bottom: 25px;
        padding: 15px 20px;
        max-width: 95%;
      }
      
      a.start-btn,
      a.close-btn {
        font-size: 16px;
        padding: 12px 20px;
      }
    }

    @media (max-width: 480px) {
      h3 {
        font-size: 20px;
        margin-top: 15px;
      }
      
      .assessment-content {
        padding: 12px;
        margin: 5px auto;
      }
      
      .direction-text {
        font-size: 14px;
        margin-top: 20px;
        margin-bottom: 15px;
        padding: 12px 15px;
        max-width: 100%;
      }
      
      .note-text {
        font-size: 13px;
        margin-bottom: 20px;
        padding: 12px 15px;
        max-width: 100%;
      }
      
      a.start-btn,
      a.close-btn {
        font-size: 14px;
        padding: 10px 16px;
      }
      
      .start-btn-container,
      .assessment-footer {
        margin-top: 20px;
      }
    }

    @media (max-width: 360px) {
      h3 {
        font-size: 18px;
      }
      
      .direction-text {
        font-size: 13px;
        padding: 10px 12px;
      }
      
      .note-text {
        font-size: 12px;
        padding: 10px 12px;
      }
      
      a.start-btn,
      a.close-btn {
        font-size: 13px;
        padding: 8px 14px;
      }
    }
  </style>
</head>
<body>

<h3>✨ Initial Skill Assessment ✨</h3>

<div class="assessment-content">

  <div class="assessment-body">

    <div class="direction-text">
      <p><strong>Direction:</strong> The first 20 items will be <strong>Multiple Choice Questions</strong>. The next 10 are <strong>coding-related questions</strong>. Kindly read each item carefully before selecting your answers.</p>
      <br>

      <div class="note-text">
        <p><strong>Note:</strong> Your assessment will be rated as follows:</p>
        <ul style="margin-left: 20px; margin-top: 10px; line-height: 1.6;">
          <li>21–30 points = <strong>Advanced</strong></li>
          <li>11–20 points = <strong>Intermediate</strong></li>
          <li>0–10 points = <strong>Beginner</strong></li>
        </ul>
      </div>

      <div class="note-text">
        <p><strong>You selected the following skills for initial assessment:</strong></p>
        <ul style="margin-left: 20px; margin-top: 10px; line-height: 1.6;">
          <?php
          for ($i = 0; $i < count($categories); $i++) {
            echo "<li>" . htmlspecialchars($categories[$i]) . " — " . htmlspecialchars($specific_skills[$i]) . "</li>";
          }
          ?>
        </ul>
      </div>

      <div class="start-btn-container">
        <a href="in_assessmentquestions.php" class="start-btn">🚀 Start Assessment</a>
      </div>  
    </div>
  </div>

  <div class="assessment-footer">
    <a href="profile_setup.php?cancel=1" class="close-btn">Cancel</a>
  </div>

</div>

</body>
</html>
