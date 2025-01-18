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
    $products = fetch("id", array_keys($cartItems), true);
} else {
    $products = fetch();
}

# Verify GET for updating
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['index']) && isset($_GET['quantity'])) {

    $index = $_GET['index'];

    $foundProduct = fetch('id', [$index]);

    if (count($foundProduct) !== 0) {
        addToCart($index, $_GET['quantity']);
    }

    header('Location: .');
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

<body>

    <?php include('../components/language.php') ?>

    <div class="page-container">
        <div class="big-circle"></div>
        <a href="cart.php" class="view-cart"><?= translate("View cart items") ?></a>

        <h1 class="page-title"><?= translate("List of available products") ?></h1>

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
                    <div>
                        <!-- The onclick attribute will add the value of the corresponding select input of the product -->
                        <a class="add-to-cart" href="index.php?index=<?= sanitize($row['id']) ?>&quantity=" onclick="this.href += document.querySelector('.select-<?= sanitize($row['id']) ?>').value;">
                            <?= translate("Add to cart") ?>
                        </a>
                        <?= translate("Select quantity") ?>
                        <select class="<?= 'select-' . sanitize($row['id']) ?>">
                            <?php for ($i = 1; $i <= 7; $i++) : ?>
                                <option><?= $i ?></option>
                            <?php endfor ?>
                        </select>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</body>

</html>