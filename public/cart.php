<?php
require_once('../config/database.php');
require_once('../common/functions.php');
require_once('../utils/email_template.php');

session_start();

$cartItems = $_SESSION['cart'];
$result = [];
$error = ['name' => '', 'email' => ''];
$config = $data['mail'];
$response = -1;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['index'])) {
    removeFromCart($_GET['index']);
    header('Location: cart.php');
} elseif (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['customer_name']) &&
    isset($_POST['customer_email'])
) {
    $name = trim($_POST['customer_name']);
    $email = trim($_POST['customer_email']);

    if (empty($name)) {
        $error['name'] = 'You must specify your name';
    }

    if (empty($email)) {
        $error['email'] = 'You must specify your email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'Invalid email format';
    }

    if (empty($error['name']) && empty($error['email'])) {
        $to = $config['admin_email'];
        $subject = 'Test Email';
        $name = sanitize($_POST['customer_name']);
        $body = getEmailBody($name, $email);
        $headers = 'From: ' . $email;

        if (count($_SESSION['cart']) === 0) {
            $response = 2;
        } elseif (mail($to, $subject, $body, $headers)) {
            $orderId = insertOrder($name, $email);

            if ($orderId > 0) {
                insertOrdersProducts($_SESSION['cart'], $orderId);
            }

            $response = 1;
            $_SESSION['cart'] = [];
        } else {
            $response = 0;
        }
    }
}

if (isset($_SESSION['cart']) && count($_SESSION['cart'])) {
    $result = fetch('products', 'id', array_keys($cartItems));
}
require('views/cart.view.php');
