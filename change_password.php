<?php
require_once 'auth.php';
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = (string)($_POST['current_password'] ?? '');
    $new     = (string)($_POST['new_password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');

    $stmt = $conn->prepare('SELECT password_hash FROM users WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || !password_verify($current, $row['password_hash'])) {
        $error = 'Current password is incorrect.';
    } elseif (strlen($new) < 8) {
        $error = 'New password must be at least 8 characters.';
    } elseif ($new !== $confirm) {
        $error = 'New passwords do not match.';
    } else {
        $upd = $conn->prepare('UPDATE users SET password_hash = :hash WHERE id = :id');
        $upd->execute([
            'hash' => password_hash($new, PASSWORD_DEFAULT),
            'id'   => $_SESSION['user_id'],
        ]);
        $success = 'Password updated successfully.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Change Password - NID</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/psalogo.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="card mt-4 mx-auto" style="max-width: 420px;">
    <div class="card-body">
      <button class="btn btn-secondary mb-3" onclick="history.back();">← Back</button>
      <h5 class="card-title fw-semibold mb-4">Change Password</h5>

      <?php if ($error): ?><div class="alert alert-danger py-2 fs-6"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <?php if ($success): ?><div class="alert alert-success py-2 fs-6"><?= htmlspecialchars($success) ?></div><?php endif; ?>

      <form method="post">
        <div class="mb-3">
          <label class="form-label">Current Password</label>
          <input type="password" name="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input type="password" name="new_password" class="form-control" required minlength="8">
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm New Password</label>
          <input type="password" name="confirm_password" class="form-control" required minlength="8">
        </div>
        <button type="submit" class="btn btn-primary w-100">Update Password</button>
      </form>
    </div>
  </div>
</body>
</html>
