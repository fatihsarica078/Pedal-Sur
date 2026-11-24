<?php
// E-posta bildirimi (PHPMailer veya PHP mail)
function send_rental_email($toEmail, $rentalId, $totalCost) {
    $subject = "Kiralama Özeti - #" . $rentalId;
    $message = "Kiralama işleminiz başarıyla tamamlandı. Toplam Tutar: " . $totalCost . " TL";
    $headers = "From: no-reply@kiralamasistemi.com\r\nContent-Type: text/plain; charset=UTF-8";
    mail($toEmail, $subject, $message, $headers);
}

// WhatsApp Mesaj API Entegrasyonu (Twilio veya yerel SMS/WhatsApp gateway örneği)
function send_whatsapp_alert($phone, $message) {
    $apiUrl = "https://api.whatsapp-gateway.com/send";
    $data = [
        'apiKey' => 'WA_API_KEY',
        'receiver' => $phone,
        'message' => $message
    ];
    
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}
?>