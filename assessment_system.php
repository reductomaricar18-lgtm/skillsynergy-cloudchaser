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

// Get language and level from URL parameters
$language = isset($_GET['language']) ? $_GET['language'] : '';
$level = isset($_GET['level']) ? $_GET['level'] : '';

// Validate parameters
if (empty($language) || empty($level)) {
    header("Location: lesson_assessment.php?error=invalid_assessment");
    exit();
}

// Check if user can take this assessment (42-hour cooldown)
$can_take_assessment = true;
$last_attempt_time = null;
$time_remaining = null;

// Create assessment_attempts table if it doesn't exist
$create_table_sql = "CREATE TABLE IF NOT EXISTS assessment_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    language VARCHAR(50) NOT NULL,
    level VARCHAR(50) NOT NULL,
    score INT NOT NULL DEFAULT 0,
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_language_level (user_id, language, level)
)";

$conn->query($create_table_sql);

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
            $can_take_assessment = false;
            $time_remaining = 42 - $hours_since_last;
        }
    }
    $check_stmt->close();
}

// Load assessments from JSON file
$assessments_data = [];
if (file_exists('assessments_bank.json')) {
    $assessments_data = json_decode(file_get_contents('assessments_bank.json'), true);
}

// Check if the requested assessment exists
if (!isset($assessments_data[$language][$level])) {
    header("Location: lesson_assessment.php?error=assessment_not_found");
    exit();
}

$assessment = $assessments_data[$language][$level];
$question_types = array_keys($assessment);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $max_score = 0;
    $results = [];
    $mc_count = isset($assessment['multipleChoice']) ? count($assessment['multipleChoice']) : 0;
    $dbg_count = isset($assessment['debugging']) ? count($assessment['debugging']) : 0;
    $coding_count = isset($assessment['coding']) ? count($assessment['coding']) : 0;

    // Set point values
    $points = [
        'multipleChoice' => 2,
        'debugging' => 8,
        'coding' => 8
    ];

    foreach ($question_types as $type) {
        if (isset($assessment[$type])) {
            foreach ($assessment[$type] as $index => $question_data) {
                $question_key = $type . '_' . $index;
                $earned = 0;
                $possible = $points[$type];
                $user_answer = isset($_POST[$question_key]) ? trim($_POST[$question_key]) : '';
                $correct_answer = isset($question_data['answer']) ? trim($question_data['answer']) : (isset($question_data['expected']) ? trim($question_data['expected']) : '');
                if ($type === 'multipleChoice') {
                    if ($user_answer === $correct_answer) {
                        $earned = $points[$type];
                    }
                } elseif ($type === 'debugging' || $type === 'coding') {
                    if (strcasecmp($user_answer, $correct_answer) === 0) {
                        $earned = $points[$type];
                    } elseif ($user_answer !== '' && stripos($user_answer, $correct_answer) !== false) {
                        $earned = round($points[$type] * 0.7); // partial credit
                    } else {
                        $earned = 0;
                    }
                }
                $score += $earned;
                $max_score += $possible;
                $results[] = [
                    'type' => $type,
                    'question' => $question_data['question'],
                    'user_answer' => $user_answer,
                    'correct_answer' => $correct_answer,
                    'is_correct' => $earned > 0,
                    'points_earned' => $earned,
                    'points_possible' => $possible
                ];
            }
        }
    }

    // Scale to 100 if needed
    $final_score = $max_score > 0 ? round(($score / $max_score) * 100, 2) : 0;

    // Calculate number of correct answers and total questions
    $correct_count = 0;
    $total_questions = 0;
    foreach ($results as $r) {
        $total_questions++;
        if ($r['is_correct']) $correct_count++;
    }
    $percentage = $total_questions > 0 ? round(($correct_count / $total_questions) * 100) : 0;

    // Record the attempt with score (after score is calculated)
    $record_sql = "INSERT INTO assessment_attempts (user_id, language, level, score) VALUES (?, ?, ?, ?)";
    $record_stmt = $conn->prepare($record_sql);
    if ($record_stmt) {
        $record_stmt->bind_param("issi", $user_id, $language, $level, $final_score);
        if (!$record_stmt->execute()) {
            error_log("[Assessment Insert Error] " . $record_stmt->error);
        }
        $record_stmt->close();
    } else {
        error_log("[Assessment Prepare Error] " . $conn->error);
    }
    
    // Store results in session
    $_SESSION['assessment_results'] = [
        'language' => $language,
        'level' => $level,
        'score' => $correct_count,
        'total' => $total_questions,
        'percentage' => $percentage,
        'results' => $results
    ];
    
    // Redirect to results page
    header("Location: assessment_results.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ucfirst($language) ?> <?= ucfirst($level) ?> Assessment - SkillSynergy</title>
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
            padding: 20px;
        }

        .assessment-container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .assessment-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .assessment-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .assessment-header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-5px);
        }

        .cooldown-message {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 15px;
            padding: 30px;
            margin: 40px;
            text-align: center;
        }

        .cooldown-icon {
            font-size: 4rem;
            color: #f59e0b;
            margin-bottom: 20px;
        }

        .cooldown-title {
            font-size: 2rem;
            color: #92400e;
            margin-bottom: 15px;
        }

        .cooldown-text {
            font-size: 1.1rem;
            color: #78350f;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .time-remaining {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            font-size: 1.2rem;
            font-weight: bold;
            color: #92400e;
        }

        .cooldown-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .assessment-content {
            padding: 40px;
        }

        .question-section {
            margin-bottom: 40px;
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            border-left: 5px solid #667eea;
        }

        .section-title {
            font-size: 1.8rem;
            color: #2d3748;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .question {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .question h3 {
            font-size: 1.3rem;
            color: #4a5568;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .question-code {
            background: #2d3748;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            overflow-x: auto;
        }

        .choices {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .choice {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .choice:hover {
            background: #edf2f7;
            border-color: #667eea;
        }

        .choice input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
        }

        .choice input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
        }

        .choice input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .submit-section {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-top: 1px solid #e2e8f0;
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .progress-bar {
            background: #e2e8f0;
            height: 8px;
            border-radius: 4px;
            margin: 20px 0;
            overflow: hidden;
        }

        .progress-fill {
            background: linear-gradient(90deg, #667eea, #764ba2);
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
        }

        /* New styles for multiple choice questions */
        .mcq-question {
            font-size: 1.3rem;
            color: #4a5568;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .mcq-choices {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .mcq-choice {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mcq-choice:hover {
            background: #edf2f7;
            border-color: #667eea;
        }

        .mcq-choice input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
        }

        .mcq-choice label {
            flex: 1;
            font-size: 1rem;
        }

        .code-block {
            background: #2d3748;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .assessment-container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .assessment-content {
                padding: 20px;
            }
            
            .assessment-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="assessment-container">
        <div class="assessment-header">
            <a href="lesson_assessment.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Back 
            </a>
            <h1><?= ucfirst($language) ?> <?= ucfirst($level) ?> Assessment</h1>
            <p>Test your knowledge with multiple question types</p>
            <?php if ($can_take_assessment): ?>
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!$can_take_assessment): ?>
        <div class="cooldown-message">
            <div class="cooldown-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h2 class="cooldown-title">Assessment Locked</h2>
            <p class="cooldown-text">
                This assessment is temporarily locked. You must wait 42 hours between attempts 
                to ensure fair practice and prevent spam.
            </p>
            <div class="time-remaining">
                <i class="fas fa-hourglass-half"></i>
                <strong>Assessment unlocks in: <?= round($time_remaining, 1) ?> hours</strong>
            </div>
            <!-- Debug info for admin/testing -->
            <!-- Debug info removed -->
            <p class="cooldown-text">
                <i class="fas fa-info-circle"></i>
                You can still take other assessments or review your previous results while waiting.
            </p>
            <div class="cooldown-actions">
                <a href="lesson_assessment.php" class="submit-btn" style="text-decoration: none; display: inline-block; margin: 10px;">
                    <i class="fas fa-home"></i> Back 
                </a>
                <a href="dashboard.php" class="submit-btn" style="text-decoration: none; display: inline-block; margin: 10px; background: linear-gradient(135deg, #6c757d, #495057);">
                    <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                </a>
            </div>
        </div>
        <?php else: ?>
        <form method="POST" class="assessment-content">
            <?php 
            $question_count = 0;
            $total_questions = 0;
            
            // Count total questions
            foreach ($question_types as $type) {
                if (isset($assessment[$type])) {
                    $total_questions += count($assessment[$type]);
                }
            }
            
            foreach ($question_types as $type): 
                if (isset($assessment[$type]) && !empty($assessment[$type])):
            ?>
                <div class="question-section">
                    <h2 class="section-title">
                        <?php if ($type === 'multipleChoice'): ?>
                            <i class="fas fa-list-ul"></i> Multiple Choice Questions
                        <?php elseif ($type === 'debugging'): ?>
                            <i class="fas fa-bug"></i> Debugging Questions
                        <?php elseif ($type === 'coding'): ?>
                            <i class="fas fa-code"></i> Coding Questions
                        <?php endif; ?>
                    </h2>
                    
                    <?php foreach ($assessment[$type] as $index => $question_data): ?>
                        <div class="question">
                            <div class="mcq-question">
                                <?= htmlspecialchars($question_data['question']) ?>
                            </div>
                            <?php if (isset($question_data['code'])): ?>
                                <div class="code-block" style="margin-bottom:10px;">
                                    <?= htmlspecialchars($question_data['code']) ?>
                                </div>
                            <?php endif; ?>
                            <div class="mcq-choices">
                                <?php foreach ($question_data['choices'] as $choice): ?>
                                    <label class="mcq-choice">
                                        <input type="radio" name="<?= $type . '_' . $index ?>" value="<?= htmlspecialchars($choice) ?>">
                                        <?= htmlspecialchars($choice) ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php 
                endif;
            endforeach; 
            ?>
            
            <div class="submit-section">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit Assessment
                </button>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <?php if ($can_take_assessment): ?>
    <script>
        // Update progress bar as user answers questions
        function updateProgress() {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input[type="radio"]:checked, input[type="text"]:not([value=""]), textarea:not([value=""])');
            const progressFill = document.getElementById('progressFill');
            const totalInputs = form.querySelectorAll('input[type="radio"], input[type="text"], textarea').length;
            
            const progress = (inputs.length / totalInputs) * 100;
            progressFill.style.width = progress + '%';
        }

        // Add event listeners to all form inputs
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input, textarea');
            
            inputs.forEach(input => {
                input.addEventListener('change', updateProgress);
                input.addEventListener('input', updateProgress);
            });
            
            updateProgress();
        });
    </script>
    <script>
    // 30-minute timer and auto-submit
    let timerDuration = 30 * 60; // 30 minutes in seconds
    let timer = timerDuration;
    let timerInterval;

    function startTimer() {
        const timerDisplay = document.createElement('div');
        timerDisplay.id = 'timerDisplay';
        timerDisplay.style = 'position:fixed;top:20px;right:30px;background:#fff3cd;color:#856404;padding:12px 22px;border-radius:10px;font-size:1.2rem;font-weight:bold;z-index:9999;box-shadow:0 2px 8px #ffeeba;';
        document.body.appendChild(timerDisplay);

        function updateTimer() {
            let minutes = Math.floor(timer / 60);
            let seconds = timer % 60;
            timerDisplay.textContent = `⏰ Time Left: ${minutes}:${seconds.toString().padStart(2, '0')}`;
            if (timer <= 0) {
                clearInterval(timerInterval);
                timerDisplay.textContent = "⏰ Time's up! Submitting...";
                setTimeout(() => {
                    autoSubmitAssessment();
                }, 1000);
            }
            timer--;
        }

        updateTimer();
        timerInterval = setInterval(updateTimer, 1000);
    }

    function autoSubmitAssessment() {
        const form = document.querySelector('form.assessment-content');
        if (form) {
            form.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', startTimer);
    </script>
    <?php endif; ?>
</body>
</html> 