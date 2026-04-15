<?php
// Error reporting disable for clean responses
error_reporting(0);
ini_set('display_errors', 0);

// Database configuration for MySQL on port 3307
$host = '127.0.0.1';
$port = '3307';
$db   = 'dark_portfolio';
$user = 'root';
$pass = '';  // XAMPP default password is empty

try {
    // Create PDO connection with explicit port and options
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );
    
    // Optional: Test connection (uncomment if needed)
    // $stmt = $pdo->query("SELECT VERSION() as version");
    // $version = $stmt->fetch();
    // error_log("MySQL Connected! Version: " . $version['version']);
    
} catch (PDOException $e) {
    // Return JSON error instead of dying (for API calls)
    if (strpos($_SERVER['REQUEST_URI'], 'api.php') !== false) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed: ' . $e->getMessage()
        ]);
        exit;
    } else {
        // For regular pages, show HTML error
        die("Connection failed: " . $e->getMessage());
    }
}

// Function to test database connection (for debugging)
function testDatabaseConnection($pdo) {
    try {
        $stmt = $pdo->query("SELECT 1");
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Function to check if tables exist
function checkTablesExist($pdo) {
    $requiredTables = ['admin_users', 'clients', 'client_images'];
    $missingTables = [];
    
    foreach ($requiredTables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() == 0) {
            $missingTables[] = $table;
        }
    }
    
    return $missingTables;
}
?>