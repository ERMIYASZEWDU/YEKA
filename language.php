<?php
require_once __DIR__ . '/includes/functions.php';
$language = $_GET['lang'] ?? 'am';
// Both languages are available for visitors to choose at any time.
$_SESSION['lang'] = $language === 'en' ? 'en' : 'am';
$back = $_SERVER['HTTP_REFERER'] ?? 'index.php';
// Only allow redirects to this site, not arbitrary external URLs.
$path = parse_url($back, PHP_URL_PATH) ?: '/yeka_college/index.php';
if (strpos($path, '/yeka_college/') !== 0) {
    $path = '/yeka_college/index.php';
}
$query = parse_url($back, PHP_URL_QUERY);
header('Location: ' . $path . ($query ? '?' . $query : ''));
exit;
