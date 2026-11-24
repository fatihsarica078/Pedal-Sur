<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/constants.php';

$host = 'localhost';
$db   = 'kiralama_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . htmlspecialchars($e->getMessage()));
}

// CSRF Token Kontrolü / Üretimi
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Çoklu Dil (i18n) Oturumu
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = in_array($_GET['lang'], ['tr', 'en']) ? $_GET['lang'] : 'tr';
}
$current_lang = $_SESSION['lang'] ?? 'tr';
$lang = require __DIR__ . "/../lang/{$current_lang}.php";
?>