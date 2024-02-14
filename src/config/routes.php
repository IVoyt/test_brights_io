<?php

return [
    '/'             => [
        'GET' => 'default/index',
    ],
    '/add'          => [
        'POST' => 'default/add',
    ],
    '/sort'         => [
        'POST' => 'default/sort',
    ],
    '/(\d+)/delete' => [
        'DELETE' => 'default/delete',
    ]
];
