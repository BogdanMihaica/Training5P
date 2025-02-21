<?php

/**
 * This function fills the body of a html with user data on checkout, including their username, email and cart items.
 * 
 * @param string $user
 * @param string $email
 * @return string
 */
function getEmailBody($user, $email)
{
    $productIds = array_keys($_SESSION['cart']);
    $products = Database::fetch('products', 'id', $productIds);

    $emailBody = "<html><body>";
    $emailBody .= "<p><strong>{$user}</strong> " . translate('with email') .  " <strong>{$email}</strong>" . translate(' has placed the following order') . ':</p>';
    $emailBody .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%; font-family: Arial, sans-serif;'>";
    $emailBody .= "<thead style='background-color: #f2f2f2;'>";
    $emailBody .= "<tr>";
    $emailBody .= "<th style='padding: 10px; text-align: left;'>" . translate('Product') . '</th>';
    $emailBody .= "<th style='padding: 10px; text-align: center;'>" . translate('Image') . '</th>';
    $emailBody .= "<th style='padding: 10px; text-align: left;'>" . translate('Description') . '</th>';
    $emailBody .= "<th style='padding: 10px; text-align: center;'>" . translate('Quantity') . '</th>';
    $emailBody .= "<th style='padding: 10px; text-align: right;'>" . translate('Price') . '</th>';
    $emailBody .= "<th style='padding: 10px; text-align: right;'>" . translate('Total') . '</th>';
    $emailBody .= "</tr>";
    $emailBody .= "</thead>";
    $emailBody .= "<tbody>";

    $grandTotal = 0;

    foreach ($products as $product) {
        $productId = $product['id'];
        $quantity = $_SESSION['cart'][$productId];
        $totalPrice = $product['price'] * $quantity;
        $imageUrl = 'http://localhost:8888/src/images/' . getImageForId($product['id']);
        $grandTotal += $totalPrice;

        $emailBody .= "<tr>";
        $emailBody .= "<td>{$product['title']}</td>";
        $emailBody .= "<td style='text-align: center;'><img src='{$imageUrl}' alt='Product Image' style='max-width: 100px; max-height: 100px;'></td>";
        $emailBody .= "<td>{$product['description']}</td>";
        $emailBody .= "<td>{$quantity}</td>";
        $emailBody .= "<td>{$product['price']}</td>";
        $emailBody .= "<td>{$totalPrice}</td>";
        $emailBody .= "</tr>";
    }

    $emailBody .= "</tbody>";
    $emailBody .= "<tfoot>";
    $emailBody .= "<tr>";
    $emailBody .= "<td colspan='5' style='text-align: right;'><strong>" . translate('Grand Total') . '</strong></td>';
    $emailBody .= "<td style='text-align: right;'>{$grandTotal}</td>";
    $emailBody .= "</tr>";
    $emailBody .= "</tfoot>";
    $emailBody .= "</table>";
    $emailBody .= '<p>' . translate('Thank you for your order!') . '</p>';
    $emailBody .= "</body></html>";

    return $emailBody;
}
