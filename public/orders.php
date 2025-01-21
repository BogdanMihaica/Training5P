<?php
session_start();

require_once('../common/functions.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
$orders = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $orders = fetch('orders');
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

<body>

    <?php include('../components/admin-navbar.php') ?>

    <?php include('../components/background.php') ?>
    <div class="orders-container">
        <h1 class="title">Orders Dashboard</h1>
        <table class="admin-products-table orders-table">
            <tr>
                <th>Id</th>
                <th>Date created</th>
                <th>Customer Name</th>
                <th>Customer email</th>
                <th>Products</th>
            </tr>

            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= sanitize($order['id']) ?></td>
                    <td><?= sanitize($order['creation_date']) ?></td>
                    <td><?= sanitize($order['customer_name']) ?></td>
                    <td><?= sanitize($order['customer_email']) ?></td>
                    <td><a href="<?= 'order.php?id=' . $order['id'] ?>">See full order</a></td>
                </tr>
            <?php endforeach ?>

        </table>
    </div>
</body>

</html>