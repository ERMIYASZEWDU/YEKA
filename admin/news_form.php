<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$item = ['title_en'=>'','title_am'=>'','body_en'=>'','body_am'=>'','status'=>'draft'];
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM news WHERE id = ?');
    $stmt->execute([$id]); $found = $stmt->fetch();
    if (!$found) { http_response_code(404); exit('Announcement not found.'); }
    $item = $found;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $title_en = trim((string)($_POST['title_en'] ?? ''));
    $title_am = trim((string)($_POST['title_am'] ?? ''));
    $body_en = trim((string)($_POST['body_en'] ?? ''));
    $body_am = trim((string)($_POST['body_am'] ?? ''));
    $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
    $item = compact('title_en','title_am','body_en','body_am','status');
    if ($title_en === '' || $body_en === '') {
        $error = 'English title and English announcement text are required.';
    } else {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE news SET title_en=?, title_am=?, body_en=?, body_am=?, status=?, published_at=CASE WHEN ?='published' THEN COALESCE(published_at,NOW()) ELSE NULL END WHERE id=?");
            $stmt->execute([$title_en,$title_am,$body_en,$body_am,$status,$status,$id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO news (title_en,title_am,body_en,body_am,status,published_at) VALUES (?,?,?,?,?,CASE WHEN ?='published' THEN NOW() ELSE NULL END)");
            $stmt->execute([$title_en,$title_am,$body_en,$body_am,$status,$status]);
        }
        header('Location: news.php?saved=1'); exit;
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $id ? 'Edit' : 'Add' ?> Announcement | YIC</title><link rel="stylesheet" href="../assets/style.css"></head><body><main class="container"><div class="admin-brand"><img src="../assets/logo.jpg" alt="Yeka Industrial College logo"><strong>Yeka Industrial College</strong></div><div class="form-card"><h1><?= $id ? 'Edit announcement' : 'Add announcement' ?></h1>
<?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="field"><label for="title_en">Title (English) *</label><input id="title_en" name="title_en" maxlength="250" required value="<?= e($item['title_en']) ?>"></div>
<div class="field"><label for="title_am">Title (Amharic)</label><input id="title_am" name="title_am" maxlength="250" value="<?= e($item['title_am']) ?>"></div>
<div class="field"><label for="body_en">Announcement (English) *</label><textarea id="body_en" name="body_en" required><?= e($item['body_en']) ?></textarea></div>
<div class="field"><label for="body_am">Announcement (Amharic)</label><textarea id="body_am" name="body_am"><?= e($item['body_am']) ?></textarea></div>
<div class="field"><label for="status">Status</label><select id="status" name="status"><option value="draft" <?= $item['status']==='draft'?'selected':'' ?>>Draft (not public)</option><option value="published" <?= $item['status']==='published'?'selected':'' ?>>Published (visible to visitors)</option></select></div>
<div class="actions"><button type="submit">Save announcement</button><a class="button-secondary" href="news.php">Cancel</a></div></form></div></main></body></html>
