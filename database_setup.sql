-- Database setup for real-time messaging system
-- Run these SQL commands in your MySQL database (sia1)

-- Table for storing messages
CREATE TABLE IF NOT EXISTS messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message_text TEXT,
    message_type ENUM('text', 'file', 'image') DEFAULT 'text',
    file_name VARCHAR(255) NULL,
    file_path VARCHAR(500) NULL,
    file_size INT NULL,
    file_type VARCHAR(100) NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE,
    is_deleted BOOLEAN DEFAULT FALSE,
    is_end_session BOOLEAN DEFAULT FALSE,
    INDEX idx_sender_receiver (sender_id, receiver_id),
    INDEX idx_sent_at (sent_at),
    FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Table for storing conversation threads (optional but useful for performance)
CREATE TABLE IF NOT EXISTS conversations (
    conversation_id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    last_message_id INT NULL,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_conversation (LEAST(user1_id, user2_id), GREATEST(user1_id, user2_id)),
    FOREIGN KEY (user1_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (last_message_id) REFERENCES messages(message_id) ON DELETE SET NULL
);

-- Table for tracking online users (for real-time features)
CREATE TABLE IF NOT EXISTS user_online_status (
    user_id INT PRIMARY KEY,
    last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_online BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Table for user ratings after conversations
CREATE TABLE IF NOT EXISTS user_ratings (
    rating_id INT AUTO_INCREMENT PRIMARY KEY,
    rater_id INT NOT NULL,
    rated_user_id INT NOT NULL,
    understanding_rating INT NOT NULL CHECK (understanding_rating BETWEEN 1 AND 5),
    knowledge_sharing_rating INT NOT NULL CHECK (knowledge_sharing_rating BETWEEN 1 AND 5),
    listening_rating INT NOT NULL CHECK (listening_rating BETWEEN 1 AND 5),
    feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_rating (rater_id, rated_user_id),
    FOREIGN KEY (rater_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (rated_user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Sample data to test (optional)
-- INSERT INTO messages (sender_id, receiver_id, message_text, message_type) 
-- VALUES (1, 2, 'Hello! How are you?', 'text');
