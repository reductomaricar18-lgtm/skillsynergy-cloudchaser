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


    $user_id = $_GET['user_id'] ?? null;
    // Validate user_id is a positive integer
    if (!$user_id || !is_numeric($user_id) || intval($user_id) <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Valid User ID required']);
        exit();
    }
    $user_id = intval($user_id);

    // Get the user's want_to_learn from learning_goals table
    $stmt = $conn->prepare("SELECT want_to_learn FROM learning_goals WHERE user_id = ? AND want_to_learn IS NOT NULL ORDER BY created_at DESC LIMIT 1");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database prepare failed: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $want_to_learn = trim($row['want_to_learn']);

        // Map skills to our available question sets (case-insensitive)

        $skillMapping = [
            'python' => 'Python',
            'java' => 'Java',
            'c' => 'C',
            'c++' => 'C++',
            'cpp' => 'C++',
            'php' => 'PHP',
            'javascript' => 'Javascript',
            'js' => 'Javascript',
            'css' => 'CSS',
            'html' => 'HTML',
            'node.js' => 'Node.js',
            'nodejs' => 'Node.js',
            'node' => 'Node.js',
            'react' => 'React',
            'laravel' => 'Laravel',
            'sql' => 'SQL',
            'nosql' => 'NoSQL',
            'mysql' => 'MySQL',
            'postgresql' => 'PostgreSQL',
            'postgres' => 'PostgreSQL',
            'oracle database' => 'Oracle Database',
            'oracle' => 'Oracle Database',
            'mongodb' => 'MongoDB',
            'mongo' => 'MongoDB',
            'sql server' => 'SQL Server',
            'mssql' => 'SQL Server',
            'cassandra' => 'Cassandra',
            'redis' => 'Redis',
            'dynamodb' => 'DynamoDB',
        ];

        $want_to_learn_key = strtolower($want_to_learn);
        $mappedSkill = $skillMapping[$want_to_learn_key] ?? 'Python'; // Default to Python if skill not found

        // Debug log (optional):
        // file_put_contents('debug_skill_choice.log', date('Y-m-d H:i:s') . " | user_id: $user_id | want_to_learn: $want_to_learn | mappedSkill: $mappedSkill\n", FILE_APPEND);

        echo json_encode([
            'status' => 'success',
            'skill' => $mappedSkill,
            'original_skill' => $want_to_learn
        ]);
    } else {
        // If no want_to_learn found, default to Python
        echo json_encode([
            'status' => 'success',
            'skill' => 'Python',
            'original_skill' => 'None'
        ]);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
