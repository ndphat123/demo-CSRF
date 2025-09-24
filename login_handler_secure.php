<?php
require 'init.php';

// 1) Chỉ chấp nhận POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// 2) Kiểm tra token
$posted = $_POST['csrf_token'] ?? '';
$sessToken = $_SESSION['csrf_token'] ?? '';

if (!is_string($posted) || !is_string($sessToken) || !hash_equals($sessToken, $posted)) {
    http_response_code(403);
    // ghi message để debug (chỉ dev)
    $_SESSION['message'] = 'CSRF token invalid or missing';
    exit('CSRF token invalid');
}

// (optional) kiểm tra Origin nếu muốn:
// if (!empty($_SERVER['HTTP_ORIGIN'])) { check host... }

// 3) Xác thực user
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === 'attacker' && $password === 'attackerpass') {
    $_SESSION['user'] = ['username' => 'attacker', 'id' => 999];
    $_SESSION['message'] = 'Login successful (secure)';
    // xóa token 1 lần dùng (tùy chọn)
    unset($_SESSION['csrf_token']);
    header('Location: profile.php');
    exit;
}

$_SESSION['message'] = 'Login failed';
header('Location: login_form_secure.php');
exit;
