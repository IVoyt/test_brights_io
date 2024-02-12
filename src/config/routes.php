<?php

return [
    '/'             => [
        'GET' => 'default/index',
    ],
    '/add'          => [
        'GET' => 'default/add',
        'POST' => 'default/add',
    ],
    '/(\d+)/delete' => [
        'DELETE' => 'default/delete',
    ]
];
