<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$rows = $pdo->query('SELECT id,title_en,title_am,status,created_at,published_at FROM news ORDER BY id DESC')->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Manage News | YIC</title><link rel="stylesheet" href="../assets/style.css"></head><body><main class="container"><div class="admin-brand"><img src="../assets/logo.jpg" alt="Yeka Industrial College logo"><strong>Yeka Industrial College</strong></div>
<div class="admin-top"><div><h1>Manage news & announcements</h1><p class="muted">Create, edit, publish or delete website news.</p></div><div class="actions"><a class="button-secondary" href="index.php">Dashboard</a><a class="button" href="news_form.php">+ Add announcement</a></div></div>
<?php if (isset($_GET['saved'])): ?><div class="notice">Announcement saved successfully.</div><?php endif; ?><?php if (isset($_GET['deleted'])): ?><div class="notice">Announcement deleted.</div><?php endif; ?>
<?php if (!$rows): ?><div class="empty">No announcements yet.</div><?php else: ?><div class="table-wrap"><table class="table"><thead><tr><th>English title</th><th>Amharic title</th><th>Status</th><th>Actions</th></tr></thead><tbody><?php foreach ($rows as $row): ?><tr><td><?= e($row['title_en']) ?></td><td><?= e($row['title_am']) ?></td><td><span class="pill <?= $row['status']==='draft'?'draft':'' ?>"><?= e(ucfirst($row['status'])) ?></span></td><td><div class="actions"><a href="news_form.php?id=<?= (int)$row['id'] ?>">Edit</a><form class="inline-form" method="post" action="delete_news.php" data-confirm="Delete this announcement permanently?"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><button type="submit">Delete</button></form></div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
</main><script src="../assets/script.js"></script></body></html>
