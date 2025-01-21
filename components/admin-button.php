<?php if (isset($_SESSION['admin'])): ?>
    <a href="products.php" class="dashboard"><?= 'Admin' ?></a>
<?php else : ?>
    <a href="login.php" class="dashboard"><?= 'Login' ?></a>
<?php endif ?>