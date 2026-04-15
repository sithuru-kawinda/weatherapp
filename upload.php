<?php
header('Content-Type: application/json');

$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (in_array($ext, $allowed)) {
        $filename = time() . '_' . rand(1000, 9999) . '.' . $ext;
        $target = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo json_encode(['status' => 'success', 'message' => 'Uploaded']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Move failed']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No file']);
}
?>