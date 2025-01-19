<?php
require_once('../common/functions.php');

/**
 * This function fills the body of a html with user data on checkout, including their username, email and cart items.
 * @param string $user
 * @param string $email
 * @return string
 */
function fill_email($user, $email)
{
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return '<p>Your cart is empty.</p>';
    }

    $productIds = array_keys($_SESSION['cart']);
    $products = fetch('id', $productIds);

    $emailBody = "<html><body>";
    $emailBody .= "<p><strong>{$user}</strong> with email <strong>{$email}</strong> has placed the following order:</p>";
    $emailBody .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
    $emailBody .= "<thead><tr><th>Product</th><th>Description</th><th>Quantity</th><th>Price</th><th>Total</th></tr></thead>";
    $emailBody .= "<tbody>";

    $grandTotal = 0;

    foreach ($products as $product) {
        $productId = $product['id'];
        $quantity = $_SESSION['cart'][$productId];
        $totalPrice = $product['price'] * $quantity;
        $grandTotal += $totalPrice;

        $emailBody .= "<tr>";
        $emailBody .= "<td>{$product['title']}</td>";
        $emailBody .= "<td>{$product['description']}</td>";
        $emailBody .= "<td>{$quantity}</td>";
        $emailBody .= "<td>{$product['price']}</td>";
        $emailBody .= "<td>{$totalPrice}</td>";
        $emailBody .= "</tr>";
    }

    $emailBody .= "</tbody>";
    $emailBody .= "<tfoot><tr><td colspan='4' style='text-align: right;'><strong>Grand Total:</strong></td><td>{$grandTotal}</td></tr></tfoot>";
    $emailBody .= "</table>";
    $emailBody .= "<p>Thank you for your order!</p>";
    $emailBody .= "</body></html>";

    return $emailBody;
}
