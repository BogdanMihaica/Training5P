<?php

$config = [
    'database' => [
        'username' => 'Your username',
        'password' => 'Your Password',
        'dbname' => 'Name of your database',
        'connection' => 'Connection name',
        'options' => []
    ],
    'admin' => [
        'username' => 'Admin username',
        'password' => password_hash('Admin password', PASSWORD_DEFAULT)
    ],
    'mail' => [
        'admin_email' => 'Your email here',
    ]
];
