<?php
// index.php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
  header("Location: pages/dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome | Task Manager</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --gradient-bg: linear-gradient(135deg, #006466 0%, #065a60 20%, #0b525b 40%, #144552 60%, #1b3a4b 80%, #212f45 100%);
      --primary-color: #06d6a0;
      --primary-light: #63f5c8;
      --text-color: #f8f9fa;
      --text-light: #e9ecef;
      --text-muted: #adb5bd;
      --card-bg: #2b2d42;
      --card-hover: #343a40;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--gradient-bg);
      color: var(--text-color);
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
      background: var(--card-bg);
      padding: 3rem;
      border-radius: 16px;
      max-width: 800px;
      width: 90%;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
      text-align: center;
      animation: fadeIn 0.5s ease-in-out;
    }

    h1 {
      font-size: 2.8rem;
      margin-bottom: 1rem;
      color: var(--primary-light);
    }

    p {
      font-size: 1.1rem;
      line-height: 1.7;
      color: var(--text-light);
      margin-bottom: 2rem;
    }

    .features {
      margin-top: 2rem;
      text-align: left;
      color: var(--text-muted);
    }

    .features ul {
      list-style: none;
      padding: 0;
    }

    .features li {
      margin-bottom: 0.7rem;
      position: relative;
      padding-left: 1.5rem;
    }

    .features li::before {
      content: "✔";
      position: absolute;
      left: 0;
      color: var(--primary-color);
    }

    .btn {
      background: var(--primary-color);
      color: #000;
      padding: 0.8rem 1.6rem;
      border-radius: 6px;
      font-weight: 600;
      text-decoration: none;
      margin: 0.5rem;
      display: inline-block;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn:hover {
      background: var(--primary-light);
      transform: translateY(-2px);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
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
    <h1>Task Manager</h1>
    <p>
      Welcome to <strong>Task Manager</strong> — your all-in-one productivity companion. Whether you're managing personal to-dos, coordinating with a team, or planning large projects, Task Manager helps you stay organized, focused, and efficient.
    </p>
    <div class="features">
      <ul>
        <li>🌟 Simple and intuitive interface for hassle-free task tracking</li>
        <li>📌 Set priorities — High, Medium, or Low — to focus on what matters</li>
        <li>🕒 Keep an eye on upcoming deadlines with due date reminders</li>
        <li>📊 Visualize your productivity with live stats and completion rate</li>
        <li>🌓 Light/Dark theme toggle for a comfortable experience</li>
        <li>🔐 Secure login and session-based access control</li>
      </ul>
    </div>
    <p>
      Ready to take control of your time and tasks?
    </p>
    <a href="pages/login.php" class="btn">🔐 Login</a>
    <a href="pages/register.php" class="btn">📝 Register</a>
  </div>

  <script>
    const root = document.documentElement;
    const savedTheme = localStorage.getItem("theme") || "light";
    root.setAttribute("data-theme", savedTheme);
  </script>
</body>

</html>