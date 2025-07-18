<?php
session_start();

// Simulate a total of 2 questions from previous assessment
$totalQuestions = 2;

// Retrieve answers from session
$answers = $_SESSION['assessment_answers'] ?? [];

// Simple correct answer key
$answerKey = [
  1 => 'A. 10',
  2 => 'B. <style>'
];

// Calculate score
$score = 0;
foreach ($answerKey as $questionId => $correctAnswer) {
  if (isset($answers[$questionId]) && $answers[$questionId] === $correctAnswer) {
    $score++;
  }
}

// Determine proficiency
$percentage = ($score / $totalQuestions) * 100;

if ($percentage < 50) {
  $proficiency = "Beginner";
} elseif ($percentage < 80) {
  $proficiency = "Intermediate";
} else {
  $proficiency = "Advanced";
}

// Save proficiency in session (simulate database storage)
$_SESSION['assessment_proficiency'] = $proficiency;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assessment Result</title>
  <style>
body {
  background: #f0f2f5;
  font-family: 'Segoe UI', sans-serif;
  text-align: center;
  padding: 60px;
}
.result-card {
  display: inline-block;
  background: white;
  border-radius: 14px;
  padding: 40px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}
.result-card h2 {
  font-size: 32px;
  margin-bottom: 20px;
}
.result-card p {
  font-size: 20px;
  margin: 10px 0;
}
.result-card .proficiency {
  font-size: 28px;
  font-weight: 700;
  color: #007bff;
  margin-top: 30px;
}
.result-card a {
  display: inline-block;
  margin-top: 20px;
  padding: 12px 24px;
  text-decoration: none;
  background: #007bff;
  color: white;
  border-radius: 8px;
}
.result-card a:hover {
  background: #0056b3;
}
</style>
</head>
<body>

<div class="result-card">
  <h2>Assessment Completed</h2>
  <p><strong>Total Score:</strong> <?= $score ?>/<?= $totalQuestions ?></p>
  <p><strong>Percentage:</strong> <?= number_format($percentage, 2) ?>%</p>
  <div class="proficiency">Proficiency: <?= $proficiency ?></div>
  <a href="profile_setup.php">Proceed to Profile Setup</a>
</div>

</body>
</html>