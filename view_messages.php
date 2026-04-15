<?php
session_start();

// Simple authentication
$isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

if (!$isLoggedIn && isset($_POST['password'])) {
    if ($_POST['password'] === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $isLoggedIn = true;
    }
}

if (!$isLoggedIn):
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body { background: #0a0a0a; color: white; font-family: Arial; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login { background: #1a1a1a; padding: 30px; border-radius: 10px; }
        input, button { padding: 10px; margin: 10px 0; width: 100%; }
        button { background: #c5a059; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login">
        <h2>Admin Login</h2>
        <form method="post">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
<?php
    exit;
endif;

// Show messages
$messagesFile = 'messages.txt';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
    <style>
        body { background: #0a0a0a; color: white; font-family: monospace; padding: 20px; }
        .message { background: #1a1a1a; padding: 15px; margin: 10px 0; border-left: 3px solid #c5a059; white-space: pre-wrap; }
        h1 { color: #c5a059; }
    </style>
</head>
<body>
    <h1>📩 Contact Messages</h1>
    <a href="index.php" style="color: #c5a059;">← Back to Website</a>
    
    <?php if (file_exists($messagesFile)): ?>
        <pre style="background: #1a1a1a; padding: 20px; overflow-x: auto;"><?php echo htmlspecialchars(file_get_contents($messagesFile)); ?></pre>
    <?php else: ?>
        <p>No messages yet.</p>
    <?php endif; ?>
    
    <hr>
    <form method="post" style="margin-top: 20px;">
        <button type="submit" name="clear" style="background: #f44336; color: white; padding: 10px 20px; border: none; cursor: pointer;">Clear Messages</button>
    </form>
    
    <?php if (isset($_POST['clear'])): ?>
        <?php file_put_contents($messagesFile, ''); ?>
        <p style="color: green;">Messages cleared!</p>
        <meta http-equiv="refresh" content="1">
    <?php endif; ?>
</body>
</html>