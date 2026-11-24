<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/security.php';
check_auth('admin');

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: vehicles.php");
    exit;
}

$vehicles = $pdo->query("SELECT * FROM vehicles ORDER BY id DESC")->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Araç Listesi</h3>
        <a href="vehicle_add.php" class="btn btn-success">+ Yeni Araç</a>
    </div>
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Resim</th>
                <th>Başlık</th>
                <th>Tür</th>
                <th>Saatlik Ücret</th>
                <th>Durum</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicles as $v): ?>
                <tr>
                    <td><?= $v['id']; ?></td>
                    <td>
                        <img src="<?= !empty($v['image']) ? SITE_URL . '/' . clean($v['image']) : 'https://placehold.co/80'; ?>" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                    </td>
                    <td><?= clean($v['title']); ?></td>
                    <td><?= ucfirst($v['type']); ?></td>
                    <td><?= $v['hourly_rate']; ?> TL</td>
                    <td><span class="badge bg-secondary"><?= $v['status']; ?></span></td>
                    <td>
                        <a href="vehicles.php?delete=<?= $v['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>