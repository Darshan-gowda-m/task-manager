<?php
// includes/functions.php

require_once 'config.php';
require_once 'auth.php';

function getUserTasks($user_id)
{
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = :user_id ORDER BY due_date ASC");
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTaskById($task_id, $user_id)
{
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = :task_id AND user_id = :user_id");
  $stmt->bindParam(':task_id', $task_id);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();

  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserById($user_id)
{
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();

  return $stmt->fetch(PDO::FETCH_ASSOC);
}
