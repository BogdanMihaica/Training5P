<?php
session_start();

require_once('../common/functions.php');
require_once('../config/database.php');

$loginData = $data['admin'];

$errorMessage = '';
if (isset($_SESSION['admin']) &&  $_SESSION['admin'] == true) {
    header('Location: .');
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === $loginData['username'] && password_verify($_POST['password'], $loginData['password'])) {
        $_SESSION['admin'] = true;
        header('Location: products.php');
    } else {
        $errorMessage = translate('Username or password don\'t match!');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

<body>
    <?php include('../components/background.php') ?>

    <?php include('../components/language.php') ?>

    <div class="login-container">
        <div class="login-box">
            <h1 class="login-title"><?= translate('Welcome Back!') ?></h1>

            <p class="error"><?= $errorMessage ?></p>

            <form class="login-form" method="POST">
                <div class="input-group">
                    <label for="username"><?= translate('Username') ?></label>
                    <input type="text" id="username" name="username" placeholder="<?= translate('Enter your username') ?>" required>
                </div>
                <div class="input-group">
                    <label for="password"><?= translate('Password') ?></label>
                    <input type="password" id="password" name="password" placeholder="<?= translate('Enter your password') ?>" required>
                </div>
                <button type="submit" class="login-button"><?= translate('Login') ?></button>
            </form>
        </div>
    </div>
</body>

</html>