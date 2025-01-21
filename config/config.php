<?php

return [
    'database' => [
        'username' => 'root',
        'password' => '',
        'dbname' => 'training',
        'connection' => 'mysql:host=127.0.0.1',
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
