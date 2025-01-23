<?php

session_start();

if (!isset($_SESSION['admin'])) {
    redirect('/login');
}

$errorMessage = '';
$prodPerPage = 12;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 0;
$products = [];

if ($_SERVER['REQUEST_METHOD'] === "GET" && !isset($_GET['id'])) {
    $products = Database::fetch();
} else if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['id'])) {

    if (!Database::deleteProduct($_GET['id'])) {
        $errorMessage = translate('Unable to delete product with id ') . $_GET['id'];
    } else {
        $products = Database::fetch();
        $totalProducts = count($products);

        $totalPages = ceil($totalProducts / $prodPerPage);

        if ($currentPage >= $totalPages) {
            $currentPage = max(0, $totalPages - 1);
        }

        redirect('/products?page=' . $currentPage);
    }
}
if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['page'])) {
    $currentPage = intval($_GET['page']);
    if ($currentPage * $prodPerPage >= count($products)) {
        redirect('/products.php?page=' . ($currentPage - 1));
    }
}

require basePath('views/products.view.php');
