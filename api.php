<?php
session_start();
require 'includes/db.php';

header('Content-Type: application/json');
$action = $_POST['action'] ?? $_GET['action'] ?? '';

function uploadFile($file, $targetDir) {
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $fileName = time() . '_' . basename($file["name"]);
    $targetFilePath = $targetDir . '/' . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    
    $allowedTypes = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            return $targetFilePath;
        }
    }
    return false;
}

switch ($action) {
    
    case 'login':
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
        $stmt->execute([$user, $pass]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['admin_logged_in'] = true;
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
        }
        break;

    case 'create_client':
        if (!isset($_SESSION['admin_logged_in'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            break;
        }
        
        $name = $_POST['client_name'];
        $code = strtoupper(substr(md5(uniqid()), 0, 8));
        $folder = 'uploads/clients/' . $code;

        if (!is_dir($folder)) mkdir($folder, 0777, true);

        $stmt = $pdo->prepare("INSERT INTO clients (client_name, access_code, folder_path) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $code, $folder])) {
            echo json_encode(['status' => 'success', 'code' => $code]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create client']);
        }
        break;

    case 'get_clients':
        if (!isset($_SESSION['admin_logged_in'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            break;
        }
        $stmt = $pdo->query("SELECT * FROM clients ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll());
        break;

    case 'upload_image':
        if (!isset($_SESSION['admin_logged_in'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            break;
        }
        
        $clientId = $_POST['client_id'];
        $stmt = $pdo->prepare("SELECT folder_path FROM clients WHERE id = ?");
        $stmt->execute([$clientId]);
        $client = $stmt->fetch();

        if ($client && isset($_FILES['image'])) {
            $path = uploadFile($_FILES['image'], $client['folder_path']);
            if ($path) {
                $ins = $pdo->prepare("INSERT INTO client_images (client_id, image_path) VALUES (?, ?)");
                $ins->execute([$clientId, $path]);
                echo json_encode(['status' => 'success', 'path' => $path]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Allowed: jpg, jpeg, png, webp, gif']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Client not found']);
        }
        break;

    case 'client_access':
        $code = $_POST['code'];
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE access_code = ?");
        $stmt->execute([$code]);
        $client = $stmt->fetch();

        if ($client) {
            $_SESSION['client_id'] = $client['id'];
            echo json_encode(['status' => 'success', 'client' => $client]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid Access Code']);
        }
        break;

    case 'get_gallery':
        if (!isset($_SESSION['client_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            break;
        }
        $stmt = $pdo->prepare("SELECT * FROM client_images WHERE client_id = ? ORDER BY uploaded_at DESC");
        $stmt->execute([$_SESSION['client_id']]);
        echo json_encode($stmt->fetchAll());
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
?>