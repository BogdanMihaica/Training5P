<?php
require_once('../config/database.php');
require_once('../common/functions.php');

session_start();

$cartItems = [];

$products = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['index']) && !isset($_GET['quantity'])) {
    if (isset($_SESSION['cart'])) {
        #$_SESSION['cart'] = [];
        $cartItems = $_SESSION['cart'];
    } else {
        $_SESSION['cart'] = [];
    }
    if (!empty($cartItems)) {
        $products = fetch("id", array_keys($cartItems), true);
    } else {
        $products = fetch();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['index']) && isset($_GET['quantity'])) {

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
        <div class="big-circle top-right"></div>
        <div class="big-circle bottom-left"></div>
        <a href="cart.php" class="view-cart"><?= translate("View cart items") ?></a>

        <h1 class="page-title"><?= translate("List of available products") ?></h1>

        <div class="products-container">
            <?php foreach ($products as $row) : ?>
                <div class="product">
                    <div class="product-details">
                        <div class="product-image-container">
                            <img class="product-image" src="<?= 'src/images/' . sanitize($row['id']) . '.jpg' ?>" alt="<?= sanitize($row['title']) ?>">
                        </div>
                        <p class="product-title">
                            <?= sanitize($row['title']) ?>
                        </p>
                    </div>
                    <div>
                        <p class="product-description">
                            <?= sanitize($row['description']) ?>
                        </p>
                        <p class="product-price">
                            <?= sanitize($row['price']) . '$' ?>
                        </p>
                        <!-- The onclick attribute will add the value of the corresponding select input of the product -->
                        <a class="add-to-cart" href="index.php?index=<?= sanitize($row['id']) ?>&quantity="
                            onclick="this.href += document.querySelector('.select-<?= sanitize($row['id']) ?>').value;">
                            <span class="left-part">
                                <img src="../misc/svg/shopping-cart.svg" alt="Icon" class="svg-icon">
                            </span>
                            <span class="right-part">
                                <?= translate("Add to cart") ?>
                            </span>
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