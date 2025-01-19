<?php
require_once('../common/functions.php');
require_once('../config/database.php');

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if ($_POST['username'] === $admin_username && password_verify($_POST['password'], $admin_password)) {
            $_SESSION['username'] = $username;
        } else {
            $error_message = 'Username or password don\'t match!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

<body>
    <div class="big-circle top-right"></div>
    <div class="big-circle bottom-left"></div>

    <?php include('../components/language.php') ?>

    <div class="login-container">
        <div class="login-box">
            <h1 class="login-title"><?= translate('Welcome Back!') ?></h1>
            <p style="color:red"><?= $error_message ?></p>
            <form class="login-form" method="POST">
                <div class="input-group">
                    <label for="username"><?= translate('Username') ?></label>
                    <input type="text" id="username" name="username" placeholder="<?= translate('Enter your username') ?>" required>
                </div>
                <div class="input-group">
                    <label for="password"><?= translate('Password') ?></label>
                    <input type="password" id="password" name="password" placeholder="<?= translate('Enter your password') ?>" required>
                </div>
                <button type="submit" class="login-button">Login</button>
            </form>
        </div>
    </div>
</body>

</html>