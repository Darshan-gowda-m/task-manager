<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

redirectIfNotLoggedIn();

if (!isset($_GET['id'])) {
  header("Location: dashboard.php");
  exit();
}

$task_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$task = getTaskById($task_id, $user_id);

if (!$task) {
  header("Location: dashboard.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Task | Task Manager</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --primary-color: #06d6a0;
      --primary-light: #63f5c8;
      --priority-high: #ef476f;
      --priority-medium: #ffd166;
      --priority-low: #06d6a0;
    }

    [data-theme="dark"] {
      --bg: linear-gradient(135deg, #006466, #065a60, #0b525b, #144552, #1b3a4b, #212f45);
      --text: #f8f9fa;
      --card: #2b2d42;
      --input-bg: rgba(255, 255, 255, 0.05);
      --border: #495057;
    }

    [data-theme="light"] {
      --bg: #f8f9fa;
      --text: #212529;
      --card: #ffffff;
      --input-bg: #ffffff;
      --border: #ced4da;
    }

    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background: var(--bg);
      color: var(--text);
    }

    .container {
      max-width: 800px;
      margin: 2rem auto;
      padding: 2rem;
      background: var(--card);
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    header h1 {
      margin-bottom: 1rem;
    }

    .btn {
      background: var(--primary-color);
      color: #000;
      border: none;
      padding: 10px 18px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 500;
      cursor: pointer;
    }

    .btn:hover {
      background: var(--primary-light);
    }

    .btn.back {
      background-color: #adb5bd;
      color: #000;
      margin-bottom: 1rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
    }

    input[type="text"],
    input[type="date"],
    textarea,
    select {
      width: 100%;
      padding: 0.6rem;
      border-radius: 6px;
      border: 1px solid var(--border);
      background: var(--input-bg);
      color: var(--text);
    }

    textarea {
      resize: vertical;
    }

    .alert.error {
      background-color: #f8d7da;
      color: #721c24;
      padding: 10px;
      border: 1px solid #f5c6cb;
      margin-bottom: 15px;
      border-radius: 5px;
    }

    .priority-selector {
      display: flex;
      gap: 10px;
      margin-top: 8px;
    }

    .priority-option {
      padding: 8px 14px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      user-select: none;
      background: var(--input-bg);
      border: 1px solid var(--border);
      color: var(--text);
    }

    .priority-option.selected {
      background-color: var(--primary-color);
      color: #000;
      border-color: var(--primary-color);
    }

    .priority-high {
      background-color: #ef476f1a;
    }

    .priority-medium {
      background-color: #ffd1661a;
    }

    .priority-low {
      background-color: #06d6a01a;
    }

    .priority-option:hover {
      opacity: 0.9;
    }
  </style>
</head>

<body>
  <div class="container">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
      <h1 style="margin: 0;">Edit Task</h1>
      <a href="dashboard.php" class="btn back">← Back to Dashboard</a>
    </header>


    <?php if (isset($_GET['error'])): ?>
      <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <form action="../actions/update_task_action.php" method="POST" class="task-form">
      <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">

      <div class="form-group">
        <label for="title">Title*</label>
        <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($task['title']); ?>" required>
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($task['description']); ?></textarea>
      </div>

      <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-control">
          <option value="pending" <?php echo $task['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
          <option value="in_progress" <?php echo $task['status'] == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
          <option value="completed" <?php echo $task['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
        </select>
      </div>

      <div class="form-group">
        <label>Priority</label>
        <input type="hidden" id="priority" name="priority" value="<?php echo htmlspecialchars($task['priority'] ?? 'medium'); ?>">
        <div class="priority-selector">
          <div class="priority-option priority-high <?php echo ($task['priority'] ?? 'medium') === 'high' ? 'selected' : ''; ?>" data-priority="high">High</div>
          <div class="priority-option priority-medium <?php echo ($task['priority'] ?? 'medium') === 'medium' ? 'selected' : ''; ?>" data-priority="medium">Medium</div>
          <div class="priority-option priority-low <?php echo ($task['priority'] ?? 'medium') === 'low' ? 'selected' : ''; ?>" data-priority="low">Low</div>
        </div>
      </div>

      <div class="form-group">
        <label for="due_date">Due Date</label>
        <input type="date" id="due_date" name="due_date" class="form-control" value="<?php echo $task['due_date']; ?>" required>
      </div>

      <button type="submit" class="btn">✅ Update Task</button>
    </form>
  </div>

  <script>
    // Apply theme from local storage
    const root = document.documentElement;
    const storedTheme = localStorage.getItem("theme");
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = storedTheme || (prefersDark ? 'dark' : 'light');
    root.setAttribute('data-theme', theme);

    // Set min date to today
    const dueDateInput = document.getElementById('due_date');
    const today = new Date().toISOString().split('T')[0];
    dueDateInput.setAttribute('min', today);

    // Priority selection handling
    const priorityOptions = document.querySelectorAll('.priority-option');
    const priorityInput = document.getElementById('priority');
    priorityOptions.forEach(opt => {
      opt.addEventListener('click', () => {
        priorityOptions.forEach(o => o.classList.remove('selected'));
        opt.classList.add('selected');
        priorityInput.value = opt.dataset.priority;
      });
    });
  </script>
</body>

</html>