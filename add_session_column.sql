-- Add is_end_session column to messages table if it doesn't exist
ALTER TABLE messages ADD COLUMN IF NOT EXISTS is_end_session BOOLEAN DEFAULT FALSE;

-- Update existing messages to have is_end_session = FALSE by default
UPDATE messages SET is_end_session = FALSE WHERE is_end_session IS NULL;

-- Index for better performance on session end queries
CREATE INDEX IF NOT EXISTS idx_messages_session_end ON messages(is_end_session);
CREATE INDEX IF NOT EXISTS idx_messages_conversation ON messages(sender_id, receiver_id, is_end_session);
