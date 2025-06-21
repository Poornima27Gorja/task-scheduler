<?php
require_once 'functions.php';

$verified = false;
$error = false;

// Accept from either GET (from email link) or POST (form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $email = trim($_REQUEST['email'] ?? '');
    $code = trim($_REQUEST['code'] ?? '');

    if (!empty($email) && !empty($code)) {
        if (verifySubscription($email, $code)) {
            $verified = true;
        } else {
            $error = true;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Subscription</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 80px;
        }
        .message {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="message">
        <h2>🔐 Verify Your Subscription</h2>

        <?php if ($verified): ?>
            <p class="success">✅ Your email has been verified!</p>
        <?php elseif ($error): ?>
            <p class="error">❌ Invalid verification code or email.</p>
        <?php else: ?>
            <p>Waiting for verification info...</p>
        <?php endif; ?>
    </div>
</body>
</html>
