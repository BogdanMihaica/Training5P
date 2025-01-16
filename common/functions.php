<?php
    function addToCart($index, $quantity)
    {
        $_SESSION["cart"][$index]=$quantity;
    }
?>