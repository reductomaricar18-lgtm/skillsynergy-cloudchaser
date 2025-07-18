-- Fix database schema for want_to_learn functionality
-- This script addresses the "Unknown column 'want_to_learn' in 'where clause'" error

-- 1. Add want_to_learn column to initial_assessment table
ALTER TABLE initial_assessment 
ADD COLUMN IF NOT EXISTS want_to_learn VARCHAR(100) DEFAULT NULL;

-- 2. Add an index for better performance when querying by want_to_learn
ALTER TABLE initial_assessment 
ADD INDEX IF NOT EXISTS idx_want_to_learn (want_to_learn);

-- 3. Create learning_goals table if it doesn't exist (for user_profile.php compatibility)
CREATE TABLE IF NOT EXISTS learning_goals (
    goal_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    want_to_learn VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_goal (user_id, want_to_learn),
    INDEX idx_created_at (created_at)
);

-- 4. Verify the changes
DESCRIBE initial_assessment;
DESCRIBE learning_goals;

-- 5. Show current data in initial_assessment table
SELECT 'Current initial_assessment data:' as info;
SELECT user_id, want_to_learn, created_at 
FROM initial_assessment 
WHERE want_to_learn IS NOT NULL 
LIMIT 10;

-- 6. Show current data in learning_goals table
SELECT 'Current learning_goals data:' as info;
SELECT * FROM learning_goals LIMIT 10; 