<?php

require_once basePath('core/common/email_template.php');

require basePath('config/config.php');

session_start();

$cartItems = $_SESSION['cart'];
$result = [];
$errors = [];
$mailConfig = $config['mail'];
$response = -1;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['index'])) {
    removeFromCart($_GET['index']);
    redirect('/cart');
} elseif (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['customer_name']) &&
    isset($_POST['customer_email'])
) {
    $name = trim($_POST['customer_name']);
    $email = trim($_POST['customer_email']);

    if (isEmpty($name)) {
        $errors['name'] = translate('You must specify your name');
    }

    if (isEmpty($email)) {
        $errors['email'] = translate('You must specify your email');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = translate('Invalid email format');
    }

    if (isEmpty($errors)) {

        $to = $mailConfig['admin_email'];
        $subject = 'Test Email';
        $name = sanitize($_POST['customer_name']);
        $body = !isEmpty($_SESSION['cart']) ? getEmailBody($name, $email) : '';
        $headers = 'From: ' . $email;

        if (isEmpty($_SESSION['cart'])) {
            $response = 2;
        } elseif (mail($to, $subject, $body, $headers)) {

            $orderId = Database::insertOrder($name, $email);

            if ($orderId > 0) {
                Database::insertOrdersProducts($_SESSION['cart'], $orderId);
            }

            $response = 1;

            $_SESSION['cart'] = [];
        } else {
            $response = 0;
        }
    }
}

if (isset($_SESSION['cart']) && !isEmpty($_SESSION['cart'])) {
    $result = Database::fetch('products', 'id', array_keys($cartItems));
}

require basePath('views/cart.view.php');
