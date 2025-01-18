<?php
session_start();

require_once('../config/credentials.php');
require_once('../components/language.php');
$response = 0;
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $to = $host_email;
    $subject = 'Test Email';
    $message = 'Comanda dumneavoastra a fost plasata cu succes.';
    $headers = 'From: ' . $client_email;

    if (count($_SESSION['cart']) === 0) {
        $response = 2;
    } elseif (mail($to, $subject, $message, $headers)) {
        $response = 1;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<?php include('../utils/header.php') ?>

<body>
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