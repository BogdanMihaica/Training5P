<?php
session_start();

require basePath('config/config.php');

$loginData = $config['admin'];
$error = false;
$errors = [];

if (isset($_SESSION['admin']) &&  $_SESSION['admin'] == true) {
    redirect('/');
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isEmpty($_POST['username'])) {
        $errors['username'] = translate('Username cannot be empty');
        $error = true;
    }

    if (isEmpty($_POST['password'])) {
        $errors['password'] = translate('Password cannot be empty');
        $error = true;
    }

    if (
        !$error &&
        $_POST['username'] === $loginData['username'] &&
        password_verify($_POST['password'], $loginData['password'])
    ) {
        $_SESSION['admin'] = true;
        redirect('/products');
    } else {
        $errors['form'] = translate('Username or password don\'t match!');
    }
}



require basePath('views/login.view.php');
