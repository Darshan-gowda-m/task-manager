<?php
// actions/register_action.php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../pages/register.php");
  exit();
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);

if (empty($username) || empty($password) || empty($confirm_password)) {
  header("Location: ../pages/register.php?error=All fields are required");
  exit();
}

if ($password !== $confirm_password) {
  header("Location: ../pages/register.php?error=Passwords do not match");
  exit();
}

if (strlen($password) < 6) {
  header("Location: ../pages/register.php?error=Password must be at least 6 characters");
  exit();
}

try {
  // Check if username already exists
  $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  if ($stmt->fetch()) {
    header("Location: ../pages/register.php?error=Username already exists");
    exit();
  }

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user
  $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $hashed_password);
  $stmt->execute();

  // Get the new user ID
  $user_id = $conn->lastInsertId();

  // Log in the new user
  $_SESSION['user_id'] = $user_id;
  $_SESSION['username'] = $username;

  header("Location: ../pages/dashboard.php?success=Registration successful!");
} catch (PDOException $e) {
  header("Location: ../pages/register.php?error=Database error: " . $e->getMessage());
}
