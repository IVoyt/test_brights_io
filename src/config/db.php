<?php

return [
    'drivers' => [
        'mysql' => [
            'host'     => env('DB_HOST', 'localhost'),
            'port'     => env('DB_PORT'),
            'database' => env('DB_NAME'),
            'username' => env('DB_LOGIN'),
            'password' => env('DB_PASSWORD'),
        ]
    ],

    'default' => env('DB_CONNECTION', 'mysql')
];
