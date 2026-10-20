<?php
require_once __DIR__ . '/functions.php';
$page_title = $page_title ?? tr('Yeka Industrial College', 'የካ ኢንዱስትሪያል ኮሌጅ');
?>
<!doctype html>
<html lang="<?= current_lang() === 'am' ? 'am' : 'en' ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($page_title) ?> | <?= e(tr('Yeka Industrial College', 'የካ ኢንዱስትሪያል ኮሌጅ')) ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
  <div class="brand"><img class="college-logo" src="assets/logo.jpg" alt="Yeka Industrial College logo"><div><strong><?= e(tr('Yeka Industrial College', 'የካ ኢንዱስትሪያል ኮሌጅ')) ?></strong><small><?= e(tr('Technical and Vocational Education and Training', 'የቴክኒክና ሙያ ትምህርትና ስልጠና')) ?></small></div></div>
  <nav class="nav">
    <a href="index.php"><?= e(tr('Home', 'መነሻ')) ?></a>
    <a href="about.php"><?= e(tr('About', 'ስለ ኮሌጁ')) ?></a>
    <a href="news.php"><?= e(tr('News', 'ዜናዎች')) ?></a>
    <a href="contact.php"><?= e(tr('Contact', 'ያግኙን')) ?></a>
    <span class="language-picker" aria-label="Choose language">
      <span class="language-label"><?= e(tr('Language', 'ቋንቋ')) ?>:</span>
      <a class="lang-link<?= current_lang() === 'am' ? ' active' : '' ?>" href="language.php?lang=am" lang="am" aria-current="<?= current_lang() === 'am' ? 'page' : 'false' ?>">አማርኛ</a>
      <span aria-hidden="true">|</span>
      <a class="lang-link<?= current_lang() === 'en' ? ' active' : '' ?>" href="language.php?lang=en" lang="en" aria-current="<?= current_lang() === 'en' ? 'page' : 'false' ?>">English</a>
    </span>
  </nav>
</header>
<main class="container">
