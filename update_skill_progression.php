<?php
/**
 * Skill Progression System
 * Automatically updates user proficiency levels based on session count
 * Called after each completed session to check for level progression
 */

function updateSkillProgression($conn, $user_id, $skill_category, $specific_skill) {
    try {
        // Get current session count for this skill
        $session_count_query = "
            SELECT COUNT(*) as session_count 
            FROM sessions s
            JOIN user_sessions us ON s.session_id = us.session_id 
            WHERE us.user_id = ? 
            AND s.skill_category = ? 
            AND s.specific_skill = ? 
            AND s.status = 'completed'
        ";
        
        $stmt = $conn->prepare($session_count_query);
        if (!$stmt) {
            error_log("Prepare failed for session count: " . $conn->error);
            return false;
        }
        
        $stmt->bind_param("iss", $user_id, $skill_category, $specific_skill);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $session_count = (int)$row['session_count'];
        $stmt->close();
        
        // Get current proficiency level
        $current_proficiency_query = "
            SELECT proficiency_level 
            FROM initial_assessment 
            WHERE user_id = ? AND category = ? AND specific_skill = ?
        ";
        
        $stmt = $conn->prepare($current_proficiency_query);
        if (!$stmt) {
            error_log("Prepare failed for current proficiency: " . $conn->error);
            return false;
        }
        
        $stmt->bind_param("iss", $user_id, $skill_category, $specific_skill);
        $stmt->execute();
        $result = $stmt->get_result();
        $current_proficiency = "Beginner"; // Default
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $current_proficiency = $row['proficiency_level'];
        }
        $stmt->close();
        
        // Determine new proficiency level based on session count
        $new_proficiency = calculateNewProficiency($session_count, $current_proficiency);
        
        // Update proficiency if there's a progression
        if ($new_proficiency !== $current_proficiency) {
            $update_query = "
                UPDATE initial_assessment 
                SET proficiency_level = ?, progression_date = NOW()
                WHERE user_id = ? AND category = ? AND specific_skill = ?
            ";
            
            $stmt = $conn->prepare($update_query);
            if (!$stmt) {
                error_log("Prepare failed for proficiency update: " . $conn->error);
                return false;
            }
            
            $stmt->bind_param("siss", $new_proficiency, $user_id, $skill_category, $specific_skill);
            $result = $stmt->execute();
            $stmt->close();
            
            if ($result) {
                // Log the progression
                logSkillProgression($conn, $user_id, $skill_category, $specific_skill, $current_proficiency, $new_proficiency, $session_count);
                return array(
                    'progressed' => true,
                    'old_level' => $current_proficiency,
                    'new_level' => $new_proficiency,
                    'session_count' => $session_count
                );
            }
        }
        
        return array('progressed' => false, 'current_level' => $current_proficiency, 'session_count' => $session_count);
        
    } catch (Exception $e) {
        error_log("Error in updateSkillProgression: " . $e->getMessage());
        return false;
    }
}

function calculateNewProficiency($session_count, $current_proficiency) {
    // Progression thresholds
    $beginner_to_intermediate = 25;   // 25 completed sessions
    $intermediate_to_advanced = 75;   // 75 completed sessions
    
    if ($session_count >= $intermediate_to_advanced && $current_proficiency !== 'Advanced') {
        return 'Advanced';
    } elseif ($session_count >= $beginner_to_intermediate && $current_proficiency === 'Beginner') {
        return 'Intermediate';
    }
    
    return $current_proficiency; // No progression
}

function logSkillProgression($conn, $user_id, $skill_category, $specific_skill, $old_level, $new_level, $session_count) {
    $log_query = "
        INSERT INTO skill_progression_log 
        (user_id, skill_category, specific_skill, old_proficiency, new_proficiency, session_count, progression_date) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ";
    
    $stmt = $conn->prepare($log_query);
    if ($stmt) {
        $stmt->bind_param("issssi", $user_id, $skill_category, $specific_skill, $old_level, $new_level, $session_count);
        $stmt->execute();
        $stmt->close();
    }
}

// Function to be called after a session is completed
function handleSessionCompletion($conn, $user_id, $session_id) {
    try {
        // Get session details
        $session_query = "
            SELECT skill_category, specific_skill 
            FROM sessions 
            WHERE session_id = ?
        ";
        
        $stmt = $conn->prepare($session_query);
        if (!$stmt) {
            error_log("Prepare failed for session details: " . $conn->error);
            return false;
        }
        
        $stmt->bind_param("i", $session_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $session = $result->fetch_assoc();
            $stmt->close();
            
            // Update skill progression
            $progression_result = updateSkillProgression(
                $conn, 
                $user_id, 
                $session['skill_category'], 
                $session['specific_skill']
            );
            
            return $progression_result;
        }
        
        $stmt->close();
        return false;
        
    } catch (Exception $e) {
        error_log("Error in handleSessionCompletion: " . $e->getMessage());
        return false;
    }
}

// Get user's skill progression stats
function getUserSkillProgressionStats($conn, $user_id, $skill_category = null, $specific_skill = null) {
    $where_clause = "WHERE user_id = ?";
    $params = array($user_id);
    $types = "i";
    
    if ($skill_category) {
        $where_clause .= " AND skill_category = ?";
        $params[] = $skill_category;
        $types .= "s";
    }
    
    if ($specific_skill) {
        $where_clause .= " AND specific_skill = ?";
        $params[] = $specific_skill;
        $types .= "s";
    }
    
    $query = "
        SELECT skill_category, specific_skill, old_proficiency, new_proficiency, 
               session_count, progression_date
        FROM skill_progression_log 
        $where_clause
        ORDER BY progression_date DESC
    ";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return array();
    }
    
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $progression_history = array();
    while ($row = $result->fetch_assoc()) {
        $progression_history[] = $row;
    }
    
    $stmt->close();
    return $progression_history;
}

// Check if user needs to be notified about progression
function checkProgressionNotification($conn, $user_id) {
    $query = "
        SELECT COUNT(*) as unnotified_count 
        FROM skill_progression_log 
        WHERE user_id = ? AND notified = 0
    ";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return 0;
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return (int)$row['unnotified_count'];
}

// Mark progression notifications as seen
function markProgressionNotificationsAsSeen($conn, $user_id) {
    $query = "UPDATE skill_progression_log SET notified = 1 WHERE user_id = ? AND notified = 0";
    
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        return true;
    }
    
    return false;
}
?>
