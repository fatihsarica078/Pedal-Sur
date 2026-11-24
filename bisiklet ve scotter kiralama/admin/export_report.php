<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../services/pdf_service.php';
require_once __DIR__ . '/../helpers/security.php';

$rental_id = filter_input(INPUT_GET, 'rental_id', FILTER_VALIDATE_INT);
if (!$rental_id) {
    die("Geçersiz işlem.");
}

$stmt = $pdo->prepare("SELECT * FROM rentals WHERE id = :id");
$stmt->execute(['id' => $rental_id]);
$rental = $stmt->fetch();

if (!$rental) {
    die("Kayıt bulunamadı.");
}

$uStmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$uStmt->execute(['id' => $rental['user_id']]);
$user = $uStmt->fetch();

$vStmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = :id");
$vStmt->execute(['id' => $rental['vehicle_id']]);
$vehicle = $vStmt->fetch();

export_rental_text_contract($rental, $user, $vehicle);