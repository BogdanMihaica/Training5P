<?php
require_once('credentials.php');

// $conn = mysqli_connect($host, $username, $password, $dbname);

// if (mysqli_connect_errno()) {
//     die('Error connecting to the database.');
// }


try {
    $conn = new PDO('mysql:host=127.0.0.1;dbname=' . $dbname, $username, $password);
} catch (PDOException $e) {
    die('' . $e->getMessage());
}
$GLOBALS['conn'] = $conn;
