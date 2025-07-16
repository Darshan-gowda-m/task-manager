<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

redirectIfNotLoggedIn();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Task | Task Manager</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    .priority-selector {
      display: flex;
      gap: 10px;
      margin-top: 8px;
    }

    .priority-option {
      padding: 8px 14px;
      border: 1px solid #ccc;
      border-radius: 4px;
      cursor: pointer;
      user-select: none;
    }

    .priority-option.selected {
      background-color: #3498db;
      color: #fff;
      border-color: #3498db;
    }

    .priority-high {
      background-color: #e74c3c1a;
    }

    .priority-medium {
      background-color: #f1c40f1a;
    }

    .priority-low {
      background-color: #2ecc711a;
    }

    .priority-option:hover {
      opacity: 0.8;
    }
  </style>
</head>

<body>
  <div class="container">
    <header class="dashboard-header">
      <h1>Add New Task</h1>
      <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </header>

    <div class="task-form-container">
      <form action="../actions/add_task_action.php" method="POST" class="task-form">
        <div class="form-group">
          <label for="title">Title*</label>
          <input type="text" id="title" name="title" class="form-control" required>
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" name="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="form-group">
          <label for="status">Status</label>
          <select id="status" name="status" class="form-control">
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
          </select>
        </div>

        <div class="form-group">
          <label>Priority</label>
          <input type="hidden" id="priority" name="priority" value="high">
          <div class="priority-selector">
            <div class="priority-option priority-high selected" data-priority="high">High</div>
            <div class="priority-option priority-medium" data-priority="medium">Medium</div>
            <div class="priority-option priority-low" data-priority="low">Low</div>
          </div>
        </div>

        <div class="form-group">
          <label for="due_date">Due Date</label>
          <input type="date" id="due_date" name="due_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Add Task</button>
      </form>
    </div>
  </div>

  <script>
    // Block past dates in due date field
    const dueDateInput = document.getElementById('due_date');
    const today = new Date().toISOString().split('T')[0];
    dueDateInput.setAttribute('min', today);

    // Priority selection logic
    const priorityOptions = document.querySelectorAll('.priority-option');
    const hiddenPriority = document.getElementById('priority');

    priorityOptions.forEach(option => {
      option.addEventListener('click', () => {
        priorityOptions.forEach(opt => opt.classList.remove('selected'));
        option.classList.add('selected');
        hiddenPriority.value = option.dataset.priority;
      });
    });
  </script>
</body>

</html>