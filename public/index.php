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
    $placeholders = implode(',', array_fill(0, count($cartItems), '?'));
    $typeString = str_repeat('i', count($cartItems));

    $stmt = $conn->prepare("SELECT * FROM products WHERE id NOT IN ($placeholders)");
    $stmt->bind_param($typeString, ...$cartItems);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    $stmt->close();
} else {
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    $stmt->close();
}

#Verify GET for updating
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['index'])) {
        $index = $_GET['index'];
        addToCart($index);
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