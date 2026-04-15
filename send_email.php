<?php
header('Content-Type: application/json');

// Get data from request
$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$message = $data['message'] ?? '';

// Validate inputs
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address']);
    exit;
}

// Save to text file (Simple working solution)
$logFile = 'messages.txt';
$timestamp = date('Y-m-d H:i:s');
$logData = "========================================\n";
$logData .= "Time: $timestamp\n";
$logData .= "Name: $name\n";
$logData .= "Email: $email\n";
$logData .= "Message:\n$message\n";
$logData .= "========================================\n\n";

// Append to file
if (file_put_contents($logFile, $logData, FILE_APPEND)) {
    echo json_encode(['status' => 'success', 'message' => 'Message sent successfully! We will contact you soon.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save message. Please try again.']);
}
?>