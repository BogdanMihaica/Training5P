<?php
session_start();

require_once('../vendor/autoload.php');
require_once('../vendor/phpmailer/src/Exception.php');
require_once('../vendor/phpmailer/src/PHPMailer.php');
require_once('../vendor/phpmailer/src/SMTP.php');
require_once('../config/manager.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$mail = new PHPMailer(true);
$response = 0;
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {

    $body = implode(' ', $_SESSION['cart']);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $email;
        $mail->Password = 'ebekiwpbhwaylhhj';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom("client@noreply.com", 'Client');
        $mail->addAddress($email);
        $mail->Subject = 'New Order';
        $mail->Body = $body;

        if ($mail->send()) {
            $response = 1;
            $_SESSION['cart'] = [];
        }
    } catch (Exception $e) {
        die("Debug error: {$mail->ErrorInfo}");
    }
} elseif (count($_SESSION['cart']) === 0) {
    $response = 2;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>

<body>
    <?php include('../components/language.php') ?>
    <?php if ($response == 1) : ?>
        <h1><?= translate("Your order has been placed succesfully!") ?></h1>
    <?php elseif ($response == 2) : ?>
        <h1><?= translate("You don't have any items in your cart!") ?></h1>
    <?php else : ?>
        <h1><?= translate("Unknown error occurred. Please try again later.") ?></h1>
    <?php endif ?>
</body>

</html>