<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Load questions JSON
$questionsData = json_decode(file_get_contents('questions.json'), true);

// Check for skill selections in session
if (!isset($_SESSION['categories']) || !isset($_SESSION['specific_skills'])) {
    die("<h2 style='color:red; text-align:center;'>No skill selections found. Please start from the assessment page.</h2>");
}

$categories = $_SESSION['categories'];
$specificSkills = $_SESSION['specific_skills'];

$selectedCategory = $categories[0];
$selectedSkill = $specificSkills[0];

if (!isset($questionsData[$selectedCategory][$selectedSkill])) {
    die("<h2 style='color:red; text-align:center;'>Invalid category or skill selected. No questions found.</h2>");
}

$multipleChoiceQuestions = $questionsData[$selectedCategory][$selectedSkill];
$codeOutputQuestions = [];

if (isset($questionsData[$selectedCategory][$selectedSkill . '_CodeOutputs'])) {
    $codeOutputQuestions = $questionsData[$selectedCategory][$selectedSkill . '_CodeOutputs'];
}

// If submitted — process answers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $total = 0;

    if (isset($_POST['answers'])) {
        foreach ($multipleChoiceQuestions as $index => $q) {
            if (isset($_POST['answers'][$index]) && $_POST['answers'][$index] == $q['answer']) {
                $score++;
            }
        }
        $total += count($multipleChoiceQuestions);
    }

    if (isset($_POST['code_outputs'])) {
        foreach ($codeOutputQuestions as $i => $cq) {
            if (isset($_POST['code_outputs'][$i]) && strtolower(trim($_POST['code_outputs'][$i])) == strtolower($cq['answer'])) {
                $score++;
            }
        }
        $total += count($codeOutputQuestions);
    }

    $proficiency = ($score >= 21) ? "Advanced" : (($score >= 11) ? "Intermediate" : "Beginner");

    // Check if skill exists in skills_offer
    $stmt = $conn->prepare("SELECT skills_id FROM skills_offer WHERE user_id = ? AND category = ? AND specific_skill = ? LIMIT 1");
    if (!$stmt) { die("Prepare failed (skills_offer check): " . $conn->error); }
    $stmt->bind_param("iss", $user_id, $selectedCategory, $selectedSkill);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $insertSkill = $conn->prepare("INSERT INTO skills_offer (user_id, category, specific_skill) VALUES (?, ?, ?)");
        if (!$insertSkill) { die("Prepare failed (skills_offer insert): " . $conn->error); }
        $insertSkill->bind_param("iss", $user_id, $selectedCategory, $selectedSkill);
        $insertSkill->execute();
        $skills_id = $insertSkill->insert_id;
        $insertSkill->close();
    } else {
        $row = $result->fetch_assoc();
        $skills_id = $row['skills_id'];
    }
    $stmt->close();

    // Insert result into initial_assessment
    $stmt = $conn->prepare("INSERT INTO initial_assessment 
        (user_id, skills_id, category, skill, score, total_items, proficiency)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) { die("Prepare failed (initial_assessment insert): " . $conn->error); }
    $stmt->bind_param("iissiis", $user_id, $skills_id, $selectedCategory, $selectedSkill, $score, $total, $proficiency);
    $stmt->execute();
    $stmt->close();

    // Show result screen
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Assessment Result</title>
        <style>
            body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; text-align: center; padding: 50px; }
            .result-box {
                max-width: 500px;
                margin: 0 auto;
                background: #fff;
                border-radius: 10px;
                padding: 30px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            }
            h2 { color: #007bff; }
            p { font-size: 18px; margin: 12px 0; }
            a.button {
                display: inline-block;
                margin-top: 20px;
                padding: 12px 28px;
                background: #007bff;
                color: #fff;
                border-radius: 8px;
                text-decoration: none;
                font-weight: bold;
            }
            a.button:hover { background: #0056b3; }
        </style>
    </head>
    <body>
    <div class="result-box">
        <h2>✅ Assessment Result</h2>
        <p><strong>Category:</strong> <?= htmlspecialchars($selectedCategory) ?></p>
        <p><strong>Skill:</strong> <?= htmlspecialchars($selectedSkill) ?></p>
        <p><strong>Your Score:</strong> <?= $score ?> out of <?= $total ?></p>
        <p><strong>Proficiency:</strong> <?= $proficiency ?></p>
        <a href="profile_setup.php?refresh=1" class="button">Continue to Profile Setup</a>
    </div>
    </body>
    </html>
    <?php
    exit();
}

// ELSE — render assessment form
?>
<!DOCTYPE html>
<html>
<head>
  <title>Skill Assessment — <?= htmlspecialchars($selectedCategory) ?>: <?= htmlspecialchars($selectedSkill) ?></title>
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
    }
    .timer {
      font-size: 24px;
      font-weight: bold;
      color: #333;
      text-align: center;
      margin-bottom: 20px;
    }
    .assessment-content {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(15px);
      border-radius: 16px;
      margin: 20px auto;
      padding: 20px;
      max-width: 1000px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    }
    .question-box {
      background: rgba(255,255,255,0.35);
      border-radius: 20px;
      padding: 20px;
      margin-bottom: 25px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .question-box strong {
      font-size: 20px;
      display: block;
      margin-bottom: 12px;
    }
    button {
      padding: 12px 24px;
      font-size: 18px;
      border-radius: 8px;
      background: linear-gradient(45deg, #007bff, #00c6ff);
      color: #fff;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background: linear-gradient(45deg, #0056b3, #008cff);
    }
    input[type="text"] {
      padding: 10px;
      margin-top: 10px;
      width: 100%;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
  </style>
  <script>
    let totalTime = 1200;
    function startTimer() {
      const timerDisplay = document.getElementById('timer');
      const interval = setInterval(() => {
        let minutes = Math.floor(totalTime / 60);
        let seconds = totalTime % 60;
        timerDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        totalTime--;
        if (totalTime < 0) {
          clearInterval(interval);
          alert("Time's up!");
          document.getElementById('assessment-form').submit();
        }
      }, 1000);
    }
    window.onload = startTimer;
  </script>
</head>
<body>

<h3>🚀 Skill Assessment — <?= htmlspecialchars($selectedCategory) ?> : <?= htmlspecialchars($selectedSkill) ?></h3>
<div class="timer">⏳ Time Left: <span id="timer">20:00</span></div>

<div class="assessment-content">
  <form method="POST" id="assessment-form">

    <?php if (!empty($multipleChoiceQuestions)): ?>
      <h3>📌 Multiple Choice Questions</h3>
      <?php foreach ($multipleChoiceQuestions as $index => $q): ?>
        <div class="question-box">
          <strong><?= ($index+1).'. '.htmlspecialchars($q['question']) ?></strong>
          <?php foreach ($q['choices'] as $choice): ?>
            <label>
              <input type="radio" name="answers[<?= $index ?>]" value="<?= htmlspecialchars($choice) ?>" required>
              <?= htmlspecialchars($choice) ?>
            </label><br>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($codeOutputQuestions)): ?>
      <h3>📌 Code Output Questions</h3>
      <?php foreach ($codeOutputQuestions as $i => $cq): ?>
        <div class="question-box">
          <strong><?= ($i+1).'. '.nl2br(htmlspecialchars($cq['question'])) ?></strong>
          <input type="text" name="code_outputs[<?= $i ?>]" placeholder="Your answer here" required>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <button type="submit">Submit Answers</button>
  </form>
</div>

</body>
</html>
