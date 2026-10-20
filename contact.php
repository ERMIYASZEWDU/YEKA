<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
$page_title = tr('Contact', 'ያግኙን');
$success = false;
$error = '';
$name = $email = $phone = $address = $message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $address = trim((string)($_POST['address'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));
    if ($name === '' || mb_strlen($name) > 120) {
        $error = tr('Please enter your name (up to 120 characters).', 'እባክዎ ስምዎን ያስገቡ (እስከ 120 ቁምፊዎች)።');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
        $error = tr('Please enter a valid email address.', 'ትክክለኛ የኢሜይል አድራሻ ያስገቡ።');
    } elseif ($phone === '' || strlen($phone) > 30) {
        $error = tr('Please enter a phone number (up to 30 characters).', 'እባክዎ የስልክ ቁጥር ያስገቡ (እስከ 30 ቁምፊዎች)።');
    } elseif ($address === '' || mb_strlen($address) > 255) {
        $error = tr('Please enter your address (up to 255 characters).', 'እባክዎ አድራሻዎን ያስገቡ (እስከ 255 ቁምፊዎች)።');
    } elseif ($message === '' || mb_strlen($message) > 10000) {
        $error = tr('Please enter a message (maximum 10,000 characters).', 'እባክዎ መልዕክት ያስገቡ (ከ10,000 ቁምፊዎች አይበልጥ)።');
    } else {
        $stmt = $pdo->prepare('INSERT INTO contact_messages (sender_name, sender_email, sender_phone, sender_address, subject, message) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$name, $email, $phone, $address, 'Contact message', $message]);
        $success = true;
        $name = $email = $phone = $address = $message = '';
    }
}
require __DIR__ . '/includes/header.php';
?>
<section class="form-card">
  <h1><?= e(tr('Contact the College', 'ኮሌጁን ያግኙ')) ?></h1>
  <p class="muted"><?= e(tr('Send a message to the college. Staff can review it and record a reply in the admin dashboard.', 'ለኮሌጁ መልዕክት ይላኩ። ሰራተኞች መልዕክቱን በአስተዳዳሪ ዳሽቦርድ ማየትና ምላሽ መመዝገብ ይችላሉ።')) ?></p>
  <?php if ($success): ?><div class="notice"><?= e(tr('Your message was submitted successfully. Thank you for contacting the college.', 'መልዕክትዎ በተሳካ ሁኔታ ተልኳል። ኮሌጁን ስላገኙ እናመሰግናለን።')) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
  <form method="post" action="contact.php">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <div class="field"><label for="name"><?= e(tr('Full name', 'ሙሉ ስም')) ?> *</label><input id="name" name="name" maxlength="120" required value="<?= e($name) ?>"></div>
    <div class="field"><label for="email"><?= e(tr('Email address', 'ኢሜይል አድራሻ')) ?> *</label><input id="email" name="email" type="email" maxlength="190" required value="<?= e($email) ?>"></div>
    <div class="field"><label for="phone"><?= e(tr('Phone number', 'ስልክ ቁጥር')) ?> *</label><input id="phone" name="phone" type="tel" maxlength="30" required value="<?= e($phone) ?>" placeholder="+251..."></div>
    <div class="field"><label for="address"><?= e(tr('Address', 'አድራሻ')) ?> *</label><input id="address" name="address" maxlength="255" required value="<?= e($address) ?>"></div>
    <div class="field"><label for="message"><?= e(tr('Message', 'መልዕክት')) ?> *</label><textarea id="message" name="message" maxlength="10000" required><?= e($message) ?></textarea></div>
    <button type="submit"><?= e(tr('Send message', 'መልዕክት ላክ')) ?></button>
  </form>
</section>
<section class="card">
  <h2><?= e(tr('College contact details', 'የኮሌጁ የመገኛ መረጃ')) ?></h2>
  <p><?= e(tr('Address: Yeka, Addis Ababa, Ethiopia (please verify before publishing)', 'አድራሻ፦ የካ፣ አዲስ አበባ፣ ኢትዮጵያ (ከማተም በፊት ያረጋግጡ)')) ?></p>
  <p><?= e(tr('Add the college’s verified official phone number and email address here.', 'የኮሌጁን የተረጋገጠ ኦፊሴላዊ ስልክና ኢሜይል እዚህ ያስገቡ።')) ?></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
