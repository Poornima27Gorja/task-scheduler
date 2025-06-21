<?php
require_once 'functions.php';

$showForm = false;
$invalidLink = false;
$email = '';
$code = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $email = trim($_GET['email'] ?? '');
    $code = trim($_GET['code'] ?? '');

    if (!empty($email) && !empty($code)) {
        // Check if code and email exist in unsubscribe_pending.txt
        $pending_file = __DIR__ . '/unsubscribe_pending.txt';
        $pending = file_exists($pending_file) ? json_decode(file_get_contents($pending_file), true) : [];

        if (isset($pending[$email]) && $pending[$email]['code'] === $code) {
            $showForm = true;
        } else {
            $invalidLink = true;
        }
    } else {
        $invalidLink = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>🔐 Verify Unsubscribe</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            text-align: center;
            padding: 50px;
        }
        .box {
            max-width: 450px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .box h2 {
            margin-bottom: 20px;
        }
        .box input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
        }
        .box button {
            padding: 10px 20px;
            background-color: #ff4b2b;
            color: white;
            border: none;
            cursor: pointer;
        }
        .error {
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="box">
    <?php if ($invalidLink): ?>
        <h2>❌ Invalid or expired unsubscription link.</h2>
    <?php elseif ($showForm): ?>
        <h2>🛑 Confirm Unsubscription</h2>
        <form method="POST" action="confirm_unsubscribe.php">
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" readonly />
            <input type="text" name="code" value="<?= htmlspecialchars($code) ?>" readonly />
            <button type="submit">Confirm Unsubscribe</button>
        </form>
    <?php else: ?>
        <h2>⏳ Processing...</h2>
    <?php endif; ?>
</div>
</body>
</html>
