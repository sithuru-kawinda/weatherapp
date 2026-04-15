<?php
header('Content-Type: application/json');

$images = [];

// Check main uploads folder
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

// Check clients folders
$clientsDir = 'uploads/clients/';
if (is_dir($clientsDir)) {
    $clientFolders = scandir($clientsDir);
    foreach ($clientFolders as $folder) {
        if ($folder != '.' && $folder != '..' && is_dir($clientsDir . $folder)) {
            $files = scandir($clientsDir . $folder);
            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $images[] = $clientsDir . $folder . '/' . $file;
                }
            }
        }
    }
}

// Reverse to show newest first
$images = array_reverse($images);

echo json_encode($images);
?>