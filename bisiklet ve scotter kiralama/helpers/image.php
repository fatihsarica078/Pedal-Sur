<?php
function process_and_upload_image($file, $targetDir = "../uploads/vehicles/", $targetWidth = 600) {
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowed)) {
        return false;
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = uniqid('vehicle_', true) . '.' . $ext;
    $targetPath = $targetDir . $newName;

    list($width, $height) = getimagesize($file['tmp_name']);
    $ratio = $targetWidth / $width;
    $targetHeight = (int)($height * $ratio);

    $thumb = imagecreatetruecolor($targetWidth, $targetHeight);

    if ($file['type'] === 'image/png') {
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        $source = imagecreatefrompng($file['tmp_name']);
    } else {
        $source = imagecreatefromjpeg($file['tmp_name']);
    }

    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
    imagejpeg($thumb, $targetPath, 85);

    imagedestroy($thumb);
    imagedestroy($source);

    return 'uploads/vehicles/' . $newName;
}
?>