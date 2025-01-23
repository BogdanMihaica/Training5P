<?php
session_start();

if (!isset($_SESSION['admin'])) {
    redirect('/login');
}

$order = null;
$products = [];
$grandTotal = 0;
$errorMessage = '';

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $result = Database::fetch('orders', 'id', [$_GET['id']]);

    if (count($result) > 0) {
        $order = $result[0];
        $products = Database::fetchOrderProducts($_GET['id']);

        foreach ($products as $product) {
            $grandTotal += $product['quantity'] * $product['price'];
        }
    } else {
        $errorMessage = translate('There is no such order with id #') . $_GET['id'];
    }
} else {
    $errorMessage = translate('This page doesn\'t exist');
}

require basePath('views/order.view.php');
