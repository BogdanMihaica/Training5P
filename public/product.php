<?php
session_start();

require_once('../common/functions.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
$edit_mode = false;
$product = null;
$id = 0;
$title = '';
$description = '';
$price = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['edit'])) {

    $product = fetch('id', [intval($_GET['edit'])])[0];

    if ($product) {

        $edit_mode = true;
        $id = $product['id'];
        $title = $product['title'];
        $description = $product['description'];
        $price = $product['price'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

<body>

    <?php include('../components/admin-navbar.php') ?>

    <?php include('../components/background.php') ?>

    <div class="center-container">
        <div class="form-container">
            <form method="post">

                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?= $edit_mode ? sanitize($title) : '' ?>">

                <label for="description">Description</label>
                <textarea id="description" name="description"><?= $edit_mode ? sanitize($description) : '' ?></textarea>

                <label for="price">Price</label>
                <input type="number" id="price" name="price" value="<?= $edit_mode ? $price : '' ?>">

                <button type="submit">Proceed</button>
            </form>
        </div>
    </div>

</body>

</html>