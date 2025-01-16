<?php
    session_start();
    function addToCart($itemId, $quantity) {

        $cartItems = array();

        if (isset($_SESSION["cart"])) {
            $cartItems = $_SESSION["cart"];
        }

        $cartItems[$itemId] = $quantity;
        $_SESSION["cart"] = $cartItems;   
    }
?>