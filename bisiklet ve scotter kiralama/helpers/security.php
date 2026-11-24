<?php
function clean($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function verify_csrf($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die("Güvenlik hatası: Geçersiz CSRF belirteci!");
    }
}

function check_auth($required_role = null) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . SITE_URL . "/login.php");
        exit;
    }
    if ($required_role && ($_SESSION['user_role'] ?? '') !== $required_role) {
        http_response_code(403);
        die("Erişim reddedildi: Bu işlem için yetkiniz bulunmuyor.");
    }
}
?>