<?php
function export_rental_text_contract($rental, $user, $vehicle) {
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=sozlesme_{$rental['id']}.txt");
    
    echo "=================================================\n";
    echo "           KİRALAMA SÖZLEŞMESİ & FATURA          \n";
    echo "=================================================\n";
    echo "Sözleşme No  : #" . $rental['id'] . "\n";
    echo "Tarih        : " . date('Y-m-d H:i:s') . "\n";
    echo "Müşteri      : " . $user['fullname'] . " (" . $user['email'] . ")\n";
    echo "Araç         : " . $vehicle['title'] . " [" . strtoupper($vehicle['type']) . "]\n";
    echo "Saatlik Ücret: " . $vehicle['hourly_rate'] . " TL\n";
    echo "Başlangıç    : " . $rental['start_time'] . "\n";
    echo "Bitiş        : " . ($rental['end_time'] ?? 'Devam Ediyor') . "\n";
    echo "Toplam Tutar : " . $rental['total_cost'] . " TL\n";
    echo "=================================================\n";
    echo "Güvenli sürüşler dileriz.\n";
    exit;
}