<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

redirectIfLoggedIn();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Task Manager | Register</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --gradient-bg: linear-gradient(135deg, #006466 0%, #065a60 20%, #0b525b 40%, #144552 60%, #1b3a4b 80%, #212f45 100%);
      --primary-color: #06d6a0;
      --primary-light: #63f5c8;
      --border-color: #495057;
      --text-color: #f8f9fa;
      --text-light: #e9ecef;
      --text-muted: #adb5bd;
      --card-bg: #2b2d42;
      --danger-color: #d00000;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      transition: all 0.3s ease;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--gradient-bg);
      color: var(--text-color);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem;
    }

    .auth-container {
      width: 100%;
      max-width: 480px;
    }

    .auth-card {
      background: var(--card-bg);
      border-radius: 12px;
      padding: 2.5rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .auth-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .auth-header h1 {
      font-size: 2rem;
      color: var(--primary-color);
      margin-bottom: 0.5rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--text-light);
    }

    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      color: var(--text-color);
      font-size: 1rem;
      padding-right: 40px;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(6, 214, 160, 0.2);
    }

    .password-toggle {
      position: absolute;
      right: 12px;
      top: 58%;
      /* 👈 slight shift down */
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--text-muted);
      font-size: 18px;
      cursor: pointer;
    }


    .password-toggle:hover {
      color: var(--primary-color);
    }

    .btn {
      display: inline-block;
      width: 100%;
      background: var(--primary-color);
      color: #000;
      border: none;
      padding: 0.75rem 1rem;
      border-radius: 8px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn:hover {
      background: var(--primary-light);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(6, 214, 160, 0.3);
    }

    .auth-footer {
      text-align: center;
      margin-top: 1.5rem;
    }

    .alert.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
      padding: 10px 15px;
      margin-bottom: 20px;
      border-radius: 6px;
      font-weight: 500;
    }
  </style>
</head>

<body>
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-header">
        <h1>Create Account</h1>
        <p>Get started with your task management</p>
      </div>

      <?php if (isset($_GET['error'])): ?>
        <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>

      <form action="../actions/register_action.php" method="POST">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" class="form-control" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" class="form-control" required>
          <button type="button" class="password-toggle" aria-label="Show password">👁️</button>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
          <button type="button" class="password-toggle" aria-label="Show password">👁️</button>
        </div>

        <button type="submit" class="btn">Register</button>
      </form>

      <div class="auth-footer">
        <p>Already have an account? <a href="login.php" style="color: var(--primary-color);">Login here</a></p>
      </div>
    </div>
  </div>

  <script>
    document.querySelectorAll(".password-toggle").forEach(button => {
      button.addEventListener("click", () => {
        const input = button.previousElementSibling;
        if (input.type === "password") {
          input.type = "text";
          button.textContent = "🙈";
        } else {
          input.type = "password";
          button.textContent = "👁️";
        }
      });
    });
  </script>
</body>

</html>