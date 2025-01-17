<?php
require_once('../config/database.php');
require_once('../common/functions.php');

session_start();

$cartItems = [];

if (isset($_SESSION['cart'])) {
    #$_SESSION['cart'] = [];
    $cartItems = $_SESSION['cart'];
} else {
    $_SESSION['cart'] = [];
}
$products = [];

if (!empty($cartItems)) {
    $products = fetch($conn, "id", $cartItems, true);
} else {
    $products = fetch($conn);
}

#Verify GET for updating
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['index'])) {

        $canAdd = false;
        $index = $_GET['index'];
        foreach ($products as $product) {
            if ($product['id'] == $index) {
                $canAdd = true;
                break;
            }
        }
        if ($canAdd) {
            addToCart($index);
        }
        header('Location: .');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('../utils/styles.php') ?>
    <title>Products</title>
</head>

<body>
    <a href="cart.php" class="view-cart">View cart items</a>
    <h1>List of available products</h1>
    <div class="products-container">
        <?php foreach ($products as $row) : ?>
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

                <a class="add-to-cart" href="index.php?index=<?= sanitize($row['id']) ?>">
                    Add to cart
                </a>
            </div>
        <?php endforeach ?>
    </div>
</body>

</html>