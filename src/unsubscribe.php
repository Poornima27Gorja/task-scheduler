<?php
require_once 'functions.php';

$unsubscribed = false;
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $email = trim($_GET['email'] ?? '');

    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $code = generateVerificationCode();
        if (unsubscribeEmail($email, $code)) {
            $unsubscribed = true; // email sent successfully
        } else {
            $error = true;
        }
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Unsubscribe</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: white;
            text-align: center;
            padding-top: 80px;
        }
        .message {
            margin-top: 20px;
            padding: 20px;
            border-radius: 6px;
            width: 80%;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .success {
            background-color: #28a745;
        }
        .error {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <h2>🛑 Unsubscribe</h2>

    <?php if ($unsubscribed): ?>
        <div class="message success">✅ A confirmation link has been sent to your email. Please click the link to complete unsubscription.</div>
    <?php elseif ($error): ?>
        <div class="message error">❌ Could not process your request. Please try again.</div>
    <?php endif; ?>
</body>
</html>
