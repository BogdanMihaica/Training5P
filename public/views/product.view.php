<!DOCTYPE html>
<html lang="en">

<?php include('../components/header.php') ?>

<body>

    <?php include('../components/admin-navbar.php') ?>

    <?php include('../components/background.php') ?>

    <?php include('../components/language.php') ?>

    <div class="center-container">
        <div class="form-container">
            <?php if ($editMode && !$product) : ?>
                <h1><?= translate("No such product") ?></h1>
            <?php else : ?>
                <h1><?= $editMode ? translate('Edit product') : translate('Add new product') ?></h1>

                <?php if ($editMode) : ?>
                    <img class="current-image" src="<?= './src/images/' . getImageForId($id) ?>" alt="product image">
                <?php endif ?>

                <br><br>

                <?= translate($logMessage) . ' ' . translate($fileLogMessage) ?>

                <br><br>

                <form method="post" enctype="multipart/form-data">
                    <div class="input-group">
                        <label for="title"><?= translate('Title') ?></label>
                        <input type="text" id="title" name="title" value="<?= $editMode ? sanitize($title) : '' ?>">
                        <p class="error"><?= translate($errors['title']) ?></p>
                    </div>

                    <div class="input-group">
                        <label for="description"><?= translate('Description') ?></label>
                        <textarea id="description" name="description"><?= $editMode ? sanitize($description) : '' ?></textarea>
                        <p class="error"><?= translate($errors['description']) ?></p>
                    </div>

                    <div class="input-group">
                        <label for="price"><?= translate('Price') ?></label>
                        <input type="number" id="price" name="price" value="<?= $editMode ? $price : '' ?>">
                        <p class="error"><?= translate($errors['price']) ?></p>
                    </div>


                    <label for="image"><?= translate('Image') ?></label>
                    <input type="file" id="image" name="image"><br><br>

                    <input type="hidden" id="mode" name="<?= $editMode ? 'edit' : 'add' ?>" value="<?= $editMode ? $product['id'] : -1 ?>">

                    <button type="submit"><?= $editMode ? translate('Update') : translate('Add') ?></button>
                </form>
            <?php endif ?>
        </div>
    </div>

</body>

</html>