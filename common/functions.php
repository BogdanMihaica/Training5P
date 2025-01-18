<?php

require_once '../utils/translations.php';
require_once '../config/database.php';
/**
 * Function for querying from table 'products'. The parameter 'not' controlls whether or not the column specified should be
 * present in the 'values' array
 * 
 * @param mixed $conn
 * @param string $columnName
 * @param array $values
 * @param bool $not
 * @return array
 */
function fetch($columnName = null, $values = [], $not = false)
{
    $result = [];
    $conn = $GLOBALS['conn'];

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

function addToCart($id, $quantity)
{
    if (!in_array($id, $_SESSION['cart'])) {
        $_SESSION['cart'][$id] = $quantity;
    }
}

function removeFromCart($index)
{
    $cartItems = $_SESSION['cart'];

    for ($i = 0; $i < count($cartItems); $i += 1) {
        if (array_keys($cartItems)[$i] == $index) {
            unset($cartItems[$index]);
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
    $translations = $GLOBALS['translations'];

    if (isset($_SESSION['language']) && $_SESSION['language'] !== '') {
        $lang = $_SESSION['language'];
        return $translations[$string][$lang];
    } else {
        return $string;
    }
}
