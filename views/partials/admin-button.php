<?php if (isset($_SESSION['admin'])): ?>
    <a href="/products" class="dashboard"><?= translate('Admin') ?></a>
<?php else : ?>
    <a href="/login" class="dashboard"><?= translate('Login') ?></a>
<?php endif ?>