<?php
require 'init.php';

// tạo token nếu chưa có
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['csrf_token'];
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login SECURE</title></head>
<body>
  <h3>Login (secure)</h3>
  <?php if (!empty($_SESSION['message'])) { echo '<p>'.htmlspecialchars($_SESSION['message']).'</p>'; unset($_SESSION['message']); } ?>
  <form method="post" action="login_handler_secure.php">
    <input name="username" placeholder="username" />
    <input name="password" placeholder="password" type="password" />
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token); ?>" />
    <button type="submit">Login</button>
  </form>
  <p><a href="profile.php">Go to profile</a></p>
</body>
</html>
