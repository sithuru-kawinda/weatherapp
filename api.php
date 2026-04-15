<?php
error_reporting(0);
ini_set('display_errors', 0);

session_start();
require 'includes/db.php';

header('Content-Type: application/json');
$action = $_POST['action'] ?? $_GET['action'] ?? '';

function sendResponse($status, $data = [], $message = '') {
    $response = ['status' => $status];
    if (!empty($data)) $response = array_merge($response, $data);
    if (!empty($message)) $response['message'] = $message;
    echo json_encode($response);
    exit;
}

switch ($action) {
    
    case 'login':
        $user = trim($_POST['username'] ?? '');
        $pass = trim($_POST['password'] ?? '');
        
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
        $stmt->execute([$user, $pass]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['admin_logged_in'] = true;
            sendResponse('success');
        } else {
            sendResponse('error', [], 'Invalid credentials');
        }
        break;

    case 'create_client':
        if (!isset($_SESSION['admin_logged_in'])) {
            sendResponse('error', [], 'Unauthorized');
        }
        
        $name = trim($_POST['client_name'] ?? '');
        $code = strtoupper(substr(md5(uniqid()), 0, 8));
        $folder = 'uploads/clients/' . $code;
        
        if (!is_dir($folder)) mkdir($folder, 0777, true);
        
        $stmt = $pdo->prepare("INSERT INTO clients (client_name, access_code, folder_path) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $code, $folder])) {
            sendResponse('success', ['code' => $code]);
        } else {
            sendResponse('error', [], 'Failed to create client');
        }
        break;

    case 'get_clients':
        if (!isset($_SESSION['admin_logged_in'])) {
            sendResponse('error', [], 'Unauthorized');
        }
        $stmt = $pdo->query("SELECT id, client_name, access_code FROM clients ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll());
        break;

    case 'upload_image':
        if (!isset($_SESSION['admin_logged_in'])) {
            sendResponse('error', [], 'Unauthorized');
        }
        
        if (empty($_POST['client_id'])) {
            sendResponse('error', [], 'No client selected');
        }
        
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            sendResponse('error', [], 'No file uploaded');
        }
        
        $clientId = intval($_POST['client_id']);
        
        $stmt = $pdo->prepare("SELECT folder_path FROM clients WHERE id = ?");
        $stmt->execute([$clientId]);
        $client = $stmt->fetch();
        
        if (!$client) {
            sendResponse('error', [], 'Client not found');
        }
        
        $uploadDir = $client['folder_path'] . '/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $fileName = time() . '_' . rand(1000, 9999) . '.' . $ext;
        $targetFile = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = 'uploads/clients/' . basename($client['folder_path']) . '/' . $fileName;
            
            $insert = $pdo->prepare("INSERT INTO client_images (client_id, image_path, uploaded_at) VALUES (?, ?, NOW())");
            if ($insert->execute([$clientId, $imagePath])) {
                sendResponse('success', ['path' => $imagePath], 'Image uploaded successfully!');
            } else {
                sendResponse('error', [], 'Database insert failed');
            }
        } else {
            sendResponse('error', [], 'File move failed');
        }
        break;

    case 'client_access':
        $code = trim($_POST['code'] ?? '');
        $stmt = $pdo->prepare("SELECT id, client_name, access_code FROM clients WHERE access_code = ?");
        $stmt->execute([$code]);
        $client = $stmt->fetch();
        
        if ($client) {
            $_SESSION['client_id'] = $client['id'];
            $_SESSION['client_name'] = $client['client_name'];
            sendResponse('success', ['client' => $client]);
        } else {
            sendResponse('error', [], 'Invalid Access Code');
        }
        break;

    case 'get_gallery':
        if (!isset($_SESSION['client_id'])) {
            sendResponse('error', [], 'Unauthorized');
        }
        $stmt = $pdo->prepare("SELECT id, image_path, uploaded_at FROM client_images WHERE client_id = ? ORDER BY uploaded_at DESC");
        $stmt->execute([$_SESSION['client_id']]);
        $images = $stmt->fetchAll();
        
        foreach ($images as &$img) {
            $img['image_path'] = '/' . str_replace('\\', '/', $img['image_path']);
        }
        
        echo json_encode($images);
        break;

    default:
        sendResponse('error', [], 'Invalid Request');
        break;
}
?>