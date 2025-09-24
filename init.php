<!-- Mỗi file PHP gọi require 'init.php'; để set cookie/session param trước session_start() -->
<?php
// init.php
// cấu hình session cookie (đặt secure=>true nếu dùng HTTPS)
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'localhost',
    'secure' => false,    // true nếu bạn dùng https
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();
