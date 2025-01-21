<?php
require_once('../config/database.php');
require_once('../common/functions.php');

session_start();

$cartItems = [];
$result = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['index'])) {
    removeFromCart($_GET['index']);
    header('Location: cart.php');
} elseif (isset($_SESSION['cart']) && count($_SESSION['cart'])) {
    $cartItems = $_SESSION['cart'];
    $result = fetch('products', 'id', array_keys($cartItems));
}

?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

<body>

    <?php include('../components/language.php') ?>

    <?php include('../components/admin-button.php') ?>

    <div class="big-circle top-right"></div>
    <div class="big-circle bottom-left"></div>

    <a href="index.php" class="view-cart"><?= translate("View product list") ?></a>
    <h1 class="page-title"><?= translate("Your cart items") ?></h1>
    <div class="products-container">
        <?php foreach ($result as $row) : ?>
            <div class="product">
                <div class="product-details">
                    <div class="product-image-container">
                        <img class="product-image" src="<?= 'src/images/' . getImageForId(sanitize($row['id'])) ?>" alt="<?= sanitize($row['title']) ?>">
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
        <form action="./checkout.php" method="POST" class="checkout-form">
            <h2><?= translate("Please fill in your details to checkout.") ?></h2>

            <label for="customer-name"><?= translate('Name') . ':' ?></label>
            <input type="text" id="customer-name" name="customer_name" placeholder="<?= translate('Enter your name') ?>">
            <p class="error"><?= $error['name'] ?></p>

            <label for="customer-email"><?= translate('Email') . ':' ?></label>
            <input type="email" id="customer-email" name="customer_email" placeholder="<?= translate('Enter your email') ?>">
            <p class="error"><?= $error['email'] ?></p>

            <button type="submit" class="checkout-button"><?= translate("Checkout") ?></button>
        </form>
    </div>


</body>

</html>