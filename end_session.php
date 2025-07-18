<?php
session_start();

// Enable error logging but disable display to prevent HTML in JSON response
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

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
    $stmt->bind_result($sender_id);
    $stmt->fetch();
    $stmt->close();

    if (!$sender_id) {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $receiver_id = $_POST['user_id'] ?? null;

        if (!$receiver_id) {
            echo json_encode(['status' => 'error', 'message' => 'Receiver ID required']);
            exit();
        }

        // Insert a session end message and mark conversations as ended
        $session_end_message = "📝 Session ended";
        
        $stmt = $conn->prepare("
            INSERT INTO messages (sender_id, receiver_id, message_text, message_type, is_end_session) 
            VALUES (?, ?, ?, 'text', 1)
        ");
        
        if (!$stmt) {
            echo json_encode(['status' => 'error', 'message' => 'Database prepare failed: ' . $conn->error]);
            exit();
        }
        
        $stmt->bind_param("iis", $sender_id, $receiver_id, $session_end_message);

        if ($stmt->execute()) {
            $message_id = $conn->insert_id;
            
            // Update or create conversation (optional - only if table exists)
            $conv_check = $conn->query("SHOW TABLES LIKE 'conversations'");
            if ($conv_check->num_rows > 0) {
                $conv_stmt = $conn->prepare("
                    INSERT INTO conversations (user1_id, user2_id, last_message_id) 
                    VALUES (LEAST(?, ?), GREATEST(?, ?), ?)
                    ON DUPLICATE KEY UPDATE last_message_id = ?, last_activity = CURRENT_TIMESTAMP
                ");
                if ($conv_stmt) {
                    $conv_stmt->bind_param("iiiiii", $sender_id, $receiver_id, $sender_id, $receiver_id, $message_id, $message_id);
                    $conv_stmt->execute();
                    $conv_stmt->close();
                }
            }

            // ===== SKILL PROGRESSION LOGIC =====
            // Include the skill progression functions
            require_once 'update_skill_progression.php';
            
            $progression_notifications = [];
            
            // Try to determine the skill from the conversation context or user skills
            $skill_query = "
                SELECT DISTINCT so.category, so.specific_skill, so.skills_id
                FROM skills_offer so 
                WHERE so.user_id = ? OR so.user_id = ?
                ORDER BY so.skills_id DESC 
                LIMIT 3
            ";
            
            $skill_stmt = $conn->prepare($skill_query);
            if ($skill_stmt) {
                $skill_stmt->bind_param("ii", $sender_id, $receiver_id);
                $skill_stmt->execute();
                $skill_result = $skill_stmt->get_result();
                
                while ($skill_row = $skill_result->fetch_assoc()) {
                    // Create a mock session for progression tracking
                    $session_insert = $conn->prepare("
                        INSERT INTO sessions (skill_category, specific_skill, status, completed_at) 
                        VALUES (?, ?, 'completed', NOW())
                    ");
                    
                    if ($session_insert) {
                        $session_insert->bind_param("ss", $skill_row['category'], $skill_row['specific_skill']);
                        if ($session_insert->execute()) {
                            $new_session_id = $conn->insert_id;
                            $session_insert->close();
                            
                            // Track both users in this session
                            foreach ([$sender_id, $receiver_id] as $user_id) {
                                $user_session_insert = $conn->prepare("
                                    INSERT INTO user_sessions (session_id, user_id, role) 
                                    VALUES (?, ?, 'learner')
                                ");
                                
                                if ($user_session_insert) {
                                    $user_session_insert->bind_param("ii", $new_session_id, $user_id);
                                    $user_session_insert->execute();
                                    $user_session_insert->close();
                                    
                                    // Check for skill progression
                                    $progression = updateSkillProgression(
                                        $conn, 
                                        $user_id, 
                                        $skill_row['category'], 
                                        $skill_row['specific_skill']
                                    );
                                    
                                    if ($progression && $progression['progressed']) {
                                        $progression_notifications[] = [
                                            'user_id' => $user_id,
                                            'skill' => $skill_row['specific_skill'],
                                            'category' => $skill_row['category'],
                                            'old_level' => $progression['old_level'],
                                            'new_level' => $progression['new_level'],
                                            'session_count' => $progression['session_count']
                                        ];
                                    }
                                }
                            }
                        } else {
                            $session_insert->close();
                        }
                    }
                    
                    // Only process the most relevant skill to avoid spam
                    break;
                }
                
                $skill_stmt->close();
            }

            $response = [
                'status' => 'success', 
                'message_id' => $message_id,
                'message' => 'Session ended successfully',
                'show_rating' => true,
                'other_user_id' => $receiver_id
            ];
            
            // Add progression notifications if any occurred
            if (!empty($progression_notifications)) {
                $response['skill_progression'] = $progression_notifications;
            }
            
            echo json_encode($response);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to end session: ' . $stmt->error]);
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
