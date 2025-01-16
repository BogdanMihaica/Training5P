<?php
    require_once("../config/database.php");

    session_start();
    
    $cartItems = array();
    if (isset($_SESSION["cart"])){
        $cartItems = $_SESSION["cart"];
    }

    $result = array();

    if (count($cartItems) > 0) {
        foreach ($cartItems as $key) {
            $stmt = "SELECT * from products where id = " . $key;
            $product = $conn->query($stmt);
            if( $row = $product->fetch_assoc() ) {
                $result[] = $row;
            }
        }
    }

    #Verify GET for removing
    if ($_SERVER["REQUEST_METHOD"] === "GET"){
        if (isset($_GET["index"])){
            $index = $_GET["index"];
            removeFromCart($index);
            header('Location: .');
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
    <link rel="stylesheet" href="../styles/universal.css">
    <title>Products</title>
</head>
<body>
    <a href="index.php" class="view-cart">View product list</a>
    <h1>Your cart items</h1>
    <div class="products-container">
        <?php foreach( $result as $row ) : ?>
            <div class="product">
                <div class="product-details">
                    <div class="product-image">
                        <!-- Image will go here -->
                    </div>
                    <p class="product-title">
                        <?= $row["title"] ?>
                    </p>
                    <p class="product-description">
                        <?= $row["description"] ?>
                    </p>
                    <p class="product-price">
                        <?= $row["price"] . "$" ?>
                    </p>
                </div>
                
                <a class="remove-from-cart" href="<?= "cart.php?index=".$row["id"] ?>">
                    Remove from cart
                </a>
            </div>
        <?php endforeach ?>
    </div>
</body>
</html>