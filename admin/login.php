<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
if (is_admin()) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: index.php'); exit;
    }
    $error = 'Incorrect username or password.';
}
$page_title = 'Admin Login';
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin Login | YIC</title><link rel="stylesheet" href="../assets/style.css"></head><body>
<main class="container"><div class="admin-brand"><img src="../assets/logo.jpg" alt="Yeka Industrial College logo"><strong>Yeka Industrial College</strong></div><div class="form-card"><h1>Administrator login</h1><p class="muted">Yeka Industrial College website management</p>
<?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="field"><label for="username">Username</label><input id="username" name="username" required autocomplete="username"></div>
<div class="field"><label for="password">Password</label><input id="password" type="password" name="password" required autocomplete="current-password"></div>
<button type="submit">Sign in</button></form><p><a href="../index.php">← Return to website</a></p></div></main></body></html>
