<?php
require_once "functions.php";
date_default_timezone_set("Asia/Kolkata");

// Add Task
if (isset($_POST['add'])) {
    $taskName = trim($_POST['task-name']);
    if ($taskName !== '') {
        addTask($taskName);
    }
}

// Delete Task
if (isset($_POST['delete'])) {
    deleteTask($_POST['delete']);
    header("Location: index.php");
    exit();
}

// Toggle Task Completion
if (isset($_POST['toggle'])) {
    markTaskAsCompleted($_POST['toggle']); // toggle logic is handled in functions.php
    header("Location: index.php");
    exit();
}

$tasks = getAllTasks();
?>
<!DOCTYPE html>
<html>
<head>
    <title>🌟 Task Scheduler</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function toggleTheme() {
            document.body.classList.toggle("dark");
            localStorage.setItem("theme", document.body.classList.contains("dark") ? "dark" : "light");
        }

        window.onload = function () {
            if (localStorage.getItem("theme") === "dark") {
                document.body.classList.add("dark");
            }
        }
    </script>
</head>
<body>

<div class="container">
    <div class="toggle-container">
        <button class="theme-toggle" onclick="toggleTheme()">🌗 Toggle Theme</button>
    </div>

    <h2>📋 Task Scheduler</h2>

    <form method="POST" action="">
        <input type="text" name="task-name" id="task-name" placeholder="✍️ Enter new task..." required>
        <button type="submit" name="add" id="add-task">➕ Add</button>
    </form>

    <ul class="task-list">
        <?php if (!empty($tasks)): ?>
            <?php foreach ($tasks as $task): 
                $isChecked = $task['completed'] ? "checked" : "";
                $taskTime = isset($task['time']) ? htmlspecialchars($task['time']) : "Unknown";
                $taskClass = $task['completed'] ? "task-item completed" : "task-item";
            ?>
                <li class="<?= $taskClass ?>">
                    <form method="POST" style="margin:0; display:inline;">
                        <input type="hidden" name="toggle" value="<?= $task['id'] ?>">
                        <input type="checkbox" class="task-status" onchange="this.form.submit()" <?= $isChecked ?>>
                    </form>
                    <div>
                        <span class='task-name'><?= htmlspecialchars($task['name']) ?></span>
                        <span class='task-meta'>🕒 <?= $taskTime ?></span>
                    </div>
                    <form method="POST" style="margin:0; display:inline;">
                        <input type="hidden" name="delete" value="<?= $task['id'] ?>">
                        <button type="submit" class="delete-task">❌</button>
                    </form>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="no-task">✨ No tasks yet. Start by adding one above!</li>
        <?php endif; ?>
    </ul>

    <h2>📨 Subscribe for Task Reminders</h2>
    <form method="POST" action="subscribe.php">
        <input type="email" name="email" required />
        <button id="submit-email">📤 Subscribe</button>
    </form>

    <?php
    if (isset($_GET['success'])) {
        echo "<div class='alert alert-success'>✅ Verification email sent! Please check your inbox.</div>";
    }
    if (isset($_GET['error'])) {
        echo "<div class='alert alert-error'>❌ Something went wrong. Try again.</div>";
    }
    ?>
</div>

<script>
  const toggleButton = document.querySelector('.theme-toggle');
  const body = document.body;

  toggleButton.addEventListener('click', () => {
    body.classList.toggle('light-theme');
    if (body.classList.contains('light-theme')) {
      localStorage.setItem('theme', 'light');
      toggleButton.textContent = '☀️';
    } else {
      localStorage.setItem('theme', 'dark');
      toggleButton.textContent = '🌙';
    }
  });

  window.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
      body.classList.add('light-theme');
      toggleButton.textContent = '☀️';
    } else {
      toggleButton.textContent = '🌙';
    }
  });
</script>

</body>
</html>
