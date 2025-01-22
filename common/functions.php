<?php

require_once '../utils/translations.php';
require_once '../config/database.php';

function getImageForId($id)
{
    $dir = '../public/src/images';
    $files = scandir($dir);

    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_FILENAME) == $id) {
            return trim($file);
        }
    }

    return '';
}

/**
 * Fetches all entries from table products in a many-to-many maneer with table orders
 * 
 * @param integer $id Order id
 * 
 * @return array
 */
function fetchOrderProducts($id)
{
    $stmt = $GLOBALS['conn']->prepare('SELECT p.id as id, p.title as title, p.description as description, p.price as price, op.quantity as quantity 
                                        FROM products p 
                                        INNER JOIN orders_products op on p.id = op.product_id 
                                        INNER JOIN orders o on op.order_id = ?
                                        GROUP BY p.id');
    $stmt->execute([$id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Handles the uploading process of an image. It also checks if the file provided is an actual image and returns a response as following:
 * 0 : Unknown error
 * 1 : File upload success
 * 2 : Not supported type
 * 
 * @param array $image
 * @param integer $id
 * 
 * @return integer
 */
function handleImageUpload($image, $id)
{
    $targetDir = "../public/src/images/";
    $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
    $newFileName = $targetDir . $id . '.' . $fileExtension;
    $acceptedFileTypes = ['png', 'jpg', 'jpeg', 'gif'];

    # Verify if the file is an image

    $check = getimagesize($image["tmp_name"]);

    if (!$check) {
        return 3;
    }

    # Verify if the file is an image of valid type

    if (!in_array($fileExtension, $acceptedFileTypes)) {
        return 2;
    }

    # Verify if there is a file with the same name (not extension)

    $filesInTargetDir = scandir($targetDir);

    foreach ($filesInTargetDir as $file) {

        $fileName = pathinfo($file, PATHINFO_FILENAME);

        if ($fileName == $id) {
            unlink($targetDir . $file);
            break;
        }
    }

    if (move_uploaded_file($image["tmp_name"], $newFileName)) {
        return 1;
    }

    return 0;
}

function update($id, $title, $description, $price)
{
    $result = false;

    $stmt = $GLOBALS['conn']->prepare('UPDATE products SET title=?, description=?, price=? WHERE id = ?');
    $result = $stmt->execute([$title, $description, $price, $id]);

    return $result;
}

function insertProduct($title, $description, $price)
{
    $result = false;

    $stmt = $GLOBALS['conn']->prepare('INSERT INTO products (title, description, price) VALUES (?, ?, ?)');
    $result = $stmt->execute([$title, $description, $price]);

    if ($result) {
        return $GLOBALS['conn']->lastInsertId();
    }

    return -1;
}

function insertOrder($customerName, $customerEmail)
{
    $result = false;

    if ($customerName && $customerEmail) {
        $stmt = $GLOBALS['conn']->prepare('INSERT INTO orders (customer_name, customer_email) VALUES (?, ?)');
        $result = $stmt->execute([$customerName, $customerEmail]);
    }

    if ($result) {
        return $GLOBALS['conn']->lastInsertId();
    }

    return -1;
}

function insertOrdersProducts($cart_items, $order_id)
{
    foreach (array_keys($cart_items) as $product_id) {
        $quantity = $cart_items[$product_id];
        $stmt = $GLOBALS['conn']->prepare('INSERT INTO orders_products (order_id, product_id, quantity) VALUES (?, ?, ?)');
        $stmt->execute([$order_id, $product_id, $quantity]);
    }
}
/**
 * Function for querying from a table. The parameter 'not' controls whether or not the column specified should be
 * present in the 'values' array
 * 
 * @param string $table
 * @param string|null $columnName
 * @param array $values
 * @param bool $not
 * 
 * @return array
 */
function fetch($table = 'products', $columnName = null, $values = [], $not = false)
{
    $result = [];

    if (is_null($columnName) && count($values) === 0) {
        $stmt = $GLOBALS['conn']->query('SELECT * FROM ' . $table);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $isIn = $not ? 'NOT' : '';

        $stmt = $GLOBALS['conn']->prepare("SELECT * FROM {$table} WHERE {$columnName} {$isIn} IN ({$placeholders})");
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
        $filePath = 'src/images/' . $value . '.jpg';
        if (file_exists($filePath)) {
            unlink($filePath);
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
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML401, 'UTF-8');
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

function dd($object)
{
    die(var_dump($object));
}
