-- Add missing columns to initial_assessment table
ALTER TABLE initial_assessment 
ADD COLUMN database_type VARCHAR(100) DEFAULT NULL;

ALTER TABLE initial_assessment 
ADD COLUMN specific_database_skill VARCHAR(100) DEFAULT NULL;

-- Verify the columns were added
DESCRIBE initial_assessment; 