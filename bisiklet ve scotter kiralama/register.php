<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/helpers/security.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf($_POST['csrf_token'] ?? '');
    $name = clean($_POST['fullname'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $pass = $_POST['password'] ?? '';

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($pass) >= 6) {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, phone, role) VALUES (:name, :email, :pass, :phone, 'musteri')");
            $stmt->execute(['name' => $name, 'email' => $email, 'pass' => $hash, 'phone' => $phone]);
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $msg = "E-posta adresi zaten kullanımda.";
        }
    } else {
        $msg = "Lütfen alanları eksiksiz ve en az 6 haneli şifreyle doldurun.";
    }
}
require_once __DIR__ . '/includes/header.php';
?>
<div class="container" style="max-width: 450px;">
    <div class="card shadow-sm p-4 mt-4">
        <h4 class="mb-3 text-center">Yeni Kayıt</h4>
        <?php if ($msg): ?><div class="alert alert-warning"><?= $msg; ?></div><?php endif; ?>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <div class="mb-2">
                <label>Ad Soyad</label>
                <input type="text" name="fullname" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>E-posta</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>Telefon</label>
                <input type="text" name="phone" class="form-control" placeholder="905xxxxxxxxx" required>
            </div>
            <div class="mb-3">
                <label>Parola</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-success w-100">Kayıt Ol</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>