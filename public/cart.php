<?php
require_once('../config/database.php');
require_once('../common/functions.php');
require_once('../utils/email_template.php');

session_start();

$cartItems = [];
$result = [];
$error = ['name' => '', 'email' => ''];
$config = $data['mail'];
$response = -1;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['index'])) {
    removeFromCart($_GET['index']);
    header('Location: cart.php');
} elseif (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['customer_name']) &&
    isset($_POST['customer_email'])
) {
    $name = trim($_POST['customer_name']);
    $email = trim($_POST['customer_email']);

    if (empty($name)) {
        $error['name'] = 'You must specify your name';
    }

    if (empty($email)) {
        $error['email'] = 'You must specify your email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'Invalid email format';
    }

    if (empty($error['name']) && empty($error['email'])) {
        $to = $config['admin_email'];
        $subject = 'Test Email';
        $body = getEmailBody('Client', $email);
        $headers = 'From: ' . $email;

        if (count($_SESSION['cart']) === 0) {
            $response = 2;
        } elseif (mail($to, $subject, $body, $headers)) {
            $orderId = insertOrder($name, $email);

            if ($orderId > 0) {
                insertOrdersProducts($_SESSION['cart'], $orderId);
            }

            $response = 1;
            $_SESSION['cart'] = [];
        } else {
            $response = 0;
        }
    }
} elseif (isset($_SESSION['cart']) && count($_SESSION['cart'])) {
    $cartItems = $_SESSION['cart'];
    $result = fetch('products', 'id', array_keys($cartItems));
}

?>
<!DOCTYPE html>
<html lang="en">

<?php include('../components/header.php') ?>

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
        <form method="POST" class="checkout-form">
            <h2><?= translate("Please fill in your details to checkout.") ?></h2>

            <label for="customer-name"><?= translate('Name') . ':' ?></label>
            <input type="text" id="customer-name" name="customer_name" placeholder="<?= translate('Enter your name') ?>">
            <p class="error"><?= translate($error['name']) ?></p>

            <label for="customer-email"><?= translate('Email') . ':' ?></label>
            <input type="text" id="customer-email" name="customer_email" placeholder="<?= translate('Enter your email') ?>">
            <p class="error"><?= translate($error['email']) ?></p>

            <button type="submit" class="checkout-button"><?= translate("Checkout") ?></button>
        </form>
    </div>

    <?php if (empty($error['name']) && empty($error['email']) && $response >= 0) : ?>
        <div class="checkout-page-container">
            <div class="response">
                <?php if ($response == 1) : ?>

                    <div class="background-circle green"></div>
                    <h1 class="checkout-message"><?= translate("Your order has been placed succesfully!") ?></h1>
                    <img src="../misc/svg/order-placed.svg" alt="order placed" class="response-image">

                <?php elseif ($response == 2) : ?>

                    <div class="background-circle red"></div>
                    <h1 class="checkout-message"><?= translate("You don't have any items in your cart!") ?></h1>
                    <img src="../misc/png/error.png" alt="error occured" class="response-image">

                <?php else : ?>

                    <div class="background-circle red"></div>
                    <h1 class="checkout-message"><?= translate("Unknown error occurred. Please try again later.") ?></h1>
                    <img src="../misc/png/error.png" alt="error occured" class="response-image">

                <?php endif ?>
            </div>
        </div>
    <?php endif ?>
</body>

</html>