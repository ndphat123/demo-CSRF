<?php
require 'init.php';
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login VULN</title></head>
<body>
  <h3>Login (vulnerable)</h3>
  <?php if (!empty($_SESSION['message'])) { echo '<p>'.htmlspecialchars($_SESSION['message']).'</p>'; unset($_SESSION['message']); } ?>
  <form method="post" action="login_handler_vuln.php">
    <input name="username" placeholder="username" />
    <input name="password" placeholder="password" type="password" />
    <button type="submit">Login</button>
  </form>
  <p><a href="profile.php">Go to profile</a></p>
</body>
</html>
