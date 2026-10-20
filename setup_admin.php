<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
$count = (int)$pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
if ($count > 0) {
    http_response_code(403);
    exit('An administrator already exists. Delete setup_admin.php now for safety.');
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');
    if (!preg_match('/^[a-zA-Z0-9_.-]{4,40}$/', $username)) {
        $error = 'Username must be 4–40 characters using letters, numbers, dot, underscore or hyphen.';
    } elseif (strlen($password) < 12) {
        $error = 'Use a password with at least 12 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO admins (username,password_hash) VALUES (?,?)');
        $stmt->execute([$username,password_hash($password, PASSWORD_DEFAULT)]);
        header('Location: admin/login.php?created=1'); exit;
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Create First Admin | YIC</title><link rel="stylesheet" href="assets/style.css"></head><body><main class="container"><div class="form-card"><h1>Create the first administrator</h1><p>Use this page only on your local computer to create your admin account. After success, delete <code>setup_admin.php</code>.</p>
<?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label for="username">Username</label><input id="username" name="username" required minlength="4" maxlength="40" autocomplete="username"></div><div class="field"><label for="password">Password (minimum 12 characters)</label><input id="password" type="password" name="password" required minlength="12" autocomplete="new-password"></div><div class="field"><label for="confirm_password">Confirm password</label><input id="confirm_password" type="password" name="confirm_password" required minlength="12" autocomplete="new-password"></div><button type="submit">Create administrator</button></form></div></main></body></html>
