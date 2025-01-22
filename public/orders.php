<?php
session_start();

require_once('../common/functions.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}

$orders = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $orders = fetch('orders');
}

require('views/orders.view.php');
