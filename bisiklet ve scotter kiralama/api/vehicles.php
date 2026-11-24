<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->prepare("SELECT id, title, slug, type, hourly_rate, lat, lng, status FROM vehicles WHERE status = 'musait'");
$stmt->execute();
$vehicles = $stmt->fetchAll();

echo json_encode($vehicles);
?>