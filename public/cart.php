<?php
require_once('../config/database.php');
require_once('../common/functions.php');
require_once('../config/manager.php');

session_start();

$cartItems = [];

if (isset($_SESSION['cart'])) {
    $cartItems = $_SESSION['cart'];
}

$result = [];

if (count($cartItems) > 0) {
    $result = fetch($conn, "id", $cartItems);
}

#Verify GET for removing
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['index'])) {
        $index = $_GET['index'];
        removeFromCart($index);
        header('Location: cart.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/product_page.css">
    <link rel="stylesheet" href="../styles/cart.css">
    <?php include('../utils/styles.php') ?>
    <title>Products</title>
</head>

<body>
    <?php include('../components/language.php') ?>
    <a href="index.php" class="view-cart"><?= translate("View product list") ?></a>
    <h1><?= translate("Your cart items") ?></h1>
    <div class="products-container">
        <?php foreach ($result as $row) : ?>
            <div class="product">
                <div class="product-details">
                    <div class="product-image">
                        <!-- Image will go here -->
                    </div>
                    <p class="product-title">
                        <?= sanitize($row['title']) ?>
                    </p>
                    <p class="product-description">
                        <?= sanitize($row['description']) ?>
                    </p>
                    <p class="product-price">
                        <?= sanitize($row['price']) . '$' ?>
                    </p>
                </div>

                <a class="remove-from-cart" href="cart.php?index=<?= sanitize($row['id']) ?>">
                    <?= translate("Remove item") ?>
                </a>
            </div>
        <?php endforeach ?>
    </div>
    <form action="./checkout.php" method="post" class="checkout-container">
        <button type="submit"><?= translate("Checkout") ?></button>
    </form>
</body>

</html>