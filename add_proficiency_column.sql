-- Add proficiency column to initial_assessment table
ALTER TABLE initial_assessment
ADD COLUMN proficiency ENUM('Beginner', 'Intermediate', 'Advanced') DEFAULT 'Beginner';

-- Verify the column was added
DESCRIBE initial_assessment; 