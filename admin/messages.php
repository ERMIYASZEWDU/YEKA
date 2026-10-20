<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $reply = trim((string)($_POST['admin_reply'] ?? ''));
    if (!$id || $reply === '' || strlen($reply) > 10000) {
        $error = 'Select a valid message and enter a reply (maximum 10,000 characters).';
    } else {
        $stmt = $pdo->prepare("UPDATE contact_messages SET admin_reply=?, status='replied', replied_at=NOW() WHERE id=?");
        $stmt->execute([$reply, $id]);
        header('Location: messages.php?replied=1'); exit;
    }
}
$selected_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
if ($selected_id) {
    $mark = $pdo->prepare("UPDATE contact_messages SET status='read' WHERE id=? AND status='new'");
    $mark->execute([$selected_id]);
}
$selected = null;
if ($selected_id) {
    $stmt = $pdo->prepare('SELECT * FROM contact_messages WHERE id=?');
    $stmt->execute([$selected_id]);
    $selected = $stmt->fetch();
}
$rows = $pdo->query('SELECT id,sender_name,sender_email,sender_phone,sender_address,subject,status,created_at FROM contact_messages ORDER BY (status="new") DESC, created_at DESC')->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Contact Inbox | YIC</title><link rel="stylesheet" href="../assets/style.css"></head><body><main class="container">
<div class="admin-brand"><img src="../assets/logo.jpg" alt="Yeka Industrial College logo"><strong>Yeka Industrial College</strong></div>
<div class="admin-top"><div><h1>Contact messages</h1><p class="muted">Review incoming messages and record staff replies.</p></div><div class="actions"><a class="button-secondary" href="index.php">Dashboard</a><a class="button-secondary" href="news.php">Manage news</a></div></div>
<?php if (isset($_GET['replied'])): ?><div class="notice">Reply saved successfully. Note: this records the reply in the dashboard; it does not send an email automatically.</div><?php endif; ?>
<?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<?php if ($selected): ?><section class="form-card"><h2><?= e($selected['subject']) ?></h2><p><strong>From:</strong> <?= e($selected['sender_name']) ?> (<?= e($selected['sender_email']) ?>)</p><p><strong>Phone:</strong> <?= e($selected['sender_phone'] ?? '') ?></p><p><strong>Address:</strong> <?= e($selected['sender_address'] ?? '') ?></p><p class="date">Received: <?= e($selected['created_at']) ?></p><div class="card"><h3>Message</h3><p><?= nl2br(e($selected['message'])) ?></p></div>
<?php if ($selected['admin_reply']): ?><div class="notice"><strong>Previous reply (<?= e($selected['replied_at'] ?? '') ?>)</strong><p><?= nl2br(e($selected['admin_reply'])) ?></p></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$selected['id'] ?>"><div class="field"><label for="admin_reply">Reply / response *</label><textarea id="admin_reply" name="admin_reply" required maxlength="10000"><?= e($selected['admin_reply'] ?? '') ?></textarea></div><button type="submit">Save reply</button></form></section><?php endif; ?>
<h2>Inbox</h2>
<?php if (!$rows): ?><div class="empty">No contact messages have been received yet.</div><?php else: ?><div class="table-wrap"><table class="table"><thead><tr><th>Status</th><th>Sender</th><th>Subject</th><th>Received</th><th>Action</th></tr></thead><tbody><?php foreach ($rows as $row): ?><tr><td><span class="pill <?= $row['status']==='new'?'draft':'' ?>"><?= e(ucfirst($row['status'])) ?></span></td><td><?= e($row['sender_name']) ?><br><small><?= e($row['sender_email']) ?><br><?= e($row['sender_phone'] ?? '') ?></small></td><td><?= e($row['subject']) ?></td><td><?= e($row['created_at']) ?></td><td><a href="messages.php?id=<?= (int)$row['id'] ?>"><?= $row['status']==='replied'?'View / edit reply':'Open / reply' ?></a></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
</main></body></html>
