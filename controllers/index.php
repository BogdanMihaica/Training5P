<?php
require_once basePath('core/database.php');

session_start();

$cartItems = [];

$products = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['index']) && isset($_GET['quantity'])) {

    $index = $_GET['index'];

    $foundProduct = Database::fetch('products', 'id', [$index]);

    if (count($foundProduct) !== 0) {
        addToCart($index, $_GET['quantity']);
    }

    redirect('/');
} else {
    if (isset($_SESSION['cart'])) {
        #$_SESSION['cart'] = [];
        $cartItems = $_SESSION['cart'];
    } else {
        $_SESSION['cart'] = [];
    }

    if (!empty($cartItems)) {
        $products = Database::fetch('products', 'id', array_keys($cartItems), true);
    } else {
        $products = Database::fetch();
    }
}

require basePath('views/index.view.php');
