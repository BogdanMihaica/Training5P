<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout']) && intval($_GET['logout']) === 1) {
    unset($_SESSION['admin']);
    header('Location: /');
}
?>
<div class="admin-navbar">
    <a href="/" class="nav-link">
        <div class="nav-item">
            <img src="src/icons/home.png" alt="" class="nav-icon">
            <p class="nav-link-text"><?= translate('Home') ?></p>
        </div>
    </a><a href="/products" class="nav-link">
        <div class="nav-item">
            <img src="src/icons/products.png" alt="" class="nav-icon">
            <p class="nav-link-text"><?= translate('Products') ?></p>
        </div>
    </a><a href="/orders" class="nav-link">
        <div class="nav-item">
            <img src="src/icons/orders.png" alt="" class="nav-icon">
            <p class="nav-link-text"><?= translate('Orders') ?></p>
        </div>
    </a>

    <?php
    $currentQueryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    $baseUrl = strtok($_SERVER['REQUEST_URI'], '?');
    $queryParams = $currentQueryString ? '&' . $currentQueryString : '';
    ?>

    <a href="<?= $baseUrl . '?logout=1' . $queryParams ?>" class="nav-link">
        <div class="nav-item">
            <img src="src/icons/logout.png" alt="" class="nav-icon">
            <p class="nav-link-text"><?= translate('Log out') ?></p>
        </div>
    </a>
</div>