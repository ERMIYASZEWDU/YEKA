CREATE DATABASE IF NOT EXISTS yeka_college_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE yeka_college_db;

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sender_name VARCHAR(120) NOT NULL,
    sender_email VARCHAR(190) NOT NULL,
    sender_phone VARCHAR(30) NOT NULL DEFAULT '',
    sender_address VARCHAR(255) NOT NULL DEFAULT '',
    subject VARCHAR(200) NOT NULL DEFAULT 'Contact message',
    message TEXT NOT NULL,
    status ENUM('new','read','replied') NOT NULL DEFAULT 'new',
    admin_reply TEXT NULL,
    replied_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_contact_status_created (status, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
