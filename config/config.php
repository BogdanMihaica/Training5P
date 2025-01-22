<?php

$config = [
    'database' => [
        'username' => 'root',
        'password' => '',
        'dbname' => 'training',
        'connection' => 'mysql:host=localhost',
        'port' => '3306',
        'options' => []
    ],
    'admin' => [
        'username' => 'bogdan',
        'password' => password_hash('parola', PASSWORD_DEFAULT)
    ],
    'mail' => [
        'admin_email' => 'claudiumihaica12@gmail.com',
    ]
];
