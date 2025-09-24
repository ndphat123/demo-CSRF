
<!-- session_start();
session_destroy();
header('location: login.php'); -->
<?php
require 'init.php';
session_unset();
session_destroy();
header('Location: login_form_vuln.php');
exit;
?>