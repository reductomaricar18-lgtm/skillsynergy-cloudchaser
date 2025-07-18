<?php
session_start();
header('Content-Type: application/json');

try {
    if (!isset($_SESSION['email'])) {
        echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
        exit();
    }

    $conn = new mysqli('localhost', 'root', '', 'sia1');
    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        exit();
    }

    // Get current user ID
    $email = $_SESSION['email'];
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database prepare failed']);
        exit();
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($rater_id);
    $stmt->fetch();
    $stmt->close();

    if (!$rater_id) {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rated_user_id = $_POST['user_id'] ?? null;
        $partner_id = $_POST['partner_id'] ?? null;
        $score = $_POST['score'] ?? 0;
        $max_score = $_POST['max_score'] ?? 0;
        $percentage = $_POST['percentage'] ?? 0;
        $answers = $_POST['answers'] ?? '{}';
        $detailed_results = $_POST['detailed_results'] ?? '{}';
        $topic = $_POST['topic'] ?? '';
        $level = $_POST['level'] ?? '';

        if (!$rated_user_id) {
            echo json_encode(['status' => 'error', 'message' => 'User ID required']);
            exit();
        }

        // Create assessment_results table if it doesn't exist
        $createTable = "
            CREATE TABLE IF NOT EXISTS assessment_results (
                id INT AUTO_INCREMENT PRIMARY KEY,
                rater_id INT NOT NULL,
                rated_user_id INT NOT NULL,
                partner_id INT,
                score DECIMAL(5,2) NOT NULL,
                max_score DECIMAL(5,2) NOT NULL,
                percentage DECIMAL(5,2) NOT NULL,
                answers TEXT,
                detailed_results TEXT,
                topic VARCHAR(100),
                level VARCHAR(50),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX(rater_id),
                INDEX(rated_user_id),
                INDEX(partner_id)
            )
        ";
        
        if (!$conn->query($createTable)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create table: ' . $conn->error]);
            exit();
        }

        // Insert assessment result
        $stmt = $conn->prepare("
            INSERT INTO assessment_results (rater_id, rated_user_id, partner_id, score, max_score, percentage, answers, detailed_results, topic, level) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        if (!$stmt) {
            echo json_encode(['status' => 'error', 'message' => 'Database prepare failed: ' . $conn->error]);
            exit();
        }
        
        $stmt->bind_param("iiidddssss", $rater_id, $rated_user_id, $partner_id, $score, $max_score, $percentage, $answers, $detailed_results, $topic, $level);

        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Assessment saved successfully',
                'assessment_id' => $conn->insert_id
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save assessment: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }

    $conn->close();

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
