<?php
session_start();

if (!isset($_SESSION['email'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Get current user ID
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

if (!$user_id) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit();
}

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Get skill progression notifications
        $notifications_query = "
            SELECT 
                progression_id,
                skill_category,
                specific_skill,
                old_proficiency,
                new_proficiency,
                session_count,
                progression_date
            FROM skill_progression_log 
            WHERE user_id = ? AND notified = 0
            ORDER BY progression_date DESC
        ";
        
        $stmt = $conn->prepare($notifications_query);
        if (!$stmt) {
            throw new Exception('Database prepare failed: ' . $conn->error);
        }
        
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = [
                'id' => $row['progression_id'],
                'title' => 'Skill Level Up!',
                'message' => "Congratulations! Your {$row['specific_skill']} skill has progressed from {$row['old_proficiency']} to {$row['new_proficiency']} after {$row['session_count']} completed sessions!",
                'skill' => $row['specific_skill'],
                'category' => $row['skill_category'],
                'old_level' => $row['old_proficiency'],
                'new_level' => $row['new_proficiency'],
                'session_count' => $row['session_count'],
                'date' => $row['progression_date'],
                'type' => 'skill_progression'
            ];
        }
        
        $stmt->close();
        
        echo json_encode([
            'status' => 'success',
            'notifications' => $notifications,
            'count' => count($notifications)
        ]);
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Mark notifications as read
        $action = $_POST['action'] ?? '';
        
        if ($action === 'mark_read') {
            $notification_ids = $_POST['notification_ids'] ?? [];
            
            if (!empty($notification_ids) && is_array($notification_ids)) {
                $placeholders = str_repeat('?,', count($notification_ids) - 1) . '?';
                $mark_read_query = "
                    UPDATE skill_progression_log 
                    SET notified = 1 
                    WHERE user_id = ? AND progression_id IN ($placeholders)
                ";
                
                $stmt = $conn->prepare($mark_read_query);
                if ($stmt) {
                    $types = str_repeat('i', count($notification_ids) + 1);
                    $params = array_merge([$user_id], $notification_ids);
                    $stmt->bind_param($types, ...$params);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            
            echo json_encode(['status' => 'success', 'message' => 'Notifications marked as read']);
            
        } elseif ($action === 'mark_all_read') {
            // Mark all progression notifications as read
            $mark_all_read_query = "
                UPDATE skill_progression_log 
                SET notified = 1 
                WHERE user_id = ? AND notified = 0
            ";
            
            $stmt = $conn->prepare($mark_all_read_query);
            if ($stmt) {
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->close();
            }
            
            echo json_encode(['status' => 'success', 'message' => 'All notifications marked as read']);
            
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        }
        
    } else {
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}

$conn->close();
?>
