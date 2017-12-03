<?php

return [
    'settings' => [
        'displayErrorDetails' => true,
        'composedEmailReader' => [
            'dsn'      => 'mysql:host=localhost;dbname=emailSender;charset=UTF8',
            'username' => 'root',
            'password' => 'Tt54321',
            'options'  => [],
        ],
        'composedEmailWriter' => [
            'dsn'      => 'mysql:host=localhost;dbname=emailSender;charset=UTF8',
            'username' => 'root',
            'password' => 'Tt54321',
            'options'  => [],
        ],
        'emailLogReader' => [
            'dsn'      => 'mysql:host=localhost;dbname=emailSender;charset=UTF8',
            'username' => 'root',
            'password' => 'Tt54321',
            'options'  => [],
        ],
        'emailLogWriter' => [
            'dsn'      => 'mysql:host=localhost;dbname=emailSender;charset=UTF8',
            'username' => 'root',
            'password' => 'Tt54321',
            'options'  => [],
        ],
        'queue' => [
            'host'     => '127.0.0.1',
            'port'     => '5672',
            'username' => 'test',
            'password' => 'test',
            'exchange' => 'emailSender',
            'queue'    => 'emailSendQueue',
        ],
        'smtp' => [
            'host'     => 'localhost',
            'port'     => '25',
            'username' => 'gyviktor',
            'password' => 'Tt54321',
        ],
    ],
];
