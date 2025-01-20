<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

<body>

    <?php include('../utils/header.php') ?>

</body>

</html>