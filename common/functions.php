<?php
    function addToCart($index)
    {
        $_SESSION["cart"][] = $index;
    }

    function removeFromCart($index)
    {
        $cartItems = $_SESSION["cart"];

        for ($i = 0 ; $i<count($cartItems) ; $i += 1) {
            if ($cartItems[$i] == $index) {
                unset($cartItems[$i]);
                break;
            }
        }

        $_SESSION["cart"] = $cartItems;
    }
?>