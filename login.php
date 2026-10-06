<?php
session_start();
require_once 'db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    $stmt = $conn->prepare('SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php');
        exit;
    }

    $error = 'Invalid username or password.';
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - NID</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/psalogo.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #4682B4, #315f86);
      margin: 0;
    }
    .login-card {
      width: 100%;
      max-width: 380px;
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    }
    .login-logos {
      display: flex;
      justify-content: center;
      gap: 16px;
      margin-bottom: 12px;
    }
    .login-logos img {
      height: 56px;
    }
    .login-title {
      text-align: center;
      font-size: 1.1rem;
      font-weight: 600;
      color: #333;
      text-transform: uppercase;
      letter-spacing: .5px;
      margin-bottom: 24px;
    }
    .password-wrapper {
      position: relative;
    }
    .password-wrapper .toggle-password {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #888;
      user-select: none;
      font-size: .85rem;
    }
  </style>
</head>

<body>
  <div class="card login-card">
    <div class="card-body p-4 p-md-5">
      <div class="login-logos">
        <img src="assets/images/logos/psalogo.png" alt="PSA Logo">
        <img src="assets/images/logos/NIDlogo.png" alt="NID Logo">
      </div>
      <div class="login-title">NID Database Management System</div>

      <?php if ($error): ?>
        <div class="alert alert-danger py-2 fs-6"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" autocomplete="off">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" id="username" name="username" class="form-control" required autofocus
                 value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        </div>

        <div class="mb-4">
          <label for="password" class="form-label">Password</label>
          <div class="password-wrapper">
            <input type="password" id="password" name="password" class="form-control" required>
            <span class="toggle-password" onclick="togglePassword()">Show</span>
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Log In</button>
      </form>
    </div>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const label = document.querySelector('.toggle-password');
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      label.textContent = isHidden ? 'Hide' : 'Show';
    }
  </script>
</body>

</html>
