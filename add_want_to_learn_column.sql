-- Add want_to_learn column to initial_assessment table
-- This fixes the "Unknown column 'want_to_learn' in 'where clause'" error

ALTER TABLE initial_assessment 
ADD COLUMN want_to_learn VARCHAR(100) DEFAULT NULL;

-- Add an index for better performance when querying by want_to_learn
ALTER TABLE initial_assessment 
ADD INDEX idx_want_to_learn (want_to_learn);

-- Verify the column was added
DESCRIBE initial_assessment; 