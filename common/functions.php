<?php

require_once '../utils/translations.php';
require_once '../config/database.php';
/**
 * Function for querying from table 'products'. The parameter 'not' controlls whether or not the column specified should be
 * present in the 'values' array
 * 
 * @param mysqli $conn
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

        $type = is_int($values[0]) ? 'i' : 's';
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $typeString = str_repeat($type, count($values));
        $isIn = $not ? 'NOT' : '';

        $stmt = $conn->prepare("SELECT * from products where ($columnName) $isIn in ($placeholders)");
        $stmt->bind_param($typeString, ...$values);
        $stmt->execute();

        $result_set = $stmt->get_result();

        while ($row = $result_set->fetch_assoc()) {
            $result[] = $row;
        }

        $stmt->close();
    }

    return $result;
}

/**
 * This function receives a parameter 'value' which is an id from the products table an deletes the entry with that speific id
 * and it's corresponding image
 * @param integer $value
 * @return bool
 */
function delete_product($value)
{
    $conn = $GLOBALS['conn'];

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param('i', $value);

    $result = $stmt->execute();

    if (!$result) {
        die('Failed to delete item');
    } else {
        $file_path = 'src/images/' . $value . '.jpg';
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    $stmt->close();
    return $result;
}

function addToCart($id, $quantity)
{
    if (!in_array($id, array_keys($_SESSION['cart']))) {
        $_SESSION['cart'][$id] = $quantity;
    }
}

function removeFromCart($index)
{
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
    }
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
