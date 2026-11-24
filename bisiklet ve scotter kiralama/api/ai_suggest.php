<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../services/ai_service.php';

$raw = file_get_contents('php://input');
$input = json_decode($raw, true);

$prompt = trim($input['plan'] ?? '');
if (empty($prompt)) {
    echo json_encode(['reply' => 'Lütfen planınızı veya sorunuzu yazın.']);
    exit;
}

$reply = ask_gemini_ai($prompt);
echo json_encode(['reply' => $reply]);
?>