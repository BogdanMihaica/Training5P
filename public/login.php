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

require('views/login.view.php');
