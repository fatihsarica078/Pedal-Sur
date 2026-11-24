<?php
function send_rental_email($toEmail, $rentalId, $totalCost) {
    $subject = "Kiralama Onayı #" . $rentalId;
    $message = "Merhaba,\n\nKiralama işleminiz başarıyla kaydedilmiştir.\nKiralama ID: {$rentalId}\nToplam Tutar: {$totalCost} TL\n\nKeyifli sürüşler!";
    $headers = "From: no-reply@pedalve-sur.local\r\nReply-To: no-reply@pedalve-sur.local\r\nContent-Type: text/plain; charset=UTF-8";
    @mail($toEmail, $subject, $message, $headers);
}
?>