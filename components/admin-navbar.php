<?php
require_once('../common/functions.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout']) && intval($_GET['logout']) === 1) {
    unset($_SESSION['admin']);
    header('Location: .');
}
?>
<div class="admin-navbar">
    <a href="index.php" class="nav-link">
        <div class="nav-item">
            <img src="../misc/png/home.png" alt="" class="nav-icon">
            <p class="nav-link-text"><?= translate('Home') ?></p>
        </div>
    </a><a href="products.php" class="nav-link">
        <div class="nav-item">
            <img src="../misc/png/products.png" alt="" class="nav-icon">
            <p class="nav-link-text"><?= translate('Products') ?></p>
        </div>
    </a><a href="orders.php" class="nav-link">
        <div class="nav-item">
            <img src="../misc/png/orders.png" alt="" class="nav-icon">
            <p class="nav-link-text"><?= translate('Orders') ?></p>
        </div>
    </a>
    </a><a href="<?= $_SERVER['PHP_SELF'] . '?logout=1' ?>" class="nav-link">
        <div class="nav-item">
            <img src="../misc/png/logout.png" alt="" class="nav-icon">
            <p class="nav-link-text"><?= translate('Log out') ?></p>
        </div>
    </a>
</div>