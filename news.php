<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
$page_title = tr('News and Announcements', 'ዜናና ማስታወቂያዎች');
$stmt = $pdo->query("SELECT title_en,title_am,body_en,body_am,published_at FROM news WHERE status='published' ORDER BY published_at DESC, id DESC");
$items = $stmt->fetchAll();
require __DIR__ . '/includes/header.php';
?>
<h1><?= e(tr('News & Announcements', 'ዜናና ማስታወቂያዎች')) ?></h1>
<p class="muted"><?= e(tr('Official updates from Yeka Industrial College.', 'ከየካ ኢንዱስትሪያል ኮሌጅ የሚቀርቡ ኦፊሴላዊ መረጃዎች።')) ?></p>
<?php if (!$items): ?><div class="empty"><?= e(tr('No announcements have been published yet.', 'እስካሁን ማስታወቂያ አልታተመም።')) ?></div>
<?php else: ?><div class="grid"><?php foreach ($items as $item): ?><article class="card"><div class="date"><?= e(date('F j, Y', strtotime($item['published_at']))) ?></div><h2><?= e(current_lang()==='am' && $item['title_am'] ? $item['title_am'] : $item['title_en']) ?></h2><p><?= nl2br(e(current_lang()==='am' && $item['body_am'] ? $item['body_am'] : $item['body_en'])) ?></p></article><?php endforeach; ?></div><?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
