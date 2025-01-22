<?php
require_once('../config/database.php');
require_once('../common/functions.php');

session_start();

$cartItems = [];

$products = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['index']) && isset($_GET['quantity'])) {

    $index = $_GET['index'];

    $foundProduct = fetch('products', 'id', [$index]);

    if (count($foundProduct) !== 0) {
        addToCart($index, $_GET['quantity']);
    }

    header('Location: .');
} else {
    if (isset($_SESSION['cart'])) {
        #$_SESSION['cart'] = [];
        $cartItems = $_SESSION['cart'];
    } else {
        $_SESSION['cart'] = [];
    }

    if (!empty($cartItems)) {
        $products = fetch('products', 'id', array_keys($cartItems), true);
    } else {
        $products = fetch();
    }
}

require('views/index.view.php');
