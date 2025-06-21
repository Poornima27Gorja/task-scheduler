<?php
$to = "poornima272321@gmail.com";
$subject = "HTML Email Test";
$message = "
<html>
<head>
  <title>HTML Email</title>
</head>
<body>
  <h1>Hello Poori 👋</h1>
  <p>This is an <strong>HTML email</strong> with a <a href='https://example.com'>link</a>.</p>
</body>
</html>
";

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: Test <no-reply@example.com>' . "\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "✅ HTML email sent successfully!";
} else {
    echo "❌ Failed to send HTML email.";
}
?>
