<!DOCTYPE html>
<html lang="en">

<?php include basePath('views/partials/header.php') ?>

<body>

    <?php include basePath('views/partials/admin-navbar.php') ?>

    <?php include basePath('views/partials/background.php') ?>

    <?php include basePath('views/partials/language.php') ?>

    <div class="orders-container">
        <h1 class="title"><?= translate('Orders Dashboard') ?></h1>
        <table class="admin-products-table orders-table">
            <tr>
                <th><?= translate('Id') ?></th>
                <th><?= translate('Date created') ?></th>
                <th><?= translate('Customer name') ?></th>
                <th><?= translate('Customer email') ?></th>
                <th><?= translate('Products') ?></th>
            </tr>

            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= sanitize($order['id']) ?></td>
                    <td><?= sanitize($order['creation_date']) ?></td>
                    <td><?= sanitize($order['customer_name']) ?></td>
                    <td><?= sanitize($order['customer_email']) ?></td>
                    <td><a href="<?= '/order?id=' . $order['id'] ?>"><?= translate('See full order') ?></a></td>
                </tr>
            <?php endforeach ?>

        </table>
    </div>
</body>

</html>