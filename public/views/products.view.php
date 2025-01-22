<!DOCTYPE html>
<html lang="en">

<?php include('../components/header.php') ?>

<body>

    <?php include('../components/admin-navbar.php') ?>

    <?php include('../components/background.php') ?>

    <?php include('../components/language.php') ?>

    <span class="dashboard-title">
        <h1 class="title"> <?= translate('Products Dashboard') ?></h1>
        <a href="product.php">
            <div class="add-product">+</div>
        </a>
    </span>

    <p style="color:red"><?= $errorMessage ?></p>

    <div class="admin-products-container">
        <a href="<?= 'products.php?page=' . ($currentPage > 0 ? ($currentPage - 1) : 0) ?>">
            <div class="arrow">&#8678;</div>
        </a>
        <table class="admin-products-table">
            <tr>
                <th><?= translate('Id') ?></th>
                <th><?= translate('Title') ?></th>
                <th><?= translate('Image') ?></th>
                <th><?= translate('Description') ?></th>
                <th><?= translate('Price') ?></th>
                <th><?= translate('Delete') ?></th>
            </tr>

            <?php for ($i = $currentPage * $prodPerPage; $i < ($currentPage + 1) * $prodPerPage && $i < count($products); $i++):
                $product = $products[$i];
            ?>
                <tr>
                    <td><?= sanitize($product['id']) ?></td>
                    <td>
                        <a href="<?= 'product.php?edit=' . $product['id'] ?>"><?= sanitize($product['title']) ?></a>
                    </td>
                    <td class="produt-image-entry">
                        <img src="<?= './src/images/' . getImageForId(sanitize($product['id'])) ?>" alt="<?= translate('Product Image') ?>">
                    </td>
                    <td><?= sanitize($product['description']) ?></td>
                    <td><?= sanitize($product['price']) ?></td>
                    <td>
                        <a href="<?= 'products.php?page=' . $currentPage . '&id=' . sanitize($product['id']) ?>">
                            <img class="delete-button" src="../misc/png/delete.png" alt="<?= translate('Delete Button') ?>">
                        </a>
                    </td>
                </tr>
            <?php endfor ?>

        </table>
        <a href="<?= 'products.php?page=' . ($currentPage + 1) ?>">
            <div class="arrow">&#8680;</div>
        </a>
    </div>
</body>

</html>