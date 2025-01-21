<?php
require_once('../common/functions.php');

session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$order = null;
$products = [];
$grand_total = 0;
$error_message = '';
if (isset($_GET['id']) && intval($_GET['id']) > 0) {
    $result = fetch('orders', 'id', [intval($_GET['id'])]);

    if (count($result) > 0) {
        $order = $result[0];
        $products = fetchOrderJoin($_GET['id']);

        foreach ($products as $product) {
            $grand_total += intval($product['quantity']) * intval($product['price']);
        }
    } else {
        $error_message = 'There is no such order with id #' . $_GET['id'];
    }
} else {
    $error_message = 'This page doesn\'t exist';
}


?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

<body>

    <?php include('../components/admin-navbar.php') ?>

    <?php include('../components/background.php') ?>

    <div class="order-container">
        <?php if ($order) : ?>
            <h1 class="order-title">
                <?= 'Order #' . sanitize($order['id']) ?>
            </h1>
            <div class="separator"></div>
            <span>
                <p class="customer-name"><?= 'Name: ' . sanitize($order['customer_name']) ?></p>
            </span>
            <span>
                <p class="customer-email"><?= 'Email: ' . sanitize($order['customer_email']) ?></p>
            </span>
            <span>
                <p class="date"><?= 'Date created: ' . sanitize($order['creation_date']) ?></p>
            </span>
            <p>Products:</p>
            <div class="ordered-products-container">
                <table class="admin-products-table">
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>

                    <?php foreach ($products as $product): ?>

                        <tr>
                            <td><?= sanitize($product['id']) ?></td>
                            <td>
                                <a href="<?= 'product.php?edit=' . $product['id'] ?>"><?= sanitize($product['title']) ?></a>
                            </td>
                            <td class="produt-image-entry">
                                <img src="<?= './src/images/' . getImageForId(sanitize($product['id'])) ?>" alt="product image">
                            </td>
                            <td><?= sanitize($product['description']) ?></td>
                            <td><?= sanitize($product['price']) ?></td>
                            <td><?= sanitize($product['quantity']) ?></td>
                            <td><?= intval($product['quantity']) * intval($product['price']) ?></td>
                        </tr>
                    <?php endforeach ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><strong>Grand total:</strong></td>
                        <td><?= $grand_total ?></td>
                    </tr>
            </div>
        <?php else : ?>
            <h1 class="title"><?= sanitize($error_message) ?></h1>
        <?php endif ?>
    </div>

</body>

</html>