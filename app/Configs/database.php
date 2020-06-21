<?php

return [
    'driver' => 'mysql',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
    'mysql' => [
        'host' => 'localhost',
        'database' => 'framework',
        'user' => 'root',
        'password' => ''
    ],

    'sqlite' => [
        'host' => 'database.db',
        'database' => 'storage/database/database.db'
    ]
];