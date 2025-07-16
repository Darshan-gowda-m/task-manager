<?php
// actions/add_task_action.php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

redirectIfNotLoggedIn();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../pages/dashboard.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$title = trim($_POST['title']);
$description = trim($_POST['description']);
$status = $_POST['status'] ?? 'pending';
$due_date = $_POST['due_date'] ? $_POST['due_date'] : null;
$priority = $_POST['priority'] ?? 'medium'; // default to medium if not provided

if (empty($title)) {
  header("Location: ../pages/add_task.php?error=Title is required");
  exit();
}

try {
  $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, status, due_date, priority) 
                          VALUES (:user_id, :title, :description, :status, :due_date, :priority)");
  $stmt->bindParam(':user_id', $user_id);
  $stmt->bindParam(':title', $title);
  $stmt->bindParam(':description', $description);
  $stmt->bindParam(':status', $status);
  $stmt->bindParam(':due_date', $due_date);
  $stmt->bindParam(':priority', $priority);
  $stmt->execute();

  header("Location: ../pages/dashboard.php?success=Task added successfully!");
} catch (PDOException $e) {
  header("Location: ../pages/add_task.php?error=Database error: " . $e->getMessage());
}
