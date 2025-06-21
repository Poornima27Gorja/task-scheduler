<?php

function generateVerificationCode(): string {
    return rand(100000, 999999);
}

// ---------------- TASK FUNCTIONS ----------------

function addTask(string $task_name): bool {
    $file = __DIR__ . '/tasks.txt';
    $tasks = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    foreach ($tasks as $task) {
        if (strtolower($task['name']) === strtolower($task_name)) return false;
    }

    $tasks[] = [
        'id' => uniqid(),
        'name' => $task_name,
        'completed' => false,
        'time' => date('d M Y, h:i A')
    ];

    file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    return true;
}

function getAllTasks(): array {
    $file = __DIR__ . '/tasks.txt';
    return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
}

function markTaskAsCompleted(string $task_id, ?bool $is_completed = null): bool {
    $file = __DIR__ . '/tasks.txt';
    $tasks = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    foreach ($tasks as &$task) {
        if ($task['id'] === $task_id) {
            $task['completed'] = $is_completed ?? !$task['completed'];
            file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
            return true;
        }
    }
    return false;
}

function deleteTask(string $task_id): bool {
    $file = __DIR__ . '/tasks.txt';
    $tasks = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    $tasks = array_filter($tasks, fn($task) => $task['id'] !== $task_id);
    file_put_contents($file, json_encode(array_values($tasks), JSON_PRETTY_PRINT));
    return true;
}

// ---------------- SUBSCRIPTION FUNCTIONS ----------------

function subscribeEmail(string $email): bool {
    $pending_file = __DIR__ . '/pending_subscriptions.txt';
    $pending = file_exists($pending_file) ? json_decode(file_get_contents($pending_file), true) : [];

    if (isset($pending[$email])) return false;

    $code = generateVerificationCode();
    $pending[$email] = [
        'code' => $code,
        'timestamp' => time()
    ];

    file_put_contents($pending_file, json_encode($pending, JSON_PRETTY_PRINT));

    $verification_link = "http://localhost/task-scheduler-Poornima27Gorja-main/src/verify.php?email=" . urlencode($email) . "&code=" . $code;

    $subject = "Verify subscription to Task Planner";
    $body = "
    <html><body>
        <p>Click the link below to verify your subscription to Task Planner:</p>
        <p><a href='$verification_link'>Verify Subscription</a></p>
    </body></html>";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: Task Scheduler <no-reply@example.com>\r\n";

    return mail($email, $subject, $body, $headers);
}

function verifySubscription(string $email, string $code): bool {
    $pending_file = __DIR__ . '/pending_subscriptions.txt';
    $subscribers_file = __DIR__ . '/subscribers.txt';

    $pending = file_exists($pending_file) ? json_decode(file_get_contents($pending_file), true) : [];

    if (!isset($pending[$email]) || trim($pending[$email]['code']) !== trim($code)) {
        return false;
    }

    unset($pending[$email]);
    file_put_contents($pending_file, json_encode($pending, JSON_PRETTY_PRINT));

    $subscribers = file_exists($subscribers_file) ? json_decode(file_get_contents($subscribers_file), true) : [];

    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        file_put_contents($subscribers_file, json_encode($subscribers, JSON_PRETTY_PRINT));
    }

    return true;
}

// ---------------- UNSUBSCRIPTION ----------------

function unsubscribeEmail($email, $code) {
    $pending_file = __DIR__ . '/unsubscribe_pending.txt';
    $pending = file_exists($pending_file) ? json_decode(file_get_contents($pending_file), true) : [];

    $pending[$email] = [
        'code' => $code,
        'timestamp' => time()
    ];

    file_put_contents($pending_file, json_encode($pending, JSON_PRETTY_PRINT));

    $unsubscribe_link = "http://localhost/task-scheduler-Poornima27Gorja-main/src/verify_unsubscribe.php?email=" . urlencode($email) . "&code=" . $code;

    $subject = "Confirm Unsubscription from Task Planner";
    $message = "
        <html><body>
        <p>Click below to confirm your unsubscription from Task Planner:</p>
        <p><a href='$unsubscribe_link'>Unsubscribe Now</a></p>
        </body></html>";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: Task Scheduler <no-reply@example.com>\r\n";

    return mail($email, $subject, $message, $headers);
}

// ---------------- TASK REMINDER + EMAIL ----------------

function sendTaskReminders() {
    $subscribers_file = __DIR__ . '/subscribers.txt';
    $tasks_file = __DIR__ . '/tasks.txt';

    $subscribers = file_exists($subscribers_file) ? json_decode(file_get_contents($subscribers_file), true) : [];
    $tasks = file_exists($tasks_file) ? json_decode(file_get_contents($tasks_file), true) : [];

    $pending_tasks = array_filter($tasks, fn($task) => !$task['completed']);

    foreach ($subscribers as $email) {
        sendTaskEmail($email, $pending_tasks);
    }
}

function sendTaskEmail(string $email, array $pending_tasks) {
    $subject = "Task Planner - Pending Tasks Reminder";

    $taskList = "<ul>";
    foreach ($pending_tasks as $task) {
        $taskList .= "<li>" . htmlspecialchars($task['name']) . "</li>";
    }
    $taskList .= "</ul>";

    // ✅ Generate unsubscribe code and store it
    $code = generateVerificationCode();
    $pending_file = __DIR__ . '/unsubscribe_pending.txt';
    $pending = file_exists($pending_file) ? json_decode(file_get_contents($pending_file), true) : [];

    $pending[$email] = [
        'code' => $code,
        'timestamp' => time()
    ];
    file_put_contents($pending_file, json_encode($pending, JSON_PRETTY_PRINT));

    $unsubscribe_link = "http://localhost/task-scheduler-Poornima27Gorja-main/src/verify_unsubscribe.php?email=" . urlencode($email) . "&code=" . $code;

    $body = "
    <html>
    <body>
        <h2>📝 Pending Tasks Reminder</h2>
        <p>Here are your current pending tasks:</p>
        $taskList
        <p>If you no longer wish to receive these reminders, you can <a href='$unsubscribe_link'>unsubscribe here</a>.</p>
    </body>
    </html>";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: Task Scheduler <no-reply@example.com>\r\n";

    mail($email, $subject, $body, $headers);
}
