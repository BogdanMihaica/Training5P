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
    if (!delete_product($_GET['id'])) {
        $error_message = 'Unable to delete product with id ' . $_GET['id'];
    } else {
        header('Location: products.php?page=' . $current_page);
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
    <div class="big-circle top-right"></div>
    <div class="big-circle bottom-left"></div>

    <h1 class="title">Products Dashboard</h1>

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
                    <td><?= sanitize($product['title']) ?></td>
                    <td class="produt-image-entry">
                        <img src="<?= './src/images/' . sanitize($product['id']) . '.jpg' ?>" alt="product image">
                    </td>
                    <td><?= sanitize($product['description']) ?></td>
                    <td><?= sanitize($product['price']) ?></td>
                    <td>
                        <a href="<?= 'products.php?id=' . sanitize($product['id']) ?>">
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