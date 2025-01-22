<?php
session_start();

require basePath('config/config.php');

$loginData = $config['admin'];

$errorMessage = '';
if (isset($_SESSION['admin']) &&  $_SESSION['admin'] == true) {
    redirect('/');
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === $loginData['username'] && password_verify($_POST['password'], $loginData['password'])) {
        $_SESSION['admin'] = true;
        redirect('/products');
    } else {
        $errorMessage = 'Username or password don\'t match!';
        $_SESSION['_flash']['errors']['login'] = $errorMessage;
    }
}



require basePath('views/login.view.php');
