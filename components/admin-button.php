<?php require_once('../common/functions.php'); ?>
<?php if (isset($_SESSION['admin'])): ?>
    <a href="products.php" class="dashboard"><?= translate('Admin') ?></a>
<?php else : ?>
    <a href="login.php" class="dashboard"><?= translate('Login') ?></a>
<?php endif ?>