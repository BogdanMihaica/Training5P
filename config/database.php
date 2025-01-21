<?php
require_once('credentials.php');

$data = require_once('config.php');
$db_config = $data['database'];

try {
    $conn = new PDO($db_config['connection'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);
} catch (PDOException $e) {
    die('' . $e->getMessage());
}
$GLOBALS['conn'] = $conn;
