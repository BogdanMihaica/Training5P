<?php
    require_once("../config/database.php");
    require_once("../common/functions.php");

    session_start();
    
    $cartItems=array();
    
    if (isset($_SESSION["cart"])) {
        #$_SESSION["cart"]=array();
        $cartItems = $_SESSION["cart"];
    } else {
        $_SESSION["cart"] = array();
    }
    
    # Fetching all products and filtering
    $stmt = "SELECT * from products";
    $result = $conn->query($stmt);
    $products = [];

    if ($result->num_rows > 0) {
        while ( $row = $result->fetch_assoc() ) {
            if ( !in_array($row["id"],$cartItems) ) {
                $products[] = $row;
            }
        }
    }

    #Verify GET for updating
    if ($_SERVER["REQUEST_METHOD"] === "GET"){
        if (isset($_GET["index"])) {
            $index = $_GET["index"];
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
    <link rel="stylesheet" href="../styles/product_page.css">
    <link rel="stylesheet" href="../styles/universal.css">
    <title>Products</title>
</head>
<body>
    <a href="cart.php" class="view-cart">View cart items</a>
    <h1>List of available products</h1>
    <div class="products-container">
        <?php foreach( $products as $row ) : ?>
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
                
                <a class="add-to-cart" href="index.php?index=<?= $row["id"] ?>">
                    Add to cart
                </a>
            </div>
        <?php endforeach ?>
    </div>
</body>
</html>