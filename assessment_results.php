<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli("localhost", "root", "", "sia1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if assessment results exist
if (!isset($_SESSION['assessment_results'])) {
    header("Location: lesson_assessment.php");
    exit();
}

$results = $_SESSION['assessment_results'];
$language = $results['language'];
$level = $results['level'];
$score = $results['score'];
$total = $results['total'];
$percentage = $results['percentage'];
$question_results = $results['results'];

// Check cooldown for retake
$can_retake = true;
$time_remaining = null;

// Check last attempt for this specific assessment
$check_sql = "SELECT attempt_time FROM assessment_attempts 
              WHERE user_id = ? AND language = ? AND level = ? 
              ORDER BY attempt_time DESC LIMIT 1";
$check_stmt = $conn->prepare($check_sql);
if ($check_stmt) {
    $check_stmt->bind_param("iss", $user_id, $language, $level);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_attempt_time = new DateTime($row['attempt_time']);
        $current_time = new DateTime();
        $time_diff = $current_time->diff($last_attempt_time);
        
        // Calculate hours since last attempt
        $hours_since_last = ($time_diff->days * 24) + $time_diff->h + ($time_diff->i / 60);
        
        if ($hours_since_last < 42) {
            $can_retake = false;
            $time_remaining = 42 - $hours_since_last;
        }
    }
    $check_stmt->close();
}

$conn->close();

// Clear results from session after displaying
unset($_SESSION['assessment_results']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Results - SkillSynergy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .results-container {
            max-width: 700px;
            margin: 40px auto 0 auto;
            background: rgba(255,255,255,0.95);
            border-radius: 30px;
            box-shadow: 0 10px 40px rgba(102,126,234,0.15), 0 2px 8px rgba(76,81,191,0.08);
            padding: 40px 30px 30px 30px;
            position: relative;
        }
        .results-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .back-button {
            position: absolute;
            left: 30px;
            top: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 0.98rem;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(102,126,234,0.12);
            transition: background 0.2s, color 0.2s;
        }
        .back-button:hover {
            background: #fff;
            color: #667eea;
            border: 2px solid #667eea;
        }
        .score-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            margin-bottom: 35px;
        }
        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: conic-gradient(#667eea <?= $percentage ?>%, #e2e8f0 0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(102,126,234,0.18);
            position: relative;
            animation: popIn 0.7s cubic-bezier(.68,-0.55,.27,1.55);
        }
        @keyframes popIn {
            0% { transform: scale(0.7); opacity: 0; }
            80% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); }
        }
        .score-text {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
            text-shadow: 0 2px 8px #e2e8f0;
        }
        .score-details {
            text-align: left;
        }
        .score-details h2 {
            font-size: 1.5rem;
            color: #764ba2;
            margin-bottom: 8px;
        }
        .score-details p {
            font-size: 1.1rem;
            color: #4a5568;
            margin-bottom: 10px;
        }
        .grade {
            font-size: 1.2rem;
            font-weight: 600;
            color: #10b981;
            margin-top: 8px;
            letter-spacing: 1px;
        }
        .results-content {
            margin-top: 20px;
        }
        .results-summary {
            background: linear-gradient(135deg, #f0f4ff 0%, #e6f3ff 100%);
            border-radius: 20px;
            padding: 35px 25px 25px 25px;
            margin-bottom: 30px;
            border-left: 7px solid #667eea;
            box-shadow: 0 4px 18px rgba(102,126,234,0.08);
        }
        .summary-title {
            font-size: 1.6rem;
            color: #764ba2;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
        }
        .summary-stats {
            display: flex;
            gap: 30px;
            justify-content: center;
            margin-bottom: 10px;
        }
        .stat-item {
            background: #fff;
            padding: 22px 18px;
            border-radius: 14px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(102,126,234,0.07);
            border: 2px solid #e2e8f0;
            min-width: 120px;
            transition: transform 0.2s;
        }
        .stat-item:hover {
            transform: translateY(-4px) scale(1.04);
            box-shadow: 0 6px 24px rgba(102,126,234,0.13);
        }
        .stat-number {
            font-size: 2.1rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 6px;
            text-shadow: 0 1px 4px #e2e8f0;
        }
        .stat-label {
            color: #4a5568;
            font-size: 1rem;
            font-weight: 500;
        }
        .question-results {
            margin-top: 30px;
        }

        .question-result {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .question-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .question-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        .question-icon.correct {
            background: #10b981;
        }

        .question-icon.incorrect {
            background: #ef4444;
        }

        .question-text {
            font-size: 1.1rem;
            color: #2d3748;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .answer-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }

        .answer-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .answer-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .answer-label {
            font-weight: 600;
            color: #4a5568;
        }

        .answer-value {
            color: #2d3748;
            max-width: 60%;
            word-wrap: break-word;
        }

        .performance-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .performance-item {
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .performance-item.correct {
            border-color: #10b981;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        }

        .performance-item.incorrect {
            border-color: #ef4444;
            background: linear-gradient(135deg, #fef2f2, #fecaca);
        }

        .performance-item i {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .performance-item.correct i {
            color: #10b981;
        }

        .performance-item.incorrect i {
            color: #ef4444;
        }

        .performance-number {
            display: block;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .performance-item.correct .performance-number {
            color: #10b981;
        }

        .performance-item.incorrect .performance-number {
            color: #ef4444;
        }

        .performance-label {
            color: #4a5568;
            font-size: 1rem;
            font-weight: 500;
        }

        .summary-note {
            background: #e6fffa;
            border: 1px solid #38b2ac;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .summary-note i {
            font-size: 1.5rem;
            color: #38b2ac;
        }

        .summary-note p {
            color: #2c7a7b;
            font-size: 1rem;
            line-height: 1.6;
            margin: 0;
        }

        .cooldown-notice {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 12px;
            padding: 15px 30px;
            margin: 0 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #92400e;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .cooldown-notice i {
            font-size: 1.2rem;
        }

        .btn-disabled {
            background: #cbd5e0 !important;
            color: #718096 !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
            border: 2px solid #e2e8f0 !important;
            opacity: 0.7;
        }

        .btn-disabled:hover {
            transform: none !important;
            box-shadow: none !important;
            background: #cbd5e0 !important;
        }

        .actions {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-top: 1px solid #e2e8f0;
        }

        .action-btn {
            display: inline-block;
            padding: 15px 30px;
            margin: 0 10px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        }

        @media (max-width: 800px) {
            .results-container {
                padding: 20px 5px 15px 5px;
            }
            .score-section {
                flex-direction: column;
                gap: 18px;
            }
            .results-summary {
                padding: 18px 8px 12px 8px;
            }
            .summary-stats {
                flex-direction: column;
                gap: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="results-container">
        <div class="results-header">
            <a href="lesson_assessment.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
            <h1>Assessment Results</h1>
            <p><?= ucfirst($language) ?> <?= ucfirst($level) ?> Level</p>
        </div>

        <div class="score-section">
            <div class="score-circle">
                <div class="score-text"><?= $percentage ?>%</div>
            </div>
            <div class="score-details">
                <h2>Your Score</h2>
                <p><?= $score ?> out of <?= $total ?> questions correct</p>
                <div class="grade">
                    <?php 
                    if ($percentage >= 80) echo 'Excellent! 🎉';
                    elseif ($percentage >= 60) echo 'Good Job! 👍';
                    else echo 'Keep Practicing! 💪';
                    ?>
                </div>
            </div>
        </div>

        <div class="results-content">
            <div class="results-summary">
                <h3 class="summary-title">
                    <i class="fas fa-chart-bar"></i>
                    Assessment Summary
                </h3>
                <div class="summary-stats">
                    <div class="stat-item">
                        <div class="stat-number"><?= $score ?></div>
                        <div class="stat-label">Correct Answers</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $total - $score ?></div>
                        <div class="stat-label">Incorrect Answers</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $percentage ?>%</div>
                        <div class="stat-label">Success Rate</div>
                    </div>
                </div>
            </div>

            <!-- Removed Performance Summary section -->
        </div>

        <div class="actions">
            <?php if ($can_retake): ?>
                <a href="assessment_system.php?language=<?= urlencode($language) ?>&level=<?= urlencode($level) ?>" class="action-btn btn-primary">
                    <i class="fas fa-redo"></i> Retake Assessment
                </a>
            <?php else: ?>
                <button class="action-btn btn-disabled" disabled>
                    <i class="fas fa-clock"></i> Retake Assessment (<?= round($time_remaining, 1) ?>h remaining)
                </button>
                <!-- Debug info for admin/testing -->
                <!-- Debug info removed -->
            <?php endif; ?>
            <a href="lesson_assessment.php" class="action-btn btn-secondary">
                <i class="fas fa-home"></i> Back
            </a>
        </div>
    </div>
</body>
</html> 