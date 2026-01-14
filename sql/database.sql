CREATE DATABASE calculator_db;
USE calculator_db;

-- USERS
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email_verified BOOLEAN DEFAULT 0,
    two_factor_enabled BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- EMAIL VERIFICATION TOKENS
CREATE TABLE email_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    token VARCHAR(255),
    expires_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- OTP CODES (2FA & SIGNUP)
CREATE TABLE otp_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    otp VARCHAR(6),
    purpose ENUM('signup','login'),
    expires_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- PASSWORD RESET
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    token VARCHAR(255),
    expires_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- TRUSTED DEVICES
CREATE TABLE trusted_devices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    device_token VARCHAR(255),
    expires_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
