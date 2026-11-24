<?php
function ask_gemini_ai($prompt) {
    $apiKey = GEMINI_API_KEY;
    $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

    $payload = [
        "contents" => [[
            "parts" => [[
                "text" => "Sen bisiklet ve scooter kiralama uzmanısın. Kullanıcı sorusu: '{$prompt}'. Bisiklet mi elektrikli scooter mı kiralamalı, rota güvenliği tavsiyeleri nelerdir? Samimi ve 3 cümleyi aşmayan doğrudan bir yanıt üret."
            ]]
        ]]
    ];

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($response, true);
    return $json['candidates'][0]['content']['parts'][0]['text'] ?? 'Şu anda AI tavsiyesi üretilemedi.';
}
?>