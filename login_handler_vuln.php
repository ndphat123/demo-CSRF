<?php
require 'init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login_form_vuln.php'); exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// giả lập auth: username=attacker, password=attackerpass là hợp lệ
if ($username === 'attacker' && $password === 'attackerpass') {
    $_SESSION['user'] = ['username' => 'attacker', 'id' => 999];
    $_SESSION['message'] = 'Login successful (attacker)';
    header('Location: profile.php');
    exit;
}

$_SESSION['message'] = 'Login failed';
header('Location: login_form_vuln.php');
exit;
