<?php
function send_whatsapp_alert($phone, $message) {
    $apiUrl = "https://api.whatsapp-gateway.local/send";
    $postData = [
        'apiKey' => WA_API_KEY,
        'phone' => $phone,
        'message' => $message
    ];
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
?>