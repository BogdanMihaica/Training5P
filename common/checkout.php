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
        }
    } catch (Exception $e) {
        die("Debug error: {$mail->ErrorInfo}");
    }
} elseif (count($_SESSION('cart')) === 0) {
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
    <h1>
        <?php switch ($response) {
            case 0:
                echo 'Unknown error occured. Please try again later.';
                break;
            case 1:
                echo 'Your order has been placed succesfully!';
                break;

            case 2:
                echo 'You don\'t have any items in your cart!';
                break;
        }
        ?>
    </h1>
</body>

</html>