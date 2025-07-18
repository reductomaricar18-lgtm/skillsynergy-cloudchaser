ALTER TABLE users
ADD COLUMN login_time DATETIME NULL AFTER last_login,
ADD COLUMN logout_time DATETIME NULL AFTER login_time; 