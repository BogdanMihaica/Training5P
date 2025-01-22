<?php
require_once('../common/functions.php');

session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
}

$order = null;
$products = [];
$grand_total = 0;
$error_message = '';

if (isset($_GET['id']) && intval($_GET['id']) > 0) {
    $result = fetch('orders', 'id', [intval($_GET['id'])]);

    if (count($result) > 0) {
        $order = $result[0];
        $products = fetchOrderProducts($_GET['id']);

        foreach ($products as $product) {
            $grand_total += intval($product['quantity']) * $product['price'];
        }
    } else {
        $error_message = translate('There is no such order with id #') . $_GET['id'];
    }
} else {
    $error_message = translate('This page doesn\'t exist');
}

require('views/order.view.php');
