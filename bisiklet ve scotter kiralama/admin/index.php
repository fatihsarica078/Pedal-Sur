<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/security.php';
check_auth('admin');

// Raporlama istatistikleri
$totalVehicles = $pdo->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
$activeRentals = $pdo->query("SELECT COUNT(*) FROM rentals WHERE status = 'aktif'")->fetchColumn();
$totalIncome = $pdo->query("SELECT SUM(total_cost) FROM rentals WHERE status = 'tamamlandi'")->fetchColumn() ?? 0;

// Tip bazlı istatistik grafiği için sorgu
$chartData = $pdo->query("SELECT type, COUNT(*) as count FROM vehicles GROUP BY type")->fetchAll(PDO::FETCH_KEY_PAIR);

require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Yönetim & Raporlama Paneli</h2>
        <div>
            <a href="vehicles.php" class="btn btn-primary">Araçları Yönet</a>
            <a href="vehicle_add.php" class="btn btn-success">+ Yeni Araç Ekle</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white p-3">
                <h5>Toplam Araç</h5>
                <h2><?= $totalVehicles; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white p-3">
                <h5>Aktif Kiralamalar</h5>
                <h2><?= $activeRentals; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark p-3">
                <h5>Toplam Gelir</h5>
                <h2><?= number_format($totalIncome, 2, ',', '.'); ?> TL</h2>
            </div>
        </div>
    </div>

    <div class="card p-3 shadow-sm" style="max-width: 500px;">
        <h5>Filo Dağılım Grafiği</h5>
        <canvas id="typeChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('typeChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Bisiklet', 'Scooter'],
        datasets: [{
            data: [<?= $chartData['bisiklet'] ?? 0; ?>, <?= $chartData['scooter'] ?? 0; ?>],
            backgroundColor: ['#0d6efd', '#ffc107']
        }]
    }
});
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>