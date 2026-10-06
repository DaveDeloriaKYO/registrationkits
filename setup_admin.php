<?php
/**
 * setup_admin.php
 *
 * Run this ONCE to create the `users` table (if needed) and the
 * single admin account. Delete this file from the server as soon
 * as the account is created — it is intentionally NOT behind
 * auth.php, since it's what creates the very first login.
 */

require_once 'db.php';

$conn->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )
");

$existingCount = (int)$conn->query('SELECT COUNT(*) FROM users')->fetchColumn();

$message = '';
$error = '';
$done = false;

if ($existingCount > 0) {
    $error = 'An admin account already exists. For security this script will not create another one. '
           . 'Delete setup_admin.php from the server now. If you need to reset the password, use change_password.php while logged in, '
           . 'or update the users table directly.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    $confirm  = (string)($_POST['confirm'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $conn->prepare('INSERT INTO users (username, password_hash) VALUES (:username, :hash)');
        $stmt->execute([
            'username' => $username,
            'hash'     => password_hash($password, PASSWORD_DEFAULT),
        ]);
        $done = true;
        $message = 'Admin account created. Delete setup_admin.php from the server now, then log in.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Set Up Admin Account - NID</title>
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:#f0f4f9; }
    .card { max-width: 420px; width:100%; }
  </style>
</head>
<body>
  <div class="card p-4 shadow-sm">
    <h5 class="mb-3">Create Admin Account</h5>

    <?php if ($error): ?><div class="alert alert-danger py-2 fs-6"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($done): ?>
      <div class="alert alert-success py-2 fs-6"><?= htmlspecialchars($message) ?></div>
      <a href="login.php" class="btn btn-primary w-100">Go to Login</a>
    <?php elseif ($existingCount === 0): ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required minlength="8">
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="confirm" class="form-control" required minlength="8">
        </div>
        <button type="submit" class="btn btn-primary w-100">Create Account</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
