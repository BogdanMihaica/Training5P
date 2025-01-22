<?php

require_once basePath('config/config.php');
$db_config = $config['database'];

try {
    $conn = new PDO(
        $db_config['connection'] . ';dbname=' . $db_config['dbname'],
        $db_config['username'],
        $db_config['password'],
        $db_config['options']
    );

    $GLOBALS['conn'] = $conn;
} catch (PDOException $e) {
    die('Database Connection Failed: ' . $e->getMessage());
}
