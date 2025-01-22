<?php
session_start();

require_once('../common/functions.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
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
$errors = ['title' => '', 'description' => '', 'price' => ''];

// If we make a GET request to the server, try to fetch the product with that id. If it doesn't exist, set the product to null
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['edit'])) {

    $query = fetch('products', 'id', [$_GET['edit']]);
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

    if (empty(trim($title))) {
        $errors['title'] = 'Title cannot be empty.';
    }

    if (empty(trim($description))) {
        $errors['description'] = 'Description cannot be null.';
    }

    if (empty($price) || !is_numeric($price) || floatval($price) <= 0) {
        $errors['price'] = 'Price must be a not null positive number';
    }

    $validationError = !(empty($errors['title']) && empty($errors['description']) && empty($errors['price']));

    if (isset($_POST['add'])) {
        $response = -1;

        if (!$validationError) {
            $response = insertProduct($title, $description, $price);
        }

        if ($response < 0) {
            $logMessage = 'Could not insert data';
        } else {
            $logMessage = 'Successfully added data!';
            $newId = $response;
        }
    } elseif (isset($_POST['edit'])) {
        $editMode = true;
        $id = $_POST['edit'];

        $query = fetch('products', 'id', [$id]);

        if (count($query) !== 0) {
            $product = $query[0];
        }

        if (!$product) {
            $logMessage = 'Product not found for editing.';
        } else {
            $response = -1;
            if (!$validationError) {
                $response = update($id, $title, $description, $price);
            }

            if (!$response) {
                $logMessage = 'Could not update data';

                // Restore product details in case of failure
                $id = $product['id'];
                $title = $product['title'];
                $description = $product['description'];
                $price = $product['price'];
            } else {
                $logMessage = 'Successfully updated data!';
                $newId = $id;
            }
        }
    }


    // Upload image
    if ($_FILES['image'] && $_FILES['image']['size'] !== 0 && $newId > 0) {

        $fileUploadResponse = handleImageUpload($_FILES['image'], $newId);

        if ($fileUploadResponse == 1) {
            $fileLogMessage = 'Image uploaded succesfully!';
        } elseif ($fileUploadResponse == 2) {
            $fileLogMessage = 'Not supported type. Required file type : jpg / jpeg / png / gif';
        } elseif ($fileUploadResponse == 3) {
            $fileLogMessage = 'Not an image';
        } else {
            $fileLogMessage = 'Unknown error while uploading image';
        }
    }
}

require('views/product.view.php');
