<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Database Configuration for XAMPP & Production
 */

return [
    'default' => getenv('DB_CONNECTION') ?: 'mysql',
    
    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => getenv('DB_HOST') ?: '127.0.0.1',
            'port'      => getenv('DB_PORT') ?: '3306',
            'database'  => getenv('DB_DATABASE') ?: 'svpl_db',
            'username'  => getenv('DB_USERNAME') ?: 'root',
            'password'  => getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '', // XAMPP default is empty password
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'options'   => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
            ],
        ],
        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => dirname(__DIR__) . '/database/svpl_local.sqlite',
            'options'  => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        ],
    ],
];
