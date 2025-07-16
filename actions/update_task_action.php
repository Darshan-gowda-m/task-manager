<?php
// actions/update_task_action.php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

redirectIfNotLoggedIn();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../pages/dashboard.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$task_id = $_POST['task_id'];
$title = trim($_POST['title']);
$description = trim($_POST['description']);
$status = $_POST['status'] ?? 'pending';
$due_date = $_POST['due_date'] ? $_POST['due_date'] : null;
$priority = $_POST['priority'] ?? 'medium'; // ✅ NEW: Capture priority input

if (empty($title)) {
  header("Location: ../pages/edit_task.php?id=$task_id&error=Title is required");
  exit();
}

try {
  $stmt = $conn->prepare("UPDATE tasks 
                           SET title = :title, 
                               description = :description, 
                               status = :status, 
                               due_date = :due_date,
                               priority = :priority
                           WHERE id = :task_id AND user_id = :user_id");
  $stmt->bindParam(':title', $title);
  $stmt->bindParam(':description', $description);
  $stmt->bindParam(':status', $status);
  $stmt->bindParam(':due_date', $due_date);
  $stmt->bindParam(':priority', $priority); // ✅ NEW: Bind priority
  $stmt->bindParam(':task_id', $task_id);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();

  header("Location: ../pages/dashboard.php?success=Task updated successfully!");
} catch (PDOException $e) {
  header("Location: ../pages/edit_task.php?id=$task_id&error=Database error: " . $e->getMessage());
}
