<!DOCTYPE html>
<html lang="en">

<?php include basePath('views/partials/header.php') ?>

<body>
    <?php include basePath('views/partials/background.php') ?>

    <?php include basePath('views/partials/language.php') ?>

    <div class="login-container">
        <div class="login-box">
            <h1 class="login-title"><?= translate('Welcome Back!') ?></h1>

            <p class="error"><?= getIfExists($errors, 'form') ?></p>

            <form class="login-form" method="POST">

                <div class="input-group">
                    <label for="username"><?= translate('Username') ?></label>
                    <input type="text" id="username" name="username" placeholder="<?= translate('Enter your username') ?>">
                    <p class="error"><?= getIfExists($errors, 'username') ?></p>
                </div>

                <div class="input-group">
                    <label for="password"><?= translate('Password') ?></label>
                    <input type="password" id="password" name="password" placeholder="<?= translate('Enter your password') ?>">
                    <p class="error"><?= getIfExists($errors, 'password') ?></p>
                </div>

                <button type="submit" class="login-button"><?= translate('Login') ?></button>

            </form>
        </div>
    </div>
</body>

</html>