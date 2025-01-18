<?php
require_once('credentials.php');

$conn = mysqli_connect($host, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    die('Error connecting to the database.');
}

$GLOBALS['conn'] = $conn;
