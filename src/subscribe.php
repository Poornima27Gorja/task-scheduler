<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?error=1");
        exit;
    }

    if (subscribeEmail($email)) {
        header("Location: index.php?success=1");
        exit;
    } else {
        header("Location: index.php?error=1");
        exit;
    }
} else {
    // Optional: Redirect if accessed directly via GET
    header("Location: index.php");
    exit;
}
