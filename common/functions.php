<?php

require_once '../utils/translations.php';
require_once '../config/database.php';

/**
 * Fetches all entries from table products in a many-to-many maneer with table orders
 * 
 * @param integer $id
 * 
 * @return array
 */
function fetchOrderJoin($id)
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
 * 2 : File is not an image
 * 3 : Not supported type
 * 
 * @param array $image
 * @param integer $id
 * 
 * @return integer
 */
function handleImageUpload($image, $id)
{
    $target_dir = "../public/src/images/";
    $file_extension = pathinfo($image['name'], PATHINFO_EXTENSION);
    $new_filename = $target_dir . $id . $file_extension;
    $accepted_file_types = ['png', 'jpg', 'jpeg', 'gif'];

    # Verify if the file is an image of valid type

    if (!in_array($file_extension, $accepted_file_types)) {
        return 3;
    }

    # Verify if there is a file with the same name (not extension)

    $files_in_target_dir = scandir($target_dir);
    $exists = false;

    foreach ($files_in_target_dir as $file) {

        $file_name = pathinfo($file, PATHINFO_FILENAME);

        if ($file_name == $id) {
            $exists = true;
            break;
        }
    }
    return 0;
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

function insertProduct($title, $description, $price)
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

function insertOrder($customer_name, $customer_email)
{
    $result = false;

    if ($customer_name && $customer_email) {
        $stmt = $GLOBALS['conn']->prepare('INSERT INTO orders (customer_name, customer_email) VALUES (?, ?)');
        $result = $stmt->execute([$customer_name, $customer_email]);
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
