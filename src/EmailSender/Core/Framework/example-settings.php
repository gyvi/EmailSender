<?php
/**
 * @codeCoverageIgnore
 */
return [
    'settings' => [
        'messageStoreReader' => [
            'dsn'      => 'mysql:host=host;dbname=DB;charset=UTF8',
            'username' => 'user',
            'password' => 'pass',
            'options'  => [],
        ],
        'messageStoreWriter' => [
            'dsn'      => 'mysql:host=host;dbname=DB;charset=UTF8',
            'username' => 'user',
            'password' => 'pass',
            'options'  => [],
        ],
        'messageLogReader' => [
            'dsn'      => 'mysql:host=host;dbname=DB;charset=UTF8',
            'username' => 'user',
            'password' => 'pass',
            'options'  => [],
        ],
        'messageLogWriter' => [
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
        ],
    ],
];
