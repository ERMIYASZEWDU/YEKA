USE yeka_college_db;

CREATE TABLE IF NOT EXISTS contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sender_name VARCHAR(120) NOT NULL,
  sender_email VARCHAR(190) NOT NULL,
  sender_phone VARCHAR(30) NOT NULL,
  sender_address VARCHAR(255) NOT NULL,
  subject VARCHAR(200) NOT NULL DEFAULT 'Contact message',
  message TEXT NOT NULL,
  status ENUM('new','read','replied') NOT NULL DEFAULT 'new',
  admin_reply TEXT NULL,
  replied_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_contact_status_created (status, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add the new columns to an existing table, if they are not already present.
SET @col_phone_exists = (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'contact_messages' AND COLUMN_NAME = 'sender_phone'
);
SET @sql_phone = IF(@col_phone_exists = 0,
  'ALTER TABLE contact_messages ADD COLUMN sender_phone VARCHAR(30) NOT NULL DEFAULT ""',
  'SELECT "sender_phone already exists"');
PREPARE stmt_phone FROM @sql_phone; EXECUTE stmt_phone; DEALLOCATE PREPARE stmt_phone;

SET @col_address_exists = (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'contact_messages' AND COLUMN_NAME = 'sender_address'
);
SET @sql_address = IF(@col_address_exists = 0,
  'ALTER TABLE contact_messages ADD COLUMN sender_address VARCHAR(255) NOT NULL DEFAULT ""',
  'SELECT "sender_address already exists"');
PREPARE stmt_address FROM @sql_address; EXECUTE stmt_address; DEALLOCATE PREPARE stmt_address;
