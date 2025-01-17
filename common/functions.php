<?php


function fetch($conn, $columnName = null, $values = [], $not = false)
{
    $result = [];

    if (is_null($columnName) && count($values) === 0) {
        $stmt = $conn->prepare('SELECT * from products');
        $stmt->execute();
        $resultSet = $stmt->get_result();
        while ($row = $resultSet->fetch_assoc()) {
            $result[] = $row;
        }
    } else {
        $type = gettype($values[0]) === 'integer' ? 'i' : 's';
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $typeString = str_repeat($type, count($values));
        $isIn = $not ? 'NOT' : '';
        $stmt = $conn->prepare("SELECT * from products where ($columnName) $isIn in ($placeholders)");
        $stmt->bind_param($typeString, ...$values);
        $stmt->execute();
        $resultSet = $stmt->get_result();

        while ($row = $resultSet->fetch_assoc()) {
            $result[] = $row;
        }

        $stmt->close();
    }

    return $result;
}
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

function translate($string)
{
    require('../utils/translations.php');

    if (isset($_SESSION['language']) && $_SESSION['language'] !== '') {
        $lang = $_SESSION['language'];
        return $translations[$string][$lang];
    } else {
        return $string;
    }
}
