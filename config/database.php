<?php

    $username = 'root';
    $password = '';
    $dbname = "training";
    $host = "localhost";
    
    $conn = mysqli_connect($host,$username, $password, $dbname);

    if (mysqli_connect_errno()) {
        die("Error connecting to the database.");
    }
    
?>