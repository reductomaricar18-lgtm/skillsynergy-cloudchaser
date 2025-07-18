-- Add is_end_session column to existing messages table
-- Run this if you already have a messages table without the is_end_session column

ALTER TABLE messages ADD COLUMN is_end_session BOOLEAN DEFAULT FALSE AFTER is_deleted;

-- Update index if needed
CREATE INDEX idx_end_session ON messages(is_end_session);

ALTER TABLE sessions ADD COLUMN thank_you_sent_at DATETIME DEFAULT NULL;
