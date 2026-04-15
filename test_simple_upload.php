<?php
header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Upload failed'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['image']['name']);
    $targetFile = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $response = ['status' => 'success', 'message' => 'Upload successful!', 'path' => $targetFile];
    } else {
        $response = ['status' => 'error', 'message' => 'Failed to move file'];
    }
}

echo json_encode($response);
?>