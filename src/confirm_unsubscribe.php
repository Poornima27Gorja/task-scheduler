<?php
require_once 'functions.php';

$done = false;
$fail = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $code = trim($_POST['code']);

    if (confirmUnsubscribe($email, $code)) {
        $done = true;
    } else {
        $fail = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Confirm Unsubscribe</title></head>
<body>
    <h2>Confirm Unsubscription</h2>

    <?php if ($done): ?>
        <p style="color:green;">✅ Unsubscribed successfully.</p>
    <?php elseif ($fail): ?>
        <p style="color:red;">❌ Invalid code or email.</p>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Your email" required />
        <input type="text" name="code" placeholder="Verification code" required />
        <button type="submit">Confirm Unsubscribe</button>
    </form>
</body>
</html>
