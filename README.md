# 🗓️ PHP Task Scheduler - Subscription & Reminder System

This is a PHP-based Task Scheduler project submitted for an internship assignment. It allows users to manage tasks, subscribe for email reminders, receive hourly notifications for pending tasks, and unsubscribe at any time.

---

## 🚀 Features Implemented

### ✅ Task Management
- Add new tasks with a name
- Prevent duplicate task names
- Mark tasks as complete/incomplete
- Delete tasks from the list
- Tasks saved in `tasks.txt` (JSON format)

### 📧 Email Subscription System
- User submits their email to subscribe
- System generates a 6-digit code
- Sends a verification email with a clickable link
- Upon clicking, email is added to `subscribers.txt`

### 🔁 Hourly Reminder System
- `cron.php` sends reminders every hour
- Includes only pending tasks
- Email includes an unsubscribe link
- Configured using `setup_cron.sh` (auto CRON job setup)

### ❌ Unsubscribe Feature
- One-click unsubscribe from reminder emails
- Email removed from `subscribers.txt`

---

## 📂 Folder Structure

src/
├── cron.php # Sends reminder emails
├── functions.php # All PHP logic (tasks + email)
├── index.php # Main user interface
├── style.css # Styling for index.php
├── subscribe.php # Handles subscription form
├── unsubscribe.php # Handles manual unsubscribe
├── verify.php # Confirms email verification
├── verify_unsubscribe.php # Confirms unsubscription
├── setup_cron.sh # Adds CRON job automatically
├── tasks.txt # Stores all tasks (JSON)
├── subscribers.txt # Verified subscribers
├── pending_subscriptions.txt # Pending email verifications
├── unsubscribe_pending.txt # Pending unsubscribe codes


### 🖥️ Localhost (XAMPP/LAMP)

1. **Clone Repository**
   ```bash
   git clone https://github.com/Poornima27Gorja/task-scheduler.git
   cd task-scheduler


----------subscribers.txt----------------
json
[
  "user1@example.com",
  "user2@example.com"
]

------------------- pending_subscriptions.txt---------------------
json
{
  "user1@example.com": {
    "code": "123456",
    "timestamp": 1718000000
  }
}


-------------------------🌐 How to Run the Project----------------------------
🔧 Local Setup (XAMPP Recommended)
Clone the repository

bash:
git clone https://github.com/Poornima27Gorja/task-scheduler.git
Move to XAMPP's htdocs folder

C:/xampp/htdocs/task-scheduler/src/
Start Apache Server

Open the app in your browser
http://localhost/task-scheduler/src/index.php


🕐 CRON Job Setup
✅ Auto Setup (Linux/macOS)
Run this in your terminal:

bash:
bash src/setup_cron.sh
This sets a CRON job to run cron.php every 1 hour and send pending task reminders.

🧪 Test manually (Windows)
Run this manually in terminal/command prompt:
bash:
php src/cron.php



------------------------💌 Email Format (Strict Requirement)---------------
🔹 Verification Email
Subject: Verify subscription to Task Planner
HTML Body:

<p>Click the link below to verify your subscription to Task Planner:</p>
<p><a id="verification-link" href="{verification_link}">Verify Subscription</a></p>
🔹 Reminder Email
Subject: Task Planner - Pending Tasks Reminder
HTML Body:

html
<h2>Pending Tasks Reminder</h2>
<p>Here are the current pending tasks:</p>
<ul>
  <li>Task 1</li>
  <li>Task 2</li>
</ul>
<p><a id="unsubscribe-link" href="{unsubscribe_link}">Unsubscribe from notifications</a></p>
📋 HTML Form & Input Requirements
🔸 Add Task
html
Copy
Edit
<input type="text" name="task-name" id="task-name" required />
<button id="add-task">Add Task</button>
🔸 Email Subscription
html
Copy
Edit
<input type="email" name="email" required />
<button id="submit-email">Submit</button>
🔸 Task UI Class Names
html
Copy
Edit
<ul class="tasks-list">
  <li class="task-item completed">
    <input type="checkbox" class="task-status" />
    <button class="delete-task">Delete</button>
  </li>
</ul>
🛠 Functions Implemented in functions.php
addTask($task_name)

getAllTasks()

markTaskAsCompleted($task_id, $is_completed)

deleteTask($task_id)

generateVerificationCode()

subscribeEmail($email)

verifySubscription($email, $code)

unsubscribeEmail($email, $code)

sendTaskReminders()

sendTaskEmail($email, $pending_tasks)


------------------------------------------------------------
🧾 Checklist for Evaluator
✅ Task	Status
Use of text files only	✔️
All code inside src/	✔️
HTML emails only	✔️
Unsubscribe functionality	✔️
CRON job setup with .sh	✔️
Proper email structure	✔️
One Pull Request from branch	✔️
------------------------------------------------------------


👤 Author
👩 Gorja Poornima
📧 Email: poornima272321@gmail.com
🌐 GitHub: Poornima27Gorja

