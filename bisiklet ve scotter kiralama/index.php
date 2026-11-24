<?php
$page_title = "Ana Sayfa - Pedal & Sür";
require_once __DIR__ . '/includes/header.php';

$typeFilter = $_GET['type'] ?? 'all';
$query = "SELECT * FROM vehicles WHERE 1=1";
$params = [];

if (in_array($typeFilter, ['bisiklet', 'scooter'])) {
    $query .= " AND type = :type";
    $params['type'] = $typeFilter;
}
$query .= " ORDER BY id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$vehicles = $stmt->fetchAll();
?>

<div class="container">
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span>📍 <?= $lang['map_title']; ?></span>
        </div>
        <div class="card-body p-0">
            <div id="map" style="height: 380px; width: 100%;"></div>
        </div>
    </div>
>
    <div class="card mb-4 border-info shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-info">🤖 <?= $lang['ai_assistant']; ?></h5>
            <div class="input-group">
                <input type="text" id="aiInput" class="form-control" placeholder="<?= $lang['ai_placeholder']; ?>">
                <button class="btn btn-info text-white" onclick="getAiAdvice()"><?= $lang['ai_btn']; ?></button>
            </div>
            <div id="aiResult" class="mt-2 text-muted fw-bold"></div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><?= $lang['available_vehicles']; ?></h3>
        <div class="btn-group">
            <a href="index.php" class="btn btn-outline-secondary <?= $typeFilter === 'all' ? 'active' : ''; ?>"><?= $lang['all_vehicles']; ?></a>
            <a href="index.php?type=bisiklet" class="btn btn-outline-secondary <?= $typeFilter === 'bisiklet' ? 'active' : ''; ?>"><?= $lang['bike']; ?></a>
            <a href="index.php?type=scooter" class="btn btn-outline-secondary <?= $typeFilter === 'scooter' ? 'active' : ''; ?>"><?= $lang['scooter']; ?></a>
        </div>
    </div>

    <div class="row">
        <?php if (empty($vehicles)): ?>
            <div class="col-12"><div class="alert alert-warning">Uygun araç bulunamadı.</div></div>
        <?php else: ?>
            <?php foreach ($vehicles as $v): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= !empty($v['image']) ? SITE_URL . '/' . clean($v['image']) : 'https://placehold.co/600x400?text=' . urlencode($v['title']); ?>" class="card-img-top" alt="<?= clean($v['title']); ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <span class="badge bg-<?= $v['type'] === 'scooter' ? 'warning' : 'primary'; ?> mb-2"><?= ucfirst($v['type']); ?></span>
                            <h5 class="card-title"><?= clean($v['title']); ?></h5>
                            <p class="card-text text-muted mb-1"><?= $lang['hourly_rate']; ?>: <strong><?= format_price($v['hourly_rate']); ?></strong></p>
                            <p class="card-text text-muted"><?= $lang['battery_gear']; ?>: <?= clean($v['battery_or_gear'] ?? '-'); ?></p>
                            <a href="<?= SITE_URL; ?>/arac/<?= clean($v['slug']); ?>" class="btn btn-outline-primary w-100">İncele</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_KEY; ?>&callback=initMap" async defer></script>
<script>
let map;
function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 41.0082, lng: 28.9784 },
        zoom: 12
    });

    fetch('<?= SITE_URL; ?>/api/vehicles.php')
        .then(res => res.json())
        .then(data => {
            data.forEach(item => {
                new google.maps.Marker({
                    position: { lat: parseFloat(item.lat), lng: parseFloat(item.lng) },
                    map: map,
                    title: item.title + " (" + item.hourly_rate + " TL)"
                });
            });
        });
}

function getAiAdvice() {
    const prompt = document.getElementById('aiInput').value;
    const box = document.getElementById('aiResult');
    if (!prompt) return;

    box.innerText = "Yapay zeka yanıtı alınıyor...";
    fetch('<?= SITE_URL; ?>/api/ai_suggest.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ plan: prompt })
    })
    .then(r => r.json())
    .then(res => {
        box.innerText = res.reply;
    })
    .catch(() => {
        box.innerText = "Bir hata oluştu.";
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>