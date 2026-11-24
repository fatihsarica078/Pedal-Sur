<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/security.php';
require_once __DIR__ . '/../helpers/utils.php';
require_once __DIR__ . '/../helpers/image.php';
check_auth('admin');

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf($_POST['csrf_token'] ?? '');

    $title = clean($_POST['title']);
    $type = clean($_POST['type']);
    $rate = (float)$_POST['hourly_rate'];
    $lat = (float)$_POST['lat'];
    $lng = (float)$_POST['lng'];
    $detail = clean($_POST['battery_or_gear']);
    $slug = slugify($title) . '-' . rand(100, 999);

    $imgPath = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $imgPath = process_and_upload_image($_FILES['image']);
    }

    $stmt = $pdo->prepare("INSERT INTO vehicles (title, slug, type, hourly_rate, battery_or_gear, lat, lng, image) VALUES (:title, :slug, :type, :rate, :detail, :lat, :lng, :img)");
    $stmt->execute([
        'title' => $title,
        'slug' => $slug,
        'type' => $type,
        'rate' => $rate,
        'detail' => $detail,
        'lat' => $lat,
        'lng' => $lng,
        'img' => $imgPath
    ]);

    header("Location: vehicles.php");
    exit;
}
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container" style="max-width: 650px;">
    <h3>Yeni Araç Ekle</h3>
    <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <div class="mb-2">
            <label>Araç Başlığı</label>
            <input type="text" name="title" class="form-control" required placeholder="Örn: Xiaomi Mi Pro 2">
        </div>
        <div class="mb-2">
            <label>Tür</label>
            <select name="type" class="form-select">
                <option value="scooter">Scooter</option>
                <option value="bisiklet">Bisiklet</option>
            </select>
        </div>
        <div class="mb-2">
            <label>Saatlik Ücret (TL)</label>
            <input type="number" step="0.5" name="hourly_rate" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Detay (Vites/Batarya)</label>
            <input type="text" name="battery_or_gear" class="form-control" placeholder="%100 Batarya veya 21 Vites">
        </div>
        <div class="row mb-2">
            <div class="col">
                <label>Enlem (Lat)</label>
                <input type="text" name="lat" class="form-control" value="41.0082" required>
            </div>
            <div class="col">
                <label>Boylam (Lng)</label>
                <input type="text" name="lng" class="form-control" value="28.9784" required>
            </div>
        </div>
        <div class="mb-3">
            <label>Araç Görseli (PHP GD ile işlenir)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button class="btn btn-primary w-100">Kaydet</button>
    </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>