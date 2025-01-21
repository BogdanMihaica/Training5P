<?php
require_once('../common/functions.php');

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$error_message = '';
$prod_per_page = 12;
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$products = [];

if ($_SERVER['REQUEST_METHOD'] === "GET" && !isset($_GET['id'])) {
    $products = fetch();
} else if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['id'])) {
    if (!deleteProduct($_GET['id'])) {
        $error_message = 'Unable to delete product with id ' . $_GET['id'];
    } else {
        $products = fetch();
        $total_products = count($products);

        $total_pages = ceil($total_products / $prod_per_page);

        if ($current_page >= $total_pages) {
            $current_page = max(0, $total_pages - 1);
        }

        header('Location: products.php?page=' . $current_page);
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['page'])) {
    $current_page = intval($_GET['page']);
    if ($current_page * $prod_per_page >= count($products)) {
        header('Location: products.php?page=' . ($current_page - 1));
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

<body>

    <?php include('../components/admin-navbar.php') ?>

    <?php include('../components/background.php') ?>
    <span class="dashboard-title">
        <h1 class="title">Products Dashboard</h1>
        <a href="product.php">
            <div class="add-product">+</div>
        </a>
    </span>
    <p style="color:red"><?= $error_message ?></p>

    <div class="admin-products-container">
        <a href="<?= 'products.php?page=' . ($current_page > 0 ? ($current_page - 1) : 0) ?>">
            <div class="arrow">&#8678;</div>
        </a>
        <table class="admin-products-table">
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Image</th>
                <th>Description</th>
                <th>Price</th>
                <th>Delete</th>
            </tr>

            <?php for ($i = $current_page * $prod_per_page; $i < ($current_page + 1) * $prod_per_page && $i < count($products); $i++):
                $product = $products[$i];
            ?>
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
                    <td>
                        <a href="<?= 'products.php?page=' . $current_page . '&id=' . sanitize($product['id']) ?>">
                            <img class="delete-button" src="../misc/png/delete.png" alt="delete button">
                        </a>
                    </td>
                </tr>
            <?php endfor ?>

        </table>
        <a href="<?= 'products.php?page=' . ($current_page + 1) ?>">
            <div class="arrow">&#8680;</div>
        </a>
    </div>
</body>

</html>