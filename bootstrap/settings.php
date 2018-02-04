<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'view' => [
            'template_path' => __DIR__ . '/../templates/',
            'cache_path' => '/var/tmp/cache',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : '/var/tmp/logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // database
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'sample_slim',
            'username' => 'slim_user',
            'password' => 'slim_pass',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
        // session
        'session' => [
            'cookie_name' => 'taka',
        ],
        // form
        'form' => [
            'csrf_timeout' => 7200,
        ],
    ],
];