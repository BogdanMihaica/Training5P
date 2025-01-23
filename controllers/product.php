<?php
session_start();

if (!isset($_SESSION['admin'])) {
    redirect('/login');
}

$editMode = false;
$product = null;
$id = -1;
$title = '';
$description = '';
$price = 0;
$logMessage = '';
$newId = -1;
$fileLogMessage = '';
$errors = [];

// If we make a GET request to the server, try to fetch the product with that id. If it doesn't exist, set the product to null
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['edit'])) {

    $query = Database::fetch('products', 'id', [$_GET['edit']]);
    $editMode = true;

    if (count($query) !== 0) {
        $product = $query[0];
    }

    if ($product) {
        $id = $product['id'];
        $title = $product['title'];
        $description = $product['description'];
        $price = $product['price'];
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';

    if (isEmpty(trim($title))) {
        $errors['title'] = translate('Title cannot be empty.');
    }

    if (isEmpty(trim($description))) {
        $errors['description'] = translate('Description cannot be empty.');
    }

    if (isEmpty($price) || !is_numeric($price) || $price <= 0) {
        $errors['price'] = translate('Price must be a not null positive number');
    }

    if (isset($_POST['add'])) {
        $response = -1;

        if (isEmpty($errors)) {
            $response = Database::insertProduct($title, $description, $price);
        } else {
            $logMessage = translate('Could not insert data');
        }
        if ($response < 0) {
            $logMessage = translate('Could not insert data');
        } else {
            $logMessage = translate('Successfully added data!');
            $newId = $response;
        }
    } elseif (isset($_POST['edit'])) {
        $editMode = true;
        $id = $_POST['edit'];

        $query = Database::fetch('products', 'id', [$id]);

        if (count($query) !== 0) {
            $product = $query[0];
        }

        if (!$product) {
            $logMessage = translate('Product not found for editing.');
        } else {
            $response = -1;

            if (isEmpty($errors)) {
                $response = Database::updateProducts($id, $title, $description, $price);
            } else {
                $logMessage = translate('Could not update data');
            }

            if ($response < 0) {
                $logMessage = translate('Could not update data');

                // Restore product details in case of failure
                $id = $product['id'];
                $title = $product['title'];
                $description = $product['description'];
                $price = $product['price'];
            } else {
                $logMessage = translate('Successfully updated data!');
                $newId = $id;
            }
        }
    }


    // Upload image
    if ($_FILES['image'] && $_FILES['image']['size'] !== 0 && $newId > 0) {

        $fileUploadResponse = handleImageUpload($_FILES['image'], $newId);

        if ($fileUploadResponse == 1) {
            $fileLogMessage = translate('Image uploaded succesfully!');
        } elseif ($fileUploadResponse == 2) {
            $fileLogMessage = translate('Not supported type. Required file type : jpg / jpeg / png / gif');
        } elseif ($fileUploadResponse == 3) {
            $fileLogMessage = translate('Not an image');
        } else {
            $fileLogMessage = translate('Unknown error while uploading image');
        }
    }
}

require basePath('views/product.view.php');
