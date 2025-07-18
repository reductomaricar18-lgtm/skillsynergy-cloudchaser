-- Add skill progression tracking table
CREATE TABLE IF NOT EXISTS skill_progression_log (
    progression_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_category VARCHAR(100) NOT NULL,
    specific_skill VARCHAR(100) NOT NULL,
    old_proficiency ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL,
    new_proficiency ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL,
    session_count INT NOT NULL,
    progression_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notified TINYINT(1) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_skill (user_id, skill_category, specific_skill),
    INDEX idx_progression_date (progression_date)
);

-- Add sessions table if it doesn't exist (for tracking completed sessions)
CREATE TABLE IF NOT EXISTS sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    skill_category VARCHAR(100) NOT NULL,
    specific_skill VARCHAR(100) NOT NULL,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    INDEX idx_skill (skill_category, specific_skill),
    INDEX idx_status (status)
);

-- Add user_sessions table to track user participation in sessions
CREATE TABLE IF NOT EXISTS user_sessions (
    user_session_id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    user_id INT NOT NULL,
    role ENUM('mentor', 'learner') DEFAULT 'learner',
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    rating TINYINT NULL CHECK (rating >= 1 AND rating <= 5),
    feedback TEXT NULL,
    FOREIGN KEY (session_id) REFERENCES sessions(session_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_session (session_id, user_id),
    INDEX idx_user_sessions (user_id),
    INDEX idx_session_user (session_id, user_id)
);

-- Add progression_date column to initial_assessment if it doesn't exist
ALTER TABLE initial_assessment 
ADD COLUMN IF NOT EXISTS progression_date TIMESTAMP NULL;

-- Insert sample data to test the progression system (optional)
-- You can uncomment this section if you want to test with sample data

/*
-- Sample sessions for testing
INSERT IGNORE INTO sessions (session_id, skill_category, specific_skill, status, completed_at) VALUES
(1, 'Programming', 'Python', 'completed', '2024-01-15 10:00:00'),
(2, 'Programming', 'Python', 'completed', '2024-01-16 11:00:00'),
(3, 'Web Development', 'JavaScript', 'completed', '2024-01-17 14:00:00');

-- Sample user sessions for testing (replace user_id with actual user IDs)
INSERT IGNORE INTO user_sessions (session_id, user_id, role, rating) VALUES
(1, 1, 'learner', 5),
(2, 1, 'learner', 4),
(3, 1, 'mentor', 5);
*/

-- Create view for easy querying of user progression stats
CREATE OR REPLACE VIEW user_skill_progression_summary AS
SELECT 
    u.user_id,
    up.first_name,
    up.last_name,
    ia.category,
    ia.specific_skill,
    ia.proficiency,
    ia.progression_date,
    COALESCE(session_stats.completed_sessions, 0) as completed_sessions,
    CASE 
        WHEN COALESCE(session_stats.completed_sessions, 0) >= 75 THEN 'Ready for Advanced'
        WHEN COALESCE(session_stats.completed_sessions, 0) >= 25 AND ia.proficiency = 'Beginner' THEN 'Ready for Intermediate'
        ELSE 'Continue Learning'
    END as progression_status
FROM users u
JOIN users_profile up ON u.user_id = up.user_id
LEFT JOIN initial_assessment ia ON u.user_id = ia.user_id
LEFT JOIN (
    SELECT 
        us.user_id,
        s.skill_category,
        s.specific_skill,
        COUNT(*) as completed_sessions
    FROM user_sessions us
    JOIN sessions s ON us.session_id = s.session_id
    WHERE s.status = 'completed'
    GROUP BY us.user_id, s.skill_category, s.specific_skill
) session_stats ON u.user_id = session_stats.user_id 
    AND ia.category = session_stats.skill_category 
    AND ia.specific_skill = session_stats.specific_skill
WHERE ia.user_id IS NOT NULL
ORDER BY u.user_id, ia.category, ia.specific_skill;
