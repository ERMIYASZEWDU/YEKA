<?php
declare(strict_types=1);
// Default local XAMPP settings. On a real host, copy config/db.local.example.php
// to config/db.local.php and put the host's MySQL details there (it is not uploaded to GitHub).
$host = '127.0.0.1';
$dbname = 'yeka_college_db';
$username = 'root';
$password = '';
if (is_file(__DIR__ . '/db.local.php')) {
    require __DIR__ . '/db.local.php';
}
$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check config/db.php and import database/yeka_college.sql.');
}


// Ensure contact_messages exists. This is also a safety net for installations
// where the SQL migration was not imported into the active database.
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS contact_messages (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Upgrade older versions of the table without deleting existing messages.
    $columns = $pdo->query("SHOW COLUMNS FROM contact_messages")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('sender_phone', $columns, true)) {
        $pdo->exec("ALTER TABLE contact_messages ADD COLUMN sender_phone VARCHAR(30) NOT NULL DEFAULT ''");
    }
    if (!in_array('sender_address', $columns, true)) {
        $pdo->exec("ALTER TABLE contact_messages ADD COLUMN sender_address VARCHAR(255) NOT NULL DEFAULT ''");
    }
    if (!in_array('subject', $columns, true)) {
        $pdo->exec("ALTER TABLE contact_messages ADD COLUMN subject VARCHAR(200) NOT NULL DEFAULT 'Contact message'");
    }
    if (!in_array('admin_reply', $columns, true)) {
        $pdo->exec("ALTER TABLE contact_messages ADD COLUMN admin_reply TEXT NULL");
    }
    if (!in_array('replied_at', $columns, true)) {
        $pdo->exec("ALTER TABLE contact_messages ADD COLUMN replied_at DATETIME NULL");
    }
} catch (PDOException $e) {
    error_log('Contact table setup failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Contact database setup failed. Check that MySQL is running and the database user has CREATE/ALTER privileges.');
}

