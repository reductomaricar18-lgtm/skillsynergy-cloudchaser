-- Create learning_requests table
CREATE TABLE IF NOT EXISTS learning_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_user_id INT NOT NULL,
    to_user_id INT NOT NULL,
    skill VARCHAR(100) NOT NULL,
    proficiency VARCHAR(50) NOT NULL,
    status ENUM('pending', 'accepted', 'expired') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    responded_at TIMESTAMP NULL,
    INDEX idx_to_user (to_user_id, status),
    FOREIGN KEY (from_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (to_user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Add indexes for better performance
CREATE INDEX idx_learning_requests_users ON learning_requests(from_user_id, to_user_id);
CREATE INDEX idx_learning_requests_status_time ON learning_requests(status, created_at); 