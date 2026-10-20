<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
$stmt = $pdo->query("SELECT id,title_en,title_am,body_en,body_am,published_at FROM news WHERE status='published' ORDER BY published_at DESC, id DESC LIMIT 3");
$news_items = $stmt->fetchAll();
$page_title = tr('Home', 'መነሻ');
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
  <p><?= e(tr('WELCOME TO', 'እንኳን ወደ ኮሌጃችን በደህና መጡ')) ?></p>
  <h1><?= e(tr('Yeka Industrial College', 'የካ ኢንዱስትሪያል ኮሌጅ')) ?></h1>
  <p><?= e(tr('Developing practical skills and preparing learners for the world of work through technical and vocational education.', 'በቴክኒክና ሙያ ትምህርት ተግባራዊ ክህሎትን ማሳደግና ተማሪዎችን ለሥራ ዓለም ማዘጋጀት።')) ?></p>
  <a class="button" href="news.php"><?= e(tr('Latest announcements', 'የቅርብ ጊዜ ማስታወቂያዎች')) ?></a>
</section>
<div class="section-heading"><h2><?= e(tr('Explore the College', 'ኮሌጁን ይተዋወቁ')) ?></h2></div>
<div class="grid">
  <article class="card"><h3><?= e(tr('About Us', 'ስለ እኛ')) ?></h3><p><?= e(tr('Learn about the college, its mission and its role in skills development.', 'ስለ ኮሌጁ፣ ተልዕኮውና በክህሎት ልማት ያለውን ሚና ይወቁ።')) ?></p><a href="about.php"><?= e(tr('Read more →', 'ተጨማሪ ያንብቡ →')) ?></a></article>
  <article class="card"><h3><?= e(tr('News & Announcements', 'ዜናና ማስታወቂያዎች')) ?></h3><p><?= e(tr('Find recent notices and official college updates.', 'የቅርብ ጊዜ ማስታወቂያዎችንና የኮሌጁን ወቅታዊ መረጃዎች ያግኙ።')) ?></p><a href="news.php"><?= e(tr('View news →', 'ዜናዎችን ይመልከቱ →')) ?></a></article>
  <article class="card"><h3><?= e(tr('Contact Us', 'ያግኙን')) ?></h3><p><?= e(tr('Contact the college for official information and enquiries.', 'ለኦፊሴላዊ መረጃና ጥያቄዎች ኮሌጁን ያግኙ።')) ?></p><a href="contact.php"><?= e(tr('Contact details →', 'የመገኛ መረጃ →')) ?></a></article>
</div>
<div class="section-heading"><h2><?= e(tr('Latest News', 'የቅርብ ጊዜ ዜናዎች')) ?></h2><a href="news.php"><?= e(tr('All news', 'ሁሉም ዜናዎች')) ?> →</a></div>
<?php if (!$news_items): ?><div class="empty"><?= e(tr('There are no published announcements yet.', 'እስካሁን የታተሙ ማስታወቂያዎች የሉም።')) ?></div>
<?php else: ?><div class="grid"><?php foreach ($news_items as $item): ?><article class="card"><div class="date"><?= e(date('M j, Y', strtotime($item['published_at']))) ?></div><h3><?= e(current_lang()==='am' && $item['title_am'] ? $item['title_am'] : $item['title_en']) ?></h3><p><?= nl2br(e(excerpt(current_lang()==='am' && $item['body_am'] ? $item['body_am'] : $item['body_en']))) ?></p><a href="news.php"><?= e(tr('Read news →', 'ዜናውን ያንብቡ →')) ?></a></article><?php endforeach; ?></div><?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
