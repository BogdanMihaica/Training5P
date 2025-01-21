<?php

require_once '../utils/translations.php';
require_once '../config/database.php';

/**
 * Handles the uploading process of an image. It also checks if the file provided is an actual image and returns a response as following:
 * 0 : Unknown error
 * 1 : File upload success
 * 2 : File is not an image
 * 3 : Not supported type
 * 
 * @param object $image
 * @param integer $id
 * 
 * @return integer
 */
function handleImageUpload($image, $id)
{
    //TODO
}

function update($id, $title, $description, $price)
{
    $result = false;
    if ($title && $description && $price) {
        $stmt = $GLOBALS['conn']->prepare('UPDATE products SET title=?, description=?, price=? WHERE id = ?');
        $result = $stmt->execute([$title, $description, $price, $id]);
    }
    return $result;
}

function insert($title, $description, $price)
{
    $result = false;
    if ($title && $description && $price) {
        $stmt = $GLOBALS['conn']->prepare('INSERT INTO products (title, description, price) VALUES (?, ?, ?)');
        $result = $stmt->execute([$title, $description, $price]);
    }
    if ($result) {
        return $GLOBALS['conn']->lastInsertId();
    }
    return -1;
}

/**
 * Function for querying from table 'products'. The parameter 'not' controls whether or not the column specified should be
 * present in the 'values' array
 * 
 * @param string|null $columnName
 * @param array $values
 * @param bool $not
 * 
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
 * 
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
