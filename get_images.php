<?php
header('Content-Type: application/json');

$images = [];
$uploadDir = 'uploads/';

if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    foreach ($files as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $images[] = $uploadDir . $file;
        }
    }
}

echo json_encode($images);
?>