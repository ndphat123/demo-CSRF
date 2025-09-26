<?php
// Bảo mật cookie trước khi start session
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '', // có thể để domain của bạn
    'secure' => isset($_SERVER['HTTPS']), // true nếu dùng HTTPS
    'httponly' => true, // JS không đọc được
    'samesite' => 'Lax' // hoặc 'Strict'
]);

session_start();

require_once 'models/UserModel.php';
$userModel = new UserModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = $userModel->auth($username, $password);

    if ($user) {
        // Chống session fixation
        session_regenerate_id(true);

        // Lưu user ID vào session
        $_SESSION['id'] = $user['id'];
        $_SESSION['message'] = 'Login successful';

        // Xử lý "Remember Me"
        if (!empty($_POST['remember'])) {
            // Tạo token
            $selector = bin2hex(random_bytes(8));
            $validator = bin2hex(random_bytes(32));
            $hashedValidator = password_hash($validator, PASSWORD_DEFAULT);

            // Lưu vào DB (bạn cần tạo thêm bảng remember_tokens)
            // Ví dụ: userModel->storeRememberToken($user['id'], $selector, $hashedValidator);
            // (Mình để comment vì phụ thuộc DB bạn)

            // Lưu cookie
            setcookie(
                'remember',
                $selector . ':' . $validator,
                [
                    'expires'  => time() + 60 * 60 * 24 * 30, // 30 ngày
                    'path'     => '/',
                    'secure'   => isset($_SERVER['HTTPS']),
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]
            );
        }

        header('Location: list_users.php');
        exit;
    } else {
        $_SESSION['message'] = 'Login failed';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <?php include 'views/meta.php' ?>
</head>
<body>
<?php include 'views/header.php'?>

<div class="container">
    <div id="loginbox" style="margin-top:50px;"
         class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">Login</div>
                <div style="float:right; font-size: 80%; position: relative; top:-10px">
                    <a href="#">Forgot password?</a>
                </div>
            </div>

            <div style="padding-top:30px" class="panel-body">
                <?php if (!empty($_SESSION['message'])): ?>
                    <div class="alert alert-info">
                        <?= htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <form method="post" class="form-horizontal" role="form">
                    <div class="margin-bottom-25 input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="login-username" type="text" class="form-control" name="username"
                               value="" placeholder="username or email" required>
                    </div>

                    <div class="margin-bottom-25 input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="login-password" type="password" class="form-control" name="password"
                               placeholder="password" required>
                    </div>

                    <div class="margin-bottom-25">
                        <input type="checkbox" tabindex="3" name="remember" id="remember">
                        <label for="remember"> Remember Me</label>
                    </div>

                    <div class="margin-bottom-25 input-group">
                        <div class="col-sm-12 controls">
                            <button type="submit" name="submit" value="submit" class="btn btn-primary">Login</button>
                            <a id="btn-fblogin" href="#" class="btn btn-primary">Login with Facebook</a>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 control">
                            Don't have an account?
                            <a href="form_user.php">Sign Up Here</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
