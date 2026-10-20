<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) || !hash_equals($_SESSION['csrf_token'] ?? '', $sent)) {
        http_response_code(403);
        exit('Invalid security token. Go back, refresh the page, and try again.');
    }
}
function is_admin(): bool {
    return isset($_SESSION['admin_id']) && (int)$_SESSION['admin_id'] > 0;
}
function require_admin(): void {
    if (!is_admin()) {
        header('Location: login.php');
        exit;
    }
}
function current_lang(): string {
    // Visitors may choose either language; Amharic is only the initial display, not a requirement.
    return ($_SESSION['lang'] ?? 'am') === 'en' ? 'en' : 'am';
}
function tr(string $en, string $am): string {
    return current_lang() === 'am' ? $am : $en;
}
function excerpt(string $text, int $length = 180): string {
    if (function_exists('mb_strlen') && mb_strlen($text, 'UTF-8') > $length) {
        return mb_substr($text, 0, $length, 'UTF-8') . '…';
    }
    return strlen($text) > $length ? substr($text, 0, $length) . '…' : $text;
}
