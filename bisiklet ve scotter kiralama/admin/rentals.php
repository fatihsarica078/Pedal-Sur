<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/security.php';
check_auth('admin');

$stmt = $pdo->query("
    SELECT r.*, u.fullname, u.email, v.title 
    FROM rentals r 
    JOIN users u ON r.user_id = u.id 
    JOIN vehicles v ON r.vehicle_id = v.id 
    ORDER BY r.id DESC
");
$allRentals = $stmt->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <h3>Tüm Kiralama İşlemleri</h3>
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Kullanıcı</th>
                <th>Araç</th>
                <th>Başlangıç</th>
                <th>Bitiş</th>
                <th>Tutar</th>
                <th>Durum</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($allRentals as $r): ?>
                <tr>
                    <td>#<?= $r['id']; ?></td>
                    <td><?= clean($r['fullname']); ?> (<?= clean($r['email']); ?>)</td>
                    <td><?= clean($r['title']); ?></td>
                    <td><?= $r['start_time']; ?></td>
                    <td><?= $r['end_time'] ?? '-'; ?></td>
                    <td><?= $r['total_cost']; ?> TL</td>
                    <td><span class="badge bg-info"><?= $r['status']; ?></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>