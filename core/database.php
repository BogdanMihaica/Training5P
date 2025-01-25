<?php
class Database
{
    private static $conn;

    public function __construct($db_config)
    {
        try {
            Database::$conn = new PDO(
                $db_config['connection'] . ';dbname=' . $db_config['dbname'],
                $db_config['username'],
                $db_config['password'],
                $db_config['options']
            );
        } catch (PDOException $e) {
            die('Database Connection Failed: ' . $e->getMessage());
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
    public static function fetch($table = 'products', $columnName = null, $values = [], $not = false)
    {
        $result = [];

        if (is_null($columnName) && count($values) === 0) {
            $stmt = Database::$conn->query('SELECT * FROM ' . $table);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $placeholders = implode(',', array_fill(0, count($values), '?'));
            $isIn = $not ? 'NOT' : '';

            $stmt = Database::$conn->prepare("SELECT * FROM {$table} WHERE {$columnName} {$isIn} IN ({$placeholders})");

            try {
                $stmt->execute($values);
            } catch (PDOException $e) {
                die("" . $e->getMessage());
            }

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    /**
     * Inserts a product into the 'orders' table and returns its id
     * 
     * @param string $customerName
     * @param string $customerEmail
     * 
     * @return int
     */
    public static function insertOrder($customerName, $customerEmail)
    {
        $result = false;

        if ($customerName && $customerEmail) {
            $stmt = Database::$conn->prepare('INSERT INTO orders (customer_name, customer_email) VALUES (?, ?)');
            $result = $stmt->execute([$customerName, $customerEmail]);
        }

        if ($result) {
            return Database::$conn->lastInsertId();
        }

        return -1;
    }

    /**
     * Inserts a product into the 'products' table and returns its id
     * 
     * @param string $title
     * @param string $description
     * @param double $price
     * 
     * @return int
     */
    public static function insertProduct($title, $description, $price)
    {
        $result = false;

        $stmt = Database::$conn->prepare('INSERT INTO products (title, description, price) VALUES (?, ?, ?)');
        $result = $stmt->execute([$title, $description, $price]);

        if ($result) {
            return Database::$conn->lastInsertId();
        }

        return -1;
    }

    /**
     * Updates a product into the 'products' table and returns true or false
     * 
     * @param int $id
     * @param string $title
     * @param string $description
     * @param double $price
     * 
     * @return bool
     */
    public static function updateProducts($id, $title, $description, $price)
    {
        $result = false;

        $stmt = Database::$conn->prepare('UPDATE products SET title=?, description=?, price=? WHERE id = ?');
        $result = $stmt->execute([$title, $description, $price, $id]);

        return $result;
    }

    /**
     * Inserts multiple entries in the table 'orders_products'
     * 
     * @param array $cartItems
     * @param integer $orderId
     */
    public static function insertOrdersProducts($cartItems, $orderId)
    {
        foreach (array_keys($cartItems) as $productId) {
            $quantity = $cartItems[$productId];
            $stmt = Database::$conn->prepare('INSERT INTO orders_products (order_id, product_id, quantity) VALUES (?, ?, ?)');
            $stmt->execute([$orderId, $productId, $quantity]);
        }
    }

    /**
     * This function receives a parameter 'value' which is an id from the products table and deletes the entry with that specific id
     * and its corresponding image
     *   
     * @param integer $value
     * 
     * @return bool
     */
    public static function deleteProduct($value)
    {
        $stmt = Database::$conn->prepare('DELETE FROM products WHERE id = ?');
        $result = $stmt->execute([$value]);

        if (!$result) {
            die('Failed to delete item');
        } else {
            $filePath = "../public/src/images/" . getImageForId($value);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        return $result;
    }

    /**
     * Fetches all entries from table products in a many-to-many maneer with table orders
     * 
     * @param integer $id Order id
     * 
     * @return array
     */
    public static function fetchOrderProducts($id)
    {
        $stmt = Database::$conn->prepare('SELECT p.id as id, p.title as title, p.description as description, p.price as price, op.quantity as quantity 
                                        FROM products p 
                                        INNER JOIN orders_products op on p.id = op.product_id 
                                        INNER JOIN orders o on op.order_id = ?
                                        GROUP BY p.id');
        $stmt->execute([$id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}

new Database($config['database']);
