<?php
// actions/login_action.php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../pages/login.php");
  exit();
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);

if (empty($username) || empty($password)) {
  header("Location: ../pages/login.php?error=Username and password are required");
  exit();
}

try {
  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    header("Location: ../pages/dashboard.php");
  } else {
    header("Location: ../pages/login.php?error=Invalid username or password");
  }
} catch (PDOException $e) {
  header("Location: ../pages/login.php?error=Database error: " . $e->getMessage());
}
