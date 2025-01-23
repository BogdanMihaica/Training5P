<?php
session_start();

require basePath('config/config.php');

$loginData = $config['admin'];
$error = false;
$errors = ['form' => '', 'username' => '', 'password' => ''];

if (isset($_SESSION['admin']) &&  $_SESSION['admin'] == true) {
    redirect('/');
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['username'])) {
        $errors['username'] = 'Username cannot be empty';
        $error = true;
    }

    if (empty($_POST['password'])) {
        $errors['password'] = 'Password cannot be empty';
        $error = true;
    }

    if (
        $_POST['username'] === $loginData['username'] &&
        password_verify($_POST['password'], $loginData['password']) &&
        $error == false
    ) {
        $_SESSION['admin'] = true;
        redirect('/products');
    } else {
        $errors['form'] = 'Username or password don\'t match!';
    }
}



require basePath('views/login.view.php');
