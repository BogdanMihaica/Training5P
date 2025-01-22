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

require('views/products.view.php');
