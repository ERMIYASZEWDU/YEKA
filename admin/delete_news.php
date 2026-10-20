<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: news.php'); exit; }
verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id) {
    $stmt = $pdo->prepare('DELETE FROM news WHERE id = ?');
    $stmt->execute([$id]);
}
header('Location: news.php?deleted=1');
exit;
