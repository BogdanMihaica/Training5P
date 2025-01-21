<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$order = null;
$products = [];

if (isset($_GET['id'])) {
    $order = fetch('orders', 'id', [intval($_GET['id'])]);
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

<body>

    <?php include('../components/admin-navbar.php') ?>

    <?php include('../components/background.php') ?>

</body>

</html>