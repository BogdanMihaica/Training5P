<!DOCTYPE html>
<html lang="en">

<?php include('../components/header.php') ?>

<body>

    <?php include('../components/language.php') ?>

    <?php include('../components/background.php') ?>

    <div class="page-container">

        <a href="cart.php" class="view-cart"><?= translate("View cart items") ?></a>

        <?php include('../components/admin-button.php') ?>

        <h1 class="page-title"><?= translate("List of available products") ?></h1>

        <div class="products-container">
            <?php foreach ($products as $row) : ?>
                <div class="product">
                    <div class="product-details">
                        <div class="product-image-container">
                            <img class="product-image" src="<?= 'src/images/' . getImageForId(sanitize($row['id'])) ?>" alt="<?= sanitize($row['title']) ?>">
                        </div>
                        <p class="product-title">
                            <?= sanitize($row['title']) ?>
                        </p>
                    </div>
                    <div>
                        <p class="product-description">
                            <?= sanitize($row['description']) ?>
                        </p>
                        <p class="product-price">
                            <?= sanitize($row['price']) . '$' ?>
                        </p>
                        <!-- The onclick attribute will add the value of the corresponding select input of the product -->
                        <a class="add-to-cart" href="index.php?index=<?= sanitize($row['id']) ?>&quantity="
                            onclick="this.href += document.querySelector('.select-<?= sanitize($row['id']) ?>').value;">
                            <span class="left-part">
                                <img src="../misc/svg/shopping-cart.svg" alt="Icon" class="svg-icon">
                            </span>
                            <span class="right-part">
                                <?= translate("Add to cart") ?>
                            </span>
                        </a>
                        <?= translate("Select quantity") ?>
                        <select class="<?= 'select-' . sanitize($row['id']) ?>">
                            <?php for ($i = 1; $i <= 7; $i++) : ?>
                                <option><?= $i ?></option>
                            <?php endfor ?>
                        </select>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</body>

</html>