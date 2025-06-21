<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?error=invalid");
        exit();
    }

    // Generate 6-digit verification code
    $code = rand(100000, 999999);

    // Save subscriber (text file based)
    $subscriber = ["email" => $email, "code" => $code];
    file_put_contents("subscribers.txt", json_encode($subscriber, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

    // Prepare email
    $subject = "📋 Task Reminder Email Verification";
    $message = "Hi!\n\nThank you for subscribing to task reminders.\nYour verification code is: $code\n\nStay productive! 💪";
    $headers = "From: Task Scheduler <no-reply@yourdomain.com>";

    // Send email
    if (mail($email, $subject, $message, $headers)) {
        header("Location: index.php?success=1");
        exit();
    } else {
        header("Location: index.php?error=mailfail");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
