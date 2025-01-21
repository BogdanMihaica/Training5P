<?php
require_once('../common/functions.php');

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$errorMessage = '';
$prodPerPage = 12;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 0;
$products = [];

if ($_SERVER['REQUEST_METHOD'] === "GET" && !isset($_GET['id'])) {
    $products = fetch();
} else if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['id'])) {
    if (!deleteProduct($_GET['id'])) {
        $errorMessage = translate('Unable to delete product with id ') . $_GET['id'];
    } else {
        $products = fetch();
        $totalProducts = count($products);

        $totalPages = ceil($totalProducts / $prodPerPage);

        if ($currentPage >= $totalPages) {
            $currentPage = max(0, $totalPages - 1);
        }

        header('Location: products.php?page=' . $currentPage);
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['page'])) {
    $currentPage = intval($_GET['page']);
    if ($currentPage * $prodPerPage >= count($products)) {
        header('Location: products.php?page=' . ($currentPage - 1));
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

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