<?php

require_once '../utils/translations.php';
require_once '../config/database.php';

/**
 * Function for querying from table 'products'. The parameter 'not' controls whether or not the column specified should be
 * present in the 'values' array
 * 
 * @param string|null $columnName
 * @param array $values
 * @param bool $not
 * @return array
 */
function fetch($columnName = null, $values = [], $not = false)
{
    $result = [];

    if (is_null($columnName) && count($values) === 0) {
        $stmt = $GLOBALS['conn']->query('SELECT * FROM products');
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $isIn = $not ? 'NOT' : '';

        $stmt = $GLOBALS['conn']->prepare("SELECT * FROM products WHERE {$columnName} {$isIn} IN ({$placeholders})");
        $stmt->execute($values);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $result;
}

/**
 * This function receives a parameter 'value' which is an id from the products table and deletes the entry with that specific id
 * and its corresponding image
 *   
 * @param integer $value
 * @return bool
 */
function deleteProduct($value)
{
    $stmt = $GLOBALS['conn']->prepare('DELETE FROM products WHERE id = ?');
    $result = $stmt->execute([$value]);

    if (!$result) {
        die('Failed to delete item');
    } else {
        $file_path = 'src/images/' . $value . '.jpg';
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

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
