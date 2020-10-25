<?php

use Taka512\Env;

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'view' => [
            'template_path' => __DIR__ . '/../../templates/',
            'cache_path' => false,
        ],

        // Monolog settings
        'logger' => [
            'path' => 'php://stdout',
            'level' => \Monolog\Logger::DEBUG,
        ],
        // database
        'db' => [
            'driver' => 'mysql',
            'host' => 'db.local',
            'database' => 'sample_slim_test',
            'username' => 'slim_user',
            'password' => ENV::getenv('DB_PASS'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
        // form
        'form' => [
            'csrf_timeout' => 7200,
        ],
        // test
        'test' => [
            'base_host' => 'http://web.local',
            'user' => [
                'login_id' => 'admin',
                'password' => '12345678',
            ],
        ],
    ],
];
