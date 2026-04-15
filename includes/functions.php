<?php
/**
 * Helper functions for the photography portfolio
 */

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Generate unique access code for clients
 */
function generateAccessCode($length = 8) {
    return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $length));
}

/**
 * Get client by ID
 */
function getClientById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Get all images for a client
 */
function getClientImages($clientId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM client_images WHERE client_id = ? ORDER BY uploaded_at DESC");
    $stmt->execute([$clientId]);
    return $stmt->fetchAll();
}

/**
 * Log admin actions
 */
function logAdminAction($action) {
    $logFile = __DIR__ . '/../logs/admin.log';
    
    // Create logs directory if not exists
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0777, true);
    }
    
    $log = date('Y-m-d H:i:s') . " - " . ($_SESSION['admin_user'] ?? 'Unknown') . " - " . $action . PHP_EOL;
    file_put_contents($logFile, $log, FILE_APPEND);
}

/**
 * Delete image file and database record
 */
function deleteImage($imageId) {
    global $pdo;
    
    // Get image path first
    $stmt = $pdo->prepare("SELECT image_path FROM client_images WHERE id = ?");
    $stmt->execute([$imageId]);
    $image = $stmt->fetch();
    
    if ($image) {
        // Delete file from disk
        if (file_exists($image['image_path'])) {
            unlink($image['image_path']);
        }
        
        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM client_images WHERE id = ?");
        return $stmt->execute([$imageId]);
    }
    
    return false;
}

/**
 * Get client statistics
 */
function getClientStats($clientId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_images FROM client_images WHERE client_id = ?");
    $stmt->execute([$clientId]);
    $result = $stmt->fetch();
    
    return [
        'total_images' => $result['total_images'] ?? 0
    ];
}

/**
 * Validate file upload
 */
function validateImageFile($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['valid' => false, 'message' => 'Invalid file type. Allowed: JPG, PNG, GIF, WEBP'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['valid' => false, 'message' => 'File too large. Max 5MB'];
    }
    
    return ['valid' => true];
}
?>