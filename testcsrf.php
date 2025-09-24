<?php
session_start();
require_once 'models/UserModel.php';
$userModel = new UserModel();

$id = 3;

// Kiểm tra user tồn tại
$user = $userModel->findUserById($id);

if (!empty($user)) {
    // Xóa user
    if ($userModel->deleteUserById($id)) {
        $msg = "✅ Đã xóa user id = $id!";
    } else {
        $msg = "⚠️ Xóa user thất bại!";
    }
} else {
    $msg = "⚠️ User id = $id không tồn tại!";
}

// Hiển thị popup alert
echo "<script>alert('$msg');</script>";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thông báo</title>
    <style>
        .flash-msg {
            padding: 15px;
            border-radius: 5px;
            margin: 20px auto;
            width: fit-content;
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="flash-msg"><?= $msg ?></div>
</body>
</html>