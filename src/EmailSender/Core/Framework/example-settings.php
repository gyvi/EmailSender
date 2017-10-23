<?php
/**
 * @codeCoverageIgnore
 */
return [
    'settings' => [
        'composedEmailReader' => [
            'dsn'      => 'mysql:host=host;dbname=DB;charset=UTF8',
            'username' => 'user',
            'password' => 'pass',
            'options'  => [],
        ],
        'composedEmailWriter' => [
            'dsn'      => 'mysql:host=host;dbname=DB;charset=UTF8',
            'username' => 'user',
            'password' => 'pass',
            'options'  => [],
        ],
        'emailLogReader' => [
            'dsn'      => 'mysql:host=host;dbname=DB;charset=UTF8',
            'username' => 'user',
            'password' => 'pass',
            'options'  => [],
        ],
        'emailLogWriter' => [
            'dsn'      => 'mysql:host=host;dbname=DB;charset=UTF8',
            'username' => 'user',
            'password' => 'pass',
            'options'  => [],
        ],
        'queue' => [
            'host'     => '127.0.0.1',
            'port'     => '5672',
            'username' => 'username',
            'password' => 'password',
            'exchange' => 'emailSender',
            'queue'    => 'emailSendQueue',
        ],
        'smtp' => [
            'host'     => '127.0.0.1',
            'port'     => '25',
            'username' => 'username',
            'password' => 'password',
        ],
    ],
];
