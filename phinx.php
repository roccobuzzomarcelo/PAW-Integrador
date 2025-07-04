<?php

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => getenv("DB_ADAPTER") ?? 'pgsql',
            'host' => getenv("DB_HOSTNAME")??'localhost',
            'name' => getenv("DB_NAME") ?? 'paw',
            'user' => getenv("DB_USERNAME") ?? 'admin',
            'pass' => getenv("DB_PASSWORD") ?? '',
            'port' => getenv("DB_PORT") ?? '5432',
            'charset' => getenv("DB_CHARSET") ?? 'utf8',
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'testing_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
