<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$tasks = getUserTasks($user_id);
$user = getUserById($user_id);

// Stats
$total_tasks = count($tasks);
$completed_tasks = $pending_tasks = $in_progress_tasks = 0;
foreach ($tasks as $task) {
  switch ($task['status']) {
    case 'completed':
      $completed_tasks++;
      break;
    case 'pending':
      $pending_tasks++;
      break;
    case 'in_progress':
      $in_progress_tasks++;
      break;
  }
}
$completion_rate = $total_tasks > 0 ? round(($completed_tasks / $total_tasks) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Task Manager - Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --primary-color: #06d6a0;
      --primary-light: #63f5c8;
      --priority-high: #ef476f;
      --priority-medium: #ffd166;
      --priority-low: #06d6a0;
    }

    [data-theme="light"] {
      --gradient-bg: linear-gradient(to right, #ffffff, #f0f0f0);
      --text-color: #212529;
      --text-light: #343a40;
      --text-muted: #6c757d;
      --card-bg: #ffffff;
      --card-hover: #f1f1f1;
      --border-color: #ced4da;
    }

    [data-theme="dark"] {
      --gradient-bg: linear-gradient(135deg, #006466 0%, #065a60 20%, #0b525b 40%, #144552 60%, #1b3a4b 80%, #212f45 100%);
      --text-color: #f8f9fa;
      --text-light: #e9ecef;
      --text-muted: #adb5bd;
      --card-bg: #2b2d42;
      --card-hover: #343a40;
      --border-color: #495057;
    }


    body {
      font-family: 'Inter', sans-serif;
      background: var(--gradient-bg);
      color: var(--text-color);
      margin: 0;
      padding: 0;
    }

    .desc-separator {
      border: none;
      height: 1px;
      background-color: rgba(255, 255, 255, 0.2);
      margin: 0.7rem 0;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: var(--card-bg);
      padding: 1rem 2rem;
      border-radius: 10px;
      margin-bottom: 2rem;
    }

    .btn {
      background: var(--primary-color);
      color: #000;
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 6px;
      cursor: pointer;
    }

    .btn:hover {
      background: var(--primary-light);
    }

    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: var(--card-bg);
      padding: 1rem;
      text-align: center;
      border-radius: 10px;
    }

    .task-controls {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .status-badge {
      padding: 0.3rem 0.6rem;
      border-radius: 12px;
      font-size: 0.85rem;
      font-weight: bold;
      color: white;
    }

    .status-badge.pending {
      background-color: #ffa502;
      /* Orange */
    }

    .status-badge.in_progress {
      background-color: #1e90ff;
      /* Dodger Blue */
    }

    .status-badge.completed {
      background-color: #2ed573;
      /* Green */
    }


    .filters {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .form-control {
      padding: 0.5rem 0.75rem;
      border-radius: 6px;
      border: 1px solid var(--border-color);
      background: rgba(255, 255, 255, 0.05);
      color: var(--text-light);
    }

    .task-card {
      background: var(--card-bg);
      padding: 1rem;
      border-radius: 10px;
      margin-bottom: 1rem;
    }

    .task-title {
      font-weight: 600;
      font-size: 1.2rem;
    }

    .task-meta,
    .task-actions {
      margin-top: 0.5rem;
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .priority-badge {
      padding: 0.3rem 0.6rem;
      border-radius: 12px;
      font-size: 0.85rem;
      font-weight: bold;
    }

    .priority-high {
      background: var(--priority-high);
      color: white;
    }

    .priority-medium {
      background: var(--priority-medium);
      color: black;
    }

    .priority-low {
      background: var(--priority-low);
      color: black;
    }

    .status-badge {
      padding: 0.3rem 0.6rem;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.1);
    }

    .due-over {
      color: #ff6b6b;
      font-weight: bold;
    }

    .fade-in {
      animation: fadeIn 0.3s ease;
    }

    /* Remove underlines from all anchor buttons */
    a.btn {
      text-decoration: none !important;
    }

    /* Optional: Ensure hover styles remain clean */
    a.btn:hover {
      text-decoration: none;
      opacity: 0.9;
    }

    /* Extra: Uniform button spacing and alignment */
    .task-actions a.btn {
      margin-right: 0.5rem;
    }

    /* Add spacing between description and due date/meta */
    .task-description {
      margin-bottom: 0.8rem;
    }

    /* Highlight overdue due dates */
    .due-over {
      color: #ff4d4f;
      font-weight: bold;
      display: inline-block;
      margin-top: 0.3rem;
    }

    /* Optional: Add red border/alert feel */
    .task-card .due-over::before {
      content: "⚠️ ";
      color: #ff4d4f;
      margin-right: 0.2rem;
    }

    @keyframes fadeIn {
      from {
        opacity: 0.5;
        transform: translateY(5px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <header>
      <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
      <div class="header-actions">
        <button id="theme-toggle" class="btn">🌓</button>
        <a href="../actions/logout_action.php" class="btn">Logout</a>
      </div>
    </header>

    <div class="stats-container">
      <div class="stat-card">Total Tasks: <?php echo $total_tasks; ?></div>
      <div class="stat-card">Completed: <?php echo $completed_tasks; ?></div>
      <div class="stat-card">In Progress: <?php echo $in_progress_tasks; ?></div>
      <div class="stat-card">Completion: <?php echo $completion_rate; ?>%</div>
    </div>

    <div class="task-controls">
      <a href="add_task.php" class="btn">➕ Add Task</a>
      <div class="filters">
        <select id="task-filter" class="form-control">
          <option value="all">All Status</option>
          <option value="pending">Pending</option>
          <option value="in_progress">In Progress</option>
          <option value="completed">Completed</option>
        </select>

        <select id="priority-filter" class="form-control">
          <option value="all">All Priorities</option>
          <option value="high">High</option>
          <option value="medium">Medium</option>
          <option value="low">Low</option>
        </select>

        <select id="due-filter" class="form-control">
          <option value="all">All Due Dates</option>
          <option value="1">Due in 1 day</option>
          <option value="2">Due in 2 days</option>
          <option value="5">Due in 5 days</option>
          <option value="7">Due in 1 week</option>
          <option value="30">Due in 1 month</option>
        </select>

        <select id="sort-by" class="form-control">
          <option value="">Sort By</option>
          <option value="due_date">Due Date</option>
          <option value="priority">Priority</option>
        </select>

        <input type="text" id="task-search" class="form-control" placeholder="🔍 Search...">

        <label><input type="checkbox" id="toggle-completed"> Hide Completed</label>
      </div>
    </div>

    <div class="task-list">
      <h2>Your Tasks</h2>
      <?php if (empty($tasks)): ?>
        <div>No tasks yet.</div>
      <?php else: ?>
        <div id="tasks-wrapper">
          <?php foreach ($tasks as $task): ?>
            <div class="task-card"
              data-status="<?php echo $task['status']; ?>"
              data-priority="<?php echo $task['priority']; ?>"
              data-title="<?php echo strtolower($task['title']); ?>"
              data-due="<?php echo $task['due_date']; ?>">
              <div class="task-title"><?php echo htmlspecialchars($task['title']); ?></div>
              <div class="task-meta">
                <span class="status-badge <?php echo $task['status']; ?>">
                  <?php echo ucfirst(str_replace('_', ' ', $task['status'])); ?>
                </span>

                <span class="priority-badge priority-<?php echo $task['priority']; ?>"><?php echo ucfirst($task['priority']); ?></span>
                <?php if ($task['due_date']):
                  $isOverdue = strtotime($task['due_date']) < strtotime(date('Y-m-d'));
                ?>
                  <span class="<?php echo $isOverdue ? 'due-over' : ''; ?>">
                    Due: <?php echo htmlspecialchars($task['due_date']); ?>
                  </span>
                <?php endif; ?>
              </div>
              <?php if (!empty($task['description'])): ?>
                <div class="task-description">

                  <?php if (strlen($task['description']) > 100) echo '...'; ?>
                </div>
                <hr class="desc-separator">
              <?php endif; ?>

              <div class="task-actions">
                <a href="view_task.php?id=<?php echo $task['id']; ?>" class="btn">👁 View</a>
                <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn">✏️ Edit</a>
                <a href="../actions/delete_task_action.php?id=<?php echo $task['id']; ?>" class="btn"
                  onclick="return confirm('Are you sure, confirm delete?')">❌ Delete</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    const root = document.documentElement;
    const toggleBtn = document.getElementById("theme-toggle");
    root.setAttribute("data-theme", localStorage.getItem("theme") || "light");
    toggleBtn.onclick = () => {
      const theme = root.getAttribute("data-theme") === "light" ? "dark" : "light";
      root.setAttribute("data-theme", theme);
      localStorage.setItem("theme", theme);
    };

    const cards = document.querySelectorAll(".task-card");
    const filter = document.getElementById("task-filter");
    const priority = document.getElementById("priority-filter");
    const due = document.getElementById("due-filter");
    const search = document.getElementById("task-search");
    const hideCompleted = document.getElementById("toggle-completed");
    const sortBy = document.getElementById("sort-by");

    function filterTasks() {
      const statusVal = filter.value;
      const priorityVal = priority.value;
      const dueVal = parseInt(due.value);
      const query = search.value.toLowerCase();
      const today = new Date();

      cards.forEach(card => {
        const status = card.dataset.status;
        const pri = card.dataset.priority;
        const title = card.dataset.title;
        const dueDate = new Date(card.dataset.due);
        let show = true;

        if (statusVal !== 'all' && status !== statusVal) show = false;
        if (priorityVal !== 'all' && pri !== priorityVal) show = false;
        if (dueVal && (isNaN(dueDate.getTime()) || (dueDate - today) / (1000 * 60 * 60 * 24) > dueVal)) show = false;
        if (hideCompleted.checked && status === 'completed') show = false;
        if (!title.includes(query)) show = false;

        card.style.display = show ? "block" : "none";
        if (show) card.classList.add("fade-in");
      });
    }

    filter.onchange = filterTasks;
    priority.onchange = filterTasks;
    due.onchange = filterTasks;
    hideCompleted.onchange = filterTasks;
    search.oninput = filterTasks;

    sortBy.onchange = () => {
      const wrapper = document.getElementById("tasks-wrapper");
      const sorted = Array.from(cards).sort((a, b) => {
        if (sortBy.value === "due_date") {
          return new Date(a.dataset.due) - new Date(b.dataset.due);
        } else if (sortBy.value === "priority") {
          const order = {
            high: 1,
            medium: 2,
            low: 3
          };
          return order[a.dataset.priority] - order[b.dataset.priority];
        }
        return 0;
      });
      sorted.forEach(c => wrapper.appendChild(c));
    };
  </script>
</body>

</html>