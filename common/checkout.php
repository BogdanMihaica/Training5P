<?php
session_start();

require_once('../config/manager.php');
require_once('../common/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cartItems = $_SESSION['cart'];

    $message = 'Cart items:\n\n';

    foreach ($cartItems as $itemId) {
        $message .= 'Product Id: ' . sanitize($itemId) . '\n';
    }

    $to = $email;
    $subject = 'New order';

    if (mail($to, $subject, $message) === false) {
        die('Failed to checkout');
    }

    header('Location: ../public/index.php');
    exit();
}
?>