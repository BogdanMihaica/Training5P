<?php
session_start();

require_once('../common/functions.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$edit_mode = false;
$product = null;
$id = -1;
$title = '';
$description = '';
$price = '';
$log_message = '';
$new_id = -1;
$file_log_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['edit'])) {

    $product = fetch('products', 'id', [intval($_GET['edit'])])[0];

    if ($product) {
        $edit_mode = true;
        $id = $product['id'];
        $title = $product['title'];
        $description = $product['description'];
        $price = $product['price'];
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['price'])) {
    if (isset($_POST['add'])) {

        $response = insertProduct($_POST['title'], $_POST['description'], intval($_POST['price']));

        if ($response < 0) {
            $log_message = 'Could not insert data';
        } else {
            $log_message = 'Succesfully added data!';

            $new_id = $response;
        }
    } else if (isset($_POST['edit'])) {

        $edit_mode = true;

        $url = $_SERVER['REQUEST_URI'];
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);
        $product_id = intval($params['edit']);

        $response = update($product_id, $_POST['title'], $_POST['description'], intval($_POST['price']));

        if (!$response) {
            $log_message = 'Could not update data';

            $product = fetch('products', 'id', [$product_id])[0];
            if ($product) {
                $id = $product['id'];
                $title = $product['title'];
                $description = $product['description'];
                $price = $product['price'];
            }
        } else {
            $log_message = 'Succesfully updated data!';
            $id = $product_id;
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
        }
        $new_id = $id;
    }
    if ($_FILES['image']) {
        $file_upload_response = handleImageUpload($_FILES['image'], $new_id);

        if ($file_upload_response == 1) {
            $file_log_message = 'Image uploaded succesfully!';
        } elseif ($file_upload_response == 2) {
            $file_log_message = 'Not supported type. Required file type : jpg / jpeg / png / gif';
        } elseif ($file_upload_response == 3) {
            $file_log_message = 'Not an image';
        } else {
            $file_log_message = 'Unknown error while uploading image';
        }
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

            <h1><?= $edit_mode ? 'Edit product' : 'Add new product' ?></h1>

            <br><br>

            <?php if ($edit_mode) : ?>
                <img class="current-image" src="<?= './src/images/' . getImageForId($id) ?>" alt="product image">
            <?php endif ?>

            <br><br>

            <?= $log_message . ' ' . $file_log_message ?>

            <br><br>

            <form method="post" enctype="multipart/form-data">

                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?= $edit_mode ? sanitize($title) : '' ?>" required>

                <label for="description">Description</label>
                <textarea id="description" name="description" required><?= $edit_mode ? sanitize($description) : '' ?></textarea>

                <label for="price">Price</label>
                <input type="number" id="price" name="price" value="<?= $edit_mode ? $price : '' ?>" required>

                <label for="image">Image</label>
                <input type="file" id="image" name="image"><br><br>

                <input type="hidden" id="mode" name="<?= $edit_mode ? 'edit' : 'add' ?>" value="<?= $edit_mode ? sanitize($product['id']) : -1 ?>">

                <button type="submit"><?= $edit_mode ? 'Update' : 'Add' ?></button>
            </form>
        </div>
    </div>

</body>

</html>