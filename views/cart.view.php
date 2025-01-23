<!DOCTYPE html>
<html lang="en">

<?php include basePath('views/partials/header.php') ?>

<body>

    <?php include basePath('views/partials/language.php') ?>

    <?php include basePath('views/partials/admin-button.php') ?>

    <div class="big-circle top-right"></div>
    <div class="big-circle bottom-left"></div>

    <a href="/" class="view-cart"><?= translate("View product list") ?></a>
    <h1 class="page-title"><?= translate("Your cart items") ?></h1>
    <div class="products-container">
        <?php foreach ($result as $row) : ?>
            <div class="product">
                <div class="product-details">
                    <div class="product-image-container">
                        <img class="product-image" src="<?= 'src/images/' . getImageForId(sanitize($row['id'])) ?>" alt="<?= sanitize($row['title']) ?>">
                    </div>
                    <p class="product-title">
                        <?= sanitize($row['title']) ?>
                    </p>
                    <p class="product-description">
                        <?= translate('Quantity:') . ' ' . sanitize($cartItems[sanitize($row['id'])]) ?>
                    </p>
                </div>
                <div>
                    <p class="product-description">
                        <?= sanitize($row['description']) ?>
                    </p>
                    <p class="product-price">
                        <?= sanitize($row['price']) . '$' ?>
                    </p>
                    <a class="remove-from-cart" href="cart?index=<?= sanitize($row['id']) ?>">
                        <?= translate("Remove item") ?>
                    </a>
                </div>

            </div>
        <?php endforeach ?>
    </div>

    <div class="checkout-container">
        <form method="POST" class="checkout-form">
            <h2><?= translate("Please fill in your details to checkout.") ?></h2>

            <label for="customer-name"><?= translate('Name') . ':' ?></label>
            <input type="text" id="customer-name" name="customer_name" placeholder="<?= translate('Enter your name') ?>">
            <p class="error"><?= getIfExists($errors, 'name') ?></p>

            <label for="customer-email"><?= translate('Email') . ':' ?></label>
            <input type="text" id="customer-email" name="customer_email" placeholder="<?= translate('Enter your email') ?>">
            <p class="error"><?= getIfExists($errors, 'email') ?></p>

            <button type="submit" class="checkout-button"><?= translate("Checkout") ?></button>
        </form>
    </div>

    <?php if (isEmpty($errors) && $response >= 0) : ?>
        <div class="checkout-page-container">
            <div class="response">
                <?php if ($response == 1) : ?>

                    <div class="background-circle green"></div>
                    <h1 class="checkout-message"><?= translate("Your order has been placed succesfully!") ?></h1>
                    <img src="src/icons/order-placed.svg" alt="order placed" class="response-image">

                <?php elseif ($response == 2) : ?>

                    <div class="background-circle red"></div>
                    <h1 class="checkout-message"><?= translate("You don't have any items in your cart!") ?></h1>
                    <img src="src/icons/error.png" alt="error occured" class="response-image">

                <?php else : ?>

                    <div class="background-circle red"></div>
                    <h1 class="checkout-message"><?= translate("Unknown error occurred. Please try again later.") ?></h1>
                    <img src="src/icons/error.png" alt="error occured" class="response-image">

                <?php endif ?>
            </div>
        </div>
    <?php endif ?>
</body>

</html>