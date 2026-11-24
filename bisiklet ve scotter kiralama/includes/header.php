<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/utils.php';
require_once __DIR__ . '/../helpers/security.php';

$current_lang = $_SESSION['lang'] ?? 'tr';
?>
<!DOCTYPE html>
<html lang="<?= $current_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? clean($page_title) . ' - ' : ''; ?><?= $lang['brand_name'] ?? 'Pedal & Sür'; ?></title>
    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= SITE_URL; ?>/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= SITE_URL; ?>/index.php">
      <span class="fs-4">🚲</span>
      <span><?= $lang['brand_name'] ?? 'Pedal & Sür'; ?></span>
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Menüyü Aç/Kapat">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="<?= SITE_URL; ?>/index.php">
            <?= $lang['home'] ?? 'Ana Sayfa'; ?>
          </a>
        </li>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link text-warning fw-semibold" href="<?= SITE_URL; ?>/admin/index.php">
              ⚙️ <?= $lang['admin_panel'] ?? 'Yönetim Paneli'; ?>
            </a>
          </li>
        <?php endif; ?>
      </ul>

      <div class="d-flex flex-wrap align-items-center gap-2">
        <div class="btn-group btn-group-sm me-2" role="group" aria-label="Dil Seçimi">
          <a href="?lang=tr" class="btn btn-outline-light <?= $current_lang === 'tr' ? 'active' : ''; ?>">TR</a>
          <a href="?lang=en" class="btn btn-outline-light <?= $current_lang === 'en' ? 'active' : ''; ?>">EN</a>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="<?= SITE_URL; ?>/profile.php" class="btn btn-outline-info btn-sm">
            👤 <?= clean($_SESSION['user_name'] ?? 'Hesabım'); ?>
          </a>
          <a href="<?= SITE_URL; ?>/logout.php" class="btn btn-danger btn-sm">
            <?= $lang['logout'] ?? 'Çıkış Yap'; ?>
          </a>
        <?php else: ?>
          <a href="<?= SITE_URL; ?>/login.php" class="btn btn-outline-light btn-sm">
            <?= $lang['login'] ?? 'Giriş Yap'; ?>
          </a>
          <a href="<?= SITE_URL; ?>/register.php" class="btn btn-primary btn-sm">
            <?= $lang['register'] ?? 'Kayıt Ol'; ?>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<?php if (isset($_SESSION['flash_message'])): ?>
  <div class="container mt-3">
    <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info'; ?> alert-dismissible fade show shadow-sm" role="alert">
      <?= clean($_SESSION['flash_message']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
    </div>
  </div>
  <?php 
    unset($_SESSION['flash_message']); 
    unset($_SESSION['flash_type']); 
  ?>
<?php endif; ?>

<main class="flex-grow-1 py-4">