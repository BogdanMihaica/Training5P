<?php
session_start();


require_once('../components/language.php');
require_once('../utils/email_template.php');

$config = $data['mail'];
$response = 0;

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['customer_name']) &&
    isset($_POST['customer_email'])
) {

    $to = $config['admin_email'];
    $subject = 'Test Email';
    $body = getEmailBody('Client', $_POST['customer_email']);
    $headers = 'From: ' . $_POST['customer_email'];

    if (count($_SESSION['cart']) === 0) {
        $response = 2;
    } elseif (mail($to, $subject, $body, $headers)) {

        $orderId = insertOrder($_POST['customer_name'], $_POST['customer_email']);

        if ($orderId > 0) {
            insertOrdersProducts($_SESSION['cart'], $orderId);
        }

        $response = 1;
        $_SESSION['cart'] = [];
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

<body>
    <?php include('../components/background.php') ?>

    <?php include('../components/language.php') ?>

    <div class="checkout-page-container">
        <div class="response">
            <?php if ($response == 1) : ?>

                <div class="background-circle green"></div>
                <h1 class="checkout-message"><?= translate("Your order has been placed succesfully!") ?></h1>
                <img src="../misc/svg/order-placed.svg" alt="order placed" class="response-image">

            <?php elseif ($response == 2) : ?>

                <div class="background-circle red"></div>
                <h1 class="checkout-message"><?= translate("You don't have any items in your cart!") ?></h1>
                <img src="../misc/png/error.png" alt="error occured" class="response-image">

            <?php else : ?>

                <div class="background-circle red"></div>
                <h1 class="checkout-message"><?= translate("Unknown error occurred. Please try again later.") ?></h1>
                <img src="../misc/png/error.png" alt="error occured" class="response-image">

            <?php endif ?>
        </div>
    </div>
</body>

</html>