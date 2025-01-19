<?php
require_once('../config/database.php');
require_once('../common/functions.php');

session_start();

$cartItems = [];

if (isset($_SESSION['cart'])) {
    $cartItems = $_SESSION['cart'];
}

$result = [];

if (count($cartItems) > 0) {
    $result = fetch("id", array_keys($cartItems));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['index'])) {

        removeFromCart($_GET['index']);

        header('Location: cart.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

<body>

    <?php include('../components/language.php') ?>

    <div class="big-circle top-right"></div>
    <div class="big-circle bottom-left"></div>

    <a href="index.php" class="view-cart"><?= translate("View product list") ?></a>
    <h1 class="page-title"><?= translate("Your cart items") ?></h1>
    <div class="products-container">
        <?php foreach ($result as $row) : ?>
            <div class="product">
                <div class="product-details">
                    <div class="product-image-container">
                        <img class="product-image" src="<?= 'src/images/' . sanitize($row['id']) . '.jpg' ?>" alt="<?= sanitize($row['title']) ?>">
                    </div>
                    <p class="product-title">
                        <?= sanitize($row['title']) ?>
                    </p>
                    <p class="product-description">
                        <?= translate('Quantity:') . ' ' . sanitize($cartItems[sanitize($row['id'])]) ?>
                    </p>
                </div>
                <div>
                    <p class="product-description">
                        <?= sanitize($row['description']) ?>
                    </p>
                    <p class="product-price">
                        <?= sanitize($row['price']) . '$' ?>
                    </p>
                    <a class="remove-from-cart" href="cart.php?index=<?= sanitize($row['id']) ?>">
                        <?= translate("Remove item") ?>
                    </a>
                </div>

            </div>
        <?php endforeach ?>
    </div>
    <div class="checkout-container">
        <a class="checkout-button" href="./checkout.php"><?= translate("Checkout") ?></a>
    </div>

</body>

</html>