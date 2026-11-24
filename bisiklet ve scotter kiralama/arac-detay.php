<?php
require_once __DIR__ . '/includes/header.php';

$slug = clean($_GET['slug'] ?? '');
$stmt = $pdo->prepare("SELECT * FROM vehicles WHERE slug = :slug LIMIT 1");
$stmt->execute(['slug' => $slug]);
$vehicle = $stmt->fetch();

if (!$vehicle) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Araç bulunamadı!</div></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
?>

<div class="container">
    <div class="card shadow-sm p-4">
        <div class="row">
            <div class="col-md-6">
                <img src="<?= !empty($vehicle['image']) ? SITE_URL . '/' . clean($vehicle['image']) : 'https://placehold.co/600x400'; ?>" class="img-fluid rounded" alt="<?= clean($vehicle['title']); ?>">
            </div>
            <div class="col-md-6">
                <h2><?= clean($vehicle['title']); ?></h2>
                <hr>
                <p><strong>Tür:</strong> <?= ucfirst($vehicle['type']); ?></p>
                <p><strong>Saatlik Ücret:</strong> <?= format_price($vehicle['hourly_rate']); ?></p>
                <p><strong>Durum:</strong> 
                    <span class="badge bg-<?= $vehicle['status'] === 'musait' ? 'success' : 'danger'; ?>">
                        <?= strtoupper($vehicle['status']); ?>
                    </span>
                </p>
                <p><strong>Özellik / Seviye:</strong> <?= clean($vehicle['battery_or_gear']); ?></p>

                <?php if ($vehicle['status'] === 'musait'): ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button class="btn btn-success btn-lg mt-3" onclick="rentVehicle(<?= $vehicle['id']; ?>)">Hemen Kirala</button>
                    <?php else: ?>
                        <a href="<?= SITE_URL; ?>/login.php" class="btn btn-warning mt-3">Kiralamak için Giriş Yapın</a>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="btn btn-secondary mt-3" disabled>Bu Araç Şu Anda Müsait Değil</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function rentVehicle(vid) {
    if (!confirm('Kiralamayı başlatmak istiyor musunuz?')) return;

    const fd = new FormData();
    fd.append('vehicle_id', vid);
    fd.append('csrf_token', '<?= $_SESSION['csrf_token']; ?>');

    fetch('<?= SITE_URL; ?>/api/rent_action.php', {
        method: 'POST',
        body: fd
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            window.location.href = '<?= SITE_URL; ?>/profile.php';
        }
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>