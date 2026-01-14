CREATE DATABASE IF NOT EXISTS `calculator_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `calculator_db`;

-- ========================================
-- USERS TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `first_name` VARCHAR(100) DEFAULT NULL,
    `last_name` VARCHAR(100) DEFAULT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email_verified` TINYINT(1) DEFAULT 0,
    `two_factor_enabled` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `failed_attempts` INT(11) DEFAULT 0,
    `lock_until` DATETIME DEFAULT NULL
);

-- ========================================
-- EMAIL VERIFICATIONS TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS `email_verifications` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) DEFAULT NULL,
    `token` VARCHAR(255) DEFAULT NULL,
    `expires_at` DATETIME DEFAULT NULL,
    KEY `user_id` (`user_id`),
    CONSTRAINT `email_verifications_fk_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- ========================================
-- OTP CODES TABLE (2FA & SIGNUP)
-- ========================================
CREATE TABLE IF NOT EXISTS `otp_codes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) DEFAULT NULL,
    `otp` VARCHAR(6) DEFAULT NULL,
    `purpose` ENUM('signup','login') DEFAULT NULL,
    `expires_at` DATETIME DEFAULT NULL,
    `last_sent_at` DATETIME DEFAULT NULL,
    KEY `user_id` (`user_id`),
    CONSTRAINT `otp_codes_fk_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- ========================================
-- PASSWORD RESETS TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS `password_resets` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) DEFAULT NULL,
    `token` VARCHAR(255) DEFAULT NULL,
    `expires_at` DATETIME DEFAULT NULL,
    KEY `user_id` (`user_id`),
    CONSTRAINT `password_resets_fk_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- ========================================
-- TRUSTED DEVICES TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS `trusted_devices` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) DEFAULT NULL,
    `device_token` VARCHAR(255) DEFAULT NULL,
    `expires_at` DATETIME DEFAULT NULL,
    `device_name` VARCHAR(100) NOT NULL,
    `user_agent` TEXT DEFAULT NULL,
    KEY `user_id` (`user_id`),
    CONSTRAINT `trusted_devices_fk_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);
