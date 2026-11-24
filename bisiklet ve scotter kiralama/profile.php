<?php
require_once __DIR__ . '/includes/header.php';
check_auth();

$user_id = $_SESSION['user_id'];

if (isset($_POST['end_rental_id'])) {
    verify_csrf($_POST['csrf_token'] ?? '');
    $rid = (int)$_POST['end_rental_id'];

    $rStmt = $pdo->prepare("SELECT r.*, v.hourly_rate FROM rentals r JOIN vehicles v ON r.vehicle_id = v.id WHERE r.id = :rid AND r.user_id = :uid AND r.status = 'aktif'");
    $rStmt->execute(['rid' => $rid, 'uid' => $user_id]);
    $rental = $rStmt->fetch();

    if ($rental) {
        $pdo->beginTransaction();
        $stmtUpd = $pdo->prepare("UPDATE rentals SET end_time = NOW(), status = 'tamamlandi' WHERE id = :id");
        $stmtUpd->execute(['id' => $rid]);

        $stmtVeh = $pdo->prepare("UPDATE vehicles SET status = 'musait' WHERE id = :vid");
        $stmtVeh->execute(['vid' => $rental['vehicle_id']]);
        $pdo->commit();
    }
}

$stmt = $pdo->prepare("
    SELECT r.*, v.title, v.type 
    FROM rentals r 
    JOIN vehicles v ON r.vehicle_id = v.id 
    WHERE r.user_id = :uid 
    ORDER BY r.id DESC
");
$stmt->execute(['uid' => $user_id]);
$rentals = $stmt->fetchAll();
?>

<div class="container">
    <h3 class="mb-4">Kiralama Geçmişim</h3>
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Araç</th>
                <th>Tür</th>
                <th>Başlangıç</th>
                <th>Bitiş</th>
                <th>Tutar</th>
                <th>Durum</th>
                <th>İşlem / Belge</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rentals as $row): ?>
                <tr>
                    <td>#<?= $row['id']; ?></td>
                    <td><?= clean($row['title']); ?></td>
                    <td><?= ucfirst($row['type']); ?></td>
                    <td><?= $row['start_time']; ?></td>
                    <td><?= $row['end_time'] ?? '-'; ?></td>
                    <td><?= format_price($row['total_cost']); ?></td>
                    <td>
                        <span class="badge bg-<?= $row['status'] === 'aktif' ? 'success' : 'secondary'; ?>">
                            <?= strtoupper($row['status']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($row['status'] === 'aktif'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="end_rental_id" value="<?= $row['id']; ?>">
                                <button class="btn btn-sm btn-danger">Sürüşü Bitir</button>
                            </form>
                        <?php else: ?>
                            <a href="<?= SITE_URL; ?>/admin/export_report.php?rental_id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-dark">Sözleşme / Fatura İndir</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>