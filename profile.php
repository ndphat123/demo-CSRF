<?php
require 'init.php';
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Profile</title></head>
<body>
  <h3>Profile</h3>
  <pre><?php print_r($_SESSION); ?></pre>

  <?php if (isset($_SESSION['user'])): ?>
    <p>Xin chào, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></p>
    <form method="post" action="logout.php"><button type="submit">Logout</button></form>
  <?php else: ?>
    <p>Not logged in.</p>
    <p><a href="login_form_vuln.php">Login (vuln)</a> | <a href="login_form_secure.php">Login (secure)</a></p>
  <?php endif; ?>
</body>
</html>
