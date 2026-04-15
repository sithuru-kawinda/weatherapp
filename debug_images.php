<?php
require 'includes/db.php';

header('Content-Type: text/html');

echo "<h1>Image Debug Information</h1>";

// Check database
$stmt = $pdo->query("SELECT * FROM client_images ORDER BY id DESC");
$images = $stmt->fetchAll();

echo "<h2>Database Records:</h2>";
if (count($images) > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Client ID</th><th>Image Path</th><th>Uploaded At</th><th>File Exists?</th><th>Preview</th></tr>";
    foreach ($images as $img) {
        $fullPath = __DIR__ . '/' . $img['image_path'];
        $fileExists = file_exists($fullPath);
        $fullPath = str_replace('\\', '/', $fullPath);
        
        echo "<tr>";
        echo "<td>{$img['id']}</td>";
        echo "<td>{$img['client_id']}</td>";
        echo "<td><small>{$img['image_path']}</small></td>";
        echo "<td>{$img['uploaded_at']}</td>";
        echo "<td>" . ($fileExists ? "✓ Yes" : "✗ No") . "</td>";
        echo "<td>";
        if ($fileExists) {
            echo "<img src='/{$img['image_path']}' style='max-width:100px; max-height:100px;' onerror='this.style.display=\"none\"'>";
        } else {
            echo "File missing at: <br><small>$fullPath</small>";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No images found in database.</p>";
}

// Check uploads directory
echo "<h2>Uploads Directory Structure:</h2>";
$uploadDir = __DIR__ . '/uploads/clients/';
if (is_dir($uploadDir)) {
    echo "<pre>";
    system("ls -la " . escapeshellarg($uploadDir));
    echo "</pre>";
    
    $clients = scandir($uploadDir);
    foreach ($clients as $client) {
        if ($client != '.' && $client != '..') {
            echo "<h3>Client: $client</h3>";
            $clientDir = $uploadDir . $client;
            if (is_dir($clientDir)) {
                $files = scandir($clientDir);
                echo "<ul>";
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..') {
                        $filePath = $clientDir . '/' . $file;
                        echo "<li>$file - " . filesize($filePath) . " bytes - <a href='/uploads/clients/$client/$file' target='_blank'>View</a></li>";
                    }
                }
                echo "</ul>";
            }
        }
    }
} else {
    echo "<p>Uploads directory does not exist!</p>";
}

// Fix: Update incorrect paths
echo "<h2>Fix Incorrect Paths:</h2>";
$updateStmt = $pdo->prepare("UPDATE client_images SET image_path = REPLACE(image_path, '\\', '/') WHERE image_path LIKE '%\\%'");
$updateStmt->execute();
echo "Updated " . $updateStmt->rowCount() . " records with backslashes.<br>";

$updateStmt2 = $pdo->prepare("UPDATE client_images SET image_path = CONCAT('/', image_path) WHERE image_path NOT LIKE '/%' AND image_path NOT LIKE 'http%'");
$updateStmt2->execute();
echo "Updated " . $updateStmt2->rowCount() . " records missing leading slash.<br>";
?>

<style>
    body { font-family: monospace; background: #0a0a0a; color: #fff; padding: 20px; }
    table { background: #1a1a1a; border-collapse: collapse; }
    th, td { padding: 10px; border: 1px solid #333; }
    h1, h2, h3 { color: #c5a059; }
    a { color: #4CAF50; }
</style>