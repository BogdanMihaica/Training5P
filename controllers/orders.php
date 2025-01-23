<?php
session_start();

if (!isset($_SESSION['admin'])) {
    redirect('/login');
}

$orders = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $orders = Database::fetch('orders');
}

require basePath('views/orders.view.php');
