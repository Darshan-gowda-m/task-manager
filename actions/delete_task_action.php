<?php
// actions/delete_task_action.php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

redirectIfNotLoggedIn();

if (!isset($_GET['id'])) {
  header("Location: ../pages/dashboard.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'];

try {
  $stmt = $conn->prepare("DELETE FROM tasks WHERE id = :task_id AND user_id = :user_id");
  $stmt->bindParam(':task_id', $task_id);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();

  header("Location: ../pages/dashboard.php?success=Task deleted successfully!");
} catch (PDOException $e) {
  header("Location: ../pages/dashboard.php?error=Database error: " . $e->getMessage());
}
