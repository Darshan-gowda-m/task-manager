# Task Manager Application 📝

A modern, full-featured task management system built with PHP, MySQL, and JavaScript.

## ✨ Features

- 🔐 Secure user authentication (login/register)
- ✅ Complete CRUD operations for tasks
- 🏷️ Priority levels (High/Medium/Low)
- 📅 Due date tracking
- 🌗 Dark/Light mode toggle
- 📱 Fully responsive design
- 🎨 Interactive UI with smooth animations
- 📊 Task statistics and completion tracking

## 🚀 Getting Started

### Prerequisites

- PHP 8.0+
- MySQL 5.7+
- Web server (Apache/Nginx)
- Composer (recommended)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/Darshan-gowda-m/task-manager.git
   cd task-manager
   ```
   
2. Set up configuration:
```php
<?php
// includes/config.php

// Database configuration
define('DB_HOST', 'your_database_host');
define('DB_PORT', 'your_database_port');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_username');
define('DB_PASS', 'your_database_password');

try {
    // Create a new PDO connection
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die("Connection failed: " . $e->getMessage());
}

// Start a new session
session_start();
```

3. Import the database schema:

```sql
CREATE DATABASE task_manager;
USE task_manager;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    priority ENUM('high', 'medium', 'low') DEFAULT 'medium',
    due_date DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```


4. Save your project in the htdocs directory of XAMPP
5. Open XAMPP Control Panel and start the Apache server.
6. Open your browser and navigate to: http://localhost/project-folder/

