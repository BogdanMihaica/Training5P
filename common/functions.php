<?php
function addToCart($id)
{
    if (!in_array($id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $id;
    }
}

function removeFromCart($index)
{
    $cartItems = $_SESSION['cart'];

    for ($i = 0; $i < count($cartItems); $i += 1) {
        if ($cartItems[$i] == $index) {
            unset($cartItems[$i]);
            break;
        }
    }

    $_SESSION['cart'] = array_values($cartItems);
}

function sanitize($string)
{
    return htmlspecialchars($string);
}
