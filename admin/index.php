<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$total = (int)$pdo->query('SELECT COUNT(*) FROM news')->fetchColumn();
$published = (int)$pdo->query("SELECT COUNT(*) FROM news WHERE status='published'")->fetchColumn();
$drafts = (int)$pdo->query("SELECT COUNT(*) FROM news WHERE status='draft'")->fetchColumn();
$new_messages = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status='new'")->fetchColumn();
$total_messages = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$recent = $pdo->query('SELECT id,title_en,status,created_at FROM news ORDER BY id DESC LIMIT 8')->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Dashboard | YIC</title><link rel="stylesheet" href="../assets/style.css"></head><body><main class="container"><div class="admin-brand"><img src="../assets/logo.jpg" alt="Yeka Industrial College logo"><strong>Yeka Industrial College</strong></div>
<div class="admin-top"><div><h1>Admin dashboard</h1><p class="muted">Signed in as <?= e($_SESSION['admin_username'] ?? 'admin') ?></p></div><div class="actions"><a class="button-secondary" href="../index.php">View website</a><a class="button-secondary" href="news.php">Manage news</a><a class="button-secondary" href="messages.php">Contact inbox (<?= $new_messages ?> new)</a><form class="inline-form" method="post" action="logout.php"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button type="submit">Log out</button></form></div></div>
<div class="grid"><div class="card"><p class="muted">New contact messages</p><h2><?= $new_messages ?></h2><a href="messages.php">Open inbox →</a></div><div class="card"><p class="muted">Total contact messages</p><h2><?= $total_messages ?></h2></div><div class="card"><p class="muted">Total news posts</p><h2><?= $total ?></h2></div><div class="card"><p class="muted">Published</p><h2><?= $published ?></h2></div><div class="card"><p class="muted">Drafts</p><h2><?= $drafts ?></h2></div></div>
<div class="section-heading"><h2>Recent news</h2><a class="button" href="news_form.php">+ Add announcement</a></div>
<?php if (!$recent): ?><div class="empty">No news posts yet. Add your first announcement.</div><?php else: ?><div class="table-wrap"><table class="table"><thead><tr><th>Title</th><th>Status</th><th>Created</th><th>Action</th></tr></thead><tbody><?php foreach ($recent as $row): ?><tr><td><?= e($row['title_en']) ?></td><td><span class="pill <?= $row['status']==='draft'?'draft':'' ?>"><?= e(ucfirst($row['status'])) ?></span></td><td><?= e($row['created_at']) ?></td><td><a href="news_form.php?id=<?= (int)$row['id'] ?>">Edit</a></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
</main></body></html>
