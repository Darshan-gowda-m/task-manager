<?php
// pages/view_task.php
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Task Manager - View Task</title>
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
      --gradient-bg: linear-gradient(135deg, #006466, #065a60, #0b525b, #144552, #1b3a4b, #212f45);
      --text-color: #f8f9fa;
      --text-light: #e9ecef;
      --card-bg: #2b2d42;
      --card-hover: #343a40;
      --border-color: #495057;
    }

    [data-theme="light"] {
      --gradient-bg: linear-gradient(to right, #ffffff, #f8f9fa);
      --text-color: #212529;
      --text-light: #495057;
      --card-bg: #ffffff;
      --card-hover: #f1f1f1;
      --border-color: #ced4da;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--gradient-bg);
      color: var(--text-color);
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 800px;
      margin: 2rem auto;
      padding: 2rem;
      background: var(--card-bg);
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    h1 {
      margin: 0;
      font-size: 1.8rem;
    }

    .btn {
      background: var(--primary-color);
      color: #000;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 500;
    }

    .btn:hover {
      background: var(--primary-light);
    }

    .task-details h2 {
      margin-top: 0;
      font-size: 1.5rem;
    }

    .detail-row {
      margin-bottom: 1rem;
    }

    .label {
      font-weight: 600;
      display: inline-block;
      min-width: 120px;
      color: var(--text-light);
    }

    .description {
      background: var(--card-hover);
      padding: 1rem;
      border-radius: 6px;
      color: var(--text-color);
    }

    .status-badge {
      padding: 0.3rem 0.6rem;
      border-radius: 12px;
      font-weight: bold;
      font-size: 0.9rem;
      color: #fff;
    }

    .pending {
      background-color: #ffa502;
    }

    .in_progress {
      background-color: #1e90ff;
    }

    .completed {
      background-color: #2ed573;
    }

    .task-actions {
      margin-top: 2rem;
      display: flex;
      gap: 1rem;
    }

    .task-actions .btn.edit {
      background-color: #1e90ff;
      color: #fff;
    }

    .task-actions .btn.delete {
      background-color: #ff6b6b;
      color: #fff;
    }

    .task-actions .btn.edit:hover {
      background-color: #63b3ff;
    }

    .task-actions .btn.delete:hover {
      background-color: #ff8787;
    }
  </style>
</head>

<body>
  <div class="container">
    <header>
      <h1>View Task</h1>
      <a href="dashboard.php" class="btn">← Back</a>
    </header>

    <div class="task-details">
      <h2><?php echo htmlspecialchars($task['title']); ?></h2>

      <div class="detail-row">
        <span class="label">Status:</span>
        <span class="status-badge <?php echo $task['status']; ?>">
          <?php echo ucfirst(str_replace('_', ' ', $task['status'])); ?>
        </span>
      </div>

      <?php if ($task['due_date']): ?>
        <div class="detail-row">
          <span class="label">Due Date:</span>
          <span><?php echo htmlspecialchars($task['due_date']); ?></span>
        </div>
      <?php endif; ?>



      <?php if ($task['updated_at'] && $task['updated_at'] != $task['created_at']): ?>
        <div class="detail-row">
          <span class="label">Last Updated:</span>
          <span><?php echo htmlspecialchars($task['updated_at']); ?></span>
        </div>
      <?php endif; ?>

      <div class="detail-row full-width">
        <span class="label">Description:</span>
        <div class="description">
          <?php echo $task['description'] ? nl2br(htmlspecialchars($task['description'])) : 'No description provided.'; ?>
        </div>
      </div>

      <div class="task-actions">
        <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn edit">✏️ Edit Task</a>
        <a href="../actions/delete_task_action.php?id=<?php echo $task['id']; ?>"
          class="btn delete"
          onclick="return confirm('Are you sure you want to delete this task?')">❌ Delete Task</a>
      </div>
    </div>
  </div>

  <script>
    // Theme toggle support
    const root = document.documentElement;
    const storedTheme = localStorage.getItem("theme");
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = storedTheme || (prefersDark ? 'dark' : 'light');
    root.setAttribute('data-theme', theme);
  </script>
</body>

</html>