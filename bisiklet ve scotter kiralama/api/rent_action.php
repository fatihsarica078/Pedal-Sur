<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/security.php';
require_once __DIR__ . '/../services/mailer.php';
require_once __DIR__ . '/../services/whatsapp.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Lütfen önce giriş yapın.']);
    exit;
}

$vehicle_id = filter_input(INPUT_POST, 'vehicle_id', FILTER_VALIDATE_INT);
$csrf = $_POST['csrf_token'] ?? '';

if (!hash_equals($_SESSION['csrf_token'], $csrf) || !$vehicle_id) {
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz istek.']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = :id AND status = 'musait'");
$stmt->execute(['id' => $vehicle_id]);
$vehicle = $stmt->fetch();

if (!$vehicle) {
    echo json_encode(['status' => 'error', 'message' => 'Araç müsait değil veya mevcut değil.']);
    exit;
}

$pdo->beginTransaction();
try {
    $ins = $pdo->prepare("INSERT INTO rentals (user_id, vehicle_id, start_time, total_cost, status) VALUES (:uid, :vid, NOW(), :cost, 'aktif')");
    $ins->execute([
        'uid' => $_SESSION['user_id'],
        'vid' => $vehicle_id,
        'cost' => $vehicle['hourly_rate']
    ]);
    $rental_id = $pdo->lastInsertId();

    $upd = $pdo->prepare("UPDATE vehicles SET status = 'kirada' WHERE id = :id");
    $upd->execute(['id' => $vehicle_id]);

    $pdo->commit();

    send_rental_email($_SESSION['user_email'] ?? '', $rental_id, $vehicle['hourly_rate']);
    if (!empty($_SESSION['user_phone'])) {
        send_whatsapp_alert($_SESSION['user_phone'], "Kiralama başlatıldı. Araç: " . $vehicle['title']);
    }

    echo json_encode(['status' => 'success', 'message' => 'Kiralama başarıyla başlatıldı!']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'İşlem sırasında hata meydana geldi.']);
}
?>